<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employees') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if (session('success'))
                        <div style="color: green; margin-bottom: 15px; font-weight: bold;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                        <a href="{{ route('employees.create') }}" class="dk-btn dk-btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Add Employee
                        </a>
                        <button type="button" onclick="openHierarchyModal()" class="dk-btn dk-btn-outline">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Manage Hierarchy
                        </button>
                        <a href="{{ route('employees.directory') }}" class="dk-btn dk-btn-secondary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                            Directory
                        </a>
                    </div>

                    <!-- Search & Filter Form -->
                    <div style="background: var(--panel-bg); padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 20px;">
                        <form method="GET" action="{{ route('employees.index') }}" id="filterForm" style="display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap;">
                            <!-- Hidden inputs for sorting -->
                            <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by', 'last_name') }}">
                            <input type="hidden" name="sort_dir" id="sort_dir" value="{{ request('sort_dir', 'asc') }}">
                            
                            <div style="flex: 1; min-width: 250px;">
                                <label for="search" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 5px;">Search Employees</label>
                                <div style="position: relative;">
                                    <div style="position: absolute; top: 9px; left: 10px; color: var(--text-muted);">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="ID, Name, Position..." style="width: 100%; padding: 8px 10px 8px 35px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.9rem; outline: none;">
                                </div>
                            </div>
                            
                            <div style="width: 180px;">
                                <label for="filter_dept" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 5px;">Department Filter</label>
                                <select name="filter_dept" id="filter_dept" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.9rem; outline: none;" onchange="document.getElementById('filterForm').requestSubmit();">
                                    <option value="">All Departments</option>
                                    @foreach(array_keys($hierarchy) as $dept)
                                        <option value="{{ $dept }}" {{ request('filter_dept') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="width: 140px;">
                                <label for="filter_status" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 5px;">Status Filter</label>
                                <select name="filter_status" id="filter_status" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.9rem; outline: none;" onchange="document.getElementById('filterForm').requestSubmit();">
                                    <option value="Active" {{ request('filter_status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Resigned" {{ request('filter_status') == 'Resigned' ? 'selected' : '' }}>Resigned</option>
                                    <option value="All" {{ request('filter_status') == 'All' ? 'selected' : '' }}>All Statuses</option>
                                </select>
                            </div>

                            <div style="width: 180px;">
                                <label for="filter_branch" style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 5px;">Branch Filter</label>
                                <select name="filter_branch" id="filter_branch" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.9rem; outline: none;" onchange="document.getElementById('filterForm').requestSubmit();">
                                    <option value="">All Branches</option>
                                    @foreach(['HEAD OFFICE', 'NDD BACOLOD', 'NDD BAESA', 'NDD BATAAN', 'NDD BATANGAS', 'NDD CAVITE', 'NDD CDO', 'NDD CEBU', 'NDD DAVAO', 'NDD DIPOLOG', 'NDD DUMAGUETE', 'NDD ILOILO', 'NDD LA UNION', 'NDD LAGUNA', 'NDD LAS PIÑAS', 'NDD NUEVA ECIJA', 'NDD PULILAN', 'NDD ROXAS', 'NDD SAN FRANCISCO', 'NDD TACLOBAN', 'NDD TARLAC', 'NDD TAYTAY'] as $br)
                                        <option value="{{ $br }}" {{ request('filter_branch') == $br ? 'selected' : '' }}>{{ $br }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <button type="submit" class="dk-btn dk-btn-primary" style="height: 38px;">Search</button>
                            </div>
                            @if(request()->hasAny(['search', 'filter_dept', 'filter_branch']))
                            <div>
                                <a href="{{ route('employees.index') }}" class="dk-btn dk-btn-outline" style="height: 38px;">Clear</a>
                            </div>
                            @endif
                        </form>
                    </div>

                    <script>
                        function sortBy(column) {
                            let currentSort = document.getElementById('sort_by').value;
                            let currentDir = document.getElementById('sort_dir').value;
                            if (currentSort === column) {
                                document.getElementById('sort_dir').value = currentDir === 'asc' ? 'desc' : 'asc';
                            } else {
                                document.getElementById('sort_by').value = column;
                                document.getElementById('sort_dir').value = 'asc';
                            }
                            document.getElementById('filterForm').submit();
                        }
                    </script>
                    <div id="employees-table-container">
                        <div class="dk-table-wrap">
                        <table class="dk-table" style="white-space: nowrap;">
                            <thead>
                                @php
                                    $sortCol = request('sort_by', 'last_name');
                                    $sortDir = request('sort_dir', 'asc');
                                    function sortArrow($col, $sCol, $sDir) {
                                        if ($col !== $sCol) return "<span style='color: #cbd5e1; font-size: 0.8rem; margin-left: 4px;'>↕</span>";
                                        return $sDir === 'asc' ? "<span style='color: #1f2937; font-size: 0.8rem; margin-left: 4px;'>↑</span>" : "<span style='color: #1f2937; font-size: 0.8rem; margin-left: 4px;'>↓</span>";
                                    }
                                @endphp
                                <tr style="user-select: none;">
                                    <th style="cursor: pointer;" onclick="sortBy('nfp_id')">Employee ID {!! sortArrow('nfp_id', $sortCol, $sortDir) !!}</th>
                                    <th style="cursor: pointer;" onclick="sortBy('last_name')">Name {!! sortArrow('last_name', $sortCol, $sortDir) !!}</th>
                                    <th style="cursor: pointer;" onclick="sortBy('position')">Position {!! sortArrow('position', $sortCol, $sortDir) !!}</th>
                                    <th style="cursor: pointer;" onclick="sortBy('department')">Department {!! sortArrow('department', $sortCol, $sortDir) !!}</th>
                                    <th style="cursor: pointer;" onclick="sortBy('branch')">Branch {!! sortArrow('branch', $sortCol, $sortDir) !!}</th>
                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->nfp_id }}</td>
                                        <td style="font-weight: bold;">
                                            {{ $employee->full_name }}
                                            @if($employee->employment_status === 'Resigned')
                                                <span style="font-size: 0.7rem; background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 4px; margin-left: 6px; vertical-align: middle;">Resigned</span>
                                            @endif
                                        </td>
                                        <td>{{ $employee->position }}</td>
                                        <td>{{ $employee->department ?? '—' }}</td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 6px;">
                                                @if ($employee->branch && strtoupper($employee->branch) === 'HEAD OFFICE')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#2563eb" style="width: 14px; height: 14px; flex-shrink: 0;" title="Head Office">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-2.25a1.5 1.5 0 0 1 1.5-1.5h3a1.5 1.5 0 0 1 1.5 1.5V21" />
                                                    </svg>
                                                    <span style="color: var(--text-secondary);">{{ $employee->branch }}</span>
                                                @elseif($employee->branch)
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4f46e5" style="width: 14px; height: 14px; flex-shrink: 0;" title="Remote Branch">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                                    </svg>
                                                    <span style="color: var(--text-secondary);">{{ $employee->branch }}</span>
                                                @else
                                                    <span style="color: #9ca3af;">N/A</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="{{ route('employees.show', $employee->id) }}" class="action-btn view" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; color: #2563eb;" data-tooltip="View Employee">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('employees.edit', $employee->id) }}" class="action-btn edit" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; color: #059669;" data-tooltip="Edit Employee">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </a>
                                            @if($employee->employment_status === 'Active')
                                            <form action="{{ route('employees.offboard', $employee->id) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('Are you sure you want to mark this employee as resigned?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn edit" style="background: none; border: none; padding: 0; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; color: #d97706;" data-tooltip="Mark as Resigned">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('Are you sure you want to delete this employee record? (This is a soft-delete).');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn edit" style="background: none; border: none; padding: 0; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; color: #dc2626;" data-tooltip="Delete Employee">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 20px; color: var(--text-light);">No employees recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                      </div>
                      
                      <div style="margin-top: 15px;">
                          {{ $employees->links() }}
                      </div>
                  </div>

                  <script>
                      document.addEventListener("DOMContentLoaded", function() {
                          
                          // Handle form submission via AJAX
                          const filterForm = document.getElementById('filterForm');
                          if (filterForm) {
                              filterForm.addEventListener('submit', function(e) {
                                  e.preventDefault();
                                  const url = this.action + '?' + new URLSearchParams(new FormData(this)).toString();
                                  const container = document.getElementById("employees-table-container");
                                  
                                  const currentScroll = window.scrollY;
                                  container.style.minHeight = container.offsetHeight + "px";
                                  container.style.opacity = "0.5";
                                  container.style.pointerEvents = "none";
                                  
                                  fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
                                  .then(res => res.text())
                                  .then(html => {
                                      const parser = new DOMParser();
                                      const doc = parser.parseFromString(html, "text/html");
                                      const newContainer = doc.getElementById("employees-table-container");
                                      if (newContainer) {
                                          container.innerHTML = newContainer.innerHTML;
                                      }
                                      container.style.opacity = "1";
                                      container.style.pointerEvents = "auto";
                                      container.style.minHeight = "";
                                      
                                      window.scrollTo(0, currentScroll);
                                      window.history.pushState({}, "", url);
                                  });
                              });
                          }

                          document.body.addEventListener("click", function(e) {
                              if (e.target.closest("#employees-table-container .pagination a") || e.target.closest("#employees-table-container nav a")) {
                                  e.preventDefault();
                                  const url = e.target.closest("a").href;
                                  const container = document.getElementById("employees-table-container");
                                  
                                  // Lock height and scroll
                                  const currentScroll = window.scrollY;
                                  container.style.minHeight = container.offsetHeight + "px";
                                  
                                  container.style.opacity = "0.5";
                                  container.style.pointerEvents = "none";
                                  
                                  fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
                                  .then(res => res.text())
                                  .then(html => {
                                      const parser = new DOMParser();
                                      const doc = parser.parseFromString(html, "text/html");
                                      const newContainer = doc.getElementById("employees-table-container");
                                      if (newContainer) {
                                          container.innerHTML = newContainer.innerHTML;
                                      }
                                      container.style.opacity = "1";
                                      container.style.pointerEvents = "auto";
                                      container.style.minHeight = "";
                                      
                                      // Restore scroll position
                                      window.scrollTo(0, currentScroll);
                                      window.history.pushState({}, "", url);
                                  });
                              }
                          });
                      });
                  </script>
              </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirm Modal -->
    <div id="deleteConfirmModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 60; flex-direction: column; align-items: center; justify-content: center;">
        <div style="background: var(--bg-card); width: 90%; max-width: 400px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); padding: 20px; text-align: center;">
            <div style="color: #ef4444; margin-bottom: 15px;">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin: 0 auto;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 10px;">Confirm Deletion</h3>
            <p id="deleteConfirmMsg" style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 20px;"></p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" onclick="closeDeleteConfirm()" style="background: #e2e8f0; color: var(--text-secondary); padding: 8px 20px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="button" onclick="executeDelete()" style="background: #334155; color: #fff; padding: 8px 20px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer;">Delete</button>
            </div>
        </div>
    </div>

    <!-- Hierarchy Modal Backdrop -->
    <div id="hierarchyModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; flex-direction: column; align-items: center; justify-content: center;">
        <!-- Modal Content -->
        <div style="background: var(--panel-bg); width: 90%; max-width: 1000px; max-height: 90vh; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden; display: flex; flex-direction: column;">
            
            <!-- Modal Header -->
            <div style="background: var(--bg-card); padding: 15px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-weight: 600; font-size: 1.1rem; color: var(--text-primary); display: flex; align-items: center; gap: 8px;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Manage Asset Hierarchy
                </h2>
                <button type="button" onclick="closeHierarchyModal()" style="background: transparent; border: none; font-size: 1.5rem; color: var(--text-muted); cursor: pointer; line-height: 1;">&times;</button>
            </div>

            <!-- Modal Body -->
            <div style="padding: 20px; overflow-y: auto; flex-grow: 1;">
                <input type="hidden" id="hierarchy_json" value="{}">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 0 10px;">
                    <div id="breadcrumb" style="font-size: 0.95rem;"></div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span style="font-size: 0.8rem; color: var(--text-muted);" id="drill-hint">Click a pill to drill down</span>
                        <button type="button" onclick="closeHierarchyModal()" class="dk-btn dk-btn-outline">Cancel</button>
                        <button type="button" onclick="saveHierarchyAjax()" id="saveHierarchyBtn" class="dk-btn dk-btn-primary">Save Changes</button>
                    </div>
                </div>

                <div id="render-container" style="padding: 20px 0; min-height: 300px;"></div>
            </div>
        </div>
    </div>

    <style>
        .pill-wrapper { position: relative; display: inline-block; margin: 5px; }
        .pill { 
            background: var(--bg-card); padding: 12px 28px; border-radius: 9999px; font-weight: 600; 
            color: var(--text-primary); box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid var(--border-color); 
            cursor: pointer; white-space: nowrap; font-size: 0.95rem; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .pill:hover { 
            border-color: #3b82f6; 
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.2), 0 4px 6px -4px rgba(59, 130, 246, 0.1);
        }
        .pill.active { 
            background: var(--bg-card); 
            color: var(--text-primary); 
            border-color: #3b82f6; 
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); 
            cursor: default; 
        }
        .pill.active:hover { 
            transform: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .action-bar { 
            position: absolute; top: -20px; left: 50%; transform: translateX(-50%); display: flex; 
            opacity: 0; pointer-events: none; transition: opacity 0.2s, top 0.2s; 
            background: rgba(255,255,255,0.85); backdrop-filter: blur(4px); padding: 6px; 
            border-radius: 9999px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); z-index: 10; 
        }
        .pill-wrapper:hover .action-bar, .action-bar:hover { opacity: 1; pointer-events: auto; top: -25px; }

        .pill-action-btn { 
            width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; 
            justify-content: center; color: #fff; border: 2px solid var(--bg-card); cursor: pointer; 
            margin-left: -8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .pill-action-btn:first-child { margin-left: 0; z-index: 3; }
        .pill-action-btn:nth-child(2) { z-index: 2; }
        .pill-action-btn:nth-child(3) { z-index: 1; }
        .pill-action-btn:hover { 
            z-index: 10 !important; 
            transform: scale(1.15) translateY(-2px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.15);
        }

        .view-btn { background: #64748b; }
        .edit-btn { background: #4b5563; }
        .del-btn { background: #334155; }

        .pill-add { 
            width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; 
            justify-content: center; background: transparent; border: 2px dashed #94a3b8; 
            color: var(--text-muted); cursor: pointer; margin: 5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .pill-add:hover { 
            border-color: #3b82f6; color: #3b82f6; background: rgba(59, 130, 246, 0.1); 
            transform: scale(1.1) translateY(-2px);
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
        }
    </style>

    <script>
        let hierarchyData = {!! json_encode($hierarchy ?? []) !!};
        let currentDept = null;
        let addingNewDept = false;
        let addingNewPos = false;
        let deleteAction = null;

        const homeIcon = `<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline; margin-bottom:2px; margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>`;

        function openHierarchyModal() {
            document.getElementById('hierarchyModal').style.display = 'flex';
            render();
        }

        function closeHierarchyModal() {
            document.getElementById('hierarchyModal').style.display = 'none';
        }

        function saveHierarchyAjax() {
            const btn = document.getElementById('saveHierarchyBtn');
            btn.innerHTML = 'Saving...';
            btn.disabled = true;
            
            fetch("{{ route('employees.hierarchy.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ hierarchy_json: document.getElementById('hierarchy_json').value })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    window.location.reload(); // Reload to reflect changes if any
                } else {
                    alert('Error saving hierarchy.');
                    btn.innerHTML = 'Save Changes';
                    btn.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error saving hierarchy.');
                btn.innerHTML = 'Save Changes';
                btn.disabled = false;
            });
        }

        function sortData() {
            const sortedData = {};
            Object.keys(hierarchyData).sort((a, b) => a.localeCompare(b, undefined, {sensitivity: 'base'})).forEach(key => {
                sortedData[key] = [...hierarchyData[key]].sort((a, b) => a.localeCompare(b, undefined, {sensitivity: 'base'}));
            });
            hierarchyData = sortedData;
        }

        function render() {
            sortData();
            
            const container = document.getElementById('render-container');
            const breadcrumb = document.getElementById('breadcrumb');
            const drillHint = document.getElementById('drill-hint');
            
            document.getElementById('hierarchy_json').value = JSON.stringify(hierarchyData);
            
            let html = '';
            
            if (currentDept === null) {
                breadcrumb.innerHTML = `<span style="color:#475569; font-weight:600; display:flex; align-items:center;">${homeIcon} Overview</span>`;
                drillHint.style.display = 'inline';

                html += `<div style="text-align: center; margin-top: 20px; margin-bottom: 25px; font-size: 0.8rem; color: var(--text-muted); font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">Departments</div>`;
                html += `<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; align-items: center; max-width: 900px; margin: 0 auto;">`;
                
                for (const dept in hierarchyData) {
                    html += generatePill(dept, 'dept', dept);
                }
                if (addingNewDept) {
                    html += `<div class="pill-wrapper"><div class="pill" id="new-dept-pill"></div></div>`;
                }
                html += `<button type="button" onclick="addDept()" class="pill-add" data-tooltip="Add Department"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg></button>`;
                html += `</div>`;
            } else {
                breadcrumb.innerHTML = `<span onclick="goOverview()" style="color:#475569; font-weight:600; cursor:pointer; display:flex; align-items:center;">${homeIcon} Overview <span style="color:#94a3b8; margin: 0 8px;">&gt;</span> ${currentDept}</span>`;
                drillHint.style.display = 'none';

                html += `<div style="display: flex; flex-direction: column; align-items: center; margin-top: 10px;">`;
                html += `<div style="margin-bottom: 0;">` + generatePill(currentDept, 'dept-active', currentDept) + `</div>`;
                html += `<div style="width: 2px; height: 35px; border-left: 2px dashed #cbd5e1; margin: 0 0 15px 0;"></div>`;
                html += `<div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 25px;">Positions for ${currentDept}</div>`;
                
                html += `<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; align-items: center; max-width: 900px; margin: 0 auto;">`;
                if(hierarchyData[currentDept]) {
                    hierarchyData[currentDept].forEach((pos, idx) => {
                        html += generatePill(pos, 'pos', idx);
                    });
                }
                if (addingNewPos) {
                    html += `<div class="pill-wrapper"><div class="pill" id="new-pos-pill"></div></div>`;
                }
                html += `<button type="button" onclick="addPos()" class="pill-add" data-tooltip="Add Position"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg></button>`;
                html += `</div></div>`;
            }
            
            container.innerHTML = html;
        }

        const iconView = `<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
        const iconEdit = `<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>`;
        const iconTrash = `<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>`;

        function generatePill(name, type, identifier) {
            let safeName = name.replace(/'/g, "\\'");
            let safeId = typeof identifier === 'string' ? identifier.replace(/'/g, "\\'") : identifier;

            let viewBtn = type === 'dept' ? `<button type="button" class="pill-action-btn view-btn" data-tooltip="View / Drill Down" onclick="drillDown('${safeId}')">${iconView}</button>` : '';
            let editFn = (type === 'dept' || type === 'dept-active') ? `editDept(this, '${safeId}')` : `editPos(this, ${identifier})`;
            let delFn = (type === 'dept' || type === 'dept-active') ? `delDept('${safeId}')` : `delPos(${identifier})`;
            
            let editBtn = `<button type="button" class="pill-action-btn edit-btn" data-tooltip="Edit Name" onclick="${editFn}">${iconEdit}</button>`;
            let delBtn = `<button type="button" class="pill-action-btn del-btn" data-tooltip="Delete" onclick="${delFn}">${iconTrash}</button>`;
            let onClick = type === 'dept' ? `onclick="drillDown('${safeId}')"` : '';
            
            let pillClass = type === 'dept-active' ? 'pill active' : 'pill';

            return `
            <div class="pill-wrapper">
                <div class="action-bar">
                    ${viewBtn}
                    ${editBtn}
                    ${delBtn}
                </div>
                <div class="${pillClass}" ${onClick}>${name}</div>
            </div>`;
        }

        function goOverview() { currentDept = null; render(); }
        function drillDown(dept) { currentDept = dept; render(); }

        function addDept() {
            addingNewDept = true;
            render();
            let pill = document.getElementById('new-dept-pill');
            makeInlineEditable(pill, '', function(newName) {
                addingNewDept = false;
                if (newName && newName !== '') {
                    if (hierarchyData[newName]) { alert("Department already exists!"); } 
                    else { hierarchyData[newName] = []; }
                }
                render();
            });
        }

        function makeInlineEditable(element, oldValue, callback) {
            element.contentEditable = "true";
            element.focus();
            element.style.cursor = "text";
            element.style.outline = "2px dashed var(--border-color-focus)";
            element.style.backgroundColor = "var(--bg-body)";
            element.style.color = "var(--text-primary)";
            
            let range = document.createRange();
            range.selectNodeContents(element);
            let sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
            
            element.onkeydown = function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    element.blur();
                }
                if (e.key === 'Escape') {
                    e.innerText = oldValue;
                    element.blur();
                }
            };
            
            element.onblur = function() {
                element.contentEditable = "false";
                element.style.outline = "none";
                element.style.cursor = "";
                callback(element.innerText.trim());
            };
        }

        function editDept(btn, oldName) {
            let pill = btn.closest('.pill-wrapper').querySelector('.pill');
            makeInlineEditable(pill, oldName, function(newName) {
                if (newName && newName !== oldName) {
                    if (hierarchyData[newName]) { alert("A department with this name already exists!"); render(); return; }
                    let newData = {};
                    for (let key in hierarchyData) {
                        if (key === oldName) newData[newName] = hierarchyData[oldName];
                        else newData[key] = hierarchyData[key];
                    }
                    hierarchyData = newData;
                    if (currentDept === oldName) currentDept = newName;
                    render();
                } else {
                    render();
                }
            });
        }

        function closeDeleteConfirm() {
            document.getElementById('deleteConfirmModal').style.display = 'none';
            deleteAction = null;
        }

        function executeDelete() {
            if (deleteAction) deleteAction();
            closeDeleteConfirm();
        }

        function delDept(name) {
            document.getElementById('deleteConfirmMsg').innerText = `Are you sure you want to delete the department "${name}" and all its positions?`;
            deleteAction = function() {
                delete hierarchyData[name]; 
                if (currentDept === name) currentDept = null;
                render();
            };
            document.getElementById('deleteConfirmModal').style.display = 'flex';
        }

        function addPos() {
            addingNewPos = true;
            render();
            let pill = document.getElementById('new-pos-pill');
            makeInlineEditable(pill, '', function(newName) {
                addingNewPos = false;
                if (newName && newName !== '') {
                    hierarchyData[currentDept].push(newName);
                }
                render();
            });
        }

        function editPos(btn, index) {
            let pill = btn.closest('.pill-wrapper').querySelector('.pill');
            let oldName = hierarchyData[currentDept][index];
            makeInlineEditable(pill, oldName, function(newName) {
                if (newName && newName !== oldName) {
                    hierarchyData[currentDept][index] = newName;
                    render();
                } else {
                    render();
                }
            });
        }

        function delPos(index) {
            let name = hierarchyData[currentDept][index];
            document.getElementById('deleteConfirmMsg').innerText = `Are you sure you want to delete the position "${name}"?`;
            deleteAction = function() {
                hierarchyData[currentDept].splice(index, 1);
                render();
            };
            document.getElementById('deleteConfirmModal').style.display = 'flex';
        }
    </script>
</x-app-layout>
