@csrf
@php($produkData = optional($produk ?? null))
<div class="form-grid">
    <div class="form-group">
        <label>Nama Produk</label>
        <input type="text" name="nama_produk" value="{{ old('nama_produk', $produkData->nama_produk) }}" required>
    </div>
    <div class="form-group">
        <label>Jenis</label>
        <input type="text" name="jenis" value="{{ old('jenis', $produkData->jenis) }}">
    </div>
    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $produkData->price ?? 0) }}" required>
    </div>
    <div class="form-group">
        <label>Stok</label>
        <input type="number" name="stock" min="0" value="{{ old('stock', $produkData->stock ?? 0) }}" required>
    </div>
</div>
<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn secondary" href="{{ route('produk.index') }}">Batal</a>
</div>
