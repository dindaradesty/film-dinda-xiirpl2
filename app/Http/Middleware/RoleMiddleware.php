<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Batasi akses halaman berdasarkan role user.
     *
     * Bisa dicek lewat role_id (angka) maupun nama role:
     *   ->middleware('role:2')
     *   ->middleware('role:admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Belum login -> arahkan ke halaman login.
        if (! $user) {
            return redirect()->route('login');
        }

        $allowed = collect($roles)->contains(function (string $role) use ($user) {
            return (string) $user->role_id === $role
                || ($user->role && $user->role->name === $role);
        });

        abort_unless($allowed, 403, 'Anda tidak memiliki hak akses ke halaman ini.');

        return $next($request);
    }
}
