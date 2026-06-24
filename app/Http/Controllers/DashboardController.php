<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\ArchiveTicket;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $prevMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $prevMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // KPI counts
        $totalActive = Ticket::count();
        $todayCount = Ticket::whereDate('created_at', $today)->count();
        $weekCount = Ticket::where('created_at', '>=', $weekStart)->count();
        $monthCount = Ticket::where('created_at', '>=', $monthStart)->count();
        $prevMonthCount = Ticket::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])->count();

        // Status counts
        $byStatus = Ticket::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $archivedCount = ArchiveTicket::count();

        // By request type
        $byRequestType = Ticket::selectRaw('request_type, count(*) as count')
            ->groupBy('request_type')
            ->orderByDesc('count')
            ->pluck('count', 'request_type')
            ->toArray();

        // Assisted by
        $assistedByMap = [
            'IT03' => 'Tristan Railey Tan',
            'IT04' => 'John Paul Villacorta',
            'Both' => 'Both',
        ];

        // Technician performance: resolved vs unresolved
        $techPerformance = Ticket::selectRaw("assisted_by, status, count(*) as count")
            ->groupBy('assisted_by', 'status')
            ->get()
            ->groupBy('assisted_by')
            ->map(function ($rows) {
                $resolved = $rows->where('status', 'Resolved')->sum('count');
                $unresolved = $rows->whereIn('status', ['In Progress', 'Escalated', 'Not Complete'])->sum('count');
                return ['resolved' => $resolved, 'unresolved' => $unresolved, 'total' => $resolved + $unresolved];
            })
            ->toArray();

        // Recent Tickets (Paginated)
        $recentTickets = Ticket::orderBy('created_at', 'desc')->paginate(5);

        return view('dashboard', compact(
            'totalActive', 'todayCount', 'weekCount', 'monthCount', 'prevMonthCount',
            'byStatus', 'archivedCount',
            'byRequestType', 'techPerformance', 'assistedByMap',
            'recentTickets'
        ));
    }
}
