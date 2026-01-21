<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Faktur Penjualan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="layout">
        <header class="header">
            <div class="brand-block">
                <span class="brand-pill">FakturPro</span>
                <h1>Panel Pengelolaan Penjualan</h1>
                <p class="muted-text">Pengelolaan perusahaan, customer, dan penjualan dalam satu tempat.</p>
            </div>
            @auth
                <div class="user-meta">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn outline">Keluar</button>
                    </form>
                </div>
            @endauth
        </header>

        @auth
            <nav class="navigation">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('perusahaan.index') }}" class="nav-link {{ request()->routeIs('perusahaan.*') ? 'active' : '' }}">Perusahaan</a>
                <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">Customer</a>
                <a href="{{ route('produk.index') }}" class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}">Produk</a>
                <a href="{{ route('faktur.index') }}" class="nav-link {{ request()->routeIs('faktur.*') ? 'active' : '' }}">Penjualan</a>
            </nav>
        @endauth

        <main class="main">
            <aside class="sidebar">
                <section>
                    <h2>Kelola Data Perusahaan</h2>
                    <ul>
                        <li><a class="sidebar-link {{ request()->routeIs('perusahaan.index') ? 'active' : '' }}" href="{{ route('perusahaan.index') }}">Tampil Daftar Perusahaan</a></li>
                        <li><a class="sidebar-link {{ request()->routeIs('perusahaan.create') ? 'active' : '' }}" href="{{ route('perusahaan.create') }}">Tambah Data Perusahaan</a></li>
                    </ul>
                </section>
                <section>
                    <h2>Kelola Data Customer</h2>
                    <ul>
                        <li><a class="sidebar-link {{ request()->routeIs('customers.index') ? 'active' : '' }}" href="{{ route('customers.index') }}">Tampil Daftar Customer</a></li>
                        <li><a class="sidebar-link {{ request()->routeIs('customers.create') ? 'active' : '' }}" href="{{ route('customers.create') }}">Tambah Data Customer</a></li>
                        <li><a class="sidebar-link {{ request()->routeIs('customers.preview') ? 'active' : '' }}" href="{{ route('customers.preview') }}">Preview / Cetak Data Customer</a></li>
                        <li><a class="sidebar-link {{ request()->routeIs('customers.export') ? 'active' : '' }}" href="{{ route('customers.export') }}">Export PDF Customer</a></li>
                    </ul>
                </section>
                <section>
                    <h2>Master Produk</h2>
                    <ul>
                        <li><a class="sidebar-link {{ request()->routeIs('produk.index') ? 'active' : '' }}" href="{{ route('produk.index') }}">Daftar Produk</a></li>
                        <li><a class="sidebar-link {{ request()->routeIs('produk.create') ? 'active' : '' }}" href="{{ route('produk.create') }}">Tambah Produk</a></li>
                    </ul>
                </section>
                <section>
                    <h2>Kelola Data Penjualan</h2>
                    <ul>
                        <li><a class="sidebar-link {{ request()->routeIs('faktur.index') ? 'active' : '' }}" href="{{ route('faktur.index') }}">Tampil Daftar Penjualan</a></li>
                        <li><a class="sidebar-link {{ request()->routeIs('faktur.create') ? 'active' : '' }}" href="{{ route('faktur.create') }}">Tambah Data Penjualan</a></li>
                    </ul>
                </section>
            </aside>

            <section class="content">
                @if (session('status'))
                    <div class="alert success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert danger">
                        <strong>Terjadi kesalahan:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </section>
        </main>

        <footer class="footer">
            <small>&copy; {{ date('Y') }} Sistem Faktur Penjualan. Semua hak cipta.</small>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
