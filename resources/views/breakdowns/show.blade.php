<x-dashboard-layout title="Breakdown Report">
<div class="max-w-3xl">
    <a href="{{ route('breakdowns.index') }}"
       class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back to reports</a>

    {{-- Report header --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
        <div class="flex items-start justify-between mb-5">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="font-mono text-xl font-bold text-blue-700">{{ $breakdown->bus->depot_reg_no }}</span>
                    <span class="px-3 py-0.5 rounded-full text-xs {{ $breakdown->status_color }}">
                        {{ ucfirst(str_replace('_',' ',$breakdown->status)) }}
                    </span>
                </div>
                <p class="text-sm text-gray-500">
                    Reported by <strong>{{ $breakdown->reportedBy->name }}</strong>
                    &middot; {{ $breakdown->created_at->format('d M Y, h:i A') }}
                </p>
            </div>

            {{-- Resolve button --}}
            @if(auth()->user()->hasRole(['admin','executive_officer']) && $breakdown->status === 'in_progress')
            <form method="POST" action="{{ route('breakdowns.resolve', $breakdown) }}"
                  onsubmit="return confirm('Mark this breakdown as resolved?')">
                @csrf
                <button class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                    Mark Resolved
                </button>
            </form>
            @endif
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Description</p>
            <p class="text-sm text-gray-800 leading-relaxed">{{ $breakdown->description }}</p>
        </div>

        {{-- Photo --}}
        @if($breakdown->photo_url)
        <div class="mb-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Photo</p>
            <img src="{{ $breakdown->photo_url }}" alt="Breakdown photo"
                 class="rounded-lg max-h-64 object-cover border border-gray-200">
        </div>
        @endif

        {{-- Location map --}}
        @if($breakdown->latitude && $breakdown->longitude)
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Location</p>
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
            <div id="view-map" style="height:200px; border-radius:10px; border:1px solid #e5e7eb; overflow:hidden;"></div>
            <p class="text-xs text-gray-400 mt-1 font-mono">
                {{ $breakdown->latitude }}, {{ $breakdown->longitude }}
            </p>
        </div>
        @endif

        {{-- Response details if actioned --}}
        @if($breakdown->response_action)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Response action</p>
            @php
                $actionLabels = [
                    'spare_parts'     => 'Spare parts dispatched',
                    'carrier'         => 'Recovery carrier sent',
                    'replacement_bus' => 'Replacement bus assigned',
                    'notify_only'     => 'Informational only',
                ];
            @endphp
            <p class="text-sm font-medium text-gray-800">{{ $actionLabels[$breakdown->response_action] ?? $breakdown->response_action }}</p>
            @if($breakdown->response_notes)
            <p class="text-sm text-gray-500 mt-1">{{ $breakdown->response_notes }}</p>
            @endif
            @if($breakdown->replacementBus)
            <p class="text-sm text-gray-600 mt-1">
                Replacement bus: <span class="font-mono font-semibold text-blue-700">{{ $breakdown->replacementBus->depot_reg_no }}</span>
            </p>
            @endif
        </div>
        @endif

        {{-- Reject reason --}}
        @if($breakdown->reject_reason)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Rejection reason</p>
            <p class="text-sm text-red-600">{{ $breakdown->reject_reason }}</p>
        </div>
        @endif
    </div>

    {{-- ── OFFICER ACTIONS (only for admin/officer on pending reports) ─── --}}
    @if(auth()->user()->hasRole(['admin','executive_officer']) && $breakdown->isPending())
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Review this report</h3>
        <div class="grid grid-cols-2 gap-4">

            {{-- Approve --}}
            <form method="POST" action="{{ route('breakdowns.approve', $breakdown) }}">
                @csrf
                <button type="submit"
                        class="w-full py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                    Approve Report
                </button>
            </form>

            {{-- Reject --}}
            <form method="POST" action="{{ route('breakdowns.reject', $breakdown) }}" x-data="{ open: false }">
                @csrf
                <div x-show="!open">
                    <button type="button" @click="open = true"
                            class="w-full py-2.5 bg-red-50 text-red-600 border border-red-200 text-sm font-medium rounded-lg hover:bg-red-100">
                        Reject Report
                    </button>
                </div>
                <div x-show="open" class="space-y-2">
                    <textarea name="reject_reason" rows="2" placeholder="Reason for rejection (required)"
                              class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-400 focus:outline-none @error('reject_reason') border-red-400 @enderror"></textarea>
                    @error('reject_reason')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
                    <button type="submit"
                            class="w-full py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- ── RESPONSE ACTION FORM (after approval, before resolution) ─── --}}
    @if(auth()->user()->hasRole(['admin','executive_officer']) && $breakdown->status === 'approved')
    <div class="bg-white rounded-xl border border-gray-200 p-6" x-data="{ action: '' }">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Choose response action</h3>

        <form method="POST" action="{{ route('breakdowns.respond', $breakdown) }}" class="space-y-4">
            @csrf

            {{-- Action selector --}}
            <div class="grid grid-cols-2 gap-3">
                @php
                    $actions = [
                        'spare_parts'     => ['label' => 'Release spare parts', 'desc' => 'Notify storekeeper to issue parts', 'color' => 'teal'],
                        'carrier'         => ['label' => 'Send recovery carrier', 'desc' => 'Dispatch recovery vehicle', 'color' => 'blue'],
                        'replacement_bus' => ['label' => 'Assign replacement bus', 'desc' => 'Send another bus on this route', 'color' => 'purple'],
                        'notify_only'     => ['label' => 'Notify only', 'desc' => 'Informational — no physical action', 'color' => 'gray'],
                    ];
                @endphp
                @foreach($actions as $value => $action)
                <label class="cursor-pointer">
                    <input type="radio" name="response_action" value="{{ $value }}"
                           x-model="action" class="sr-only" @selected(old('response_action')===$value)>
                    <div :class="action === '{{ $value }}'
                            ? 'border-blue-500 bg-blue-50'
                            : 'border-gray-200 hover:border-gray-300'"
                         class="border-2 rounded-xl p-4 transition-colors">
                        <p class="text-sm font-semibold text-gray-800">{{ $action['label'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $action['desc'] }}</p>
                    </div>
                </label>
                @endforeach
            </div>
            @error('response_action')<p class="text-xs text-red-500">{{ $message }}</p>@enderror

            {{-- Replacement bus select (only shown when replacement_bus is selected) --}}
            <div x-show="action === 'replacement_bus'" x-transition>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Select replacement bus <span class="text-red-400">*</span>
                </label>
                <select name="replacement_bus_id"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Choose an available bus...</option>
                    @foreach($availableBuses as $bus)
                        <option value="{{ $bus->id }}">{{ $bus->depot_reg_no }} — {{ $bus->vehicle_no }} ({{ $bus->seat_count }} seats)</option>
                    @endforeach
                </select>
                @error('replacement_bus_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Response notes (optional)</label>
                <textarea name="response_notes" rows="2"
                          placeholder="Any additional information about the action taken..."
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('response_notes') }}</textarea>
            </div>

            <button type="submit"
                    class="w-full py-3 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700"
                    x-bind:disabled="action === ''">
                Confirm Response Action
            </button>
        </form>
    </div>
    @endif
</div>

@if($breakdown->latitude && $breakdown->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('view-map').setView([{{ $breakdown->latitude }}, {{ $breakdown->longitude }}], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    L.marker([{{ $breakdown->latitude }}, {{ $breakdown->longitude }}]).addTo(map);
});
</script>
@endif
</x-dashboard-layout>