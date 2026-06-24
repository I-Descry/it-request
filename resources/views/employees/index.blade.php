<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employees') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div style="color: green; margin-bottom: 15px; font-weight: bold;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                        <a href="{{ route('employees.create') }}">
                            <button style="background-color: #000; color: #fff; padding: 8px 15px; border-radius: 5px; cursor: pointer; border: none;">
                                + Add Employee
                            </button>
                        </a>
                        <button type="button" onclick="openHierarchyModal()" style="background-color: #6b7280; color: #fff; padding: 8px 15px; border-radius: 5px; cursor: pointer; border: none;">
                            ⚙️ Manage Hierarchy
                        </button>
                        <a href="{{ route('employees.directory') }}">
                            <button style="background-color: #10b981; color: #fff; padding: 8px 15px; border-radius: 5px; cursor: pointer; border: none;">
                                📇 Directory
                            </button>
                        </a>
                    </div>

                    <!-- Search & Filter Form -->
                    <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                        <form method="GET" action="{{ route('employees.index') }}" id="filterForm" style="display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap;">
                            <!-- Hidden inputs for sorting -->
                            <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by', 'last_name') }}">
                            <input type="hidden" name="sort_dir" id="sort_dir" value="{{ request('sort_dir', 'asc') }}">
                            
                            <div style="flex: 1; min-width: 250px;">
                                <label for="search" style="display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 5px;">Search Employees</label>
                                <div style="position: relative;">
                                    <div style="position: absolute; top: 9px; left: 10px; color: #94a3b8;">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="ID, Name, Position..." style="width: 100%; padding: 8px 10px 8px 35px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none;">
                                </div>
                            </div>
                            
                            <div style="width: 180px;">
                                <label for="filter_dept" style="display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 5px;">Department Filter</label>
                                <select name="filter_dept" id="filter_dept" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none;" onchange="document.getElementById('filterForm').submit();">
                                    <option value="">All Departments</option>
                                    @foreach(array_keys($hierarchy) as $dept)
                                        <option value="{{ $dept }}" {{ request('filter_dept') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="width: 180px;">
                                <label for="filter_branch" style="display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 5px;">Branch Filter</label>
                                <select name="filter_branch" id="filter_branch" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none;" onchange="document.getElementById('filterForm').submit();">
                                    <option value="">All Branches</option>
                                    @foreach(['HEAD OFFICE', 'NDD BACOLOD', 'NDD BAESA', 'NDD BATAAN', 'NDD BATANGAS', 'NDD CAVITE', 'NDD CDO', 'NDD CEBU', 'NDD DAVAO', 'NDD DIPOLOG', 'NDD DUMAGUETE', 'NDD ILOILO', 'NDD LA UNION', 'NDD LAGUNA', 'NDD LAS PIÑAS', 'NDD NUEVA ECIJA', 'NDD PULILAN', 'NDD ROXAS', 'NDD SAN FRANCISCO', 'NDD TACLOBAN', 'NDD TARLAC', 'NDD TAYTAY'] as $br)
                                        <option value="{{ $br }}" {{ request('filter_branch') == $br ? 'selected' : '' }}>{{ $br }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <button type="submit" style="background: #2563eb; color: #fff; padding: 8px 15px; border-radius: 6px; border: none; font-size: 0.9rem; font-weight: 600; cursor: pointer; height: 38px;">Search</button>
                            </div>
                            @if(request()->hasAny(['search', 'filter_dept', 'filter_branch']))
                            <div>
                                <a href="{{ route('employees.index') }}" style="display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 15px; background: #fff; color: #64748b; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; font-weight: 600; text-decoration: none;">Clear</a>
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

                    <div style="overflow-x: auto; max-width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <table style="width: 100%; text-align: left; border-collapse: collapse; white-space: nowrap;">
                            <thead>
                                @php
                                    $sortCol = request('sort_by', 'last_name');
                                    $sortDir = request('sort_dir', 'asc');
                                    function sortArrow($col, $sCol, $sDir) {
                                        if ($col !== $sCol) return "<span style='color: #cbd5e1; font-size: 0.8rem; margin-left: 4px;'>↕</span>";
                                        return $sDir === 'asc' ? "<span style='color: #2563eb; font-size: 0.8rem; margin-left: 4px;'>↑</span>" : "<span style='color: #2563eb; font-size: 0.8rem; margin-left: 4px;'>↓</span>";
                                    }
                                @endphp
                                <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb; user-select: none;">
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700; cursor: pointer;" onclick="sortBy('nfp_id')">Employee ID {!! sortArrow('nfp_id', $sortCol, $sortDir) !!}</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700; cursor: pointer;" onclick="sortBy('last_name')">Name {!! sortArrow('last_name', $sortCol, $sortDir) !!}</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700; cursor: pointer;" onclick="sortBy('position')">Position {!! sortArrow('position', $sortCol, $sortDir) !!}</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700; cursor: pointer;" onclick="sortBy('department')">Department {!! sortArrow('department', $sortCol, $sortDir) !!}</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700; cursor: pointer;" onclick="sortBy('branch')">Branch {!! sortArrow('branch', $sortCol, $sortDir) !!}</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $index => $employee)
                                    @php $rowBg = $index % 2 === 0 ? '#ffffff' : '#f9fafb'; @endphp
                                    <tr style="border-bottom: 1px solid #e5e7eb; background-color: {{ $rowBg }};">
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $employee->nfp_id }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem; font-weight: bold;">{{ $employee->full_name }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $employee->position }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $employee->department ?? '—' }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $employee->branch }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem; text-align: center;">
                                            <a href="{{ route('employees.edit', $employee->id) }}" style="background-color: #f59e0b; color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 0.75rem; font-weight: 600;">
                                                ✏️ Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 20px; color: #6b7280;">No employees recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div style="margin-top: 15px;">
                        {{ $employees->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirm Modal -->
    <div id="deleteConfirmModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 60; flex-direction: column; align-items: center; justify-content: center;">
        <div style="background: #fff; width: 90%; max-width: 400px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); padding: 20px; text-align: center;">
            <div style="color: #ef4444; margin-bottom: 15px;">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin: 0 auto;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: #1e293b; margin-bottom: 10px;">Confirm Deletion</h3>
            <p id="deleteConfirmMsg" style="font-size: 0.95rem; color: #64748b; margin-bottom: 20px;"></p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" onclick="closeDeleteConfirm()" style="background: #e2e8f0; color: #475569; padding: 8px 20px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="button" onclick="executeDelete()" style="background: #ef4444; color: #fff; padding: 8px 20px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer;">Delete</button>
            </div>
        </div>
    </div>

    <!-- Hierarchy Modal Backdrop -->
    <div id="hierarchyModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; flex-direction: column; align-items: center; justify-content: center;">
        <!-- Modal Content -->
        <div style="background: #eef5f9; width: 90%; max-width: 1000px; max-height: 90vh; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden; display: flex; flex-direction: column;">
            
            <!-- Modal Header -->
            <div style="background: #fff; padding: 15px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-weight: 600; font-size: 1.1rem; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Manage Asset Hierarchy
                </h2>
                <button type="button" onclick="closeHierarchyModal()" style="background: transparent; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer; line-height: 1;">&times;</button>
            </div>

            <!-- Modal Body -->
            <div style="padding: 20px; overflow-y: auto; flex-grow: 1;">
                <input type="hidden" id="hierarchy_json" value="{}">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 0 10px;">
                    <div id="breadcrumb" style="font-size: 0.95rem;"></div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span style="font-size: 0.8rem; color: #94a3b8;" id="drill-hint">Click a pill to drill down</span>
                        <button type="button" onclick="closeHierarchyModal()" style="background: #fff; color: #64748b; padding: 6px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.85rem; font-weight: 600; cursor: pointer;">Cancel</button>
                        <button type="button" onclick="saveHierarchyAjax()" id="saveHierarchyBtn" style="background: #2563eb; color: #fff; padding: 6px 16px; border-radius: 6px; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; box-shadow: 0 1px 3px rgba(37, 99, 235, 0.2);">Save Changes</button>
                    </div>
                </div>

                <div id="render-container" style="padding: 20px 0; min-height: 300px;"></div>
            </div>
        </div>
    </div>

    <style>
        .pill-wrapper { position: relative; display: inline-block; margin: 5px; }
        .pill { 
            background: #fff; padding: 12px 28px; border-radius: 9999px; font-weight: 600; 
            color: #334155; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; 
            cursor: pointer; transition: all 0.2s; white-space: nowrap; font-size: 0.95rem; 
        }
        .pill:hover { border-color: #cbd5e1; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .pill.active { background: #374151; color: #fff; border-color: #1f2937; cursor: default; }
        .pill.active:hover { box-shadow: 0 1px 3px rgba(0,0,0,0.05); }

        .action-bar { 
            position: absolute; top: -20px; left: 50%; transform: translateX(-50%); display: flex; 
            opacity: 0; pointer-events: none; transition: opacity 0.2s, top 0.2s; 
            background: rgba(255,255,255,0.85); backdrop-filter: blur(4px); padding: 6px; 
            border-radius: 9999px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); z-index: 10; 
        }
        .pill-wrapper:hover .action-bar, .action-bar:hover { opacity: 1; pointer-events: auto; top: -25px; }

        .action-btn { 
            width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; 
            justify-content: center; color: #fff; border: 2px solid #fff; cursor: pointer; 
            transition: transform 0.15s, z-index 0s; margin-left: -8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: relative;
        }
        .action-btn:first-child { margin-left: 0; z-index: 3; }
        .action-btn:nth-child(2) { z-index: 2; }
        .action-btn:nth-child(3) { z-index: 1; }
        .action-btn:hover { transform: scale(1.15); z-index: 10 !important; }

        .view-btn { background: #64748b; }
        .edit-btn { background: #f59e0b; }
        .del-btn { background: #ef4444; }

        .pill-add { 
            width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; 
            justify-content: center; background: transparent; border: 2px dashed #94a3b8; 
            color: #94a3b8; cursor: pointer; transition: all 0.2s; margin: 5px;
        }
        .pill-add:hover { border-color: #64748b; color: #64748b; background: rgba(255,255,255,0.5); }
    </style>

    <script>
        let hierarchyData = {!! json_encode($hierarchy ?? []) !!};
        let currentDept = null;
        let addingNewDept = false;
        let addingNewPos = false;
        let deleteAction = null;

        const homeIcon = `<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline; margin-bottom:2px; margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>`;

        function openHierarchyModal() {
            document.getElementById('hierarchyModal').style.display = 'flex';
            render();
        }

        function closeHierarchyModal() {
            document.getElementById('hierarchyModal').style.display = 'none';
        }

        function saveHierarchyAjax() {
            const btn = document.getElementById('saveHierarchyBtn');
            btn.innerHTML = 'Saving...';
            btn.disabled = true;
            
            fetch("{{ route('employees.hierarchy.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ hierarchy_json: document.getElementById('hierarchy_json').value })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    window.location.reload(); // Reload to reflect changes if any
                } else {
                    alert('Error saving hierarchy.');
                    btn.innerHTML = 'Save Changes';
                    btn.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error saving hierarchy.');
                btn.innerHTML = 'Save Changes';
                btn.disabled = false;
            });
        }

        function sortData() {
            const sortedData = {};
            Object.keys(hierarchyData).sort((a, b) => a.localeCompare(b, undefined, {sensitivity: 'base'})).forEach(key => {
                sortedData[key] = [...hierarchyData[key]].sort((a, b) => a.localeCompare(b, undefined, {sensitivity: 'base'}));
            });
            hierarchyData = sortedData;
        }

        function render() {
            sortData();
            
            const container = document.getElementById('render-container');
            const breadcrumb = document.getElementById('breadcrumb');
            const drillHint = document.getElementById('drill-hint');
            
            document.getElementById('hierarchy_json').value = JSON.stringify(hierarchyData);
            
            let html = '';
            
            if (currentDept === null) {
                breadcrumb.innerHTML = `<span style="color:#475569; font-weight:600; display:flex; align-items:center;">${homeIcon} Overview</span>`;
                drillHint.style.display = 'inline';

                html += `<div style="text-align: center; margin-top: 20px; margin-bottom: 25px; font-size: 0.8rem; color: #64748b; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">Departments</div>`;
                html += `<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; align-items: center; max-width: 900px; margin: 0 auto;">`;
                
                for (const dept in hierarchyData) {
                    html += generatePill(dept, 'dept', dept);
                }
                if (addingNewDept) {
                    html += `<div class="pill-wrapper"><div class="pill" id="new-dept-pill"></div></div>`;
                }
                html += `<button type="button" onclick="addDept()" class="pill-add" title="Add Department"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg></button>`;
                html += `</div>`;
            } else {
                breadcrumb.innerHTML = `<span onclick="goOverview()" style="color:#475569; font-weight:600; cursor:pointer; display:flex; align-items:center;">${homeIcon} Overview <span style="color:#94a3b8; margin: 0 8px;">&gt;</span> ${currentDept}</span>`;
                drillHint.style.display = 'none';

                html += `<div style="display: flex; flex-direction: column; align-items: center; margin-top: 10px;">`;
                html += `<div style="margin-bottom: 0;">` + generatePill(currentDept, 'dept-active', currentDept) + `</div>`;
                html += `<div style="width: 2px; height: 35px; border-left: 2px dashed #cbd5e1; margin: 0 0 15px 0;"></div>`;
                html += `<div style="font-size: 0.8rem; color: #64748b; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 25px;">Positions for ${currentDept}</div>`;
                
                html += `<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; align-items: center; max-width: 900px; margin: 0 auto;">`;
                if(hierarchyData[currentDept]) {
                    hierarchyData[currentDept].forEach((pos, idx) => {
                        html += generatePill(pos, 'pos', idx);
                    });
                }
                if (addingNewPos) {
                    html += `<div class="pill-wrapper"><div class="pill" id="new-pos-pill"></div></div>`;
                }
                html += `<button type="button" onclick="addPos()" class="pill-add" title="Add Position"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg></button>`;
                html += `</div></div>`;
            }
            
            container.innerHTML = html;
        }

        const iconView = `<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
        const iconEdit = `<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>`;
        const iconTrash = `<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>`;

        function generatePill(name, type, identifier) {
            let safeName = name.replace(/'/g, "\\'");
            let safeId = typeof identifier === 'string' ? identifier.replace(/'/g, "\\'") : identifier;

            let viewBtn = type === 'dept' ? `<button type="button" class="action-btn view-btn" onclick="drillDown('${safeId}')" title="View/Drill Down">${iconView}</button>` : '';
            let editFn = (type === 'dept' || type === 'dept-active') ? `editDept(this, '${safeId}')` : `editPos(this, ${identifier})`;
            let delFn = (type === 'dept' || type === 'dept-active') ? `delDept('${safeId}')` : `delPos(${identifier})`;
            
            let editBtn = `<button type="button" class="action-btn edit-btn" onclick="${editFn}" title="Edit Name">${iconEdit}</button>`;
            let delBtn = `<button type="button" class="action-btn del-btn" onclick="${delFn}" title="Delete">${iconTrash}</button>`;
            let onClick = type === 'dept' ? `onclick="drillDown('${safeId}')"` : '';
            
            let pillClass = type === 'dept-active' ? 'pill active' : 'pill';

            return `
            <div class="pill-wrapper">
                <div class="action-bar">
                    ${viewBtn}
                    ${editBtn}
                    ${delBtn}
                </div>
                <div class="${pillClass}" ${onClick}>${name}</div>
            </div>`;
        }

        function goOverview() { currentDept = null; render(); }
        function drillDown(dept) { currentDept = dept; render(); }

        function addDept() {
            addingNewDept = true;
            render();
            let pill = document.getElementById('new-dept-pill');
            makeInlineEditable(pill, '', function(newName) {
                addingNewDept = false;
                if (newName && newName !== '') {
                    if (hierarchyData[newName]) { alert("Department already exists!"); } 
                    else { hierarchyData[newName] = []; }
                }
                render();
            });
        }

        function makeInlineEditable(element, oldValue, callback) {
            element.contentEditable = "true";
            element.focus();
            element.style.cursor = "text";
            element.style.outline = "2px solid #3b82f6";
            element.style.backgroundColor = "#ffffff";
            if (element.classList.contains('active')) {
                element.style.color = "#000";
            }
            
            let range = document.createRange();
            range.selectNodeContents(element);
            let sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
            
            element.onkeydown = function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    element.blur();
                }
                if (e.key === 'Escape') {
                    e.innerText = oldValue;
                    element.blur();
                }
            };
            
            element.onblur = function() {
                element.contentEditable = "false";
                element.style.outline = "none";
                element.style.cursor = "";
                callback(element.innerText.trim());
            };
        }

        function editDept(btn, oldName) {
            let pill = btn.closest('.pill-wrapper').querySelector('.pill');
            makeInlineEditable(pill, oldName, function(newName) {
                if (newName && newName !== oldName) {
                    if (hierarchyData[newName]) { alert("A department with this name already exists!"); render(); return; }
                    let newData = {};
                    for (let key in hierarchyData) {
                        if (key === oldName) newData[newName] = hierarchyData[oldName];
                        else newData[key] = hierarchyData[key];
                    }
                    hierarchyData = newData;
                    if (currentDept === oldName) currentDept = newName;
                    render();
                } else {
                    render();
                }
            });
        }

        function closeDeleteConfirm() {
            document.getElementById('deleteConfirmModal').style.display = 'none';
            deleteAction = null;
        }

        function executeDelete() {
            if (deleteAction) deleteAction();
            closeDeleteConfirm();
        }

        function delDept(name) {
            document.getElementById('deleteConfirmMsg').innerText = `Are you sure you want to delete the department "${name}" and all its positions?`;
            deleteAction = function() {
                delete hierarchyData[name]; 
                if (currentDept === name) currentDept = null;
                render();
            };
            document.getElementById('deleteConfirmModal').style.display = 'flex';
        }

        function addPos() {
            addingNewPos = true;
            render();
            let pill = document.getElementById('new-pos-pill');
            makeInlineEditable(pill, '', function(newName) {
                addingNewPos = false;
                if (newName && newName !== '') {
                    hierarchyData[currentDept].push(newName);
                }
                render();
            });
        }

        function editPos(btn, index) {
            let pill = btn.closest('.pill-wrapper').querySelector('.pill');
            let oldName = hierarchyData[currentDept][index];
            makeInlineEditable(pill, oldName, function(newName) {
                if (newName && newName !== oldName) {
                    hierarchyData[currentDept][index] = newName;
                    render();
                } else {
                    render();
                }
            });
        }

        function delPos(index) {
            let name = hierarchyData[currentDept][index];
            document.getElementById('deleteConfirmMsg').innerText = `Are you sure you want to delete the position "${name}"?`;
            deleteAction = function() {
                hierarchyData[currentDept].splice(index, 1);
                render();
            };
            document.getElementById('deleteConfirmModal').style.display = 'flex';
        }
    </script>
</x-app-layout>
