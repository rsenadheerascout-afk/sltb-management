<x-dashboard-layout title="Edit Schedule">
<div class="max-w-2xl">
    <a href="{{ route('schedules.show', $schedule) }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back</a>

    @if($errors->any())
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-sm font-medium text-red-700 mb-1">Please fix the following before saving:</p>
        <ul class="text-xs text-red-600 list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-6">Edit schedule #{{ $schedule->id }}</h2>
        <form method="POST" action="{{ route('schedules.update', $schedule) }}" class="space-y-5">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                    <select name="route_id"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('route_id') border-red-400 @enderror">
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}" @selected(old('route_id', $schedule->route_id) == $route->id)>{{ $route->name }}</option>
                        @endforeach
                    </select>
                    @error('route_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bus</label>
                    <select name="bus_id"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('bus_id') border-red-400 @enderror">
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" @selected(old('bus_id', $schedule->bus_id) == $bus->id)>
                                {{ $bus->depot_reg_no }} — {{ $bus->vehicle_no }}{{ $bus->status !== 'active' ? ' (inactive)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('bus_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Driver</label>
                    <select name="driver_id"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('driver_id') border-red-400 @enderror">
                        <option value="">No driver assigned</option>
                        @foreach($drivers as $d)
                            <option value="{{ $d->id }}" @selected(old('driver_id', $schedule->driver_id) == $d->id)>
                                {{ $d->name }}{{ $d->status !== 'active' ? ' (inactive)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('driver_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conductor</label>
                    <select name="conductor_id"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('conductor_id') border-red-400 @enderror">
                        <option value="">No conductor assigned</option>
                        @foreach($conductors as $c)
                            <option value="{{ $c->id }}" @selected(old('conductor_id', $schedule->conductor_id) == $c->id)>
                                {{ $c->name }}{{ $c->status !== 'active' ? ' (inactive)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('conductor_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Schedule date</label>
                    <input type="date" name="schedule_date"
                           value="{{ old('schedule_date', $schedule->schedule_date->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('schedule_date') border-red-400 @enderror">
                    @error('schedule_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fare (LKR)</label>
                    <input type="number" name="fare" value="{{ old('fare', $schedule->fare) }}"
                           min="1" step="0.01"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('fare') border-red-400 @enderror">
                    @error('fare')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- These two fields are the actual fix — Carbon::parse() guarantees a clean
                     "H:i" value regardless of whether the DB returned seconds. --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departure time</label>
                    <input type="time" name="departure_time"
                           value="{{ old('departure_time', \Carbon\Carbon::parse($schedule->departure_time)->format('H:i')) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('departure_time') border-red-400 @enderror">
                    @error('departure_time')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Arrival time</label>
                    <input type="time" name="arrival_time"
                           value="{{ old('arrival_time', \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i')) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('arrival_time') border-red-400 @enderror">
                    @error('arrival_time')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
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