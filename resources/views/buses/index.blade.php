<x-dashboard-layout title="Buses">
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">Fleet Management</h2>
        <p class="text-sm text-gray-500">{{ $buses->total() }} buses registered</p>
    </div>
    <a href="{{ route('buses.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
        + Add Bus
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="flex gap-3 mb-6 flex-wrap">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search vehicle no, depot ID, brand..."
           class="flex-1 min-w-48 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
        <option value="">All statuses</option>
        @foreach(['active'=>'Active','needs_repair'=>'Needs Repair','under_repair'=>'Under Repair','maintenance'=>'Maintenance','condemned'=>'Condemned'] as $val => $label)
            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg">Filter</button>
    <a href="{{ route('buses.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">Reset</a>
</form>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Depot ID</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Vehicle No.</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Brand</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Seats</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Year</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Status</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($buses as $bus)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono font-semibold text-blue-700">{{ $bus->depot_reg_no }}</td>
                <td class="px-4 py-3 text-gray-700">{{ $bus->vehicle_no }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $bus->brand }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $bus->seat_count }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $bus->manufactured_year ?? '—' }}</td>
                <td class="px-4 py-3">
                    @php
                        $colors = [
                            'active'       => 'bg-green-50 text-green-700',
                            'needs_repair' => 'bg-amber-50 text-amber-700',
                            'under_repair' => 'bg-orange-50 text-orange-700',
                            'maintenance'  => 'bg-blue-50 text-blue-700',
                            'condemned'    => 'bg-red-50 text-red-600',
                        ];
                    @endphp
                    <span class="px-2 py-0.5 rounded text-xs {{ $colors[$bus->status] ?? 'bg-gray-50 text-gray-600' }}">
                        {{ $bus->status_label }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-3">
                        <a href="{{ route('buses.show', $bus) }}" class="text-xs text-blue-600 hover:underline">View</a>
                        @if(!$bus->isCondemned())
                        <a href="{{ route('buses.edit', $bus) }}" class="text-xs text-gray-600 hover:underline">Edit</a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">
                    No buses found.
                    <a href="{{ route('buses.create') }}" class="text-blue-600 hover:underline ml-1">Add the first bus</a>.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $buses->links() }}</div>
</x-dashboard-layout>