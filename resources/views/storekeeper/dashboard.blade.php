<x-dashboard-layout title="Storekeeper Dashboard">
<div class="grid grid-cols-2 gap-4 mb-6">
    <a href="{{ route('storekeeper.breakdowns') }}"
       class="bg-white rounded-xl border {{ $sparesNeeded > 0 ? 'border-amber-300 bg-amber-50' : 'border-gray-200' }}
              p-5 block hover:shadow-sm transition">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Spare Parts Jobs</p>
        <p class="text-3xl font-bold {{ $sparesNeeded > 0 ? 'text-amber-600' : 'text-gray-800' }} mt-2">
            {{ $sparesNeeded }}
        </p>
        @if($sparesNeeded > 0)
        <p class="text-xs text-amber-600 mt-1">Requires attention</p>
        @endif
    </a>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Inventory</p>
        <p class="text-sm text-gray-400 mt-3">Available in Branch 7.</p>
    </div>
</div>
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-sm font-semibold text-gray-800 mb-2">Quick actions</h3>
    <a href="{{ route('storekeeper.breakdowns') }}"
       class="text-sm text-blue-600 hover:underline block">View spare parts jobs</a>
    <a href="{{ route('profile.edit') }}"
       class="text-sm text-blue-600 hover:underline block mt-1">Edit my profile</a>
</div>
</x-dashboard-layout>