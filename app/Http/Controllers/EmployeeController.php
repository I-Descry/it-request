<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::orderBy('last_name')->get();
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employees.create');
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
}
