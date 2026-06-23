<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Ticket') }} — {{ $ticket->ticket_no }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div style="margin-bottom: 20px;">
                        <a href="{{ route('tickets.show', $ticket->id) }}"
                           style="background-color: #6b7280; color: #fff; padding: 8px 16px; border-radius: 5px; text-decoration: none; font-size: 0.9rem;">
                            ← Back to Ticket
                        </a>
                    </div>

                    @if ($ticket->restored_from_archive)
                        <div style="background-color: #dbeafe; color: #1e40af; padding: 10px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; font-size: 0.9rem;">
                            ♻️ This ticket was restored from the Archived Tickets on {{ $ticket->restored_at ? $ticket->restored_at->format('M d, Y h:i A') : 'N/A' }}
                        </div>
                    @endif

                    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div style="margin-bottom: 15px;">
                            <label for="ticket_no" style="font-weight: bold;">Ticket No.:</label><br>
                            <input type="text" id="ticket_no" value="{{ $ticket->ticket_no }}" disabled
                                   style="width: 100%; padding: 8px; margin-top: 5px; background-color: #f3f4f6; color: #6b7280;">
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="requested_by" style="font-weight: bold;">Requested By (Employee Name):</label><br>
                            <input type="text" name="requested_by" id="requested_by" value="{{ old('requested_by', $ticket->requested_by) }}" required style="width: 100%; padding: 8px; margin-top: 5px;">
                            @error('requested_by') <span style="color: red;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="position" style="font-weight: bold;">Position (Optional):</label><br>
                            <input type="text" name="position" id="position" value="{{ old('position', $ticket->position) }}" style="width: 100%; padding: 8px; margin-top: 5px;">
                            @error('position') <span style="color: red;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="branch" style="font-weight: bold;">Branch (Optional):</label><br>
                            <input type="text" name="branch" id="branch" value="{{ old('branch', $ticket->branch) }}" style="width: 100%; padding: 8px; margin-top: 5px;">
                            @error('branch') <span style="color: red;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="request_type" style="font-weight: bold;">Request Type:</label><br>
                            <select name="request_type" id="request_type" required style="width: 100%; padding: 8px; margin-top: 5px;">
                                <option value="">— Select Request Type —</option>
                                @foreach ($requestTypes as $type)
                                    <option value="{{ $type }}" {{ old('request_type', $ticket->request_type) == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('request_type') <span style="color: red;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="affected_system" style="font-weight: bold;">Affected System (Optional):</label><br>
                            <input type="text" name="affected_system" id="affected_system" value="{{ old('affected_system', $ticket->affected_system) }}" style="width: 100%; padding: 8px; margin-top: 5px;">
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="request_details" style="font-weight: bold;">Details of the Issue:</label><br>
                            <textarea name="request_details" id="request_details" rows="4" required style="width: 100%; padding: 8px; margin-top: 5px;">{{ old('request_details', $ticket->request_details) }}</textarea>
                            @error('request_details') <span style="color: red;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="assisted_by" style="font-weight: bold;">Assisted By:</label><br>
                            <select name="assisted_by" id="assisted_by" style="width: 100%; padding: 8px; margin-top: 5px;">
                                <option value="IT03" {{ old('assisted_by', $ticket->assisted_by) == 'IT03' ? 'selected' : '' }}>Tristan Railey Tan</option>
                                <option value="IT04" {{ old('assisted_by', $ticket->assisted_by) == 'IT04' ? 'selected' : '' }}>John Paul Villacorta</option>
                                <option value="Both" {{ old('assisted_by', $ticket->assisted_by) == 'Both' ? 'selected' : '' }}>Both</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="status" style="font-weight: bold;">Status:</label><br>
                            <select name="status" id="status" style="width: 100%; padding: 8px; margin-top: 5px;">
                                <option value="Open" {{ old('status', $ticket->status) == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="In Progress" {{ old('status', $ticket->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Resolved" {{ old('status', $ticket->status) == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="Closed" {{ old('status', $ticket->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="remarks" style="font-weight: bold;">Admin Remarks / Resolution Details:</label><br>
                            <textarea name="remarks" id="remarks" rows="2" style="width: 100%; padding: 8px; margin-top: 5px;">{{ old('remarks', $ticket->remarks) }}</textarea>
                        </div>

                        {{-- Existing Attachments --}}
                        @if ($ticket->attachments->count() > 0)
                            <div style="margin-bottom: 15px;">
                                <label style="font-weight: bold;">Current Attachments:</label>
                                <div style="margin-top: 8px; display: flex; flex-direction: column; gap: 8px;">
                                    @foreach ($ticket->attachments as $attachment)
                                        <div style="display: flex; align-items: center; gap: 10px; padding: 8px 12px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px;">
                                            <input type="checkbox" name="remove_attachments[]" value="{{ $attachment->id }}" id="remove_att_{{ $attachment->id }}">
                                            <label for="remove_att_{{ $attachment->id }}" style="flex: 1; font-size: 0.9rem; cursor: pointer;">
                                                📎 {{ $attachment->file_name }}
                                                <span style="color: #6b7280; font-size: 0.8rem;">({{ number_format($attachment->file_size / 1048576, 2) }} MB)</span>
                                            </label>
                                            <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                                               style="color: #2563eb; font-size: 0.85rem; text-decoration: none;">View</a>
                                        </div>
                                    @endforeach
                                </div>
                                <p style="font-size: 0.8rem; color: #dc2626; margin-top: 6px;">☑️ Check the box next to a file to remove it when saving.</p>
                            </div>
                        @endif

                        {{-- Add New Attachments --}}
                        <div style="margin-bottom: 15px;">
                            <label for="attachments" style="font-weight: bold;">Add New Attachments (Optional):</label><br>
                            <input type="file" name="attachments[]" id="attachments" multiple style="margin-top: 5px;" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.mp4,.avi,.mov,.wmv,.webm,.mkv,.mp3,.wav,.ogg,.aac,.wma,.flac">
                            <p style="font-size: 0.85rem; color: #6b7280; margin-top: 4px;">Accepted: JPG, PNG, GIF, PDF, DOC, DOCX, XLS, XLSX, MP4, AVI, MOV, WMV, WEBM, MKV, MP3, WAV, OGG, AAC, WMA, FLAC — Max 25MB per file</p>
                            @error('attachments.*') <span style="color: red;">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" style="background-color: #000; color: #fff; padding: 10px 20px; border-radius: 5px; cursor: pointer; border: none;">
                            💾 Update Ticket
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
