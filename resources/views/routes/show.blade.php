<x-dashboard-layout title="Route Details">
<div class="max-w-3xl">
    <a href="{{ route('routes.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back to routes</a>
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $route->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $route->origin }} → {{ $route->destination }}{{ $route->distance_km ? ' · ' . $route->distance_km . ' km' : '' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('routes.edit', $route) }}" class="px-3 py-1.5 border border-gray-200 text-sm rounded-lg">Edit</a>
                <form method="POST" action="{{ route('routes.toggle-status', $route) }}">
                    @csrf
                    <button class="px-3 py-1.5 text-sm rounded-lg {{ $route->status === 'active' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-green-50 text-green-700 border border-green-200' }}">
                        {{ $route->status === 'active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-700">Schedules on this route</h3>
            <a href="{{ route('schedules.create') }}" class="text-xs text-blue-600 hover:underline">+ Add schedule</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-2 text-left text-gray-500 text-xs font-medium">Bus</th>
                <th class="px-4 py-2 text-left text-gray-500 text-xs font-medium">Date</th>
                <th class="px-4 py-2 text-left text-gray-500 text-xs font-medium">Departure</th>
                <th class="px-4 py-2 text-left text-gray-500 text-xs font-medium">Driver</th>
                <th class="px-4 py-2 text-left text-gray-500 text-xs font-medium">Fare</th>
                <th class="px-4 py-2 text-left text-gray-500 text-xs font-medium">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($schedules as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 font-mono text-blue-700 text-xs">{{ $s->bus->depot_reg_no }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $s->schedule_date->format('d M Y') }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $s->departure_time }}</td>
                    <td class="px-4 py-2 text-gray-500">{{ $s->driver?->name ?? '—' }}</td>
                    <td class="px-4 py-2 text-gray-600">LKR {{ number_format($s->fare, 2) }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-0.5 rounded text-xs {{ $s->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ ucfirst($s->status) }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">No schedules for this route yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $schedules->links() }}</div>
    </div>
</div>
</x-dashboard-layout>