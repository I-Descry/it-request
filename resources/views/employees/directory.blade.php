<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                📇 {{ __('Employee Directory') }}
            </h2>
            <a href="{{ route('employees.index') }}" style="background: var(--bg-card); color: var(--text-muted); padding: 6px 16px; border-radius: 6px; border: 1px solid var(--border-color); text-decoration: none; font-size: 0.85rem; font-weight: 600;">Back to List</a>
        </div>
    </x-slot>

    <div class="py-6" style="background-color: var(--th-bg); min-height: calc(100vh - 100px);">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @forelse($employeesByDept as $deptName => $deptEmployees)
                @php $deptNameDisplay = empty($deptName) ? 'Unassigned Department' : $deptName; @endphp
                
                <div style="margin-bottom: 35px;">
                    <!-- Department Header -->
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 25px; border-bottom: 2px solid var(--border-color); padding-bottom: 8px;">
                        <svg width="22" height="22" fill="none" stroke="var(--text-muted)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--text-primary);">
                            {{ $deptNameDisplay }}
                        </h3>
                        <span class="dk-badge dk-badge-closed" style="margin-left: auto;">{{ $deptEmployees->count() }} Employees</span>
                    </div>

                    @php 
                        $deptHierarchy = isset($hierarchy[$deptName]) ? $hierarchy[$deptName] : [];
                        
                        // Sort employees so their positions match the hierarchy rank
                        $sortedEmployees = $deptEmployees->sortBy(function($emp) use ($deptHierarchy) {
                            $rank = array_search($emp->position, $deptHierarchy);
                            return $rank !== false ? $rank : 999;
                        });
                    @endphp

                    <!-- Employee Cards Grid -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 12px;">
                        @foreach($sortedEmployees as $emp)
                            <a href="{{ route('employees.show', $emp->id) }}" class="dk-card" style="text-decoration: none; padding: 12px; display: flex; flex-direction: row; align-items: center; gap: 12px; border-left: 3px solid var(--border-color-focus); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-4px) scale(1.02)'; this.style.backgroundColor='var(--bg-hover)';" onmouseout="this.style.transform='none'; this.style.backgroundColor='var(--bg-card)';">
                                <!-- Avatar -->
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--th-bg); color: var(--text-primary); display: flex; align-items: center; justify-content: center; font-size: 0.95rem; font-weight: bold; flex-shrink: 0; border: 1px solid var(--border-color);">
                                    {{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1)) }}
                                </div>
                                <!-- Info -->
                                <div style="flex-grow: 1; overflow: hidden;">
                                    <div style="font-weight: 700; color: var(--text-primary); font-size: 0.95rem; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $emp->full_name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 3px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $emp->position ?? 'No Position' }}
                                    </div>
                                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 4px; display: flex; align-items: center; justify-content: space-between;">
                                        <span style="font-family: monospace;">ID: {{ $emp->nfp_id }}</span>
                                        @if($emp->branch && strtoupper($emp->branch) === 'HEAD OFFICE')
                                              <span style="display: flex; align-items: center; gap: 4px;">
                                                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#2563eb" style="width: 14px; height: 14px; flex-shrink: 0;" title="Head Office">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-2.25a1.5 1.5 0 0 1 1.5-1.5h3a1.5 1.5 0 0 1 1.5 1.5V21" />
                                                  </svg>
                                                  {{ $emp->branch }}
                                              </span>
                                          @elseif($emp->branch)
                                              <span style="display: flex; align-items: center; gap: 4px;">
                                                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4f46e5" style="width: 14px; height: 14px; flex-shrink: 0;" title="Remote Branch">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                                  </svg>
                                                  {{ $emp->branch }}
                                              </span>
                                          @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 40px; color: var(--text-muted); font-size: 1.05rem;">
                    No employees recorded yet.
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
