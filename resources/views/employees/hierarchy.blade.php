<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                {{ __('Manage Asset Hierarchy') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-3" style="min-height: calc(100vh - 100px); background-color: #eef5f9;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div style="color: #166534; margin-bottom: 15px; font-weight: bold; background: #dcfce3; padding: 10px; border-radius: 6px; border: 1px solid #bbf7d0;">
                    {{ session('success') }}
                </div>
            @endif

            <form id="hierarchy-form" action="{{ route('employees.hierarchy.update') }}" method="POST">
                @csrf
                <input type="hidden" name="hierarchy_json" id="hierarchy_json" value="{}">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 0 10px;">
                    <div id="breadcrumb" style="font-size: 0.95rem;">
                        <!-- JS injected -->
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span style="font-size: 0.8rem; color: #94a3b8;" id="drill-hint">Click a pill to drill down</span>
                        <a href="{{ route('employees.index') }}" style="background: #fff; color: #64748b; padding: 6px 12px; border-radius: 6px; border: 1px solid #cbd5e1; text-decoration: none; font-size: 0.85rem; font-weight: 600;">Cancel</a>
                        <button type="submit" style="background: #2563eb; color: #fff; padding: 6px 16px; border-radius: 6px; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; box-shadow: 0 1px 3px rgba(37, 99, 235, 0.2);">Save Changes</button>
                    </div>
                </div>

                <div id="render-container" style="padding: 20px 0; min-height: 400px;">
                    <!-- JS injected -->
                </div>
            </form>

        </div>
    </div>

    <style>
        .pill-wrapper { position: relative; display: inline-block; margin: 5px; }
        .pill { 
            background: #fff; 
            padding: 12px 28px; 
            border-radius: 9999px; 
            font-weight: 600; 
            color: #334155; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); 
            border: 1px solid #e2e8f0; 
            cursor: pointer; 
            transition: all 0.2s; 
            white-space: nowrap; 
            font-size: 0.95rem; 
        }
        .pill:hover { border-color: #cbd5e1; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .pill.active { background: #1d4ed8; color: #fff; border-color: #1d4ed8; cursor: default; }
        .pill.active:hover { box-shadow: 0 1px 3px rgba(0,0,0,0.05); }

        .action-bar { 
            position: absolute; 
            top: -20px; 
            left: 50%; 
            transform: translateX(-50%); 
            display: flex; 
            opacity: 0; 
            pointer-events: none; 
            transition: opacity 0.2s, top 0.2s; 
            background: rgba(255,255,255,0.85); 
            backdrop-filter: blur(4px);
            padding: 6px; 
            border-radius: 9999px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            z-index: 10; 
        }
        .pill-wrapper:hover .action-bar { opacity: 1; pointer-events: auto; top: -25px; }
        
        /* Ensures action bar stays visible if hovering it directly */
        .action-bar:hover { opacity: 1; pointer-events: auto; top: -25px; }

        .action-btn { 
            width: 36px; 
            height: 36px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: #fff; 
            border: 2px solid #fff; 
            cursor: pointer; 
            transition: transform 0.15s, z-index 0s; 
            margin-left: -8px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
            position: relative;
        }
        .action-btn:first-child { margin-left: 0; z-index: 3; }
        .action-btn:nth-child(2) { z-index: 2; }
        .action-btn:nth-child(3) { z-index: 1; }
        .action-btn:hover { transform: scale(1.15); z-index: 10 !important; }

        .view-btn { background: #67c3ce; }
        .edit-btn { background: #fdd05a; }
        .del-btn { background: #eb8c91; }

        .pill-add { 
            width: 44px; 
            height: 44px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: transparent; 
            border: 2px dashed #94a3b8; 
            color: #94a3b8; 
            cursor: pointer; 
            transition: all 0.2s; 
            margin: 5px;
        }
        .pill-add:hover { border-color: #64748b; color: #64748b; background: rgba(255,255,255,0.5); }
    </style>

    <script>
        // Use the passed JSON variable safely
        let hierarchyData = {!! json_encode($hierarchy) !!};
        let currentDept = null;

        const homeIcon = `<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline; margin-bottom:2px; margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>`;

        function sortData() {
            const sortedData = {};
            // Sort department keys alphabetically (case-insensitive)
            Object.keys(hierarchyData).sort((a, b) => a.localeCompare(b, undefined, {sensitivity: 'base'})).forEach(key => {
                // Sort position arrays alphabetically
                sortedData[key] = [...hierarchyData[key]].sort((a, b) => a.localeCompare(b, undefined, {sensitivity: 'base'}));
            });
            hierarchyData = sortedData;
        }

        function render() {
            sortData(); // Ensure everything is alphabetically sorted
            
            const container = document.getElementById('render-container');
            const breadcrumb = document.getElementById('breadcrumb');
            const drillHint = document.getElementById('drill-hint');
            
            // Sync hidden input for form submission
            document.getElementById('hierarchy_json').value = JSON.stringify(hierarchyData);
            
            let html = '';
            
            if (currentDept === null) {
                // STATE: OVERVIEW
                breadcrumb.innerHTML = `<span style="color:#3b82f6; font-weight:600; display:flex; align-items:center;">${homeIcon} Overview</span>`;
                drillHint.style.display = 'inline';

                html += `<div style="text-align: center; margin-top: 20px; margin-bottom: 25px; font-size: 0.8rem; color: #64748b; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">Departments</div>`;
                html += `<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; align-items: center; max-width: 900px; margin: 0 auto;">`;
                
                for (const dept in hierarchyData) {
                    html += generatePill(dept, 'dept', dept);
                }
                html += `<button type="button" onclick="addDept()" class="pill-add" title="Add Department"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg></button>`;
                html += `</div>`;
            } else {
                // STATE: DRILL DOWN
                breadcrumb.innerHTML = `<span onclick="goOverview()" style="color:#3b82f6; font-weight:600; cursor:pointer; display:flex; align-items:center;">${homeIcon} Overview <span style="color:#94a3b8; margin: 0 8px;">&gt;</span> ${currentDept}</span>`;
                drillHint.style.display = 'none';

                html += `<div style="display: flex; flex-direction: column; align-items: center; margin-top: 10px;">`;
                
                html += `<div class="pill active" style="margin-bottom: 0;">${currentDept}</div>`;
                html += `<div style="width: 2px; height: 35px; background-color: #3b82f6; margin: 0 0 15px 0;"></div>`;
                
                html += `<div style="font-size: 0.8rem; color: #64748b; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 25px;">Positions for ${currentDept}</div>`;
                
                html += `<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; align-items: center; max-width: 900px; margin: 0 auto;">`;
                if(hierarchyData[currentDept]) {
                    hierarchyData[currentDept].forEach((pos, idx) => {
                        html += generatePill(pos, 'pos', idx);
                    });
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
            // Escaping for JS string boundaries
            let safeName = name.replace(/'/g, "\\'");
            let safeId = typeof identifier === 'string' ? identifier.replace(/'/g, "\\'") : identifier;

            let viewBtn = type === 'dept' 
                ? `<button type="button" class="action-btn view-btn" onclick="drillDown('${safeId}')" title="View/Drill Down">${iconView}</button>` 
                : '';
            
            let editFn = type === 'dept' ? `editDept('${safeId}')` : `editPos(${identifier})`;
            let delFn = type === 'dept' ? `delDept('${safeId}')` : `delPos(${identifier})`;
            
            let editBtn = `<button type="button" class="action-btn edit-btn" onclick="${editFn}" title="Edit Name">${iconEdit}</button>`;
            let delBtn = `<button type="button" class="action-btn del-btn" onclick="${delFn}" title="Delete">${iconTrash}</button>`;
            
            let onClick = type === 'dept' ? `onclick="drillDown('${safeId}')"` : '';

            return `
            <div class="pill-wrapper">
                <div class="action-bar">
                    ${viewBtn}
                    ${editBtn}
                    ${delBtn}
                </div>
                <div class="pill" ${onClick}>${name}</div>
            </div>`;
        }

        // Actions
        function goOverview() {
            currentDept = null;
            render();
        }

        function drillDown(dept) {
            currentDept = dept;
            render();
        }

        function addDept() {
            let name = prompt("Enter new department name:");
            if (name && name.trim() !== '') {
                name = name.trim();
                if (!hierarchyData[name]) {
                    hierarchyData[name] = [];
                    render();
                } else {
                    alert("Department already exists!");
                }
            }
        }

        function editDept(oldName) {
            let newName = prompt("Edit department name:", oldName);
            if (newName && newName.trim() !== '' && newName !== oldName) {
                newName = newName.trim();
                if (hierarchyData[newName]) {
                    alert("A department with this name already exists!");
                    return;
                }
                // Rename key while preserving order
                let newData = {};
                for (let key in hierarchyData) {
                    if (key === oldName) {
                        newData[newName] = hierarchyData[oldName];
                    } else {
                        newData[key] = hierarchyData[key];
                    }
                }
                hierarchyData = newData;
                render();
            }
        }

        function delDept(name) {
            if (confirm(`Are you sure you want to delete the department "${name}" and all its positions?`)) {
                delete hierarchyData[name];
                render();
            }
        }

        function addPos() {
            let name = prompt(`Enter new position for ${currentDept}:`);
            if (name && name.trim() !== '') {
                hierarchyData[currentDept].push(name.trim());
                render();
            }
        }

        function editPos(index) {
            let oldName = hierarchyData[currentDept][index];
            let newName = prompt("Edit position name:", oldName);
            if (newName && newName.trim() !== '' && newName !== oldName) {
                hierarchyData[currentDept][index] = newName.trim();
                render();
            }
        }

        function delPos(index) {
            let name = hierarchyData[currentDept][index];
            if (confirm(`Are you sure you want to delete the position "${name}"?`)) {
                hierarchyData[currentDept].splice(index, 1);
                render();
            }
        }

        // Initial render
        document.addEventListener('DOMContentLoaded', render);
    </script>
</x-app-layout>
