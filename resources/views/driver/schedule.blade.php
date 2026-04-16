<x-dashboard-layout title="My Schedule">
<div class="max-w-3xl">

    {{-- Upcoming schedules --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-800">Upcoming schedules</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $schedules->total() }} upcoming</p>
        </div>

        @if($schedules->isEmpty())
        <div class="px-5 py-10 text-center">
            <p class="text-sm text-gray-400">No upcoming schedules assigned to you.</p>
        </div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Date</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Route</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Bus</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Departure</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Arrival</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Fare</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($schedules as $s)
                <tr class="{{ $s->schedule_date->isToday() ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-800">{{ $s->schedule_date->format('d M Y') }}</p>
                        @if($s->schedule_date->isToday())
                        <span class="text-xs font-semibold text-blue-600">Today</span>
                        @elseif($s->schedule_date->isTomorrow())
                        <span class="text-xs text-amber-600">Tomorrow</span>
                        @else
                        <span class="text-xs text-gray-400">{{ $s->schedule_date->diffForHumans() }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-700">{{ $s->route->name }}</td>
                    <td class="px-5 py-3 font-mono font-semibold text-blue-700 text-xs">
                        {{ $s->bus->depot_reg_no }}
                    </td>
                    <td class="px-5 py-3 text-gray-700 font-medium">
                        {{ \Carbon\Carbon::parse($s->departure_time)->format('h:i A') }}
                    </td>
                    <td class="px-5 py-3 text-gray-500">
                        {{ \Carbon\Carbon::parse($s->arrival_time)->format('h:i A') }}
                    </td>
                    <td class="px-5 py-3 text-gray-600">LKR {{ number_format($s->fare, 0) }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded text-xs bg-green-50 text-green-700">Active</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-3">{{ $schedules->links() }}</div>
        @endif
    </div>

    {{-- Past schedules --}}
    @if($pastSchedules->count())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-800">Past assignments</h2>
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
                @foreach($pastSchedules as $s)
                <tr class="hover:bg-gray-50 opacity-75">
                    <td class="px-5 py-3 text-gray-500">{{ $s->schedule_date->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $s->route->name }}</td>
                    <td class="px-5 py-3 font-mono text-gray-500 text-xs">{{ $s->bus->depot_reg_no }}</td>
                    <td class="px-5 py-3 text-gray-500">
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