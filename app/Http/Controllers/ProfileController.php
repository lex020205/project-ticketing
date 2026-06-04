<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $roleName = $user?->role?->nama_role;
        $dashboardRoute = match ($roleName) {
            'Admin' => 'admin.dashboard',
            'SPV' => 'spv.dashboard',
            'Teknisi' => 'teknisi.dashboard',
            default => 'dashboard',
        };

        return view('profile.show', compact('user', 'dashboardRoute'));
    }
}
