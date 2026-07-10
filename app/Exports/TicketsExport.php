<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TicketsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Ticket::query();

        if (!empty($this->filters['status']) && is_array($this->filters['status'])) {
            $query->whereIn('status', $this->filters['status']);
        }

        if (!empty($this->filters['request_type']) && is_array($this->filters['request_type'])) {
            $query->whereIn('request_type', $this->filters['request_type']);
        }

        if (!empty($this->filters['assisted_by']) && is_array($this->filters['assisted_by'])) {
            $query->whereIn('assisted_by', $this->filters['assisted_by']);
        }

        if (!empty($this->filters['department']) && is_array($this->filters['department'])) {
            $query->whereIn('department', $this->filters['department']);
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
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn();

        // Set Nunito font for the entire sheet
        $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
            'font' => ['name' => 'Nunito', 'size' => 10],
        ]);

        // Header row styling
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10, 'name' => 'Nunito'],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '6b7280'],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '6b7280']],
            ],
        ]);

        // Data rows — light alternating stripes
        for ($row = 2; $row <= $lastRow; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'f8fafc'],
                    ],
                ]);
            }
        }

        // Thin inner borders for data area
        if ($lastRow > 1) {
            $sheet->getStyle("A2:{$lastCol}{$lastRow}")->applyFromArray([
                'borders' => [
                    'horizontal' => ['borderStyle' => Border::BORDER_HAIR, 'color' => ['rgb' => 'e2e8f0']],
                ],
            ]);
        }

        $sheet->getRowDimension(1)->setRowHeight(24);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->freezePane('A2');
            },
        ];
    }
}
