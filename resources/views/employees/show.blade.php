<x-dashboard-layout title="Employee Profile">
    <div class="max-w-2xl">
        <a href="{{ route('employees.index') }}"
            class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back</a>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $employee->name }}</h2>
                    <p class="text-sm text-gray-400 mt-1">{{ $employee->employee_id }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('employees.edit', $employee) }}"
                        class="px-3 py-1.5 border border-gray-200 text-sm rounded-lg hover:bg-gray-50">Edit</a>
                    @if($employee->status === 'active')
                        <form method="POST" action="{{ route('employees.disable', $employee) }}"
                            onsubmit="return confirm('Disable this account?')">
                            @csrf <button
                                class="px-3 py-1.5 bg-red-50 text-red-600 text-sm rounded-lg hover:bg-red-100 border border-red-200">Disable</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('employees.enable', $employee) }}">
                            @csrf <button
                                class="px-3 py-1.5 bg-green-50 text-green-700 text-sm rounded-lg hover:bg-green-100 border border-green-200">Enable</button>
                        </form>
                    @endif
                </div>
            </div>
            <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <dt class="text-gray-400">Email</dt>
                    <dd class="text-gray-800 mt-0.5">{{ $employee->email }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Role</dt>
                    <dd class="mt-0.5">
                        <span
                            class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs">{{ ucfirst(str_replace('_', ' ', $employee->role)) }}</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-400">NIC</dt>
                    <dd class="text-gray-800 mt-0.5">{{ $employee->nic }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Phone</dt>
                    <dd class="text-gray-800 mt-0.5">{{ $employee->phone }}</dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-gray-400">Address</dt>
                    <dd class="text-gray-800 mt-0.5">{{ $employee->address }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Status</dt>
                    <dd class="mt-0.5">
                        <span
                            class="px-2 py-0.5 rounded text-xs {{ $employee->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600' }}">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-400">Joined</dt>
                    <dd class="text-gray-800 mt-0.5">{{ $employee->created_at->format('d M Y') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</x-dashboard-layout>