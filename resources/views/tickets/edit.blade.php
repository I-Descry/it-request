<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Ticket') }} — {{ $ticket->ticket_no }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900">

                    <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 12px;">
                        <a href="{{ route('tickets.show', $ticket->id) }}" style="color: #6b7280; text-decoration: none; font-size: 0.85rem; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            Back to Ticket
                        </a>

                        @if ($ticket->restored_from_archive)
                            <span style="background: #dbeafe; color: #1e40af; padding: 3px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                                ♻️ Restored {{ $ticket->restored_at ? $ticket->restored_at->format('M d, Y') : '' }}
                            </span>
                        @endif
                    </div>

                    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.default.min.css" rel="stylesheet">
<style>
                        .t-label {
                            display: block;
                            font-size: 0.8rem;
                            font-weight: 600;
                            color: #374151;
                            margin-bottom: 4px;
                            letter-spacing: 0.01em;
                        }
                        .t-input {
                            display: block;
                            width: 100%;
                            padding: 7px 10px;
                            font-size: 0.875rem;
                            color: #111827;
                            background-color: #fff;
                            border: 1px solid #d1d5db;
                            border-radius: 6px;
                            outline: none;
                            transition: border-color 0.15s, box-shadow 0.15s;
                        }
                        .t-input:focus {
                            border-color: #2563eb;
                            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
                        }
                        .t-input::placeholder { color: #9ca3af; }
                        .t-input-readonly {
                            display: block;
                            width: 100%;
                            padding: 7px 10px;
                            font-size: 0.875rem;
                            color: #6b7280;
                            background-color: #f9fafb;
                            border: 1px solid #e5e7eb;
                            border-radius: 6px;
                            outline: none;
                            cursor: not-allowed;
                        }
                        .t-section { margin-bottom: 14px; }
                        .t-section-title {
                            font-size: 0.8rem;
                            font-weight: 700;
                            color: #6b7280;
                            text-transform: uppercase;
                            letter-spacing: 0.05em;
                            margin-bottom: 8px;
                            padding-bottom: 4px;
                            border-bottom: 1px solid #e5e7eb;
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
                            background: #f9fafb;
                            text-align: center;
                            transition: border-color 0.2s, background 0.2s;
                        }
                        .t-drop:hover { border-color: #93c5fd; background: #eff6ff; }
                        .t-drop input[type="file"] { cursor: pointer; font-size: 0.8rem; width: 100%; }
                        .t-drop-hint { font-size: 0.7rem; color: #9ca3af; margin-top: 4px; margin-bottom: 0; }
                        .t-footer { display: flex; justify-content: flex-end; align-items: center; gap: 12px; padding-top: 10px; border-top: 1px solid #e5e7eb; }
                        .t-btn-cancel { text-decoration: none; color: #6b7280; font-size: 0.85rem; font-weight: 500; padding: 7px 16px; border-radius: 6px; border: 1px solid #d1d5db; background: #fff; transition: background 0.15s; }
                        .t-btn-cancel:hover { background: #f3f4f6; }
                        .t-btn-submit { background: #2563eb; color: #fff; padding: 7px 20px; border-radius: 6px; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: background 0.15s; box-shadow: 0 1px 3px rgba(37, 99, 235, 0.2); }
                        .t-btn-submit:hover { background: #1d4ed8; }
                                            
                        .ts-wrapper { width: 100% !important; padding: 0 !important; border: none !important; background: none !important; box-shadow: none !important; min-height: 0 !important; outline: none !important; }
                        .ts-wrapper * { box-sizing: border-box !important; }
                        .ts-wrapper.single .ts-control { padding: 7px 10px 7px 30px !important; font-size: 0.875rem !important; background-color: #fff !important; background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="%239ca3af"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>') !important; background-repeat: no-repeat !important; background-position: left 10px center !important; background-size: 1.5em 1.5em !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; box-shadow: none !important; min-height: 0 !important; height: auto !important; }
                        .ts-wrapper.single .ts-control::after { display: none !important; }
                        .ts-wrapper.single .ts-control > input { padding: 0 !important; margin: 0 !important; font-size: 0.875rem !important; line-height: 1.5rem !important; height: 1.5rem !important; min-height: 0 !important; }
                        .ts-wrapper.single .ts-control > .item { margin: 0 !important; padding: 0 !important; font-size: 0.875rem !important; line-height: 1.5rem !important; color: #111827 !important; }
                        .ts-wrapper.single.focus .ts-control { border-color: #3b82f6 !important; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important; }
                        .ts-dropdown { border: 1px solid #d1d5db !important; border-radius: 6px !important; margin-top: 2px !important; box-shadow: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06) !important; background: #fff !important; z-index: 99999 !important; }
                        .ts-dropdown .option { padding: 7px 10px !important; font-size: 0.875rem !important; color: #111827 !important; }
                        .ts-dropdown .option:hover, .ts-dropdown .option.active { background-color: #f3f4f6 !important; color: #111827 !important; }
                        .ts-dropdown .option.selected { background-color: #e5e7eb !important; }
</style>



                    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Requestor Information --}}
                        <div class="t-section">
                            <div class="t-section-title">Requestor Information</div>
                            <div class="t-grid t-grid-4">
                                <div>
                                    <label for="requested_by" class="t-label">Requested By</label>
                                    <select name="requested_by" id="requested_by" required class="t-input" onchange="fillEmployeeData()">
                                        <option value="">— Select Employee —</option>
                                        @foreach ($employees as $emp)
                                            <option value="{{ $emp->full_name }}" data-position="{{ $emp->position }}" data-branch="{{ $emp->branch }}" data-department="{{ $emp->department }}" {{ old('requested_by', $ticket->requested_by) == $emp->full_name ? 'selected' : '' }}>
                                                {{ $emp->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('requested_by') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="position" class="t-label">Position</label>
                                    <input type="text" name="position" id="position" value="{{ old('position', $ticket->position) }}" readonly class="t-input-readonly" placeholder="Auto-filled">
                                </div>
                                <div>
                                    <label for="department" class="t-label">Department</label>
                                    <input type="text" name="department" id="department" value="{{ old('department', $ticket->department) }}" readonly class="t-input-readonly" placeholder="Auto-filled">
                                </div>
                                <div>
                                    <label for="branch" class="t-label">Branch</label>
                                    <input type="text" name="branch" id="branch" value="{{ old('branch', $ticket->branch) }}" readonly class="t-input-readonly" placeholder="Auto-filled">
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
                                            <option value="{{ $type }}" {{ old('request_type', $ticket->request_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="affected_system" class="t-label">Affected System <span style="color:#9ca3af; font-weight:400;">(optional)</span></label>
                                    <input type="text" name="affected_system" id="affected_system" value="{{ old('affected_system', $ticket->affected_system) }}" class="t-input" placeholder="e.g. Payroll, Email">
                                </div>
                                <div>
                                    <label for="assisted_by" class="t-label">Assisted By</label>
                                    <select name="assisted_by" id="assisted_by" class="t-input">
                                        <option value="IT03" {{ old('assisted_by', $ticket->assisted_by) == 'IT03' ? 'selected' : '' }}>Tristan Railey Tan</option>
                                        <option value="IT04" {{ old('assisted_by', $ticket->assisted_by) == 'IT04' ? 'selected' : '' }}>John Paul Villacorta</option>
                                        <option value="Both" {{ old('assisted_by', $ticket->assisted_by) == 'Both' ? 'selected' : '' }}>Both</option>
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
                                    <input type="text" name="request" id="request" value="{{ old('request', $ticket->request) }}" required class="t-input" placeholder="e.g. Need new mouse, Software installation...">
                                    @error('request') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="request_details" class="t-label">Details</label>
                                    <textarea name="request_details" id="request_details" rows="3" required class="t-input" placeholder="Describe the issue or request...">{{ old('request_details', $ticket->request_details) }}</textarea>
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
                                        <option value="In Progress" {{ old('status', $ticket->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Escalated" {{ old('status', $ticket->status) == 'Escalated' ? 'selected' : '' }}>Escalated</option>
                                        <option value="Resolved" {{ old('status', $ticket->status) == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="Not Complete" {{ old('status', $ticket->status) == 'Not Complete' ? 'selected' : '' }}>Not Complete</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="remarks" class="t-label">Remarks <span style="color:#9ca3af; font-weight:400;">(optional)</span></label>
                                    <input type="text" name="remarks" id="remarks" value="{{ old('remarks', $ticket->remarks) }}" class="t-input" placeholder="Brief resolution notes...">
                                </div>
                            </div>
                        </div>

                        {{-- Attachments --}}
                        <div class="t-section">
                            <div class="t-section-title">Attachments</div>

                            @if ($ticket->attachments->count() > 0)
                                <div class="t-attach-list">
                                    <span class="t-label" style="margin-bottom: 6px;">Current Files</span>
                                    @foreach ($ticket->attachments as $attachment)
                                        <div class="t-attach-item">
                                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="t-attach-link">
                                                📄 {{ $attachment->file_name }}
                                            </a>
                                            <label class="t-attach-delete">
                                                <input type="checkbox" name="delete_attachments[]" value="{{ $attachment->id }}"> Remove
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div>
                                <label for="attachments" class="t-label">Upload Additional Files <span style="color:#9ca3af; font-weight:400;">(optional)</span></label>
                                <div class="t-drop">
                                    <input type="file" name="attachments[]" id="attachments" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.mp4,.avi,.mov,.wmv,.webm,.mkv,.mp3,.wav,.ogg,.aac,.wma,.flac">
                                    <p class="t-drop-hint">Images, PDFs, Docs, Audio, Video — Max 25MB per file</p>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="t-footer">
                            <a href="{{ route('tickets.index') }}" class="t-btn-cancel">Cancel</a>
                            <button type="submit" class="t-btn-submit">Update Ticket</button>
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
        new TomSelect("#requested_by", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    });
    function deleteAttachment(id) {
        if(confirm('Are you sure you want to delete this attachment?')) {
            fetch(`/tickets/attachments/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById(`attach-${id}`).remove();
                }
            });
        }
    }
</script>

