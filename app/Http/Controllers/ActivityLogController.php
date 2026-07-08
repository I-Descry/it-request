<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('subject')->latest();

        if ($request->has('type')) {
            if ($request->type === 'tickets') {
                $query->where('subject_type', 'App\Models\Ticket');
            } elseif ($request->type === 'employees') {
                $query->where('subject_type', 'App\Models\Employee');
            } elseif ($request->type === 'sso_accounts') {
                $query->where('subject_type', 'App\Models\SsoAccount');
            }
        }

        $logs = $query->paginate(20)->withQueryString();
        
        return view('logs.index', compact('logs'));
    }
}
