<div class="max-w-3xl mx-auto">
    <a href="{{ route('maintenance.index') }}" class="inline-flex items-center text-muted hover:text-primary mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Maintenance
    </a>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-primary">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">{{ $request ? 'Edit Request' : 'New Maintenance Request' }}</h1>
            <p class="text-muted mt-1">{{ $request ? 'Update the maintenance request details' : 'Fill out the details for the repair request' }}</p>
        </div>

        <form wire:submit="save" class="p-4 sm:p-6 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Property *</label>
                    <select wire:model.live="property_id" class="input-field w-full px-4 py-3 rounded-xl">
                        <option value="">Select Property</option>
                        @foreach($properties as $property)
                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                        @endforeach
                    </select>
                    @error('property_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Unit *</label>
                    <select wire:model="unit_id" class="input-field w-full px-4 py-3 rounded-xl" @if(!$property_id) disabled @endif>
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}">Unit {{ $unit->unit_number }}</option>
                        @endforeach
                    </select>
                    @error('unit_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-secondary mb-2">Title *</label>
                <input wire:model="title" type="text" placeholder="e.g., Leaking faucet in bathroom" class="input-field w-full px-4 py-3 rounded-xl">
                @error('title') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-secondary mb-2">Description *</label>
                <textarea wire:model="description" rows="4" placeholder="Describe the issue in detail..." class="input-field w-full px-4 py-3 rounded-xl resize-none"></textarea>
                @error('description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-secondary mb-3">Category *</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @php
                        $categories = [
                            'plumbing' => ['icon' => '🚿', 'label' => 'Plumbing'],
                            'electrical' => ['icon' => '⚡', 'label' => 'Electrical'],
                            'hvac' => ['icon' => '❄️', 'label' => 'HVAC'],
                            'appliance' => ['icon' => '🔧', 'label' => 'Appliance'],
                            'structural' => ['icon' => '🏗️', 'label' => 'Structural'],
                            'pest' => ['icon' => '🐛', 'label' => 'Pest'],
                            'general' => ['icon' => '🔨', 'label' => 'General'],
                            'other' => ['icon' => '📝', 'label' => 'Other'],
                        ];
                    @endphp
                    @foreach($categories as $key => $cat)
                    <button type="button" wire:click="setCategory('{{ $key }}')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($category === $key) border-accent bg-accent/10 text-accent @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="text-lg">{{ $cat['icon'] }}</span>
                        <span class="block mt-1">{{ $cat['label'] }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-secondary mb-3">Priority *</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button type="button" wire:click="setPriority('low')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($priority === 'low') border-green-500 bg-green-500/10 text-green-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>Low</span>
                    </button>
                    <button type="button" wire:click="setPriority('medium')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($priority === 'medium') border-amber-500 bg-amber-500/10 text-amber-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>Medium</span>
                    </button>
                    <button type="button" wire:click="setPriority('high')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($priority === 'high') border-orange-500 bg-orange-500/10 text-orange-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-orange-500 mr-2"></span>High</span>
                    </button>
                    <button type="button" wire:click="setPriority('emergency')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($priority === 'emergency') border-red-500 bg-red-500/10 text-red-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>Emergency</span>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-secondary mb-2">Internal Notes</label>
                <textarea wire:model="notes" rows="2" placeholder="Any internal notes..." class="input-field w-full px-4 py-3 rounded-xl resize-none"></textarea>
            </div>

            <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4">
                <a href="{{ route('maintenance.index') }}" class="btn-secondary flex-1 px-6 py-3 rounded-xl font-medium text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1 px-6 py-3 rounded-xl font-medium flex items-center justify-center">
                    <span wire:loading.remove wire:target="save">{{ $request ? 'Update Request' : 'Create Request' }}</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>
