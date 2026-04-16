<x-dashboard-layout title="Assign Duty">
<div class="max-w-2xl">
    <a href="{{ route('rosters.index') }}"
       class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back to rosters</a>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-1">Assign a duty roster</h2>
        <p class="text-xs text-gray-400 mb-6">
            Link a driver or conductor to a specific schedule. The employee will be notified.
        </p>

        <form method="POST" action="{{ route('rosters.store') }}" class="space-y-5">
            @csrf

            {{-- Schedule picker --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Schedule <span class="text-red-400">*</span>
                </label>
                <select name="schedule_id"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                               focus:ring-2 focus:ring-blue-500 focus:outline-none
                               @error('schedule_id') border-red-400 @enderror">
                    <option value="">Select a schedule...</option>
                    @foreach($schedules as $schedule)
                        <option value="{{ $schedule->id }}" @selected(old('schedule_id') == $schedule->id)>
                            {{ $schedule->schedule_date->format('d M Y') }}
                            &middot;
                            {{ $schedule->route->name }}
                            &middot;
                            Bus {{ $schedule->bus->depot_reg_no }}
                            &middot;
                            {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}
                        </option>
                    @endforeach
                </select>
                @error('schedule_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Employee picker (driver OR conductor) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Assign employee <span class="text-red-400">*</span>
                </label>
                <select name="user_id"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                               focus:ring-2 focus:ring-blue-500 focus:outline-none
                               @error('user_id') border-red-400 @enderror">
                    <option value="">Select driver or conductor...</option>
                    @if($drivers->count())
                    <optgroup label="Drivers">
                        @foreach($drivers as $d)
                            <option value="{{ $d->id }}" @selected(old('user_id') == $d->id)>
                                {{ $d->name }} ({{ $d->employee_id }})
                            </option>
                        @endforeach
                    </optgroup>
                    @endif
                    @if($conductors->count())
                    <optgroup label="Conductors">
                        @foreach($conductors as $c)
                            <option value="{{ $c->id }}" @selected(old('user_id') == $c->id)>
                                {{ $c->name }} ({{ $c->employee_id }})
                            </option>
                        @endforeach
                    </optgroup>
                    @endif
                </select>
                @error('user_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Duty date --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Duty date <span class="text-red-400">*</span>
                </label>
                <input type="date" name="duty_date"
                       value="{{ old('duty_date', today()->format('Y-m-d')) }}"
                       min="{{ today()->format('Y-m-d') }}"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                              focus:ring-2 focus:ring-blue-500 focus:outline-none
                              @error('duty_date') border-red-400 @enderror">
                @error('duty_date')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                               focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="assigned"  @selected(old('status','assigned') === 'assigned')>Assigned</option>
                    <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                    <option value="absent"    @selected(old('status') === 'absent')>Absent</option>
                </select>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-3 text-xs text-blue-700">
                The assigned employee will receive an in-system notification about this duty.
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('rosters.index') }}"
                   class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700">
                    Assign Duty
                </button>
            </div>
        </form>
    </div>
</div>
</x-dashboard-layout>