<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Dashboard') }}</h2>
            <div style="display: flex; align-items: center; gap: 10px;">
                <label for="timeframeFilter" style="font-size: 0.85rem; font-weight: 600; color: #6b7280;">Timeframe:</label>
                <div style="position: relative;">
                    <select id="timeframeFilter" 
                        style="appearance: none; padding: 6px 32px 6px 12px; border: 1px solid var(--border-color); border-radius: 6px; background-color: var(--bg-card); color: var(--text-primary); font-size: 0.85rem; font-weight: 600; cursor: pointer; outline: none; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);"
                        onchange="window.location.href='?timeframe=' + this.value">
                        @foreach($timeframes as $val => $label)
                            <option value="{{ $val }}" {{ $selectedTimeframe === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <div style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #9ca3af;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div style="padding: 10px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])

            {{-- Row 1: KPI Overview --}}
            <div class="dk-grid-5">
                <div class="dk-card dk-card-accent dk-clickable-card" style="border-left-color: #8b5cf6; cursor: pointer;" onclick="window.location='{{ route('tickets.index') }}'">
                    <div class="dk-kpi-icon" style="background: rgba(139, 92, 246, 0.15);"><svg fill="none" stroke="#8b5cf6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                    <div class="dk-kpi-label">Total Active</div>
                    <div class="dk-kpi-value">{{ $totalActive }}</div>
                    <div class="dk-kpi-sub">Requests for selected timeframe</div>
                </div>
                <div class="dk-card dk-card-accent dk-clickable-card" style="border-left-color: #3b82f6; cursor: pointer;" onclick="window.location='{{ route('tickets.index', ['status' => 'In Progress']) }}'">
                    <div class="dk-kpi-icon" style="background: var(--badge-prog-bg);"><svg fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                    <div class="dk-kpi-label">In Progress</div>
                    <div class="dk-kpi-value">{{ $byStatus['In Progress'] ?? 0 }}</div>
                    <div class="dk-kpi-sub">{{ $totalActive > 0 ? number_format((($byStatus['In Progress'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
                <div class="dk-card dk-card-accent dk-clickable-card" style="border-left-color: #f59e0b; cursor: pointer;" onclick="window.location='{{ route('tickets.index', ['status' => 'Escalated']) }}'">
                    <div class="dk-kpi-icon" style="background: var(--badge-open-bg);"><svg fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
                    <div class="dk-kpi-label">Escalated</div>
                    <div class="dk-kpi-value">{{ $byStatus['Escalated'] ?? 0 }}</div>
                    <div class="dk-kpi-sub">{{ $totalActive > 0 ? number_format((($byStatus['Escalated'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
                <div class="dk-card dk-card-accent dk-clickable-card" style="border-left-color: #10b981; cursor: pointer;" onclick="window.location='{{ route('tickets.index', ['status' => 'Resolved']) }}'">
                    <div class="dk-kpi-icon" style="background: var(--badge-res-bg);"><svg fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div class="dk-kpi-label">Resolved</div>
                    <div class="dk-kpi-value">{{ $byStatus['Resolved'] ?? 0 }}</div>
                    <div class="dk-kpi-sub">{{ $totalActive > 0 ? number_format((($byStatus['Resolved'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
                <div class="dk-card dk-card-accent dk-clickable-card" style="border-left-color: var(--text-light); cursor: pointer;" onclick="window.location='{{ route('tickets.index', ['status' => 'Not Complete']) }}'">
                    <div class="dk-kpi-icon" style="background: var(--badge-closed-bg);"><svg fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div class="dk-kpi-label">Not Complete</div>
                    <div class="dk-kpi-value">{{ $byStatus['Not Complete'] ?? 0 }}</div>
                    <div class="dk-kpi-sub">{{ $totalActive > 0 ? number_format((($byStatus['Not Complete'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
            </div>

            {{-- Row 2: Analytics Charts (Full Width for Readability) --}}
            <div class="dk-grid-2">
                <div class="dk-card">
                    <h3 style="margin: 0 0 16px 0; font-size: 0.95rem; color: var(--text-primary); font-weight: 600;">Request Types</h3>
                    <div class="dk-chart-wrap"><canvas id="donutChart"></canvas></div>
                </div>
                <div class="dk-card">
                    <h3 style="margin: 0 0 16px 0; font-size: 0.95rem; color: var(--text-primary); font-weight: 600;">Technician Performance</h3>
                    <div class="dk-chart-wrap"><canvas id="techChart"></canvas></div>
                </div>
            </div>

            {{-- Row 3: Actionable Data (Table + Volume) --}}
            <div class="dk-grid-70-30" style="align-items: flex-start;">
                {{-- Tickets Table --}}
                <div class="dk-table-wrap" id="recent-tickets-container">
                    <div style="padding: 8px 12px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="dk-section-title" style="margin: 0; color: var(--text-primary);">Recent Tickets</h3>
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
                                    <td colspan="6" style="text-align: center; color: #9ca3af; padding: 10px;">No tickets found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="dk-pagination">
                        {{ $recentTickets->links() }}
                    </div>
                </div>

                  <div style="display: flex; flex-direction: column; gap: 12px;">
                      {{-- Request Volume --}}
                      <div class="dk-card" style="padding: 16px;">
                        <h3 style="margin: 0 0 16px 0; font-size: 0.95rem; color: var(--text-primary); font-weight: 600;">Request Volume</h3>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @foreach($metrics as $index => $metric)
                            <div style="display: flex; justify-content: space-between; align-items: center; {{ $index < count($metrics) - 1 ? 'border-bottom: 1px solid var(--border-color); padding-bottom: 8px;' : 'padding-bottom: 4px;' }}">
                                <span style="font-weight: 600; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">{{ $metric['label'] }}</span>
                                <div style="font-size: 1.1rem; color: {{ $metric['color'] }}; font-weight: 700;">{{ $metric['value'] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
      
                      {{-- Top Requestors --}}
                      <div class="dk-card" style="padding: 16px;">
                          <h3 style="margin: 0 0 16px 0; font-size: 0.95rem; color: var(--text-primary); font-weight: 600;">Top Requestors</h3>
                          <div style="width: 100%; overflow-x: auto;">
                              <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">
                                  <thead>
                                      <tr style="border-bottom: 1px solid var(--border-color); color: var(--text-secondary); text-align: left;">
                                          <th style="padding-bottom: 8px; font-weight: 600; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px;">Name</th>
                                          <th style="padding-bottom: 8px; text-align: center; font-weight: 600; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px;">Total Requests</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach($topRequestors as $req)
                                      <tr style="border-bottom: 1px solid var(--border-color);">
                                          <td style="padding: 10px 0; font-weight: 600; color: var(--text-primary);">
                                              <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;">
                                                  {{ $req->requested_by }}
                                              </div>
                                          </td>
                                          <td style="padding: 10px 0; text-align: center; color: #3b82f6; font-weight: 700;">{{ $req->total }}</td>
                                      </tr>
                                      @endforeach
                                  </tbody>
                              </table>
                              @if($topRequestors->isEmpty())
                                  <div style="text-align: center; color: var(--text-muted); font-size: 0.85rem; padding: 15px 10px;">No requests found.</div>
                              @endif
                          </div>
                      </div>
                  </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        @php 
            $tL=[]; $tR=[]; $tI=[]; $tE=[]; 
            foreach($techPerformance as $c=>$d){ 
                $tL[]=$assistedByMap[$c]??$c; 
                $tR[]=$d['resolved']; 
                $tI[]=$d['in_progress']; 
                $tE[]=$d['escalated']; 
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
                in_progress: {!! json_encode($tI) !!},
                escalated: {!! json_encode($tE) !!}
            }
        };
    </script>
</x-app-layout>
