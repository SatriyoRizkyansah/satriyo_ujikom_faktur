@extends('layouts.app')

@section('title', 'Ubah Customer')

@section('content')
    <h2>Ubah Data Customer</h2>
    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @method('PUT')
        @include('customers.form', ['customer' => $customer])
    </form>
@endsection
