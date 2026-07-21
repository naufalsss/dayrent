<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsMerchant
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login dan rolenya murni hanya merchant
        if (auth()->check() && auth()->user()->role === 'merchant') {
            return $next($request);
        }

        // Jika bukan merchant, lempar kembali ke halaman depan dengan pesan error
        return redirect('/')->with('error', 'Akses ditolak. Anda bukan Merchant!');
    }
}