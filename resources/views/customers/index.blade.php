@extends('layouts.app')

@section('title', 'Kelola Data Customer')

@section('content')
    <div class="content-header">
        <h2>Kelola Data Customer</h2>
        <a class="btn" href="{{ route('customers.create') }}">Tambah Customer</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Perusahaan</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $customer)
                <tr>
                    <td>{{ $customer->id_customer }}</td>
                    <td>{{ $customer->nama_customer }}</td>
                    <td>{{ $customer->perusahaan_cust ?? '-' }}</td>
                    <td>{{ $customer->alamat }}</td>
                    <td>
                        <a class="btn" href="{{ route('customers.edit', $customer) }}">Ubah</a>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button class="btn secondary" onclick="return confirm('Hapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
