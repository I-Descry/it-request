<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('IT Requests Dashboard') }}
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
                           style="padding: 10px 24px; font-weight: bold; text-decoration: none; border-bottom: 3px solid #000; color: #000; margin-bottom: -2px;">
                            📋 Active Tickets
                        </a>
                        <a href="{{ route('tickets.archived') }}"
                           style="padding: 10px 24px; font-weight: bold; text-decoration: none; border-bottom: 3px solid transparent; color: #6b7280; margin-bottom: -2px;">
                            🗄️ Archived Tickets
                        </a>
                    </div>

                    <a href="{{ route('tickets.create') }}">
                        <button style="background-color: #000; color: #fff; padding: 8px 15px; border-radius: 5px; cursor: pointer;">
                            + Record New Request
                        </button>
                    </a>
                    <br><br>

                    {{-- Scrollable Table Container --}}
                    <div style="overflow-x: auto; max-width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <table style="width: 100%; text-align: left; border-collapse: collapse; white-space: nowrap; min-width: 1100px;">
                            <thead>
                                <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Ticket No.</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Requested By</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Position</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Branch</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Type</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">System</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Status</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700; text-align: center;">Attachments</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Date Created</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700; position: sticky; right: 0; background-color: #f3f4f6; z-index: 2; box-shadow: -4px 0 8px rgba(0,0,0,0.06);">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tickets as $index => $ticket)
                                    @php $rowBg = $index % 2 === 0 ? '#ffffff' : '#f9fafb'; @endphp
                                    <tr style="border-bottom: 1px solid #e5e7eb; background-color: {{ $rowBg }};">
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">
                                            {{ $ticket->ticket_no }}
                                            @if ($ticket->restored_from_archive)
                                                <span style="margin-left: 6px; padding: 2px 6px; border-radius: 9999px; font-size: 0.7rem; font-weight: bold; background-color: #dbeafe; color: #1e40af;">♻️ Restored</span>
                                            @endif
                                        </td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->requested_by }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->position ?? 'N/A' }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->branch ?? 'N/A' }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->request_type }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->affected_system ?? 'N/A' }}</td>
                                        
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
                                            @if ($ticket->attachments_count > 0)
                                                📎 {{ $ticket->attachments_count }}
                                            @else
                                                —
                                            @endif
                                        </td>

                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->created_at->format('M d, Y h:i A') }}</td>

                                        {{-- Sticky Actions Column --}}
                                        <td style="padding: 10px 14px; position: sticky; right: 0; background-color: {{ $rowBg }}; z-index: 1; box-shadow: -4px 0 8px rgba(0,0,0,0.06);">
                                            <div style="display: flex; gap: 4px; align-items: center;">
                                                <a href="{{ route('tickets.show', $ticket->id) }}"
                                                   style="background-color: #2563eb; color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 0.8rem;">
                                                    👁️ View
                                                </a>
                                                <a href="{{ route('tickets.edit', $ticket->id) }}"
                                                   style="background-color: #f59e0b; color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 0.8rem;">
                                                    ✏️ Edit
                                                </a>
                                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST"
                                                      onsubmit="return confirm('Are you sure you want to archive this ticket?');"
                                                      style="display: inline; margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            style="background-color: #dc2626; color: #fff; padding: 4px 10px; border-radius: 4px; border: none; cursor: pointer; font-size: 0.8rem;">
                                                        🗑️ Archive
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" style="text-align: center; padding: 20px; color: #6b7280;">No tickets recorded yet.</td>
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