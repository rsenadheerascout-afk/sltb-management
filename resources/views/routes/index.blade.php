<x-dashboard-layout title="Routes">
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">Routes</h2>
        <p class="text-sm text-gray-500">{{ $routes->total() }} routes registered</p>
    </div>
    <a href="{{ route('routes.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">+ Add Route</a>
</div>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Route Name</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Origin</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Destination</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Distance</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Schedules</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Status</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($routes as $route)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $route->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $route->origin }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $route->destination }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $route->distance_km ? $route->distance_km . ' km' : '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $route->schedules_count }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded text-xs {{ $route->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst($route->status) }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-3">
                        <a href="{{ route('routes.show', $route) }}" class="text-xs text-blue-600 hover:underline">View</a>
                        <a href="{{ route('routes.edit', $route) }}" class="text-xs text-gray-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('routes.toggle-status', $route) }}">
                            @csrf
                            <button class="text-xs {{ $route->status === 'active' ? 'text-amber-600' : 'text-green-600' }} hover:underline">
                                {{ $route->status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">No routes yet. <a href="{{ route('routes.create') }}" class="text-blue-600 hover:underline">Add one</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $routes->links() }}</div>
</x-dashboard-layout>