<?php

namespace App\Http\Controllers;

use App\Helpers\RoleRedirectHelper;
use Illuminate\Http\Request;

// Modul 1 - Auth, Role Access, dan Dashboard Awal
// Ringkas: redirect dashboard sesuai role user.
class DashboardController extends Controller
{
    /**
     * Redirect user to their respective dashboard based on their role
     */
    public function redirect()
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Redirect based on user's role
        return RoleRedirectHelper::redirectByRole($user->role?->nama_role);
    }
}

