<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Buka form kosong untuk input customer baru.
     */
    /**
     * Controller ini jadi pusat data pelanggan: nampilkan list, form tambah/edit,
     * simpan perubahan, sampai hapus data. Di luar CRUD standar, ada juga fitur
     * buat preview sekaligus ekspor PDF biar admin gampang cetak daftar customer
     * tanpa harus pindah aplikasi lagi.
     */
    /**
     * Display a listing of the resource.
     */
    /**
     * Ambil seluruh customer (urut nama) lalu lempar ke view index untuk ditampilkan.
     */
    public function index(): View
    {
        $customers = Customer::orderBy('nama_customer')->get();

        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Validasi input customer baru, simpan ke database, kemudian redirect dengan notifikasi.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_customer' => ['required', 'string', 'max:255'],
            'perusahaan_cust' => ['nullable', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
        ]);

        Customer::create($data);

        return redirect()->route('customers.index')->with('status', 'Customer berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    /**
     * Tampilkan form edit dengan data customer yang dipilih.
     */
    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Validasi data terbaru, update baris customer terkait, dan balikkan user ke list.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $data = $request->validate([
            'nama_customer' => ['required', 'string', 'max:255'],
            'perusahaan_cust' => ['nullable', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
        ]);

        $customer->update($data);

        return redirect()->route('customers.index')->with('status', 'Customer berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Hapus customer yang dipilih lalu redirect dengan pesan sukses.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('status', 'Customer dihapus.');
    }

    /**
     * Ambil list customer dan tampilkan di halaman preview (buat cek layout sebelum cetak).
     */
    public function preview(): View
    {
        $customers = Customer::orderBy('nama_customer')->get();

        return view('customers.preview', compact('customers'));
    }

    /**
     * Render daftar customer ke template PDF dan stream langsung ke browser untuk dicetak.
     */
    public function exportPdf(): Response
    {
        $customers = Customer::orderBy('nama_customer')->get();

        $pdf = Pdf::loadView('customers.export', [
            'customers' => $customers,
            'exportedAt' => now()->format('d/m/Y H:i'),
        ])->setPaper('a4', 'portrait');

        $fileName = 'data-customer-' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($fileName, ['Attachment' => false]);
    }
}
