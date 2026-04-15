<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Passenger Login — SLTB Yatinuwara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
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
            <div class="flex items-center gap-3 text-sm">
                <span class="text-gray-400">Depot staff?</span>
                <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Staff login</a>
            </div>
        </div>
    </header>
    <main class="max-w-sm mx-auto px-6 py-12">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Passenger login</h1>
            <p class="text-gray-500 text-sm mt-2">Sign in to book seats and view your booking history.</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-7">
            <form method="POST" action="{{ route('passenger.login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" autofocus
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded">
                        Remember me
                    </label>
                </div>
                <button type="submit"
                        class="w-full py-3 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700">
                    Sign in
                </button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-5">
                No account?
                <a href="{{ route('passenger.register') }}" class="text-blue-600 font-medium hover:underline">Create one</a>
            </p>
        </div>
    </main>
</body>
</html>