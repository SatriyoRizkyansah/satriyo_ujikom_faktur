<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
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
    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
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
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('status', 'Customer dihapus.');
    }

    public function preview(): View
    {
        $customers = Customer::orderBy('nama_customer')->get();

        return view('customers.preview', compact('customers'));
    }
}
