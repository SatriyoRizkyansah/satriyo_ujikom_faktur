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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function destroy(Faktur $faktur, int $produkId): RedirectResponse
    {
        DetailFaktur::where('no_faktur', $faktur->no_faktur)
            ->where('id_produk', $produkId)
            ->delete();

        $this->syncGrandTotal($faktur);

        return back()->with('status', 'Detail faktur dihapus.');
    }

    private function syncGrandTotal(Faktur $faktur): void
    {
        $subtotal = $faktur->detailFaktur()->get()->sum(fn ($detail) => $detail->qty * $detail->price);
        $ppnValue = $subtotal * ($faktur->ppn / 100);
        $grand = max($subtotal + $ppnValue - ($faktur->dp ?? 0), 0);

        $faktur->update(['grand_total' => $grand]);
    }
}
