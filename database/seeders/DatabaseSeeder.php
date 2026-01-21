<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\DetailFaktur;
use App\Models\Faktur;
use App\Models\Perusahaan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('admin123'),
            ]
        );

        $user2 = User::updateOrCreate(
            ['email' => 'satriyorizkyansah@gmail.com'],
            [
                'name' => 'Satriyo Rizkyansah',
                'password' => bcrypt('password'),
            ]
        );

        $perusahaan = Perusahaan::firstOrCreate([
            'nama_perusahaan' => 'PT. Sukses Makmur',
        ], [
            'alamat' => 'Jl. Merpati No. 10, Jakarta',
            'no_telp' => '021-5556677',
            'fax' => '021-5556678',
        ]);

        $customer = Customer::firstOrCreate([
            'nama_customer' => 'Budi Santoso',
        ], [
            'perusahaan_cust' => 'CV. Budi Sentosa',
            'alamat' => 'Jl. Kenanga No. 5, Bandung',
        ]);

        $produkA = Produk::firstOrCreate([
            'nama_produk' => 'Kertas A4 Premium',
        ], [
            'jenis' => 'ATK',
            'price' => 75000,
            'stock' => 120,
        ]);

        $produkB = Produk::firstOrCreate([
            'nama_produk' => 'Tinta Printer Hitam',
        ], [
            'jenis' => 'ATK',
            'price' => 95000,
            'stock' => 80,
        ]);

        $faktur = Faktur::firstOrCreate([
            'no_faktur' => 1,
        ], [
            'tgl_faktur' => now()->subDays(2),
            'due_date' => now()->addDays(12),
            'metode_bayar' => 'Transfer Bank',
            'ppn' => 11,
            'dp' => 250000,
            'grand_total' => 0,
            'user' => $user->name,
            'id_customer' => $customer->id_customer,
            'id_perusahaan' => $perusahaan->id_perusahaan,
        ]);

        DetailFaktur::updateOrCreate([
            'id_produk' => $produkA->id_produk,
            'no_faktur' => $faktur->no_faktur,
        ], [
            'qty' => 10,
            'price' => $produkA->price,
        ]);

        DetailFaktur::updateOrCreate([
            'id_produk' => $produkB->id_produk,
            'no_faktur' => $faktur->no_faktur,
        ], [
            'qty' => 5,
            'price' => $produkB->price,
        ]);

        $subtotal = $faktur->detailFaktur()->get()->sum(fn ($detail) => $detail->qty * $detail->price);
        $ppnValue = $subtotal * ($faktur->ppn / 100);
        $faktur->update(['grand_total' => max($subtotal + $ppnValue - $faktur->dp, 0)]);
    }
}
