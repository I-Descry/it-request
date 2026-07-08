<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\SsoAccount;
use App\Models\Employee;
use App\Models\ActivityLog;

class SsoAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $ssoAccounts = SsoAccount::with('employee')
            ->when($search, function ($query, $search) {
                return $query->where('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        $employees = Employee::where('employment_status', 'Active')->orderBy('last_name')->get();

        return view('sso_accounts.index', compact('ssoAccounts', 'search', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('employment_status', 'Active')->orderBy('last_name')->get();
        $hierarchy = \App\Http\Controllers\HierarchyController::getHierarchy();
        return view('sso_accounts.create', compact('employees', 'hierarchy'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('sso_accounts')->whereNull('deleted_at')
            ],
            'password' => 'nullable|string',
            'password_changed' => 'nullable|boolean',
            'employee_id' => 'nullable|exists:employees,id',
            'name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'account_type' => 'required|in:New,Transferred',
            'transferred_from' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => [
                'nullable', 'email', 'max:255',
                Rule::unique('sso_accounts')->whereNull('deleted_at')
            ],
            'status' => 'required|in:Active,Inactive,Locked',
        ]);

        $validated['password_changed'] = $request->has('password_changed');

        // If employee_id is provided, we could optionally sync name/dept/pos from employee 
        // but since we allow creating without employee and overriding fields, we just use the submitted data.
        
        $ssoAccount = SsoAccount::create($validated);

        ActivityLog::create([
            'action' => 'created',
            'subject_type' => 'App\Models\SsoAccount',
            'subject_id' => $ssoAccount->id,
            'description' => "Created SSO account for {$ssoAccount->username}",
            'properties' => ['attributes' => $ssoAccount->toArray()]
        ]);

        return redirect()->route('sso_accounts.index')
            ->with('success', 'SSO Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SsoAccount $ssoAccount)
    {
        return view('sso_accounts.show', compact('ssoAccount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SsoAccount $ssoAccount)
    {
        $employees = Employee::orderBy('last_name')->get();
        $hierarchy = \App\Http\Controllers\HierarchyController::getHierarchy();
        return view('sso_accounts.edit', compact('ssoAccount', 'employees', 'hierarchy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SsoAccount $ssoAccount)
    {
        $validated = $request->validate([
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('sso_accounts')->ignore($ssoAccount->id)->whereNull('deleted_at')
            ],
            'password' => 'nullable|string',
            'password_changed' => 'nullable|boolean',
            'employee_id' => 'nullable|exists:employees,id',
            'name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'account_type' => 'required|in:New,Transferred',
            'transferred_from' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => [
                'nullable', 'email', 'max:255',
                Rule::unique('sso_accounts')->ignore($ssoAccount->id)->whereNull('deleted_at')
            ],
            'status' => 'required|in:Active,Inactive,Locked',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $validated['password_changed'] = $request->has('password_changed');

        $oldAttributes = $ssoAccount->getOriginal();
        $ssoAccount->update($validated);

        ActivityLog::create([
            'action' => 'updated',
            'subject_type' => 'App\Models\SsoAccount',
            'subject_id' => $ssoAccount->id,
            'description' => "Updated SSO account for {$ssoAccount->username}",
            'properties' => [
                'old' => $oldAttributes,
                'dirty' => $ssoAccount->getChanges()
            ]
        ]);

        return redirect()->route('sso_accounts.index')
            ->with('success', 'SSO Account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SsoAccount $ssoAccount)
    {
        $username = $ssoAccount->username;
        $id = $ssoAccount->id;
        
        $ssoAccount->delete();

        ActivityLog::create([
            'action' => 'deleted',
            'subject_type' => 'App\Models\SsoAccount',
            'subject_id' => $id,
            'description' => "Deleted SSO account for {$username}",
            'properties' => []
        ]);

        return redirect()->route('sso_accounts.index')
            ->with('success', 'SSO Account deleted successfully.');
    }

    /**
     * Link an existing SSO account to an employee directly.
     */
    public function linkEmployee(Request $request, SsoAccount $ssoAccount)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $employee = Employee::find($request->employee_id);
        
        $oldAttributes = $ssoAccount->getOriginal();
        
        $ssoAccount->update([
            'employee_id' => $employee->id,
            'name' => $employee->full_name,
            'department' => $employee->department,
            'position' => $employee->position,
        ]);

        ActivityLog::create([
            'action' => 'updated',
            'subject_type' => 'App\Models\SsoAccount',
            'subject_id' => $ssoAccount->id,
            'description' => "Linked SSO account to Employee: {$employee->full_name}",
            'properties' => [
                'old' => [
                    'employee_id' => $oldAttributes['employee_id'] ?? 'none',
                    'name' => $oldAttributes['name'] ?? 'none',
                    'department' => $oldAttributes['department'] ?? 'none'
                ],
                'dirty' => [
                    'employee_id' => $employee->id,
                    'name' => $employee->full_name,
                    'department' => $employee->department
                ]
            ]
        ]);

        return redirect()->back()->with('success', 'Employee successfully linked to SSO Account.');
    }

    public function markPasswordChanged(SsoAccount $ssoAccount)
    {
        $ssoAccount->update(['password_changed' => true]);

        ActivityLog::create([
            'action' => 'updated',
            'subject_type' => 'App\Models\SsoAccount',
            'subject_id' => $ssoAccount->id,
            'description' => "Marked temporary password as changed by user for {$ssoAccount->username}",
            'properties' => [
                'old' => ['password_changed' => 'false'],
                'dirty' => ['password_changed' => 'true']
            ]
        ]);

        return back()->with('success', 'Password marked as changed by user.');
    }
}
