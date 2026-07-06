<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Schema;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Schema::hasTable('system_settings')
            ? SystemSetting::orderBy('key')->get()
            : collect();

        $tableReady = Schema::hasTable('system_settings');

        return view('super-admin.settings.index', compact('settings', 'tableReady'));
    }
}
