<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Confirmed — SLTB</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

    <header class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="max-w-xl mx-auto">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="h-10 w-auto flex items-center justify-center flex-shrink-0">
                    <img src="{{ asset('images/sltb-logo.png') }}" alt="SLTB Logo" class="w-full h-full object-contain">
                </div>
                <div>
                <span class="text-sm font-semibold text-gray-800">SLTB Yatinuwara</span>
                <span class="text-gray-400 text-xs block leading-tight">Seat Reservation</span>
                </div>
            </a>
        </div>
    </header>

    <main class="max-w-xl mx-auto px-6 py-10">

        {{-- Success banner --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Booking Confirmed!</h1>
            <p class="text-gray-500 text-sm mt-1">
                Confirmation sent to <strong>{{ $bookings->first()->passenger_email }}</strong>
            </p>
        </div>

        {{-- One card per seat booked --}}
        @foreach($bookings as $booking)
        <div class="bg-white rounded-xl border border-gray-200 p-5 mb-4">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="font-mono text-lg font-bold text-blue-700">{{ $booking->booking_ref }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Booking reference</p>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-semibold">
                    Paid
                </span>
            </div>

            <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                <div>
                    <dt class="text-xs text-gray-400">Route</dt>
                    <dd class="font-medium text-gray-800 mt-0.5">{{ $booking->schedule->route->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400">Bus</dt>
                    <dd class="font-mono text-blue-700 font-semibold mt-0.5">{{ $booking->schedule->bus->depot_reg_no }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400">Date</dt>
                    <dd class="text-gray-700 mt-0.5">{{ $booking->schedule->schedule_date->format('D, d M Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400">Departure</dt>
                    <dd class="text-gray-700 mt-0.5">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400">Seat</dt>
                    <dd class="font-bold text-gray-800 text-base mt-0.5">{{ $booking->seat->seat_number }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-400">Amount paid</dt>
                    <dd class="font-semibold text-gray-800 mt-0.5">LKR {{ number_format($booking->amount, 2) }}</dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-xs text-gray-400">Passenger</dt>
                    <dd class="text-gray-700 mt-0.5">{{ $booking->passenger_name }} &middot; {{ $booking->passenger_phone }}</dd>
                </div>
            </dl>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('booking.receipt', $booking->booking_ref) }}"
                   class="flex items-center justify-center gap-2 w-full py-2.5 border border-blue-200
                          text-blue-600 text-sm font-medium rounded-lg hover:bg-blue-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download Receipt (PDF)
                </a>
            </div>
        </div>
        @endforeach

        <div class="text-center mt-6 space-y-2">
            <a href="{{ route('schedules.public') }}"
               class="block text-sm text-blue-600 hover:underline">
                Book another journey
            </a>
            @if(auth()->guard('passenger')->check())
            <a href="{{ route('passenger.bookings') }}"
               class="block text-sm text-gray-500 hover:underline">
                View all my bookings
            </a>
            @endif
        </div>

    </main>

</body>
</html>