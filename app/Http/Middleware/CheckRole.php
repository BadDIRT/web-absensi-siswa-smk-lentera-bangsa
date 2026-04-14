<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Middleware\Middleware;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Middleware untuk membatasi akses berdasarkan role user.
     * Penggunaan di route: ->middleware('role:administrator,scanner')
     * Artinya: user harus memiliki salah satu role tersebut.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Pastikan user sudah login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Defensive: jika tidak ada role yang didefinisikan di route, tolak akses
        if (empty($roles)) {
            abort(403, 'Middleware role tidak dikonfigurasi dengan benar.');
        }

        // Cek apakah role user termasuk dalam daftar yang diizinkan
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
