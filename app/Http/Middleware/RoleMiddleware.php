<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini menerima satu atau lebih role yang diizinkan.
     * Contoh penggunaan di route:
     *   ->middleware('role:kabid')
     *   ->middleware('role:kabid,staff')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // Konversi string role dari parameter ke array UserRole enum values
        $allowedRoles = array_map(
            fn(string $role) => UserRole::tryFrom(trim($role)),
            $roles
        );

        // Cek apakah role user termasuk dalam daftar yang diizinkan
        if (!in_array($user->role, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
