<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:Arial, sans-serif; font-size:11px; color:#1f2937; }

        .header { background:#1e3a5f; color:#fff; padding:16px 20px; margin-bottom:16px; }
        .header h1 { font-size:16px; font-weight:bold; }
        .header p { font-size:10px; opacity:0.75; margin-top:3px; }

        .meta { display:flex; gap:20px; padding:0 20px 14px; }
        .meta-item { background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:8px 14px; }
        .meta-item .label { font-size:9px; text-transform:uppercase; color:#9ca3af; letter-spacing:0.4px; }
        .meta-item .value { font-size:14px; font-weight:bold; color:#1e3a5f; margin-top:2px; }

        table { width:calc(100% - 40px); margin:0 20px; border-collapse:collapse; }
        thead th { background:#1e3a5f; color:#fff; padding:8px 10px; text-align:left; font-size:10px; font-weight:600; }
        tbody tr:nth-child(even) { background:#f9fafb; }
        tbody td { padding:7px 10px; border-bottom:1px solid #e5e7eb; font-size:11px; }

        .badge { display:inline-block; padding:2px 8px; border-radius:99px; font-size:9px; font-weight:600; }
        .badge-assigned  { background:#dbeafe; color:#1d4ed8; }
        .badge-completed { background:#d1fae5; color:#065f46; }
        .badge-absent    { background:#fee2e2; color:#991b1b; }

        .role-driver    { background:#ede9fe; color:#4c1d95; }
        .role-conductor { background:#fce7f3; color:#831843; }

        .footer { margin-top:16px; padding:10px 20px 0; border-top:1px solid #e5e7eb;
                  text-align:center; font-size:9px; color:#9ca3af; }
    </style>
</head>
<body>

<div class="header">
    <h1>SLTB Yatinuwara Depot — Duty Roster</h1>
    <p>Printed: {{ now()->format('D, d M Y \a\t h:i A') }}</p>
</div>

<div class="meta">
    <div class="meta-item">
        <div class="label">Roster Date</div>
        <div class="value">{{ \Carbon\Carbon::parse($date)->format('D, d M Y') }}</div>
    </div>
    <div class="meta-item">
        <div class="label">Total Assignments</div>
        <div class="value">{{ $rosters->count() }}</div>
    </div>
    <div class="meta-item">
        <div class="label">Drivers</div>
        <div class="value">{{ $rosters->where('user.role', 'driver')->count() }}</div>
    </div>
    <div class="meta-item">
        <div class="label">Conductors</div>
        <div class="value">{{ $rosters->where('user.role', 'conductor')->count() }}</div>
    </div>
</div>

@if($rosters->isEmpty())
<p style="padding:20px; text-align:center; color:#9ca3af;">
    No duty assignments for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}.
</p>
@else
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Employee</th>
            <th>Employee ID</th>
            <th>Role</th>
            <th>Route</th>
            <th>Bus</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Fare (LKR)</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rosters as $i => $roster)
        <tr>
            <td style="color:#9ca3af;">{{ $i + 1 }}</td>
            <td><strong>{{ $roster->user->name }}</strong></td>
            <td style="font-family:monospace; color:#1d4ed8;">{{ $roster->user->employee_id }}</td>
            <td>
                <span class="badge {{ $roster->user->role === 'driver' ? 'role-driver' : 'role-conductor' }}">
                    {{ ucfirst($roster->user->role) }}
                </span>
            </td>
            <td>{{ $roster->schedule->route->name ?? '—' }}</td>
            <td style="font-family:monospace; font-weight:bold; color:#1d4ed8;">
                {{ $roster->schedule->bus->depot_reg_no ?? '—' }}
            </td>
            <td><strong>{{ $roster->schedule ? \Carbon\Carbon::parse($roster->schedule->departure_time)->format('h:i A') : '—' }}</strong></td>
            <td>{{ $roster->schedule ? \Carbon\Carbon::parse($roster->schedule->arrival_time)->format('h:i A') : '—' }}</td>
            <td>{{ $roster->schedule ? number_format($roster->schedule->fare, 2) : '—' }}</td>
            <td>
                <span class="badge badge-{{ $roster->status }}">
                    {{ ucfirst($roster->status) }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<div class="footer">
    SLTB Yatinuwara Depot &middot; Duty Roster &middot;
    {{ \Carbon\Carbon::parse($date)->format('d M Y') }} &middot;
    Printed {{ now()->format('d M Y h:i A') }}
</div>

</body>
</html>