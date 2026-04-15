<x-dashboard-layout title="Edit Route">
<div class="max-w-lg">
    <a href="{{ route('routes.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back to routes</a>
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-6">Edit route</h2>
        <form method="POST" action="{{ route('routes.update', $route) }}" class="space-y-5">
            @csrf @method('PATCH')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Route name</label>
                <input type="text" name="name" value="{{ old('name', $route->name) }}"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Origin</label>
                    <input type="text" name="origin" value="{{ old('origin', $route->origin) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                    <input type="text" name="destination" value="{{ old('destination', $route->destination) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Distance (km)</label>
                <input type="number" name="distance_km" value="{{ old('distance_km', $route->distance_km) }}"
                       step="0.1" min="0.1"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('routes.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Save changes</button>
            </div>
        </form>
    </div>
</div>
</x-dashboard-layout>