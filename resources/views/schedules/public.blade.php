<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bus Schedules — SLTB Yatinuwara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold text-gray-800">SLTB Yatinuwara Depot</h1>
                <p class="text-xs text-gray-400">Bus Schedules &amp; Seat Booking</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-800">Staff login</a>
                <a href="{{ route('employee.apply') }}" class="text-sm text-blue-600 hover:text-blue-800">Apply to join</a>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-8">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-1">Available Schedules</h2>
            <p class="text-sm text-gray-500">Select a schedule to book your seat.</p>
        </div>

        {{-- Filter form --}}
        <form method="GET" class="flex gap-3 mb-6 flex-wrap">
            <select name="route_id" class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white">
                <option value="">All routes</option>
                @foreach($routes as $route)
                    <option value="{{ $route->id }}" @selected(request('route_id') == $route->id)>
                        {{ $route->name }}
                    </option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ request('date') }}"
                   min="{{ today()->format('Y-m-d') }}"
                   class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Search</button>
            <a href="{{ route('schedules.public') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">Clear</a>
        </form>

        {{-- Schedule cards --}}
        @forelse($schedules as $schedule)
        <div class="bg-white rounded-xl border border-gray-200 p-5 mb-4 hover:border-blue-300 transition-colors">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="font-semibold text-gray-800 text-lg">{{ $schedule->route->name }}</h3>
                        <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-mono">{{ $schedule->bus->depot_reg_no }}</span>
                    </div>
                    <div class="flex gap-6 text-sm text-gray-600">
                        <span>
                            <span class="text-gray-400 text-xs">From</span>
                            <span class="font-medium ml-1">{{ $schedule->route->origin }}</span>
                        </span>
                        <span class="text-gray-300">→</span>
                        <span>
                            <span class="text-gray-400 text-xs">To</span>
                            <span class="font-medium ml-1">{{ $schedule->route->destination }}</span>
                        </span>
                    </div>
                    <div class="flex gap-6 text-sm mt-2">
                        <div>
                            <span class="text-gray-400 text-xs block">Departure</span>
                            <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-xs block">Date</span>
                            <span class="text-gray-700">{{ $schedule->schedule_date->format('D, d M Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-xs block">Available seats</span>
                            @php $available = $schedule->availableSeatsCount(); @endphp
                            <span class="{{ $available > 5 ? 'text-green-600' : ($available > 0 ? 'text-amber-600' : 'text-red-600') }} font-medium">
                                {{ $available }} / {{ $schedule->bus->seat_count }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-right ml-6">
                    <div class="text-2xl font-bold text-gray-800">LKR {{ number_format($schedule->fare, 0) }}</div>
                    <div class="text-xs text-gray-400 mb-3">per seat</div>
                    @if($available > 0)
                        {{-- This link will go to seat booking in Branch 4 --}}
                        <a href="#" class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 inline-block">
                            Book Seats
                        </a>
                    @else
                        <span class="px-5 py-2 bg-gray-100 text-gray-400 text-sm rounded-lg inline-block">Fully Booked</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <p class="text-gray-400 text-sm">No schedules available for your search.</p>
            <a href="{{ route('schedules.public') }}" class="text-blue-600 text-sm hover:underline mt-2 inline-block">View all schedules</a>
        </div>
        @endforelse

        <div class="mt-4">{{ $schedules->links() }}</div>
    </main>
</body>
</html>