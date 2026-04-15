<x-dashboard-layout title="Schedule Details">
<div class="max-w-3xl">
    <a href="{{ route('schedules.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back to schedules</a>

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $schedule->route->name }}</h2>
                <p class="text-sm text-gray-400 mt-1">Schedule #{{ $schedule->id }} · {{ $schedule->schedule_date->format('D, d M Y') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('schedules.edit', $schedule) }}" class="px-3 py-1.5 border border-gray-200 text-sm rounded-lg">Edit</a>
                @if(auth()->user()->hasRole(['admin','executive_officer']))
                    @if($schedule->status === 'active')
                        <form method="POST" action="{{ route('schedules.deactivate', $schedule) }}" onsubmit="return confirm('Deactivate this schedule?')">
                            @csrf
                            <button class="px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-sm rounded-lg">Deactivate</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('schedules.activate', $schedule) }}">
                            @csrf
                            <button class="px-3 py-1.5 bg-green-50 text-green-700 border border-green-200 text-sm rounded-lg">Activate</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <dl class="grid grid-cols-3 gap-x-6 gap-y-4 text-sm">
            <div><dt class="text-gray-400 text-xs">Bus</dt><dd class="font-mono font-semibold text-blue-700 mt-0.5">{{ $schedule->bus->depot_reg_no }}</dd></div>
            <div><dt class="text-gray-400 text-xs">Departure</dt><dd class="font-medium text-gray-800 mt-0.5">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</dd></div>
            <div><dt class="text-gray-400 text-xs">Arrival</dt><dd class="font-medium text-gray-800 mt-0.5">{{ \Carbon\Carbon::parse($schedule->arrival_time)->format('h:i A') }}</dd></div>
            <div><dt class="text-gray-400 text-xs">Driver</dt><dd class="text-gray-700 mt-0.5">{{ $schedule->driver?->name ?? 'Not assigned' }}</dd></div>
            <div><dt class="text-gray-400 text-xs">Conductor</dt><dd class="text-gray-700 mt-0.5">{{ $schedule->conductor?->name ?? 'Not assigned' }}</dd></div>
            <div><dt class="text-gray-400 text-xs">Fare</dt><dd class="font-semibold text-gray-800 mt-0.5">LKR {{ number_format($schedule->fare, 2) }}</dd></div>
            <div><dt class="text-gray-400 text-xs">Available seats</dt><dd class="text-gray-800 mt-0.5">{{ $schedule->availableSeatsCount() }} / {{ $schedule->bus->seat_count }}</dd></div>
            <div><dt class="text-gray-400 text-xs">Status</dt>
                @php $sc=['active'=>'bg-green-50 text-green-700','inactive'=>'bg-gray-100 text-gray-500','cancelled'=>'bg-red-50 text-red-600']; @endphp
                <dd class="mt-0.5"><span class="px-2 py-0.5 rounded text-xs {{ $sc[$schedule->status] ?? '' }}">{{ ucfirst($schedule->status) }}</span></dd>
            </div>
        </dl>
    </div>

    {{-- Bookings on this schedule --}}
    @if($schedule->bookings->count())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">Bookings ({{ $schedule->bookings->count() }})</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-2 text-left text-gray-500 text-xs font-medium">Ref.</th>
                <th class="px-4 py-2 text-left text-gray-500 text-xs font-medium">Passenger</th>
                <th class="px-4 py-2 text-left text-gray-500 text-xs font-medium">Seat</th>
                <th class="px-4 py-2 text-left text-gray-500 text-xs font-medium">Payment</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($schedule->bookings as $booking)
                <tr>
                    <td class="px-4 py-2 font-mono text-xs text-gray-600">{{ $booking->booking_ref }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $booking->passenger_name }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $booking->seat->seat_number ?? '—' }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-0.5 rounded text-xs {{ $booking->payment_status === 'paid' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
</x-dashboard-layout>