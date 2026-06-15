<?php

namespace App\Http\Middleware;

use App\Helpers\RoleRedirectHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Modul 1 - Auth, Role Access, dan Dashboard Awal
// Ringkas: pembatasan akses berdasarkan role.
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Get user's role name
        $userRole = auth()->user()->role?->nama_role;

        // If user's role doesn't match the required role, redirect to their dashboard
        if ($userRole !== $role) {
            // Redirect ke dashboard sesuai role mereka
            return RoleRedirectHelper::redirectByRole($userRole);
        }

        return $next($request);
    }
}

