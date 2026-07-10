<?php

namespace App\Exports;

use App\Models\Employee;
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

class EmployeesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Employee::query();

        if (!empty($this->filters['employment_status']) && is_array($this->filters['employment_status'])) {
            $query->whereIn('employment_status', $this->filters['employment_status']);
        }

        if (!empty($this->filters['department']) && is_array($this->filters['department'])) {
            $query->whereIn('department', $this->filters['department']);
        }

        if (!empty($this->filters['branch']) && is_array($this->filters['branch'])) {
            $query->whereIn('branch', $this->filters['branch']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('last_name', 'asc');
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'NFP ID',
            'Position',
            'Department',
            'Branch',
            'Contact No',
            'Employment Status',
            'Resigned Date',
            'Date Added',
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->full_name,
            $employee->nfp_id,
            $employee->position,
            $employee->department,
            $employee->branch,
            $employee->contact_no,
            $employee->employment_status,
            $employee->resigned_date ? \Carbon\Carbon::parse($employee->resigned_date)->format('M d, Y') : null,
            $employee->created_at?->format('M d, Y'),
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
