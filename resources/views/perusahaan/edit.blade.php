@extends('layouts.app')

@section('title', 'Ubah Data Perusahaan')

@section('content')
    <h2>Ubah Data Perusahaan</h2>
    <form action="{{ route('perusahaan.update', $perusahaan) }}" method="POST">
        @method('PUT')
        @include('perusahaan.form', ['perusahaan' => $perusahaan])
    </form>
@endsection
