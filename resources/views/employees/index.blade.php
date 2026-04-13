<x-dashboard-layout title="Employees">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">All Employees</h2>
            <p class="text-sm text-gray-500">{{ $employees->total() }} total records</p>
        </div>
        <a href="{{ route('employees.create') }}"
            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">+ Add Employee</a>
    </div>
    <form method="GET" class="flex gap-3 mb-6 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, ID, NIC..."
            class="flex-1 min-w-48 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="role" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
            <option value="">All roles</option>
            @foreach(['executive_officer', 'timekeeper', 'storekeeper', 'driver', 'conductor', 'employee'] as $r)
                <option value="{{ $r }}" @selected(request('role') === $r)>{{ ucfirst(str_replace('_', ' ', $r)) }}</option>
            @endforeach
        </select>
        <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
            <option value="">All statuses</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="disabled" @selected(request('status') === 'disabled')>Disabled</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg">Filter</button>
        <a href="{{ route('employees.index') }}"
            class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">Reset</a>
    </form>
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Employee</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">ID</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Role</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">NIC</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Phone</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Status</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($employees as $emp)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $emp->name }}</p>
                            <p class="text-xs text-gray-400">{{ $emp->email }}</p>
                        </td>
                        <td class="px-4 py-3 font-mono text-gray-600">{{ $emp->employee_id }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs">
                                {{ ucfirst(str_replace('_', ' ', $emp->role)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $emp->nic }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $emp->phone }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-0.5 rounded text-xs {{ $emp->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600' }}">
                                {{ ucfirst($emp->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-3">
                                <a href="{{ route('employees.show', $emp) }}"
                                    class="text-xs text-blue-600 hover:underline">View</a>
                                <a href="{{ route('employees.edit', $emp) }}"
                                    class="text-xs text-gray-600 hover:underline">Edit</a>
                                @if($emp->status === 'active')
                                    <form method="POST" action="{{ route('employees.disable', $emp) }}"
                                        onsubmit="return confirm('Disable {{ $emp->name }}?')">
                                        @csrf <button class="text-xs text-red-500 hover:underline">Disable</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('employees.enable', $emp) }}">
                                        @csrf <button class="text-xs text-green-600 hover:underline">Enable</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">
                            No employees found. <a href="{{ route('employees.create') }}"
                                class="text-blue-600 hover:underline">Add one</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $employees->links() }}</div>
</x-dashboard-layout>