<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('View Employee: ') }} <span style="color: #2563eb;">{{ $employee->first_name }} {{ $employee->last_name }}</span>
        </h2>
    </x-slot>

    <div class="py-12" style="background-color: var(--th-bg); min-height: calc(100vh - 65px);">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="p-6 text-gray-900 dark:text-gray-100 dark:text-gray-100">

                    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                        <a href="{{ route('employees.index') }}" style="color: var(--text-light); text-decoration: none; font-size: 0.85rem; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            Back to Employees
                        </a>
                        <a href="{{ route('employees.edit', $employee->id) }}" class="dk-btn dk-btn-warning">
                            âœï¸ Edit Employee
                        </a>
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
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
