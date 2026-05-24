@php
    $formKeluhan = $keluhan ?? null;
    $tanggalValue = old(
        'tanggal_keluhan',
        $formKeluhan?->tanggal_keluhan
            ? \Illuminate\Support\Carbon::parse($formKeluhan->tanggal_keluhan)->format('Y-m-d')
            : now()->format('Y-m-d')
    );
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label for="nama_pelapor" class="form-label">Nama Pelapor</label>
        <input type="text" name="nama_pelapor" id="nama_pelapor" class="form-control" value="{{ old('nama_pelapor', $formKeluhan?->nama_pelapor) }}" required>
        @error('nama_pelapor')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="jenis_pelapor" class="form-label">Jenis Pelapor</label>
        <select name="jenis_pelapor" id="jenis_pelapor" class="form-select" required>
            <option value="">Pilih jenis pelapor</option>
            @foreach (['dosen', 'mahasiswa', 'staff', 'panitia', 'lainnya'] as $jenis)
                <option value="{{ $jenis }}" @selected(old('jenis_pelapor', $formKeluhan?->jenis_pelapor) === $jenis)>
                    {{ ucfirst($jenis) }}
                </option>
            @endforeach
        </select>
        @error('jenis_pelapor')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="kontak_pelapor" class="form-label">Kontak Pelapor</label>
        <input type="text" name="kontak_pelapor" id="kontak_pelapor" class="form-control" value="{{ old('kontak_pelapor', $formKeluhan?->kontak_pelapor) }}">
        @error('kontak_pelapor')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="kategori_id" class="form-label">Kategori Masalah</label>
        <select name="kategori_id" id="kategori_id" class="form-select" required>
            <option value="">Pilih kategori</option>
            @foreach ($kategoriList as $kategori)
                <option value="{{ $kategori->id }}" @selected((string) old('kategori_id', $formKeluhan?->kategori_id) === (string) $kategori->id)>
                    {{ $kategori->nama_kategori }}
                </option>
            @endforeach
        </select>
        @error('kategori_id')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="lokasi_keluhan" class="form-label">Lokasi Keluhan</label>
        <input type="text" name="lokasi_keluhan" id="lokasi_keluhan" class="form-control" value="{{ old('lokasi_keluhan', $formKeluhan?->lokasi_keluhan) }}">
        @error('lokasi_keluhan')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="detail_lokasi" class="form-label">Detail Lokasi</label>
        <input type="text" name="detail_lokasi" id="detail_lokasi" class="form-control" value="{{ old('detail_lokasi', $formKeluhan?->detail_lokasi) }}">
        @error('detail_lokasi')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="deskripsi_keluhan" class="form-label">Deskripsi Keluhan</label>
        <textarea name="deskripsi_keluhan" id="deskripsi_keluhan" class="form-control" rows="4" required>{{ old('deskripsi_keluhan', $formKeluhan?->deskripsi_keluhan) }}</textarea>
        @error('deskripsi_keluhan')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="prioritas_awal" class="form-label">Prioritas Awal</label>
        <select name="prioritas_awal" id="prioritas_awal" class="form-select" required>
            <option value="">Pilih prioritas</option>
            @foreach (['rendah', 'sedang', 'tinggi', 'darurat'] as $prioritas)
                <option value="{{ $prioritas }}" @selected(old('prioritas_awal', $formKeluhan?->prioritas_awal) === $prioritas)>
                    {{ ucfirst($prioritas) }}
                </option>
            @endforeach
        </select>
        @error('prioritas_awal')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="tanggal_keluhan" class="form-label">Tanggal Keluhan</label>
        <input type="date" name="tanggal_keluhan" id="tanggal_keluhan" class="form-control" value="{{ $tanggalValue }}" required>
        @error('tanggal_keluhan')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
</div>
