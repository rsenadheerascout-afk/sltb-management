<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — SLTB Yatinuwara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ tab: '{{ old('_tab', 'passenger') }}' }">

<header class="bg-white border-b border-gray-200 px-6 py-4">
    <div class="max-w-lg mx-auto flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div class="w-7 h-7 bg-blue-700 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <span class="text-sm font-semibold text-gray-800">SLTB Yatinuwara</span>
        </a>
        <a href="{{ route('passenger.login') }}" class="text-sm text-gray-500 hover:text-gray-700">
            Already have an account? <span class="text-blue-600 font-medium">Log in</span>
        </a>
    </div>
</header>

<main class="max-w-lg mx-auto px-6 py-10">

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Create an account</h1>
        <p class="text-gray-500 text-sm mt-2">
            Book seats as a passenger, or apply to join the depot staff.
        </p>
    </div>

    {{-- Toggle --}}
    <div class="bg-gray-100 rounded-xl p-1 flex mb-6">
        <button type="button"
                @click="tab = 'passenger'"
                :class="tab === 'passenger'
                    ? 'bg-white shadow-sm text-gray-900'
                    : 'text-gray-500 hover:text-gray-700'"
                class="flex-1 py-2.5 text-sm font-medium rounded-lg transition-all">
            Passenger Account
        </button>
        <button type="button"
                @click="tab = 'staff'"
                :class="tab === 'staff'
                    ? 'bg-white shadow-sm text-gray-900'
                    : 'text-gray-500 hover:text-gray-700'"
                class="flex-1 py-2.5 text-sm font-medium rounded-lg transition-all">
            Staff Application
        </button>
    </div>

    {{-- PASSENGER PANEL --}}
    <div x-show="tab === 'passenger'" x-transition>
        <div class="bg-white rounded-xl border border-gray-200 p-7">
            <div class="flex items-center gap-3 mb-5 pb-5 border-b border-gray-100">
                <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Passenger account</p>
                    <p class="text-xs text-gray-400">Book seats, view history, save your details.</p>
                </div>
            </div>

            @if ($errors->any() && old('_tab', 'passenger') === 'passenger')
                <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-4">
                    <ul class="text-xs text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('passenger.register') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="_tab" value="passenger">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Full name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="As on your NIC"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none
                                  @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email address <span class="text-red-400">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="you@example.com"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none
                                  @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Phone <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="07X XXXXXXX"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:outline-none
                                      @error('phone') border-red-400 @enderror">
                        @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIC number</label>
                        <input type="text" name="nic" value="{{ old('nic') }}"
                               placeholder="Optional"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password <span class="text-red-400">*</span>
                    </label>
                    <input type="password" name="password"
                           placeholder="Minimum 8 characters"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none
                                  @error('password') border-red-400 @enderror">
                    @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Confirm password <span class="text-red-400">*</span>
                    </label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <button type="submit"
                        class="w-full py-3 bg-blue-600 text-white text-sm font-semibold rounded-lg
                               hover:bg-blue-700 transition-colors mt-2">
                    Create Passenger Account
                </button>
            </form>
        </div>
    </div>

    {{-- STAFF APPLICATION PANEL --}}
    <div x-show="tab === 'staff'" x-transition>
        <div class="bg-white rounded-xl border border-gray-200 p-7">
            <div class="flex items-center gap-3 mb-5 pb-5 border-b border-gray-100">
                <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2
                                 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0
                                 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Staff application</p>
                    <p class="text-xs text-gray-400">Submit your application to work at the depot.</p>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 mb-5">
                <p class="text-xs text-amber-800 font-semibold mb-0.5">Requires approval</p>
                <p class="text-xs text-amber-700">
                    Applications are reviewed by the depot manager. You will be contacted once a decision is made.
                </p>
            </div>

            @if ($errors->any() && old('_tab') === 'staff')
                <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-4">
                    <ul class="text-xs text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ url('/apply') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="_tab" value="staff">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Full name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none
                                  @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email address <span class="text-red-400">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none
                                  @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            NIC <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="nic" value="{{ old('nic') }}"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:outline-none
                                      @error('nic') border-red-400 @enderror">
                        @error('nic')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Phone <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:outline-none
                                      @error('phone') border-red-400 @enderror">
                        @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Applying for role <span class="text-red-400">*</span>
                    </label>
                    <select name="applied_role"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                   focus:ring-2 focus:ring-blue-500 focus:outline-none
                                   @error('applied_role') border-red-400 @enderror">
                        <option value="">Select a role...</option>
                        @foreach([
                            'executive_officer' => 'Executive Officer',
                            'timekeeper'        => 'Timekeeper',
                            'storekeeper'       => 'Storekeeper',
                            'driver'            => 'Driver',
                            'conductor'         => 'Conductor',
                            'employee'          => 'General Employee',
                        ] as $val => $label)
                            <option value="{{ $val }}" @selected(old('applied_role') === $val)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('applied_role')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Address <span class="text-red-400">*</span>
                    </label>
                    <textarea name="address" rows="2"
                              class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                     focus:ring-2 focus:ring-blue-500 focus:outline-none
                                     @error('address') border-red-400 @enderror">{{ old('address') }}</textarea>
                    @error('address')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit"
                        class="w-full py-3 bg-amber-600 text-white text-sm font-semibold rounded-lg
                               hover:bg-amber-700 transition-colors mt-2">
                    Submit Staff Application
                </button>
            </form>
        </div>
    </div>

</main>
</body>
</html>