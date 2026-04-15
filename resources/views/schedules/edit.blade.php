<x-dashboard-layout title="Edit Schedule">
<div class="max-w-2xl">
    <a href="{{ route('schedules.show', $schedule) }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back</a>
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-6">Edit schedule #{{ $schedule->id }}</h2>
        <form method="POST" action="{{ route('schedules.update', $schedule) }}" class="space-y-5">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                    <select name="route_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}" @selected(old('route_id', $schedule->route_id) == $route->id)>{{ $route->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bus</label>
                    <select name="bus_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" @selected(old('bus_id', $schedule->bus_id) == $bus->id)>{{ $bus->depot_reg_no }} — {{ $bus->vehicle_no }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Driver</label>
                    <select name="driver_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        <option value="">No driver assigned</option>
                        @foreach($drivers as $d)
                            <option value="{{ $d->id }}" @selected(old('driver_id', $schedule->driver_id) == $d->id)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conductor</label>
                    <select name="conductor_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        <option value="">No conductor assigned</option>
                        @foreach($conductors as $c)
                            <option value="{{ $c->id }}" @selected(old('conductor_id', $schedule->conductor_id) == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Schedule date</label>
                    <input type="date" name="schedule_date" value="{{ old('schedule_date', $schedule->schedule_date->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fare (LKR)</label>
                    <input type="number" name="fare" value="{{ old('fare', $schedule->fare) }}"
                           min="1" step="0.01"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departure time</label>
                    <input type="time" name="departure_time" value="{{ old('departure_time', $schedule->departure_time) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Arrival time</label>
                    <input type="time" name="arrival_time" value="{{ old('arrival_time', $schedule->arrival_time) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('schedules.show', $schedule) }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Save changes</button>
            </div>
        </form>
    </div>
</div>
</x-dashboard-layout>