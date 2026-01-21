@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <h2>Beranda / Menu Utama</h2>
    <p class="muted-text">Pilih salah satu kategori di bawah untuk mulai mengelola data.</p>

    <div class="card-grid">
        <article class="card">
            <h3>Kelola Data Perusahaan</h3>
            <ul>
                <li>Tampil daftar perusahaan</li>
                <li>Tambah / ubah data perusahaan</li>
                <li>Hapus data perusahaan</li>
            </ul>
            <a class="btn" href="{{ route('perusahaan.index') }}">Masuk</a>
        </article>

        <article class="card">
            <h3>Kelola Data Customer</h3>
            <ul>
                <li>Tampil daftar customer</li>
                <li>Tambah / ubah data customer</li>
                <li>Preview / cetak data customer</li>
            </ul>
            <a class="btn" href="{{ route('customers.index') }}">Masuk</a>
        </article>

        <article class="card">
            <h3>Kelola Data Produk</h3>
            <ul>
                <li>Tampil daftar produk</li>
                <li>Tambah / ubah data produk</li>
                <li>Atur stok dan harga</li>
            </ul>
            <a class="btn" href="{{ route('produk.index') }}">Masuk</a>
        </article>

        <article class="card">
            <h3>Kelola Data Penjualan</h3>
            <ul>
                <li>Tampil daftar penjualan</li>
                <li>Tambah / ubah data penjualan</li>
                <li>Cetak faktur</li>
            </ul>
            <a class="btn" href="{{ route('faktur.index') }}">Masuk</a>
        </article>
    </div>
@endsection
