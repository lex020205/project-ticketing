<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;
use App\Models\TicketEskalasi;
use App\Models\TicketProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Modul 7 - SPV Keputusan Eskalasi
// Ringkas: daftar eskalasi dan keputusan SPV.
class EskalasiController extends Controller
{
    public function index()
    {
        $eskalasiList = TicketEskalasi::with([
                'ticket.keluhan',
                'ticket.kategori',
                'ticket.teknisi',
                'pengaju',
            ])
            ->where('status_eskalasi', 'menunggu')
            ->orderByDesc('created_at')
            ->get();

        return view('spv.eskalasi.index', compact('eskalasiList'));
    }

    public function show(TicketEskalasi $eskalasi)
    {
        $eskalasi->load([
            'ticket.keluhan',
            'ticket.kategori',
            'ticket.teknisi',
            'pengaju',
            'pemutus',
        ]);

        $progressList = $eskalasi->ticket
            ? $eskalasi->ticket->progress()->with('user')->orderByDesc('created_at')->get()
            : collect();

        $teknisiList = [];
        if ($eskalasi->status_eskalasi === 'menunggu') {
            $teknisiList = User::where('status_user', 'aktif')
                ->whereHas('role', function ($query) {
                    $query->where('nama_role', 'Teknisi');
                })
                ->orderBy('name')
                ->get();
        }

        return view('spv.eskalasi.show', compact('eskalasi', 'progressList', 'teknisiList'));
    }

    public function keputusan(Request $request, TicketEskalasi $eskalasi)
    {
        if ($eskalasi->status_eskalasi !== 'menunggu') {
            return redirect()
                ->route('spv.eskalasi.show', $eskalasi)
                ->with('error', 'Eskalasi ini sudah diproses.');
        }

        $validated = $request->validate([
            'keputusan_status' => 'required|in:disetujui,ditolak,selesai',
            'status_ticket_baru' => 'required|in:sedang_dikerjakan,menunggu_alat,ditugaskan,dibatalkan',
            'teknisi_baru_id' => 'nullable|exists:users,id',
            'catatan_keputusan' => 'required|string|min:10',
        ]);

        if (!empty($validated['teknisi_baru_id'])) {
            $isTeknisi = User::where('id', $validated['teknisi_baru_id'])
                ->where('status_user', 'aktif')
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

        $ticket = $eskalasi->ticket;
        $statusSebelumnya = $ticket?->status_ticket ?? null;

        DB::transaction(function () use ($eskalasi, $validated, $ticket, $statusSebelumnya) {
            $eskalasi->update([
                'status_eskalasi' => $validated['keputusan_status'],
                'diputuskan_oleh' => auth()->id(),
                'keputusan' => $validated['catatan_keputusan'],
            ]);

            if ($ticket) {
                $updateTicket = [
                    'status_ticket' => $validated['status_ticket_baru'],
                ];

                if (!empty($validated['teknisi_baru_id'])) {
                    $updateTicket['teknisi_id'] = $validated['teknisi_baru_id'];
                    $updateTicket['tanggal_ditugaskan'] = now();
                }

                $ticket->update($updateTicket);

                TicketProgress::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => auth()->id(),
                    'catatan' => 'SPV memberi keputusan eskalasi: ' . $validated['keputusan_status'] . '. Catatan: ' . $validated['catatan_keputusan'],
                    'status_sebelumnya' => $statusSebelumnya,
                    'status_baru' => $validated['status_ticket_baru'],
                ]);
            }
        });

        return redirect()
            ->route('spv.eskalasi.index')
            ->with('success', 'Keputusan eskalasi berhasil disimpan.');
    }
}
