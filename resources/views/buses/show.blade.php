<x-dashboard-layout title="Bus Profile">
<div class="max-w-3xl">
    <a href="{{ route('buses.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back to fleet</a>

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
        <div class="flex items-start justify-between mb-6">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-bold text-blue-700 font-mono">{{ $bus->depot_reg_no }}</h2>
                    @php
                        $colors = ['active'=>'bg-green-50 text-green-700','needs_repair'=>'bg-amber-50 text-amber-700','under_repair'=>'bg-orange-50 text-orange-700','maintenance'=>'bg-blue-50 text-blue-700','condemned'=>'bg-red-50 text-red-600'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $colors[$bus->status] ?? '' }}">{{ $bus->status_label }}</span>
                </div>
                <p class="text-gray-500 text-sm mt-1">{{ $bus->vehicle_no }}</p>
            </div>
            @if(!$bus->isCondemned())
            <a href="{{ route('buses.edit', $bus) }}" class="px-3 py-1.5 border border-gray-200 text-sm rounded-lg hover:bg-gray-50">Edit</a>
            @endif
        </div>

        <dl class="grid grid-cols-3 gap-x-6 gap-y-4 text-sm">
            <div><dt class="text-gray-400">Brand</dt><dd class="text-gray-800 font-medium mt-0.5">{{ $bus->brand }}</dd></div>
            <div><dt class="text-gray-400">Total seats</dt><dd class="text-gray-800 font-medium mt-0.5">{{ $bus->seat_count }}</dd></div>
            <div><dt class="text-gray-400">Manufactured</dt><dd class="text-gray-800 font-medium mt-0.5">{{ $bus->manufactured_year ?? '—' }}</dd></div>
        </dl>

        @if($bus->notes)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Notes</p>
            <p class="text-sm text-gray-600">{{ $bus->notes }}</p>
        </div>
        @endif
    </div>

    {{-- Status change panel --}}
    @if(!$bus->isCondemned())
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Change bus status</h3>
        <form method="POST" action="{{ route('buses.status', $bus) }}" class="flex gap-3 flex-wrap">
            @csrf
            <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm flex-1">
                @foreach(['active'=>'Active','needs_repair'=>'Needs Repair','under_repair'=>'Under Repair','maintenance'=>'Maintenance','condemned'=>'Condemned (irreversible)'] as $val => $label)
                    <option value="{{ $val }}" @selected($bus->status === $val)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit"
                    onclick="return confirm('Change status? Note: Condemned is permanent.')"
                    class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700">
                Update Status
            </button>
        </form>
    </div>
    @endif

    {{-- Recent schedules --}}
    @if($recentSchedules->count())
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Recent schedules</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-gray-500 text-xs font-medium">Route</th>
                <th class="px-3 py-2 text-left text-gray-500 text-xs font-medium">Date</th>
                <th class="px-3 py-2 text-left text-gray-500 text-xs font-medium">Departure</th>
                <th class="px-3 py-2 text-left text-gray-500 text-xs font-medium">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($recentSchedules as $s)
                <tr>
                    <td class="px-3 py-2 text-gray-700">{{ $s->route->name }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $s->schedule_date->format('d M Y') }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $s->departure_time }}</td>
                    <td class="px-3 py-2">
                        <span class="px-2 py-0.5 rounded text-xs {{ $s->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($s->status) }}
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