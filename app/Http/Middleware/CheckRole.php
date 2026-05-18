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
            switch ($userRole) {
                case 'Admin':
                    return redirect()->route('admin.dashboard');
                case 'SPV':
                    return redirect()->route('spv.dashboard');
                case 'Teknisi':
                    return redirect()->route('teknisi.dashboard');
                default:
                    return redirect('/');
            }
        }

        return $next($request);
    }
}
