<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;
use App\Models\KategoriMasalah;
use App\Models\Ticket;
use App\Models\TicketProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['keluhan', 'kategori', 'teknisi']);

        $query->when($request->filled('status_ticket'), function ($builder) use ($request) {
            $builder->where('status_ticket', $request->status_ticket);
        });

        $query->when($request->filled('prioritas'), function ($builder) use ($request) {
            $builder->where('prioritas', $request->prioritas);
        });

        $query->when($request->filled('kategori_id'), function ($builder) use ($request) {
            $builder->where('kategori_id', $request->kategori_id);
        });

        $query->when($request->filled('teknisi_id'), function ($builder) use ($request) {
            $builder->where('teknisi_id', $request->teknisi_id);
        });

        $query->when($request->filled('keyword'), function ($builder) use ($request) {
            $keyword = trim($request->keyword);

            $builder->where(function ($subQuery) use ($keyword) {
                $subQuery->where('kode_ticket', 'like', "%{$keyword}%")
                    ->orWhereHas('keluhan', function ($keluhanQuery) use ($keyword) {
                        $keluhanQuery->where('kode_keluhan', 'like', "%{$keyword}%")
                            ->orWhere('nama_pelapor', 'like', "%{$keyword}%")
                            ->orWhere('lokasi_keluhan', 'like', "%{$keyword}%")
                            ->orWhere('deskripsi_keluhan', 'like', "%{$keyword}%");
                    });
            });
        });

        $tickets = $query->orderByDesc('id')->get();

        $kategoriList = KategoriMasalah::where('status_kategori', 'aktif')
            ->orderBy('nama_kategori')
            ->get();

        $teknisiList = User::where('status_user', 'aktif')
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'Teknisi');
            })
            ->orderBy('name')
            ->get();

        return view('spv.tickets.index', compact('tickets', 'kategoriList', 'teknisiList'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load([
            'keluhan',
            'kategori',
            'teknisi',
            'assignedBy',
            'verifiedBy',
            'progress.user',
            'lampiran.uploader',
            'eskalasi.pengaju',
            'eskalasi.pemutus',
            'verifikasi.verifier',
        ]);

        $teknisiList = User::where('status_user', 'aktif')
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'Teknisi');
            })
            ->orderBy('name')
            ->get();

        return view('spv.tickets.show', compact('ticket', 'teknisiList'));
    }

    public function updatePrioritas(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'prioritas' => 'required|in:rendah,sedang,tinggi,darurat',
        ]);

        $prioritasLama = $ticket->prioritas;

        DB::transaction(function () use ($ticket, $validated, $prioritasLama) {
            $ticket->update(['prioritas' => $validated['prioritas']]);

            TicketProgress::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'catatan' => 'SPV mengubah prioritas ticket dari ' . $prioritasLama . ' menjadi ' . $validated['prioritas'] . '.',
                'status_sebelumnya' => $ticket->status_ticket,
                'status_baru' => $ticket->status_ticket,
            ]);
        });

        return redirect()
            ->route('spv.tickets.show', $ticket)
            ->with('success', 'Prioritas ticket berhasil diperbarui.');
    }

    public function assignTeknisi(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'teknisi_id' => 'required|exists:users,id',
            'catatan_assign' => 'required|string|min:10',
        ]);

        $isTeknisi = User::where('id', $validated['teknisi_id'])
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

        $statusSebelumnya = $ticket->status_ticket;
        $statusBaru = $statusSebelumnya;

        if (in_array($statusSebelumnya, ['menunggu_penugasan', 'eskalasi'], true)) {
            $statusBaru = 'ditugaskan';
        }

        DB::transaction(function () use ($ticket, $validated, $statusSebelumnya, $statusBaru) {
            $ticket->update([
                'teknisi_id' => $validated['teknisi_id'],
                'assigned_by' => auth()->id(),
                'tanggal_ditugaskan' => now(),
                'status_ticket' => $statusBaru,
            ]);

            TicketProgress::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'catatan' => 'SPV melakukan assign ulang teknisi. Catatan: ' . $validated['catatan_assign'],
                'status_sebelumnya' => $statusSebelumnya,
                'status_baru' => $statusBaru,
            ]);
        });

        return redirect()
            ->route('spv.tickets.show', $ticket)
            ->with('success', 'Assign ulang teknisi berhasil disimpan.');
    }
}
