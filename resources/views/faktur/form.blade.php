@csrf
@php
    $existing = $faktur ?? null;
    $detailCollection = $existing?->detailFaktur ?? collect();

    $detailProduk = old('id_produk', $detailCollection->pluck('id_produk')->toArray());
    $detailQty = old('qty', $detailCollection->pluck('qty')->toArray());
    $detailPrice = old('price', $detailCollection->pluck('price')->toArray());

    if (empty($detailProduk) || !is_array($detailProduk)) {
        $detailProduk = [null];
        $detailQty = [1];
        $detailPrice = [0];
    }
@endphp
<div class="form-grid">
    <div class="form-group">
        <label>Tanggal Faktur</label>
        <input type="date" name="tgl_faktur" value="{{ old('tgl_faktur', optional($existing?->tgl_faktur)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
    </div>
    <div class="form-group">
        <label>Jatuh Tempo</label>
        <input type="date" name="due_date" value="{{ old('due_date', optional($existing?->due_date)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
    </div>
    <div class="form-group">
        <label>Metode Bayar</label>
        <select name="metode_bayar" required>
            <option value="">-- Pilih Metode Bayar --</option>
            <option value="Transfer" @selected(old('metode_bayar', $existing?->metode_bayar ?? '') == 'Transfer')>Transfer</option>
            <option value="Cash" @selected(old('metode_bayar', $existing?->metode_bayar ?? '') == 'Cash')>Cash</option>
            <option value="QRIS" @selected(old('metode_bayar', $existing?->metode_bayar ?? '') == 'QRIS')>QRIS</option>
        </select>
    </div>
    <div class="form-group">
        <label>PPN (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="ppn" value="{{ old('ppn', $existing?->ppn ?? 11) }}" required>
    </div>
    <div class="form-group">
        <label>DP (Rp)</label>
        <input type="number" step="0.01" min="0" name="dp" value="{{ old('dp', $existing?->dp ?? 0) }}">
    </div>
    <div class="form-group">
        <label>Nama User</label>
        <input type="text" name="user" value="{{ old('user', $existing?->user ?? 'Admin') }}" required>
    </div>
    <div class="form-group">
        <label>Customer</label>
        <select name="id_customer" required>
            <option value="">-- Pilih Customer --</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id_customer }}" @selected(old('id_customer', $existing?->id_customer ?? '') == $customer->id_customer)>
                    {{ $customer->nama_customer }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Perusahaan</label>
        <select name="id_perusahaan" required>
            <option value="">-- Pilih Perusahaan --</option>
            @foreach ($perusahaan as $company)
                <option value="{{ $company->id_perusahaan }}" @selected(old('id_perusahaan', $existing?->id_perusahaan ?? '') == $company->id_perusahaan)>
                    {{ $company->nama_perusahaan }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<h3>Detail Produk</h3>
<p>Isi minimal satu produk untuk faktur.</p>

<div class="detail-list" id="detail-list">
    @foreach ($detailProduk as $index => $produkId)
        <div class="detail-row">
            <div class="form-group">
                <label>Produk</label>
                <select name="id_produk[]" class="produk-select" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($produk as $item)
                        <option value="{{ $item->id_produk }}" data-price="{{ $item->price }}" @selected($produkId == $item->id_produk)>
                            {{ $item->nama_produk }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Qty</label>
                <input type="number" name="qty[]" min="1" value="{{ $detailQty[$index] ?? 1 }}" required>
            </div>
            <div class="form-group">
                <label>Harga Satuan</label>
                <input type="number" step="0.01" min="0" name="price[]" value="{{ $detailPrice[$index] ?? 0 }}" required>
            </div>
            <button type="button" class="btn ghost remove-row">Hapus</button>
        </div>
    @endforeach
</div>

<div class="form-actions">
    <button type="button" class="btn ghost" id="add-row">Tambah Baris Produk</button>
</div>

<div class="form-actions align-end">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn secondary" href="{{ route('faktur.index') }}">Batal</a>
</div>

@push('scripts')
    <script>
        const detailList = document.getElementById('detail-list');
        document.getElementById('add-row').addEventListener('click', () => {
            const template = document.createElement('div');
            template.classList.add('detail-row');
            template.innerHTML = `
                <div class="form-group">
                    <label>Produk</label>
                    <select name="id_produk[]" class="produk-select" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($produk as $item)
                            <option value="{{ $item->id_produk }}" data-price="{{ $item->price }}">{{ $item->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Qty</label>
                    <input type="number" name="qty[]" min="1" value="1" required>
                </div>
                <div class="form-group">
                    <label>Harga Satuan</label>
                    <input type="number" step="0.01" min="0" name="price[]" value="0" required>
                </div>
                <button type="button" class="btn ghost remove-row">Hapus</button>
            `;
            detailList.appendChild(template);
        });

        detailList.addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-row')) {
                const rows = detailList.querySelectorAll('.detail-row');
                if (rows.length > 1) {
                    event.target.closest('.detail-row').remove();
                }
            }
        });

        detailList.addEventListener('change', (event) => {
            if (event.target.classList.contains('produk-select')) {
                const price = event.target.selectedOptions[0]?.dataset.price ?? 0;
                const priceInput = event.target.closest('.detail-row').querySelector('input[name="price[]"]');
                if (priceInput && (!priceInput.value || priceInput.value === '0')) {
                    priceInput.value = price;
                }
            }
        });
    </script>
@endpush
