<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment — SLTB Booking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <header class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="max-w-xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span class="text-sm text-gray-500">Secure Payment</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">1</span>
                <div class="w-8 h-px bg-gray-200"></div>
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">2</span>
                <div class="w-8 h-px bg-gray-200"></div>
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">3</span>
                <div class="w-8 h-px bg-gray-200"></div>
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">4</span>
            </div>
        </div>
    </header>

    <main class="max-w-xl mx-auto px-6 py-8">

        {{-- Order summary --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 mb-5">
            <h2 class="font-semibold text-gray-800 mb-3">Order summary</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Route</span>
                    <span class="font-medium text-gray-800">{{ $schedule->route->name }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Date &amp; time</span>
                    <span>{{ $schedule->schedule_date->format('d M Y') }} &middot; {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Seats</span>
                    <span>{{ $seats->pluck('seat_number')->join(', ') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Passenger</span>
                    <span>{{ $passengerName }}</span>
                </div>
                <div class="border-t border-gray-100 pt-2 flex justify-between font-bold text-gray-800">
                    <span>Total</span>
                    <span>LKR {{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Stripe payment form --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-1">Card details</h2>
            <p class="text-xs text-gray-400 mb-5">
                Secured by Stripe. We never store your card details.
            </p>

            <div id="payment-element" class="mb-5"></div>

            <div id="payment-message"
                 class="hidden bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm mb-4">
            </div>

            <button id="pay-button"
                    class="w-full py-3 bg-blue-600 text-white text-sm font-semibold rounded-lg
                           hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Pay LKR {{ number_format($total, 2) }}
            </button>

            <p class="text-xs text-gray-400 mt-3 text-center">
                Test card: <span class="font-mono">4242 4242 4242 4242</span>
                &middot; any future date &middot; any CVC
            </p>
        </div>

    </main>

    <script>
    const stripe   = Stripe('{{ $stripeKey }}');
    const elements = stripe.elements({ clientSecret: '{{ $clientSecret }}' });

    const paymentEl = elements.create('payment');
    paymentEl.mount('#payment-element');

    const btn = document.getElementById('pay-button');
    const msg = document.getElementById('payment-message');

    btn.addEventListener('click', async () => {
        btn.disabled    = true;
        btn.textContent = 'Processing...';
        msg.classList.add('hidden');
        msg.textContent = '';

        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: '{{ route("booking.confirm") }}',
            },
        });

        if (error) {
            msg.textContent = error.message;
            msg.classList.remove('hidden');
            btn.disabled    = false;
            btn.textContent = 'Pay LKR {{ number_format($total, 2) }}';
        }
        // If no error, Stripe redirects to return_url automatically
    });
    </script>

</body>
</html>