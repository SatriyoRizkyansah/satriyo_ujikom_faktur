@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
    @php($currency = fn ($value) => 'Rp ' . number_format($value ?? 0, 0, ',', '.'))

    <div class="content-header">
        <div>
            <h2>Laporan Penjualan</h2>
            <p class="muted-text">Pantau performa faktur yang keluar per periode.</p>
        </div>
        <form method="GET" class="filter-bar">
            <div class="form-group">
                <label for="sales-from">Dari tanggal</label>
                <input type="date" id="sales-from" name="from" value="{{ $filters['from'] }}">
            </div>
            <div class="form-group">
                <label for="sales-to">Sampai tanggal</label>
                <input type="date" id="sales-to" name="to" value="{{ $filters['to'] }}">
            </div>
            <div class="form-group">
                <label for="search">Cari Faktur / Customer</label>
                <input type="text" id="search" name="search" placeholder="contoh: Budi / 001" value="{{ $filters['search'] }}">
            </div>
            <div class="form-group">
                <label for="metode">Metode Bayar</label>
                <select id="metode" name="metode">
                    <option value="">Semua metode</option>
                    @foreach ($filters['metode_list'] as $metode)
                        <option value="{{ $metode }}" {{ $filters['metode'] === $metode ? 'selected' : '' }}>{{ $metode }}</option>
                    @endforeach
                </select>
            </div>
            <label class="checkbox-inline">
                <input type="checkbox" name="all_time" value="1" {{ $filters['all_time'] ? 'checked' : '' }}>
                <span>Semua waktu</span>
            </label>
            <div class="filter-actions">
                <button class="btn" type="submit">Terapkan</button>
                <a class="btn outline" href="{{ route('laporan.penjualan') }}">Reset</a>
                <button class="btn secondary" type="button" onclick="window.print()">Cetak</button>
            </div>
        </form>
    </div>

    <div class="stat-grid">
        <article class="stat-card">
            <small>Jumlah Faktur</small>
            <strong>{{ number_format($summary['total_faktur']) }}</strong>
            <span class="muted-text">Faktur dalam periode ini</span>
        </article>
        <article class="stat-card">
            <small>Pendapatan</small>
            <strong>{{ $currency($summary['pendapatan']) }}</strong>
            <span class="muted-text">Grand total faktur</span>
        </article>
        <article class="stat-card">
            <small>DP Terkumpul</small>
            <strong>{{ $currency($summary['dp']) }}</strong>
            <span class="muted-text">Akumulasi uang muka</span>
        </article>
        <article class="stat-card">
            <small>Sisa Tagihan</small>
            <strong>{{ $currency($summary['piutang']) }}</strong>
            <span class="muted-text">Grand total - DP</span>
        </article>
        <article class="stat-card">
            <small>Total Barang Keluar</small>
            <strong>{{ number_format($summary['barang_terjual']) }}</strong>
            <span class="muted-text">Qty keseluruhan</span>
        </article>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No Faktur</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Metode Bayar</th>
                    <th>Qty Barang</th>
                    <th>Grand Total</th>
                    <th>DP</th>
                    <th>Sisa</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($faktur as $row)
                    <tr>
                        <td><strong>#{{ $row->no_faktur }}</strong></td>
                        <td>
                            <div>{{ $row->tgl_faktur?->format('d M Y') }}</div>
                            <small class="muted-text">Jatuh tempo: {{ $row->due_date?->format('d M Y') ?? '-' }}</small>
                        </td>
                        <td>
                            <div>{{ $row->customer->nama_customer ?? '-' }}</div>
                            <small class="muted-text">PIC: {{ $row->user }}</small>
                        </td>
                        <td>{{ $row->metode_bayar ?? '-' }}</td>
                        <td>{{ number_format($row->total_barang ?? 0) }}</td>
                        <td>{{ $currency($row->grand_total) }}</td>
                        <td>{{ $currency($row->dp) }}</td>
                        <td>{{ $currency(max(($row->grand_total ?? 0) - ($row->dp ?? 0), 0)) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="muted-text" style="text-align: center;">Belum ada data penjualan untuk filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($faktur->count())
                <tfoot>
                    <tr>
                        <th colspan="4">Total</th>
                        <th>{{ number_format($summary['barang_terjual']) }}</th>
                        <th>{{ $currency($summary['pendapatan']) }}</th>
                        <th>{{ $currency($summary['dp']) }}</th>
                        <th>{{ $currency($summary['piutang']) }}</th>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('.filter-bar');
            if (!form) return;
            const checkbox = form.querySelector('input[name="all_time"]');
            const dateInputs = form.querySelectorAll('input[type="date"]');

            if (!checkbox) {
                return;
            }

            const toggleDateInputs = () => {
                const disabled = checkbox.checked;
                dateInputs.forEach((input) => {
                    input.disabled = disabled;
                    input.classList.toggle('is-disabled', disabled);
                });
            };

            checkbox.addEventListener('change', toggleDateInputs);
            toggleDateInputs();
        });
    </script>
@endpush
