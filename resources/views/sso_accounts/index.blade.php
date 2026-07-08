<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('SSO Accounts') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ linkModalOpen: false, linkUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if (session('success'))
                        <div style="color: green; margin-bottom: 15px; font-weight: bold;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                        <a href="{{ route('sso_accounts.create') }}" class="dk-btn dk-btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Add SSO Account
                        </a>
                    </div>

                    <!-- Search Form -->
                    <div style="background: var(--panel-bg); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 20px;">
                        <form method="GET" action="{{ route('sso_accounts.index') }}" id="filterForm" style="display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap;">
                            <div style="flex: 1; min-width: 250px;">
                                <label for="search" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 5px;">Search Accounts</label>
                                <div style="position: relative;">
                                    <div style="position: absolute; top: 9px; left: 10px; color: var(--text-muted);">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Username, Name, Email..." style="width: 100%; padding: 8px 10px 8px 35px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.9rem; outline: none;">
                                </div>
                            </div>
                            
                            <div>
                                <button type="submit" class="dk-btn dk-btn-primary" style="height: 38px;">Search</button>
                            </div>
                            @if(request()->has('search'))
                            <div>
                                <a href="{{ route('sso_accounts.index') }}" class="dk-btn dk-btn-outline" style="height: 38px;">Clear</a>
                            </div>
                            @endif
                        </form>
                    </div>

                    <div id="sso-table-container">
                        <div class="dk-table-wrap">
                        <table class="dk-table" style="white-space: nowrap;">
                            <thead>
                                <tr style="user-select: none;">
                                    <th>Username</th>
                                    <th>Temporary Password</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ssoAccounts as $account)
                                    <tr>
                                        <td style="font-weight: bold; color: #2563eb;">{{ $account->username }}</td>
                                        <td>
                                            @if($account->password_changed)
                                                <span style="font-size: 0.75rem; background: #e0e7ff; color: #4338ca; padding: 2px 8px; border-radius: 9999px;">Changed by user</span>
                                            @elseif($account->password)
                                                <div x-data="{ showPw: false }" style="display: flex; align-items: center; gap: 8px;">
                                                    <span style="font-family: monospace; letter-spacing: 1px;" x-text="showPw ? '{{ addslashes($account->password) }}' : '••••••••'"></span>
                                                    <button type="button" @click="showPw = !showPw" style="background: none; border: none; padding: 0; cursor: pointer; color: #6b7280; display: flex; align-items: center; justify-content: center;" title="Toggle Password Visibility">
                                                        <svg x-show="!showPw" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </svg>
                                                        <svg x-show="showPw" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @else
                                                <span style="color: var(--text-muted); font-size: 0.85rem;">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($account->account_type === 'New')
                                                <span style="font-size: 0.75rem; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 9999px;">New</span>
                                            @else
                                                <span style="font-size: 0.75rem; background: #fef3c7; color: #b45309; padding: 2px 8px; border-radius: 9999px;" title="Transferred From: {{ $account->transferred_from ?? 'Unknown' }}">Transferred</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $account->name }}
                                            @if($account->employee_id)
                                                <span title="Linked to Employee Record" style="color: #10b981; margin-left: 5px;">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline; vertical-align:text-bottom;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $account->department ?? '—' }}</td>
                                        <td>{{ $account->position ?? '—' }}</td>
                                        <td>{{ $account->email ?? '—' }}</td>
                                        <td>
                                            @if($account->status === 'Active')
                                                <span style="font-size: 0.75rem; background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 9999px;">Active</span>
                                            @elseif($account->status === 'Inactive')
                                                <span style="font-size: 0.75rem; background: #f3f4f6; color: #4b5563; padding: 2px 8px; border-radius: 9999px;">Inactive</span>
                                            @elseif($account->status === 'Locked')
                                                <span style="font-size: 0.75rem; background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 9999px;">Locked</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if(!$account->password_changed && $account->password)
                                                <form action="{{ route('sso_accounts.mark_password_changed', $account->id) }}" method="POST" style="display: inline-block; margin: 0;" x-data @submit.prevent="$dispatch('open-confirm', { title: 'Mark Password Changed', message: 'Are you sure you want to mark this temporary password as changed by the user?', buttonText: 'Mark Changed', buttonColor: '#10b981', form: $el })">
                                                    @csrf
                                                    <button type="submit" class="action-btn" style="background: none; border: none; padding: 0; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; color: #10b981;" data-tooltip="Mark Password Changed">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <div style="display: inline-block; margin: 0;">
                                                    <button type="button" disabled style="background: none; border: none; padding: 0; cursor: not-allowed; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; color: #d1d5db;" data-tooltip="Password Already Changed">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endif
                                            @if(!$account->employee_id)
                                                <button type="button" @click="linkUrl = '{{ route('sso_accounts.link', $account->id) }}'; linkModalOpen = true" class="action-btn" style="background: none; border: none; padding: 0; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; color: #4f46e5;" data-tooltip="Link Employee">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                                    </svg>
                                                </button>
                                            @else
                                                <div style="display: inline-block; margin: 0;">
                                                    <button type="button" disabled style="background: none; border: none; padding: 0; cursor: not-allowed; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; color: #d1d5db;" data-tooltip="Already Linked">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endif
                                            <a href="{{ route('sso_accounts.edit', $account->id) }}" class="action-btn edit" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; color: #059669;" data-tooltip="Edit Account">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('sso_accounts.destroy', $account->id) }}" method="POST" style="display: inline-block; margin: 0;" x-data @submit.prevent="$dispatch('open-confirm', { title: 'Delete Account', message: 'Are you sure you want to delete this SSO account? This action cannot be undone.', buttonText: 'Delete', buttonColor: '#dc2626', form: $el })">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn edit" style="background: none; border: none; padding: 0; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; color: #dc2626;" data-tooltip="Delete Account">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 20px; color: var(--text-light);">No SSO accounts recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                        
                        <div style="margin-top: 15px;">
                            {{ $ssoAccounts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Link Employee Modal -->
        <div x-show="linkModalOpen" 
             style="display: none; position: fixed; inset: 0; z-index: 9999; overflow-y: auto;" 
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 16px;">
                
                <div x-show="linkModalOpen" 
                     @click="linkModalOpen = false" 
                     x-transition.opacity 
                     style="position: fixed; inset: 0; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); transition: opacity;"></div>

                <div x-show="linkModalOpen" 
                     x-transition 
                     style="position: relative; background-color: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-color); width: 100%; max-width: 500px; padding: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                    <form x-bind:action="linkUrl" method="POST">
                        @csrf
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                            <div style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; padding: 8px; border-radius: 50%;">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"></path></svg>
                            </div>
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin: 0;" id="modal-title">
                                Link Employee to SSO
                            </h3>
                        </div>
                        <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 20px;">
                            Select an employee to link to this SSO account. The SSO account name, department, and position will be updated to match the employee's record.
                        </p>
                        <div style="margin-bottom: 24px;">
                            <label style="display: block; font-size: 0.9rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px;">Select Employee <span style="color: #ef4444;">*</span></label>
                            
                            <div x-data="{
                                open: false,
                                search: '',
                                selected: '',
                                selectedText: '-- Choose Employee --',
                                options: [
                                    @foreach($employees as $employee)
                                        { value: '{{ $employee->id }}', text: '{{ addslashes($employee->full_name) }} ({{ $employee->nfp_id }})' },
                                    @endforeach
                                ],
                                get filteredOptions() {
                                    if (this.search === '') return this.options;
                                    return this.options.filter(opt => opt.text.toLowerCase().includes(this.search.toLowerCase()));
                                },
                                selectOption(opt) {
                                    this.selected = opt.value;
                                    this.selectedText = opt.text;
                                    this.open = false;
                                    this.search = '';
                                }
                            }" @click.outside="open = false" style="position: relative;">
                                <input type="hidden" name="employee_id" :value="selected" required>
                                
                                <button type="button" @click="open = !open" style="width: 100%; text-align: left; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: 6px; background-color: var(--panel-bg); color: var(--text-primary); display: flex; justify-content: space-between; align-items: center; outline: none; cursor: pointer;">
                                    <span x-text="selectedText"></span>
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                
                                <div x-show="open" style="position: absolute; top: 100%; left: 0; right: 0; margin-top: 4px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 6px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.5); z-index: 100; overflow: hidden; display: none;">
                                    <div style="padding: 8px; border-bottom: 1px solid var(--border-color);">
                                        <input type="text" x-model="search" placeholder="Search employee..." style="width: 100%; padding: 6px 10px; border: 1px solid var(--border-color); border-radius: 4px; background: var(--panel-bg); color: var(--text-primary); outline: none;">
                                    </div>
                                    <div style="max-height: 200px; overflow-y: auto;">
                                        <template x-for="opt in filteredOptions" :key="opt.value">
                                            <div @click="selectOption(opt)" 
                                                 style="padding: 8px 12px; cursor: pointer;"
                                                 x-bind:style="selected === opt.value ? 'background: rgba(59,130,246,0.1); color: #3b82f6;' : 'color: var(--text-primary);'"
                                                 x-text="opt.text"
                                                 onmouseover="this.style.background='rgba(59,130,246,0.05)'"
                                                 onmouseout="if(this.style.color !== 'rgb(59, 130, 246)') this.style.background='transparent'">
                                            </div>
                                        </template>
                                        <div x-show="filteredOptions.length === 0" style="padding: 10px; color: var(--text-muted); text-align: center;">No matches found</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 12px;">
                            <button type="button" @click="linkModalOpen = false" class="dk-btn" style="background: transparent; border: 1px solid var(--border-color); color: var(--text-secondary);">
                                Cancel
                            </button>
                            <button type="submit" class="dk-btn" style="background: #3b82f6; color: white; border: none;">
                                Link Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
