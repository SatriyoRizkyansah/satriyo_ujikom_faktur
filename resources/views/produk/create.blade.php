@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <h2>Tambah Produk</h2>
    <form action="{{ route('produk.store') }}" method="POST">
        @include('produk.form', ['produk' => null])
    </form>
@endsection
