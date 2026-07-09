<?php

namespace App\Exports;

use App\Models\SsoAccount;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SsoAccountsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = SsoAccount::query();

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['account_type'])) {
            $query->where('account_type', $this->filters['account_type']);
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
