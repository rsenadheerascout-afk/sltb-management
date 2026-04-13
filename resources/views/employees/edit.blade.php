<x-dashboard-layout title="Edit Employee">
    <div class="max-w-2xl">
        <a href="{{ route('employees.show', $employee) }}"
            class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back</a>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-6">Edit — {{ $employee->employee_id }}</h2>
            <form method="POST" action="{{ route('employees.update', $employee) }}" class="space-y-5">
                @csrf @method('PATCH')
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                        <input type="text" name="name" value="{{ old('name', $employee->name) }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $employee->email) }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIC</label>
                        <input type="text" name="nic" value="{{ old('nic', $employee->nic) }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('nic')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @foreach(['executive_officer' => 'Executive Officer', 'timekeeper' => 'Timekeeper', 'storekeeper' => 'Storekeeper', 'driver' => 'Driver', 'conductor' => 'Conductor', 'employee' => 'General Employee'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('role', $employee->role) === $val)>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="2"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('address', $employee->address) }}</textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('employees.show', $employee) }}"
                        class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg">Cancel</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Save
                        changes</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>