<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('IT Requests Dashboard') }}
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
                    <div style="display: flex; gap: 0; margin-bottom: 20px; border-bottom: 2px solid var(--border-color); align-items: center; justify-content: space-between;">
                        <div style="display: flex; gap: 0;">
                            <a href="{{ route('tickets.index') }}"
                               style="padding: 10px 24px; font-weight: bold; text-decoration: none; border-bottom: 3px solid var(--text-primary); color: var(--text-primary); margin-bottom: -2px;">
                                📂 Active Tickets
                            </a>
                            <a href="{{ route('tickets.archived') }}"
                               style="padding: 10px 24px; font-weight: bold; text-decoration: none; border-bottom: 3px solid transparent; color: var(--text-light); margin-bottom: -2px;">
                                🗄️ Archived Tickets
                            </a>
                        </div>
                        @if(request()->has('status') && request()->status !== '')
                            <div>
                                @php
                                    $filterBadgeClass = match(request('status')) {
                                        'Open', 'Escalated' => 'dk-badge-open',
                                        'In Progress' => 'dk-badge-progress',
                                        'Resolved' => 'dk-badge-resolved',
                                        'Closed', 'Not Complete' => 'dk-badge-closed',
                                        default => 'dk-badge-closed',
                                    };
                                @endphp
                                <span style="font-size: 0.85rem; color: var(--text-secondary); font-weight: 500;">Filtered by Status: <span class="dk-badge {{ $filterBadgeClass }}" style="margin-right: 8px;">{{ request('status') }}</span></span>
                                <a href="{{ route('tickets.index') }}" style="font-size: 0.85rem; color: #ef4444; text-decoration: none; font-weight: 600;">× Clear</a>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('tickets.create') }}">
                        <button style="background-color: var(--text-primary); color: var(--bg-card); padding: 8px 15px; border-radius: 5px; cursor: pointer;">
                            + Record New Request
                        </button>
                    </a>
                    <br><br>

                    {{-- Scrollable Table Container --}}                    <div id="tickets-table-container">
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
                                    <th style="position: sticky; right: 0; background-color: var(--th-bg); z-index: 2; box-shadow: -4px 0 8px rgba(0,0,0,0.06);">Actions</th>
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
                                                $badgeClass = match($ticket->status) {
                                                    'Open', 'Escalated' => 'dk-badge-open',
                                                    'In Progress' => 'dk-badge-progress',
                                                    'Resolved' => 'dk-badge-resolved',
                                                    'Closed', 'Not Complete' => 'dk-badge-closed',
                                                    default => 'dk-badge-closed',
                                                };
                                            @endphp
                                            <span class="dk-badge {{ $badgeClass }}">
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
                                                <a href="{{ route('tickets.show', $ticket->ticket_no) }}" class="action-btn view" data-tooltip="View Ticket"
                                                   style="color: #2563eb; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('tickets.edit', $ticket->ticket_no) }}" class="action-btn edit" data-tooltip="Edit Ticket"
                                                   style="color: #059669; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('tickets.destroy', $ticket->ticket_no) }}" method="POST"
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
                                        <td colspan="11" style="text-align: center; padding: 20px; color: var(--text-light);">No tickets recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div style="margin-top: 15px;">
                        {{ $tickets->links() }}
                    </div>
                    </div>

                    <script>
                      document.addEventListener("DOMContentLoaded", function() {
                          document.body.addEventListener("click", function(e) {
                              if (e.target.closest("#tickets-table-container .pagination a") || e.target.closest("#tickets-table-container nav a")) {
                                  e.preventDefault();
                                  const url = e.target.closest("a").href;
                                  const container = document.getElementById("tickets-table-container");
                                  
                                  // Lock height and scroll
                                  const currentScroll = window.scrollY;
                                  container.style.minHeight = container.offsetHeight + "px";
                                  
                                  container.style.opacity = "0.5";
                                  container.style.pointerEvents = "none";
                                  
                                  fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
                                  .then(res => res.text())
                                  .then(html => {
                                      const parser = new DOMParser();
                                      const doc = parser.parseFromString(html, "text/html");
                                      const newContainer = doc.getElementById("tickets-table-container");
                                      if (newContainer) {
                                          container.innerHTML = newContainer.innerHTML;
                                      }
                                      container.style.opacity = "1";
                                      container.style.pointerEvents = "auto";
                                      container.style.minHeight = "";
                                      
                                      // Restore scroll position
                                      window.scrollTo(0, currentScroll);
                                  });
                              }
                          });
                      });
                  </script>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
