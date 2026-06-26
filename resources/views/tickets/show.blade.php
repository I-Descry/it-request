<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('View Ticket') }} — {{ $ticket->ticket_no }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <div style="color: green; margin-bottom: 12px; font-weight: bold; font-size: 0.9rem;">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Restored Badge --}}
                    @if ($ticket->restored_from_archive)
                        <div style="background-color: #dbeafe; color: #1e40af; padding: 8px 12px; border-radius: 6px; margin-bottom: 15px; font-weight: bold; font-size: 0.85rem;">
                            ♻️ This ticket was restored from the Archived Tickets on {{ $ticket->restored_at ? $ticket->restored_at->format('M d, Y h:i A') : 'N/A' }}
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                        <a href="{{ route('tickets.index') }}" class="dk-btn dk-btn-secondary">
                            ← Back to Tickets
                        </a>
                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="dk-btn dk-btn-warning">
                            ✏️ Edit Ticket
                        </a>
                    </div>

                    {{-- Ticket Details --}}
                    <div class="dk-panel dk-grid-4">
                        <div><strong class="dk-text-label">Ticket No.:</strong> <br>{{ $ticket->ticket_no }}</div>
                        <div><strong class="dk-text-label">Status:</strong> <br>
                            @php
                                $statusColor = match($ticket->status) {
                                    'Open' => 'dk-badge-open',
                                    'In Progress' => 'dk-badge-progress',
                                    'Resolved' => 'dk-badge-resolved',
                                    'Closed' => 'dk-badge-closed',
                                    default => 'dk-badge-closed',
                                };
                            @endphp
                            <span class="dk-badge {{ $statusColor }}" style="margin-top: 2px;">
                                {{ $ticket->status }}
                            </span>
                        </div>
                        <div><strong class="dk-text-label">Request Type:</strong> <br>{{ $ticket->request_type }}</div>
                        <div><strong class="dk-text-label">Request:</strong> <br>{{ $ticket->request ?? '—' }}</div>
                        
                        <div><strong class="dk-text-label">Requested By:</strong> <br>{{ $ticket->requested_by }}</div>
                        <div><strong class="dk-text-label">Position:</strong> <br>{{ $ticket->position ?? 'N/A' }}</div>
                        <div><strong class="dk-text-label">Department:</strong> <br>{{ $ticket->department ?? 'N/A' }}</div>
                        <div><strong class="dk-text-label">Branch:</strong> <br>{{ $ticket->branch ?? 'N/A' }}</div>
                        
                        <div><strong class="dk-text-label">Affected System:</strong> <br>{{ $ticket->affected_system ?? 'N/A' }}</div>
                        <div><strong class="dk-text-label">Assisted By:</strong> <br>{{ $ticket->assisted_by ?? 'N/A' }}</div>
                        <div><strong class="dk-text-label">Date Created:</strong> <br>{{ $ticket->created_at->format('M d, Y h:i A') }}</div>
                    </div>

                    <div class="dk-panel dk-grid-2">
                        <div>
                            <strong class="dk-text-label">Details of the Issue:</strong>
                            <div style="margin-top: 4px; white-space: pre-wrap; background: var(--bg-input); padding: 8px; border: 1px solid var(--border-color); border-radius: 4px; min-height: 50px;">{{ $ticket->request_details }}</div>
                        </div>
                        <div>
                            <strong class="dk-text-label">Admin Remarks:</strong>
                            <div style="margin-top: 4px; white-space: pre-wrap; background: var(--bg-input); padding: 8px; border: 1px solid var(--border-color); border-radius: 4px; min-height: 50px;">{{ $ticket->remarks ?? 'N/A' }}</div>
                        </div>
                    </div>

                    {{-- Attachments Section --}}
                    <h3 style="font-size: 1.2rem; font-weight: bold; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                        📎 Attachments ({{ $ticket->attachments->count() }})
                    </h3>

                    @if ($ticket->attachments->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                            @foreach ($ticket->attachments as $attachment)
                                <div style="border: 1px solid var(--border-color); border-radius: 6px; padding: 8px; background-color: var(--panel-bg);">
                                    {{-- File Preview --}}
                                    @php
                                        $isImage = str_starts_with($attachment->file_type, 'image/');
                                        $isVideo = str_starts_with($attachment->file_type, 'video/');
                                        $isAudio = str_starts_with($attachment->file_type, 'audio/');
                                        $fileUrl = asset('storage/' . $attachment->file_path);
                                        $fileSizeMB = number_format($attachment->file_size / 1048576, 2);
                                    @endphp

                                    @if ($isImage)
                                        <div style="margin-bottom: 6px; text-align: center;">
                                            <img src="{{ $fileUrl }}" alt="{{ $attachment->file_name }}"
                                                 style="max-width: 100%; height: 100px; border-radius: 4px; object-fit: cover; cursor: pointer;"
                                                 onclick="window.open('{{ $fileUrl }}', '_blank')">
                                        </div>
                                    @elseif ($isVideo)
                                        <div style="margin-bottom: 6px;">
                                            <video controls style="width: 100%; height: 100px; border-radius: 4px; object-fit: cover;">
                                                <source src="{{ $fileUrl }}" type="{{ $attachment->file_type }}">
                                            </video>
                                        </div>
                                    @elseif ($isAudio)
                                        <div style="margin-bottom: 6px;">
                                            <audio controls style="width: 100%; height: 30px;">
                                                <source src="{{ $fileUrl }}" type="{{ $attachment->file_type }}">
                                            </audio>
                                        </div>
                                    @else
                                        <div style="margin-bottom: 6px; text-align: center; padding: 10px; background-color: var(--border-color); border-radius: 4px;">
                                            <span style="font-size: 1.5rem;">📄</span>
                                        </div>
                                    @endif

                                    <p style="font-weight: bold; font-size: 0.75rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-bottom: 2px;" data-tooltip="{{ $attachment->file_name }}">
                                        {{ $attachment->file_name }}
                                    </p>
                                    <p style="font-size: 0.7rem; color: var(--text-light); margin-bottom: 6px;">
                                        {{ strtoupper(pathinfo($attachment->file_name, PATHINFO_EXTENSION)) }} • {{ $fileSizeMB }} MB
                                    </p>

                                    <a href="{{ $fileUrl }}" download="{{ $attachment->file_name }}"
                                       style="display: inline-block; background-color: #2563eb; color: #fff; padding: 3px 8px; border-radius: 4px; text-decoration: none; font-size: 0.75rem;">
                                        ⬇️
                                    </a>
                                    <a href="{{ $fileUrl }}" target="_blank"
                                       style="display: inline-block; background-color: var(--text-light); color: #fff; padding: 3px 8px; border-radius: 4px; text-decoration: none; font-size: 0.75rem; margin-left: 2px;">
                                        🔗
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color: var(--text-light); font-style: italic;">No attachments for this ticket.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
