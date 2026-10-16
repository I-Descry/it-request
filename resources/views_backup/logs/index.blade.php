<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Activity Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Tab Bar --}}
                    <div style="display: flex; gap: 0; margin-bottom: 20px; border-bottom: 2px solid var(--border-color); align-items: center; justify-content: space-between;">
                        <div style="display: flex; gap: 0;">
                            <a href="{{ route('logs.index') }}"
                               style="padding: 10px 24px; font-weight: bold; text-decoration: none; {{ !request('type') ? 'border-bottom: 3px solid var(--text-primary); color: var(--text-primary); margin-bottom: -2px;' : 'color: var(--text-secondary);' }}">
                                All Logs
                            </a>
                            <a href="{{ route('logs.index', ['type' => 'tickets']) }}"
                               style="padding: 10px 24px; font-weight: bold; text-decoration: none; {{ request('type') == 'tickets' ? 'border-bottom: 3px solid var(--text-primary); color: var(--text-primary); margin-bottom: -2px;' : 'color: var(--text-secondary);' }}">
                                Ticket Logs
                            </a>
                            <a href="{{ route('logs.index', ['type' => 'employees']) }}"
                               style="padding: 10px 24px; font-weight: bold; text-decoration: none; {{ request('type') == 'employees' ? 'border-bottom: 3px solid var(--text-primary); color: var(--text-primary); margin-bottom: -2px;' : 'color: var(--text-secondary);' }}">
                                Employee Logs
                            </a>
                            <a href="{{ route('logs.index', ['type' => 'sso_accounts']) }}"
                               style="padding: 10px 24px; font-weight: bold; text-decoration: none; {{ request('type') == 'sso_accounts' ? 'border-bottom: 3px solid var(--text-primary); color: var(--text-primary); margin-bottom: -2px;' : 'color: var(--text-secondary);' }}">
                                SSO Account Logs
                            </a>
                        </div>
                    </div>

                    <div class="dk-table-wrap">
                        <table class="dk-table">
                            <thead>
                                <tr>
                                    <th style="width: 180px;">Timestamp</th>
                                    <th>Subject</th>
                                    <th>Action</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                    <tr>
                                        <td style="color: var(--text-secondary); font-size: 0.85rem;">
                                            {{ $log->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td>
                                            @if(class_basename($log->subject_type) === 'Ticket')
                                                <span class="dk-badge dk-badge-open" style="font-size: 0.7rem;">Ticket #{{ $log->subject?->ticket_no ?? $log->subject_id }}</span>
                                            @elseif(class_basename($log->subject_type) === 'Employee')
                                                <span class="dk-badge dk-badge-prog" style="font-size: 0.7rem;">
                                                    Employee: {{ $log->subject?->full_name ?? '#' . $log->subject_id }}
                                                </span>
                                            @elseif(class_basename($log->subject_type) === 'SsoAccount')
                                                <span class="dk-badge" style="background: #e0e7ff; color: #4338ca; font-size: 0.7rem;">
                                                    SSO: {{ $log->subject?->username ?? '#' . $log->subject_id }}
                                                </span>
                                            @else
                                                {{ class_basename($log->subject_type) }}
                                            @endif
                                        </td>
                                        <td>
                                            <span style="font-weight: 600; text-transform: capitalize; color: var(--text-primary);">{{ $log->action }}</span>
                                        </td>
                                        <td>
                                            <div style="font-weight: 500; color: var(--text-primary);">{{ $log->description }}</div>
                                            @if($log->action === 'updated' && isset($log->properties['dirty']))
                                                <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 4px; display: flex; flex-direction: column; gap: 2px;">
                                                    @foreach($log->properties['dirty'] as $key => $newValue)
                                                        @if($key !== 'updated_at')
                                                            <div style="display: flex; align-items: center; gap: 6px;">
                                                                <strong style="color: var(--text-muted); text-transform: capitalize;">{{ str_replace('_', ' ', $key) }}:</strong>
                                                                <span style="color: #ef4444; text-decoration: line-through;">{{ $log->properties['old'][$key] ?? 'null' }}</span>
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 12px; height: 12px; color: #9ca3af;">
                                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                                                </svg>
                                                                <span style="color: #10b981; font-weight: 600;">{{ $newValue ?? 'null' }}</span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                            No activity logs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <div style="padding: 12px 20px;">
                            {{ $logs->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
