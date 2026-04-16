<x-dashboard-layout title="Duty Rosters">
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">Duty Rosters</h2>
        <p class="text-sm text-gray-500">{{ $rosters->total() }} assignments</p>
    </div>
    <a href="{{ route('rosters.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
        + Assign Duty
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="flex gap-3 mb-6 flex-wrap">
    <input type="date" name="date" value="{{ request('date') }}"
           class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
    <select name="user_id" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
        <option value="">All drivers &amp; conductors</option>
        @foreach($drivers as $d)
            <option value="{{ $d->id }}" @selected(request('user_id') == $d->id)>
                {{ $d->name }} (Driver)
            </option>
        @endforeach
        @foreach($conductors as $c)
            <option value="{{ $c->id }}" @selected(request('user_id') == $c->id)>
                {{ $c->name }} (Conductor)
            </option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg">Filter</button>
    <a href="{{ route('rosters.index') }}"
       class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">
        Reset
    </a>
</form>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Employee</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Role</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Route</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Bus</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Duty date</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Departure</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Status</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($rosters as $roster)
            <tr class="{{ $roster->duty_date->isToday() ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800">{{ $roster->user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $roster->user->employee_id }}</p>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs">
                        {{ ucfirst($roster->user->role) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-700">{{ $roster->schedule->route->name }}</td>
                <td class="px-4 py-3 font-mono font-semibold text-blue-700 text-xs">
                    {{ $roster->schedule->bus->depot_reg_no }}
                </td>
                <td class="px-4 py-3">
                    <p class="text-gray-700">{{ $roster->duty_date->format('d M Y') }}</p>
                    @if($roster->duty_date->isToday())
                    <span class="text-xs font-semibold text-blue-600">Today</span>
                    @elseif($roster->duty_date->isTomorrow())
                    <span class="text-xs text-amber-600">Tomorrow</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-600">
                    {{ \Carbon\Carbon::parse($roster->schedule->departure_time)->format('h:i A') }}
                </td>
                <td class="px-4 py-3">
                    @php
                        $sc = [
                            'assigned'  => 'bg-blue-50 text-blue-700',
                            'completed' => 'bg-green-50 text-green-700',
                            'absent'    => 'bg-red-50 text-red-600',
                        ];
                    @endphp
                    <span class="px-2 py-0.5 rounded text-xs {{ $sc[$roster->status] ?? 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst($roster->status) }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <form method="POST" action="{{ route('rosters.destroy', $roster) }}"
                          onsubmit="return confirm('Remove this duty assignment?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:underline">Remove</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">
                    No duty assignments found.
                    <a href="{{ route('rosters.create') }}" class="text-blue-600 hover:underline ml-1">
                        Assign one now
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $rosters->links() }}</div>
</x-dashboard-layout>