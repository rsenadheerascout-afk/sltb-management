<x-dashboard-layout title="Add Employee">
    <div class="max-w-2xl">
        <a href="{{ route('employees.index') }}"
            class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back</a>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-6">Add new employee</h2>
            <form method="POST" action="{{ route('employees.store') }}" class="space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full name <span
                                class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('name') border-red-400 @enderror">
                        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                class="text-red-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('email') border-red-400 @enderror">
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIC <span
                                class="text-red-400">*</span></label>
                        <input type="text" name="nic" value="{{ old('nic') }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('nic') border-red-400 @enderror">
                        @error('nic')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span
                                class="text-red-400">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('phone') border-red-400 @enderror">
                        @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role <span
                                class="text-red-400">*</span></label>
                        <select name="role"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('role') border-red-400 @enderror">
                            <option value="">Select role...</option>
                            @foreach(['executive_officer' => 'Executive Officer', 'timekeeper' => 'Timekeeper', 'storekeeper' => 'Storekeeper', 'driver' => 'Driver', 'conductor' => 'Conductor', 'employee' => 'General Employee'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('role') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Temporary password <span
                                class="text-red-400">*</span></label>
                        <input type="password" name="password"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('password') border-red-400 @enderror">
                        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm password <span
                                class="text-red-400">*</span></label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address <span
                            class="text-red-400">*</span></label>
                    <textarea name="address" rows="2"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('address') border-red-400 @enderror">{{ old('address') }}</textarea>
                    @error('address')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('employees.index') }}"
                        class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">Cancel</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Add
                        Employee</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>