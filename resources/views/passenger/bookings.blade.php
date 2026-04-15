<x-passenger-layout title="My Bookings">
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">My Bookings</h2>
            <p class="text-sm text-gray-500">{{ $bookings->total() }} total bookings</p>
        </div>
        <a href="{{ route('schedules.public') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 font-medium">
            + Book new seat
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Reference</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Route</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Date</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Departure</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Seat</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Amount</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Status</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Receipt</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-xs text-blue-700 font-medium">
                        {{ $booking->booking_ref }}
                    </td>
                    <td class="px-5 py-3 text-gray-700">
                        {{ $booking->schedule->route->name ?? '—' }}
                    </td>
                    <td class="px-5 py-3 text-gray-500">
                        {{ $booking->schedule->schedule_date?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="px-5 py-3 text-gray-500">
                        @if($booking->schedule)
                            {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A') }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-700 font-medium">
                        {{ $booking->seat->seat_number ?? '—' }}
                    </td>
                    <td class="px-5 py-3 text-gray-700">
                        LKR {{ number_format($booking->amount, 2) }}
                    </td>
                    <td class="px-5 py-3">
                        @php
                            $colors = [
                                'paid'     => 'bg-green-50 text-green-700',
                                'pending'  => 'bg-amber-50 text-amber-700',
                                'failed'   => 'bg-red-50 text-red-600',
                                'refunded' => 'bg-gray-100 text-gray-500',
                            ];
                        @endphp
                        <span class="px-2 py-0.5 rounded text-xs {{ $colors[$booking->payment_status] ?? '' }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        @if($booking->payment_status === 'paid' && \Illuminate\Support\Facades\Route::has('booking.receipt'))
                            <a href="{{ route('booking.receipt', $booking->booking_ref) }}"
                               class="text-xs text-blue-600 hover:underline">
                                PDF
                            </a>
                        @else
                            <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-gray-400 text-sm">
                        No bookings yet.
                        <a href="{{ route('schedules.public') }}"
                           class="text-blue-600 hover:underline ml-1">Browse schedules</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $bookings->links() }}</div>
</div>
</x-passenger-layout>