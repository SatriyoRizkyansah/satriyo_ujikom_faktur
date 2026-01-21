@extends('layouts.app')

@section('title', 'Preview Faktur')

@section('content')
    <div class="content-header">
        <h2>Preview Faktur #{{ $faktur->no_faktur }}</h2>
        <button class="btn" onclick="window.print()">Cetak Faktur</button>
    </div>

    <div class="card-grid">
        <article class="card">
            <h3>Informasi Penjualan</h3>
            <p><strong>Tanggal:</strong> {{ $faktur->tgl_faktur->format('d M Y') }}</p>
            <p><strong>Jatuh Tempo:</strong> {{ $faktur->due_date->format('d M Y') }}</p>
            <p><strong>Metode Bayar:</strong> {{ $faktur->metode_bayar }}</p>
            <p><strong>User:</strong> {{ $faktur->user }}</p>
        </article>
        <article class="card">
            <h3>Customer</h3>
            <p><strong>Nama:</strong> {{ $faktur->customer->nama_customer }}</p>
            <p><strong>Alamat:</strong> {{ $faktur->customer->alamat }}</p>
        </article>
        <article class="card">
            <h3>Perusahaan</h3>
            <p><strong>Nama:</strong> {{ $faktur->perusahaan->nama_perusahaan }}</p>
            <p><strong>Alamat:</strong> {{ $faktur->perusahaan->alamat }}</p>
            <p><strong>Kontak:</strong> {{ $faktur->perusahaan->no_telp ?? '-' }}</p>
        </article>
    </div>

    <h3>Detail Produk</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($faktur->detailFaktur as $detail)
                <tr>
                    <td>{{ $detail->produk->nama_produk }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->qty * $detail->price, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('detail-faktur.destroy', [$faktur, $detail->id_produk]) }}" method="POST" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button class="btn danger" onclick="return confirm('Hapus baris produk ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada detail produk.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">PPN ({{ $faktur->ppn }}%)</th>
                <td colspan="2">Rp {{ number_format($faktur->detailFaktur->sum(fn($d) => $d->qty * $d->price) * ($faktur->ppn / 100), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="3">DP</th>
                <td colspan="2">Rp {{ number_format($faktur->dp ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="3">Grand Total</th>
                <td colspan="2"><strong>Rp {{ number_format($faktur->grand_total, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <h3>Tambah / Update Detail Produk</h3>
    <form action="{{ route('detail-faktur.store', $faktur) }}" method="POST" class="detail-row form-stack">
        @csrf
        <div class="form-group">
            <label>Produk</label>
            <select name="id_produk" required>
                <option value="">-- Pilih Produk --</option>
                @foreach ($produk as $item)
                    <option value="{{ $item->id_produk }}">{{ $item->nama_produk }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Qty</label>
            <input type="number" name="qty" min="1" value="1" required>
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="number" step="0.01" min="0" name="price" required>
        </div>
        <button class="btn" type="submit">Simpan Detail</button>
    </form>
@endsection
