<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Ticket') }} — {{ $ticket->ticket_no }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div style="color: green; margin-bottom: 15px; font-weight: bold;">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Restored Badge --}}
                    @if ($ticket->restored_from_archive)
                        <div style="background-color: #dbeafe; color: #1e40af; padding: 10px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; font-size: 0.9rem;">
                            ♻️ This ticket was restored from the Archived Tickets on {{ $ticket->restored_at ? $ticket->restored_at->format('M d, Y h:i A') : 'N/A' }}
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div style="display: flex; gap: 10px; margin-bottom: 25px;">
                        <a href="{{ route('tickets.index') }}"
                           style="background-color: #6b7280; color: #fff; padding: 8px 16px; border-radius: 5px; text-decoration: none; font-size: 0.9rem;">
                            ← Back to Tickets
                        </a>
                        <a href="{{ route('tickets.edit', $ticket->id) }}"
                           style="background-color: #f59e0b; color: #fff; padding: 8px 16px; border-radius: 5px; text-decoration: none; font-size: 0.9rem;">
                            ✏️ Edit Ticket
                        </a>
                    </div>

                    {{-- Ticket Details --}}
                    <table cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="font-weight: bold; width: 200px; vertical-align: top; padding: 12px;">Ticket No.</td>
                            <td style="padding: 12px;">{{ $ticket->ticket_no }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb; background-color: #f9fafb;">
                            <td style="font-weight: bold; padding: 12px;">Status</td>
                            <td style="padding: 12px;">
                                @php
                                    $statusColor = match($ticket->status) {
                                        'Open' => 'background-color: #fee2e2; color: #991b1b;',
                                        'In Progress' => 'background-color: #fef08a; color: #854d0e;',
                                        'Resolved' => 'background-color: #dcfce3; color: #166534;',
                                        'Closed' => 'background-color: #f3f4f6; color: #374151;',
                                        default => 'background-color: #f3f4f6; color: #374151;',
                                    };
                                @endphp
                                <span style="padding: 4px 12px; border-radius: 9999px; font-size: 0.85rem; font-weight: bold; {{ $statusColor }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="font-weight: bold; padding: 12px;">Request Type</td>
                            <td style="padding: 12px;">{{ $ticket->request_type }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb; background-color: #f9fafb;">
                            <td style="font-weight: bold; padding: 12px;">Requested By</td>
                            <td style="padding: 12px;">{{ $ticket->requested_by }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="font-weight: bold; padding: 12px;">Position</td>
                            <td style="padding: 12px;">{{ $ticket->position ?? 'N/A' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb; background-color: #f9fafb;">
                            <td style="font-weight: bold; padding: 12px;">Branch</td>
                            <td style="padding: 12px;">{{ $ticket->branch ?? 'N/A' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="font-weight: bold; padding: 12px;">Affected System</td>
                            <td style="padding: 12px;">{{ $ticket->affected_system ?? 'N/A' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb; background-color: #f9fafb;">
                            <td style="font-weight: bold; vertical-align: top; padding: 12px;">Details of the Issue</td>
                            <td style="padding: 12px; white-space: pre-wrap;">{{ $ticket->request_details }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="font-weight: bold; padding: 12px;">Assisted By</td>
                            <td style="padding: 12px;">{{ $ticket->assisted_by ?? 'N/A' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb; background-color: #f9fafb;">
                            <td style="font-weight: bold; vertical-align: top; padding: 12px;">Remarks</td>
                            <td style="padding: 12px; white-space: pre-wrap;">{{ $ticket->remarks ?? 'N/A' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="font-weight: bold; padding: 12px;">Date Created</td>
                            <td style="padding: 12px;">{{ $ticket->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb; background-color: #f9fafb;">
                            <td style="font-weight: bold; padding: 12px;">Last Updated</td>
                            <td style="padding: 12px;">{{ $ticket->updated_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    </table>

                    {{-- Attachments Section --}}
                    <h3 style="font-size: 1.2rem; font-weight: bold; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                        📎 Attachments ({{ $ticket->attachments->count() }})
                    </h3>

                    @if ($ticket->attachments->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
                            @foreach ($ticket->attachments as $attachment)
                                <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; background-color: #f9fafb;">
                                    {{-- File Preview --}}
                                    @php
                                        $isImage = str_starts_with($attachment->file_type, 'image/');
                                        $isVideo = str_starts_with($attachment->file_type, 'video/');
                                        $isAudio = str_starts_with($attachment->file_type, 'audio/');
                                        $fileUrl = asset('storage/' . $attachment->file_path);
                                        $fileSizeMB = number_format($attachment->file_size / 1048576, 2);
                                    @endphp

                                    @if ($isImage)
                                        <div style="margin-bottom: 10px; text-align: center;">
                                            <img src="{{ $fileUrl }}" alt="{{ $attachment->file_name }}"
                                                 style="max-width: 100%; max-height: 200px; border-radius: 6px; object-fit: contain; cursor: pointer;"
                                                 onclick="window.open('{{ $fileUrl }}', '_blank')">
                                        </div>
                                    @elseif ($isVideo)
                                        <div style="margin-bottom: 10px;">
                                            <video controls style="width: 100%; max-height: 200px; border-radius: 6px;">
                                                <source src="{{ $fileUrl }}" type="{{ $attachment->file_type }}">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @elseif ($isAudio)
                                        <div style="margin-bottom: 10px;">
                                            <audio controls style="width: 100%;">
                                                <source src="{{ $fileUrl }}" type="{{ $attachment->file_type }}">
                                                Your browser does not support the audio tag.
                                            </audio>
                                        </div>
                                    @else
                                        <div style="margin-bottom: 10px; text-align: center; padding: 20px; background-color: #e5e7eb; border-radius: 6px;">
                                            <span style="font-size: 2rem;">📄</span>
                                        </div>
                                    @endif

                                    {{-- File Info --}}
                                    <p style="font-weight: bold; font-size: 0.85rem; word-break: break-all; margin-bottom: 4px;">
                                        {{ $attachment->file_name }}
                                    </p>
                                    <p style="font-size: 0.8rem; color: #6b7280; margin-bottom: 8px;">
                                        {{ strtoupper(pathinfo($attachment->file_name, PATHINFO_EXTENSION)) }} • {{ $fileSizeMB }} MB
                                    </p>

                                    {{-- Download Button --}}
                                    <a href="{{ $fileUrl }}" download="{{ $attachment->file_name }}"
                                       style="display: inline-block; background-color: #2563eb; color: #fff; padding: 4px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85rem;">
                                        ⬇️ Download
                                    </a>
                                    <a href="{{ $fileUrl }}" target="_blank"
                                       style="display: inline-block; background-color: #6b7280; color: #fff; padding: 4px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85rem; margin-left: 4px;">
                                        🔗 Open
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
