@extends('layouts.app')

@section('title', 'Preview Data Customer')

@section('content')
    <h2>Preview / Cetak Data Customer</h2>
    <p>Gunakan halaman ini sebagai ringkasan sebelum mencetak data customer.</p>

    <div class="card-grid">
        @forelse ($customers as $customer)
            <article class="card">
                <h3>{{ $customer->nama_customer }}</h3>
                <p><strong>Perusahaan:</strong> {{ $customer->perusahaan_cust ?? '-' }}</p>
                <p><strong>Alamat:</strong> {{ $customer->alamat }}</p>
            </article>
        @empty
            <p>Belum ada data customer untuk ditampilkan.</p>
        @endforelse
    </div>

    <button class="btn" onclick="window.print()">Cetak Halaman</button>
@endsection
