<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SLTB Yatinuwara — Bus Schedules & Seat Booking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white font-sans antialiased">

    {{-- ═══ NAVIGATION ══════════════════════════════════════════════════════════ --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-700 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <div>
                    <span class="font-bold text-gray-900 text-sm">SLTB Yatinuwara</span>
                    <span class="text-gray-400 text-xs block leading-tight">Yatinuwara Depot</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="#schedules" class="text-sm text-gray-600 hover:text-gray-900">Schedules</a>
                <a href="#how-to-book" class="text-sm text-gray-600 hover:text-gray-900">How to book</a>

                @auth('passenger')
                    {{-- Passenger is logged in --}}
                    <a href="{{ route('passenger.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">My
                        Account</a>
                @else
                    <a href="{{ route('passenger.login') }}" class="text-sm text-gray-600 hover:text-gray-900">Sign in</a>
                    <a href="{{ route('passenger.register') }}"
                        class="text-sm px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Register
                    </a>
                @endauth

                @auth
                    <a href="{{ route('dashboard') }}"
                        class="text-sm px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Staff Portal
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">Staff login</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ═══ HERO ════════════════════════════════════════════════════════════════ --}}
    <section class="bg-gradient-to-br from-blue-700 via-blue-800 to-blue-900 text-white">
        <div class="max-w-6xl mx-auto px-6 py-20 text-center">
            <div
                class="inline-flex items-center gap-2 bg-blue-600/50 border border-blue-500/50 rounded-full px-4 py-1.5 text-xs text-blue-100 mb-6">
                <span class="w-1.5 h-1.5 bg-green-400 rounded-full inline-block"></span>
                Schedules updated daily
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
                Travel with SLTB<br>
                <span class="text-blue-200">Yatinuwara Depot</span>
            </h1>
            <p class="text-blue-100 text-lg mb-10 max-w-xl mx-auto">
                View bus schedules, choose your seat, and book your journey online — fast and easy.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="#schedules"
                    class="px-8 py-3.5 bg-white text-blue-800 font-semibold rounded-xl hover:bg-blue-50 transition-colors text-sm">
                    View Schedules
                </a>
                <a href="#how-to-book"
                    class="px-8 py-3.5 border border-blue-400 text-white rounded-xl hover:bg-blue-700/50 transition-colors text-sm">
                    How to book
                </a>
            </div>
        </div>
    </section>

    {{-- ═══ STATS BAR ═══════════════════════════════════════════════════════════ --}}
    <section class="bg-gray-900 text-white">
        <div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-3 gap-8 text-center">
            <div>
                <p class="text-3xl font-bold text-blue-400">Yatinuwara</p>
                <p class="text-sm text-gray-400 mt-1">Depot</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-blue-400">Daily</p>
                <p class="text-sm text-gray-400 mt-1">Bus Services</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-blue-400">Online</p>
                <p class="text-sm text-gray-400 mt-1">Seat Booking</p>
            </div>
        </div>
    </section>

    {{-- ═══ SCHEDULES SECTION ═══════════════════════════════════════════════════ --}}
    <section id="schedules" class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900">Bus Schedules</h2>
                <p class="text-gray-500 mt-2">Browse upcoming bus services from Yatinuwara Depot</p>
            </div>

            {{-- Schedule search/filter placeholder --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
                <div class="flex gap-3 flex-wrap">
                    <input type="text" placeholder="Search route or destination..."
                        class="flex-1 min-w-48 px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <input type="date"
                        class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <button class="px-6 py-2.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 font-medium">
                        Search
                    </button>
                </div>
            </div>

            {{-- Placeholder schedule cards — will be replaced with real data in Branch 3 --}}
            <div class="space-y-3">
                @php
                    $placeholderSchedules = [
                        ['route' => 'Kandy — Peradeniya', 'from' => 'Kandy', 'to' => 'Peradeniya', 'dep' => '6:00 AM', 'arr' => '6:30 AM', 'bus' => 'YT001', 'fare' => 35, 'seats' => 42],
                        ['route' => 'Kandy — Gampola', 'from' => 'Kandy', 'to' => 'Gampola', 'dep' => '7:30 AM', 'arr' => '8:15 AM', 'bus' => 'YT007', 'fare' => 55, 'seats' => 18],
                        ['route' => 'Peradeniya — Kandy', 'from' => 'Peradeniya', 'to' => 'Kandy', 'dep' => '9:00 AM', 'arr' => '9:30 AM', 'bus' => 'YT042', 'fare' => 35, 'seats' => 0],
                    ];
                @endphp

                @foreach($placeholderSchedules as $s)
                    <div
                        class="bg-white rounded-xl border border-gray-200 p-5 flex items-center justify-between hover:border-blue-300 transition-colors">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-semibold text-gray-900">{{ $s['route'] }}</h3>
                                <span
                                    class="font-mono text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded">{{ $s['bus'] }}</span>
                            </div>
                            <div class="flex gap-6 text-sm text-gray-500">
                                <span>Departs <strong class="text-gray-800">{{ $s['dep'] }}</strong></span>
                                <span>Arrives <strong class="text-gray-800">{{ $s['arr'] }}</strong></span>
                                <span
                                    class="{{ $s['seats'] > 5 ? 'text-green-600' : ($s['seats'] > 0 ? 'text-amber-600' : 'text-red-500') }} font-medium">
                                    {{ $s['seats'] > 0 ? $s['seats'] . ' seats available' : 'Fully booked' }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right ml-6">
                            <p class="text-xl font-bold text-gray-900">LKR {{ $s['fare'] }}</p>
                            <p class="text-xs text-gray-400 mb-3">per seat</p>
                            @if($s['seats'] > 0)
                                <a href="{{ route('login') }}"
                                    class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 inline-block font-medium">
                                    Book Now
                                </a>
                            @else
                                <span class="px-5 py-2 bg-gray-100 text-gray-400 text-sm rounded-lg inline-block">Fully
                                    Booked</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <p class="text-sm text-gray-400">Showing sample schedules. Full schedule available after system launch.
                </p>
            </div>
        </div>
    </section>

    {{-- ═══ HOW TO BOOK ══════════════════════════════════════════════════════════ --}}
    <section id="how-to-book" class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">How to book a seat</h2>
                <p class="text-gray-500 mt-2">Simple steps to reserve your seat online</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @php
                    $steps = [
                        ['num' => '01', 'title' => 'Browse schedules', 'desc' => 'View all available bus routes and departure times from Yatinuwara Depot.', 'color' => 'blue'],
                        ['num' => '02', 'title' => 'Pick your seat', 'desc' => 'Choose your preferred seat from the visual bus layout — like booking a cinema.', 'color' => 'blue'],
                        ['num' => '03', 'title' => 'Enter details', 'desc' => 'Provide your name, email, and phone number for the booking confirmation.', 'color' => 'blue'],
                        ['num' => '04', 'title' => 'Pay & receive receipt', 'desc' => 'Pay securely online and download your receipt immediately.', 'color' => 'green'],
                    ];
                @endphp
                @foreach($steps as $step)
                    <div class="text-center">
                        <div
                            class="w-12 h-12 bg-{{ $step['color'] }}-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-{{ $step['color'] }}-700 font-bold text-sm">{{ $step['num'] }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══ INFO BAR ════════════════════════════════════════════════════════════ --}}
    <section class="py-12 bg-blue-50 border-t border-blue-100">
        <div class="max-w-5xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="flex gap-4">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm">Operating hours</h4>
                    <p class="text-sm text-gray-500 mt-1">Daily services from 5:30 AM to 9:00 PM. Check schedules for
                        exact timings.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm">Depot location</h4>
                    <p class="text-sm text-gray-500 mt-1">Yatinuwara Depot, Kandy District, Central Province, Sri Lanka.
                    </p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm">Enquiries</h4>
                    <p class="text-sm text-gray-500 mt-1">For assistance, visit the depot office or contact SLTB
                        Yatinuwara directly.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ FOOTER ══════════════════════════════════════════════════════════════ --}}
    <footer class="bg-gray-900 text-white py-10">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <p class="font-semibold text-sm">SLTB Yatinuwara Depot</p>
                <p class="text-gray-400 text-xs mt-1">Sri Lanka Transport Board &middot; Yatinuwara Depot</p>
            </div>
            <div class="flex gap-6 text-xs text-gray-400">
                <a href="#schedules" class="hover:text-white transition-colors">Schedules</a>
                <a href="#how-to-book" class="hover:text-white transition-colors">How to book</a>
                <a href="{{ route('employee.apply') }}" class="hover:text-white transition-colors">Join staff</a>
                <a href="{{ route('login') }}" class="hover:text-white transition-colors">Staff login</a>
            </div>
            <p class="text-xs text-gray-500">&copy; {{ date('Y') }} SLTB Yatinuwara</p>
        </div>
    </footer>

</body>

</html>