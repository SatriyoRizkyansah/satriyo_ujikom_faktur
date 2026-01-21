@extends('layouts.app')

@section('title', 'Kelola Data Produk')

@section('content')
    <div class="content-header">
        <h2>Data Produk</h2>
        <a class="btn" href="{{ route('produk.create') }}">Tambah Produk</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($produk as $item)
                <tr>
                    <td>{{ $item->id_produk }}</td>
                    <td>{{ $item->nama_produk }}</td>
                    <td>{{ $item->jenis ?? '-' }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->stock }}</td>
                    <td>
                        <a class="btn" href="{{ route('produk.edit', $item) }}">Ubah</a>
                        <form action="{{ route('produk.destroy', $item) }}" method="POST" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button class="btn secondary" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
