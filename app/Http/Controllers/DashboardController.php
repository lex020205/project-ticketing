<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        // Get user's role name
        $roleName = $user->role?->nama_role;

        switch ($roleName) {
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
}
