<x-dashboard-layout title="Driver Dashboard">
<div class="max-w-3xl">

    {{-- Welcome + today's assignment --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-semibold text-gray-800">{{ auth()->user()->name }}</h2>
                <p class="text-sm text-gray-400">{{ auth()->user()->employee_id }}
                    &middot; {{ ucfirst(auth()->user()->role) }}</p>
            </div>
            <a href="{{ route('breakdowns.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white
                      text-sm font-semibold rounded-lg hover:bg-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667
                             1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34
                             16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Report Breakdown
            </a>
        </div>

        @if($todaySchedule)
        {{-- Today has an assigned schedule --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
            <p class="text-xs text-blue-600 font-semibold uppercase tracking-wide mb-3">
                Today's Assignment
            </p>
            <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                <div>
                    <p class="text-xs text-gray-400">Route</p>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ $todaySchedule->route->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Bus</p>
                    <p class="font-mono font-bold text-blue-700 mt-0.5">{{ $todaySchedule->bus->depot_reg_no }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Departure</p>
                    <p class="font-semibold text-gray-800 mt-0.5">
                        {{ \Carbon\Carbon::parse($todaySchedule->departure_time)->format('h:i A') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Arrival</p>
                    <p class="font-semibold text-gray-800 mt-0.5">
                        {{ \Carbon\Carbon::parse($todaySchedule->arrival_time)->format('h:i A') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Passengers booked</p>
                    <p class="font-semibold text-gray-800 mt-0.5">
                        {{ $todaySchedule->bookings->where('payment_status','paid')->count() }}
                        / {{ $todaySchedule->bus->seat_count }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Fare</p>
                    <p class="font-semibold text-gray-800 mt-0.5">
                        LKR {{ number_format($todaySchedule->fare, 2) }}
                    </p>
                </div>
            </div>
        </div>
        @else
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 text-center">
            <p class="text-sm text-gray-400">No schedule assigned for today.</p>
            <p class="text-xs text-gray-400 mt-1">Contact your timekeeper if you believe this is an error.</p>
        </div>
        @endif
    </div>

    {{-- Upcoming schedules --}}
    @if($upcomingSchedules->count())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800">Upcoming assignments</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Date</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Route</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Bus</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Departure</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($upcomingSchedules as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-700">{{ $s->schedule_date->format('D, d M') }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $s->route->name }}</td>
                    <td class="px-5 py-3 font-mono text-blue-700 font-semibold text-xs">{{ $s->bus->depot_reg_no }}</td>
                    <td class="px-5 py-3 text-gray-600">
                        {{ \Carbon\Carbon::parse($s->departure_time)->format('h:i A') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
</x-dashboard-layout>