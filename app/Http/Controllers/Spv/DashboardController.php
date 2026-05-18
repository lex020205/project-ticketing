<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('spv.dashboard');
    }
}
