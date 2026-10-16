<x-app-layout>
    <x-slot name="header">
        <div class="dash-header-wrap">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Dashboard') }}</h2>
            <div class="timeframe-wrap">
                <label for="timeframeFilter" class="timeframe-label">Timeframe:</label>
                <div class="relative-wrap">
                    <select id="timeframeFilter" 
                        class="timeframe-select"
                        onchange="window.location.href='?timeframe=' + this.value">
                        @foreach($timeframes as $val => $label)
                            <option value="{{ $val }}" {{ $selectedTimeframe === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <div class="select-icon-wrap">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="dash-container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
@endpush
            <script src="{{ asset('js/dashboard.js') }}?v={{ time() }}" defer></script>

            {{-- Row 1: KPI Overview --}}
            <div class="dk-grid-6" >
                <div class="dk-card dk-card-accent dk-clickable-card border-purple cursor-pointer" onclick="window.location='{{ route('tickets.index') }}'" title="Total Active: All requests in the selected timeframe.">
                    <div class="dk-kpi-icon bg-purple-light"><svg fill="none" stroke="#8b5cf6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                    <div class="dk-kpi-label">Total Active</div>
                    <div class="dk-kpi-value" id="kpi-total-val">{{ $totalActive }}</div>
                    <div class="dk-kpi-sub">Requests for selected timeframe</div>
                </div>
                <div class="dk-card dk-card-accent dk-clickable-card border-blue cursor-pointer" onclick="window.location='{{ route('tickets.index', ['status' => 'In Progress']) }}'" title="In Progress: The request is currently being worked on by the IT staff.">
                    <div class="dk-kpi-icon bg-prog-bg"><svg fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                    <div class="dk-kpi-label">In Progress</div>
                    <div class="dk-kpi-value" id="kpi-prog-val">{{ $byStatus['In Progress'] ?? 0 }}</div>
                    <div class="dk-kpi-sub" id="kpi-prog-sub">{{ $totalActive > 0 ? number_format((($byStatus['In Progress'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
                <div class="dk-card dk-card-accent dk-clickable-card border-amber cursor-pointer" onclick="window.location='{{ route('tickets.index', ['status' => 'Escalated']) }}'" title="Escalated: The request requires higher-level intervention or approvals.">
                    <div class="dk-kpi-icon bg-open-bg"><svg fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
                    <div class="dk-kpi-label">Escalated</div>
                    <div class="dk-kpi-value" id="kpi-esc-val">{{ $byStatus['Escalated'] ?? 0 }}</div>
                    <div class="dk-kpi-sub" id="kpi-esc-sub">{{ $totalActive > 0 ? number_format((($byStatus['Escalated'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
                <div class="dk-card dk-card-accent dk-clickable-card border-emerald cursor-pointer" onclick="window.location='{{ route('tickets.index', ['status' => 'Resolved']) }}'" title="Resolved: The request has been successfully completed.">
                    <div class="dk-kpi-icon bg-res-bg"><svg fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div class="dk-kpi-label">Resolved</div>
                    <div class="dk-kpi-value" id="kpi-res-val">{{ $byStatus['Resolved'] ?? 0 }}</div>
                    <div class="dk-kpi-sub" id="kpi-res-sub">{{ $totalActive > 0 ? number_format((($byStatus['Resolved'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
                <div class="dk-card dk-card-accent dk-clickable-card border-light cursor-pointer" onclick="window.location='{{ route('tickets.index', ['status' => 'Not Complete']) }}'" title="Not Complete: The request is not done due to limitations or other external factors.">
                    <div class="dk-kpi-icon bg-closed-bg"><svg fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div class="dk-kpi-label">Not Complete</div>
                    <div class="dk-kpi-value" id="kpi-not-val">{{ $byStatus['Not Complete'] ?? 0 }}</div>
                    <div class="dk-kpi-sub" id="kpi-not-sub">{{ $totalActive > 0 ? number_format((($byStatus['Not Complete'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
                <div class="dk-card dk-card-accent dk-clickable-card border-red cursor-pointer" onclick="window.location='{{ route('tickets.index', ['status' => 'Cancelled']) }}'" title="Cancelled: The request was cancelled by the user or staff.">
                    <div class="dk-kpi-icon bg-red-light"><svg fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg></div>
                    <div class="dk-kpi-label">Cancelled</div>
                    <div class="dk-kpi-value" id="kpi-cancel-val">{{ $byStatus['Cancelled'] ?? 0 }}</div>
                    <div class="dk-kpi-sub" id="kpi-cancel-sub">{{ $totalActive > 0 ? number_format((($byStatus['Cancelled'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
            </div>

            {{-- Row 2: Analytics Charts (Full Width for Readability) --}}
            <div class="dk-grid-2">
                <div class="dk-card">
                    <h3 class="card-title">Request Types</h3>
                    <div class="dk-chart-wrap"><canvas id="donutChart"></canvas></div>
                </div>
                <div class="dk-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h3 class="card-title mb-0" style="margin-bottom: 0;">IT Staff Performance</h3>
                        <select id="techStaffFilter" style="padding: 4px 8px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 0.75rem; background: var(--bg-input); color: var(--input-text); outline: none;">
                            <option value="">All Staff</option>
                        </select>
                    </div>
                    <div class="dk-chart-wrap"><canvas id="techChart"></canvas></div>
                </div>
            </div>

            {{-- Row 3: Actionable Data (Table + Volume) --}}
            <div class="dk-grid-70-30">
                {{-- Tickets Table --}}
                <div class="dk-table-wrap" id="recent-tickets-container">
                    @include('partials.dashboard.recent_tickets')
                </div>

                  <div class="flex-col-gap">
                      {{-- Request Volume --}}
                      <div class="dk-card p-4">
                        <h3 class="card-title">Request Volume</h3>
                        <div id="volume-container" class="flex-col-gap">
                            @foreach($metrics as $index => $metric)
                            <div style="display: flex; justify-content: space-between; align-items: center; {{ $index < count($metrics) - 1 ? 'border-bottom: 1px solid var(--border-color); padding-bottom: 8px;' : 'padding-bottom: 4px;' }}">
                                <span style="font-weight: 600; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">{{ $metric['label'] }}</span>
                                <div style="font-size: 1.1rem; color: {{ $metric['color'] }}; font-weight: 700;">{{ $metric['value'] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
      
                      {{-- Top Requestors / Department Requests (Toggle) --}}
                      <div class="dk-card p-4">
                          <div class="flex-between mb-4">
                              <h3 id="toggle-title" class="card-title mb-0">Top Requestors</h3>
                              <div class="toggle-bg">
                                  <button id="btn-requestors" onclick="toggleView('requestors')" style="padding: 4px 10px; border-radius: 4px; border: none; font-size: 0.7rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease; background: #3b82f6; color: #fff;">People</button>
                                  <button id="btn-departments" onclick="toggleView('departments')" style="padding: 4px 10px; border-radius: 4px; border: none; font-size: 0.7rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease; background: transparent; color: #9ca3af;">Dept</button>
                              </div>
                          </div>
                          <div id="requestors-container" style="width: 100%; overflow-x: auto;">
                              @include('partials.dashboard.top_requestors')
                          </div>
                          <div id="department-container" style="width: 100%; overflow-x: auto; display: none;">
                              @include('partials.dashboard.department_breakdown')
                          </div>
                      </div>
                  </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        @php 
            $tL=[]; $tR=[]; $tI=[]; $tE=[]; $tO=[]; $tN=[]; $tC=[];
            foreach($techPerformance as $c=>$d){ 
                $tL[]=$assistedByMap[$c]??$c; 
                $tR[]=$d['resolved'] ?? 0; 
                $tI[]=$d['in_progress'] ?? 0; 
                $tE[]=$d['escalated'] ?? 0; 
                $tO[]=$d['open'] ?? 0;
                $tN[]=$d['not_complete'] ?? 0;
                $tC[]=$d['cancelled'] ?? 0;
            } 
        @endphp
        window.dashboardData = {
            excludedTypes: {!! json_encode($excludedTypes) !!},
            requestTypes: {
                keys: {!! json_encode(array_keys($byRequestType)) !!},
                values: {!! json_encode(array_values($byRequestType)) !!}
            },
            techPerformance: {
                labels: {!! json_encode($tL) !!},
                resolved: {!! json_encode($tR) !!},
                in_progress: {!! json_encode($tI) !!},
                escalated: {!! json_encode($tE) !!},
                open: {!! json_encode($tO) !!},
                not_complete: {!! json_encode($tN) !!},
                cancelled: {!! json_encode($tC) !!}
            }
        };

        function toggleView(view) {
            const reqContainer = document.getElementById('requestors-container');
            const deptContainer = document.getElementById('department-container');
            const btnReq = document.getElementById('btn-requestors');
            const btnDept = document.getElementById('btn-departments');
            const title = document.getElementById('toggle-title');

            if (view === 'requestors') {
                reqContainer.style.display = '';
                deptContainer.style.display = 'none';
                title.textContent = 'Top Requestors';
                btnReq.style.background = '#3b82f6';
                btnReq.style.color = '#fff';
                btnDept.style.background = 'transparent';
                btnDept.style.color = '#9ca3af';
            } else {
                reqContainer.style.display = 'none';
                deptContainer.style.display = '';
                title.textContent = 'Top Departments';
                btnDept.style.background = '#3b82f6';
                btnDept.style.color = '#fff';
                btnReq.style.background = 'transparent';
                btnReq.style.color = '#9ca3af';
            }
        }
    </script>
</x-app-layout>



