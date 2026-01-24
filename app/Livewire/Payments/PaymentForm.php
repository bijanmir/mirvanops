<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Lease;
use App\Models\ActivityLog;
use Carbon\Carbon;

class PaymentForm extends Component
{
    public ?Payment $payment = null;
    
    public $lease_id = '';
    public $amount = '';
    public $payment_date = '';
    public $payment_method = 'ach';
    public $period_covered = '';
    public $reference_number = '';
    public $late_fee = '';
    public $status = 'completed';
    public $notes = '';
    
    public $selectedLease = null;

    public function mount($paymentId = null)
    {
        $this->payment_date = now()->format('Y-m-d');
        $this->period_covered = now()->format('F Y');
        
        if ($paymentId) {
            $this->payment = Payment::where('company_id', auth()->user()->company_id)
                ->with('lease')
                ->findOrFail($paymentId);
            
            $this->lease_id = $this->payment->lease_id;
            $this->amount = $this->payment->amount;
            $this->payment_date = $this->payment->payment_date->format('Y-m-d');
            $this->payment_method = $this->payment->payment_method;
            $this->period_covered = $this->payment->period_covered;
            $this->reference_number = $this->payment->reference_number ?? '';
            $this->late_fee = $this->payment->late_fee ?? '';
            $this->status = $this->payment->status;
            $this->notes = $this->payment->notes ?? '';
            
            $this->loadLeaseDetails();
        } else {
            $leaseId = request()->query('lease_id');
            if ($leaseId) {
                $lease = Lease::where('company_id', auth()->user()->company_id)
                    ->with(['tenant', 'unit.property'])
                    ->find($leaseId);
                
                if ($lease) {
                    $this->lease_id = $lease->id;
                    $this->amount = $lease->rent_amount + ($lease->pet_rent ?? 0);
                    $this->loadLeaseDetails();
                }
            }
        }
    }

    public function updatedLeaseId()
    {
        $this->loadLeaseDetails();
    }

    public function loadLeaseDetails()
    {
        if ($this->lease_id) {
            $this->selectedLease = Lease::where('company_id', auth()->user()->company_id)
                ->with(['tenant', 'unit.property'])
                ->find($this->lease_id);
            
            if ($this->selectedLease && !$this->amount) {
                $this->amount = $this->selectedLease->rent_amount + ($this->selectedLease->pet_rent ?? 0);
            }
        } else {
            $this->selectedLease = null;
        }
    }

    public function useFullRent()
    {
        if ($this->selectedLease) {
            $this->amount = $this->selectedLease->rent_amount + ($this->selectedLease->pet_rent ?? 0);
        }
    }

    public function setPaymentMethod($method)
    {
        $this->payment_method = $method;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function save()
    {
        $this->validate([
            'lease_id' => 'required|exists:leases,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:ach,check,cash,money_order,zelle,venmo,card,other',
            'period_covered' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'late_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:completed,pending,failed,refunded',
        ]);

        $lease = Lease::findOrFail($this->lease_id);

        $data = [
            'company_id' => auth()->user()->company_id,
            'lease_id' => $this->lease_id,
            'tenant_id' => $lease->tenant_id,
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
            'payment_method' => $this->payment_method,
            'period_covered' => $this->period_covered,
            'reference_number' => $this->reference_number ?: null,
            'late_fee' => $this->late_fee ?: null,
            'status' => $this->status,
            'notes' => $this->notes ?: null,
            'recorded_by' => auth()->id(),
        ];

        if ($this->payment) {
            $this->payment->update($data);
            ActivityLog::log('updated', $this->payment);
            session()->flash('success', 'Payment updated successfully.');
        } else {
            $payment = Payment::create($data);
            ActivityLog::log('created', $payment);
            session()->flash('success', 'Payment recorded successfully.');
        }

        return redirect()->route('payments.index');
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;
        
        $leases = Lease::where('company_id', $companyId)
            ->whereIn('status', ['active', 'pending'])
            ->with(['tenant', 'unit.property'])
            ->get()
            ->sortBy(function ($lease) {
                return $lease->tenant->last_name ?? '';
            });

        $periods = [];
        for ($i = -2; $i <= 12; $i++) {
            $date = now()->subMonths($i);
            $periods[$date->format('F Y')] = $date->format('F Y');
        }

        return view('livewire.payments.payment-form', [
            'leases' => $leases,
            'periods' => $periods,
            'paymentMethods' => Payment::PAYMENT_METHODS,
        ]);
    }
}