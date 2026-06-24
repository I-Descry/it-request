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
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Request Type</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Status</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700; text-align: center;">Assisted By</th>
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
                                                <span title="Restored from archive" style="margin-left: 6px; cursor: help; font-size: 1rem;">♻️</span>
                                            @endif
                                        </td>
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

                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $ticket->created_at->format('M d, Y h:i A') }}</td>

                                        {{-- Sticky Actions Column --}}
                                        <td style="padding: 10px 14px; position: sticky; right: 0; background-color: {{ $rowBg }}; z-index: 1; box-shadow: -4px 0 8px rgba(0,0,0,0.06);">
                                            <div style="display: flex; gap: 4px; align-items: center;">
                                                <a href="{{ route('tickets.show', $ticket->id) }}" title="View"
                                                   style="color: #2563eb; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('tickets.edit', $ticket->id) }}" title="Edit"
                                                   style="color: #f59e0b; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST"
                                                      onsubmit="return confirm('Are you sure you want to archive this ticket?');"
                                                      style="display: inline; margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Archive"
                                                            style="color: #dc2626; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px;">
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