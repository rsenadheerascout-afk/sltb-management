<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SLTB — {{ $title ?? 'My Account' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

{{-- Passenger top navigation --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
    <div class="max-w-5xl mx-auto px-6 py-3 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div class="w-7 h-7 bg-blue-700 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <span class="text-sm font-semibold text-gray-800">SLTB Yatinuwara</span>
        </a>

        {{-- Passenger nav links --}}
        <div class="flex items-center gap-1">
            <a href="{{ route('passenger.dashboard') }}"
               class="px-3 py-1.5 text-sm rounded-lg {{ request()->routeIs('passenger.dashboard') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                Dashboard
            </a>
            <a href="{{ route('schedules.public') }}"
               class="px-3 py-1.5 text-sm rounded-lg text-gray-600 hover:bg-gray-50">
                Book a Seat
            </a>
            <a href="{{ route('passenger.bookings') }}"
               class="px-3 py-1.5 text-sm rounded-lg {{ request()->routeIs('passenger.bookings') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                My Bookings
            </a>
            <a href="{{ route('passenger.profile') }}"
               class="px-3 py-1.5 text-sm rounded-lg {{ request()->routeIs('passenger.profile') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                Profile
            </a>
        </div>

        {{-- Passenger avatar + logout --}}
        <div class="flex items-center gap-3">
            @php $passenger = Auth::guard('passenger')->user(); @endphp
            <img src="{{ $passenger->avatar_url }}" alt="Avatar"
                 class="w-8 h-8 rounded-full object-cover border border-gray-200">
            <span class="text-sm text-gray-700 font-medium">{{ $passenger->name }}</span>
            <form method="POST" action="{{ route('passenger.logout') }}">
                @csrf
                <button class="text-xs text-gray-400 hover:text-gray-600">Sign out</button>
            </form>
        </div>
    </div>
</nav>

{{-- Flash messages --}}
<div class="max-w-5xl mx-auto px-6 pt-4">
    @if(session('success'))
    <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm mb-4">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm mb-4">
        {{ session('error') }}
    </div>
    @endif
</div>

<main class="max-w-5xl mx-auto px-6 py-6">
    {{ $slot }}
</main>

</body>
</html>