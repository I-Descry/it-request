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

                    {{-- Scrollable Table Container --}}
                    <div style="overflow-x: auto; max-width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <table style="width: 100%; text-align: left; border-collapse: collapse; white-space: nowrap; min-width: 1300px;">
                            <thead>
                                <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Ticket No.</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Requested By</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Position</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Branch</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Request Type</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Status</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700; text-align: center;">Assisted By</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Original Date</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Archived At</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Archived By</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700; position: sticky; right: 0; background-color: #f3f4f6; z-index: 2; box-shadow: -4px 0 8px rgba(0,0,0,0.06);">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($archivedTickets as $index => $ticket)
                                @php $rowBg = $index % 2 === 0 ? '#ffffff' : '#f9fafb'; @endphp
                                <tr style="border-bottom: 1px solid #e5e7eb; background-color: {{ $rowBg }};">
                                    <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->ticket_no }}</td>
                                    <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->requested_by }}</td>
                                    <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->position ?? 'N/A' }}</td>
                                    <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->branch ?? 'N/A' }}</td>
                                    <td style="padding: 10px 14px; font-size: 0.85rem;">
                                        <span style="padding: 3px 10px; border-radius: 9999px; background-color: #e5e7eb; color: #374151; font-weight: bold; font-size: 0.8rem;">
                                            {{ $ticket->request_type }}
                                        </span>
                                    </td>
                                    <td style="padding: 10px 14px;">
                                        @php
                                            $statusColor = match($ticket->status) {
                                                'Open' => 'background-color: #fee2e2; color: #991b1b;',
                                                'In Progress' => 'background-color: #fef08a; color: #854d0e;',
                                                'Resolved' => 'background-color: #dcfce3; color: #166534;',
                                                'Closed' => 'background-color: #f3f4f6; color: #374151;',
                                                default => 'background-color: #f3f4f6; color: #374151;',
                                            };
                                        @endphp
                                        <span style="padding: 3px 10px; border-radius: 9999px; font-size: 0.8rem; font-weight: bold; {{ $statusColor }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>

                                    <td style="padding: 10px 14px; text-align: center; font-size: 0.85rem;">
                                        {{ $ticket->assisted_by ?? '—' }}
                                    </td>

                                    <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->original_created_at ? $ticket->original_created_at->format('M d, Y h:i A') : 'N/A' }}</td>
                                    <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->archived_at ? $ticket->archived_at->format('M d, Y h:i A') : 'N/A' }}</td>
                                    <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->archived_by ?? 'N/A' }}</td>

                                    <td style="padding: 10px 14px; position: sticky; right: 0; background-color: {{ $rowBg }}; z-index: 1; box-shadow: -4px 0 8px rgba(0,0,0,0.06);">
                                        <form action="{{ route('tickets.restore', $ticket->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to restore this ticket back to active tickets?');"
                                              style="display: inline; margin: 0;">
                                            @csrf
                                            <button type="submit" title="Restore"
                                                    style="color: #16a34a; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                </svg>
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
    </div>
</x-app-layout>
