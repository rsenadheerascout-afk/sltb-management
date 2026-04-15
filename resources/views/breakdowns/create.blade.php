<x-dashboard-layout title="Report Breakdown">
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-1">Report a breakdown</h2>
        <p class="text-sm text-gray-400 mb-6">
            Fill in the details below. The depot manager will be notified immediately.
        </p>

        <form method="POST" action="{{ route('breakdowns.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Bus selection --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Bus <span class="text-red-400">*</span>
                </label>
                @if($assignedBus)
                <div class="flex items-center gap-3 px-3 py-2.5 bg-blue-50 border border-blue-200 rounded-lg">
                    <span class="font-mono font-semibold text-blue-700">{{ $assignedBus->depot_reg_no }}</span>
                    <span class="text-sm text-gray-600">{{ $assignedBus->vehicle_no }} — {{ $assignedBus->brand }}</span>
                </div>
                <input type="hidden" name="bus_id" value="{{ $assignedBus->id }}">
                @else
                <select name="bus_id" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('bus_id') border-red-400 @enderror">
                    <option value="">Select bus...</option>
                    @foreach($buses as $bus)
                        <option value="{{ $bus->id }}" @selected(old('bus_id') == $bus->id)>
                            {{ $bus->depot_reg_no }} — {{ $bus->vehicle_no }}
                        </option>
                    @endforeach
                </select>
                @error('bus_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                @endif
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description <span class="text-red-400">*</span>
                </label>
                <textarea name="description" rows="3"
                          placeholder="Describe the breakdown in detail (e.g. engine failure, flat tyre, etc.)..."
                          class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                 focus:ring-2 focus:ring-blue-500 focus:outline-none
                                 @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Photo upload --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Photo (optional)</label>
                <input type="file" name="photo" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0 file:text-sm file:font-medium
                              file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-400 mt-1">JPG, PNG or WebP. Max 4 MB.</p>
                @error('photo')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Location picker --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Location <span class="text-gray-400 font-normal">(click map to pin your location)</span>
                </label>

                {{-- Leaflet map --}}
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
                <div id="breakdown-map" style="height:280px; border-radius:10px; border:1px solid #e5e7eb; overflow:hidden;"></div>

                <input type="hidden" name="latitude"  id="lat-input">
                <input type="hidden" name="longitude" id="lng-input">

                <div id="location-display" class="mt-2 text-xs text-gray-400 hidden">
                    Pinned location:
                    <span id="lat-display" class="font-mono"></span>,
                    <span id="lng-display" class="font-mono"></span>
                </div>

                <p id="location-hint" class="mt-2 text-xs text-amber-600">
                    Click on the map to pin your current location. Or use the button below to auto-detect.
                </p>

                <button type="button" onclick="detectLocation()"
                        class="mt-2 px-3 py-1.5 text-xs border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">
                    Detect my location automatically
                </button>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('driver.dashboard') }}"
                   class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700">
                    Submit Breakdown Report
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Default center: Kandy, Sri Lanka
const map = L.map('breakdown-map').setView([7.2906, 80.6337], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

let marker = null;

function pinLocation(lat, lng) {
    if (marker) marker.remove();
    marker = L.marker([lat, lng]).addTo(map);
    map.setView([lat, lng], 15);

    document.getElementById('lat-input').value    = lat;
    document.getElementById('lng-input').value    = lng;
    document.getElementById('lat-display').textContent = lat.toFixed(5);
    document.getElementById('lng-display').textContent = lng.toFixed(5);
    document.getElementById('location-display').classList.remove('hidden');
    document.getElementById('location-hint').classList.add('hidden');
}

map.on('click', function(e) {
    pinLocation(e.latlng.lat, e.latlng.lng);
});

function detectLocation() {
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by your browser.');
        return;
    }
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            pinLocation(pos.coords.latitude, pos.coords.longitude);
        },
        function() {
            alert('Could not detect location. Please click the map to pin manually.');
        }
    );
}
</script>
</x-dashboard-layout>