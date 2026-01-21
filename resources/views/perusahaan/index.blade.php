@extends('layouts.app')

@section('title', 'Kelola Data Perusahaan')

@section('content')
    <div class="content-header">
        <h2>Kelola Data Perusahaan</h2>
        <a class="btn" href="{{ route('perusahaan.create') }}">Tambah Perusahaan</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No. Telp</th>
                <th>Fax</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($perusahaan as $item)
                <tr>
                    <td>{{ $item->id_perusahaan }}</td>
                    <td>{{ $item->nama_perusahaan }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->no_telp ?? '-' }}</td>
                    <td>{{ $item->fax ?? '-' }}</td>
                    <td>
                        <div class="table-actions compact">
                            <div class="action-row">
                                <a class="btn ghost" href="{{ route('perusahaan.edit', $item) }}">Ubah</a>
                                <form action="{{ route('perusahaan.destroy', $item) }}" method="POST" class="inline-form">
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
                    <td colspan="6">Belum ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
