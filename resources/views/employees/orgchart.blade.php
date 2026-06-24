<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                📊 {{ __('Organization Chart') }}
            </h2>
            <a href="{{ route('employees.index') }}" style="background: #fff; color: #64748b; padding: 6px 16px; border-radius: 6px; border: 1px solid #cbd5e1; text-decoration: none; font-size: 0.85rem; font-weight: 600;">Back to List</a>
        </div>
    </x-slot>

    <div class="py-6" style="background-color: #eef2f6; min-height: calc(100vh - 100px); overflow-x: auto;">
        <div class="tree-container" style="padding: 20px; min-width: max-content; display: flex; justify-content: center;">
            <div class="tree">
                <ul>
                    <li>
                        <div class="node company-node">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin: 0 auto; margin-bottom: 5px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Company Overview
                        </div>
                        <ul>
                            @foreach($employeesByDept as $deptName => $deptEmployees)
                                @php 
                                    $positions = $deptEmployees->groupBy('position'); 
                                    $deptNameDisplay = empty($deptName) ? 'Unassigned' : $deptName;
                                @endphp
                                <li>
                                    <div class="node dept-node">{{ $deptNameDisplay }}</div>
                                    <ul>
                                        @foreach($positions as $posName => $posEmployees)
                                            @php $posNameDisplay = empty($posName) ? 'Unassigned' : $posName; @endphp
                                            <li>
                                                <div class="node pos-node">{{ $posNameDisplay }}</div>
                                                <ul>
                                                    @foreach($posEmployees as $emp)
                                                        <li>
                                                            <div class="node emp-node">
                                                                <div class="emp-avatar">{{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1)) }}</div>
                                                                <div class="emp-name">{{ $emp->full_name }}</div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <style>
        /* CSS Tree Logic */
        .tree-container { margin: 0 auto; }
        .tree ul {
            padding-top: 20px; position: relative;
            transition: all 0.5s;
            display: flex;
            justify-content: center;
        }
        .tree li {
            float: left; text-align: center;
            list-style-type: none;
            position: relative;
            padding: 20px 10px 0 10px;
            transition: all 0.5s;
        }
        /* Connectors */
        .tree li::before, .tree li::after{
            content: '';
            position: absolute; top: 0; right: 50%;
            border-top: 2px solid #cbd5e1;
            width: 50%; height: 20px;
        }
        .tree li::after{
            right: auto; left: 50%;
            border-left: 2px solid #cbd5e1;
        }
        /* Remove left-right connectors from single items */
        .tree li:only-child::after, .tree li:only-child::before {
            display: none;
        }
        /* Remove space from the top of single children */
        .tree li:only-child{ padding-top: 0;}
        
        /* Remove left connector from first child and right connector from last child */
        .tree li:first-child::before, .tree li:last-child::after{
            border: 0 none;
        }
        /* Add back the vertical connector to the last nodes */
        .tree li:last-child::before{
            border-right: 2px solid #cbd5e1;
            border-radius: 0 5px 0 0;
            transform: translateX(1px); /* pixel perfect alignment */
        }
        .tree li:first-child::after{
            border-radius: 5px 0 0 0;
        }
        
        /* Downward connectors from parents */
        .tree ul ul::before{
            content: '';
            position: absolute; top: 0; left: 50%;
            border-left: 2px solid #cbd5e1;
            width: 0; height: 20px;
        }
        
        /* The Nodes */
        .node {
            display: inline-block;
            padding: 10px 15px;
            text-decoration: none;
            color: #1e293b;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            background: #fff;
            border: 1px solid #e2e8f0;
            min-width: 100px;
        }
        
        /* Hover FX */
        .node:hover {
            background: #f1f5f9; color: #0f172a; border-color: #94a3b8;
            transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            cursor: pointer;
        }
        /* Highlight path up to the node on hover */
        .tree li:hover > .node { background: #e0f2fe; border-color: #38bdf8; color: #0284c7; }
        .tree li:hover > .company-node { background: #1e293b; color: #fff; border-color: #0f172a; }
        .tree li:hover > .dept-node { background: #3b82f6; color: #fff; border-color: #2563eb; }
        
        .tree li:hover > ul::before, 
        .tree li:hover > ul > li::before, 
        .tree li:hover > ul > li::after { border-color: #38bdf8; z-index: 5; }
        
        /* Specific Node Colors */
        .company-node { background: #1e293b; color: #fff; padding: 15px 30px; font-size: 1.1rem; border-color: #0f172a; }
        .company-node:hover { background: #334155; color: #fff; }

        .dept-node { background: #3b82f6; color: #fff; border-color: #2563eb; padding: 12px 20px; font-size: 1rem; }
        .dept-node:hover { background: #60a5fa; color: #fff; border-color: #3b82f6; }

        .pos-node { background: #f8fafc; color: #475569; border-top: 4px solid #f59e0b; padding: 8px 15px; font-size: 0.9rem;}
        
        .emp-node { display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 15px; min-width: 120px; }
        .emp-avatar { width: 40px; height: 40px; border-radius: 50%; background: #e2e8f0; color: #475569; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; box-shadow: inset 0 -2px 0 rgba(0,0,0,0.1); }
        .emp-name { font-size: 0.85rem; font-weight: 700; color: #334155; }
        
        /* Responsive scroll hint */
        .tree-container::-webkit-scrollbar { height: 12px; }
        .tree-container::-webkit-scrollbar-track { background: #e2e8f0; border-radius: 6px; }
        .tree-container::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 6px; }
    </style>
</x-app-layout>
