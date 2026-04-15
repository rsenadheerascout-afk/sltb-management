<x-passenger-layout title="My Profile">
<div class="max-w-xl">

    {{-- Avatar upload section --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Profile photo</h3>
        <div class="flex items-center gap-5">
            <img src="{{ $passenger->avatar_url }}" alt="Avatar"
                 class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
            <div>
                <form method="POST" action="{{ route('passenger.avatar') }}" enctype="multipart/form-data">
                    @csrf
                    <label class="cursor-pointer">
                        <span class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50 inline-block">
                            Choose photo
                        </span>
                        <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp"
                               class="hidden" onchange="this.form.submit()">
                    </label>
                    @error('avatar')<p class="text-xs text-red-500 mt-2">{{ $message }}</p>@enderror
                </form>
                <p class="text-xs text-gray-400 mt-2">JPG, PNG or WebP. Max 2 MB.</p>
            </div>
        </div>
    </div>

    {{-- Profile details --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-5">Personal details</h3>
        <form method="POST" action="{{ route('passenger.profile.update') }}" class="space-y-5">
            @csrf @method('PATCH')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                <input type="text" name="name" value="{{ old('name', $passenger->name) }}"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                <input type="email" value="{{ $passenger->email }}" disabled
                       class="w-full px-3 py-2.5 border border-gray-100 rounded-lg text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                <p class="text-xs text-gray-400 mt-1">Email cannot be changed.</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $passenger->phone) }}"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIC number</label>
                    <input type="text" name="nic" value="{{ old('nic', $passenger->nic) }}"
                           placeholder="Optional"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="address" rows="2"
                          class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('address', $passenger->address) }}</textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                    Save changes
                </button>
            </div>
        </form>
    </div>

</div>
</x-passenger-layout>