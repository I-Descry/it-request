<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add SSO Account') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 dark:text-gray-100">
                    
                    <div style="margin-bottom: 10px;">
                        <a href="{{ route('sso_accounts.index') }}" style="color: var(--text-light); text-decoration: none; font-size: 0.85rem; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            Back to SSO Accounts
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

                    <form action="{{ route('sso_accounts.store') }}" method="POST" autocomplete="off">
                        @csrf
                        
                        {{-- Account Details --}}
                        <div class="t-section">
                            <div class="t-section-title">Account Details</div>
                            <div class="t-grid t-grid-2" style="margin-bottom: 10px;">
                                <div>
                                    <label for="username" class="t-label">Username</label>
                                    <input type="text" name="username" id="username" value="{{ old('username') }}" required class="t-input" autocomplete="new-username">
                                    @error('username') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="password" class="t-label">Temporary Password</label>
                                    <div style="position: relative; display: flex; align-items: stretch; border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden; background: var(--bg-card); transition: border-color 0.15s, box-shadow 0.15s;" x-data="{ show: false }" onfocusin="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37, 99, 235, 0.12)';" onfocusout="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none';">
                                        <input :type="show ? 'text' : 'password'" name="password" id="password" value="{{ old('password') }}" class="t-input" style="border: none; box-shadow: none; border-radius: 0;" autocomplete="new-password">
                                        <button type="button" @click="show = !show" style="background: none; border: none; padding: 0 10px; cursor: pointer; color: #6b7280; display: flex; align-items: center; justify-content: center; border-left: 1px solid var(--border-color);" tabindex="-1">
                                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('password') <span class="t-error" style="display:block; margin-top:4px;">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="t-grid t-grid-3">
                                <div>
                                    <label for="account_type" class="t-label">Account Type</label>
                                    <select name="account_type" id="account_type" required class="t-input">
                                        <option value="New" {{ old('account_type') == 'New' ? 'selected' : '' }}>New</option>
                                        <option value="Transferred" {{ old('account_type') == 'Transferred' ? 'selected' : '' }}>Transferred</option>
                                    </select>
                                    @error('account_type') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div id="transferred_from_container" style="display: none;">
                                    <label for="transferred_from" class="t-label">Transferred From</label>
                                    <input type="text" name="transferred_from" id="transferred_from" value="{{ old('transferred_from') }}" class="t-input" placeholder="e.g. John Doe">
                                    @error('transferred_from') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="status" class="t-label">Status</label>
                                    <select name="status" id="status" required class="t-input">
                                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="Locked" {{ old('status') == 'Locked' ? 'selected' : '' }}>Locked</option>
                                    </select>
                                    @error('status') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- User Information --}}
                        <div class="t-section">
                            <div class="t-section-title">User Information</div>
                            <div class="t-grid t-grid-2" style="margin-bottom: 10px;">
                                <div>
                                    <label for="employee_id" class="t-label">Link to Employee <span style="color: var(--text-muted); font-weight:400;">(optional)</span></label>
                                    <select name="employee_id" id="employee_id" class="t-input">
                                        <option value="">-- No Employee Linked --</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" 
                                                data-name="{{ $employee->full_name }}" 
                                                data-dept="{{ $employee->department }}" 
                                                data-pos="{{ $employee->position }}"
                                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->full_name }} ({{ $employee->nfp_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="name" class="t-label">Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="t-input">
                                    @error('name') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="t-grid t-grid-3">
                                <div>
                                    <label for="email" class="t-label">Email <span style="color: var(--text-muted); font-weight:400;">(optional)</span></label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="t-input">
                                    @error('email') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="department" class="t-label">Department</label>
                                    <select name="department" id="department" class="t-input">
                                        <option value="">— Select Department —</option>
                                        @foreach(array_keys($hierarchy) as $dept)
                                            <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                        @endforeach
                                    </select>
                                    @error('department') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="position" class="t-label">Position</label>
                                    <select name="position" id="position" class="t-input" data-old="{{ old('position') }}">
                                        <option value="">— Select Position —</option>
                                    </select>
                                    @error('position') <span class="t-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="t-footer">
                            <a href="{{ route('sso_accounts.index') }}" class="t-btn-cancel">Cancel</a>
                            <button type="submit" class="t-btn-submit">Save Account</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const employeeSelect = document.getElementById('employee_id');
            const nameInput = document.getElementById('name');
            const deptSelect = document.getElementById('department');
            const posSelect = document.getElementById('position');
            const typeSelect = document.getElementById('account_type');
            const transferContainer = document.getElementById('transferred_from_container');

            function toggleTransfer() {
                if (typeSelect.value === 'Transferred') {
                    transferContainer.style.display = 'block';
                } else {
                    transferContainer.style.display = 'none';
                    document.getElementById('transferred_from').value = '';
                }
            }

            typeSelect.addEventListener('change', toggleTransfer);
            toggleTransfer(); // Initial load

            const hierarchy = @json($hierarchy);
            
            function updatePositions(triggerChange = false) {
                const dept = deptSelect.value;
                const oldPos = posSelect.getAttribute('data-old');
                
                posSelect.innerHTML = '<option value="">— Select Position —</option>';
                
                if (dept && hierarchy[dept]) {
                    hierarchy[dept].forEach(function(pos) {
                        const opt = document.createElement('option');
                        opt.value = pos;
                        opt.textContent = pos;
                        if (pos === oldPos) {
                            opt.selected = true;
                        }
                        posSelect.appendChild(opt);
                    });
                }
            }

            deptSelect.addEventListener('change', function() {
                // When manual change, clear the old so it doesn't auto select
                posSelect.removeAttribute('data-old');
                updatePositions();
            });

            // Run on load
            updatePositions();

            employeeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value !== '') {
                    nameInput.value = selectedOption.getAttribute('data-name') || '';
                    
                    const newDept = selectedOption.getAttribute('data-dept') || '';
                    const newPos = selectedOption.getAttribute('data-pos') || '';
                    
                    // Auto-fill department
                    let foundDept = false;
                    for (let i = 0; i < deptSelect.options.length; i++) {
                        if (deptSelect.options[i].value === newDept) {
                            deptSelect.selectedIndex = i;
                            foundDept = true;
                            break;
                        }
                    }
                    
                    if (foundDept) {
                        posSelect.setAttribute('data-old', newPos);
                        updatePositions();
                    } else {
                        // If no match found, clear out
                        deptSelect.selectedIndex = 0;
                        updatePositions();
                    }
                }
            });
        });
    </script>
</x-app-layout>
