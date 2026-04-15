<x-dashboard-layout title="Add Bus">
<div class="max-w-2xl">
    <a href="{{ route('buses.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back to fleet</a>
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-1">Register new bus</h2>
        <p class="text-xs text-gray-400 mb-6">Seats will be auto-generated after adding the bus.</p>
        <form method="POST" action="{{ route('buses.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle number <span class="text-red-400">*</span></label>
                    <input type="text" name="vehicle_no" value="{{ old('vehicle_no') }}"
                           placeholder="e.g. NB-1978"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('vehicle_no') border-red-400 @enderror">
                    @error('vehicle_no')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Depot registration no. <span class="text-red-400">*</span></label>
                    <input type="text" name="depot_reg_no" value="{{ old('depot_reg_no') }}"
                           placeholder="e.g. YT042"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('depot_reg_no') border-red-400 @enderror">
                    <p class="text-xs text-gray-400 mt-1">Format: YT followed by 2–3 digits (YT001 to YT999)</p>
                    @error('depot_reg_no')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand / Manufacturer <span class="text-red-400">*</span></label>
                    <input type="text" name="brand" value="{{ old('brand') }}"
                           placeholder="e.g. TATA, Ashok Leyland"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('brand') border-red-400 @enderror">
                    @error('brand')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of seats <span class="text-red-400">*</span></label>
                    <input type="number" name="seat_count" value="{{ old('seat_count') }}"
                           min="1" max="60" placeholder="e.g. 52"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('seat_count') border-red-400 @enderror">
                    @error('seat_count')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Manufactured year</label>
                    <input type="number" name="manufactured_year" value="{{ old('manufactured_year') }}"
                           min="1980" max="{{ date('Y') }}" placeholder="e.g. 2015"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2"
                          placeholder="Any additional details about this bus..."
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('notes') }}</textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('buses.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Add Bus</button>
            </div>
        </form>
    </div>
</div>
</x-dashboard-layout>