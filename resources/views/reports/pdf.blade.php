<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>
        @if($type === 'tickets') IT Requests Report
        @elseif($type === 'employees') Employees Report
        @else SSO Accounts Report
        @endif
    </title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Nunito', 'Segoe UI', Arial, sans-serif;
            font-size: 9px;
            color: #1e293b;
            line-height: 1.5;
        }

        .doc-header {
            padding: 18px 24px 14px;
            border-bottom: 3px solid #6b7280;
        }
        .doc-header-top {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .doc-header-left {
            display: table-cell;
            vertical-align: middle;
        }
        .doc-header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }
        .doc-org {
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .doc-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 2px;
        }
        .doc-ref {
            font-size: 8px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* ── Metadata Grid ── */
        .meta-grid {
            padding: 10px 24px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .meta-grid table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        .meta-grid td {
            padding: 3px 0;
            border: none;
            font-size: 8.5px;
            color: #475569;
            vertical-align: top;
        }
        .meta-grid .meta-label {
            font-weight: 700;
            color: #334155;
            width: 90px;
        }
        .meta-grid .meta-sep {
            width: 12px;
            color: #cbd5e1;
        }

        /* ── Summary Strip ── */
        .summary-strip {
            padding: 8px 24px;
            font-size: 9px;
            color: #334155;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
        }

        /* ── Data Table ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        .data-table thead th {
            background: #6b7280;
            color: #ffffff;
            padding: 7px 8px;
            text-align: left;
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border: none;
        }
        .data-table thead th:first-child {
            text-align: center;
            width: 28px;
        }
        .data-table tbody td {
            padding: 5px 8px;
            font-size: 8px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
            word-wrap: break-word;
            color: #334155;
        }
        .data-table tbody tr:nth-child(even) td {
            background: #f8fafc;
        }
        .data-table tbody td:first-child {
            text-align: center;
            color: #94a3b8;
            font-size: 7.5px;
            font-weight: 600;
        }

        /* ── Status Badges ── */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .badge-resolved     { background: #dcfce7; color: #15803d; }
        .badge-in-progress  { background: #dbeafe; color: #1d4ed8; }
        .badge-escalated    { background: #fef3c7; color: #92400e; }
        .badge-not-complete { background: #fee2e2; color: #b91c1c; }
        .badge-cancelled    { background: #f1f5f9; color: #475569; }
        .badge-active       { background: #dcfce7; color: #15803d; }
        .badge-inactive     { background: #f1f5f9; color: #475569; }
        .badge-locked       { background: #fef3c7; color: #92400e; }
        .badge-resigned     { background: #fee2e2; color: #b91c1c; }

        /* ── Footer ── */
        .doc-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 8px 24px;
            border-top: 1px solid #e2e8f0;
            background: #ffffff;
        }
        .doc-footer table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        .doc-footer td {
            font-size: 7px;
            color: #94a3b8;
            padding: 0;
            border: none;
            vertical-align: middle;
        }

        .no-data {
            text-align: center;
            padding: 50px 24px;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    @php
        $reportTitles = [
            'tickets' => 'IT Requests Report',
            'employees' => 'Employees Report',
            'sso_accounts' => 'SSO Accounts Report',
        ];
        $reportTitle = $reportTitles[$type] ?? 'Report';
        $refNo = strtoupper(substr($type, 0, 3)) . '-' . now()->format('Ymd-His');
    @endphp

    {{-- Document Header --}}
    <div class="doc-header">
        <div class="doc-header-top">
            <div class="doc-header-left">
                <div class="doc-org">IT Department</div>
                <div class="doc-title">{{ $reportTitle }}</div>
            </div>
            <div class="doc-header-right">
                <div class="doc-ref">Ref: {{ $refNo }}</div>
                <div class="doc-ref">Classification: Internal Use Only</div>
            </div>
        </div>
    </div>

    {{-- Metadata --}}
    <div class="meta-grid">
        <table>
            <tr>
                <td class="meta-label">Date Generated</td>
                <td class="meta-sep">:</td>
                <td>{{ $generatedAt }}</td>
                <td class="meta-label" style="padding-left: 20px;">Total Records</td>
                <td class="meta-sep">:</td>
                <td>{{ $data->count() }}</td>
            </tr>
            @if(!empty($filters['date_from']) || !empty($filters['date_to']))
            <tr>
                <td class="meta-label">Date Range</td>
                <td class="meta-sep">:</td>
                <td colspan="4">
                    {{ !empty($filters['date_from']) ? \Carbon\Carbon::parse($filters['date_from'])->format('M d, Y') : 'Start' }}
                    —
                    {{ !empty($filters['date_to']) ? \Carbon\Carbon::parse($filters['date_to'])->format('M d, Y') : 'Present' }}
                </td>
            </tr>
            @endif
        </table>
    </div>

    @if($data->count() === 0)
        <div class="no-data">No records found matching the selected filters.</div>
    @else

        {{-- TICKETS TABLE --}}
        @if($type === 'tickets')
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th style="width: 7%;">Ticket No.</th>
                    <th style="width: 10%;">Type</th>
                    <th style="width: 13%;">Request</th>
                    <th style="width: 10%;">Requested By</th>
                    <th style="width: 10%;">Department</th>
                    <th style="width: 8%;">Branch</th>
                    <th style="width: 10%;">Assisted By</th>
                    <th style="width: 7%;">Status</th>
                    <th style="width: 12%;">Remarks</th>
                    <th style="width: 10%;">Date</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $assistedByMap = ['IT03' => 'Tristan Railey Tan', 'IT04' => 'John Paul Villacorta', 'Both' => 'Both'];
                @endphp
                @foreach($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->ticket_no }}</td>
                    <td>{{ $row->request_type }}</td>
                    <td>{{ $row->request }}</td>
                    <td>{{ $row->requested_by }}</td>
                    <td>{{ $row->department }}</td>
                    <td>{{ $row->branch }}</td>
                    <td>{{ $assistedByMap[$row->assisted_by] ?? $row->assisted_by }}</td>
                    <td>
                        @php
                            $bc = match($row->status) {
                                'Resolved' => 'badge-resolved',
                                'In Progress' => 'badge-in-progress',
                                'Escalated' => 'badge-escalated',
                                'Not Complete' => 'badge-not-complete',
                                'Cancelled' => 'badge-cancelled',
                                default => '',
                            };
                        @endphp
                        <span class="badge {{ $bc }}">{{ $row->status }}</span>
                    </td>
                    <td>{{ $row->remarks }}</td>
                    <td>{{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('M d, Y') : ($row->original_created_at ? \Carbon\Carbon::parse($row->original_created_at)->format('M d, Y') : '') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- EMPLOYEES TABLE --}}
        @if($type === 'employees')
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th style="width: 16%;">Full Name</th>
                    <th style="width: 7%;">NFP ID</th>
                    <th style="width: 13%;">Position</th>
                    <th style="width: 12%;">Department</th>
                    <th style="width: 11%;">Branch</th>
                    <th style="width: 10%;">Contact No.</th>
                    <th style="width: 7%;">Status</th>
                    <th style="width: 11%;">Resigned Date</th>
                    <th style="width: 11%;">Date Added</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->full_name }}</td>
                    <td>{{ $row->nfp_id }}</td>
                    <td>{{ $row->position }}</td>
                    <td>{{ $row->department }}</td>
                    <td>{{ $row->branch }}</td>
                    <td>{{ $row->contact_no }}</td>
                    <td>
                        <span class="badge {{ $row->employment_status === 'Active' ? 'badge-active' : 'badge-resigned' }}">
                            {{ $row->employment_status }}
                        </span>
                    </td>
                    <td>{{ $row->resigned_date ? \Carbon\Carbon::parse($row->resigned_date)->format('M d, Y') : '' }}</td>
                    <td>{{ $row->created_at?->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- SSO ACCOUNTS TABLE --}}
        @if($type === 'sso_accounts')
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th style="width: 13%;">Username</th>
                    <th style="width: 15%;">Name</th>
                    <th style="width: 12%;">Department</th>
                    <th style="width: 12%;">Position</th>
                    <th style="width: 13%;">Email</th>
                    <th style="width: 7%;">Type</th>
                    <th style="width: 10%;">Transferred From</th>
                    <th style="width: 6%;">Status</th>
                    <th style="width: 8%;">Date Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->username }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->department }}</td>
                    <td>{{ $row->position }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->account_type }}</td>
                    <td>{{ $row->transferred_from }}</td>
                    <td>
                        @php
                            $sc = match($row->status) {
                                'Active' => 'badge-active',
                                'Inactive' => 'badge-inactive',
                                'Locked' => 'badge-locked',
                                default => '',
                            };
                        @endphp
                        <span class="badge {{ $sc }}">{{ $row->status }}</span>
                    </td>
                    <td>{{ $row->created_at?->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

    @endif

    {{-- Footer --}}
    <div class="doc-footer">
        <table>
            <tr>
                <td>IT Request System · {{ $reportTitle }}</td>
                <td style="text-align: center;">{{ $generatedAt }}</td>
                <td style="text-align: right;">Internal Use Only · Confidential</td>
            </tr>
        </table>
    </div>
</body>
</html>
