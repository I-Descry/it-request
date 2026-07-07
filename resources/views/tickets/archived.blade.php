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

                    <!-- Search & Filter Form -->
                    <div style="background: var(--panel-bg); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 20px;">
                        <form method="GET" action="{{ route('tickets.archived') }}" id="filterForm" style="display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap;">
                            <!-- Hidden inputs for sorting -->
                            <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by', 'archived_at') }}">
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
                                    @foreach(['Open', 'In Progress', 'Resolved', 'Closed', 'Escalated', 'Not Complete'] as $stat)
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
                                    @foreach(['HEAD OFFICE', 'NDD BACOLOD', 'NDD BAESA', 'NDD BATAAN', 'NDD BATANGAS', 'NDD CAVITE', 'NDD CDO', 'NDD CEBU', 'NDD DAVAO', 'NDD DIPOLOG', 'NDD DUMAGUETE', 'NDD ILOILO', 'NDD LA UNION', 'NDD LAGUNA', 'NDD LAS PIÑAS', 'NDD NUEVA ECIJA', 'NDD PULILAN', 'NDD ROXAS', 'NDD SAN FRANCISCO', 'NDD TACLOBAN', 'NDD TARLAC', 'NDD TAYTAY'] as $br)
                                        <option value="{{ $br }}" {{ request('filter_branch') == $br ? 'selected' : '' }}>{{ $br }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <button type="submit" class="dk-btn dk-btn-primary" style="height: 38px;">Filter</button>
                            </div>
                            @if(request()->hasAny(['search', 'filter_dept', 'filter_branch', 'status', 'request_type']))
                            <div>
                                <a href="{{ route('tickets.archived') }}" class="dk-btn dk-btn-outline" style="height: 38px;">Clear</a>
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

                    <p style="margin-bottom: 15px; color: var(--text-light); font-size: 0.9rem;">
                        These tickets have been archived for backup purposes. They are kept forever and cannot be permanently deleted.
                    </p>

                    {{-- Scrollable Table Container --}}
                    <div class="dk-table-wrap">
                        <table class="dk-table" style="min-width: 1300px;">
                            <thead>
                                @php
                                    $sortCol = request('sort_by', 'archived_at');
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
                                    <th>Original Date</th>
                                    <th style="cursor: pointer;" onclick="sortBy('archived_at')">Archived At {!! sortArrow('archived_at', $sortCol, $sortDir) !!}</th>
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
