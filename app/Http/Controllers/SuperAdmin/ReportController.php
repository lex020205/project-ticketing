<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        return view('super-admin.laporan.index');
    }
}
