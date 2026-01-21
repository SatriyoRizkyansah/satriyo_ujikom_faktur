<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerusahaanController extends Controller
{
    /**
     * Fokus controller ini cuma satu: ngejamin data perusahaan induk tertata.
     * Ada halaman daftar, form tambah/edit, validasi ringan, dan aksi hapus.
     * Semua route diarahkan balik ke index lengkap dengan flash message supaya
     * admin tau aksi terakhirnya berhasil.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $perusahaan = Perusahaan::orderBy('nama_perusahaan')->get();

        return view('perusahaan.index', compact('perusahaan'));
    }

    public function create(): View
    {
        return view('perusahaan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'no_telp' => ['nullable', 'string', 'max:30'],
            'fax' => ['nullable', 'string', 'max:30'],
        ]);

        Perusahaan::create($data);

        return redirect()->route('perusahaan.index')->with('status', 'Data perusahaan tersimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Perusahaan $perusahaan): View
    {
        return view('perusahaan.edit', compact('perusahaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perusahaan $perusahaan): RedirectResponse
    {
        $data = $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'no_telp' => ['nullable', 'string', 'max:30'],
            'fax' => ['nullable', 'string', 'max:30'],
        ]);

        $perusahaan->update($data);

        return redirect()->route('perusahaan.index')->with('status', 'Data perusahaan diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perusahaan $perusahaan): RedirectResponse
    {
        $perusahaan->delete();

        return redirect()->route('perusahaan.index')->with('status', 'Data perusahaan dihapus.');
    }
}
