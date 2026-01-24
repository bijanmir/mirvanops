<?php

namespace App\Livewire\Maintenance;

use Livewire\Component;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceComment;
use App\Models\Vendor;
use App\Models\ActivityLog;

class MaintenanceShow extends Component
{
    public MaintenanceRequest $request;

    public $newComment = '';
    public $showAssignModal = false;
    public $selectedVendor = '';

    // Edit comment
    public $editingCommentId = null;
    public $editCommentBody = '';

    // Delete comment
    public $showDeleteCommentModal = false;
    public $commentToDelete = null;

    public function mount($requestId)
    {
        $this->request = MaintenanceRequest::where('company_id', auth()->user()->company_id)
            ->with(['unit.property', 'vendor', 'comments.user', 'assignedTo', 'reportedBy'])
            ->findOrFail($requestId);
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|max:1000',
        ]);

        MaintenanceComment::create([
            'maintenance_request_id' => $this->request->id,
            'user_id' => auth()->id(),
            'body' => $this->newComment,
        ]);

        $this->newComment = '';
        $this->refreshComments();

        session()->flash('success', 'Comment added successfully.');
    }

    public function startEditComment($commentId)
    {
        $comment = MaintenanceComment::find($commentId);

        if ($comment && $comment->canEdit()) {
            $this->editingCommentId = $commentId;
            $this->editCommentBody = $comment->body;
        }
    }

    public function cancelEditComment()
    {
        $this->editingCommentId = null;
        $this->editCommentBody = '';
    }

    public function saveEditComment()
    {
        $this->validate([
            'editCommentBody' => 'required|string|max:1000',
        ]);

        $comment = MaintenanceComment::find($this->editingCommentId);

        if ($comment && $comment->canEdit()) {
            $comment->update([
                'body' => $this->editCommentBody,
                'edited_at' => now(),
            ]);
        }

        $this->cancelEditComment();
        $this->refreshComments();

        session()->flash('success', 'Comment updated successfully.');
    }

    public function confirmDeleteComment($commentId)
    {
        $comment = MaintenanceComment::find($commentId);

        if ($comment && $comment->canDelete()) {
            $this->commentToDelete = $commentId;
            $this->showDeleteCommentModal = true;
        }
    }

    public function deleteComment()
    {
        $comment = MaintenanceComment::find($this->commentToDelete);

        if ($comment && $comment->canDelete()) {
            $comment->delete();
        }

        $this->showDeleteCommentModal = false;
        $this->commentToDelete = null;
        $this->refreshComments();

        session()->flash('success', 'Comment deleted successfully.');
    }

    public function cancelDeleteComment()
    {
        $this->showDeleteCommentModal = false;
        $this->commentToDelete = null;
    }

    public function quickStatus($status)
    {
        $oldStatus = $this->request->status;
        $this->request->update(['status' => $status]);

        $userName = auth()->user()->name;
        $oldStatusLabel = ucfirst(str_replace('_', ' ', $oldStatus));
        $newStatusLabel = ucfirst(str_replace('_', ' ', $status));

        MaintenanceComment::create([
            'maintenance_request_id' => $this->request->id,
            'user_id' => auth()->id(),
            'body' => "{$userName} changed status from {$oldStatusLabel} to {$newStatusLabel}",
            'is_system' => true,
        ]);

        ActivityLog::log('updated status', $this->request);
        $this->refreshComments();

        session()->flash('success', 'Status updated to ' . $newStatusLabel . '.');
    }

    public function openAssignModal()
    {
        $this->selectedVendor = $this->request->vendor_id ?? '';
        $this->showAssignModal = true;
    }

    public function assignVendor()
    {
        $this->request->update([
            'vendor_id' => $this->selectedVendor ?: null,
            'status' => $this->selectedVendor && $this->request->status === 'new' ? 'assigned' : $this->request->status,
        ]);

        $userName = auth()->user()->name;

        if ($this->selectedVendor) {
            $vendor = Vendor::find($this->selectedVendor);
            MaintenanceComment::create([
                'maintenance_request_id' => $this->request->id,
                'user_id' => auth()->id(),
                'body' => "{$userName} assigned this request to {$vendor->name}",
                'is_system' => true,
            ]);
        } else {
            MaintenanceComment::create([
                'maintenance_request_id' => $this->request->id,
                'user_id' => auth()->id(),
                'body' => "{$userName} removed the vendor assignment",
                'is_system' => true,
            ]);
        }

        ActivityLog::log('assigned vendor', $this->request);

        $this->showAssignModal = false;
        $this->request->refresh();
        $this->request->load(['vendor', 'comments.user']);

        session()->flash('success', 'Vendor updated successfully.');
    }

    private function refreshComments()
    {
        $this->request->refresh();
        $this->request->load(['comments.user']);
    }

    public function render()
    {
        return view('livewire.maintenance.maintenance-show', [
            'vendors' => Vendor::where('company_id', auth()->user()->company_id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }
}