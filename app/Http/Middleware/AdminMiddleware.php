<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        if (!in_array($user->role, ['admin', 'staff'])) {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Unauthorized access.');
        }

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Account is deactivated.');
        }

        return $next($request);
    }
}
