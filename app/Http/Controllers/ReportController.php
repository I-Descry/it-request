<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Employee;
use App\Models\SsoAccount;
use App\Models\ArchiveTicket;
use App\Exports\TicketsExport;
use App\Exports\EmployeesExport;
use App\Exports\SsoAccountsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;

class ReportController extends Controller
{
    /**
     * Show the reports page.
     */
    public function index()
    {
        // Gather filter options for each report type
        $ticketStatuses = ['In Progress', 'Escalated', 'Resolved', 'Not Complete', 'Cancelled'];
        $requestTypes = Ticket::select('request_type')->distinct()->orderBy('request_type')->pluck('request_type');
        $ticketDepartments = Ticket::select('department')->whereNotNull('department')->distinct()->orderBy('department')->pluck('department');

        $employeeStatuses = ['Active', 'Resigned'];
        $employeeDepartments = Employee::select('department')->whereNotNull('department')->distinct()->orderBy('department')->pluck('department');
        $employeeBranches = Employee::select('branch')->whereNotNull('branch')->distinct()->orderBy('branch')->pluck('branch');

        $ssoStatuses = ['Active', 'Inactive', 'Locked'];
        $ssoAccountTypes = ['New', 'Transferred'];
        $ssoDepartments = SsoAccount::select('department')->whereNotNull('department')->distinct()->orderBy('department')->pluck('department');

        return view('reports.index', compact(
            'ticketStatuses', 'requestTypes', 'ticketDepartments',
            'employeeStatuses', 'employeeDepartments', 'employeeBranches',
            'ssoStatuses', 'ssoAccountTypes', 'ssoDepartments'
        ));
    }

    /**
     * Export as Excel.
     */
    public function exportExcel(Request $request)
    {
        $type = $request->input('report_type', 'tickets');
        $filters = $this->getFilters($request);
        $timestamp = now()->format('Y-m-d_His');

        switch ($type) {
            case 'employees':
                return Excel::download(new EmployeesExport($filters), "Employees_Report_{$timestamp}.xlsx");
            case 'sso_accounts':
                return Excel::download(new SsoAccountsExport($filters), "SSO_Accounts_Report_{$timestamp}.xlsx");
            default:
                return Excel::download(new TicketsExport($filters), "IT_Requests_Report_{$timestamp}.xlsx");
        }
    }

    /**
     * Export as PDF.
     */
    public function exportPdf(Request $request)
    {
        $type = $request->input('report_type', 'tickets');
        $filters = $this->getFilters($request);
        $data = $this->getReportData($type, $filters);
        $timestamp = now()->format('Y-m-d_His');

        $pdf = Pdf::loadView('reports.pdf', [
            'data' => $data,
            'type' => $type,
            'filters' => $filters,
            'generatedAt' => now()->format('M d, Y h:i A'),
        ])->setPaper('a4', 'landscape');

        $names = ['tickets' => 'IT_Requests', 'employees' => 'Employees', 'sso_accounts' => 'SSO_Accounts'];
        $name = $names[$type] ?? 'Report';

        return $pdf->download("{$name}_Report_{$timestamp}.pdf");
    }



    /**
     * Extract filters from request.
     */
    private function getFilters(Request $request): array
    {
        return [
            'status' => $request->input('status'),
            'request_type' => $request->input('request_type'),
            'assisted_by' => $request->input('assisted_by'),
            'department' => $request->input('department'),
            'branch' => $request->input('branch'),
            'employment_status' => $request->input('employment_status'),
            'account_type' => $request->input('account_type'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'include_archived' => $request->boolean('include_archived'),
        ];
    }

    /**
     * Get report data for PDF generation.
     */
    private function getReportData(string $type, array $filters)
    {
        switch ($type) {
            case 'employees':
                return $this->getEmployeeData($filters);
            case 'sso_accounts':
                return $this->getSsoData($filters);
            default:
                return $this->getTicketData($filters);
        }
    }

    private function getTicketData(array $filters)
    {
        $query = Ticket::query();

        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['request_type'])) $query->where('request_type', $filters['request_type']);
        if (!empty($filters['assisted_by'])) $query->where('assisted_by', $filters['assisted_by']);
        if (!empty($filters['department'])) $query->where('department', $filters['department']);
        if (!empty($filters['date_from'])) $query->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('created_at', '<=', $filters['date_to']);

        $tickets = $query->orderBy('created_at', 'desc')->get();

        // If include archived, merge archive tickets too
        if (!empty($filters['include_archived'])) {
            $archiveQuery = ArchiveTicket::query();
            if (!empty($filters['status'])) $archiveQuery->where('status', $filters['status']);
            if (!empty($filters['request_type'])) $archiveQuery->where('request_type', $filters['request_type']);
            if (!empty($filters['assisted_by'])) $archiveQuery->where('assisted_by', $filters['assisted_by']);
            if (!empty($filters['department'])) $archiveQuery->where('department', $filters['department']);
            if (!empty($filters['date_from'])) $archiveQuery->whereDate('original_created_at', '>=', $filters['date_from']);
            if (!empty($filters['date_to'])) $archiveQuery->whereDate('original_created_at', '<=', $filters['date_to']);

            $archived = $archiveQuery->orderBy('original_created_at', 'desc')->get();
            $tickets = $tickets->concat($archived);
        }

        return $tickets;
    }

    private function getEmployeeData(array $filters)
    {
        $query = Employee::query();

        if (!empty($filters['employment_status'])) $query->where('employment_status', $filters['employment_status']);
        if (!empty($filters['department'])) $query->where('department', $filters['department']);
        if (!empty($filters['branch'])) $query->where('branch', $filters['branch']);
        if (!empty($filters['date_from'])) $query->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('created_at', '<=', $filters['date_to']);

        return $query->orderBy('last_name', 'asc')->get();
    }

    private function getSsoData(array $filters)
    {
        $query = SsoAccount::query();

        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['account_type'])) $query->where('account_type', $filters['account_type']);
        if (!empty($filters['department'])) $query->where('department', $filters['department']);
        if (!empty($filters['date_from'])) $query->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('created_at', '<=', $filters['date_to']);

        return $query->orderBy('created_at', 'desc')->get();
    }
}
