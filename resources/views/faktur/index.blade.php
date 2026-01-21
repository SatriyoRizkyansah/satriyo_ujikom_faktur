@extends('layouts.app')

@section('title', 'Kelola Data Penjualan')

@section('content')
    <div class="content-header">
        <h2>Kelola Data Penjualan</h2>
        <a class="btn" href="{{ route('faktur.create') }}">Tambah Penjualan</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No. Faktur</th>
                <th>Tgl Faktur</th>
                <th>Customer</th>
                <th>Perusahaan</th>
                <th>Grand Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($faktur as $item)
                <tr>
                    <td>{{ $item->no_faktur }}</td>
                    <td>{{ $item->tgl_faktur->format('d/m/Y') }}</td>
                    <td>{{ $item->customer->nama_customer }}</td>
                    <td>{{ $item->perusahaan->nama_perusahaan }}</td>
                    <td>Rp {{ number_format($item->grand_total, 0, ',', '.') }}</td>
                    <td>
                        <div class="table-actions">
                            <a class="btn block" href="{{ route('faktur.show', $item) }}">Preview / Cetak</a>
                            <div class="action-row">
                                <a class="btn ghost" href="{{ route('faktur.edit', $item) }}">Ubah</a>
                                <form action="{{ route('faktur.destroy', $item) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger" onclick="return confirm('Hapus data penjualan ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada data penjualan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
