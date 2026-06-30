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
        return view('employees.show', compact('employee'));
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

        $sortBy = $request->input('sort_by', 'last_name');
        $sortDir = $request->input('sort_dir', 'asc');
        $validColumns = ['nfp_id', 'first_name', 'last_name', 'position', 'department', 'branch'];
        if (in_array($sortBy, $validColumns)) {
            $query->orderBy($sortBy, $sortDir === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('last_name', 'asc');
        }

        $employees = $query->paginate(10)->appends($request->query());
        $hierarchy = \App\Http\Controllers\HierarchyController::getHierarchy();
        
        return view('employees.index', compact('employees', 'hierarchy'));
    }

    public function directory()
    {
        // Fetch hierarchy for proper position sorting
        $hierarchy = \App\Http\Controllers\HierarchyController::getHierarchy();
        
        // Group by department, then position is handled in the view
        $employeesByDept = Employee::orderBy('department')
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
        ]);

        $oldFullName = $employee->full_name;

        $employee->update($validated);

        $newFullName = $employee->full_name;

        // Cascade updates to all tickets involving this employee
        \App\Models\Ticket::withoutEvents(function () use ($oldFullName, $newFullName, $employee) {
            \App\Models\Ticket::where('requested_by', $oldFullName)->get()->each(function ($ticket) use ($newFullName, $employee) {
                // Keep track of old attributes for the log
                $oldAttrs = $ticket->getAttributes();

                $ticket->update([
                    'requested_by' => $newFullName,
                    'position'     => $employee->position,
                    'branch'       => $employee->branch,
                    'department'   => $employee->department,
                ]);

                // Manually log it with a descriptive message linking back to the employee
                $ticket->activityLogs()->create([
                    'action' => 'updated',
                    'description' => 'System sync: Cascaded from Employee update',
                    'properties' => [
                        'old' => $oldAttrs,
                        'new' => $ticket->getAttributes(),
                        'dirty' => $ticket->getChanges()
                    ]
                ]);
            });
        });

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }
}
