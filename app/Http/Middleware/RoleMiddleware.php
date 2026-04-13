<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->status === 'disabled') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been disabled. Contact the depot manager.']);
        }

        if (!$user->is_approved) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is pending approval by the depot manager.']);
        }

        if (!empty($roles) && !in_array($user->role, $roles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}