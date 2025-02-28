<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Cek apakah user sudah login dan memiliki role yang sesuai
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // Jika tidak, kembalikan respon Unauthorized
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
