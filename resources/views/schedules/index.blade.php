<x-dashboard-layout title="Schedules">
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">Schedules</h2>
        <p class="text-sm text-gray-500">{{ $schedules->total() }} total schedules</p>
    </div>
    <a href="{{ route('schedules.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">+ Add Schedule</a>
</div>

<form method="GET" class="flex gap-3 mb-6 flex-wrap">
    <select name="route_id" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
        <option value="">All routes</option>
        @foreach($routes as $r)
            <option value="{{ $r->id }}" @selected(request('route_id') == $r->id)>{{ $r->name }}</option>
        @endforeach
    </select>
    <input type="date" name="date" value="{{ request('date') }}"
           class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
    <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
        <option value="">All statuses</option>
        <option value="active"   @selected(request('status')==='active')>Active</option>
        <option value="inactive" @selected(request('status')==='inactive')>Inactive</option>
        <option value="cancelled" @selected(request('status')==='cancelled')>Cancelled</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg">Filter</button>
    <a href="{{ route('schedules.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg">Reset</a>
</form>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Route</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Bus</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Date</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Departure</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Driver</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Fare</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Status</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($schedules as $schedule)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $schedule->route->name }}</td>
                <td class="px-4 py-3 font-mono text-blue-700 text-xs">{{ $schedule->bus->depot_reg_no }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $schedule->schedule_date->format('d M Y') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $schedule->driver?->name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">LKR {{ number_format($schedule->fare, 0) }}</td>
                <td class="px-4 py-3">
                    @php $sc=['active'=>'bg-green-50 text-green-700','inactive'=>'bg-gray-100 text-gray-500','cancelled'=>'bg-red-50 text-red-600']; @endphp
                    <span class="px-2 py-0.5 rounded text-xs {{ $sc[$schedule->status] ?? '' }}">{{ ucfirst($schedule->status) }}</span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('schedules.show', $schedule) }}" class="text-xs text-blue-600 hover:underline">View</a>
                        <a href="{{ route('schedules.edit', $schedule) }}" class="text-xs text-gray-600 hover:underline">Edit</a>
                        @if(auth()->user()->hasRole(['admin','executive_officer']))
                            @if($schedule->status === 'active')
                                <form method="POST" action="{{ route('schedules.deactivate', $schedule) }}" onsubmit="return confirm('Deactivate this schedule?')">
                                    @csrf
                                    <button class="text-xs text-amber-600 hover:underline">Deactivate</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('schedules.activate', $schedule) }}">
                                    @csrf
                                    <button class="text-xs text-green-600 hover:underline">Activate</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">No schedules found. <a href="{{ route('schedules.create') }}" class="text-blue-600 hover:underline">Create one</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $schedules->links() }}</div>
</x-dashboard-layout>