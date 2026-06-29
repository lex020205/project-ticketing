<?php

namespace App\Helpers;

use Illuminate\Http\RedirectResponse;

/**
 * Helper statis untuk redirect user berdasarkan role.
 * Menghilangkan duplikasi logic redirect di LoginController, DashboardController, dan CheckRole.
 */
class RoleRedirectHelper
{
    /**
     * Redirect user ke dashboard sesuai role.
     */
    public static function redirectByRole(?string $roleName): RedirectResponse
    {
        return match ($roleName) {
            'Super Admin' => redirect()->route('super-admin.dashboard'),
            'Admin'   => redirect()->route('admin.dashboard'),
            'SPV'     => redirect()->route('spv.dashboard'),
            'Teknisi' => redirect()->route('teknisi.dashboard'),
            default   => redirect('/'),
        };
    }
}
