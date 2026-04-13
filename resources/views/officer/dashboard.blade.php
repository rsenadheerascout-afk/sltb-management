<x-dashboard-layout title="Executive Officer Dashboard">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('registrations.index') }}"
            class="bg-white rounded-xl border {{ $pendingRegistrations > 0 ? 'border-amber-300 bg-amber-50' : 'border-gray-200' }} p-5 block">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Pending Registrations</p>
            <p class="text-3xl font-bold {{ $pendingRegistrations > 0 ? 'text-amber-600' : 'text-gray-800' }} mt-2">
                {{ $pendingRegistrations }}</p>
        </a>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Open Breakdowns</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">—</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Today's Schedules</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">—</p>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <a href="{{ route('employees.index') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 transition-colors">
            <p class="font-medium text-gray-800 text-sm">Manage employees</p>
            <p class="text-xs text-gray-400 mt-1">View and edit staff records</p>
        </a>
        <a href="{{ route('registrations.index') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 transition-colors">
            <p class="font-medium text-gray-800 text-sm">Review applications</p>
            <p class="text-xs text-gray-400 mt-1">Approve or reject registrations</p>
        </a>
    </div>
</x-dashboard-layout>