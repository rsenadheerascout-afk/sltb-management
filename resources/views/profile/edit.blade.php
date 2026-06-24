<x-dashboard-layout title="My Profile">
    {{-- Employee summary card --}}
    <div class="max-w-2xl mb-5">
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-5">
            <div class="relative flex-shrink-0">
                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" 
                     class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                
                {{-- Dynamic Avatar Upload Form Overlay --}}
                <form method="POST" action="{{ route('account.avatar') }}" enctype="multipart/form-data" id="emp-prof-avatar">
                    @csrf
                    <label class="absolute -bottom-1 -right-1 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-blue-700">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <input type="file" name="avatar" accept="image/*" class="hidden"
                               onchange="document.getElementById('emp-prof-avatar').submit()">
                    </label>
                </form>
            </div>
            <div>
                <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                <p class="text-sm text-gray-400 mt-0.5">
                    {{ auth()->user()->employee_id }}
                    &middot;
                    {{ ucfirst(str_replace('_',' ', auth()->user()->role)) }}
                </p>
                @error('avatar')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Breeze's default profile sections --}}
    <div class="max-w-2xl space-y-5">
        {{-- Profile Information Card --}}
        <div class="p-4 sm:p-8 bg-white border border-gray-200 rounded-xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Update Password Card --}}
        <div class="p-4 sm:p-8 bg-white border border-gray-200 rounded-xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Delete Account Card --}}
        <div class="p-4 sm:p-8 bg-white border border-gray-200 rounded-xl">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-dashboard-layout>