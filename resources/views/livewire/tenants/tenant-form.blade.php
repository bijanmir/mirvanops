<div class="max-w-3xl mx-auto">
    <a href="{{ route('tenants.index') }}" class="inline-flex items-center text-muted hover:text-primary mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Tenants
    </a>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-primary">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">{{ $tenant ? 'Edit Tenant' : 'Add Tenant' }}</h1>
            <p class="text-muted mt-1">{{ $tenant ? 'Update tenant information' : 'Add a new tenant to your properties' }}</p>
        </div>

        <form wire:submit="save" class="p-4 sm:p-6 space-y-6">
            <!-- Basic Info -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">First Name *</label>
                        <input wire:model="first_name" type="text" placeholder="John" class="input-field w-full px-4 py-3 rounded-xl">
                        @error('first_name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Last Name *</label>
                        <input wire:model="last_name" type="text" placeholder="Doe" class="input-field w-full px-4 py-3 rounded-xl">
                        @error('last_name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Email</label>
                        <input wire:model="email" type="email" placeholder="john@example.com" class="input-field w-full px-4 py-3 rounded-xl">
                        @error('email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Phone</label>
                        <input wire:model="phone" type="tel" placeholder="(555) 123-4567" class="input-field w-full px-4 py-3 rounded-xl">
                        @error('phone') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Alternate Phone</label>
                        <input wire:model="alternate_phone" type="tel" placeholder="(555) 987-6543" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Date of Birth</label>
                        <input wire:model="date_of_birth" type="date" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                </div>
            </div>

            <!-- Identification -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Identification</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">SSN (Last 4 digits)</label>
                        <input wire:model="ssn_last_four" type="text" maxlength="4" placeholder="1234" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Driver's License</label>
                        <input wire:model="drivers_license" type="text" placeholder="D1234567" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Emergency Contact</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Contact Name</label>
                        <input wire:model="emergency_contact_name" type="text" placeholder="Jane Doe" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Contact Phone</label>
                        <input wire:model="emergency_contact_phone" type="tel" placeholder="(555) 111-2222" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                </div>
            </div>

            <!-- Employment -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Employment</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Employer</label>
                        <input wire:model="employer" type="text" placeholder="ABC Company" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Employer Phone</label>
                        <input wire:model="employer_phone" type="tel" placeholder="(555) 333-4444" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Annual Income</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted">$</span>
                            <input wire:model="annual_income" type="number" step="0.01" min="0" placeholder="50000" class="input-field w-full pl-8 pr-4 py-3 rounded-xl">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Status</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button type="button" wire:click="setStatus('pending')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'pending') border-amber-500 bg-amber-500/10 text-amber-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>Pending</span>
                    </button>
                    <button type="button" wire:click="setStatus('active')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'active') border-green-500 bg-green-500/10 text-green-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>Active</span>
                    </button>
                    <button type="button" wire:click="setStatus('past')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'past') border-gray-500 bg-gray-500/10 text-gray-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-gray-500 mr-2"></span>Past</span>
                    </button>
                    <button type="button" wire:click="setStatus('evicted')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'evicted') border-red-500 bg-red-500/10 text-red-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>Evicted</span>
                    </button>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-secondary mb-2">Notes</label>
                <textarea wire:model="notes" rows="3" placeholder="Any additional notes about this tenant..." class="input-field w-full px-4 py-3 rounded-xl resize-none"></textarea>
            </div>

            <!-- Actions -->
            <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4">
                <a href="{{ route('tenants.index') }}" class="btn-secondary flex-1 px-6 py-3 rounded-xl font-medium text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1 px-6 py-3 rounded-xl font-medium flex items-center justify-center">
                    <span wire:loading.remove wire:target="save">{{ $tenant ? 'Update Tenant' : 'Add Tenant' }}</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>