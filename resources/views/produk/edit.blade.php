@extends('layouts.app')

@section('title', 'Ubah Produk')

@section('content')
    <h2>Ubah Produk</h2>
    <form action="{{ route('produk.update', $produk) }}" method="POST">
        @method('PUT')
        @include('produk.form', ['produk' => $produk])
    </form>
@endsection
