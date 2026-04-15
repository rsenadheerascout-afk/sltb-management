<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PassengerController extends Controller
{
    private function passenger()
    {
        return Auth::guard('passenger')->user();
    }

    public function dashboard()
    {
        $passenger = $this->passenger();

        $recentBookings = Booking::where('passenger_id', $passenger->id)
            ->with(['schedule.route', 'schedule.bus', 'seat'])
            ->where('payment_status', 'paid')
            ->latest()
            ->take(5)
            ->get();

        $totalSpent = Booking::where('passenger_id', $passenger->id)
            ->where('payment_status', 'paid')
            ->sum('amount');

        return view('passenger.dashboard', compact('passenger', 'recentBookings', 'totalSpent'));
    }

    public function profile()
    {
        return view('passenger.profile', ['passenger' => $this->passenger()]);
    }

    public function updateProfile(Request $request)
    {
        $passenger = $this->passenger();

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'phone'   => ['required', 'string', 'max:15'],
            'nic'     => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        $passenger->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $passenger = $this->passenger();

        // Delete old avatar file if it exists
        if ($passenger->avatar && \Storage::disk('public')->exists($passenger->avatar)) {
            \Storage::disk('public')->delete($passenger->avatar);
        }

        // Store new avatar — generates a unique filename
        $filename = 'avatars/' . Str::uuid() . '.' . $request->file('avatar')->getClientOriginalExtension();
        $request->file('avatar')->storeAs('', $filename, 'public');

        // Save only the path to the database
        $passenger->update(['avatar' => $filename]);

        return back()->with('success', 'Profile photo updated.');
    }

    public function bookings()
    {
        $bookings = Booking::where('passenger_id', $this->passenger()->id)
            ->with(['schedule.route', 'schedule.bus', 'seat'])
            ->latest()
            ->paginate(10);

        return view('passenger.bookings', compact('bookings'));
    }
}