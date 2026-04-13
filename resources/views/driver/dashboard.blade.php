<x-dashboard-layout title="Driver Dashboard">
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
        <h2 class="font-semibold text-gray-800 mb-2">Welcome, {{ auth()->user()->name }}</h2>
        <p class="text-sm text-gray-500">Employee ID: {{ auth()->user()->employee_id }}</p>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Today's Assignment</p>
            <p class="text-sm text-gray-600 mt-2">No schedule assigned yet.</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Quick Actions</p>
            <p class="text-sm text-gray-400 mt-2">Breakdown reporting available in Branch 5.</p>
        </div>
    </div>
</x-dashboard-layout>