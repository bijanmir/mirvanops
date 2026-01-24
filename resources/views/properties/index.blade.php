<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Properties</h1>
            <p class="text-white/60">Manage your property portfolio</p>
        </div>
        <a href="{{ route('properties.create') }}" class="btn-primary mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 text-white font-medium rounded-xl">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
            </svg>
            Add Property
        </a>
    </div>

    <livewire:properties.property-list />
</x-app-layout>