<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::orderBy('key')->get();
        return view('super-admin.settings.index', compact('settings'));
    }
}
