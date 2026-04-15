@component('mail::message')
# Hi {{ $firstName }}, your booking is confirmed!

Thank you for choosing SLTB Yatinuwara Depot. Your seat(s) have been successfully reserved and payment received.

---

@foreach($bookings as $booking)
## {{ $booking->booking_ref }}

@component('mail::panel')
**Route:** {{ $booking->schedule->route->name }}
**Date:** {{ $booking->schedule->schedule_date->format('D, d M Y') }}
**Departure:** {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A') }}
**Bus:** {{ $booking->schedule->bus->depot_reg_no }}
**Seat:** {{ $booking->seat->seat_number }}
**Amount paid:** LKR {{ number_format($booking->amount, 2) }}
@endcomponent

@component('mail::button', ['url' => route('booking.receipt', $booking->booking_ref), 'color' => 'blue'])
Download Receipt (PDF)
@endcomponent

@endforeach

---

Please present your booking reference or receipt when boarding.

Thank You,
**SLTB Yatinuwara Depot**
@endcomponent