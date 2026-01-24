<div class="max-w-3xl mx-auto">
    <a href="{{ route('vendors.index') }}" class="inline-flex items-center text-muted hover:text-primary mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Vendors
    </a>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-primary">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">{{ $vendor ? 'Edit Vendor' : 'Add Vendor' }}</h1>
            <p class="text-muted mt-1">{{ $vendor ? 'Update vendor information' : 'Add a new contractor or service provider' }}</p>
        </div>

        <form wire:submit="save" class="p-4 sm:p-6 space-y-6">
            <!-- Basic Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-secondary mb-2">Company/Name *</label>
                    <input wire:model="name" type="text" placeholder="e.g., ABC Plumbing" class="input-field w-full px-4 py-3 rounded-xl">
                    @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Email</label>
                    <input wire:model="email" type="email" placeholder="vendor@example.com" class="input-field w-full px-4 py-3 rounded-xl">
                    @error('email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Phone</label>
                    <input wire:model="phone" type="tel" placeholder="(555) 123-4567" class="input-field w-full px-4 py-3 rounded-xl">
                    @error('phone') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Specialty -->
            <div>
                <label class="block text-sm font-medium text-secondary mb-3">Specialty *</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach($specialtyOptions as $option)
                    <button type="button" wire:click="setSpecialty('{{ $option }}')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($specialty === $option) border-accent bg-accent/10 text-accent @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        {{ $option }}
                    </button>
                    @endforeach
                </div>
                @error('specialty') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Hourly Rate -->
            <div>
                <label class="block text-sm font-medium text-secondary mb-2">Hourly Rate</label>
                <div class="relative max-w-xs">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted">$</span>
                    <input wire:model="hourly_rate" type="number" step="0.01" min="0" placeholder="75.00" class="input-field w-full pl-8 pr-16 py-3 rounded-xl">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-muted">/hr</span>
                </div>
            </div>

            <!-- Address -->
            <div>
                <label class="block text-sm font-medium text-secondary mb-2">Address</label>
                <input wire:model="address" type="text" placeholder="123 Main St" class="input-field w-full px-4 py-3 rounded-xl mb-3">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2">
                        <input wire:model="city" type="text" placeholder="City" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                    <div>
                        <input wire:model="state" type="text" placeholder="State" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                    <div>
                        <input wire:model="zip" type="text" placeholder="ZIP" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-secondary mb-2">Notes</label>
                <textarea wire:model="notes" rows="3" placeholder="Any additional notes about this vendor..." class="input-field w-full px-4 py-3 rounded-xl resize-none"></textarea>
            </div>

            <!-- Status -->
            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input wire:model="is_active" type="checkbox" class="w-5 h-5 rounded border-primary text-accent focus:ring-accent">
                    <span class="text-sm font-medium text-secondary">Active vendor (available for assignment)</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4">
                <a href="{{ route('vendors.index') }}" class="btn-secondary flex-1 px-6 py-3 rounded-xl font-medium text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1 px-6 py-3 rounded-xl font-medium flex items-center justify-center">
                    <span wire:loading.remove wire:target="save">{{ $vendor ? 'Update Vendor' : 'Add Vendor' }}</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>