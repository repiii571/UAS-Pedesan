<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Menangani permintaan masuk.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login dan apakah rolenya ada dalam daftar yang diizinkan
        if (auth()->check() && in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        // Jika tidak punya akses, kembalikan ke halaman sebelumnya dengan pesan error
        return redirect('/')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
    }
}