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
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Nunito', sans-serif; font-size: 10px; color: #334155; line-height: 1.4; }

        .header {
            background: #ffffff;
            padding: 20px 24px;
            border-bottom: 2px solid #2563eb;
            display: table;
            width: 100%;
        }
        .header-content {
            display: table-cell;
            vertical-align: middle;
        }
        .header-title { 
            font-size: 22px; 
            font-weight: 700; 
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .header-sub { 
            font-size: 11px; 
            color: #64748b; 
            margin-top: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .meta-bar {
            background: #f8fafc;
            padding: 12px 24px;
            font-size: 9px;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
        }
        .meta-bar span { margin-right: 24px; display: inline-block; }
        .meta-bar strong { color: #0f172a; font-weight: 700; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background: #f1f5f9;
            color: #334155;
            padding: 10px 12px;
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #cbd5e1;
        }
        td {
            padding: 8px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 9px;
            vertical-align: middle;
            word-wrap: break-word;
        }
        tr:nth-child(even) { background: #fdfdfd; }
        tr:hover { background: #f8fafc; }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            padding: 10px;
            border-top: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .status-resolved { background: #dcfce7; color: #166534; }
        .status-in-progress { background: #dbeafe; color: #1e40af; }
        .status-escalated { background: #fef9c3; color: #854d0e; }
        .status-not-complete { background: #fee2e2; color: #991b1b; }
        .status-cancelled { background: #f1f5f9; color: #475569; }
        .status-active { background: #dcfce7; color: #166534; }
        .status-inactive { background: #fee2e2; color: #991b1b; }
        .status-locked { background: #fef9c3; color: #854d0e; }
        .status-resigned { background: #fee2e2; color: #991b1b; }

        .summary-bar {
            padding: 15px 24px 5px;
            font-size: 11px;
            color: #0f172a;
            font-weight: 700;
        }

        .no-data {
            text-align: center;
            padding: 60px;
            font-size: 14px;
            color: #94a3b8;
            font-style: italic;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="header-content">
            <div class="header-title">
                @if($type === 'tickets') IT Requests Report
                @elseif($type === 'employees') Employees Report
                @else SSO Accounts Report
                @endif
            </div>
            <div class="header-sub">IT Department • Internal System Report</div>
        </div>
    </div>

    {{-- Meta Bar --}}
    <div class="meta-bar">
        <span><strong>Generated:</strong> {{ $generatedAt }}</span>
        <span><strong>Total Records:</strong> {{ $data->count() }}</span>
        @if(!empty($filters['date_from']))
            <span><strong>From:</strong> {{ $filters['date_from'] }}</span>
        @endif
        @if(!empty($filters['date_to']))
            <span><strong>To:</strong> {{ $filters['date_to'] }}</span>
        @endif
        @if(!empty($filters['status']))
            <span><strong>Status:</strong> {{ $filters['status'] }}</span>
        @endif
        @if(!empty($filters['department']))
            <span><strong>Department:</strong> {{ $filters['department'] }}</span>
        @endif
    </div>

    <div class="summary-bar">
        Showing {{ $data->count() }} record{{ $data->count() !== 1 ? 's' : '' }}
    </div>

    @if($data->count() === 0)
        <div class="no-data">No records found matching the selected filters.</div>
    @else
        {{-- TICKETS TABLE --}}
        @if($type === 'tickets')
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Ticket No</th>
                    <th style="width: 10%;">Type</th>
                    <th style="width: 14%;">Request</th>
                    <th style="width: 12%;">Requested By</th>
                    <th style="width: 10%;">Department</th>
                    <th style="width: 8%;">Branch</th>
                    <th style="width: 10%;">Assisted By</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 12%;">Remarks</th>
                    <th style="width: 8%;">Date</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $assistedByMap = ['IT03' => 'Tristan Railey Tan', 'IT04' => 'John Paul Villacorta', 'Both' => 'Both'];
                @endphp
                @foreach($data as $row)
                <tr>
                    <td>{{ $row->ticket_no }}</td>
                    <td>{{ $row->request_type }}</td>
                    <td>{{ $row->request }}</td>
                    <td>{{ $row->requested_by }}</td>
                    <td>{{ $row->department }}</td>
                    <td>{{ $row->branch }}</td>
                    <td>{{ $assistedByMap[$row->assisted_by] ?? $row->assisted_by }}</td>
                    <td>
                        @php
                            $statusClass = match($row->status) {
                                'Resolved' => 'status-resolved',
                                'In Progress' => 'status-in-progress',
                                'Escalated' => 'status-escalated',
                                'Not Complete' => 'status-not-complete',
                                'Cancelled' => 'status-cancelled',
                                default => '',
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $row->status }}</span>
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
        <table>
            <thead>
                <tr>
                    <th style="width: 18%;">Full Name</th>
                    <th style="width: 8%;">NFP ID</th>
                    <th style="width: 14%;">Position</th>
                    <th style="width: 14%;">Department</th>
                    <th style="width: 12%;">Branch</th>
                    <th style="width: 10%;">Contact No</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 10%;">Resigned Date</th>
                    <th style="width: 8%;">Date Added</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{ $row->full_name }}</td>
                    <td>{{ $row->nfp_id }}</td>
                    <td>{{ $row->position }}</td>
                    <td>{{ $row->department }}</td>
                    <td>{{ $row->branch }}</td>
                    <td>{{ $row->contact_no }}</td>
                    <td>
                        <span class="status-badge {{ $row->employment_status === 'Active' ? 'status-active' : 'status-resigned' }}">
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
        <table>
            <thead>
                <tr>
                    <th style="width: 14%;">Username</th>
                    <th style="width: 16%;">Name</th>
                    <th style="width: 12%;">Department</th>
                    <th style="width: 12%;">Position</th>
                    <th style="width: 14%;">Email</th>
                    <th style="width: 8%;">Type</th>
                    <th style="width: 10%;">Transferred From</th>
                    <th style="width: 6%;">Status</th>
                    <th style="width: 8%;">Date Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{ $row->username }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->department }}</td>
                    <td>{{ $row->position }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->account_type }}</td>
                    <td>{{ $row->transferred_from }}</td>
                    <td>
                        @php
                            $ssoClass = match($row->status) {
                                'Active' => 'status-active',
                                'Inactive' => 'status-inactive',
                                'Locked' => 'status-locked',
                                default => '',
                            };
                        @endphp
                        <span class="status-badge {{ $ssoClass }}">{{ $row->status }}</span>
                    </td>
                    <td>{{ $row->created_at?->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    @endif

    {{-- Footer --}}
    <div class="footer">
        IT Request System &bull; Generated on {{ $generatedAt }} &bull; Confidential
    </div>
</body>
</html>
