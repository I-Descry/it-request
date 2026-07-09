<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        // Previous / Next navigation (alphabetical by last_name)
        $prevEmployee = Employee::where('last_name', '<', $employee->last_name)
            ->orWhere(function ($q) use ($employee) {
                $q->where('last_name', $employee->last_name)->where('id', '<', $employee->id);
            })
            ->orderBy('last_name', 'desc')->orderBy('id', 'desc')->first();

        $nextEmployee = Employee::where('last_name', '>', $employee->last_name)
            ->orWhere(function ($q) use ($employee) {
                $q->where('last_name', $employee->last_name)->where('id', '>', $employee->id);
            })
            ->orderBy('last_name', 'asc')->orderBy('id', 'asc')->first();

        // Calculate requestor statistics
        $statsRaw = \App\Models\Ticket::where('requested_by', $employee->full_name)
            ->selectRaw("
                COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as today,
                COUNT(CASE WHEN created_at >= ? AND created_at <= ? THEN 1 END) as this_week,
                COUNT(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 END) as this_month
            ", [
                \Carbon\Carbon::now()->startOfWeek(),
                \Carbon\Carbon::now()->endOfWeek(),
                \Carbon\Carbon::now()->month,
                \Carbon\Carbon::now()->year
            ])->first();

        $stats = [
            'today' => $statsRaw->today ?? 0,
            'this_week' => $statsRaw->this_week ?? 0,
            'this_month' => $statsRaw->this_month ?? 0,
        ];

        return view('employees.show', compact('employee', 'prevEmployee', 'nextEmployee', 'stats'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('nfp_id', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('branch', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter_dept')) {
            $query->where('department', $request->filter_dept);
        }
        if ($request->filled('filter_branch')) {
            $query->where('branch', $request->filter_branch);
        }

        $statusFilter = $request->input('filter_status', 'Active');
        if ($statusFilter !== 'All') {
            $query->where('employment_status', $statusFilter);
        }

        $sortBy = $request->input('sort_by', 'last_name');
        $sortDir = $request->input('sort_dir', 'asc');
        $validColumns = ['nfp_id', 'first_name', 'last_name', 'position', 'department', 'branch', 'employment_status'];
        if (in_array($sortBy, $validColumns)) {
            $query->orderBy($sortBy, $sortDir === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('last_name', 'asc');
        }

        $employees = $query->paginate(10)->appends($request->query());
        $hierarchy = \App\Http\Controllers\HierarchyController::getHierarchy();
        
        return view('employees.index', compact('employees', 'hierarchy', 'statusFilter'));
    }

    public function directory()
    {
        // Fetch hierarchy for proper position sorting
        $hierarchy = \App\Http\Controllers\HierarchyController::getHierarchy();
        
        // Group by department, then position is handled in the view
        $employeesByDept = Employee::where('employment_status', 'Active')
                                   ->orderBy('department')
                                   ->orderBy('last_name')
                                   ->get()
                                   ->groupBy('department');
                                   
        return view('employees.directory', compact('employeesByDept', 'hierarchy'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hierarchy = \App\Http\Controllers\HierarchyController::getHierarchy();
        return view('employees.create', compact('hierarchy'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->filled('nfp_id') && !str_starts_with($request->nfp_id, 'NFP-')) {
            $request->merge(['nfp_id' => 'NFP-' . $request->nfp_id]);
        }

        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name'   => 'required|string|max:255',
            'suffix'      => 'nullable|string|max:255',
            'nfp_id'      => 'nullable|string|max:255|unique:employees,nfp_id',
            'position'    => 'required|string|max:255',
            'branch'      => 'required|string|max:255',
            'department'  => 'nullable|string|max:255',
            'contact_no'  => 'nullable|string|max:255',
            'employment_status' => 'nullable|string|in:Active,Resigned',
            'resigned_date'     => 'nullable|date',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $hierarchy = \App\Http\Controllers\HierarchyController::getHierarchy();
        return view('employees.edit', compact('employee', 'hierarchy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        if ($request->filled('nfp_id') && !str_starts_with($request->nfp_id, 'NFP-')) {
            $request->merge(['nfp_id' => 'NFP-' . $request->nfp_id]);
        }

        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name'   => 'required|string|max:255',
            'suffix'      => 'nullable|string|max:255',
            'nfp_id'      => 'nullable|string|max:255|unique:employees,nfp_id,' . $employee->id,
            'position'    => 'required|string|max:255',
            'branch'      => 'required|string|max:255',
            'department'  => 'nullable|string|max:255',
            'contact_no'  => 'nullable|string|max:255',
            'employment_status' => 'required|string|in:Active,Resigned',
            'resigned_date'     => 'nullable|date',
        ]);

        $oldFullName = $employee->full_name;

        $employee->update($validated);

        $newFullName = $employee->full_name;

        // Cascade updates to all tickets involving this employee
        $ticketIds = \App\Models\Ticket::where('requested_by', $oldFullName)->pluck('id')->toArray();
        
        if (!empty($ticketIds)) {
            \App\Models\Ticket::withoutEvents(function () use ($ticketIds, $newFullName, $employee) {
                \App\Models\Ticket::whereIn('id', $ticketIds)->update([
                    'requested_by' => $newFullName,
                    'position'     => $employee->position,
                    'branch'       => $employee->branch,
                    'department'   => $employee->department,
                ]);

                // Bulk insert activity logs
                $logs = [];
                $now = now();
                foreach ($ticketIds as $id) {
                    $logs[] = [
                        'action'       => 'updated',
                        'subject_type' => 'App\Models\Ticket',
                        'subject_id'   => $id,
                        'description'  => 'System sync: Cascaded from Employee update',
                        'properties'   => json_encode(['note' => "Updated via employee ({$newFullName}) sync"]),
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ];
                }
                \App\Models\ActivityLog::insert($logs);
            });
        }

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    /**
     * Mark an employee as Resigned.
     */
    public function offboard(Request $request, Employee $employee)
    {
        $employee->update([
            'employment_status' => 'Resigned',
            'resigned_date'     => now(),
        ]);
        
        return redirect()->back()->with('success', 'Employee successfully marked as Resigned.');
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee record successfully deleted.');
    }
}
