<x-dashboard-layout title="Dashboard">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-1">Welcome, {{ auth()->user()->name }}</h2>
        <p class="text-sm text-gray-500">Employee ID: {{ auth()->user()->employee_id }}</p>
        <div class="mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('profile.edit') }}" class="text-sm text-blue-600 hover:underline">Edit my profile</a>
        </div>
    </div>
</x-dashboard-layout>