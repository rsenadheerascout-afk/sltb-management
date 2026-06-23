<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureNotPassenger
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in with your employee account to access messages.');
        }

        return $next($request);
    }
}