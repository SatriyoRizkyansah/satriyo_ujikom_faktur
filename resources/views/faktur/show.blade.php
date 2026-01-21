@extends('layouts.app')

@section('title', 'Preview Faktur')

@section('content')
    @php
        $perusahaan = $faktur->perusahaan;
        $customer = $faktur->customer;
        $subtotal = $faktur->detailFaktur->sum(fn($detail) => $detail->qty * $detail->price);
        $ppnValue = $subtotal * ($faktur->ppn / 100);
        $discount = $faktur->dp ?? 0;
        $grandTotal = $faktur->grand_total ?? ($subtotal + $ppnValue - $discount);
        $grandTotal = max($grandTotal, 0);
        $companyInitial = strtoupper(mb_substr($perusahaan->nama_perusahaan, 0, 1));
    @endphp

    <div class="invoice-toolbar no-print">
        <div class="action-row">
            <a class="btn ghost" href="{{ route('faktur.index') }}">Kembali</a>
            <a class="btn ghost" href="{{ route('faktur.edit', $faktur) }}">Ubah Faktur</a>
        </div>
        <button class="btn" onclick="window.print()">Cetak Faktur</button>
    </div>

    <section class="invoice-wrapper">
        <article class="invoice-paper">
            <header class="invoice-header">
                <div class="invoice-brand">
                    <div class="invoice-logo">{{ $companyInitial }}</div>
                    <div>
                        <p class="company-name">{{ $perusahaan->nama_perusahaan }}</p>
                        <p class="company-meta">
                            {{ $perusahaan->alamat }}<br>
                            Telp. {{ $perusahaan->no_telp ?? '-' }}@if ($perusahaan->fax) | Fax {{ $perusahaan->fax }}@endif
                        </p>
                    </div>
                </div>
                <div class="invoice-title">
                    <span>FAKTUR</span>
                    <p>No. Faktur: {{ $faktur->no_faktur }}</p>
                </div>
            </header>

            <div class="invoice-meta">
                <div class="meta-block">
                    <dl>
                        <div>
                            <dt>Nama Pelanggan</dt>
                            <dd>{{ $customer->nama_customer }}</dd>
                        </div>
                        <div>
                            <dt>No. Telp</dt>
                            <dd>{{ $customer->no_telp ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt>Alamat</dt>
                            <dd>{{ $customer->alamat }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="meta-block">
                    <table class="meta-table">
                        <tr>
                            <th>Kasir</th>
                            <td>{{ $faktur->user }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ $faktur->tgl_faktur->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Jatuh Tempo</th>
                            <td>{{ $faktur->due_date->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Pembayaran</th>
                            <td>{{ strtoupper($faktur->metode_bayar) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Qty</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Disc</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($faktur->detailFaktur as $index => $detail)
                        @php($lineSubtotal = $detail->qty * $detail->price)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->produk->nama_produk }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>{{ $detail->produk->jenis ?? 'Unit' }}</td>
                            <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td>0%</td>
                            <td>Rp {{ number_format($lineSubtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Belum ada detail produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="invoice-summary">
                <div class="invoice-notes">
                    <p>Catatan</p>
                    <p>
                        Terima kasih telah berbelanja, semoga sehat selalu. Barang yang sudah dibeli
                        tidak dapat ditukar atau dikembalikan.
                    </p>
                </div>
                <table class="summary-table">
                    <tr>
                        <th>Total</th>
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Diskon / DP</th>
                        <td>Rp {{ number_format($discount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Pajak ({{ $faktur->ppn }}%)</th>
                        <td>Rp {{ number_format($ppnValue, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="grand-total">
                        <th>Grand Total</th>
                        <td>Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <div class="signature-row">
                <div>
                    <p>Penerima / Pembeli</p>
                    <span>{{ $customer->nama_customer }}</span>
                </div>
                <div>
                    <p>Kasir</p>
                    <span>{{ $faktur->user }}</span>
                </div>
            </div>
        </article>
    </section>

@endsection
