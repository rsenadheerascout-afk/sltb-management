<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1f2937; background: #fff; }

        .header { background: #1e3a5f; color: #fff; padding: 20px 24px; }
        .header h1 { font-size: 18px; font-weight: bold; margin-bottom: 2px; }
        .header p  { font-size: 11px; opacity: 0.7; }

        .ref-block { background: #eff6ff; border-left: 4px solid #1d4ed8; padding: 14px 20px; margin: 16px 24px; }
        .ref-block .ref   { font-size: 20px; font-weight: bold; color: #1d4ed8; font-family: monospace; }
        .ref-block .label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge { display: inline-block; background: #d1fae5; color: #065f46; padding: 2px 10px; border-radius: 99px; font-size: 10px; font-weight: 700; text-transform: uppercase; margin-top: 6px; }

        .section       { padding: 0 24px 14px; }
        .section-title { font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; color: #9ca3af; margin-bottom: 8px; font-weight: bold; }
        .row           { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .row .key      { color: #6b7280; }
        .row .val      { font-weight: 500; text-align: right; }

        .divider { border-top: 1px solid #e5e7eb; margin: 12px 24px; }

        .total-block { background: #f9fafb; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; }
        .total-block .key { font-weight: 600; font-size: 13px; }
        .total-block .val { font-weight: 800; font-size: 18px; color: #1e3a5f; }

        .footer { text-align: center; padding: 14px 24px; font-size: 10px; color: #9ca3af; border-top: 1px solid #e5e7eb; margin-top: 10px; }
    </style>
</head>
<body>

<div class="header">
    <h1>SLTB Yatinuwara Depot</h1>
    <p>Sri Lanka Transport Board &middot; Bus Seat Booking Receipt</p>
</div>

<div class="ref-block">
    <div class="label">Booking Reference</div>
    <div class="ref">{{ $booking->booking_ref }}</div>
    <div>
        <span class="badge">{{ strtoupper($booking->payment_status) }}</span>
        <span style="font-size:10px; color:#6b7280; margin-left:8px;">
            Booked {{ $booking->booked_at?->format('d M Y, h:i A') ?? $booking->created_at->format('d M Y, h:i A') }}
        </span>
    </div>
</div>

<div class="section">
    <div class="section-title">Journey</div>
    <div class="row"><span class="key">Route</span><span class="val">{{ $booking->schedule->route->name }}</span></div>
    <div class="row"><span class="key">From</span><span class="val">{{ $booking->schedule->route->origin }}</span></div>
    <div class="row"><span class="key">To</span><span class="val">{{ $booking->schedule->route->destination }}</span></div>
    <div class="row"><span class="key">Date</span><span class="val">{{ $booking->schedule->schedule_date->format('D, d M Y') }}</span></div>
    <div class="row"><span class="key">Departure</span><span class="val">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A') }}</span></div>
    <div class="row"><span class="key">Bus</span><span class="val" style="font-family:monospace; color:#1d4ed8; font-weight:700;">{{ $booking->schedule->bus->depot_reg_no }}</span></div>
    <div class="row"><span class="key">Seat number</span><span class="val" style="font-size:16px; font-weight:800;">{{ $booking->seat->seat_number }}</span></div>
</div>

<div class="divider"></div>

<div class="section">
    <div class="section-title">Passenger</div>
    <div class="row"><span class="key">Name</span><span class="val">{{ $booking->passenger_name }}</span></div>
    <div class="row"><span class="key">Email</span><span class="val">{{ $booking->passenger_email }}</span></div>
    <div class="row"><span class="key">Phone</span><span class="val">{{ $booking->passenger_phone }}</span></div>
    @if($booking->passenger_nic)
    <div class="row"><span class="key">NIC</span><span class="val">{{ $booking->passenger_nic }}</span></div>
    @endif
</div>

<div class="total-block">
    <span class="key">Amount Paid</span>
    <span class="val">LKR {{ number_format($booking->amount, 2) }}</span>
</div>

<div class="footer">
    <strong style="color:#4b5563;">SLTB Yatinuwara Depot — Official Receipt</strong><br>
    Present this receipt when boarding. Ref: {{ $booking->booking_ref }}<br>
    Generated: {{ now()->format('d M Y, h:i A') }}
</div>

</body>
</html>