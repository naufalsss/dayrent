<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsMerchant
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login dan rolenya adalah merchant atau admin
        if (auth()->check() && (auth()->user()->role === 'merchant' || auth()->user()->role === 'admin')) {
            return $next($request);
        }

        // Jika bukan merchant, lempar kembali ke halaman depan dengan pesan error
        return redirect('/')->with('error', 'Akses ditolak. Anda bukan Merchant!');
    }
}