<x-dashboard-layout title="Admin Dashboard">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Total Employees</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalEmployees }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Active Buses</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $activeBuses }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Today's Schedules</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $todaySchedules }}</p>
        </div>
        <a href="{{ route('registrations.index') }}"
            class="bg-white rounded-xl border {{ $pendingRegistrations > 0 ? 'border-amber-300 bg-amber-50' : 'border-gray-200' }} p-5 block hover:shadow-sm transition">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Pending Registrations</p>
            <p class="text-3xl font-bold {{ $pendingRegistrations > 0 ? 'text-amber-600' : 'text-gray-800' }} mt-2">
                {{ $pendingRegistrations }}</p>
        </a>
    </div>
    <div class="grid grid-cols-3 gap-4">
        @if(\Illuminate\Support\Facades\Route::has('employees.index'))
            <a href="{{ route('employees.index') }}"
                class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 transition-colors">
                <p class="font-medium text-gray-800 text-sm">Manage employees</p>
                <p class="text-xs text-gray-400 mt-1">Add, edit, view all staff</p>
            </a>
        @endif
        @if(\Illuminate\Support\Facades\Route::has('registrations.index'))
            <a href="{{ route('registrations.index') }}"
                class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 transition-colors">
                <p class="font-medium text-gray-800 text-sm">Review registrations</p>
                <p class="text-xs text-gray-400 mt-1">Approve or reject applications</p>
            </a>
        @endif
        <a href="{{ route('profile.edit') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 transition-colors">
            <p class="font-medium text-gray-800 text-sm">My profile</p>
            <p class="text-xs text-gray-400 mt-1">Update your account details</p>
        </a>
    </div>
</x-dashboard-layout>