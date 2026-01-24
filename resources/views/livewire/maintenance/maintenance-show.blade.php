<div>
    <a href="{{ route('maintenance.index') }}" class="inline-flex items-center text-muted hover:text-primary mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Maintenance
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Header -->
            <div class="glass-card rounded-2xl p-4 sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0
                            @if($request->priority === 'emergency') bg-red-500/15 text-red-500
                            @elseif($request->priority === 'high') bg-orange-500/15 text-orange-500
                            @elseif($request->priority === 'medium') bg-amber-500/15 text-amber-500
                            @else bg-green-500/15 text-green-500 @endif">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-primary mb-2">{{ $request->title }}</h1>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full uppercase
                                    @if($request->priority === 'emergency') bg-red-500/15 text-red-500
                                    @elseif($request->priority === 'high') bg-orange-500/15 text-orange-500
                                    @elseif($request->priority === 'medium') bg-amber-500/15 text-amber-500
                                    @else bg-green-500/15 text-green-500 @endif">
                                    {{ $request->priority }}
                                </span>
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-input text-secondary">{{ ucfirst($request->category) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-secondary mb-6">{{ $request->description }}</p>
                <div class="flex flex-wrap gap-4 text-sm text-muted pt-4 border-t border-primary">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        {{ $request->unit->property->name }} - Unit {{ $request->unit->unit_number }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Created {{ $request->created_at->format('M d, Y') }}
                    </span>
                </div>
            </div>

            <!-- Activity / Comments -->
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-primary">
                    <h2 class="text-lg font-semibold text-primary">Activity</h2>
                </div>

                <!-- Add Comment -->
                <div class="p-4 sm:p-6 border-b border-primary">
                    <form wire:submit="addComment" class="flex gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-semibold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <textarea wire:model="newComment" rows="2" placeholder="Add a comment..." class="input-field w-full px-4 py-3 rounded-xl resize-none text-sm"></textarea>
                            @error('newComment') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            <div class="flex justify-end mt-2">
                                <button type="submit" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium">
                                    <span wire:loading.remove wire:target="addComment">Add Comment</span>
                                    <span wire:loading wire:target="addComment">Adding...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Comments List -->
                <div class="divide-y divide-primary">
                    @forelse($request->comments->sortByDesc('created_at') as $comment)
                    <div class="p-4 sm:p-6 @if($comment->is_system) bg-input/50 @endif">
                        <div class="flex gap-3">
                            <div class="w-10 h-10 rounded-full @if($comment->is_system) bg-input @else bg-gradient-to-br from-indigo-500 to-purple-600 @endif flex items-center justify-center flex-shrink-0">
                                @if($comment->is_system)
                                <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                @else
                                <span class="text-sm font-semibold text-white">{{ substr($comment->user->name ?? 'U', 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-medium text-primary text-sm">{{ $comment->is_system ? 'System' : ($comment->user->name ?? 'Unknown') }}</span>
                                        <span class="text-xs text-muted">{{ $comment->created_at->diffForHumans() }}</span>
                                        @if($comment->edited_at)
                                        <span class="inline-flex items-center px-1.5 py-0.5 text-xs text-muted bg-input rounded" title="Edited {{ $comment->edited_at->diffForHumans() }}">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            edited
                                        </span>
                                        @endif
                                    </div>
                                    
                                    @if(!$comment->is_system && $comment->user_id === auth()->id())
                                    <div class="flex items-center gap-1">
                                        <button wire:click="startEditComment({{ $comment->id }})" class="p-1.5 text-muted hover:text-primary hover:bg-input rounded transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDeleteComment({{ $comment->id }})" class="p-1.5 text-muted hover:text-red-500 hover:bg-red-500/10 rounded transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                
                                @if($editingCommentId === $comment->id)
                                <!-- Edit Mode -->
                                <div class="mt-2">
                                    <textarea wire:model="editCommentBody" rows="2" class="input-field w-full px-3 py-2 rounded-lg resize-none text-sm"></textarea>
                                    @error('editCommentBody') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    <div class="flex items-center gap-2 mt-2">
                                        <button wire:click="saveEditComment" class="btn-primary px-3 py-1.5 rounded-lg text-xs font-medium">
                                            <span wire:loading.remove wire:target="saveEditComment">Save</span>
                                            <span wire:loading wire:target="saveEditComment">Saving...</span>
                                        </button>
                                        <button wire:click="cancelEditComment" class="btn-secondary px-3 py-1.5 rounded-lg text-xs font-medium">Cancel</button>
                                    </div>
                                </div>
                                @else
                                <!-- View Mode -->
                                <p class="text-secondary text-sm @if($comment->is_system) italic @endif">{{ $comment->body }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center">
                        <p class="text-muted text-sm">No activity yet. Add a comment to get started.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="glass-card rounded-2xl p-4 sm:p-6">
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Status</h3>
                <span class="px-3 py-1.5 text-sm font-semibold rounded-full
                    @if($request->status === 'new') bg-blue-500/15 text-blue-500
                    @elseif($request->status === 'assigned') bg-purple-500/15 text-purple-500
                    @elseif($request->status === 'in_progress') bg-amber-500/15 text-amber-500
                    @elseif($request->status === 'on_hold') bg-gray-500/15 text-gray-500
                    @elseif($request->status === 'completed') bg-green-500/15 text-green-500
                    @else bg-red-500/15 text-red-500 @endif">
                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                </span>
                <div class="grid grid-cols-2 gap-2 mt-4">
                    @if($request->status !== 'in_progress')
                    <button wire:click="quickStatus('in_progress')" class="p-2 text-xs font-medium rounded-lg bg-amber-500/10 text-amber-500 hover:bg-amber-500/20 transition-colors">Start Work</button>
                    @endif
                    @if($request->status !== 'completed')
                    <button wire:click="quickStatus('completed')" class="p-2 text-xs font-medium rounded-lg bg-green-500/10 text-green-500 hover:bg-green-500/20 transition-colors">Complete</button>
                    @endif
                    @if($request->status !== 'on_hold')
                    <button wire:click="quickStatus('on_hold')" class="p-2 text-xs font-medium rounded-lg bg-gray-500/10 text-gray-500 hover:bg-gray-500/20 transition-colors">On Hold</button>
                    @endif
                </div>
            </div>

            <!-- Vendor Assignment -->
            <div class="glass-card rounded-2xl p-4 sm:p-6">
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Assigned Vendor</h3>
                @if($request->vendor)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-input">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                        <span class="text-sm font-semibold text-white">{{ substr($request->vendor->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-primary text-sm truncate">{{ $request->vendor->name }}</p>
                        <p class="text-xs text-muted truncate">{{ $request->vendor->phone }}</p>
                    </div>
                </div>
                @else
                <p class="text-muted text-sm mb-3">No vendor assigned</p>
                @endif
                <button wire:click="openAssignModal" class="w-full btn-secondary px-4 py-2 rounded-lg text-sm font-medium mt-3">{{ $request->vendor ? 'Change Vendor' : 'Assign Vendor' }}</button>
            </div>

            <!-- Request Details -->
            <div class="glass-card rounded-2xl p-4 sm:p-6">
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Details</h3>
                <div class="space-y-4 text-sm">
                    @if($request->scheduled_date)
                    <div>
                        <p class="text-xs text-muted">Scheduled Date</p>
                        <p class="font-medium text-primary">{{ $request->scheduled_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                    @if($request->estimated_cost)
                    <div>
                        <p class="text-xs text-muted">Estimated Cost</p>
                        <p class="font-medium text-primary">${{ number_format($request->estimated_cost, 2) }}</p>
                    </div>
                    @endif
                    @if($request->notes)
                    <div>
                        <p class="text-xs text-muted">Internal Notes</p>
                        <p class="text-secondary">{{ $request->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Vendor Modal -->
    @if($showAssignModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="$set('showAssignModal', false)"></div>
        <div class="relative glass-card rounded-2xl p-6 w-full max-w-md animate-fade-in">
            <h3 class="text-xl font-semibold text-primary mb-6">Assign Vendor</h3>
            <select wire:model="selectedVendor" class="input-field w-full px-4 py-3 rounded-xl mb-6">
                <option value="">No Vendor</option>
                @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ $vendor->specialty }})</option>
                @endforeach
            </select>
            <div class="flex gap-3">
                <button wire:click="$set('showAssignModal', false)" class="flex-1 btn-secondary px-4 py-3 rounded-xl font-medium">Cancel</button>
                <button wire:click="assignVendor" class="flex-1 btn-primary px-4 py-3 rounded-xl font-medium">Assign</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Comment Modal -->
    @if($showDeleteCommentModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="cancelDeleteComment"></div>
        <div class="relative glass-card rounded-2xl p-6 w-full max-w-md animate-fade-in">
            <div class="w-14 h-14 rounded-full bg-red-500/15 text-red-500 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-primary text-center mb-2">Delete Comment?</h3>
            <p class="text-muted text-center mb-6">This action cannot be undone.</p>
            <div class="flex gap-3">
                <button wire:click="cancelDeleteComment" class="flex-1 btn-secondary px-4 py-3 rounded-xl font-medium">Cancel</button>
                <button wire:click="deleteComment" class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 rounded-xl text-white font-medium transition-all">Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>