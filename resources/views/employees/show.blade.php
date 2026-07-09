<x-app-layout>
    <x-slot name="header">
        <h2 id="employee-header-title" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('View Employee: ') }} <span style="color: #2563eb;">{{ $employee->first_name }} {{ $employee->last_name }}</span>
        </h2>
    </x-slot>

    <div id="employee-content-container" class="py-12" style="background-color: var(--th-bg); min-height: calc(100vh - 65px);">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="p-6 text-gray-900 dark:text-gray-100 dark:text-gray-100">

                    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <a href="{{ route('employees.index') }}" style="color: var(--text-light); text-decoration: none; font-size: 0.85rem; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                Back to Employees
                            </a>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="dk-btn dk-btn-warning" style="display: flex; align-items: center; gap: 6px;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                Edit Employee
                            </a>
                        </div>
                        <div style="display: flex; gap: 6px; align-items: center;">
                            @if($prevEmployee)
                                <a href="{{ route('employees.show', $prevEmployee->id) }}" class="ajax-nav-btn" style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-primary); text-decoration: none; transition: all 0.15s;" title="{{ $prevEmployee->full_name }}" onmouseover="this.style.borderColor='#2563eb'; this.style.color='#2563eb';" onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)';">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                </a>
                            @else
                                <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-muted); opacity: 0.4; cursor: not-allowed;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                </span>
                            @endif
                            <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">{{ $employee->last_name }}, {{ $employee->first_name }}</span>
                            @if($nextEmployee)
                                <a href="{{ route('employees.show', $nextEmployee->id) }}" class="ajax-nav-btn" style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-primary); text-decoration: none; transition: all 0.15s;" title="{{ $nextEmployee->full_name }}" onmouseover="this.style.borderColor='#2563eb'; this.style.color='#2563eb';" onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)';">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @else
                                <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-muted); opacity: 0.4; cursor: not-allowed;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div style="background: var(--panel-bg); border-radius: 8px; border: 1px solid var(--border-color); padding: 25px;">
                        
                        {{-- Personal Information --}}
                        <div style="margin-bottom: 30px;">
                            <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Personal Information</h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                                <div>
                                    <span style="display: block; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 4px;">First Name</span>
                                    <div style="font-size: 1rem; color: var(--text-primary); font-weight: 500;">{{ $employee->first_name }}</div>
                                </div>
                                <div>
                                    <span style="display: block; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 4px;">Middle Name</span>
                                    <div style="font-size: 1rem; color: var(--text-primary); font-weight: 500;">{{ $employee->middle_name ?? '—' }}</div>
                                </div>
                                <div>
                                    <span style="display: block; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 4px;">Last Name</span>
                                    <div style="font-size: 1rem; color: var(--text-primary); font-weight: 500;">{{ $employee->last_name }}</div>
                                </div>
                                <div>
                                    <span style="display: block; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 4px;">Suffix</span>
                                    <div style="font-size: 1rem; color: var(--text-primary); font-weight: 500;">{{ $employee->suffix ?? '—' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Employment Details --}}
                        <div>
                            <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Employment Details</h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                                <div>
                                    <span style="display: block; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 4px;">Employee ID (NFP)</span>
                                    <div style="font-size: 1rem; color: var(--text-primary); font-weight: 500;">
                                        @if($employee->nfp_id)
                                            <span style="background: #eff6ff; color: #2563eb; padding: 2px 8px; border-radius: 4px; font-family: monospace; border: 1px solid #bfdbfe;">
                                                {{ $employee->nfp_id }}
                                            </span>
                                        @else
                                            —
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <span style="display: block; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 4px;">Department</span>
                                    <div style="font-size: 1rem; color: var(--text-primary); font-weight: 500;">{{ $employee->department ?? '—' }}</div>
                                </div>
                                <div>
                                    <span style="display: block; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 4px;">Position</span>
                                    <div style="font-size: 1rem; color: var(--text-primary); font-weight: 500;">{{ $employee->position ?? '—' }}</div>
                                </div>
                                <div>
                                    <span style="display: block; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 4px;">Branch</span>
                                    <div style="font-size: 1rem; color: var(--text-primary); font-weight: 500;">{{ $employee->branch ?? '—' }}</div>
                                </div>
                                <div>
                                    <span style="display: block; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 4px;">Contact No.</span>
                                    <div style="font-size: 1rem; color: var(--text-primary); font-weight: 500;">{{ $employee->contact_no ?? '—' }}</div>
                                </div>
                                <div>
                                    <span style="display: block; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 4px;">Status</span>
                                    <div style="font-size: 1rem; color: var(--text-primary); font-weight: 500;">
                                        @if($employee->employment_status === 'Active')
                                            <span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 4px; font-size: 0.85rem;">Active</span>
                                        @else
                                            <span style="background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 4px; font-size: 0.85rem;">Resigned</span>
                                            @if($employee->resigned_date)
                                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">as of {{ \Carbon\Carbon::parse($employee->resigned_date)->format('M d, Y') }}</div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Ticket Statistics --}}
                        @if(isset($stats))
                        <div style="margin-top: 30px;">
                            <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Ticket Statistics</h3>
                            <div>
                                <strong class="dk-text-label" style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Request Frequency</strong>
                                <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 6px;">
                                    <a href="{{ route('tickets.index', ['requested_by' => $employee->full_name, 'date_filter' => 'today']) }}" style="background: var(--th-bg); border: 1px solid var(--border-color); padding: 5px 10px; border-radius: 6px; display: flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='var(--border-color)'" title="View Today's Tickets">
                                        <span style="color: var(--text-secondary); font-size: 0.75rem;">Today</span>
                                        <b style="color: #3b82f6; font-size: 1rem;">{{ $stats['today'] }}</b>
                                    </a>
                                    <a href="{{ route('tickets.index', ['requested_by' => $employee->full_name, 'date_filter' => 'this_week']) }}" style="background: var(--th-bg); border: 1px solid var(--border-color); padding: 5px 10px; border-radius: 6px; display: flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='#6366f1'" onmouseout="this.style.borderColor='var(--border-color)'" title="View This Week's Tickets">
                                        <span style="color: var(--text-secondary); font-size: 0.75rem;">Week</span>
                                        <b style="color: #6366f1; font-size: 1rem;">{{ $stats['this_week'] }}</b>
                                    </a>
                                    <a href="{{ route('tickets.index', ['requested_by' => $employee->full_name, 'date_filter' => 'this_month']) }}" style="background: var(--th-bg); border: 1px solid var(--border-color); padding: 5px 10px; border-radius: 6px; display: flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='#8b5cf6'" onmouseout="this.style.borderColor='var(--border-color)'" title="View This Month's Tickets">
                                        <span style="color: var(--text-secondary); font-size: 0.75rem;">Month</span>
                                        <b style="color: #8b5cf6; font-size: 1rem;">{{ $stats['this_month'] }}</b>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('employee-content-container');
            if (!container) return;

            container.addEventListener('click', function(e) {
                const btn = e.target.closest('.ajax-nav-btn');
                if (!btn) return;

                e.preventDefault();
                const url = btn.href;

                container.style.opacity = '0.5';
                container.style.pointerEvents = 'none';

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const newHeader = doc.getElementById('employee-header-title');
                        const oldHeader = document.getElementById('employee-header-title');
                        if (newHeader && oldHeader) {
                            oldHeader.innerHTML = newHeader.innerHTML;
                        }

                        const newContent = doc.getElementById('employee-content-container');
                        if (newContent) {
                            container.innerHTML = newContent.innerHTML;
                        }

                        container.style.opacity = '1';
                        container.style.pointerEvents = 'auto';

                        window.history.pushState({}, '', url);
                        if (doc.title) document.title = doc.title;
                    })
                    .catch(err => {
                        console.error('AJAX navigation failed', err);
                        window.location.href = url;
                    });
            });

            window.addEventListener('popstate', function() {
                window.location.reload();
            });
        });
    </script>
</x-app-layout>
