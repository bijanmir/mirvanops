<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Lease;
use App\Models\ActivityLog;
use Carbon\Carbon;

class PaymentList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $methodFilter = '';
    public $propertyFilter = '';
    public $monthFilter = '';
    
    public $showDeleteModal = false;
    public $paymentToDelete = null;

    public function mount()
    {
        $this->monthFilter = now()->format('Y-m');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($paymentId)
    {
        $this->paymentToDelete = $paymentId;
        $this->showDeleteModal = true;
    }

    public function deletePayment()
    {
        $payment = Payment::where('company_id', auth()->user()->company_id)
            ->findOrFail($this->paymentToDelete);
        
        ActivityLog::log('deleted', $payment);
        $payment->delete();
        
        $this->showDeleteModal = false;
        $this->paymentToDelete = null;
        
        session()->flash('success', 'Payment deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->paymentToDelete = null;
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;

        $payments = Payment::where('company_id', $companyId)
            ->with(['lease.unit.property', 'tenant', 'recordedBy'])
            ->when($this->search, function ($query) {
                $query->whereHas('tenant', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('reference_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->methodFilter, fn($q) => $q->where('payment_method', $this->methodFilter))
            ->when($this->propertyFilter, function ($query) {
                $query->whereHas('lease.unit', function ($q) {
                    $q->where('property_id', $this->propertyFilter);
                });
            })
            ->when($this->monthFilter, function ($query) {
                $startOfMonth = Carbon::parse($this->monthFilter . '-01')->startOfMonth();
                $endOfMonth = Carbon::parse($this->monthFilter . '-01')->endOfMonth();
                $query->whereBetween('payment_date', [$startOfMonth, $endOfMonth]);
            })
            ->latest('payment_date')
            ->paginate(15);

        $statsQuery = Payment::where('company_id', $companyId);
        if ($this->monthFilter) {
            $startOfMonth = Carbon::parse($this->monthFilter . '-01')->startOfMonth();
            $endOfMonth = Carbon::parse($this->monthFilter . '-01')->endOfMonth();
            $statsQuery->whereBetween('payment_date', [$startOfMonth, $endOfMonth]);
        }

        $stats = [
            'total_collected' => (clone $statsQuery)->where('status', 'completed')->sum('amount'),
            'total_late_fees' => (clone $statsQuery)->where('status', 'completed')->sum('late_fee'),
            'payment_count' => (clone $statsQuery)->where('status', 'completed')->count(),
            'pending_count' => (clone $statsQuery)->where('status', 'pending')->count(),
        ];

        $expectedRent = Lease::where('company_id', $companyId)
            ->where('status', 'active')
            ->sum('rent_amount');

        $stats['expected_rent'] = $expectedRent;
        $stats['collection_rate'] = $expectedRent > 0 
            ? round(($stats['total_collected'] / $expectedRent) * 100, 1) 
            : 0;

        if ($this->monthFilter) {
            $paidLeaseIds = Payment::where('company_id', $companyId)
                ->where('status', 'completed')
                ->where('period_covered', Carbon::parse($this->monthFilter . '-01')->format('F Y'))
                ->pluck('lease_id');
            
            $stats['outstanding_count'] = Lease::where('company_id', $companyId)
                ->where('status', 'active')
                ->whereNotIn('id', $paidLeaseIds)
                ->count();
        } else {
            $stats['outstanding_count'] = 0;
        }

        $properties = Property::where('company_id', $companyId)->orderBy('name')->get();

        return view('livewire.payments.payment-list', [
            'payments' => $payments,
            'stats' => $stats,
            'properties' => $properties,
            'paymentMethods' => Payment::PAYMENT_METHODS,
        ]);
    }
}