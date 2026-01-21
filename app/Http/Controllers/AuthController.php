<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Controller ini ngurus jalur paling basic soal akses aplikasi:
     * - nampilin form login
     * - ngecek kredensial, set session, dan arahkan user ke dashboard
     * - beresin session waktu logout biar aman
     * Semua prosesnya dibuat sesingkat mungkin biar alur login feels ringan.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Nerima data dari form login, validasi email & password, lalu coba login.
     * Kalau sukses kita regen session biar aman dan lempar user ke home.
     * Kalau gagal, balikin lagi ke form sambil ngasih pesan error di field email.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Logout tinggal panggil Auth::logout(), ikuti dengan invalidate session dan
     * regenerate token CSRF supaya sesi lama nggak kepake lagi. Terakhir arahkan
     * user balik ke halaman login.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
