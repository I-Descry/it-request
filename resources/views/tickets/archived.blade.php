<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Archived Tickets') }}
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

                    {{-- Tab Bar --}}
                    <div style="display: flex; gap: 0; margin-bottom: 20px; border-bottom: 2px solid #e5e7eb;">
                        <a href="{{ route('tickets.index') }}"
                           style="padding: 10px 24px; font-weight: bold; text-decoration: none; border-bottom: 3px solid transparent; color: #6b7280; margin-bottom: -2px;">
                            📋 Active Tickets
                        </a>
                        <a href="{{ route('tickets.archived') }}"
                           style="padding: 10px 24px; font-weight: bold; text-decoration: none; border-bottom: 3px solid #000; color: #000; margin-bottom: -2px;">
                            🗄️ Archived Tickets
                        </a>
                    </div>

                    <p style="margin-bottom: 15px; color: #6b7280; font-size: 0.9rem;">
                        These tickets have been archived for backup purposes. They are kept forever and cannot be permanently deleted.
                    </p>

                    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left; border-collapse: collapse;">
                        <thead style="background-color: #f3f4f6;">
                            <tr>
                                <th>Ticket No.</th>
                                <th>Requested By</th>
                                <th>Position</th>
                                <th>Branch</th>
                                <th>Type</th>
                                <th>System</th>
                                <th>Status</th>
                                <th>Attachments</th>
                                <th>Original Date</th>
                                <th>Archived At</th>
                                <th>Archived By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($archivedTickets as $ticket)
                                <tr style="border-bottom: 1px solid #e5e7eb; background-color: #fafafa;">
                                    <td>{{ $ticket->ticket_no }}</td>
                                    <td>{{ $ticket->requested_by }}</td>
                                    <td>{{ $ticket->position ?? 'N/A' }}</td>
                                    <td>{{ $ticket->branch ?? 'N/A' }}</td>
                                    <td>{{ $ticket->request_type }}</td>
                                    <td>{{ $ticket->affected_system ?? 'N/A' }}</td>

                                    <td>
                                        @php
                                            $statusColor = match($ticket->status) {
                                                'Open' => 'background-color: #fee2e2; color: #991b1b;',
                                                'In Progress' => 'background-color: #fef08a; color: #854d0e;',
                                                'Resolved' => 'background-color: #dcfce3; color: #166534;',
                                                'Closed' => 'background-color: #f3f4f6; color: #374151;',
                                                default => 'background-color: #f3f4f6; color: #374151;',
                                            };
                                        @endphp
                                        <span style="padding: 4px 10px; border-radius: 9999px; font-size: 0.85rem; font-weight: bold; {{ $statusColor }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>

                                    <td style="text-align: center;">
                                        @if ($ticket->attachments_count > 0)
                                            📎 {{ $ticket->attachments_count }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td>{{ $ticket->original_created_at ? $ticket->original_created_at->format('M d, Y h:i A') : 'N/A' }}</td>
                                    <td>{{ $ticket->archived_at ? $ticket->archived_at->format('M d, Y h:i A') : 'N/A' }}</td>
                                    <td>{{ $ticket->archived_by ?? 'N/A' }}</td>

                                    <td>
                                        <form action="{{ route('tickets.restore', $ticket->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to restore this ticket back to active tickets?');">
                                            @csrf
                                            <button type="submit"
                                                    style="background-color: #16a34a; color: #fff; padding: 4px 12px; border-radius: 4px; border: none; cursor: pointer; font-size: 0.85rem;">
                                                ♻️ Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" style="text-align: center; padding: 20px;">No archived tickets.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
