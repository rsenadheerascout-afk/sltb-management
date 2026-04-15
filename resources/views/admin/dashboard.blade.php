<x-dashboard-layout title="Admin Dashboard">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-5 col-span-1">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Employees</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalEmployees }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 col-span-1">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Active Buses</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $activeBuses }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 col-span-1">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Today's Schedules</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $todaySchedules }}</p>
        </div>
        <a href="{{ route('registrations.index') }}"
            class="bg-white rounded-xl border {{ $pendingRegistrations > 0 ? 'border-amber-300 bg-amber-50' : 'border-gray-200' }} p-5 col-span-1 block hover:shadow-sm transition">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Pending Applications</p>
            <p class="text-3xl font-bold {{ $pendingRegistrations > 0 ? 'text-amber-600' : 'text-gray-800' }} mt-2">
                {{ $pendingRegistrations }}</p>
        </a>
        <div class="bg-white rounded-xl border border-gray-200 p-5 col-span-1">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Today's Revenue</p>
            <p class="text-2xl font-bold text-gray-800 mt-2">LKR {{ number_format($todayRevenue, 0) }}</p>
        </div>
        <a href="{{ route('bookings.index') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 col-span-1 block hover:shadow-sm transition">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Total Bookings</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalBookings }}</p>
        </a>
    </div>
    <div class="grid grid-cols-3 gap-4">
        <a href="{{ route('buses.index') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 transition-colors">
            <p class="text-sm font-medium text-gray-700">Manage fleet</p>
            <p class="text-xs text-gray-400 mt-1">Add, edit, update bus status</p>
        </a>
        <a href="{{ route('routes.index') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 transition-colors">
            <p class="text-sm font-medium text-gray-700">Manage routes</p>
            <p class="text-xs text-gray-400 mt-1">Add and edit bus routes</p>
        </a>
        <a href="{{ route('schedules.index') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 transition-colors">
            <p class="text-sm font-medium text-gray-700">Manage schedules</p>
            <p class="text-xs text-gray-400 mt-1">Create and assign schedules</p>
        </a>
    </div>
</x-dashboard-layout>