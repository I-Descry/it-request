<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Record a New IT Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div style="margin-bottom: 15px;">
                            <label for="requested_by" style="font-weight: bold;">Requested By (Employee Name):</label><br>
                            <input type="text" name="requested_by" id="requested_by" value="{{ old('requested_by') }}" required style="width: 100%; padding: 8px; margin-top: 5px;">
                            @error('requested_by') <span style="color: red;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="position" style="font-weight: bold;">Position (Optional):</label><br>
                            <input type="text" name="position" id="position" value="{{ old('position') }}" style="width: 100%; padding: 8px; margin-top: 5px;">
                            @error('position') <span style="color: red;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="branch" style="font-weight: bold;">Branch (Optional):</label><br>
                            <input type="text" name="branch" id="branch" value="{{ old('branch') }}" style="width: 100%; padding: 8px; margin-top: 5px;">
                            @error('branch') <span style="color: red;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="request_type" style="font-weight: bold;">Request Type:</label><br>
                            <select name="request_type" id="request_type" required style="width: 100%; padding: 8px; margin-top: 5px;">
                                <option value="">— Select Request Type —</option>
                                @foreach ($requestTypes as $type)
                                    <option value="{{ $type }}" {{ old('request_type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('request_type') <span style="color: red;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="affected_system" style="font-weight: bold;">Affected System (Optional):</label><br>
                            <input type="text" name="affected_system" id="affected_system" value="{{ old('affected_system') }}" style="width: 100%; padding: 8px; margin-top: 5px;">
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="request_details" style="font-weight: bold;">Details of the Issue:</label><br>
                            <textarea name="request_details" id="request_details" rows="4" required style="width: 100%; padding: 8px; margin-top: 5px;">{{ old('request_details') }}</textarea>
                            @error('request_details') <span style="color: red;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="assisted_by" style="font-weight: bold;">Assisted By:</label><br>
                            <select name="assisted_by" id="assisted_by" style="width: 100%; padding: 8px; margin-top: 5px;">
                                <option value="IT03">Tristan Railey Tan</option>
                                <option value="IT04">John Paul Villacorta</option>
                                <option value="Both">Both</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="status" style="font-weight: bold;">Status:</label><br>
                            <select name="status" id="status" style="width: 100%; padding: 8px; margin-top: 5px;">
                                <option value="Open">Open</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Resolved" selected>Resolved</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="remarks" style="font-weight: bold;">Admin Remarks / Resolution Details:</label><br>
                            <textarea name="remarks" id="remarks" rows="2" style="width: 100%; padding: 8px; margin-top: 5px;">{{ old('remarks') }}</textarea>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label for="attachments" style="font-weight: bold;">Attachments (Optional — proof of problem, error screenshots, etc.):</label><br>
                            <input type="file" name="attachments[]" id="attachments" multiple style="margin-top: 5px;" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.mp4,.avi,.mov,.wmv,.webm,.mkv,.mp3,.wav,.ogg,.aac,.wma,.flac">
                            <p style="font-size: 0.85rem; color: #6b7280; margin-top: 4px;">Accepted: JPG, PNG, GIF, PDF, DOC, DOCX, XLS, XLSX, MP4, AVI, MOV, WMV, WEBM, MKV, MP3, WAV, OGG, AAC, WMA, FLAC — Max 25MB per file</p>
                            @error('attachments.*') <span style="color: red;">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" style="background-color: #000; color: #fff; padding: 10px 20px; border-radius: 5px; cursor: pointer; border: none;">
                            Save Ticket
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>