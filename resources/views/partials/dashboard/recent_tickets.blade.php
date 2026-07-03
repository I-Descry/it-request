                    <div style="padding: 8px 12px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                        <h3 class="dk-section-title" style="margin: 0; color: var(--text-primary);">Recent Tickets</h3>
                        <a href="{{ route('tickets.create') }}" style="background-color: #2563eb; color: var(--bg-card); padding: 4px 12px; border-radius: 4px; font-size: 0.7rem; font-weight: 600; text-decoration: none;">+ New Ticket</a>
                    </div>
                    <div style="flex: 1; overflow-y: auto; display: flex; flex-direction: column;">
                        <table class="dk-table" style="margin-bottom: auto;">
                            <thead>
                                <tr>
                                    <th>Ticket No.</th>
                                    <th>Request Type</th>
                                    <th>Requested By</th>
                                    <th>Assisted By</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentTickets as $ticket)
                                    <tr onclick="window.location='{{ route('tickets.show', $ticket->ticket_no) }}'">
                                        <td style="font-weight: 600; color: #3b82f6; white-space: nowrap;">
                                            {{ $ticket->ticket_no }}
                                        </td>
                                        <td>{{ $ticket->request_type }}</td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 6px;">
                                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 120px;">
                                                    {{ $ticket->requested_by }}
                                                </div>
                                                @if ($ticket->branch && strtoupper($ticket->branch) === 'HEAD OFFICE')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#2563eb" style="width: 14px; height: 14px; flex-shrink: 0;" title="Head Office">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-2.25a1.5 1.5 0 0 1 1.5-1.5h3a1.5 1.5 0 0 1 1.5 1.5V21" />
                                                    </svg>
                                                @elseif($ticket->branch)
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4f46e5" style="width: 14px; height: 14px; flex-shrink: 0;" title="Remote Branch ({{ $ticket->branch }})">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $ticket->assisted_by == 'IT03' ? 'Tristan Railey Tan' : ($ticket->assisted_by == 'IT04' ? 'John Paul Villacorta' : $ticket->assisted_by) }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match($ticket->status) {
                                                    'In Progress' => 'dk-badge-progress',
                                                    'Escalated' => 'dk-badge-open',
                                                    'Resolved' => 'dk-badge-resolved',
                                                    'Not Complete' => 'dk-badge-closed',
                                                    default => 'dk-badge-closed',
                                                };
                                            @endphp
                                            <span class="dk-badge {{ $badgeClass }}">{{ $ticket->status }}</span>
                                        </td>
                                        <td style="color: var(--text-light);">{{ $ticket->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: #9ca3af; padding: 10px;">No tickets found for this timeframe.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="dk-pagination" style="flex-shrink: 0; min-height: 52px; display: flex; align-items: center; justify-content: flex-end; padding: 0 16px; border-top: 1px solid var(--border-color); background: var(--bg-card);">
                        @if ($recentTickets->hasPages())
                            <div style="width: 100%;">
                                {{ $recentTickets->links() }}
                            </div>
                        @else
                            <div style="width: 100%;">
                                <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700 dark:text-gray-400 leading-5">
                                                Showing <span class="font-medium">{{ $recentTickets->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $recentTickets->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $recentTickets->total() }}</span> results
                                            </p>
                                        </div>
                                        <div>
                                            <span class="relative z-0 inline-flex shadow-sm rounded-md">
                                                <span aria-disabled="true" aria-label="&laquo; Previous">
                                                    <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-l-md dark:bg-gray-800 dark:border-gray-600">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </span>
                                                <span aria-current="page">
                                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 dark:bg-gray-800 dark:border-gray-600">1</span>
                                                </span>
                                                <span aria-disabled="true" aria-label="Next &raquo;">
                                                    <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-r-md dark:bg-gray-800 dark:border-gray-600">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        @endif
                    </div>
