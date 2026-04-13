<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apply — SLTB Yatinuwara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">
    <header class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="max-w-xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-7 h-7 bg-blue-700 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-800">SLTB Yatinuwara</span>
            </a>
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">Already staff? Login</a>
        </div>
    </header>
    <main class="max-w-xl mx-auto px-6 py-10">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Employee Registration</h1>
            <p class="text-gray-500 text-sm mt-2">Submit your application to join SLTB Yatinuwara Depot staff.</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-8">
            <form method="POST" action="{{ url('/apply') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full name <span
                            class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email address <span
                            class="text-red-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIC number <span
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
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Applying for role <span
                            class="text-red-400">*</span></label>
                    <select name="applied_role"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('applied_role') border-red-400 @enderror">
                        <option value="">Select a role...</option>
                        @foreach(['executive_officer' => 'Executive Officer', 'timekeeper' => 'Timekeeper', 'storekeeper' => 'Storekeeper', 'driver' => 'Driver', 'conductor' => 'Conductor', 'employee' => 'General Employee'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('applied_role') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('applied_role')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address <span
                            class="text-red-400">*</span></label>
                    <textarea name="address" rows="2"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('address') border-red-400 @enderror">{{ old('address') }}</textarea>
                    @error('address')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit"
                    class="w-full py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                    Submit Application
                </button>
            </form>
        </div>
    </main>
</body>

</html>