<x-dashboard-layout title="Admin Dashboard">
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <p class="text-sm text-gray-500">Total Employees</p>
        <p class="text-3xl font-semibold text-gray-800 mt-1">{{ $totalEmployees }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <p class="text-sm text-gray-500">Active Buses</p>
        <p class="text-3xl font-semibold text-gray-800 mt-1">{{ $activeBuses }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <p class="text-sm text-gray-500">Today's Schedules</p>
        <p class="text-3xl font-semibold text-gray-800 mt-1">{{ $todaySchedules }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($pendingRegistrations > 0)
        <a href="{{ route('registrations.index') }}">
        @endif
        <p class="text-sm text-gray-500">Pending Registrations</p>
        <p class="text-3xl font-semibold {{ $pendingRegistrations > 0 ? 'text-amber-600' : 'text-gray-800' }} mt-1">{{ $pendingRegistrations }}</p>
        @if($pendingRegistrations > 0)
        </a>
        @endif
    </div>
</div>
<div class="grid grid-cols-3 gap-4">
    <a href="{{ route('buses.index') }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 transition-colors">
        <p class="text-sm font-medium text-gray-700">Manage fleet</p>
        <p class="text-xs text-gray-400 mt-1">Add, edit, update bus status</p>
    </a>
    <a href="{{ route('routes.index') }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 transition-colors">
        <p class="text-sm font-medium text-gray-700">Manage routes</p>
        <p class="text-xs text-gray-400 mt-1">Add and edit bus routes</p>
    </a>
    <a href="{{ route('schedules.index') }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 transition-colors">
        <p class="text-sm font-medium text-gray-700">Manage schedules</p>
        <p class="text-xs text-gray-400 mt-1">Create and assign schedules</p>
    </a>
</div>
</x-dashboard-layout>