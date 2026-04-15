<x-dashboard-layout title="Driver Dashboard">
<div class="max-w-3xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-5">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-800">{{ auth()->user()->name }}</h2>
                <p class="text-sm text-gray-400 mt-0.5">{{ auth()->user()->employee_id }}</p>
            </div>
            <a href="{{ route('breakdowns.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667
                             1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34
                             16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Report Breakdown
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Today's assignment</p>
            <p class="text-sm text-gray-500">Schedule details available after Branch 3 is deployed.</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Quick actions</p>
            <a href="{{ route('breakdowns.create') }}" class="text-sm text-red-600 hover:underline block">
                Report a breakdown
            </a>
            <a href="{{ route('profile.edit') }}" class="text-sm text-blue-600 hover:underline block mt-1">
                Edit my profile
            </a>
        </div>
    </div>
</div>
</x-dashboard-layout>