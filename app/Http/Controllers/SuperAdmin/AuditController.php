<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class AuditController extends Controller
{
    public function index()
    {
        if (Schema::hasTable('activity_logs')) {
            $logs = ActivityLog::with('user')->latest()->paginate(20);
            $totalLogs = ActivityLog::count();
            $todayLogs = ActivityLog::whereDate('created_at', today())->count();
            $moduleBreakdown = ActivityLog::selectRaw('module, COUNT(*) as total')
                ->groupBy('module')
                ->orderByDesc('total')
                ->limit(5)
                ->get();
        } else {
            $logs = new LengthAwarePaginator([], 0, 20, 1, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
            $totalLogs = 0;
            $todayLogs = 0;
            $moduleBreakdown = collect();
        }

        return view('super-admin.audit.index', compact('logs', 'totalLogs', 'todayLogs', 'moduleBreakdown'));
    }
}
