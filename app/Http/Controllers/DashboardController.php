<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\ArchiveTicket;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $timeframes = [
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'this_week' => 'This Week',
            'last_7_days' => 'Last 7 Days',
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
            'this_year' => 'This Year',
            'all_time' => 'All Time',
        ];

        $selectedTimeframe = request('timeframe', 'this_month');
        if (!array_key_exists($selectedTimeframe, $timeframes)) {
            $selectedTimeframe = 'this_month';
        }

        $now = Carbon::now();
        $startDate = null;
        $endDate = null;
        $daysPassed = 1;

        switch ($selectedTimeframe) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $daysPassed = 1;
                break;
            case 'yesterday':
                $startDate = $now->copy()->subDay()->startOfDay();
                $endDate = $now->copy()->subDay()->endOfDay();
                $daysPassed = 1;
                break;
            case 'this_week':
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                $daysPassed = max(1, $now->dayOfWeekIso);
                break;
            case 'last_7_days':
                $startDate = $now->copy()->subDays(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $daysPassed = 7;
                break;
            case 'this_month':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $daysPassed = max(1, $now->day);
                break;
            case 'last_month':
                $startDate = $now->copy()->subMonth()->startOfMonth();
                $endDate = $now->copy()->subMonth()->endOfMonth();
                $daysPassed = $startDate->daysInMonth;
                break;
            case 'this_year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                $daysPassed = max(1, $now->dayOfYear);
                break;
            case 'all_time':
                $startDate = Carbon::create(2000, 1, 1);
                $endDate = clone $now;
                $firstTicket = Ticket::orderBy('created_at')->first();
                if ($firstTicket) {
                    $daysPassed = max(1, $firstTicket->created_at->diffInDays($now));
                } else {
                    $daysPassed = 1;
                }
                break;
        }

        $baseQuery = function () use ($startDate, $endDate) {
            return Ticket::whereBetween('created_at', [$startDate, $endDate]);
        };

        // KPI counts
        $totalActive = $baseQuery()->count();
        
        // Dynamic Averages
        $weeksPassed = max(1, $daysPassed / 7);
        $monthsPassed = max(1, $daysPassed / 30.44);

        $metrics = [];
        $metrics[] = ['label' => 'Total Requests', 'value' => $totalActive, 'color' => '#3b82f6'];

        if (in_array($selectedTimeframe, ['this_week', 'last_7_days', 'this_month', 'last_month'])) {
            $metrics[] = ['label' => 'Avg / Day', 'value' => round($totalActive / $daysPassed, 1), 'color' => '#6366f1'];
            if (in_array($selectedTimeframe, ['this_month', 'last_month'])) {
                $metrics[] = ['label' => 'Avg / Week', 'value' => round($totalActive / $weeksPassed, 1), 'color' => '#8b5cf6'];
            }
        } elseif (in_array($selectedTimeframe, ['this_year', 'all_time'])) {
            $metrics[] = ['label' => 'Avg / Week', 'value' => round($totalActive / $weeksPassed, 1), 'color' => '#6366f1'];
            $metrics[] = ['label' => 'Avg / Month', 'value' => round($totalActive / $monthsPassed, 1), 'color' => '#8b5cf6'];
        }

        // Status counts
        $byStatus = $baseQuery()->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $archivedCount = ArchiveTicket::whereBetween('created_at', [$startDate, $endDate])->count();

        // By request type
        $byRequestType = $baseQuery()->selectRaw('request_type, count(*) as count')
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
        $techPerformance = $baseQuery()->selectRaw("assisted_by, status, count(*) as count")
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
        $recentTickets = $baseQuery()->orderBy('created_at', 'desc')->paginate(5)->appends(['timeframe' => $selectedTimeframe]);

        // Top Requestors
        $topRequestors = $baseQuery()->selectRaw("requested_by, COUNT(*) as total")
            ->groupBy('requested_by')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalActive', 'timeframes', 'selectedTimeframe', 'metrics',
            'byStatus', 'archivedCount',
            'byRequestType', 'techPerformance', 'assistedByMap',
            'recentTickets', 'topRequestors'
        ));
    }
}
