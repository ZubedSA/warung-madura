<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($role === 'penjaga' && !$user->isPenjaga()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Penjaga Warung.');
        }

        if ($role === 'pemilik' && !$user->isPemilik()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Pemilik Warung.');
        }

        return $next($request);
    }
}
