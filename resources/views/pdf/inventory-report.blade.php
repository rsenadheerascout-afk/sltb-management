<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1f2937; }

        .header { background: #1e3a5f; color: #fff; padding: 16px 20px; margin-bottom: 16px; }
        .header h1 { font-size: 16px; font-weight: bold; }
        .header p  { font-size: 10px; opacity: 0.7; margin-top: 2px; }

        .summary { display: flex; gap: 16px; padding: 0 20px 16px; }
        .stat { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; flex: 1; }
        .stat .label { font-size: 9px; text-transform: uppercase; color: #9ca3af; letter-spacing: 0.5px; }
        .stat .value { font-size: 20px; font-weight: bold; color: #1e3a5f; margin-top: 2px; }

        table { width: calc(100% - 40px); margin: 0 20px; border-collapse: collapse; }
        thead th { background: #1e3a5f; color: #fff; padding: 8px 10px; text-align: left; font-size: 10px; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 9px; font-weight: 600; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-amber  { background: #fef3c7; color: #92400e; }
        .badge-red    { background: #fee2e2; color: #991b1b; }

        .section-title { padding: 12px 20px 8px; font-size: 12px; font-weight: bold; color: #374151; }

        .footer { margin-top: 20px; padding: 12px 20px 0; border-top: 1px solid #e5e7eb;
                  text-align: center; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>

<div class="header">
    <h1>SLTB Yatinuwara Depot — Inventory Report</h1>
    <p>Generated: {{ now()->format('D, d M Y \a\t h:i A') }}</p>
</div>

{{-- Summary stats --}}
<div class="summary">
    <div class="stat">
        <div class="label">Total items</div>
        <div class="value">{{ $items->count() }}</div>
    </div>
    <div class="stat">
        <div class="label">Low stock</div>
        <div class="value" style="color: #d97706;">{{ $lowItems->count() }}</div>
    </div>
    <div class="stat">
        <div class="label">Out of stock</div>
        <div class="value" style="color: #dc2626;">{{ $items->filter(fn($i) => $i->isOutOfStock())->count() }}</div>
    </div>
    <div class="stat">
        <div class="label">Categories</div>
        <div class="value">{{ $items->pluck('category')->unique()->count() }}</div>
    </div>
</div>

{{-- Low stock alerts --}}
@if($lowItems->count())
<div class="section-title" style="color: #92400e;">&#9888; Items requiring attention</div>
<table>
    <thead>
        <tr>
            <th>Item</th>
            <th>Category</th>
            <th>Current qty</th>
            <th>Threshold</th>
            <th>Unit</th>
            <th>Supplier</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lowItems->sortBy('quantity') as $item)
        <tr>
            <td><strong>{{ $item->name }}</strong></td>
            <td>{{ $item->category }}</td>
            <td><strong style="color:{{ $item->isOutOfStock() ? '#dc2626' : '#d97706' }}">
                {{ number_format($item->quantity, 2) }}
            </strong></td>
            <td>{{ number_format($item->low_stock_threshold, 2) }}</td>
            <td>{{ $item->unit }}</td>
            <td>{{ $item->supplier ?? '—' }}</td>
            <td>
                <span class="badge {{ $item->isOutOfStock() ? 'badge-red' : 'badge-amber' }}">
                    {{ $item->isOutOfStock() ? 'Out of stock' : 'Low stock' }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Full inventory list --}}
<div class="section-title">Complete Inventory List</div>
<table>
    <thead>
        <tr>
            <th>Item</th>
            <th>Category</th>
            <th>In stock</th>
            <th>Unit</th>
            <th>Threshold</th>
            <th>Unit price</th>
            <th>Supplier</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items->groupBy('category') as $category => $categoryItems)
            <tr>
                <td colspan="8" style="background:#f3f4f6; font-weight:bold; color:#374151; padding: 5px 10px;">
                    {{ $category }}
                </td>
            </tr>
            @foreach($categoryItems as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category }}</td>
                <td><strong>{{ number_format($item->quantity, 2) }}</strong></td>
                <td>{{ $item->unit }}</td>
                <td>{{ number_format($item->low_stock_threshold, 2) }}</td>
                <td>{{ $item->unit_price ? 'LKR ' . number_format($item->unit_price, 2) : '—' }}</td>
                <td>{{ $item->supplier ?? '—' }}</td>
                <td>
                    <span class="badge {{ match($item->stock_status) {
                        'out_of_stock' => 'badge-red',
                        'low_stock'    => 'badge-amber',
                        default        => 'badge-green',
                    } }}">{{ $item->stock_badge_label }}</span>
                </td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

<div class="footer">
    SLTB Yatinuwara Depot &middot; Inventory Report &middot; {{ now()->format('d M Y') }}
</div>

</body>
</html>