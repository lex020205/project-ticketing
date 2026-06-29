<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class AuditController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->orderByDesc('created_at')->paginate(50);
        return view('super-admin.audit.index', compact('logs'));
    }
}
