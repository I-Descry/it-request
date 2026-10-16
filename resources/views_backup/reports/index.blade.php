<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Generate Reports') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 dark:text-gray-100">

<style>
    .t-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 4px;
        letter-spacing: 0.01em;
    }
    .t-input {
        display: block;
        width: 100%;
        padding: 7px 10px;
        font-size: 0.875rem;
        color: var(--text-primary);
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 6px;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .t-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
    .t-section { margin-bottom: 14px; }
    .t-section-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 8px;
        padding-bottom: 4px;
        border-bottom: 1px solid var(--border-color);
    }
    .t-grid { display: grid; gap: 10px; }
    .t-grid-4 { grid-template-columns: repeat(4, 1fr); }
    .t-grid-3 { grid-template-columns: repeat(3, 1fr); }
    .t-grid-2 { grid-template-columns: repeat(2, 1fr); }

    .report-type-card {
        padding: 16px;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        background: var(--bg-card);
    }
    .report-type-card:hover {
        border-color: #3b82f6;
        background: rgba(59, 130, 246, 0.05);
    }
    .report-type-card.active {
        border-color: #2563eb;
        background: rgba(37, 99, 235, 0.08);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
    .report-type-card .icon {
        font-size: 1.8rem;
        margin-bottom: 6px;
    }
    .report-type-card .title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .report-type-card .desc {
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .filter-group { display: none; }
    .filter-group.active { display: block; }

    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-export:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .btn-export:active { transform: translateY(0); }

    .btn-excel {
        background: linear-gradient(135deg, #059669, #10b981);
        color: #fff;
    }
    .btn-pdf {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        color: #fff;
    }
    .btn-both {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: #fff;
    }

    .export-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        padding-top: 14px;
        border-top: 1px solid var(--border-color);
        margin-top: 14px;
        flex-wrap: wrap;
    }

    .checkbox-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
    }
    .checkbox-row input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--border-color-focus);
        cursor: pointer;
    }
    .checkbox-row label {
        font-size: 0.8rem;
        color: var(--text-primary);
        cursor: pointer;
    }

    /* ── Filter Columns ── */
    .filter-col .filter-col-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
    }
    .filter-col .filter-col-list {
        max-height: 170px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .filter-col .filter-col-list::-webkit-scrollbar { width: 3px; }
    .filter-col .filter-col-list::-webkit-scrollbar-track { background: transparent; }
    .filter-col .filter-col-list::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 3px; }

    .cb-option {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 6px;
        font-size: 0.8rem;
        color: var(--text-primary);
        cursor: pointer;
        border-radius: 5px;
        transition: background 0.15s;
    }
    .cb-option:hover { background: var(--bg-hover); }
    .cb-option input[type="checkbox"] {
        width: 15px;
        height: 15px;
        accent-color: var(--border-color-focus);
        cursor: pointer;
        flex-shrink: 0;
    }
    .select-all-label {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        white-space: nowrap;
        padding: 2px 6px;
        border-radius: 4px;
        transition: background 0.15s;
    }
    .select-all-label:hover { background: var(--bg-hover); }
    .select-all-label input[type="checkbox"] {
        width: 13px;
        height: 13px;
        accent-color: var(--border-color-focus);
        cursor: pointer;
    }
