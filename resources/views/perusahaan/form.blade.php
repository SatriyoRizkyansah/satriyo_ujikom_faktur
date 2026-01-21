@csrf
@php($perusahaanData = optional($perusahaan ?? null))
<div class="form-grid">
    <div class="form-group">
        <label>Nama Perusahaan</label>
        <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $perusahaanData->nama_perusahaan) }}" required>
    </div>
    <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat" rows="3" required>{{ old('alamat', $perusahaanData->alamat) }}</textarea>
    </div>
    <div class="form-group">
        <label>No. Telp</label>
        <input type="text" name="no_telp" value="{{ old('no_telp', $perusahaanData->no_telp) }}">
    </div>
    <div class="form-group">
        <label>Fax</label>
        <input type="text" name="fax" value="{{ old('fax', $perusahaanData->fax) }}">
    </div>
</div>
<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn secondary" href="{{ route('perusahaan.index') }}">Batal</a>
</div>
