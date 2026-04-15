<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Passenger Details — SLTB Booking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

    <header class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="max-w-xl mx-auto flex items-center justify-between">
            <a href="{{ route('booking.select-seats', $schedule) }}"
               class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to seat selection
            </a>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">1</span>
                <div class="w-8 h-px bg-gray-200"></div>
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">2</span>
                <div class="w-8 h-px bg-gray-200"></div>
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">3</span>
                <div class="w-8 h-px bg-gray-200"></div>
                <span class="w-6 h-6 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-xs font-bold">4</span>
            </div>
        </div>
    </header>

    <main class="max-w-xl mx-auto px-6 py-8">

        {{-- Journey + seats summary --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 mb-5">
            <h2 class="font-semibold text-gray-800 mb-1">{{ $schedule->route->name }}</h2>
            <p class="text-sm text-gray-500">
                {{ $schedule->schedule_date->format('D, d M Y') }}
                &middot;
                {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}
            </p>
            <div class="flex flex-wrap gap-2 mt-3">
                @foreach($seats as $seat)
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-full text-xs font-semibold">
                    Seat {{ $seat->seat_number }}
                </span>
                @endforeach
            </div>
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                <span class="text-sm text-gray-500">
                    {{ count($seatIds) }} seat{{ count($seatIds) > 1 ? 's' : '' }}
                    × LKR {{ number_format($schedule->fare, 2) }}
                </span>
                <span class="text-sm font-bold text-gray-800">
                    Total: LKR {{ number_format($total, 2) }}
                </span>
            </div>
        </div>

        {{-- Passenger details form --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-5">Your details</h2>

            <form method="POST" action="{{ route('booking.checkout') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                @foreach($seatIds as $seatId)
                    <input type="hidden" name="seat_ids[]" value="{{ $seatId }}">
                @endforeach

                {{-- Pre-fill from logged-in user --}}
                @php
                    $prefillName  = '';
                    $prefillEmail = '';
                    $prefillPhone = '';
                    $prefillNic   = '';
                    if (auth()->check()) {
                        $prefillName  = auth()->user()->name;
                        $prefillEmail = auth()->user()->email;
                        $prefillPhone = auth()->user()->phone ?? '';
                        $prefillNic   = auth()->user()->nic ?? '';
                    } elseif (auth()->guard('passenger')->check()) {
                        $p = auth()->guard('passenger')->user();
                        $prefillName  = $p->name;
                        $prefillEmail = $p->email;
                        $prefillPhone = $p->phone ?? '';
                        $prefillNic   = $p->nic ?? '';
                    }
                @endphp

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Full name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="passenger_name"
                           value="{{ old('passenger_name', $prefillName) }}"
                           placeholder="As on your NIC"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none
                                  @error('passenger_name') border-red-400 @enderror">
                    @error('passenger_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email address <span class="text-red-400">*</span>
                    </label>
                    <input type="email" name="passenger_email"
                           value="{{ old('passenger_email', $prefillEmail) }}"
                           placeholder="Confirmation will be sent here"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none
                                  @error('passenger_email') border-red-400 @enderror">
                    @error('passenger_email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Phone number <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="passenger_phone"
                               value="{{ old('passenger_phone', $prefillPhone) }}"
                               placeholder="07X XXXXXXX"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:outline-none
                                      @error('passenger_phone') border-red-400 @enderror">
                        @error('passenger_phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIC number</label>
                        <input type="text" name="passenger_nic"
                               value="{{ old('passenger_nic', $prefillNic) }}"
                               placeholder="Optional"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-3 text-xs text-blue-700">
                    Your seats are temporarily held. Complete payment within 15 minutes to confirm.
                </div>

                <button type="submit"
                        class="w-full py-3 bg-blue-600 text-white text-sm font-semibold rounded-lg
                               hover:bg-blue-700 transition-colors">
                    Proceed to Payment &rarr;
                </button>
            </form>
        </div>

    </main>
</body>
</html>