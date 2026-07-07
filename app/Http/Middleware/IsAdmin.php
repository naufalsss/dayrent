<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login, dan apakah role-nya adalah 'admin'
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request); // Lolos, silakan masuk dashboard
        }

        // 2. Jika bukan admin, tendang ke beranda depan dengan pesan peringatan
        return redirect('/')->with('error', 'Akses ditolak! Anda bukan admin.');
    }
}