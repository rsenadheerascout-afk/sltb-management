<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Select Seats — {{ $schedule->route->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Ensure grid + panel stay side by side at all viewport sizes */
        .seat-layout { display: flex; gap: 1.5rem; align-items: flex-start; }
        .seat-map-col { flex: 1; min-width: 0; }
        .seat-summary-col { width: 240px; flex-shrink: 0; }
        @media (max-width: 680px) {
            .seat-layout { flex-direction: column; }
            .seat-summary-col { width: 100%; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Header --}}
    <header class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="{{ route('schedules.public') }}"
               class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to schedules
            </a>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">1</span>
                <div class="w-8 h-px bg-gray-200"></div>
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">2</span>
                <div class="w-8 h-px bg-gray-200"></div>
                <span class="w-6 h-6 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-xs font-bold">3</span>
                <div class="w-8 h-px bg-gray-200"></div>
                <span class="w-6 h-6 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-xs font-bold">4</span>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-8" x-data="seatPicker()">

        {{-- Flash error --}}
        @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            {{ session('error') }}
        </div>
        @endif

        {{-- Journey summary --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 mb-5">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">{{ $schedule->route->name }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ $schedule->schedule_date->format('D, d M Y') }}
                        &nbsp;&middot;&nbsp;
                        {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}
                        &nbsp;&middot;&nbsp;
                        <span class="font-mono text-blue-700 font-semibold">{{ $schedule->bus->depot_reg_no }}</span>
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-gray-800">LKR {{ number_format($schedule->fare, 0) }}</p>
                    <p class="text-xs text-gray-400">per seat</p>
                </div>
            </div>
        </div>

        {{-- Main layout: seat map LEFT, summary panel RIGHT --}}
        <div class="seat-layout">

            {{-- ── SEAT MAP ────────────────────────────────────────────── --}}
            <div class="seat-map-col">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-sm font-semibold text-gray-700 mb-5">Choose your seats</h2>

                    {{-- Legend --}}
                    <div class="flex flex-wrap gap-4 mb-5 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-md bg-green-100 border-2 border-green-400 inline-block"></span>
                            Available
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-md bg-yellow-100 border-2 border-yellow-400 inline-block"></span>
                            Selected
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-md bg-red-100 border-2 border-red-200 inline-block opacity-70"></span>
                            Booked
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-md bg-gray-100 border-2 border-gray-200 inline-block opacity-60"></span>
                            Held
                        </span>
                    </div>

                    {{-- Driver cab --}}
                    <div class="bg-gray-100 rounded-lg py-2 text-center text-xs text-gray-400 mb-4 border border-gray-200">
                        &#9673; Driver
                    </div>

                    {{-- Column labels: A B | C D E --}}
                    <div class="flex items-center gap-2 mb-2 pl-7">
                        <div class="flex gap-1.5">
                            <div class="w-10 text-center text-xs text-gray-300 font-medium">A</div>
                            <div class="w-10 text-center text-xs text-gray-300 font-medium">B</div>
                            <div class="w-6"></div>
                            <div class="w-10 text-center text-xs text-gray-300 font-medium">C</div>
                            <div class="w-10 text-center text-xs text-gray-300 font-medium">D</div>
                            <div class="w-10 text-center text-xs text-gray-300 font-medium">E</div>
                        </div>
                    </div>

                    {{-- Seat rows — 5 seats per row: 2 | aisle | 3 --}}
                    @php
                        $seatRows  = $seats->chunk(5);
                        $rowNumber = 1;
                    @endphp

                    @foreach($seatRows as $row)
                    <div class="flex items-center gap-2 mb-1.5">
                        {{-- Row number --}}
                        <span class="text-xs text-gray-300 w-5 text-right flex-shrink-0 select-none">
                            {{ $rowNumber }}
                        </span>
                        <div class="flex gap-1.5">
                            @foreach($row->values() as $index => $seat)

                                {{-- Aisle gap between seat index 1 and 2 (after 2nd seat) --}}
                                @if($index === 2)
                                    <div class="w-6 flex items-center justify-center flex-shrink-0">
                                        <div class="h-9 w-px bg-gray-200"></div>
                                    </div>
                                @endif

                                @if($seat['status'] === 'available')
                                    <button
                                        type="button"
                                        @click="toggleSeat({{ $seat['id'] }}, '{{ $seat['seat_number'] }}')"
                                        :class="isSelected({{ $seat['id'] }})
                                            ? 'bg-yellow-100 border-yellow-400 text-yellow-800 ring-2 ring-yellow-300 scale-105'
                                            : 'bg-green-100 border-green-400 text-green-800 hover:bg-green-200 hover:scale-105'"
                                        class="w-10 h-10 rounded-lg border-2 text-xs font-semibold
                                               transition-all duration-150 flex-shrink-0 shadow-sm select-none">
                                        {{ $seat['seat_number'] }}
                                    </button>

                                @elseif($seat['status'] === 'booked')
                                    <div class="w-10 h-10 rounded-lg border-2 bg-red-100 border-red-200
                                                text-red-400 text-xs font-medium flex items-center justify-center
                                                cursor-not-allowed flex-shrink-0 opacity-70 select-none">
                                        {{ $seat['seat_number'] }}
                                    </div>

                                @else
                                    <div class="w-10 h-10 rounded-lg border-2 bg-gray-100 border-gray-200
                                                text-gray-400 text-xs font-medium flex items-center justify-center
                                                cursor-not-allowed flex-shrink-0 opacity-60 select-none">
                                        {{ $seat['seat_number'] }}
                                    </div>
                                @endif

                            @endforeach
                        </div>
                    </div>
                    @php $rowNumber++; @endphp
                    @endforeach

                </div>
            </div>

            {{-- ── SUMMARY PANEL ───────────────────────────────────────── --}}
            <div class="seat-summary-col">
                <div class="bg-white rounded-xl border border-gray-200 p-5 sticky top-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Your selection</h3>

                    {{-- Empty state --}}
                    <div x-show="selectedSeats.length === 0"
                         class="text-center py-6">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            No seats selected.<br>Click a green seat to begin.
                        </p>
                    </div>

                    {{-- Selected seats list --}}
                    <div x-show="selectedSeats.length > 0">
                        <div class="space-y-2 mb-4 max-h-48 overflow-y-auto">
                            <template x-for="seat in selectedSeats" :key="seat.id">
                                <div class="flex items-center justify-between bg-yellow-50 border border-yellow-200 rounded-lg px-3 py-2">
                                    <span class="text-sm font-semibold text-gray-700">
                                        Seat <span x-text="seat.number"></span>
                                    </span>
                                    <button type="button"
                                            @click="toggleSeat(seat.id, seat.number)"
                                            class="text-xs text-red-500 hover:text-red-700 hover:underline ml-2">
                                        Remove
                                    </button>
                                </div>
                            </template>
                        </div>

                        {{-- Price breakdown --}}
                        <div class="border-t border-gray-100 pt-3 mb-4 space-y-1">
                            <div class="flex justify-between text-xs text-gray-500">
                                <span x-text="selectedSeats.length + ' seat(s) × LKR {{ number_format($schedule->fare, 0) }}'"></span>
                            </div>
                            <div class="flex justify-between text-sm font-bold text-gray-800">
                                <span>Total</span>
                                <span>
                                    LKR <span x-text="(selectedSeats.length * {{ $schedule->fare }}).toLocaleString()"></span>
                                </span>
                            </div>
                        </div>

                        {{-- Form — uses direct DOM injection for reliable input submission --}}
                        <form method="POST"
                              action="{{ route('booking.passenger-details', $schedule) }}"
                              id="seat-form">
                            @csrf
                            <div id="seat-inputs-container"></div>
                            <button type="button"
                                    @click="submitSeats()"
                                    class="w-full py-2.5 bg-blue-600 text-white text-sm font-semibold
                                           rounded-lg hover:bg-blue-700 transition-colors">
                                Continue to Details &rarr;
                            </button>
                        </form>
                    </div>

                    <p class="text-xs text-gray-400 mt-3 text-center">Max 6 seats per booking</p>
                </div>
            </div>

        </div>{{-- end seat-layout --}}

    </main>

    <script>
    function seatPicker() {
        return {
            selectedSeats: [],

            isSelected(id) {
                return this.selectedSeats.some(s => s.id === id);
            },

            toggleSeat(id, number) {
                if (this.isSelected(id)) {
                    this.selectedSeats = this.selectedSeats.filter(s => s.id !== id);
                } else {
                    if (this.selectedSeats.length >= 6) {
                        alert('You can select a maximum of 6 seats per booking.');
                        return;
                    }
                    this.selectedSeats.push({ id, number });
                }
            },

            submitSeats() {
                if (this.selectedSeats.length === 0) {
                    alert('Please select at least one seat before continuing.');
                    return;
                }

                const form      = document.getElementById('seat-form');
                const container = document.getElementById('seat-inputs-container');

                // Clear previous inputs
                container.innerHTML = '';

                // Write real hidden inputs — avoids Alpine x-for timing issues
                this.selectedSeats.forEach(seat => {
                    const input = document.createElement('input');
                    input.type  = 'hidden';
                    input.name  = 'seat_ids[]';
                    input.value = seat.id;
                    container.appendChild(input);
                });

                form.submit();
            }
        }
    }
    </script>

</body>
</html>