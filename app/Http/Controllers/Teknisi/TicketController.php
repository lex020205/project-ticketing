<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketEskalasi;
use App\Models\TicketLampiran;
use App\Models\TicketProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['keluhan', 'kategori'])
            ->where('teknisi_id', auth()->id())
            ->orderByDesc('tanggal_ditugaskan')
            ->orderByDesc('id')
            ->get();

        return view('teknisi.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        $ticket->load(['keluhan', 'kategori', 'teknisi']);
        $progressList = $ticket->progress()
            ->with('user')
            ->orderByDesc('created_at')
            ->get();
        $lampiranList = $ticket->lampiran()
            ->with('uploader')
            ->orderByDesc('created_at')
            ->get();
        $eskalasiList = $ticket->eskalasi()
            ->with(['pengaju', 'pemutus'])
            ->orderByDesc('created_at')
            ->get();

        return view('teknisi.tickets.show', compact('ticket', 'progressList', 'lampiranList', 'eskalasiList'));
    }

    public function mulai(Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        if ($ticket->status_ticket !== 'ditugaskan') {
            return redirect()
                ->route('teknisi.tickets.show', $ticket)
                ->with('error', 'Ticket tidak bisa dimulai dari status saat ini.');
        }

        DB::transaction(function () use ($ticket) {
            $ticket->update([
                'status_ticket' => 'sedang_dikerjakan',
                'tanggal_mulai' => $ticket->tanggal_mulai ?? now(),
            ]);

            TicketProgress::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'catatan' => 'Ticket mulai dikerjakan oleh teknisi.',
                'status_sebelumnya' => 'ditugaskan',
                'status_baru' => 'sedang_dikerjakan',
            ]);
        });

        return redirect()
            ->route('teknisi.tickets.show', $ticket)
            ->with('success', 'Ticket berhasil dimulai.');
    }

    public function storeProgress(Request $request, Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        if (!in_array($ticket->status_ticket, ['sedang_dikerjakan', 'menunggu_alat'], true)) {
            return redirect()
                ->route('teknisi.tickets.show', $ticket)
                ->with('error', 'Progress hanya bisa ditambahkan saat ticket sedang dikerjakan atau menunggu alat.');
        }

        $validated = $request->validate([
            'catatan' => 'required|string',
            'status_baru' => 'required|in:sedang_dikerjakan,menunggu_alat',
        ]);

        DB::transaction(function () use ($ticket, $validated) {
            TicketProgress::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'catatan' => $validated['catatan'],
                'status_sebelumnya' => $ticket->status_ticket,
                'status_baru' => $validated['status_baru'],
            ]);

            $ticket->update(['status_ticket' => $validated['status_baru']]);
        });

        return redirect()
            ->route('teknisi.tickets.show', $ticket)
            ->with('success', 'Progress berhasil disimpan.');
    }

    public function selesai(Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        if (!in_array($ticket->status_ticket, ['sedang_dikerjakan', 'menunggu_alat'], true)) {
            return redirect()
                ->route('teknisi.tickets.show', $ticket)
                ->with('error', 'Ticket tidak bisa ditandai selesai dari status saat ini.');
        }

        $statusSebelumnya = $ticket->status_ticket;

        DB::transaction(function () use ($ticket, $statusSebelumnya) {
            $ticket->update([
                'status_ticket' => 'menunggu_verifikasi',
                'tanggal_selesai' => now(),
            ]);

            TicketProgress::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'catatan' => 'Pekerjaan telah ditandai selesai dan menunggu verifikasi.',
                'status_sebelumnya' => $statusSebelumnya,
                'status_baru' => 'menunggu_verifikasi',
            ]);
        });

        return redirect()
            ->route('teknisi.tickets.show', $ticket)
            ->with('success', 'Ticket berhasil ditandai selesai.');
    }

    public function uploadLampiran(Request $request, Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        if (!in_array($ticket->status_ticket, ['sedang_dikerjakan', 'menunggu_alat', 'menunggu_verifikasi'], true)) {
            return redirect()
                ->route('teknisi.tickets.show', $ticket)
                ->with('error', 'Lampiran hanya bisa diupload saat ticket sedang dikerjakan, menunggu alat, atau menunggu verifikasi.');
        }

        $validated = $request->validate([
            'lampiran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $file = $validated['lampiran'];
        $path = $file->store('ticket-lampiran', 'public');

        DB::transaction(function () use ($ticket, $validated, $file, $path) {
            TicketLampiran::create([
                'ticket_id' => $ticket->id,
                'uploaded_by' => auth()->id(),
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            TicketProgress::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'catatan' => 'Teknisi mengupload lampiran bukti pengerjaan.',
                'status_sebelumnya' => $ticket->status_ticket,
                'status_baru' => $ticket->status_ticket,
            ]);
        });

        return redirect()
            ->route('teknisi.tickets.show', $ticket)
            ->with('success', 'Lampiran berhasil diupload.');
    }

    public function ajukanEskalasi(Request $request, Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        if (!in_array($ticket->status_ticket, ['sedang_dikerjakan', 'menunggu_alat'], true)) {
            return redirect()
                ->route('teknisi.tickets.show', $ticket)
                ->with('error', 'Eskalasi hanya dapat diajukan saat ticket sedang dikerjakan atau menunggu alat.');
        }

        $validated = $request->validate([
            'jenis_eskalasi' => 'required|in:butuh_bantuan,butuh_alat,dialihkan,keputusan_spv',
            'alasan' => 'required|string|min:10',
        ]);

        $statusSebelumnya = $ticket->status_ticket;

        DB::transaction(function () use ($ticket, $validated, $statusSebelumnya) {
            TicketEskalasi::create([
                'ticket_id' => $ticket->id,
                'diajukan_oleh' => auth()->id(),
                'jenis_eskalasi' => $validated['jenis_eskalasi'],
                'alasan' => $validated['alasan'],
                'status_eskalasi' => 'menunggu',
                'diputuskan_oleh' => null,
                'keputusan' => null,
            ]);

            $ticket->update(['status_ticket' => 'eskalasi']);

            TicketProgress::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'catatan' => 'Teknisi mengajukan eskalasi: ' . $validated['jenis_eskalasi'] . ' - ' . $validated['alasan'],
                'status_sebelumnya' => $statusSebelumnya,
                'status_baru' => 'eskalasi',
            ]);
        });

        return redirect()
            ->route('teknisi.tickets.show', $ticket)
            ->with('success', 'Eskalasi berhasil diajukan dan menunggu keputusan SPV.');
    }

    private function authorizeTicket(Ticket $ticket): void
    {
        if ((int) $ticket->teknisi_id !== (int) auth()->id()) {
            abort(403);
        }
    }
}
