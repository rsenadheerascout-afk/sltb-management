<x-dashboard-layout title="Create Schedule">
<div class="max-w-2xl">
    <a href="{{ route('schedules.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back to schedules</a>
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-6">Create new schedule</h2>
        <form method="POST" action="{{ route('schedules.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Route <span class="text-red-400">*</span></label>
                    <select name="route_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('route_id') border-red-400 @enderror">
                        <option value="">Select route...</option>
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}" @selected(old('route_id') == $route->id)>{{ $route->name }}</option>
                        @endforeach
                    </select>
                    @error('route_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bus <span class="text-red-400">*</span></label>
                    <select name="bus_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('bus_id') border-red-400 @enderror">
                        <option value="">Select bus...</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" @selected(old('bus_id') == $bus->id)>{{ $bus->depot_reg_no }} — {{ $bus->vehicle_no }} ({{ $bus->seat_count }} seats)</option>
                        @endforeach
                    </select>
                    @error('bus_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Driver</label>
                    <select name="driver_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">Select driver...</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" @selected(old('driver_id') == $driver->id)>{{ $driver->name }} ({{ $driver->employee_id }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conductor</label>
                    <select name="conductor_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">Select conductor...</option>
                        @foreach($conductors as $c)
                            <option value="{{ $c->id }}" @selected(old('conductor_id') == $c->id)>{{ $c->name }} ({{ $c->employee_id }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Schedule date <span class="text-red-400">*</span></label>
                    <input type="date" name="schedule_date" value="{{ old('schedule_date') }}"
                           min="{{ today()->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('schedule_date') border-red-400 @enderror">
                    @error('schedule_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fare (LKR) <span class="text-red-400">*</span></label>
                    <input type="number" name="fare" value="{{ old('fare') }}"
                           min="1" step="0.01" placeholder="e.g. 350"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('fare') border-red-400 @enderror">
                    @error('fare')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departure time <span class="text-red-400">*</span></label>
                    <input type="time" name="departure_time" value="{{ old('departure_time') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('departure_time') border-red-400 @enderror">
                    @error('departure_time')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Arrival time <span class="text-red-400">*</span></label>
                    <input type="time" name="arrival_time" value="{{ old('arrival_time') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('arrival_time') border-red-400 @enderror">
                    @error('arrival_time')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('schedules.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Create Schedule</button>
            </div>
        </form>
    </div>
</div>
</x-dashboard-layout>