<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Dashboard') }}</h2>
    </x-slot>

    <div style="padding: 10px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])

            {{-- Row 1: KPI Overview --}}
            <div class="dk-grid-5">
                <div class="dk-card dk-card-accent dk-clickable-card" style="border-left-color: #3b82f6; cursor: pointer;" onclick="window.location='{{ route('tickets.index') }}'">
                    <div class="dk-kpi-icon" style="background: #eff6ff;"><svg fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                    <div class="dk-kpi-label">Total Active</div>
                    <div class="dk-kpi-value">{{ $totalActive }}</div>
                    <div class="dk-kpi-sub">Total recorded in system</div>
                </div>
                <div class="dk-card dk-card-accent dk-clickable-card" style="border-left-color: #3b82f6; cursor: pointer;" onclick="window.location='{{ route('tickets.index', ['status' => 'In Progress']) }}'">
                    <div class="dk-kpi-icon" style="background: #eff6ff;"><svg fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                    <div class="dk-kpi-label">In Progress</div>
                    <div class="dk-kpi-value">{{ $byStatus['In Progress'] ?? 0 }}</div>
                    <div class="dk-kpi-sub">{{ $totalActive > 0 ? number_format((($byStatus['In Progress'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
                <div class="dk-card dk-card-accent dk-clickable-card" style="border-left-color: #f59e0b; cursor: pointer;" onclick="window.location='{{ route('tickets.index', ['status' => 'Escalated']) }}'">
                    <div class="dk-kpi-icon" style="background: var(--bg-card)beb;"><svg fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
                    <div class="dk-kpi-label">Escalated</div>
                    <div class="dk-kpi-value">{{ $byStatus['Escalated'] ?? 0 }}</div>
                    <div class="dk-kpi-sub">{{ $totalActive > 0 ? number_format((($byStatus['Escalated'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
                <div class="dk-card dk-card-accent dk-clickable-card" style="border-left-color: #10b981; cursor: pointer;" onclick="window.location='{{ route('tickets.index', ['status' => 'Resolved']) }}'">
                    <div class="dk-kpi-icon" style="background: #ecfdf5;"><svg fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div class="dk-kpi-label">Resolved</div>
                    <div class="dk-kpi-value">{{ $byStatus['Resolved'] ?? 0 }}</div>
                    <div class="dk-kpi-sub">{{ $totalActive > 0 ? number_format((($byStatus['Resolved'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
                <div class="dk-card dk-card-accent dk-clickable-card" style="border-left-color: var(--text-light); cursor: pointer;" onclick="window.location='{{ route('tickets.index', ['status' => 'Not Complete']) }}'">
                    <div class="dk-kpi-icon" style="background: #f3f4f6;"><svg fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div class="dk-kpi-label">Not Complete</div>
                    <div class="dk-kpi-value">{{ $byStatus['Not Complete'] ?? 0 }}</div>
                    <div class="dk-kpi-sub">{{ $totalActive > 0 ? number_format((($byStatus['Not Complete'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
            </div>

            {{-- Row 2: Analytics Charts (Full Width for Readability) --}}
            <div class="dk-grid-2">
                <div class="dk-card">
                    <div class="dk-section-title">Request Types</div>
                    <div class="dk-chart-wrap"><canvas id="donutChart"></canvas></div>
                </div>
                <div class="dk-card">
                    <div class="dk-section-title">Technician Performance</div>
                    <div class="dk-chart-wrap"><canvas id="techChart"></canvas></div>
                </div>
            </div>

            {{-- Row 3: Actionable Data (Table + Volume) --}}
            <div class="dk-grid-70-30">
                {{-- Tickets Table --}}
                <div class="dk-table-wrap" id="recent-tickets-container">
                    <div style="padding: 8px 12px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="dk-section-title" style="margin: 0; color: #111827;">Recent Tickets</h3>
                        <a href="{{ route('tickets.create') }}" style="background-color: #2563eb; color: var(--bg-card); padding: 4px 12px; border-radius: 4px; font-size: 0.7rem; font-weight: 600; text-decoration: none;">+ New Ticket</a>
                    </div>
                    <table class="dk-table">
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
                                            {{ Str::limit($ticket->requested_by, 20) }}
                                            @if ($ticket->branch && strtoupper($ticket->branch) === 'HEAD OFFICE')
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#0ea5e9" style="width: 14px; height: 14px; flex-shrink: 0;" title="Head Office">
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
                                    <td colspan="6" style="text-align: center; color: #9ca3af; padding: 10px;">No tickets found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="dk-pagination">
                        {{ $recentTickets->links() }}
                    </div>
                </div>

                {{-- Request Volume --}}
                <div class="dk-card" style="justify-content: flex-start;">
                    <div class="dk-section-title">Request Volume</div>
                    <div style="display: flex; flex-direction: column; gap: 8px; flex-grow: 1;">
                        <div style="background: var(--th-bg); padding: 8px 12px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--border-color); flex: 1;">
                            <div class="dk-kpi-label" style="margin: 0;">Today</div>
                            <div class="dk-kpi-value" style="margin: 0; font-size: 1.1rem; color: #3b82f6;">{{ $todayCount }} <span style="font-size: 0.75rem; color: #9ca3af; font-weight: 600;">({{ $totalActive > 0 ? number_format(($todayCount / $totalActive) * 100, 1) : '0.0' }}%)</span></div>
                        </div>
                        <div style="background: var(--th-bg); padding: 8px 12px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--border-color); flex: 1;">
                            <div class="dk-kpi-label" style="margin: 0;">This Week</div>
                            <div class="dk-kpi-value" style="margin: 0; font-size: 1.1rem; color: #6366f1;">{{ $weekCount }} <span style="font-size: 0.75rem; color: #9ca3af; font-weight: 600;">({{ $totalActive > 0 ? number_format(($weekCount / $totalActive) * 100, 1) : '0.0' }}%)</span></div>
                        </div>
                        <div style="background: var(--th-bg); padding: 8px 12px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--border-color); flex: 1;">
                            <div class="dk-kpi-label" style="margin: 0;">This Month</div>
                            <div class="dk-kpi-value" style="margin: 0; font-size: 1.1rem; color: #8b5cf6;">{{ $monthCount }} <span style="font-size: 0.75rem; color: #9ca3af; font-weight: 600;">({{ $totalActive > 0 ? number_format(($monthCount / $totalActive) * 100, 1) : '0.0' }}%)</span></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        @php 
            $tL=[]; $tR=[]; $tU=[]; 
            foreach($techPerformance as $c=>$d){ 
                $tL[]=$assistedByMap[$c]??$c; 
                $tR[]=$d['resolved']; 
                $tU[]=$d['unresolved']; 
            } 
        @endphp
        window.dashboardData = {
            requestTypes: {
                keys: {!! json_encode(array_keys($byRequestType)) !!},
                values: {!! json_encode(array_values($byRequestType)) !!}
            },
            techPerformance: {
                labels: {!! json_encode($tL) !!},
                resolved: {!! json_encode($tR) !!},
                unresolved: {!! json_encode($tU) !!}
            }
        };
    </script>
</x-app-layout>
