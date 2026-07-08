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
                    <div style="display: flex; gap: 0; margin-bottom: 20px; border-bottom: 2px solid var(--border-color); align-items: center;">
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
                    </div>

                    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                        <a href="{{ route('tickets.create') }}" class="dk-btn dk-btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Record New Request
                        </a>
                    </div>

                    <!-- Search & Filter Form -->
                    <div style="background: var(--panel-bg); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 20px;">
                        <form method="GET" action="{{ route('tickets.index') }}" id="filterForm" style="display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap;">
                            <!-- Hidden inputs for sorting -->
                            <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by', 'created_at') }}">
                            <input type="hidden" name="sort_dir" id="sort_dir" value="{{ request('sort_dir', 'desc') }}">
                            
                            <div style="flex: 1; min-width: 200px;">
                                <label for="search" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 5px;">Search Tickets</label>
                                <div style="position: relative;">
                                    <div style="position: absolute; top: 9px; left: 10px; color: var(--text-muted);">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Ticket No, Requestor, Type..." style="width: 100%; padding: 8px 10px 8px 35px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.9rem; outline: none;">
                                </div>
                            </div>
                            
                            <div style="width: 150px;">
                                <label for="status" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 5px;">Status</label>
                                <select name="status" id="status" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.9rem; outline: none;" onchange="document.getElementById('filterForm').requestSubmit();">
                                    <option value="">All Statuses</option>
                                    @foreach(['Open', 'In Progress', 'Resolved', 'Closed', 'Escalated', 'Not Complete', 'Cancelled'] as $stat)
                                        <option value="{{ $stat }}" {{ request('status') == $stat ? 'selected' : '' }}>{{ $stat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div style="width: 150px;">
                                <label for="filter_dept" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 5px;">Department</label>
                                <select name="filter_dept" id="filter_dept" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.9rem; outline: none;" onchange="document.getElementById('filterForm').requestSubmit();">
                                    <option value="">All Departments</option>
                                    @foreach(array_keys($hierarchy) as $dept)
                                        <option value="{{ $dept }}" {{ request('filter_dept') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="width: 150px;">
                                <label for="filter_branch" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 5px;">Branch</label>
                                <select name="filter_branch" id="filter_branch" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.9rem; outline: none;" onchange="document.getElementById('filterForm').requestSubmit();">
                                    <option value="">All Branches</option>
                                    @foreach(['HEAD OFFICE', 'DC TAYTAY', 'NDD BACOLOD', 'NDD BAESA', 'NDD BATAAN', 'NDD BATANGAS', 'NDD CAVITE', 'NDD CDO', 'NDD CEBU', 'NDD DAVAO', 'NDD DIPOLOG', 'NDD DUMAGUETE', 'NDD ILOILO', 'NDD LA UNION', 'NDD LAGUNA', 'NDD LAS PIÑAS', 'NDD NUEVA ECIJA', 'NDD PULILAN', 'NDD ROXAS', 'NDD SAN FRANCISCO', 'NDD TACLOBAN', 'NDD TARLAC', 'NDD TAYTAY'] as $br)
                                        <option value="{{ $br }}" {{ request('filter_branch') == $br ? 'selected' : '' }}>{{ $br }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <button type="submit" class="dk-btn dk-btn-primary" style="height: 38px;">Filter</button>
                            </div>
                            @if(request()->hasAny(['search', 'filter_dept', 'filter_branch', 'status', 'request_type']))
                            <div>
                                <a href="{{ route('tickets.index') }}" class="dk-btn dk-btn-outline" style="height: 38px;">Clear</a>
                            </div>
                            @endif
                        </form>
                    </div>

                    <script>
                        function sortBy(column) {
                            let currentSort = document.getElementById('sort_by').value;
                            let currentDir = document.getElementById('sort_dir').value;
                            if (currentSort === column) {
                                document.getElementById('sort_dir').value = currentDir === 'asc' ? 'desc' : 'asc';
                            } else {
                                document.getElementById('sort_by').value = column;
                                document.getElementById('sort_dir').value = 'asc';
                            }
                            document.getElementById('filterForm').submit();
                        }
                    </script>

                    {{-- Scrollable Table Container --}}                    <div id="tickets-table-container">
                        <div class="dk-table-wrap">
                        <table class="dk-table" style="min-width: 1100px;">
                            <thead>
                                @php
                                    $sortCol = request('sort_by', 'created_at');
                                    $sortDir = request('sort_dir', 'desc');
                                    if (!function_exists('sortArrow')) {
                                        function sortArrow($col, $sCol, $sDir) {
                                            if ($col !== $sCol) return "<span style='color: #cbd5e1; font-size: 0.8rem; margin-left: 4px;'>↕</span>";
                                            return $sDir === 'asc' ? "<span style='color: #1f2937; font-size: 0.8rem; margin-left: 4px;'>↑</span>" : "<span style='color: #1f2937; font-size: 0.8rem; margin-left: 4px;'>↓</span>";
                                        }
                                    }
                                @endphp
                                <tr style="user-select: none;">
                                    <th style="cursor: pointer;" onclick="sortBy('ticket_no')">Ticket No. {!! sortArrow('ticket_no', $sortCol, $sortDir) !!}</th>
                                    <th style="cursor: pointer;" onclick="sortBy('requested_by')">Requested By {!! sortArrow('requested_by', $sortCol, $sortDir) !!}</th>
                                    <th style="cursor: pointer;" onclick="sortBy('position')">Position {!! sortArrow('position', $sortCol, $sortDir) !!}</th>
                                    <th style="cursor: pointer;" onclick="sortBy('branch')">Branch {!! sortArrow('branch', $sortCol, $sortDir) !!}</th>
                                    <th style="cursor: pointer;" onclick="sortBy('request_type')">Request Type {!! sortArrow('request_type', $sortCol, $sortDir) !!}</th>
                                    <th>Request</th>
                                    <th style="cursor: pointer;" onclick="sortBy('status')">Status {!! sortArrow('status', $sortCol, $sortDir) !!}</th>
                                    <th style="text-align: center;">Assisted By</th>
                                    <th style="cursor: pointer;" onclick="sortBy('created_at')">Date Created {!! sortArrow('created_at', $sortCol, $sortDir) !!}</th>
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
                                                    'Cancelled' => 'dk-badge-cancelled',
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
                                                <a href="{{ route('tickets.create', ['from' => $ticket->ticket_no]) }}" class="action-btn duplicate" data-tooltip="Duplicate Ticket"
                                                   style="color: #64748b; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('tickets.edit', $ticket->ticket_no) }}" class="action-btn edit" data-tooltip="Edit Ticket"
                                                   style="color: #059669; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('tickets.destroy', $ticket->ticket_no) }}" method="POST" 
                                                      style="display: inline-block; margin: 0;" 
                                                      x-data @submit.prevent="$dispatch('open-confirm', { title: 'Archive Ticket', message: 'Are you sure you want to archive this ticket?', buttonText: 'Archive', buttonColor: '#f59e0b', form: $el })">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn" style="background: none; border: none; padding: 0; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; color: #f59e0b;" data-tooltip="Archive">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
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
