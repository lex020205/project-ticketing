<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use App\Models\KategoriMasalah;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KeluhanController extends Controller
{
    public function index()
    {
        $keluhans = Keluhan::with('kategori')
            ->orderByDesc('tanggal_keluhan')
            ->orderByDesc('id')
            ->get();

        return view('admin.keluhan.index', compact('keluhans'));
    }

    public function create()
    {
        $kategoriList = KategoriMasalah::where('status_kategori', 'aktif')
            ->orderBy('nama_kategori')
            ->get();

        return view('admin.keluhan.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'jenis_pelapor' => 'required|in:dosen,mahasiswa,staff,panitia,lainnya',
            'kontak_pelapor' => 'nullable|string|max:255',
            'kategori_id' => 'required|exists:kategori_masalah,id',
            'lokasi_keluhan' => 'nullable|string|max:255',
            'detail_lokasi' => 'nullable|string|max:255',
            'deskripsi_keluhan' => 'required|string',
            'prioritas_awal' => 'required|in:rendah,sedang,tinggi,darurat',
            'tanggal_keluhan' => 'required|date',
        ]);

        $validated['kode_keluhan'] = $this->generateKodeKeluhan();
        $validated['status_keluhan'] = 'baru';
        $validated['created_by'] = auth()->id();

        $keluhan = Keluhan::create($validated);

        return redirect()
            ->route('admin.keluhan.show', $keluhan)
            ->with('success', 'Keluhan berhasil disimpan.');
    }

    public function show(Keluhan $keluhan)
    {
        $keluhan->load(['kategori', 'pembuat', 'ticket']);

        return view('admin.keluhan.show', compact('keluhan'));
    }

    public function edit(Keluhan $keluhan)
    {
        $kategoriList = KategoriMasalah::where('status_kategori', 'aktif')
            ->orderBy('nama_kategori')
            ->get();

        return view('admin.keluhan.edit', compact('keluhan', 'kategoriList'));
    }

    public function update(Request $request, Keluhan $keluhan)
    {
        $validated = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'jenis_pelapor' => 'required|in:dosen,mahasiswa,staff,panitia,lainnya',
            'kontak_pelapor' => 'nullable|string|max:255',
            'kategori_id' => 'required|exists:kategori_masalah,id',
            'lokasi_keluhan' => 'nullable|string|max:255',
            'detail_lokasi' => 'nullable|string|max:255',
            'deskripsi_keluhan' => 'required|string',
            'prioritas_awal' => 'required|in:rendah,sedang,tinggi,darurat',
            'tanggal_keluhan' => 'required|date',
        ]);

        $keluhan->update($validated);

        return redirect()
            ->route('admin.keluhan.show', $keluhan)
            ->with('success', 'Keluhan berhasil diperbarui.');
    }

    public function validasi(Keluhan $keluhan)
    {
        $keluhan->update(['status_keluhan' => 'valid']);

        return redirect()
            ->route('admin.keluhan.show', $keluhan)
            ->with('success', 'Keluhan berhasil divalidasi.');
    }

    public function tidakValid(Keluhan $keluhan)
    {
        $keluhan->update(['status_keluhan' => 'tidak_valid']);

        return redirect()
            ->route('admin.keluhan.show', $keluhan)
            ->with('success', 'Keluhan ditandai tidak valid.');
    }

    private function generateKodeKeluhan(): string
    {
        $latest = Keluhan::orderByDesc('id')->value('kode_keluhan');

        if (!$latest) {
            return 'KLH-0001';
        }

        $number = (int) Str::after($latest, 'KLH-');
        $next = $number + 1;

        return 'KLH-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
