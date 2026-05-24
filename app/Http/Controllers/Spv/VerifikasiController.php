<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketProgress;
use App\Models\TicketVerifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['keluhan', 'kategori', 'teknisi'])
            ->where('status_ticket', 'menunggu_verifikasi')
            ->orderByDesc('tanggal_selesai')
            ->orderByDesc('id')
            ->get();

        return view('spv.verifikasi.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['keluhan', 'kategori', 'teknisi']);

        $progressList = $ticket->progress()
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        $lampiranList = $ticket->lampiran()
            ->with('uploader')
            ->orderByDesc('created_at')
            ->get();

        return view('spv.verifikasi.show', compact('ticket', 'progressList', 'lampiranList'));
    }

    public function verifikasi(Request $request, Ticket $ticket)
    {
        if ($ticket->status_ticket !== 'menunggu_verifikasi') {
            return redirect()
                ->route('spv.verifikasi.show', $ticket)
                ->with('error', 'Ticket belum siap diverifikasi.');
        }

        $validated = $request->validate([
            'hasil_verifikasi' => 'required|in:diterima,dikembalikan',
            'catatan_verifikasi' => 'nullable|string',
        ]);

        if ($validated['hasil_verifikasi'] === 'dikembalikan') {
            $request->validate([
                'catatan_verifikasi' => 'required|string|min:10',
            ]);
        }

        $statusSebelumnya = $ticket->status_ticket;

        DB::transaction(function () use ($ticket, $validated, $statusSebelumnya) {
            TicketVerifikasi::create([
                'ticket_id' => $ticket->id,
                'verified_by' => auth()->id(),
                'hasil_verifikasi' => $validated['hasil_verifikasi'],
                'catatan_verifikasi' => $validated['catatan_verifikasi'] ?? null,
                'tanggal_verifikasi' => now(),
            ]);

            if ($validated['hasil_verifikasi'] === 'diterima') {
                $ticket->update([
                    'status_ticket' => 'ditutup',
                    'verified_by' => auth()->id(),
                    'tanggal_ditutup' => now(),
                ]);

                TicketProgress::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => auth()->id(),
                    'catatan' => 'Ticket diverifikasi dan diterima.',
                    'status_sebelumnya' => $statusSebelumnya,
                    'status_baru' => 'ditutup',
                ]);
            } else {
                $ticket->update([
                    'status_ticket' => 'sedang_dikerjakan',
                    'verified_by' => auth()->id(),
                    'tanggal_ditutup' => null,
                ]);

                $catatan = $validated['catatan_verifikasi'] ?? '';
                TicketProgress::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => auth()->id(),
                    'catatan' => 'Ticket dikembalikan ke teknisi. Catatan: ' . $catatan,
                    'status_sebelumnya' => $statusSebelumnya,
                    'status_baru' => 'sedang_dikerjakan',
                ]);
            }
        });

        return redirect()
            ->route('spv.verifikasi.index')
            ->with('success', 'Verifikasi ticket berhasil disimpan.');
    }
}
