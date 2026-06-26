<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Archived Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if (session('success'))
                        <div style="color: green; margin-bottom: 15px; font-weight: bold;">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Tab Bar --}}
                    <div style="display: flex; gap: 0; margin-bottom: 20px; border-bottom: 2px solid #e5e7eb;">
                        <a href="{{ route('tickets.index') }}"
                           style="padding: 10px 24px; font-weight: bold; text-decoration: none; border-bottom: 3px solid transparent; color: var(--text-light); margin-bottom: -2px;">
                            📋 Active Tickets
                        </a>
                        <a href="{{ route('tickets.archived') }}"
                           style="padding: 10px 24px; font-weight: bold; text-decoration: none; border-bottom: 3px solid var(--text-primary); color: var(--text-primary); margin-bottom: -2px;">
                            🗄️ Archived Tickets
                        </a>
                    </div>

                    <p style="margin-bottom: 15px; color: var(--text-light); font-size: 0.9rem;">
                        These tickets have been archived for backup purposes. They are kept forever and cannot be permanently deleted.
                    </p>

                    {{-- Scrollable Table Container --}}
                    <div class="dk-table-wrap">
                        <table class="dk-table" style="min-width: 1300px;">
                            <thead>
                                <tr>
                                    <th>Ticket No.</th>
                                    <th>Requested By</th>
                                    <th>Position</th>
                                    <th>Branch</th>
                                    <th>Request Type</th>
                                    <th>Request</th>
                                    <th>Status</th>
                                    <th style="text-align: center;">Assisted By</th>
                                    <th>Original Date</th>
                                    <th>Archived At</th>
                                    <th>Archived By</th>
                                    <th style="position: sticky; right: 0; background-color: var(--th-bg); z-index: 2; box-shadow: -4px 0 8px rgba(0,0,0,0.06);">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($archivedTickets as $ticket)
                                <tr>
                                    <td style="position: relative; white-space: nowrap;">
                                        {{ $ticket->ticket_no }}
                                    </td>
                                    <td>{{ $ticket->requested_by }}</td>
                                    <td>{{ $ticket->position ?? 'N/A' }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            @if ($ticket->branch && strtoupper($ticket->branch) === 'HEAD OFFICE')
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#2563eb" style="width: 14px; height: 14px; flex-shrink: 0;" title="Head Office">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-2.25a1.5 1.5 0 0 1 1.5-1.5h3a1.5 1.5 0 0 1 1.5 1.5V21" />
                                                </svg>
                                                <span style="color: var(--text-secondary);">{{ $ticket->branch }}</span>
                                            @elseif($ticket->branch)
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4f46e5" style="width: 14px; height: 14px; flex-shrink: 0;" title="Remote Branch">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                                </svg>
                                                <span style="color: var(--text-secondary);">{{ $ticket->branch }}</span>
                                            @else
                                                <span style="color: #9ca3af;">N/A</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="dk-badge" style="background-color: #e5e7eb; color: #374151;">
                                            {{ $ticket->request_type }}
                                        </span>
                                    </td>
                                    <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" data-tooltip="{{ $ticket->request ?? '' }}">{{ $ticket->request ?? '—' }}</td>
                                    <td>
                                            @php
                                                $statusColor = match($ticket->status) {
                                                    'Open', 'Escalated' => 'background-color: var(--bg-card)beb; color: #f59e0b;',
                                                    'In Progress' => 'background-color: #eff6ff; color: #3b82f6;',
                                                    'Resolved' => 'background-color: #ecfdf5; color: #10b981;',
                                                    'Closed', 'Not Complete' => 'background-color: #f3f4f6; color: var(--text-light);',
                                                    default => 'background-color: #f3f4f6; color: var(--text-light);',
                                                };
                                            @endphp
                                        <span class="dk-badge" style="{{ $statusColor }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>

                                    <td style="text-align: center;">
                                        {{ $ticket->assisted_by ?? '—' }}
                                    </td>

                                    <td>{{ $ticket->original_created_at ? $ticket->original_created_at->format('M d, Y h:i A') : 'N/A' }}</td>
                                    <td>{{ $ticket->archived_at ? $ticket->archived_at->format('M d, Y h:i A') : 'N/A' }}</td>
                                    <td>{{ $ticket->archived_by ?? 'N/A' }}</td>

                                    <td style="position: sticky; right: 0; background-color: inherit; z-index: 1; box-shadow: -4px 0 8px rgba(0,0,0,0.06);">
                                        <form action="{{ route('tickets.restore', $ticket->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to restore this ticket back to active tickets?');"
                                              style="display: inline; margin: 0;">
                                            @csrf
                                            <button type="submit" class="action-btn restore" data-tooltip="Restore Ticket"
                                                    style="color: #4f46e5; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; padding: 0;">
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

                    <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-color); background: var(--bg-card); border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                        {{ $archivedTickets->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
