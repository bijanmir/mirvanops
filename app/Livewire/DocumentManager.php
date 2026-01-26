<?php

namespace App\Livewire;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentManager extends Component
{
    use WithFileUploads;

    public $documentableType;
    public $documentableId;
    public $documents = [];
    
    public $file;
    public $name = '';
    public $category = '';
    public $notes = '';
    public $showUploadForm = false;

    public function mount($documentableType, $documentableId)
    {
        $this->documentableType = $documentableType;
        $this->documentableId = $documentableId;
        $this->loadDocuments();
    }

    public function loadDocuments()
    {
        $this->documents = Document::where('documentable_type', $this->documentableType)
            ->where('documentable_id', $this->documentableId)
            ->where('company_id', auth()->user()->company_id)
            ->latest()
            ->get();
    }

    public function toggleUploadForm()
    {
        $this->showUploadForm = !$this->showUploadForm;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->file = null;
        $this->name = '';
        $this->category = '';
        $this->notes = '';
    }

    public function upload()
    {
        $this->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:50',
        ]);

        $originalFilename = $this->file->getClientOriginalName();
        $filename = time() . '_' . $this->file->hashName();
        $path = $this->file->storeAs('documents/' . auth()->user()->company_id, $filename, 'public');

        Document::create([
            'company_id' => auth()->user()->company_id,
            'uploaded_by' => auth()->id(),
            'documentable_type' => $this->documentableType,
            'documentable_id' => $this->documentableId,
            'name' => $this->name,
            'original_filename' => $originalFilename,
            'filename' => $filename,
            'path' => $path,
            'mime_type' => $this->file->getMimeType(),
            'size' => $this->file->getSize(),
            'category' => $this->category ?: null,
            'notes' => $this->notes ?: null,
        ]);

        $this->loadDocuments();
        $this->toggleUploadForm();
        
        session()->flash('message', 'Document uploaded successfully.');
    }

    public function download($documentId)
    {
        $document = Document::where('id', $documentId)
            ->where('company_id', auth()->user()->company_id)
            ->firstOrFail();

        return Storage::disk('public')->download($document->path, $document->original_filename);
    }

    public function delete($documentId)
    {
        $document = Document::where('id', $documentId)
            ->where('company_id', auth()->user()->company_id)
            ->firstOrFail();

        Storage::disk('public')->delete($document->path);
        $document->delete();

        $this->loadDocuments();
        
        session()->flash('message', 'Document deleted.');
    }

    public function render()
    {
        return view('livewire.document-manager');
    }
}