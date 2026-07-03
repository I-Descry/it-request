<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('View Ticket') }} — {{ $ticket->ticket_no }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <div style="color: green; margin-bottom: 12px; font-weight: bold; font-size: 0.9rem;">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Restored Badge --}}
                    @if ($ticket->restored_from_archive)
                        <div style="background-color: #dbeafe; color: #1e40af; padding: 8px 12px; border-radius: 6px; margin-bottom: 15px; font-weight: bold; font-size: 0.85rem;">
                            ♻️ This ticket was restored from the Archived Tickets on {{ $ticket->restored_at ? $ticket->restored_at->format('M d, Y h:i A') : 'N/A' }}
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div style="display: flex; gap: 10px; margin-bottom: 15px; align-items: center; justify-content: space-between;">
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <a href="{{ route('tickets.index') }}" class="dk-btn dk-btn-secondary">
                                ← Back to Tickets
                            </a>
                            <a href="{{ route('tickets.edit', $ticket->ticket_no) }}" class="dk-btn dk-btn-warning">
                                ✏️ Edit Ticket
                            </a>
                            <a href="{{ route('tickets.create', ['from' => $ticket->ticket_no]) }}" class="dk-btn dk-btn-secondary" style="background: var(--bg-card); border: 1px solid #6366f1; color: #6366f1;">
                                📋 Duplicate
                            </a>
                        </div>
                        <div style="display: flex; gap: 6px; align-items: center;">
                            @if($prevTicket)
                                <a href="{{ route('tickets.show', $prevTicket->ticket_no) }}" style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-primary); text-decoration: none; transition: all 0.15s;" title="{{ $prevTicket->ticket_no }}" onmouseover="this.style.borderColor='#2563eb'; this.style.color='#2563eb';" onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)';">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                </a>
                            @else
                                <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-muted); opacity: 0.4; cursor: not-allowed;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                </span>
                            @endif
                            <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">{{ $ticket->ticket_no }}</span>
                            @if($nextTicket)
                                <a href="{{ route('tickets.show', $nextTicket->ticket_no) }}" style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-primary); text-decoration: none; transition: all 0.15s;" title="{{ $nextTicket->ticket_no }}" onmouseover="this.style.borderColor='#2563eb'; this.style.color='#2563eb';" onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)';">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @else
                                <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-muted); opacity: 0.4; cursor: not-allowed;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Ticket Details --}}
                    <div class="dk-panel dk-grid-4">
                        <div><strong class="dk-text-label">Ticket No.:</strong> <br>{{ $ticket->ticket_no }}</div>
                        <div><strong class="dk-text-label">Status:</strong> <br>
                            @php
                                $statusColor = match($ticket->status) {
                                    'Open', 'Escalated' => 'dk-badge-open',
                                    'In Progress' => 'dk-badge-progress',
                                    'Resolved' => 'dk-badge-resolved',
                                    'Closed', 'Not Complete' => 'dk-badge-closed',
                                    default => 'dk-badge-closed',
                                };
                            @endphp
                            <span class="dk-badge {{ $statusColor }}" style="margin-top: 2px;">
                                {{ $ticket->status }}
                            </span>
                        </div>
                        <div><strong class="dk-text-label">Request Type:</strong> <br>{{ $ticket->request_type }}</div>
                        <div><strong class="dk-text-label">Request:</strong> <br>{{ $ticket->request ?? '—' }}</div>
                        
                        <div>
                            <strong class="dk-text-label">Requested By:</strong> <br>
                            <span style="font-size: 1.05rem; font-weight: 500;">{{ $ticket->requested_by }}</span>
                        </div>
                        <div><strong class="dk-text-label">Position:</strong> <br>{{ $ticket->position ?? 'N/A' }}</div>
                        <div><strong class="dk-text-label">Department:</strong> <br>{{ $ticket->department ?? 'N/A' }}</div>
                        <div>
                            <strong class="dk-text-label">Branch:</strong> <br>
                            @if ($ticket->branch && strtoupper($ticket->branch) === 'HEAD OFFICE')
                                <div style="display: flex; align-items: center; gap: 4px; margin-top: 2px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#2563eb" style="width: 14px; height: 14px; flex-shrink: 0;" title="Head Office">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-2.25a1.5 1.5 0 0 1 1.5-1.5h3a1.5 1.5 0 0 1 1.5 1.5V21" />
                                    </svg>
                                    {{ $ticket->branch }}
                                </div>
                            @elseif($ticket->branch)
                                <div style="display: flex; align-items: center; gap: 4px; margin-top: 2px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4f46e5" style="width: 14px; height: 14px; flex-shrink: 0;" title="Remote Branch">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    {{ $ticket->branch }}
                                </div>
                            @else
                                N/A
                            @endif
                        </div>
                        
                        <div><strong class="dk-text-label">Affected System:</strong> <br>{{ $ticket->affected_system ?? 'N/A' }}</div>
                        <div><strong class="dk-text-label">Assisted By:</strong> <br>{{ $ticket->assisted_by ?? 'N/A' }}</div>
                        <div><strong class="dk-text-label">Date Created:</strong> <br>{{ $ticket->created_at->format('M d, Y h:i A') }}</div>
                        
                        @if(isset($stats))
                        <div>
                            <strong class="dk-text-label">Request Frequency:</strong>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 6px;">
                                <div style="background: var(--th-bg); border: 1px solid var(--border-color); padding: 5px 10px; border-radius: 6px; display: flex; align-items: center; gap: 6px;">
                                    <span style="color: var(--text-secondary); font-size: 0.75rem;">Today</span>
                                    <b style="color: #3b82f6; font-size: 0.9rem;">{{ $stats['today'] }}</b>
                                </div>
                                <div style="background: var(--th-bg); border: 1px solid var(--border-color); padding: 5px 10px; border-radius: 6px; display: flex; align-items: center; gap: 6px;">
                                    <span style="color: var(--text-secondary); font-size: 0.75rem;">Week</span>
                                    <b style="color: #6366f1; font-size: 0.9rem;">{{ $stats['this_week'] }}</b>
                                </div>
                                <div style="background: var(--th-bg); border: 1px solid var(--border-color); padding: 5px 10px; border-radius: 6px; display: flex; align-items: center; gap: 6px;">
                                    <span style="color: var(--text-secondary); font-size: 0.75rem;">Month</span>
                                    <b style="color: #8b5cf6; font-size: 0.9rem;">{{ $stats['this_month'] }}</b>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="dk-panel dk-grid-2">
                        <div>
                            <strong class="dk-text-label">Details of the Issue:</strong>
                            <div style="margin-top: 4px; white-space: pre-wrap; background: var(--bg-input); padding: 8px; border: 1px solid var(--border-color); border-radius: 4px; min-height: 50px;">{{ $ticket->request_details }}</div>
                        </div>
                        <div>
                            <strong class="dk-text-label">Admin Remarks:</strong>
                            <div style="margin-top: 4px; white-space: pre-wrap; background: var(--bg-input); padding: 8px; border: 1px solid var(--border-color); border-radius: 4px; min-height: 50px;">{{ $ticket->remarks ?? 'N/A' }}</div>
                        </div>
                    </div>

                    {{-- Attachments Section --}}
                    <h3 style="font-size: 1.2rem; font-weight: bold; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                        📎 Attachments ({{ $ticket->attachments->count() }})
                    </h3>

                    @if ($ticket->attachments->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                            @foreach ($ticket->attachments as $attachment)
                                <div style="border: 1px solid var(--border-color); border-radius: 6px; padding: 8px; background-color: var(--panel-bg);">
                                    {{-- File Preview --}}
                                    @php
                                        $isImage = str_starts_with($attachment->file_type, 'image/');
                                        $isVideo = str_starts_with($attachment->file_type, 'video/');
                                        $isAudio = str_starts_with($attachment->file_type, 'audio/');
                                        $fileUrl = asset('storage/' . $attachment->file_path);
                                        $fileSizeMB = number_format($attachment->file_size / 1048576, 2);
                                    @endphp

                                    @if ($isImage)
                                        <div style="margin-bottom: 6px; text-align: center;">
                                            <img src="{{ $fileUrl }}" alt="{{ $attachment->file_name }}"
                                                 style="max-width: 100%; height: 100px; border-radius: 4px; object-fit: cover; cursor: pointer;"
                                                 onclick="window.open('{{ $fileUrl }}', '_blank')">
                                        </div>
                                    @elseif ($isVideo)
                                        <div style="margin-bottom: 6px;">
                                            <video controls style="width: 100%; height: 100px; border-radius: 4px; object-fit: cover;">
                                                <source src="{{ $fileUrl }}" type="{{ $attachment->file_type }}">
                                            </video>
                                        </div>
                                    @elseif ($isAudio)
                                        <div style="margin-bottom: 6px;">
                                            <audio controls style="width: 100%; height: 30px;">
                                                <source src="{{ $fileUrl }}" type="{{ $attachment->file_type }}">
                                            </audio>
                                        </div>
                                    @else
                                        <div style="margin-bottom: 6px; text-align: center; padding: 10px; background-color: var(--border-color); border-radius: 4px;">
                                            <span style="font-size: 1.5rem;">📄</span>
                                        </div>
                                    @endif

                                    <p style="font-weight: bold; font-size: 0.75rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-bottom: 2px;" data-tooltip="{{ $attachment->file_name }}">
                                        {{ $attachment->file_name }}
                                    </p>
                                    <p style="font-size: 0.7rem; color: var(--text-light); margin-bottom: 6px;">
                                        {{ strtoupper(pathinfo($attachment->file_name, PATHINFO_EXTENSION)) }} • {{ $fileSizeMB }} MB
                                    </p>

                                    <a href="{{ $fileUrl }}" download="{{ $attachment->file_name }}"
                                       style="display: inline-block; background-color: #2563eb; color: #fff; padding: 3px 8px; border-radius: 4px; text-decoration: none; font-size: 0.75rem;">
                                        ⬇️
                                    </a>
                                    <a href="{{ $fileUrl }}" target="_blank"
                                       style="display: inline-block; background-color: var(--text-light); color: #fff; padding: 3px 8px; border-radius: 4px; text-decoration: none; font-size: 0.75rem; margin-left: 2px;">
                                        🔗
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color: var(--text-light); font-style: italic;">No attachments for this ticket.</p>
                    @endif

                </div>
            </div>

            <!-- Activity Logs / History Timeline -->
            <div class="dk-panel" style="margin-top: 20px;">
                <h3 style="margin-top: 0; color: var(--text-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-bottom: 16px;">
                    Ticket History
                </h3>
                
                @if($ticket->activityLogs->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @foreach($ticket->activityLogs as $log)
                            <div style="display: flex; gap: 12px; align-items: flex-start;">
                                <div style="min-width: 140px; font-size: 0.8rem; color: var(--text-secondary); text-align: right; padding-top: 2px;">
                                    {{ $log->created_at->format('M d, Y h:i A') }}
                                </div>
                                <div style="width: 2px; background-color: var(--border-color); align-self: stretch; margin: 0 4px; position: relative;">
                                    <div style="position: absolute; top: 4px; left: -4px; width: 10px; height: 10px; border-radius: 50%; background-color: {{ $log->action === 'created' ? '#10b981' : '#3b82f6' }};"></div>
                                </div>
                                <div style="flex: 1; padding-bottom: 16px;">
                                    <div style="font-weight: 600; color: var(--text-primary);">{{ $log->description }}</div>
                                    
                                    @if($log->action === 'updated' && isset($log->properties['dirty']))
                                        <div style="font-size: 0.85rem; margin-top: 6px; display: flex; flex-direction: column; gap: 4px; background: var(--bg-body); padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border-color);">
                                            @foreach($log->properties['dirty'] as $key => $newValue)
                                                @if($key !== 'updated_at')
                                                    <div style="display: flex; align-items: center; gap: 6px;">
                                                        <span style="color: var(--text-muted); font-weight: 600; text-transform: capitalize;">{{ str_replace('_', ' ', $key) }}:</span>
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
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: var(--text-light); font-style: italic;">No history recorded yet.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
