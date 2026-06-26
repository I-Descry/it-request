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
                @php 
                    $positions = $deptEmployees->groupBy('position'); 
                    $deptNameDisplay = empty($deptName) ? 'Unassigned Department' : $deptName;
                @endphp
                <div style="margin-bottom: 40px; background: var(--bg-card); border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); overflow: hidden; border: 1px solid var(--border-color);">
                    <!-- Department Header -->
                    <div style="background: #1e293b; color: var(--bg-card); padding: 15px 25px;">
                        <h3 style="font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            {{ $deptNameDisplay }}
                        </h3>
                    </div>

                    <div style="padding: 25px;">
                        @foreach($positions as $posName => $posEmployees)
                            @php $posNameDisplay = empty($posName) ? 'Unassigned Position' : $posName; @endphp
                            <div style="margin-bottom: 25px;">
                                <h4 style="font-size: 1.05rem; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px; margin-bottom: 15px; display: inline-block;">
                                    {{ $posNameDisplay }}
                                </h4>
                                
                                <!-- Employee Cards Grid -->
                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px;">
                                    @foreach($posEmployees as $emp)
                                        <div style="background: #f1f5f9; border-radius: 8px; padding: 15px; display: flex; align-items: flex-start; gap: 15px; transition: transform 0.2s, box-shadow 0.2s; border: 1px solid transparent;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.1)'; this.style.borderColor='#cbd5e1';" onmouseout="this.style.transform='none'; this.style.boxShadow='none'; this.style.borderColor='transparent';">
                                            <!-- Avatar -->
                                            <div style="width: 48px; height: 48px; border-radius: 50%; background: #3b82f6; color: var(--bg-card); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: bold; flex-shrink: 0; box-shadow: inset 0 -2px 0 rgba(0,0,0,0.15);">
                                                {{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1)) }}
                                            </div>
                                            <!-- Info -->
                                            <div>
                                                <div style="font-weight: 700; color: var(--text-primary); font-size: 1.05rem; line-height: 1.2;">{{ $emp->full_name }}</div>
                                                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">
                                                    <span style="font-weight: 600; color: #94a3b8;">ID:</span> {{ $emp->nfp_id }}
                                                </div>
                                                @if($emp->branch)
                                                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    {{ $emp->branch }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 40px; color: var(--text-muted); font-size: 1.1rem;">
                    No employees recorded yet.
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
