@extends('layouts.app')

@section('title', 'Tambah Data Penjualan')

@section('content')
    <h2>Tambah Data Penjualan</h2>
    <form action="{{ route('faktur.store') }}" method="POST">
        @include('faktur.form')
    </form>
@endsection
