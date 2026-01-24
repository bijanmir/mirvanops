<div class="max-w-2xl mx-auto">
    <!-- Back Link -->
    <a href="{{ route('properties.index') }}" class="inline-flex items-center text-white/60 hover:text-white mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Properties
    </a>

    <!-- Form Card -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-white/10">
            <h1 class="text-2xl font-bold text-white">{{ $property ? 'Edit Property' : 'Add New Property' }}</h1>
            <p class="text-white/60 mt-1">{{ $property ? 'Update property information' : 'Enter the property details below' }}</p>
        </div>

        <form wire:submit="save" class="p-6 space-y-6">
            <!-- Property Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-white/80 mb-2">Property Name *</label>
                <input 
                    wire:model="name" 
                    type="text" 
                    id="name"
                    placeholder="e.g., Sunset Apartments"
                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-amber-500/50 focus:bg-white/10 transition-all"
                >
                @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-white/80 mb-2">Street Address *</label>
                <input 
                    wire:model="address" 
                    type="text" 
                    id="address"
                    placeholder="e.g., 123 Main Street"
                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-amber-500/50 focus:bg-white/10 transition-all"
                >
                @error('address') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <!-- City, State, Zip -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-1">
                    <label for="city" class="block text-sm font-medium text-white/80 mb-2">City *</label>
                    <input 
                        wire:model="city" 
                        type="text" 
                        id="city"
                        placeholder="San Diego"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-amber-500/50 focus:bg-white/10 transition-all"
                    >
                    @error('city') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="state" class="block text-sm font-medium text-white/80 mb-2">State *</label>
                    <input 
                        wire:model="state" 
                        type="text" 
                        id="state"
                        placeholder="CA"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-amber-500/50 focus:bg-white/10 transition-all"
                    >
                    @error('state') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="zip" class="block text-sm font-medium text-white/80 mb-2">ZIP Code *</label>
                    <input 
                        wire:model="zip" 
                        type="text" 
                        id="zip"
                        placeholder="92101"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-amber-500/50 focus:bg-white/10 transition-all"
                    >
                    @error('zip') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Property Type -->
            <div>
                <label class="block text-sm font-medium text-white/80 mb-3">Property Type *</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="relative cursor-pointer">
                        <input wire:model="type" type="radio" value="residential" class="peer sr-only">
                        <div class="p-4 bg-white/5 border border-white/10 rounded-xl text-center peer-checked:border-amber-500/50 peer-checked:bg-amber-500/10 transition-all">
                            <div class="w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-white">Residential</p>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input wire:model="type" type="radio" value="commercial" class="peer sr-only">
                        <div class="p-4 bg-white/5 border border-white/10 rounded-xl text-center peer-checked:border-amber-500/50 peer-checked:bg-amber-500/10 transition-all">
                            <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-white">Commercial</p>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input wire:model="type" type="radio" value="mixed" class="peer sr-only">
                        <div class="p-4 bg-white/5 border border-white/10 rounded-xl text-center peer-checked:border-amber-500/50 peer-checked:bg-amber-500/10 transition-all">
                            <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-white">Mixed Use</p>
                        </div>
                    </label>
                </div>
                @error('type') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-white/80 mb-2">Notes (Optional)</label>
                <textarea 
                    wire:model="notes" 
                    id="notes"
                    rows="3"
                    placeholder="Any additional notes about this property..."
                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/40 focus:outline-none focus:border-amber-500/50 focus:bg-white/10 transition-all resize-none"
                ></textarea>
            </div>

            <!-- Actions -->
            <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4">
                <a href="{{ route('properties.index') }}" class="flex-1 px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl text-white font-medium text-center transition-all">
                    Cancel
                </a>
                <button type="submit" class="flex-1 btn-primary px-6 py-3 text-white font-medium rounded-xl flex items-center justify-center">
                    <svg wire:loading wire:target="save" class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">{{ $property ? 'Update Property' : 'Create Property' }}</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>