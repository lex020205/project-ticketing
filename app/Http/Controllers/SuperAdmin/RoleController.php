<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('nama_role')->get();
        return view('super-admin.roles.index', compact('roles'));
    }
}
