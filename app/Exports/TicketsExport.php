<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Ticket::query();

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['request_type'])) {
            $query->where('request_type', $this->filters['request_type']);
        }

        if (!empty($this->filters['assisted_by'])) {
            $query->where('assisted_by', $this->filters['assisted_by']);
        }

        if (!empty($this->filters['department'])) {
            $query->where('department', $this->filters['department']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Ticket No',
            'Request Type',
            'Request',
            'Request Details',
            'Requested By',
            'Position',
            'Department',
            'Branch',
            'Assisted By',
            'Status',
            'Remarks',
            'Date Created',
        ];
    }

    public function map($ticket): array
    {
        $assistedByMap = [
            'IT03' => 'Tristan Railey Tan',
            'IT04' => 'John Paul Villacorta',
            'Both' => 'Both',
        ];

        return [
            $ticket->ticket_no,
            $ticket->request_type,
            $ticket->request,
            $ticket->request_details,
            $ticket->requested_by,
            $ticket->position,
            $ticket->department,
            $ticket->branch,
            $assistedByMap[$ticket->assisted_by] ?? $ticket->assisted_by,
            $ticket->status,
            $ticket->remarks,
            $ticket->created_at?->format('M d, Y h:i A'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2563EB'],
                ],
            ],
        ];
    }
}
