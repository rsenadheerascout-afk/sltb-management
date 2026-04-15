<x-dashboard-layout title="Bookings">
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">All Bookings</h2>
        <p class="text-sm text-gray-500">{{ $bookings->total() }} total records</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Reference</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Passenger</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Route</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Date</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Seat</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Amount</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Status</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Receipt</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($bookings as $booking)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-xs text-blue-700 font-medium">
                    {{ $booking->booking_ref }}
                </td>
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800">{{ $booking->passenger_name }}</p>
                    <p class="text-xs text-gray-400">{{ $booking->passenger_email }}</p>
                </td>
                <td class="px-4 py-3 text-gray-700">{{ $booking->schedule->route->name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">
                    {{ $booking->schedule->schedule_date?->format('d M Y') ?? '—' }}
                </td>
                <td class="px-4 py-3 text-gray-700 font-medium">{{ $booking->seat->seat_number ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-700">LKR {{ number_format($booking->amount, 2) }}</td>
                <td class="px-4 py-3">
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
                <td class="px-4 py-3">
                    @if($booking->isPaid())
                        <a href="{{ route('booking.receipt', $booking->booking_ref) }}"
                           class="text-xs text-blue-600 hover:underline">PDF</a>
                    @else
                        <span class="text-xs text-gray-300">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">
                    No bookings yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $bookings->links() }}</div>
</x-dashboard-layout>