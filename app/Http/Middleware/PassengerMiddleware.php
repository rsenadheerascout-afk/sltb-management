<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassengerMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (!Auth::guard('passenger')->check()) {
            return redirect()->route('passenger.login')
                ->with('error', 'Please log in to continue.');
        }

        return $next($request);
    }
}