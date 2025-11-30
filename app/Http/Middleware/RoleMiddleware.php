<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user login dan apakah role-nya sesuai
        // Kita asumsikan di tabel auth_users ada kolom 'role'
        if (! $request->user() || $request->user()->role !== $role) {
            return response()->json(['message' => 'Unauthorized Access'], 403);
        }

        return $next($request);
    }
}