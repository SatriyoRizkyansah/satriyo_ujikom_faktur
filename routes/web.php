<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DetailFakturController;
use App\Http\Controllers\FakturController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::resource('perusahaan', PerusahaanController::class)->except(['show']);
    Route::get('customers/preview', [CustomerController::class, 'preview'])->name('customers.preview');
    Route::resource('customers', CustomerController::class)->except(['show']);
    Route::resource('produk', ProdukController::class)->except(['show']);
    Route::resource('faktur', FakturController::class);

    Route::post('faktur/{faktur}/detail', [DetailFakturController::class, 'store'])->name('detail-faktur.store');
    Route::delete('faktur/{faktur}/detail/{produkId}', [DetailFakturController::class, 'destroy'])->name('detail-faktur.destroy');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
