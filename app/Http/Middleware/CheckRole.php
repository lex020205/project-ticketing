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
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Get user's role name
        $userRole = auth()->user()->role?->nama_role;

        // If user's role doesn't match any of the required roles, redirect to their dashboard
        // Allow Super Admin to access any role-restricted route
        $requiredRoles = array_map('trim', $roles);
        if ($userRole !== 'Super Admin' && !in_array($userRole, $requiredRoles, true)) {
            return RoleRedirectHelper::redirectByRole($userRole);
        }

        return $next($request);
    }
}

