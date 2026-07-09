<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Employee::query();

        if (!empty($this->filters['employment_status'])) {
            $query->where('employment_status', $this->filters['employment_status']);
        }

        if (!empty($this->filters['department'])) {
            $query->where('department', $this->filters['department']);
        }

        if (!empty($this->filters['branch'])) {
            $query->where('branch', $this->filters['branch']);
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
            $employee->resigned_date?->format('M d, Y'),
            $employee->created_at?->format('M d, Y'),
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
