<div class="max-w-2xl mx-auto">
        <!-- Debug: Current type is: {{ $type }} -->

    <!-- Back Link -->
    <a href="{{ route('properties.index') }}"
        class="inline-flex items-center text-muted hover:text-primary mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Properties
    </a>

    <!-- Form Card -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-primary">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">
                {{ $property ? 'Edit Property' : 'Add New Property' }}
            </h1>
            <p class="text-muted mt-1">
                {{ $property ? 'Update property information' : 'Enter the property details below' }}
            </p>
        </div>

        <form wire:submit="save" class="p-4 sm:p-6 space-y-6">
            <!-- Property Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-secondary mb-2">Property Name *</label>
                <input wire:model="name" type="text" id="name" placeholder="e.g., Sunset Apartments"
                    class="input-field w-full px-4 py-3 rounded-xl">
                @error('name') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-secondary mb-2">Street Address *</label>
                <input wire:model="address" type="text" id="address" placeholder="e.g., 123 Main Street"
                    class="input-field w-full px-4 py-3 rounded-xl">
                @error('address') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- City, State, Zip -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-1">
                    <label for="city" class="block text-sm font-medium text-secondary mb-2">City *</label>
                    <input wire:model="city" type="text" id="city" placeholder="San Diego"
                        class="input-field w-full px-4 py-3 rounded-xl">
                    @error('city') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="state" class="block text-sm font-medium text-secondary mb-2">State *</label>
                    <input wire:model="state" type="text" id="state" placeholder="CA"
                        class="input-field w-full px-4 py-3 rounded-xl">
                    @error('state') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="zip" class="block text-sm font-medium text-secondary mb-2">ZIP Code *</label>
                    <input wire:model="zip" type="text" id="zip" placeholder="92101"
                        class="input-field w-full px-4 py-3 rounded-xl">
                    @error('zip') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Property Type -->
            <div>
                <label class="block text-sm font-medium text-secondary mb-3">Property Type *</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Residential -->
                    <button type="button" wire:click="setType('residential')"
                        class="relative p-4 rounded-xl text-center transition-all border-2 @if($type === 'residential') border-emerald-500 bg-emerald-500/10 @else bg-input border-primary hover:border-secondary @endif">
                        <div
                            class="w-10 h-10 rounded-lg bg-emerald-500/20 text-emerald-500 flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-primary">Residential</p>
                        @if($type === 'residential')
                            <div
                                class="absolute top-2 right-2 w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        @endif
                    </button>

                    <!-- Commercial -->
                    <button type="button" wire:click="setType('commercial')"
                        class="relative p-4 rounded-xl text-center transition-all border-2 @if($type === 'commercial') border-blue-500 bg-blue-500/10 @else bg-input border-primary hover:border-secondary @endif">
                        <div
                            class="w-10 h-10 rounded-lg bg-blue-500/20 text-blue-500 flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-primary">Commercial</p>
                        @if($type === 'commercial')
                            <div
                                class="absolute top-2 right-2 w-5 h-5 rounded-full bg-blue-500 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        @endif
                    </button>

                    <!-- Mixed Use -->
                    <button type="button" wire:click="setType('mixed')"
                        class="relative p-4 rounded-xl text-center transition-all border-2 @if($type === 'mixed') border-purple-500 bg-purple-500/10 @else bg-input border-primary hover:border-secondary @endif">
                        <div
                            class="w-10 h-10 rounded-lg bg-purple-500/20 text-purple-500 flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-primary">Mixed Use</p>
                        @if($type === 'mixed')
                            <div
                                class="absolute top-2 right-2 w-5 h-5 rounded-full bg-purple-500 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        @endif
                    </button>
                </div>
                @error('type') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-secondary mb-2">Notes (Optional)</label>
                <textarea wire:model="notes" id="notes" rows="3"
                    placeholder="Any additional notes about this property..."
                    class="input-field w-full px-4 py-3 rounded-xl resize-none"></textarea>
            </div>

            <!-- Actions -->
            <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4">
                <a href="{{ route('properties.index') }}"
                    class="btn-secondary flex-1 px-6 py-3 rounded-xl font-medium text-center">
                    Cancel
                </a>
                <button type="submit"
                    class="btn-primary flex-1 px-6 py-3 rounded-xl font-medium flex items-center justify-center">
                    <svg wire:loading wire:target="save" class="w-5 h-5 mr-2 animate-spin" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span wire:loading.remove
                        wire:target="save">{{ $property ? 'Update Property' : 'Create Property' }}</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>