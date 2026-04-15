<x-passenger-layout title="My Dashboard">
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

    {{-- Avatar + welcome --}}
    <div class="md:col-span-2 bg-white rounded-xl border border-gray-200 p-6 flex items-center gap-5">
        <div class="relative flex-shrink-0">
            <img src="{{ $passenger->avatar_url }}" alt="Avatar"
                 class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
            <form method="POST" action="{{ route('passenger.avatar') }}" enctype="multipart/form-data" id="avatar-form">
                @csrf
                <label class="absolute -bottom-1 -right-1 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-blue-700">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <input type="file" name="avatar" accept="image/*" class="hidden"
                           onchange="document.getElementById('avatar-form').submit()">
                </label>
            </form>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-gray-800">{{ $passenger->name }}</h2>
            <p class="text-sm text-gray-400">{{ $passenger->email }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $passenger->phone }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Total spent</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">LKR {{ number_format($totalSpent, 0) }}</p>
        <a href="{{ route('passenger.bookings') }}" class="text-xs text-blue-600 hover:underline mt-2 inline-block">
            View all bookings &rarr;
        </a>
    </div>
</div>

{{-- Quick action --}}
<div class="mb-6">
    <a href="{{ route('schedules.public') }}"
       class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4v16m8-8H4"/>
        </svg>
        Book a new seat
    </a>
</div>

{{-- Recent bookings --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-sm font-semibold text-gray-800">Recent bookings</h3>
        <a href="{{ route('passenger.bookings') }}" class="text-xs text-blue-600 hover:underline">View all</a>
    </div>
    @if($recentBookings->isEmpty())
    <div class="px-5 py-12 text-center">
        <p class="text-gray-400 text-sm">No bookings yet. Book your first seat!</p>
    </div>
    @else
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Reference</th>
                <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Route</th>
                <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Date</th>
                <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Seat</th>
                <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Amount</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($recentBookings as $booking)
            <tr>
                <td class="px-5 py-3 font-mono text-xs text-blue-700 font-medium">{{ $booking->booking_ref }}</td>
                <td class="px-5 py-3 text-gray-700">{{ $booking->schedule->route->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-500">{{ $booking->schedule->schedule_date?->format('d M Y') ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-700">{{ $booking->seat->seat_number ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-700">LKR {{ number_format($booking->amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
</x-passenger-layout>