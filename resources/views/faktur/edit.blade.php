@extends('layouts.app')

@section('title', 'Ubah Data Penjualan')

@section('content')
    <h2>Ubah Data Penjualan</h2>
    <form action="{{ route('faktur.update', $faktur) }}" method="POST">
        @method('PUT')
        @include('faktur.form', ['faktur' => $faktur])
    </form>
@endsection
