@extends('layouts.app')

@section('title', 'Kelola Data Customer')

@section('content')
    <div class="content-header">
        <h2>Kelola Data Customer</h2>
        <div class="action-row">
            <a class="btn ghost" href="{{ route('customers.export') }}" target="_blank" rel="noopener">Export PDF</a>
            <a class="btn" href="{{ route('customers.create') }}">Tambah Customer</a>
        </div>
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
                        <div class="table-actions compact">
                            <div class="action-row">
                                <a class="btn ghost" href="{{ route('customers.edit', $customer) }}">Ubah</a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
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
