<x-dashboard-layout title="Edit Bus">
<div class="max-w-2xl">
    <a href="{{ route('buses.show', $bus) }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back to profile</a>
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-6">Edit bus — {{ $bus->depot_reg_no }}</h2>
        <form method="POST" action="{{ route('buses.update', $bus) }}" class="space-y-5">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle number</label>
                    <input type="text" name="vehicle_no" value="{{ old('vehicle_no', $bus->vehicle_no) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('vehicle_no') border-red-400 @enderror">
                    @error('vehicle_no')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Depot registration no.</label>
                    <input type="text" name="depot_reg_no" value="{{ old('depot_reg_no', $bus->depot_reg_no) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('depot_reg_no') border-red-400 @enderror">
                    @error('depot_reg_no')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                    <input type="text" name="brand" value="{{ old('brand', $bus->brand) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of seats</label>
                    <input type="number" name="seat_count" value="{{ old('seat_count', $bus->seat_count) }}"
                           min="1" max="60"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <p class="text-xs text-gray-400 mt-1">Increasing will auto-generate new seats. Decreasing does not remove seats.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Manufactured year</label>
                    <input type="number" name="manufactured_year" value="{{ old('manufactured_year', $bus->manufactured_year) }}"
                           min="1980" max="{{ date('Y') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('notes', $bus->notes) }}</textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('buses.show', $bus) }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Save changes</button>
            </div>
        </form>
    </div>
</div>
</x-dashboard-layout>