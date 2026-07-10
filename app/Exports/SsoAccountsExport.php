<?php

namespace App\Exports;

use App\Models\SsoAccount;
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

class SsoAccountsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = SsoAccount::query();

        if (!empty($this->filters['status']) && is_array($this->filters['status'])) {
            $query->whereIn('status', $this->filters['status']);
        }

        if (!empty($this->filters['account_type']) && is_array($this->filters['account_type'])) {
            $query->whereIn('account_type', $this->filters['account_type']);
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
            'Username',
            'Name',
            'Department',
            'Position',
            'Email',
            'Account Type',
            'Transferred From',
            'Status',
            'Date Created',
        ];
    }

    public function map($sso): array
    {
        return [
            $sso->username,
            $sso->name,
            $sso->department,
            $sso->position,
            $sso->email,
            $sso->account_type,
            $sso->transferred_from,
            $sso->status,
            $sso->created_at?->format('M d, Y h:i A'),
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
