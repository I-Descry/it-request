<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($sourceTicket) ? __('Duplicate Request — from ' . $sourceTicket->ticket_no) : __('Record a New IT Request') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 dark:text-gray-100">

                    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.default.min.css" rel="stylesheet">
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
                        .t-input::placeholder { color: var(--text-muted); }
                        .t-input-readonly {
                            display: block;
                            width: 100%;
                            padding: 7px 10px;
                            font-size: 0.875rem;
                            color: var(--text-light);
                            background-color: var(--panel-bg);
                            border: 1px solid var(--border-color);
                            border-radius: 6px;
                            outline: none;
                            cursor: not-allowed;
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
                        .t-grid-4 { grid-template-columns: 2fr 1fr 1fr 1fr; }
                        .t-grid-3 { grid-template-columns: 1fr 1fr 1fr; }
                        .t-grid-2 { grid-template-columns: 1fr 2fr; }
                        .t-error { color: #dc2626; font-size: 0.75rem; margin-top: 2px; display: block; }
                        .t-drop {
                            border: 2px dashed #d1d5db;
                            border-radius: 6px;
                            padding: 10px 14px;
                            background: var(--panel-bg);
                            text-align: center;
                            transition: border-color 0.2s, background 0.2s;
                        }
                        .t-drop:hover { border-color: var(--border-color-focus); background: var(--bg-hover); }
                        .t-drop input[type="file"] { cursor: pointer; font-size: 0.8rem; width: 100%; }
                        .t-drop-hint { font-size: 0.7rem; color: var(--text-muted); margin-top: 4px; margin-bottom: 0; }
                        .t-footer { display: flex; justify-content: flex-end; align-items: center; gap: 12px; padding-top: 10px; border-top: 1px solid var(--border-color); }
                        .t-btn-cancel { text-decoration: none; color: var(--text-light); font-size: 0.85rem; font-weight: 500; padding: 7px 16px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-card); transition: background 0.15s; }
                        .t-btn-cancel:hover { background: var(--th-bg); }
                        .t-btn-submit { background: #2563eb; color: var(--bg-card); padding: 7px 20px; border-radius: 6px; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: background 0.15s; box-shadow: 0 1px 3px rgba(37, 99, 235, 0.2); }
                        .t-btn-submit:hover { background: #1d4ed8; }
                                            
                        .ts-wrapper { width: 100% !important; padding: 0 !important; border: none !important; background: none !important; box-shadow: none !important; min-height: 0 !important; outline: none !important; }
                        .ts-wrapper * { box-sizing: border-box !important; }
                        .ts-wrapper.single .ts-control { padding: 7px 10px 7px 30px !important; font-size: 0.875rem !important; background-color: var(--bg-card) !important; background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="%239ca3af"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>') !important; background-repeat: no-repeat !important; background-position: left 10px center !important; background-size: 1.5em 1.5em !important; border: 1px solid var(--border-color) !important; border-radius: 6px !important; box-shadow: none !important; min-height: 0 !important; height: auto !important; }
                        .ts-wrapper.single .ts-control::after { display: none !important; }
                        .ts-wrapper.single .ts-control > input { padding: 0 !important; margin: 0 !important; font-size: 0.875rem !important; line-height: 1.5rem !important; height: 1.5rem !important; min-height: 0 !important; color: var(--text-primary) !important; }
                        .ts-wrapper.single .ts-control > .item { margin: 0 !important; padding: 0 !important; font-size: 0.875rem !important; line-height: 1.5rem !important; color: var(--text-primary) !important; }
                        .ts-wrapper.single.focus .ts-control { border-color: #3b82f6 !important; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important; }
                        .ts-dropdown { border: 1px solid var(--border-color) !important; border-radius: 6px !important; margin-top: 2px !important; box-shadow: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06) !important; background: var(--bg-card) !important; z-index: 99999 !important; }
                        .ts-dropdown .option { padding: 7px 10px !important; font-size: 0.875rem !important; color: var(--text-primary) !important; }
                        .ts-dropdown .option:hover, .ts-dropdown .option.active { background-color: var(--th-bg) !important; color: var(--text-primary) !important; }
                        .ts-dropdown .option.selected { background-color: rgba(128, 128, 128, 0.2) !important; }
</style>



                    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Requestor Information --}}
                        <div class="t-section">
                            <div class="t-section-title">Requestor Information</div>
                            <div class="t-grid t-grid-4">
                                <div>
                                    <label for="requested_by" class="t-label">Requested By</label>
                                    <select name="requested_by" id="requested_by" required class="t-input" onchange="fillEmployeeData()">
                                        <option value="">— Select Employee —</option>
                                        @foreach ($employees as $emp)
                                            <option value="{{ $emp->full_name }}" data-position="{{ $emp->position }}" data-branch="{{ $emp->branch }}" data-department="{{ $emp->department }}" {{ (old('requested_by') ?? ($sourceTicket->requested_by ?? '')) == $emp->full_name ? 'selected' : '' }}>
                                                {{ $emp->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('requested_by') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="position" class="t-label">Position</label>
                                    <input type="text" name="position" id="position" value="{{ old('position') ?? ($sourceTicket->position ?? '') }}" readonly class="t-input-readonly" placeholder="Auto-filled">
                                </div>
                                <div>
                                    <label for="department" class="t-label">Department</label>
                                    <input type="text" name="department" id="department" value="{{ old('department') ?? ($sourceTicket->department ?? '') }}" readonly class="t-input-readonly" placeholder="Auto-filled">
                                </div>
                                <div>
                                    <label for="branch" class="t-label">Branch</label>
                                    <input type="text" name="branch" id="branch" value="{{ old('branch') ?? ($sourceTicket->branch ?? '') }}" readonly class="t-input-readonly" placeholder="Auto-filled">
                                </div>
                            </div>
                        </div>

                        {{-- Request Classification --}}
                        <div class="t-section">
                            <div class="t-section-title">Request Classification</div>
                            <div class="t-grid t-grid-3">
                                <div>
                                    <label for="request_type" class="t-label">Request Type</label>
                                    <select name="request_type" id="request_type" required class="t-input">
                                        <option value="">— Select Type —</option>
                                        @foreach ($requestTypes as $type)
                                            <option value="{{ $type }}" {{ (old('request_type') ?? ($sourceTicket->request_type ?? '')) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="affected_system" class="t-label">Affected System <span style="color: var(--text-muted); font-weight:400;">(optional)</span></label>
                                    <input type="text" name="affected_system" id="affected_system" value="{{ old('affected_system') ?? ($sourceTicket->affected_system ?? '') }}" class="t-input" placeholder="e.g. Payroll, Email">
                                </div>
                                <div>
                                    <label for="assisted_by" class="t-label">Assisted By</label>
                                    <select name="assisted_by" id="assisted_by" class="t-input">
                                        <option value="IT03" {{ (old('assisted_by') ?? ($sourceTicket->assisted_by ?? '')) == 'IT03' ? 'selected' : '' }}>Tristan Railey Tan</option>
                                        <option value="IT04" {{ (old('assisted_by') ?? ($sourceTicket->assisted_by ?? '')) == 'IT04' ? 'selected' : '' }}>John Paul Villacorta</option>
                                        <option value="Both" {{ (old('assisted_by') ?? ($sourceTicket->assisted_by ?? '')) == 'Both' ? 'selected' : '' }}>Both</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Issue Description --}}
                        <div class="t-section">
                            <div class="t-section-title">Issue Description</div>
                            <div class="t-grid" style="gap: 12px;">
                                <div>
                                    <label for="request" class="t-label">Short Summary (Title)</label>
                                    <input type="text" name="request" id="request" value="{{ old('request') ?? ($sourceTicket->request ?? '') }}" required class="t-input" placeholder="e.g. Need new mouse, Software installation...">
                                    @error('request') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="request_details" class="t-label">Details</label>
                                    <textarea name="request_details" id="request_details" rows="3" required class="t-input" placeholder="Describe the issue or request in detail...">{{ old('request_details') ?? ($sourceTicket->request_details ?? '') }}</textarea>
                                    @error('request_details') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Resolution & Status --}}
                        <div class="t-section">
                            <div class="t-section-title">Resolution & Status</div>
                            <div class="t-grid t-grid-2">
                                <div>
                                    <label for="status" class="t-label">Status</label>
                                    <select name="status" id="status" class="t-input">
                                        @php $dupStatus = old('status') ?? ($sourceTicket->status ?? 'Resolved'); @endphp
                                        <option value="In Progress" data-description="Currently being worked on." {{ $dupStatus == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Escalated" data-description="Requires higher-level intervention." {{ $dupStatus == 'Escalated' ? 'selected' : '' }}>Escalated</option>
                                        <option value="Resolved" data-description="Successfully completed." {{ $dupStatus == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="Not Complete" data-description="Request not done due to limitations." {{ $dupStatus == 'Not Complete' ? 'selected' : '' }}>Not Complete</option>
                                        <option value="Cancelled" data-description="Request was cancelled by user or staff." {{ $dupStatus == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="remarks" class="t-label">Remarks / Resolution Details <span style="color: var(--text-muted); font-weight:400;">(optional)</span></label>
                                    <input type="text" name="remarks" id="remarks" value="{{ old('remarks') ?? ($sourceTicket->remarks ?? '') }}" class="t-input" placeholder="Brief resolution notes...">
                                </div>
                            </div>
                        </div>

                        {{-- Attachments --}}
                        <div class="t-section">
                            <div class="t-section-title">Attachments</div>
                            <div>
                                <label for="attachments" class="t-label">Upload Files <span style="color: var(--text-muted); font-weight:400;">(optional)</span></label>
                                <div class="t-drop">
                                    <input type="file" name="attachments[]" id="attachments" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.mp4,.avi,.mov,.wmv,.webm,.mkv,.mp3,.wav,.ogg,.aac,.wma,.flac">
                                    <p class="t-drop-hint">Images, PDFs, Docs, Audio, Video — Max 25MB per file</p>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="t-footer">
                            <a href="{{ route('tickets.index') }}" class="t-btn-cancel">Cancel</a>
                            <button type="submit" class="t-btn-submit">Submit Ticket</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function fillEmployeeData() {
        const select = document.getElementById('requested_by');
        const selectedOption = select.options[select.selectedIndex];
        const positionInput = document.getElementById('position');
        const departmentInput = document.getElementById('department');
        const branchInput = document.getElementById('branch');

        if(selectedOption && selectedOption.value) {
            positionInput.value = selectedOption.dataset.position || '';
            departmentInput.value = selectedOption.dataset.department || '';
            branchInput.value = selectedOption.dataset.branch || '';
        } else {
            positionInput.value = '';
            departmentInput.value = '';
            branchInput.value = '';
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ts = new TomSelect("#requested_by", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            maxOptions: null,
            searchField: ['text', 'value'],
            onChange: function(value) {
                if (value && this.options[value] && this.options[value].node) {
                    var opt = this.options[value].node;
                    document.getElementById('position').value = opt.dataset.position || '';
                    document.getElementById('department').value = opt.dataset.department || '';
                    document.getElementById('branch').value = opt.dataset.branch || '';
                } else {
                    document.getElementById('position').value = '';
                    document.getElementById('department').value = '';
                    document.getElementById('branch').value = '';
                }
            }
        });

        var statusTs = new TomSelect("#status", {
            create: false,
            maxOptions: null,
            searchField: ['text'],
            render: {
                option: function(data, escape) {
                    var desc = '';
                    if (data.value === 'In Progress') desc = 'Currently being worked on.';
                    else if (data.value === 'Escalated') desc = 'Requires higher-level intervention.';
                    else if (data.value === 'Resolved') desc = 'Successfully completed.';
                    else if (data.value === 'Not Complete') desc = 'Request not done due to limitations.';
                    else if (data.value === 'Cancelled') desc = 'Request was cancelled by user or staff.';

                    return '<div>' +
                        '<span style="display: block; font-weight: bold;">' + escape(data.text) + '</span>' +
                        '<span style="display: block; font-size: 0.75rem; color: var(--text-muted);">' + escape(desc) + '</span>' +
                    '</div>';
                },
                item: function(data, escape) {
                    return '<div>' + escape(data.text) + '</div>';
                }
            }
        });
    });
</script>
