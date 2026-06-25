<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('IT Requests Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-visible shadow-sm sm:rounded-lg">
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
                    <div class="dk-table-wrap">
                        <table class="dk-table" style="min-width: 1100px;">
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
                                    <th>Date Created</th>
                                    <th style="position: sticky; right: 0; background-color: #f8fafc; z-index: 2; box-shadow: -4px 0 8px rgba(0,0,0,0.06);">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tickets as $ticket)
                                    <tr>
                                        <td style="position: relative; white-space: nowrap;">
                                            {{ $ticket->ticket_no }}
                                            @if ($ticket->restored_from_archive)
                                                <span data-tooltip="Restored from archive" style="margin-left: 6px; cursor: help; font-size: 1rem;">♻️</span>
                                            @endif
                                        </td>
                                        <td>{{ $ticket->requested_by }}</td>
                                        <td>{{ $ticket->position ?? 'N/A' }}</td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 6px;">
                                                @if ($ticket->branch && strtoupper($ticket->branch) === 'HEAD OFFICE')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#2563eb" style="width: 14px; height: 14px; flex-shrink: 0;" title="Head Office">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-2.25a1.5 1.5 0 0 1 1.5-1.5h3a1.5 1.5 0 0 1 1.5 1.5V21" />
                                                    </svg>
                                                    <span style="color: #4b5563;">{{ $ticket->branch }}</span>
                                                @elseif($ticket->branch)
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4f46e5" style="width: 14px; height: 14px; flex-shrink: 0;" title="Remote Branch">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                                    </svg>
                                                    <span style="color: #4b5563;">{{ $ticket->branch }}</span>
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
                                                    'Open', 'Escalated' => 'background-color: #fffbeb; color: #f59e0b;',
                                                    'In Progress' => 'background-color: #eff6ff; color: #3b82f6;',
                                                    'Resolved' => 'background-color: #ecfdf5; color: #10b981;',
                                                    'Closed', 'Not Complete' => 'background-color: #f3f4f6; color: #6b7280;',
                                                    default => 'background-color: #f3f4f6; color: #6b7280;',
                                                };
                                            @endphp
                                            <span class="dk-badge" style="{{ $statusColor }}">
                                                {{ $ticket->status }}
                                            </span>
                                        </td>

                                        <td style="text-align: center;">
                                            {{ $ticket->assisted_by ?? '—' }}
                                        </td>

                                        <td>{{ $ticket->created_at->format('M d, Y h:i A') }}</td>

                                        {{-- Sticky Actions Column --}}
                                        <td style="position: sticky; right: 0; background-color: inherit; z-index: 1; box-shadow: -4px 0 8px rgba(0,0,0,0.06);">
                                            <div style="display: flex; gap: 4px; align-items: center; background-color: inherit;">
                                                <a href="{{ route('tickets.show', $ticket->id) }}" class="action-btn view" data-tooltip="View Ticket"
                                                   style="color: #2563eb; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="action-btn edit" data-tooltip="Edit Ticket"
                                                   style="color: #059669; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST"
                                                      onsubmit="return confirm('Are you sure you want to archive this ticket?');"
                                                      style="display: inline; margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn delete" data-tooltip="Delete Ticket"
                                                            style="color: #dc2626; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; padding: 0;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" style="text-align: center; padding: 20px; color: #6b7280;">No tickets recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div style="padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background: #fff; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                        {{ $tickets->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
