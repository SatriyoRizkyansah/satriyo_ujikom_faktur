<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailFaktur;
use App\Models\Faktur;
use App\Models\Perusahaan;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FakturController extends Controller
{
    /**
     * Controller ini nge-manage seluruh siklus hidup faktur: dari list penjualan,
     * form input yang sudah kebagi customer/perusahaan/produk, simpan data plus
     * detail produknya, sampai preview dan edit ulang. Perhitungan subtotal, PPN,
     * DP, dan grand total juga dirapel di sini biar data yang keluar ke view selalu
     * rapi dan siap dicetak.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $faktur = Faktur::with(['customer', 'perusahaan'])
            ->orderBy('no_faktur')
            ->get();

        return view('faktur.index', compact('faktur'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('nama_customer')->get();
        $perusahaan = Perusahaan::orderBy('nama_perusahaan')->get();
        $produk = Produk::orderBy('nama_produk')->get();

        return view('faktur.create', compact('customers', 'perusahaan', 'produk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateFaktur($request);
        $details = $this->collectDetails($request);

        if ($details->isEmpty()) {
            return back()->withErrors('Minimal satu produk harus diisi pada detail faktur.')->withInput();
        }

        $grandTotal = $this->calculateGrandTotal($details, $data['ppn'], $data['dp'] ?? 0);

        DB::transaction(function () use ($details, $data, $grandTotal) {
            $lockedProducts = $this->assertStockAvailability($details);

            $faktur = Faktur::create(array_merge($data, ['grand_total' => $grandTotal]));

            $this->persistDetails($faktur, $details, $lockedProducts);
        });

        return redirect()->route('faktur.index')->with('status', 'Data penjualan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Faktur $faktur): View
    {
        $faktur->load(['customer', 'perusahaan', 'detailFaktur.produk']);
        $produk = Produk::orderBy('nama_produk')->get();

        return view('faktur.show', compact('faktur', 'produk'));
    }

    public function edit(Faktur $faktur): View
    {
        $faktur->load('detailFaktur');
        $customers = Customer::orderBy('nama_customer')->get();
        $perusahaan = Perusahaan::orderBy('nama_perusahaan')->get();
        $produk = Produk::orderBy('nama_produk')->get();

        return view('faktur.edit', compact('faktur', 'customers', 'perusahaan', 'produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faktur $faktur): RedirectResponse
    {
        $data = $this->validateFaktur($request);
        $details = $this->collectDetails($request);

        if ($details->isEmpty()) {
            return back()->withErrors('Minimal satu produk harus diisi pada detail faktur.')->withInput();
        }

        $grandTotal = $this->calculateGrandTotal($details, $data['ppn'], $data['dp'] ?? 0);

        DB::transaction(function () use ($faktur, $details, $data, $grandTotal) {
            $existingDetails = $faktur->detailFaktur()->lockForUpdate()->get();
            $this->returnStock($existingDetails);
            $faktur->detailFaktur()->delete();

            $lockedProducts = $this->assertStockAvailability($details);

            $faktur->update(array_merge($data, ['grand_total' => $grandTotal]));

            $this->persistDetails($faktur, $details, $lockedProducts);
        });

        return redirect()->route('faktur.index')->with('status', 'Data penjualan diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faktur $faktur): RedirectResponse
    {
        DB::transaction(function () use ($faktur) {
            $lockedFaktur = Faktur::where('no_faktur', $faktur->no_faktur)->lockForUpdate()->firstOrFail();
            $details = $lockedFaktur->detailFaktur()->lockForUpdate()->get();
            $this->returnStock($details);
            $lockedFaktur->detailFaktur()->delete();

            $lockedFaktur->delete();
        });

        return redirect()->route('faktur.index')->with('status', 'Data penjualan dihapus.');
    }

    private function validateFaktur(Request $request): array
    {
        return $request->validate([
            'tgl_faktur' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:tgl_faktur'],
            'metode_bayar' => ['required', 'string', 'max:100'],
            'ppn' => ['required', 'numeric', 'min:0', 'max:100'],
            'dp' => ['nullable', 'numeric', 'min:0'],
            'user' => ['required', 'string', 'max:100'],
            'id_customer' => ['required', 'exists:customer,id_customer'],
            'id_perusahaan' => ['required', 'exists:perusahaan,id_perusahaan'],
        ]);
    }

    private function collectDetails(Request $request): Collection
    {
        $produkIds = $request->input('id_produk', []);
        $qty = $request->input('qty', []);
        $prices = $request->input('price', []);

        return collect($produkIds)->map(function ($produkId, $index) use ($qty, $prices) {
            return [
                'id_produk' => $produkId,
                'qty' => (int) ($qty[$index] ?? 0),
                'price' => (float) ($prices[$index] ?? 0),
            ];
        })->filter(fn ($line) => !empty($line['id_produk']) && $line['qty'] > 0 && $line['price'] >= 0);
    }

    private function calculateGrandTotal(Collection $details, float $ppn, float $dp): float
    {
        $subtotal = $details->sum(fn ($detail) => $detail['qty'] * $detail['price']);
        $ppnValue = $subtotal * ($ppn / 100);
        $grand = $subtotal + $ppnValue - ($dp ?? 0);

        return max($grand, 0);
    }

    private function assertStockAvailability(Collection $details): Collection
    {
        $grouped = $details->groupBy('id_produk')->map(fn ($items) => $items->sum('qty'));

        if ($grouped->isEmpty()) {
            return collect();
        }

        $products = Produk::whereIn('id_produk', $grouped->keys())
            ->lockForUpdate()
            ->get()
            ->keyBy('id_produk');

        foreach ($grouped as $productId => $requiredQty) {
            $product = $products->get($productId);

            if (!$product) {
                throw ValidationException::withMessages([
                    'detail' => 'Produk dengan ID ' . $productId . ' tidak ditemukan.',
                ]);
            }

            if ($product->stock < $requiredQty) {
                throw ValidationException::withMessages([
                    'detail' => 'Stok produk "' . $product->nama_produk . '" tidak mencukupi. Sisa stok: ' . $product->stock,
                ]);
            }
        }

        return $products;
    }

    private function persistDetails(Faktur $faktur, Collection $details, ?Collection $lockedProducts = null): void
    {
        $details->each(function ($detail) use ($faktur, $lockedProducts) {
            DetailFaktur::create([
                'no_faktur' => $faktur->no_faktur,
                'id_produk' => $detail['id_produk'],
                'qty' => $detail['qty'],
                'price' => $detail['price'],
            ]);

            $product = $lockedProducts?->get($detail['id_produk'])
                ?? Produk::where('id_produk', $detail['id_produk'])->lockForUpdate()->first();

            if ($product) {
                $product->decrement('stock', $detail['qty']);
                $product->stock -= $detail['qty'];
            }
        });
    }

    private function returnStock(Collection $details): void
    {
        if ($details->isEmpty()) {
            return;
        }

        $grouped = $details->groupBy('id_produk')->map(fn ($items) => $items->sum('qty'));

        $products = Produk::whereIn('id_produk', $grouped->keys())
            ->lockForUpdate()
            ->get()
            ->keyBy('id_produk');

        foreach ($grouped as $productId => $qty) {
            $product = $products->get($productId);

            if ($product) {
                $product->increment('stock', $qty);
                $product->stock += $qty;
            }
        }
    }
}
