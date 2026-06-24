<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Ticket') }} — {{ $ticket->ticket_no }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900">

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
                        <a href="{{ route('tickets.index') }}"
                           style="background-color: #6b7280; color: #fff; padding: 6px 14px; border-radius: 5px; text-decoration: none; font-size: 0.85rem;">
                            ← Back to Tickets
                        </a>
                        <a href="{{ route('tickets.edit', $ticket->id) }}"
                           style="background-color: #f59e0b; color: #fff; padding: 6px 14px; border-radius: 5px; text-decoration: none; font-size: 0.85rem;">
                            ✏️ Edit Ticket
                        </a>
                    </div>

                    {{-- Ticket Details --}}
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 15px; font-size: 0.85rem; border: 1px solid #e5e7eb; padding: 12px; border-radius: 6px; background-color: #f9fafb;">
                        <div><strong style="color: #4b5563;">Ticket No.:</strong> <br>{{ $ticket->ticket_no }}</div>
                        <div><strong style="color: #4b5563;">Status:</strong> <br>
                            @php
                                $statusColor = match($ticket->status) {
                                    'Open' => 'background-color: #fee2e2; color: #991b1b;',
                                    'In Progress' => 'background-color: #fef08a; color: #854d0e;',
                                    'Resolved' => 'background-color: #dcfce3; color: #166534;',
                                    'Closed' => 'background-color: #e5e7eb; color: #374151;',
                                    default => 'background-color: #e5e7eb; color: #374151;',
                                };
                            @endphp
                            <span style="padding: 2px 8px; border-radius: 9999px; font-size: 0.75rem; font-weight: bold; {{ $statusColor }} display: inline-block; margin-top: 2px;">
                                {{ $ticket->status }}
                            </span>
                        </div>
                        <div><strong style="color: #4b5563;">Request Type:</strong> <br>{{ $ticket->request_type }}</div>
                        
                        <div><strong style="color: #4b5563;">Requested By:</strong> <br>{{ $ticket->requested_by }}</div>
                        <div><strong style="color: #4b5563;">Position:</strong> <br>{{ $ticket->position ?? 'N/A' }}</div>
                        <div><strong style="color: #4b5563;">Department:</strong> <br>{{ $ticket->department ?? 'N/A' }}</div>
                        <div><strong style="color: #4b5563;">Branch:</strong> <br>{{ $ticket->branch ?? 'N/A' }}</div>
                        
                        <div><strong style="color: #4b5563;">Affected System:</strong> <br>{{ $ticket->affected_system ?? 'N/A' }}</div>
                        <div><strong style="color: #4b5563;">Assisted By:</strong> <br>{{ $ticket->assisted_by ?? 'N/A' }}</div>
                        <div><strong style="color: #4b5563;">Date Created:</strong> <br>{{ $ticket->created_at->format('M d, Y h:i A') }}</div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; font-size: 0.85rem; border: 1px solid #e5e7eb; padding: 12px; border-radius: 6px; background-color: #f9fafb;">
                        <div>
                            <strong style="color: #4b5563;">Details of the Issue:</strong>
                            <div style="margin-top: 4px; white-space: pre-wrap; background: #fff; padding: 8px; border: 1px solid #e5e7eb; border-radius: 4px; min-height: 50px;">{{ $ticket->request_details }}</div>
                        </div>
                        <div>
                            <strong style="color: #4b5563;">Admin Remarks:</strong>
                            <div style="margin-top: 4px; white-space: pre-wrap; background: #fff; padding: 8px; border: 1px solid #e5e7eb; border-radius: 4px; min-height: 50px;">{{ $ticket->remarks ?? 'N/A' }}</div>
                        </div>
                    </div>

                    {{-- Attachments Section --}}
                    <h3 style="font-size: 1.2rem; font-weight: bold; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                        📎 Attachments ({{ $ticket->attachments->count() }})
                    </h3>

                    @if ($ticket->attachments->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                            @foreach ($ticket->attachments as $attachment)
                                <div style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px; background-color: #f9fafb;">
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
                                        <div style="margin-bottom: 6px; text-align: center; padding: 10px; background-color: #e5e7eb; border-radius: 4px;">
                                            <span style="font-size: 1.5rem;">📄</span>
                                        </div>
                                    @endif

                                    <p style="font-weight: bold; font-size: 0.75rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-bottom: 2px;" title="{{ $attachment->file_name }}">
                                        {{ $attachment->file_name }}
                                    </p>
                                    <p style="font-size: 0.7rem; color: #6b7280; margin-bottom: 6px;">
                                        {{ strtoupper(pathinfo($attachment->file_name, PATHINFO_EXTENSION)) }} • {{ $fileSizeMB }} MB
                                    </p>

                                    <a href="{{ $fileUrl }}" download="{{ $attachment->file_name }}"
                                       style="display: inline-block; background-color: #2563eb; color: #fff; padding: 3px 8px; border-radius: 4px; text-decoration: none; font-size: 0.75rem;">
                                        ⬇️
                                    </a>
                                    <a href="{{ $fileUrl }}" target="_blank"
                                       style="display: inline-block; background-color: #6b7280; color: #fff; padding: 3px 8px; border-radius: 4px; text-decoration: none; font-size: 0.75rem; margin-left: 2px;">
                                        🔗
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color: #6b7280; font-style: italic;">No attachments for this ticket.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
