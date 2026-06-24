<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Dashboard') }}</h2>
    </x-slot>

    <div style="padding: 10px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <style>
                .dk-grid-5 { display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 12px; }
                .dk-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px; }
                .dk-grid-70-30 { display: grid; grid-template-columns: 2.2fr 1fr; gap: 12px; margin-bottom: 12px; align-items: stretch; }
                
                .dk-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: center; }
                .dk-card-accent { border-left: 4px solid; }
                .dk-kpi-label { font-size: 0.7rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; }
                .dk-kpi-value { font-size: 1.4rem; font-weight: 800; color: #111827; line-height: 1.1; margin: 2px 0; }
                .dk-kpi-sub { font-size: 0.65rem; font-weight: 500; color: #9ca3af; }
                .dk-kpi-icon { position: absolute; top: 12px; right: 12px; width: 30px; height: 30px; border-radius: 6px; display: flex; align-items: center; justify-content: center; }
                .dk-kpi-icon svg { width: 16px; height: 16px; }
                
                .dk-section-title { font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }
                .dk-chart-wrap { position: relative; height: 180px; }
                
                /* Tickets Table Styles */
                .dk-table-wrap { overflow-x: auto; background: #fff; border-radius: 8px; border: 1px solid #e5e7eb; height: 100%; display: flex; flex-direction: column; }
                .dk-table { width: 100%; border-collapse: collapse; font-size: 0.75rem; flex-grow: 1; }
                .dk-table th { text-align: left; font-weight: 700; color: #6b7280; text-transform: uppercase; padding: 6px 10px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
                .dk-table td { padding: 6px 10px; border-bottom: 1px solid #f3f4f6; color: #374151; }
                .dk-table tbody tr { transition: background-color 0.15s; cursor: pointer; }
                .dk-table tbody tr:hover { background-color: #f8fafc; }
                .dk-badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 0.65rem; font-weight: 600; }
                .dk-badge-open { background: #fffbeb; color: #f59e0b; }
                .dk-badge-progress { background: #eff6ff; color: #3b82f6; }
                .dk-badge-resolved { background: #ecfdf5; color: #10b981; }
                .dk-badge-closed { background: #f3f4f6; color: #6b7280; }
                .dk-pagination { padding: 4px 10px; border-top: 1px solid #e5e7eb; background: #fff; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; font-size: 0.7rem;}
            </style>

            {{-- Row 1: KPI Overview --}}
            <div class="dk-grid-5">
                <div class="dk-card dk-card-accent" style="border-left-color: #3b82f6;">
                    <div class="dk-kpi-icon" style="background: #eff6ff;"><svg fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                    <div class="dk-kpi-label">Total Active</div>
                    <div class="dk-kpi-value">{{ $totalActive }}</div>
                    <div class="dk-kpi-sub">Total recorded in system</div>
                </div>
                <div class="dk-card dk-card-accent" style="border-left-color: #3b82f6;">
                    <div class="dk-kpi-icon" style="background: #eff6ff;"><svg fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                    <div class="dk-kpi-label">In Progress</div>
                    <div class="dk-kpi-value">{{ $byStatus['In Progress'] ?? 0 }}</div>
                    <div class="dk-kpi-sub">{{ $totalActive > 0 ? number_format((($byStatus['In Progress'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
                <div class="dk-card dk-card-accent" style="border-left-color: #f59e0b;">
                    <div class="dk-kpi-icon" style="background: #fffbeb;"><svg fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
                    <div class="dk-kpi-label">Escalated</div>
                    <div class="dk-kpi-value">{{ $byStatus['Escalated'] ?? 0 }}</div>
                    <div class="dk-kpi-sub">{{ $totalActive > 0 ? number_format((($byStatus['Escalated'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
                <div class="dk-card dk-card-accent" style="border-left-color: #10b981;">
                    <div class="dk-kpi-icon" style="background: #ecfdf5;"><svg fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div class="dk-kpi-label">Resolved</div>
                    <div class="dk-kpi-value">{{ $byStatus['Resolved'] ?? 0 }}</div>
                    <div class="dk-kpi-sub">{{ $totalActive > 0 ? number_format((($byStatus['Resolved'] ?? 0) / $totalActive) * 100, 1) : '0.0' }}%</div>
                </div>
                <div class="dk-card dk-card-accent" style="border-left-color: #6b7280;">
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
                <div class="dk-table-wrap">
                    <div style="padding: 8px 12px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="dk-section-title" style="margin: 0; color: #111827;">Recent Tickets</h3>
                        <a href="{{ route('tickets.create') }}" style="background-color: #2563eb; color: #fff; padding: 4px 12px; border-radius: 4px; font-size: 0.7rem; font-weight: 600; text-decoration: none;">+ New Ticket</a>
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
                                <tr onclick="window.location='{{ route('tickets.show', $ticket->id) }}'">
                                    <td style="font-weight: 600; color: #3b82f6;">{{ $ticket->ticket_no }}</td>
                                    <td>{{ $ticket->request_type }}</td>
                                    <td>
                                        {{ Str::limit($ticket->requested_by, 20) }}
                                        @if($ticket->branch && strtoupper($ticket->branch) !== 'HEAD OFFICE')
                                            <span class="dk-badge" style="background: #e0e7ff; color: #4338ca; margin-left: 4px; font-size: 0.6rem; padding: 2px 6px;">Remote</span>
                                        @endif
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
                                    <td style="color: #6b7280;">{{ $ticket->created_at->format('M d, Y') }}</td>
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
                        <div style="background: #f8fafc; padding: 8px 12px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #e2e8f0; flex: 1;">
                            <div class="dk-kpi-label" style="margin: 0;">Today</div>
                            <div class="dk-kpi-value" style="margin: 0; font-size: 1.1rem; color: #3b82f6;">{{ $todayCount }} <span style="font-size: 0.75rem; color: #9ca3af; font-weight: 600;">({{ $totalActive > 0 ? number_format(($todayCount / $totalActive) * 100, 1) : '0.0' }}%)</span></div>
                        </div>
                        <div style="background: #f8fafc; padding: 8px 12px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #e2e8f0; flex: 1;">
                            <div class="dk-kpi-label" style="margin: 0;">This Week</div>
                            <div class="dk-kpi-value" style="margin: 0; font-size: 1.1rem; color: #6366f1;">{{ $weekCount }} <span style="font-size: 0.75rem; color: #9ca3af; font-weight: 600;">({{ $totalActive > 0 ? number_format(($weekCount / $totalActive) * 100, 1) : '0.0' }}%)</span></div>
                        </div>
                        <div style="background: #f8fafc; padding: 8px 12px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #e2e8f0; flex: 1;">
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
        const ff = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        const gc = '#f3f4f6';
        const base = {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1f2937', titleFont: { size: 10, family: ff }, bodyFont: { size: 10, family: ff }, padding: 8, cornerRadius: 5 } },
            scales: { x: { grid: { display: false }, ticks: { font: { size: 9, family: ff }, color: '#9ca3af' } }, y: { grid: { color: gc }, ticks: { font: { size: 9, family: ff }, color: '#9ca3af', precision: 0 }, beginAtZero: true } }
        };

        // Donut Chart - Request Types (With numbers inside the chart)
        const dc = ['#3b82f6','#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#ef4444','#14b8a6'];
        new Chart(document.getElementById('donutChart'), { type: 'doughnut', data: { labels: {!! json_encode(array_keys($byRequestType)) !!}, datasets: [{ data: {!! json_encode(array_values($byRequestType)) !!}, backgroundColor: dc.slice(0, {{ count($byRequestType) }}), borderWidth: 2, borderColor: '#fff', hoverOffset: 4 }] }, options: { responsive: true, maintainAspectRatio: false, cutout: '55%', plugins: { legend: { position: 'right', labels: { font: { size: 10, family: ff }, padding: 10, boxWidth: 12 } }, tooltip: { ...base.plugins.tooltip, callbacks: { label: function(c) { const t = c.dataset.data.reduce((a,b)=>a+b,0); return c.label+': '+c.parsed+' tickets ('+(t>0?((c.parsed/t)*100).toFixed(1):'0.0')+'%)'; } } } } } });

        // Technician Performance - Horizontal Bar (With numbers on tooltip)
        @php $tL=[]; $tR=[]; $tU=[]; foreach($techPerformance as $c=>$d){ $tL[]=$assistedByMap[$c]??$c; $tR[]=$d['resolved']; $tU[]=$d['unresolved']; } @endphp
        new Chart(document.getElementById('techChart'), { type: 'bar', data: { labels: {!! json_encode($tL) !!}, datasets: [ { label: 'Resolved', data: {!! json_encode($tR) !!}, backgroundColor: '#10b981', borderRadius: 4, barPercentage: 0.6 }, { label: 'Unresolved', data: {!! json_encode($tU) !!}, backgroundColor: '#f59e0b', borderRadius: 4, barPercentage: 0.6 } ] }, options: { ...base, indexAxis: 'y', plugins: { ...base.plugins, legend: { display: true, position: 'top', labels: { font: { size: 10, family: ff }, boxWidth: 12, padding: 8 } }, tooltip: { ...base.plugins.tooltip, callbacks: { label: function(c) { return c.dataset.label + ': ' + c.parsed.x + ' tickets'; } } } }, scales: { x: { ...base.scales.y, grid: { color: gc } }, y: { ...base.scales.x, grid: { display: false } } } } });
    </script>
</x-app-layout>
