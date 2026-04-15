<x-dashboard-layout title="Employee Profile">
    <div class="max-w-2xl">
        <a href="{{ route('employees.index') }}"
            class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back</a>
        <div class="bg-white rounded-xl border border-gray-200 p-6">

            <div class="flex items-center gap-5 mb-6 pb-6 border-b border-gray-100">
                <div class="relative flex-shrink-0">
                    <img src="{{ $employee->avatar_url }}" alt="Avatar"
                        class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                    @if(auth()->user()->id === $employee->id)
                        <form method="POST" action="{{ route('account.avatar') }}" enctype="multipart/form-data"
                            id="emp-avatar-form">
                            @csrf
                            <label
                                class="absolute -bottom-1 -right-1 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-blue-700">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" name="avatar" accept="image/*" class="hidden"
                                    onchange="document.getElementById('emp-avatar-form').submit()">
                            </label>
                        </form>
                    @endif
                </div>
                <div>
                    <p class="font-semibold text-gray-800 text-lg">{{ $employee->name }}</p>
                    <p class="text-sm text-gray-400">{{ $employee->employee_id }}</p>
                </div>
            </div>
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