<?php

namespace App\Http\Controllers\Spv;

use App\Http\Controllers\Controller;
use App\Models\KategoriMasalah;
use Illuminate\Http\Request;

class KategoriMasalahController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriMasalah::query()->withCount(['keluhans', 'tickets']);

        $query->when($request->filled('status_kategori'), function ($builder) use ($request) {
            $builder->where('status_kategori', $request->status_kategori);
        });

        $query->when($request->filled('keyword'), function ($builder) use ($request) {
            $keyword = trim($request->keyword);

            $builder->where(function ($subQuery) use ($keyword) {
                $subQuery->where('nama_kategori', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        });

        $kategoriList = $query->orderBy('nama_kategori')->get();

        return view('spv.kategori.index', compact('kategoriList'));
    }

    public function create()
    {
        return view('spv.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_masalah,nama_kategori',
            'deskripsi' => 'nullable|string',
            'status_kategori' => 'required|in:aktif,nonaktif',
        ]);

        KategoriMasalah::create($validated);

        return redirect()
            ->route('spv.kategori.index')
            ->with('success', 'Kategori masalah berhasil ditambahkan.');
    }

    public function show(KategoriMasalah $kategori)
    {
        $kategori->loadCount(['keluhans', 'tickets']);

        $latestTickets = $kategori->tickets()
            ->with(['keluhan', 'teknisi'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('spv.kategori.show', compact('kategori', 'latestTickets'));
    }

    public function edit(KategoriMasalah $kategori)
    {
        return view('spv.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriMasalah $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_masalah,nama_kategori,' . $kategori->id,
            'deskripsi' => 'nullable|string',
            'status_kategori' => 'required|in:aktif,nonaktif',
        ]);

        $kategori->update($validated);

        return redirect()
            ->route('spv.kategori.index')
            ->with('success', 'Kategori masalah berhasil diperbarui.');
    }

    public function toggleStatus(KategoriMasalah $kategori)
    {
        $kategori->update([
            'status_kategori' => $kategori->status_kategori === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return back()->with('success', 'Status kategori berhasil diperbarui.');
    }
}
