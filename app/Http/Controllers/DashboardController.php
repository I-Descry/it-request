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

        // Persist the timeframe filter in session
        if (request()->has('timeframe')) {
            $selectedTimeframe = request('timeframe');
            session(['dashboard_timeframe' => $selectedTimeframe]);
        } else {
            $selectedTimeframe = session('dashboard_timeframe', 'this_month');
        }

        if (!array_key_exists($selectedTimeframe, $timeframes)) {
            $selectedTimeframe = 'this_month';
            session(['dashboard_timeframe' => $selectedTimeframe]);
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

        // Check for exclusions (from Chart legend clicks)
        $excludedTypes = request()->input('exclude', []);

        $baseQuery = function () use ($startDate, $endDate, $excludedTypes) {
            $query = Ticket::whereBetween('created_at', [$startDate, $endDate]);
            if (!empty($excludedTypes)) {
                $query->whereNotIn('request_type', $excludedTypes);
            }
            return $query;
        };

        // KPI counts
        $totalActive = $baseQuery()->count();
        
        // Dynamic Averages
        $weeksPassed = max(1, $daysPassed / 7);
        $monthsPassed = max(1, $daysPassed / 30.44);

        $metrics = [];
        $metrics[] = ['label' => 'Total Requests', 'value' => $totalActive, 'color' => '#10b981'];

        if (in_array($selectedTimeframe, ['this_week', 'last_7_days', 'this_month', 'last_month'])) {
            $metrics[] = ['label' => 'Avg / Day', 'value' => round($totalActive / $daysPassed, 1), 'color' => '#06b6d4'];
            if (in_array($selectedTimeframe, ['this_month', 'last_month'])) {
                $metrics[] = ['label' => 'Avg / Week', 'value' => round($totalActive / $weeksPassed, 1), 'color' => '#f59e0b'];
            }
        } elseif (in_array($selectedTimeframe, ['this_year', 'all_time'])) {
            $metrics[] = ['label' => 'Avg / Week', 'value' => round($totalActive / $weeksPassed, 1), 'color' => '#06b6d4'];
            $metrics[] = ['label' => 'Avg / Month', 'value' => round($totalActive / $monthsPassed, 1), 'color' => '#f59e0b'];
        }

        // Status counts
        $byStatus = $baseQuery()->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Archived count (not affected by request type filter typically, but we'll leave it as is since it's separate)
        $archivedCount = ArchiveTicket::whereBetween('created_at', [$startDate, $endDate]);
        if (!empty($excludedTypes)) {
            $archivedCount->whereNotIn('request_type', $excludedTypes);
        }
        $archivedCount = $archivedCount->count();

        // By request type (we want the original counts for the donut chart, so we use a non-excluded query here!)
        // Wait, if we exclude it, the chart still needs the original data so it can toggle it back on.
        $chartQuery = Ticket::whereBetween('created_at', [$startDate, $endDate]);
        $byRequestType = $chartQuery->selectRaw('request_type, count(*) as count')
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

        // Technician performance: resolved vs in progress vs escalated
        $techPerformance = $baseQuery()->selectRaw("assisted_by, status, count(*) as count")
            ->groupBy('assisted_by', 'status')
            ->get()
            ->groupBy('assisted_by')
            ->map(function ($rows) {
                $resolved = $rows->where('status', 'Resolved')->sum('count');
                $inProgress = $rows->where('status', 'In Progress')->sum('count');
                $escalated = $rows->whereIn('status', ['Escalated', 'Not Complete', 'Open'])->sum('count');
                return ['resolved' => $resolved, 'in_progress' => $inProgress, 'escalated' => $escalated, 'total' => $resolved + $inProgress + $escalated];
            })
            ->toArray();

        // Top Requestors
        $topRequestors = $baseQuery()->selectRaw("requested_by, COUNT(*) as total")
            ->groupBy('requested_by')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Department Breakdown (Top 5)
        $departmentBreakdown = $baseQuery()->selectRaw("department, COUNT(*) as total")
            ->groupBy('department')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Calculate optimal pagination based on the right column's data height
        $metricsCount = count($metrics);
        $requestorsCount = $topRequestors->count();
        
        // Each metric and each requestor/department row takes ~1 ticket row height (~40px)
        $optimalPagination = max(5, $metricsCount + $requestorsCount);

        // Recent Tickets (Paginated)
        $recentTickets = $baseQuery()->orderBy('created_at', 'desc')->paginate($optimalPagination)->appends(['timeframe' => $selectedTimeframe, 'exclude' => $excludedTypes]);

        if (request()->ajax() && request()->has('exclude')) {
            return response()->json([
                'totalActive' => $totalActive,
                'metrics' => $metrics,
                'byStatus' => $byStatus,
                'techPerformance' => $techPerformance,
                'topRequestors' => view('partials.dashboard.top_requestors', compact('topRequestors'))->render(),
                'recentTickets' => view('partials.dashboard.recent_tickets', compact('recentTickets'))->render(),
                'departmentBreakdown' => view('partials.dashboard.department_breakdown', compact('departmentBreakdown'))->render(),
            ]);
        }

        return view('dashboard', compact(
            'totalActive', 'timeframes', 'selectedTimeframe', 'metrics',
            'byStatus', 'archivedCount',
            'byRequestType', 'techPerformance', 'assistedByMap',
            'recentTickets', 'topRequestors', 'departmentBreakdown', 'excludedTypes'
        ));
    }
}
