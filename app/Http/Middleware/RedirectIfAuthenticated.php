<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = Auth::user()->role;

                return match ($role) {
                    'administrator' => redirect()->route('dashboard.admin'),
                    'scanner'       => redirect()->route('dashboard.scanner'),
                    'siswa'         => redirect()->route('dashboard.siswa'),
                    default         => redirect('/'),
                };
            }
        }

        return $next($request);
    }
}
