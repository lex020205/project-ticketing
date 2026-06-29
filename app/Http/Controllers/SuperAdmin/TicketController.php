<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['keluhan','kategori','teknisi'])->orderByDesc('id')->paginate(25);
        return view('super-admin.tickets.index', compact('tickets'));
    }
}
