<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriMasalah;
use App\Models\Keluhan;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['keluhan', 'kategori', 'teknisi'])
            ->orderByDesc('tanggal_ditugaskan')
            ->orderByDesc('id')
            ->get();

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['keluhan', 'kategori', 'teknisi', 'assignedBy']);

        return view('admin.tickets.show', compact('ticket'));
    }

    public function createFromKeluhan(Keluhan $keluhan)
    {
        $keluhan->load(['kategori', 'ticket']);

        if ($keluhan->status_keluhan !== 'valid') {
            return redirect()
                ->route('admin.keluhan.show', $keluhan)
                ->with('error', 'Keluhan harus berstatus valid untuk dibuat ticket.');
        }

        if ($keluhan->ticket) {
            return redirect()
                ->route('admin.keluhan.show', $keluhan)
                ->with('error', 'Keluhan ini sudah dibuat menjadi ticket.');
        }

        $kategoriList = KategoriMasalah::where('status_kategori', 'aktif')
            ->orderBy('nama_kategori')
            ->get();

        $teknisiList = User::where('status_user', 'aktif')
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'Teknisi');
            })
            ->orderBy('name')
            ->get();

        return view('admin.tickets.create', compact('keluhan', 'kategoriList', 'teknisiList'));
    }

    public function storeFromKeluhan(Request $request, Keluhan $keluhan)
    {
        $keluhan->load('ticket');

        if ($keluhan->status_keluhan !== 'valid') {
            return redirect()
                ->route('admin.keluhan.show', $keluhan)
                ->with('error', 'Keluhan harus berstatus valid untuk dibuat ticket.');
        }

        if ($keluhan->ticket) {
            return redirect()
                ->route('admin.keluhan.show', $keluhan)
                ->with('error', 'Keluhan ini sudah dibuat menjadi ticket.');
        }

        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_masalah,id',
            'prioritas' => 'required|in:rendah,sedang,tinggi,darurat',
            'teknisi_id' => 'nullable|exists:users,id',
        ]);

        if (!empty($validated['teknisi_id'])) {
            $isTeknisi = User::where('id', $validated['teknisi_id'])
                ->whereHas('role', function ($query) {
                    $query->where('nama_role', 'Teknisi');
                })
                ->exists();

            if (!$isTeknisi) {
                return back()
                    ->withInput()
                    ->with('error', 'Teknisi yang dipilih tidak valid.');
            }
        }

        $ticket = DB::transaction(function () use ($validated, $keluhan) {
            $teknisiId = $validated['teknisi_id'] ?? null;
            $statusTicket = $teknisiId ? 'ditugaskan' : 'menunggu_penugasan';

            $ticket = Ticket::create([
                'kode_ticket' => $this->generateKodeTicket(),
                'keluhan_id' => $keluhan->id,
                'kategori_id' => $validated['kategori_id'],
                'teknisi_id' => $teknisiId,
                'assigned_by' => auth()->id(),
                'verified_by' => null,
                'prioritas' => $validated['prioritas'],
                'status_ticket' => $statusTicket,
                'tanggal_ditugaskan' => $teknisiId ? now() : null,
                'tanggal_mulai' => null,
                'tanggal_selesai' => null,
                'tanggal_ditutup' => null,
            ]);

            $keluhan->update(['status_keluhan' => 'menjadi_ticket']);

            return $ticket;
        });

        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket berhasil dibuat.');
    }

    private function generateKodeTicket(): string
    {
        $latest = Ticket::orderByDesc('id')->value('kode_ticket');

        if (!$latest) {
            return 'TCK-0001';
        }

        $number = (int) Str::after($latest, 'TCK-');
        $next = $number + 1;

        return 'TCK-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
