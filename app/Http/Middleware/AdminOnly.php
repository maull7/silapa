<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        // Sesuaikan dengan role admin kamu (misalnya 0 = admin)
        if (auth()->check() && auth()->user()->role == 0) {
            return $next($request);
        }

        // Kalau bukan admin, tampilkan halaman 403
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
