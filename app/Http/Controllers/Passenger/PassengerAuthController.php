<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PassengerAuthController extends Controller
{
    // Show the unified register/apply page
    public function showRegister()
    {
        return view('public.register');
    }

    // Handle passenger self-registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:passengers,email'],
            'phone'                 => ['required', 'string', 'max:15'],
            'nic'                   => ['nullable', 'string', 'max:20'],
            'address'               => ['nullable', 'string'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $passenger = Passenger::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'nic'      => $validated['nic'] ?? null,
            'address'  => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::guard('passenger')->login($passenger);

        return redirect()->route('passenger.dashboard')
            ->with('success', 'Welcome! Your passenger account has been created.');
    }

    // Show passenger login page
    public function showLogin()
    {
        return view('public.passenger-login');
    }

    // Handle passenger login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('passenger')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('passenger.dashboard'));
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Passenger logout
    public function logout(Request $request)
    {
        Auth::guard('passenger')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}