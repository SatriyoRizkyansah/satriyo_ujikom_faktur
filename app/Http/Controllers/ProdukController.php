<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $produk = Produk::orderBy('nama_produk')->get();

        return view('produk.index', compact('produk'));
    }

    public function create(): View
    {
        return view('produk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_produk' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'jenis' => ['nullable', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        Produk::create($data);

        return redirect()->route('produk.index')->with('status', 'Produk tersimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Produk $produk): View
    {
        return view('produk.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk): RedirectResponse
    {
        $data = $request->validate([
            'nama_produk' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'jenis' => ['nullable', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $produk->update($data);

        return redirect()->route('produk.index')->with('status', 'Produk diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk): RedirectResponse
    {
        $produk->delete();

        return redirect()->route('produk.index')->with('status', 'Produk dihapus.');
    }
}
