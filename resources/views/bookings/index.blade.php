<x-dashboard-layout title="Bookings">

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">All Bookings</h2>
        <p class="text-sm text-gray-500">{{ $bookings->total() }} total records</p>
    </div>
</div>

{{-- Filtered summary bar — updates based on current filters --}}
@if(request()->hasAny(['search','route','status','travel_date','seat','bus']))
<div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 mb-4 flex items-center justify-between">
    <p class="text-sm text-blue-700">
        <span class="font-semibold">{{ $filteredCount }}</span> booking{{ $filteredCount !== 1 ? 's' : '' }} match your filters
    </p>
    <p class="text-sm text-blue-700">
        Paid total: <span class="font-semibold">LKR {{ number_format($filteredTotal, 2) }}</span>
    </p>
</div>
@endif

{{-- Filters --}}
<form method="GET" class="flex gap-3 mb-6 flex-wrap">

    {{-- Search --}}
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search booking ref, passenger name, email..."
           class="flex-1 min-w-64 px-3 py-2 border border-gray-200 rounded-lg text-sm
                  focus:ring-2 focus:ring-blue-500 focus:outline-none">

    {{-- Route --}}
    <select name="route"
            class="px-3 py-2 border border-gray-200 rounded-lg text-sm
                   focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <option value="">All Routes</option>
        @foreach($routes as $route)
            <option value="{{ $route->id }}" @selected(request('route') == $route->id)>
                {{ $route->name }}
            </option>
        @endforeach
    </select>

    {{-- Payment Status --}}
    <select name="status"
            class="px-3 py-2 border border-gray-200 rounded-lg text-sm
                   focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <option value="">All Status</option>
        <option value="paid"     @selected(request('status')==='paid')>Paid</option>
        <option value="pending"  @selected(request('status')==='pending')>Pending</option>
        <option value="failed"   @selected(request('status')==='failed')>Failed</option>
        <option value="refunded" @selected(request('status')==='refunded')>Refunded</option>
    </select>

    {{-- Travel Date --}}
    <input type="date" name="travel_date" value="{{ request('travel_date') }}"
           class="px-3 py-2 border border-gray-200 rounded-lg text-sm
                  focus:ring-2 focus:ring-blue-500 focus:outline-none">

    {{-- Seat number (text — seat numbers repeat across buses, so a dropdown of all of them isn't useful) --}}
    <input type="text" name="seat" value="{{ request('seat') }}"
           placeholder="Seat no. e.g. 05"
           class="w-32 px-3 py-2 border border-gray-200 rounded-lg text-sm
                  focus:ring-2 focus:ring-blue-500 focus:outline-none">

    {{-- Bus --}}
    <select name="bus"
            class="px-3 py-2 border border-gray-200 rounded-lg text-sm
                   focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <option value="">All Buses</option>
        @foreach($buses as $bus)
            <option value="{{ $bus->id }}" @selected(request('bus') == $bus->id)>
                {{ $bus->depot_reg_no }} — {{ $bus->vehicle_no }}
            </option>
        @endforeach
    </select>

    {{-- Sort --}}
    <select name="sort"
            class="px-3 py-2 border border-gray-200 rounded-lg text-sm
                   focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <option value=""            @selected(request('sort')==='' || !request()->has('sort'))>Newest First</option>
        <option value="oldest"      @selected(request('sort')==='oldest')>Oldest First</option>
        <option value="amount_high" @selected(request('sort')==='amount_high')>Highest Amount</option>
        <option value="amount_low"  @selected(request('sort')==='amount_low')>Lowest Amount</option>
    </select>

    <button type="submit"
            class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700">
        Filter
    </button>
    <a href="{{ route('bookings.index') }}"
       class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">
        Reset
    </a>
</form>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Reference</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Passenger</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Route</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Bus</th>
                <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Travel Date</th>
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
                <td class="px-4 py-3 text-gray-700">
                    {{ $booking->schedule->route->name ?? '—' }}
                </td>
                <td class="px-4 py-3 font-mono text-xs font-semibold text-blue-700">
                    {{ $booking->schedule->bus->depot_reg_no ?? '—' }}
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">
                    {{ $booking->schedule->schedule_date?->format('d M Y') ?? '—' }}
                </td>
                <td class="px-4 py-3 text-gray-700 font-medium">
                    {{ $booking->seat->seat_number ?? '—' }}
                </td>
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
                <td colspan="9" class="px-4 py-12 text-center text-gray-400 text-sm">
                    No bookings match your filters.
                    @if(request()->hasAny(['search','route','status','travel_date','seat','bus']))
                        <a href="{{ route('bookings.index') }}" class="text-blue-600 hover:underline ml-1">Clear filters</a>
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $bookings->links() }}</div>
</x-dashboard-layout>