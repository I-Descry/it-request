<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Employee') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 dark:text-gray-100">

                    <div style="margin-bottom: 10px;">
                        <a href="{{ route('employees.index') }}" style="color: var(--text-light); text-decoration: none; font-size: 0.85rem; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            Back to Employees
                        </a>
                    </div>

                    <style>
                        .t-label { display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-primary); margin-bottom: 4px; letter-spacing: 0.01em; }
                        .t-input { display: block; width: 100%; padding: 7px 10px; font-size: 0.875rem; color: var(--text-primary); background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 6px; outline: none; transition: border-color 0.15s, box-shadow 0.15s; }
                        .t-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12); }
                        .t-input::placeholder { color: var(--text-muted); }
                        .t-section { margin-bottom: 14px; }
                        .t-section-title { font-size: 0.8rem; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 1px solid var(--border-color); }
                        .t-grid { display: grid; gap: 10px; }
                        .t-grid-2 { grid-template-columns: 1fr 1fr; }
                        .t-grid-3 { grid-template-columns: 1fr 1fr 1fr; }
                        .t-error { color: #dc2626; font-size: 0.75rem; margin-top: 2px; display: block; }
                        .t-footer { display: flex; justify-content: flex-end; align-items: center; gap: 12px; padding-top: 10px; border-top: 1px solid var(--border-color); }
                        .t-btn-cancel { text-decoration: none; color: var(--text-light); font-size: 0.85rem; font-weight: 500; padding: 7px 16px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-card); transition: background 0.15s; }
                        .t-btn-cancel:hover { background: var(--th-bg); }
                        .t-btn-submit { background: #2563eb; color: var(--bg-card); padding: 7px 20px; border-radius: 6px; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: background 0.15s; box-shadow: 0 1px 3px rgba(37, 99, 235, 0.2); }
                        .t-btn-submit:hover { background: #1d4ed8; }
                    </style>

                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf

                        {{-- Personal Information --}}
                        <div class="t-section">
                            <div class="t-section-title">Personal Information</div>
                            <div class="t-grid t-grid-2" style="margin-bottom: 10px;">
                                <div>
                                    <label for="first_name" class="t-label">First Name</label>
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="t-input" placeholder="e.g. John">
                                    @error('first_name') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="middle_name" class="t-label">Middle Name <span style="color: var(--text-muted); font-weight:400;">(optional)</span></label>
                                    <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" class="t-input" placeholder="e.g. Santos">
                                    @error('middle_name') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="t-grid t-grid-2">
                                <div>
                                    <label for="last_name" class="t-label">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="t-input" placeholder="e.g. Dela Cruz">
                                    @error('last_name') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="suffix" class="t-label">Suffix <span style="color: var(--text-muted); font-weight:400;">(optional)</span></label>
                                    <input type="text" name="suffix" id="suffix" value="{{ old('suffix') }}" class="t-input" placeholder="e.g. Jr., Sr., III">
                                    @error('suffix') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Employment Details --}}
                        <div class="t-section">
                            <div class="t-section-title">Employment Details</div>
                            <div class="t-grid t-grid-3" style="margin-bottom: 10px;">
                                <div>
                                    <label for="nfp_id" class="t-label">Employee ID <span style="color: var(--text-muted); font-weight:400;">(optional)</span></label>
                                    <div style="display: flex; align-items: stretch; border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden; background: var(--bg-card); transition: border-color 0.15s, box-shadow 0.15s;" onfocusin="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37, 99, 235, 0.12)';" onfocusout="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';">
                                        <span style="background: var(--panel-bg); color: var(--text-primary); padding: 7px 10px; font-size: 0.875rem; border-right: 1px solid #d1d5db; display: flex; align-items: center;">NFP-</span>
                                        <input type="text" name="nfp_id" id="nfp_id" value="{{ str_replace('NFP-', '', old('nfp_id')) }}" maxlength="4" class="t-input" style="border: none; border-radius: 0; box-shadow: none; outline: none; width: 100%;" placeholder="e.g. 1234">
                                    </div>
                                    @error('nfp_id') <span class="t-error" style="display:block; margin-top:4px;">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="department" class="t-label">Department</label>
                                    <select name="department" id="department" required class="t-input">
                                        <option value="">— Select Department —</option>
                                        @foreach(array_keys($hierarchy) as $dept)
                                            <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                        @endforeach
                                    </select>
                                    @error('department') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="position" class="t-label">Position</label>
                                    <select name="position" id="position" required class="t-input" data-old="{{ old('position') }}">
                                        <option value="">— Select Position —</option>
                                    </select>
                                    @error('position') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="t-grid t-grid-2">
                                <div>
                                    <label for="branch" class="t-label">Branch</label>
                                    <select name="branch" id="branch" required class="t-input">
                                        <option value="">— Select Branch —</option>
                                        @foreach(['HEAD OFFICE', 'NDD BACOLOD', 'NDD BAESA', 'NDD BATAAN', 'NDD BATANGAS', 'NDD CAVITE', 'NDD CDO', 'NDD CEBU', 'NDD DAVAO', 'NDD DIPOLOG', 'NDD DUMAGUETE', 'NDD ILOILO', 'NDD LA UNION', 'NDD LAGUNA', 'NDD LAS PIÑAS', 'NDD NUEVA ECIJA', 'NDD PULILAN', 'NDD ROXAS', 'NDD SAN FRANCISCO', 'NDD TACLOBAN', 'NDD TARLAC', 'NDD TAYTAY'] as $br)
                                            <option value="{{ $br }}" {{ old('branch') == $br ? 'selected' : '' }}>{{ $br }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="contact_no_suffix" class="t-label">Contact No. <span style="color: var(--text-muted); font-weight:400;">(optional)</span></label>
                                    <div style="display: flex; align-items: stretch; border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden; background: var(--bg-card); transition: border-color 0.15s, box-shadow 0.15s;" onfocusin="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37, 99, 235, 0.12)';" onfocusout="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';">
                                        <span style="background: var(--panel-bg); color: var(--text-primary); padding: 7px 10px; font-size: 0.875rem; border-right: 1px solid #d1d5db; display: flex; align-items: center;">+63</span>
                                        <input type="text" name="contact_no_suffix" id="contact_no_suffix" value="{{ str_replace('+63', '', old('contact_no')) }}" maxlength="10" class="t-input" style="border: none; border-radius: 0; box-shadow: none; outline: none; width: 100%;" placeholder="e.g. 9123456789" oninput="this.value = this.value.replace(/[^0-9]/g, ''); document.getElementById('contact_no_hidden').value = this.value ? '+63' + this.value : '';">
                                        <input type="hidden" name="contact_no" id="contact_no_hidden" value="{{ old('contact_no') }}">
                                    </div>
                                    @error('contact_no') <span class="t-error" style="display:block; margin-top:4px;">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="t-footer">
                            <a href="{{ route('employees.index') }}" class="t-btn-cancel">Cancel</a>
                            <button type="submit" class="t-btn-submit">Save Employee</button>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const hierarchy = @json($hierarchy);
                            const deptSelect = document.getElementById('department');
                            const posSelect = document.getElementById('position');
                            
                            function updatePositions() {
                                const dept = deptSelect.value;
                                const oldPos = posSelect.getAttribute('data-old');
                                
                                posSelect.innerHTML = '<option value="">— Select Position —</option>';
                                
                                if (dept && hierarchy[dept]) {
                                    hierarchy[dept].forEach(function(pos) {
                                        const selected = (pos === oldPos) ? 'selected' : '';
                                        posSelect.innerHTML += `<option value="${pos}" ${selected}>${pos}</option>`;
                                    });
                                }
                            }
                            
                            deptSelect.addEventListener('change', updatePositions);
                            
                            // Initialize on load to restore old inputs if validation failed
                            if (deptSelect.value) {
                                updatePositions();
                            }
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
