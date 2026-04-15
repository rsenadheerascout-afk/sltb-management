<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Seat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class BookingController extends Controller
{
    // ── Step 2: Visual seat picker ───────────────────────────────────────
    public function selectSeats(Schedule $schedule)
    {
        if ($schedule->status !== 'active') {
            return redirect()->route('schedules.public')
                ->with('error', 'This schedule is no longer available.');
        }

        $schedule->load(['route', 'bus.seats']);

        // Paid bookings for this schedule
        $bookedSeatIds = Booking::where('schedule_id', $schedule->id)
            ->where('payment_status', 'paid')
            ->pluck('seat_id')
            ->toArray();

        // Pending bookings held for under 15 minutes
        $pendingSeatIds = Booking::where('schedule_id', $schedule->id)
            ->where('payment_status', 'pending')
            ->where('created_at', '>', now()->subMinutes(15))
            ->pluck('seat_id')
            ->toArray();

        $seats = $schedule->bus->seats->map(fn($seat) => [
            'id'          => $seat->id,
            'seat_number' => $seat->seat_number,
            'status'      => in_array($seat->id, $bookedSeatIds)
                ? 'booked'
                : (in_array($seat->id, $pendingSeatIds) ? 'pending' : 'available'),
        ]);

        return view('bookings.select-seats', compact('schedule', 'seats'));
    }

    // ── Step 3: Passenger details form ──────────────────────────────────
    // Accepts GET (browser refresh) and POST (form submission from seat picker)
    public function passengerDetails(Request $request, Schedule $schedule)
    {
        if ($schedule->status !== 'active') {
            return redirect()->route('schedules.public')
                ->with('error', 'This schedule is no longer available.');
        }

        if ($request->isMethod('POST')) {
            $request->validate([
                'seat_ids'   => ['required', 'array', 'min:1', 'max:6'],
                'seat_ids.*' => ['exists:seats,id'],
            ]);

            // Store in session — survives page refresh
            session([
                'booking_seat_ids'    => $request->seat_ids,
                'booking_schedule_id' => $schedule->id,
            ]);
        }

        // Read from session for both GET and POST
        $seatIds = session('booking_seat_ids', []);

        // Guard: redirect back if session is empty or for a different schedule
        if (empty($seatIds) || session('booking_schedule_id') != $schedule->id) {
            return redirect()->route('booking.select-seats', $schedule)
                ->with('error', 'Please select your seats first.');
        }

        $seats = Seat::whereIn('id', $seatIds)->get();

        if ($seats->isEmpty()) {
            return redirect()->route('booking.select-seats', $schedule)
                ->with('error', 'Selected seats not found. Please try again.');
        }

        // Check seats are still available
        $conflict = Booking::where('schedule_id', $schedule->id)
            ->whereIn('seat_id', $seatIds)
            ->where('payment_status', 'paid')
            ->exists();

        if ($conflict) {
            session()->forget(['booking_seat_ids', 'booking_schedule_id']);
            return redirect()->route('booking.select-seats', $schedule)
                ->with('error', 'One or more seats were just booked. Please reselect.');
        }

        $total = $schedule->fare * count($seatIds);

        return view('bookings.passenger-details', compact('schedule', 'seats', 'seatIds', 'total'));
    }

    // ── Step 4: Create Stripe Payment Intent and show payment page ───────
    public function checkout(StoreBookingRequest $request)
    {
        $schedule = Schedule::with(['route', 'bus'])->findOrFail($request->schedule_id);
        $seatIds  = $request->seat_ids;
        $seats    = Seat::whereIn('id', $seatIds)->get();
        $total    = $schedule->fare * count($seatIds);

        // Final availability check
        $conflict = Booking::where('schedule_id', $schedule->id)
            ->whereIn('seat_id', $seatIds)
            ->where('payment_status', 'paid')
            ->exists();

        if ($conflict) {
            return redirect()->route('booking.select-seats', $schedule)
                ->with('error', 'One or more seats were just taken. Please reselect.');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $intent = PaymentIntent::create([
                'amount'   => (int) ($total * 100), // LKR in cents
                'currency' => 'lkr',
                'metadata' => [
                    'schedule_id'    => $schedule->id,
                    'seat_ids'       => implode(',', $seatIds),
                    'passenger_name' => $request->passenger_name,
                ],
            ]);

            // Create one pending booking record per seat
            foreach ($seatIds as $seatId) {
                Booking::create([
                    'user_id'                  => Auth::check() ? Auth::id() : null,
                    'passenger_id'             => Auth::guard('passenger')->check()
                                                    ? Auth::guard('passenger')->id()
                                                    : null,
                    'schedule_id'              => $schedule->id,
                    'seat_id'                  => $seatId,
                    'passenger_name'           => $request->passenger_name,
                    'passenger_email'          => $request->passenger_email,
                    'passenger_phone'          => $request->passenger_phone,
                    'passenger_nic'            => $request->passenger_nic,
                    'amount'                   => $schedule->fare,
                    'stripe_payment_intent_id' => $intent->id,
                    'payment_status'           => 'pending',
                    'booking_ref'              => Booking::generateRef(),
                ]);
            }

            // Clear seat session
            session()->forget(['booking_seat_ids', 'booking_schedule_id']);

            return view('bookings.payment', [
                'schedule'       => $schedule,
                'seats'          => $seats,
                'total'          => $total,
                'clientSecret'   => $intent->client_secret,
                'stripeKey'      => config('services.stripe.key'),
                'passengerName'  => $request->passenger_name,
                'passengerEmail' => $request->passenger_email,
                'intentId'       => $intent->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe checkout error: ' . $e->getMessage());
            return back()->with('error', 'Payment setup failed. Please try again.');
        }
    }

    // ── Step 5: Stripe redirects back after payment ──────────────────────
    public function confirm(Request $request)
    {
        $intentId = $request->payment_intent;

        if (!$intentId) {
            return redirect()->route('schedules.public')
                ->with('error', 'Invalid confirmation request.');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $intent = PaymentIntent::retrieve($intentId);

            if ($intent->status !== 'succeeded') {
                Booking::where('stripe_payment_intent_id', $intentId)
                    ->update(['payment_status' => 'failed']);

                return redirect()->route('schedules.public')
                    ->with('error', 'Payment was not successful. Please try again.');
            }

            // Mark all bookings for this intent as paid
            $bookings = Booking::where('stripe_payment_intent_id', $intentId)->get();

            foreach ($bookings as $booking) {
                $booking->update([
                    'payment_status' => 'paid',
                    'booked_at'      => now(),
                ]);
            }

            // Send confirmation email
            try {
                Mail::to($bookings->first()->passenger_email)
                    ->send(new BookingConfirmation($bookings));
            } catch (\Exception $e) {
                Log::warning('Booking confirmation email failed: ' . $e->getMessage());
            }

            return redirect()->route('booking.confirmation', ['intentId' => $intentId]);

        } catch (\Exception $e) {
            Log::error('Booking confirm error: ' . $e->getMessage());
            return redirect()->route('schedules.public')
                ->with('error', 'Could not confirm booking. Please contact the depot.');
        }
    }

    // ── Step 6: Confirmation page ────────────────────────────────────────
    public function confirmation(Request $request)
    {
        $intentId = $request->intentId;

        $bookings = Booking::with(['schedule.route', 'schedule.bus', 'seat'])
            ->where('stripe_payment_intent_id', $intentId)
            ->where('payment_status', 'paid')
            ->get();

        if ($bookings->isEmpty()) {
            return redirect()->route('schedules.public');
        }

        return view('bookings.confirmation', compact('bookings'));
    }

    // ── Download PDF receipt ─────────────────────────────────────────────
    public function downloadReceipt(string $ref)
    {
        $booking = Booking::with(['schedule.route', 'schedule.bus', 'seat'])
            ->where('booking_ref', $ref)
            ->where('payment_status', 'paid')
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.receipt', compact('booking'));
        $pdf->setPaper('A5', 'portrait');

        return $pdf->download("receipt-{$booking->booking_ref}.pdf");
    }

    // ── Admin: list all bookings ─────────────────────────────────────────
    public function index()
    {
        $bookings = Booking::with(['schedule.route', 'seat'])
            ->latest()
            ->paginate(20);

        return view('bookings.index', compact('bookings'));
    }
}