@extends('layouts.app')

@section('title', 'Tambah Data Perusahaan')

@section('content')
    <h2>Tambah Data Perusahaan</h2>
    <form action="{{ route('perusahaan.store') }}" method="POST">
        @include('perusahaan.form', ['perusahaan' => null])
    </form>
@endsection
