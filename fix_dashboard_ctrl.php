<?php
$file = 'app/Http/Controllers/DashboardController.php';
$c = file_get_contents($file);

$insert = <<<'EOT'
        // Recent Tickets (Paginated)
        $recentTickets = Ticket::orderBy('created_at', 'desc')->paginate(5);

        // Top Requestors
        $topRequestors = Ticket::selectRaw("requested_by, 
            COUNT(*) as total, 
            SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today,
            SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_week,
            SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_month", 
            [$today->toDateString(), $weekStart, $monthStart]
        )
        ->groupBy('requested_by')
        ->orderByDesc('this_month')
        ->limit(5)
        ->get();
EOT;

$c = str_replace("        // Recent Tickets (Paginated)\n        \$recentTickets = Ticket::orderBy('created_at', 'desc')->paginate(5);", $insert, $c);

$c = str_replace("'recentTickets'", "'recentTickets', 'topRequestors'", $c);

file_put_contents($file, $c);