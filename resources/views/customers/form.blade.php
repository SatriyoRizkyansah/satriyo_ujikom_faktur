@csrf
@php($customerData = optional($customer ?? null))
<div class="form-grid">
    <div class="form-group">
        <label>Nama Customer</label>
        <input type="text" name="nama_customer" value="{{ old('nama_customer', $customerData->nama_customer) }}" required>
    </div>
    <div class="form-group">
        <label>Perusahaan Customer</label>
        <input type="text" name="perusahaan_cust" value="{{ old('perusahaan_cust', $customerData->perusahaan_cust) }}">
    </div>
    <div class="form-group full-width">
        <label>Alamat</label>
        <textarea name="alamat" rows="3" required>{{ old('alamat', $customerData->alamat) }}</textarea>
    </div>
</div>
<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn secondary" href="{{ route('customers.index') }}">Batal</a>
</div>
