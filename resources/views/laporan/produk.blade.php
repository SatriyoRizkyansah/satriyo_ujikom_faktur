@extends('layouts.app')

@section('title', 'Laporan Produk')

@section('content')
    @php($currency = fn ($value) => 'Rp ' . number_format($value ?? 0, 0, ',', '.'))

    <div class="content-header">
        <div>
            <h2>Laporan Produk</h2>
            <p class="muted-text">Rekap stok, harga, dan performa penjualan tiap produk.</p>
        </div>
        <form method="GET" class="filter-bar">
            <div class="form-group">
                <label for="from">Dari tanggal</label>
                <input type="date" id="from" name="from" value="{{ $filters['from'] }}">
            </div>
            <div class="form-group">
                <label for="to">Sampai tanggal</label>
                <input type="date" id="to" name="to" value="{{ $filters['to'] }}">
            </div>
            <div class="form-group">
                <label for="jenis">Kategori Produk</label>
                <select name="jenis" id="jenis">
                    <option value="">Semua jenis</option>
                    @foreach ($filters['jenis_list'] as $jenis)
                        <option value="{{ $jenis }}" {{ $filters['jenis'] === $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                    @endforeach
                </select>
            </div>
            <label class="checkbox-inline">
                <input type="checkbox" name="all_time" value="1" {{ $filters['all_time'] ? 'checked' : '' }}>
                <span>Semua waktu</span>
            </label>
            <div class="filter-actions">
                <button class="btn" type="submit">Terapkan</button>
                <a class="btn outline" href="{{ route('laporan.produk') }}">Reset</a>
                <button class="btn secondary" type="button" onclick="window.print()">Cetak</button>
            </div>
        </form>
    </div>

    <div class="stat-grid">
        <article class="stat-card">
            <small>Total Produk</small>
            <strong>{{ $summary['total_produk'] }}</strong>
            <span class="muted-text">Produk aktif di katalog</span>
        </article>
        <article class="stat-card">
            <small>Total Stok Tersedia</small>
            <strong>{{ number_format($summary['stok_tersedia']) }}</strong>
            <span class="muted-text">Jumlah unit siap jual</span>
        </article>
        <article class="stat-card">
            <small>Qty Terjual</small>
            <strong>{{ number_format($summary['qty_terjual']) }}</strong>
            <span class="muted-text">Output berdasarkan periode</span>
        </article>
        <article class="stat-card">
            <small>Pendapatan</small>
            <strong>{{ $currency($summary['pendapatan']) }}</strong>
            <span class="muted-text">Akumulasi nilai produk terjual</span>
        </article>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jenis</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Qty Terjual</th>
                    <th>Pendapatan</th>
                    <th>Terakhir Terjual</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($report as $row)
                    <tr>
                        <td>
                            <strong>{{ $row->produk->nama_produk }}</strong>
                        </td>
                        <td>{{ $row->produk->jenis ?: '-' }}</td>
                        <td>{{ $currency($row->produk->price) }}</td>
                        <td>{{ number_format($row->produk->stock) }}</td>
                        <td>{{ number_format($row->sold_qty) }}</td>
                        <td>{{ $currency($row->revenue) }}</td>
                        <td>{{ $row->last_sold_at ? $row->last_sold_at->format('d M Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted-text" style="text-align: center;">Belum ada data produk untuk periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($report->count())
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th>{{ number_format($summary['stok_tersedia']) }}</th>
                        <th>{{ number_format($summary['qty_terjual']) }}</th>
                        <th>{{ $currency($summary['pendapatan']) }}</th>
                        <th></th>
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
