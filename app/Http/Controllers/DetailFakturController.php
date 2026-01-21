<?php

namespace App\Http\Controllers;

use App\Models\DetailFaktur;
use App\Models\Faktur;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DetailFakturController extends Controller
{
    /**
     * Tugas controller ini simpel tapi krusial: nge-handle baris detail di faktur.
     * Saat user tambah/edit produk, datanya langsung disimpan dan total faktur
     * dihitung ulang. Kalau ada baris dihapus, grand total ikut disesuaikan supaya
     * angka di layar sama dengan yang dicetak.
     */
    /**
     * Simpan atau update satu baris detail faktur berdasarkan produk yang dipilih,
     * otomatis isi harga default kalau user nggak input, lalu sinkronkan grand total.
     */
    public function store(Request $request, Faktur $faktur): RedirectResponse
    {
        $data = $request->validate([
            'id_produk' => ['required', 'exists:produk,id_produk'],
            'qty' => ['required', 'integer', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:0'],
        ]);

        if (!isset($data['price'])) {
            $data['price'] = Produk::find($data['id_produk'])->price ?? 0;
        }

        DetailFaktur::updateOrCreate(
            [
                'no_faktur' => $faktur->no_faktur,
                'id_produk' => $data['id_produk'],
            ],
            [
                'qty' => $data['qty'],
                'price' => $data['price'],
            ]
        );

        $this->syncGrandTotal($faktur);

        return back()->with('status', 'Detail faktur diperbarui.');
    }

    /**
     * Hapus baris detail berdasarkan produk tertentu dari faktur dan update totalnya.
     */
    public function destroy(Faktur $faktur, int $produkId): RedirectResponse
    {
        DetailFaktur::where('no_faktur', $faktur->no_faktur)
            ->where('id_produk', $produkId)
            ->delete();

        $this->syncGrandTotal($faktur);

        return back()->with('status', 'Detail faktur dihapus.');
    }

    /**
     * Hitung ulang subtotal, ppn, dan grand total, kemudian simpan ke model faktur.
     */
    private function syncGrandTotal(Faktur $faktur): void
    {
        $subtotal = $faktur->detailFaktur()->get()->sum(fn ($detail) => $detail->qty * $detail->price);
        $ppnValue = $subtotal * ($faktur->ppn / 100);
        $grand = max($subtotal + $ppnValue - ($faktur->dp ?? 0), 0);

        $faktur->update(['grand_total' => $grand]);
    }
}
