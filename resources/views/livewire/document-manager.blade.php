<div class="glass-card rounded-xl overflow-hidden">
    <div class="p-4 border-b border-primary flex items-center justify-between">
        <h3 class="text-lg font-semibold text-primary flex items-center">
            <span class="mr-2">📁</span> Documents
            <span class="ml-2 text-sm font-normal text-muted">({{ count($documents) }})</span>
        </h3>
        <button wire:click="toggleUploadForm" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center">
            @if($showUploadForm)
                <span>✕ Cancel</span>
            @else
                <span>+ Upload</span>
            @endif
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mx-4 mt-4 p-3 rounded-lg bg-green-500/15 border border-green-500/30 text-green-500 text-sm">
            {{ session('message') }}
        </div>
    @endif

    @if($showUploadForm)
        <div class="p-4 border-b border-primary bg-input/30">
            <form wire:submit="upload" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">File</label>
                    <input type="file" wire:model="file" class="input-field w-full px-4 py-2.5 rounded-xl text-sm">
                    @error('file') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    
                    <div wire:loading wire:target="file" class="text-sm text-muted mt-2">
                        Uploading...
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Document Name</label>
                        <input type="text" wire:model="name" class="input-field w-full px-4 py-2.5 rounded-xl text-sm" placeholder="e.g. Signed Lease Agreement">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Category</label>
                        <select wire:model="category" class="input-field w-full px-4 py-2.5 rounded-xl text-sm">
                            <option value="">Select category...</option>
                            <option value="lease">Lease Agreement</option>
                            <option value="id">ID / Verification</option>
                            <option value="insurance">Insurance</option>
                            <option value="inspection">Inspection</option>
                            <option value="financial">Financial</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Notes (optional)</label>
                    <textarea wire:model="notes" rows="2" class="input-field w-full px-4 py-2.5 rounded-xl text-sm" placeholder="Any additional notes..."></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-medium" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="upload">Upload Document</span>
                        <span wire:loading wire:target="upload">Uploading...</span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="divide-y divide-primary">
        @forelse($documents as $document)
            <div class="p-4 flex items-center justify-between hover:bg-input/30 transition-colors">
                <div class="flex items-center space-x-3 flex-1 min-w-0">
                    <span class="text-2xl">{{ $document->icon }}</span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-primary truncate">{{ $document->name }}</p>
                        <p class="text-xs text-muted">
                            {{ $document->size_for_humans }} • 
                            {{ $document->created_at->format('M d, Y') }}
                            @if($document->category)
                                • <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-accent/15 text-accent">{{ ucfirst($document->category) }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 ml-4">
                    <button wire:click="download({{ $document->id }})" class="p-2 text-muted hover:text-primary transition-colors" title="Download">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </button>
                    <button wire:click="delete({{ $document->id }})" wire:confirm="Are you sure you want to delete this document?" class="p-2 text-muted hover:text-red-500 transition-colors" title="Delete">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="p-8 text-center">
                <span class="text-4xl mb-3 block">📂</span>
                <p class="text-muted">No documents yet.</p>
                <p class="text-muted text-sm">Click "Upload" to add your first document.</p>
            </div>
        @endforelse
    </div>
</div>