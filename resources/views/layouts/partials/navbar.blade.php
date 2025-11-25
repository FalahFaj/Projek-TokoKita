<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-store"></i> TokoKita
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>

                    @if (auth()->user()->hakAkesaAdminPanel())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cogs"></i> Admin
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.products.index') }}"><i
                                            class="fas fa-boxes"></i> Produk</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}"><i
                                            class="fas fa-tags"></i> Kategori</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.suppliers.index') }}"><i
                                            class="fas fa-truck"></i> Supplier</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li class="dropend">
                                    <a class="dropdown-item dropdown-toggle" href="#" id="submenuLaporan"
                                        role="button" aria-expanded="false">
                                        <i class="fas fa-chart-bar"></i> Laporan
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="submenuLaporan">
                                        <li><a class="dropdown-item" href="{{ route('admin.reports.sales') }}">Laporan
                                                Penjualan</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.reports.products') }}">Laporan
                                                Produk</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.reports.stock') }}">Laporan
                                                Stok</a></li>
                                    </ul>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>

                                @if (auth()->user()->isOwner())
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                            <i class="fas fa-users"></i> Manajemen User
                                        </a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pos.index') }}">
                            <i class="fas fa-cash-register"></i> POS
                        </a>
                    </li>

                    @if (auth()->user()->isKasir())
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-history"></i> Riwayat Transaksi
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            @if (auth()->user()->isLowStockProducts()->count() > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ auth()->user()->isLowStockProducts()->count() }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <h6 class="dropdown-header">Notifikasi</h6>
                            </li>

                            @if (auth()->user()->isLowStockProducts()->count() > 0)
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ auth()->user()->isLowStockProducts()->count() }} produk stok rendah
                                    </a>
                                </li>
                            @else
                                <li><a class="dropdown-item text-muted" href="#">Tidak ada notifikasi</a></li>
                            @endif

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Pengaturan</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <span class="bg-primary rounded-circle p-2 me-1"
                                style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px;">
                                {{ auth()->user()->initials }}
                            </span>
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <div class="dropdown-header">
                                    <strong>{{ auth()->user()->name }}</strong>
                                    <div class="text-muted small">{{ auth()->user()->role_name }}</div>
                                </div>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user me-2"></i> Profile
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cog me-2"></i> Settings
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