</style>

                    <form id="reportForm" method="GET" action="">
                        {{-- Report Type Selection --}}
                        <div class="t-section">
                            <div class="t-section-title">Select Report Type</div>
                            <div class="t-grid t-grid-3">
                                <div class="report-type-card active" data-type="tickets" onclick="selectReportType('tickets')">
                                    <div class="icon">🎫</div>
                                    <div class="title">IT Requests</div>
                                    <div class="desc">Tickets, status, request types & staff assignments</div>
                                </div>
                                <div class="report-type-card" data-type="employees" onclick="selectReportType('employees')">
                                    <div class="icon">👥</div>
                                    <div class="title">Employees</div>
                                    <div class="desc">Employee directory, positions & departments</div>
                                </div>
                                <div class="report-type-card" data-type="sso_accounts" onclick="selectReportType('sso_accounts')">
                                    <div class="icon">🔐</div>
                                    <div class="title">SSO Accounts</div>
                                    <div class="desc">Account list, types & statuses</div>
                                </div>
                            </div>
                            <input type="hidden" name="report_type" id="report_type" value="tickets">
                        </div>

                        {{-- Date Range (shared) --}}
                        <div class="t-section">
                            <div class="t-section-title">Date Range</div>
                            <div class="t-grid t-grid-2">
                                <div>
                                    <label for="date_from" class="t-label">From</label>
                                    <input type="date" name="date_from" id="date_from" class="t-input">
                                </div>
                                <div>
                                    <label for="date_to" class="t-label">To</label>
                                    <input type="date" name="date_to" id="date_to" class="t-input">
                                </div>
                            </div>
                        </div>

                        {{-- Ticket Filters --}}
                        <div class="filter-group active" id="filters-tickets">
                            <div class="t-section">
                                <div class="t-section-title">Ticket Filters</div>
                                <div class="t-grid t-grid-4">
                                    <div class="filter-col">
                                        <div class="filter-col-header">
                                            <span class="t-label" style="margin-bottom:0">Status</span>
                                            <label class="select-all-label"><input type="checkbox" checked onchange="toggleAll(this, 'status[]')"> All</label>
                                        </div>
                                        <div class="filter-col-list">
                                            @foreach ($ticketStatuses as $s)
                                                <label class="cb-option"><input type="checkbox" name="status[]" value="{{ $s }}" checked> {{ $s }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="filter-col">
                                        <div class="filter-col-header">
                                            <span class="t-label" style="margin-bottom:0">Request Type</span>
                                            <label class="select-all-label"><input type="checkbox" checked onchange="toggleAll(this, 'request_type[]')"> All</label>
                                        </div>
                                        <div class="filter-col-list">
                                            @foreach ($requestTypes as $rt)
                                                <label class="cb-option"><input type="checkbox" name="request_type[]" value="{{ $rt }}" checked> {{ $rt }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="filter-col">
                                        <div class="filter-col-header">
                                            <span class="t-label" style="margin-bottom:0">Assisted By</span>
                                            <label class="select-all-label"><input type="checkbox" checked onchange="toggleAll(this, 'assisted_by[]')"> All</label>
                                        </div>
                                        <div class="filter-col-list">
                                            <label class="cb-option"><input type="checkbox" name="assisted_by[]" value="IT03" checked> Tristan Railey Tan</label>
                                            <label class="cb-option"><input type="checkbox" name="assisted_by[]" value="IT04" checked> John Paul Villacorta</label>
                                            <label class="cb-option"><input type="checkbox" name="assisted_by[]" value="Both" checked> Both</label>
                                        </div>
                                    </div>
                                    <div class="filter-col">
                                        <div class="filter-col-header">
                                            <span class="t-label" style="margin-bottom:0">Department</span>
                                            <label class="select-all-label"><input type="checkbox" checked onchange="toggleAll(this, 'department[]')"> All</label>
                                        </div>
                                        <div class="filter-col-list">
                                            @foreach ($ticketDepartments as $d)
                                                <label class="cb-option"><input type="checkbox" name="department[]" value="{{ $d }}" checked> {{ $d }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="checkbox-row" style="margin-top: 10px;">
                                    <input type="checkbox" name="include_archived" id="include_archived" value="1">
                                    <label for="include_archived">Include Archived Tickets</label>
                                </div>
                            </div>
                        </div>

                        {{-- Employee Filters --}}
                        <div class="filter-group" id="filters-employees">
                            <div class="t-section">
                                <div class="t-section-title">Employee Filters</div>
                                <div class="t-grid t-grid-3">
                                    <div class="filter-col">
                                        <div class="filter-col-header">
                                            <span class="t-label" style="margin-bottom:0">Employment Status</span>
                                            <label class="select-all-label"><input type="checkbox" checked onchange="toggleAll(this, 'employment_status[]')"> All</label>
                                        </div>
                                        <div class="filter-col-list">
                                            @foreach ($employeeStatuses as $es)
                                                <label class="cb-option"><input type="checkbox" name="employment_status[]" value="{{ $es }}" checked> {{ $es }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="filter-col">
                                        <div class="filter-col-header">
                                            <span class="t-label" style="margin-bottom:0">Department</span>
                                            <label class="select-all-label"><input type="checkbox" checked onchange="toggleAll(this, 'emp_department[]')"> All</label>
                                        </div>
                                        <div class="filter-col-list">
                                            @foreach ($employeeDepartments as $d)
                                                <label class="cb-option"><input type="checkbox" name="emp_department[]" value="{{ $d }}" checked> {{ $d }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="filter-col">
                                        <div class="filter-col-header">
                                            <span class="t-label" style="margin-bottom:0">Branch</span>
                                            <label class="select-all-label"><input type="checkbox" checked onchange="toggleAll(this, 'branch[]')"> All</label>
                                        </div>
                                        <div class="filter-col-list">
                                            @foreach ($employeeBranches as $b)
                                                <label class="cb-option"><input type="checkbox" name="branch[]" value="{{ $b }}" checked> {{ $b }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SSO Filters --}}
                        <div class="filter-group" id="filters-sso_accounts">
                            <div class="t-section">
                                <div class="t-section-title">SSO Account Filters</div>
                                <div class="t-grid t-grid-3">
                                    <div class="filter-col">
                                        <div class="filter-col-header">
                                            <span class="t-label" style="margin-bottom:0">Status</span>
                                            <label class="select-all-label"><input type="checkbox" checked onchange="toggleAll(this, 'sso_status[]')"> All</label>
                                        </div>
                                        <div class="filter-col-list">
                                            @foreach ($ssoStatuses as $ss)
                                                <label class="cb-option"><input type="checkbox" name="sso_status[]" value="{{ $ss }}" checked> {{ $ss }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="filter-col">
                                        <div class="filter-col-header">
                                            <span class="t-label" style="margin-bottom:0">Account Type</span>
                                            <label class="select-all-label"><input type="checkbox" checked onchange="toggleAll(this, 'account_type[]')"> All</label>
                                        </div>
                                        <div class="filter-col-list">
                                            @foreach ($ssoAccountTypes as $at)
                                                <label class="cb-option"><input type="checkbox" name="account_type[]" value="{{ $at }}" checked> {{ $at }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="filter-col">
                                        <div class="filter-col-header">
                                            <span class="t-label" style="margin-bottom:0">Department</span>
                                            <label class="select-all-label"><input type="checkbox" checked onchange="toggleAll(this, 'sso_department[]')"> All</label>
                                        </div>
                                        <div class="filter-col-list">
                                            @foreach ($ssoDepartments as $d)
                                                <label class="cb-option"><input type="checkbox" name="sso_department[]" value="{{ $d }}" checked> {{ $d }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Export Buttons --}}
                        <div class="export-actions">
                            <button type="button" class="btn-export btn-excel" onclick="submitReport('excel')">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Download Excel
                            </button>
                            <button type="button" class="btn-export btn-pdf" onclick="submitReport('pdf')">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Download PDF
                            </button>
                            <button type="button" class="btn-export btn-both" onclick="submitReport('both')">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Download Both
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function selectReportType(type) {
        // Update hidden input
        document.getElementById('report_type').value = type;

        // Update card styles
        document.querySelectorAll('.report-type-card').forEach(card => {
            card.classList.toggle('active', card.dataset.type === type);
        });

        // Show/hide filter groups and disable/enable their inputs
        document.querySelectorAll('.filter-group').forEach(fg => {
            fg.classList.remove('active');
            // Disable all inputs in hidden groups so they aren't submitted
            fg.querySelectorAll('input, select, textarea').forEach(input => {
                input.disabled = true;
            });
        });
        
        const activeGroup = document.getElementById('filters-' + type);
        if (activeGroup) {
            activeGroup.classList.add('active');
            // Enable inputs for the active group
            activeGroup.querySelectorAll('input, select, textarea').forEach(input => {
                input.disabled = false;
            });
        }
    }

    function submitReport(format) {
        const form = document.getElementById('reportForm');
        
        if (format === 'both') {
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();
            
            // Download excel via hidden iframe
            const iframeExcel = document.createElement('iframe');
            iframeExcel.style.display = 'none';
            iframeExcel.src = "{{ route('reports.excel') }}?" + params;
            document.body.appendChild(iframeExcel);
            
            // Download PDF via hidden iframe with slight delay
            setTimeout(() => {
                const iframePdf = document.createElement('iframe');
                iframePdf.style.display = 'none';
                iframePdf.src = "{{ route('reports.pdf') }}?" + params;
                document.body.appendChild(iframePdf);
                
                // Cleanup iframes after a while
                setTimeout(() => {
                    if (document.body.contains(iframeExcel)) document.body.removeChild(iframeExcel);
                    if (document.body.contains(iframePdf)) document.body.removeChild(iframePdf);
                }, 10000);
            }, 1000);
            
            return;
        }
        
        const routes = {
            excel: "{{ route('reports.excel') }}",
            pdf: "{{ route('reports.pdf') }}"
        };
        form.action = routes[format];
        form.target = ""; // Current window
        form.submit();
    }

    // Toggle all checkboxes in a group
    function toggleAll(masterCheckbox, groupName) {
        const checkboxes = document.querySelectorAll('input[name="' + groupName + '"]');
        checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
    }

    // Sync the "All" master checkbox when individual items change
    function syncSelectAll(checkbox) {
        const name = checkbox.name;
        const allCbs = document.querySelectorAll('input[name="' + name + '"]');
        const allChecked = Array.from(allCbs).every(cb => cb.checked);
        // Find the master checkbox in the same filter-box
        const filterCol = checkbox.closest('.filter-col');
        if (filterCol) {
            const master = filterCol.querySelector('.select-all-label input[type="checkbox"]');
            if (master) master.checked = allChecked;
        }
    }

    // Attach sync listeners to all filter checkboxes
    document.querySelectorAll('.cb-option input[type="checkbox"]').forEach(cb => {
        cb.addEventListener('change', function() { syncSelectAll(this); });
    });

    // Initialize state on load
    document.addEventListener('DOMContentLoaded', () => {
        const defaultType = document.getElementById('report_type').value || 'tickets';
        selectReportType(defaultType);
    });
</script>
