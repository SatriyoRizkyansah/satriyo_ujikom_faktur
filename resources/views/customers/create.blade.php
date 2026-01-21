@extends('layouts.app')

@section('title', 'Tambah Customer')

@section('content')
    <h2>Tambah Data Customer</h2>
    <form action="{{ route('customers.store') }}" method="POST">
        @include('customers.form', ['customer' => null])
    </form>
@endsection
