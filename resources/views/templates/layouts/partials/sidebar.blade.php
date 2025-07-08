<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            STO<span>KEU</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main Menu</li>

            {{-- menu user --}}
            @can('user')
            <li class="nav-item {{ request()->is('dashboard-admin/*') ? 'active' : '' }}">
                <a href="{{ url('/dashboard-admin') }}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('saldo-awal/*') ? 'active' : '' }}">
                <a href="{{ url('/saldo-awal') }}" class="nav-link">
                    <i class="link-icon" data-feather="dollar-sign"></i>
                    <span class="link-title">Saldo Awal</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('penerimaan/*') ? 'active' : '' }}">
                <a href="{{ url('/penerimaan') }}" class="nav-link">
                    <i class="link-icon" data-feather="plus-square"></i>
                    <span class="link-title">Penerimaan</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('pengeluaran/*') ? 'active' : '' }}">
                <a href="{{ url('/pengeluaran') }}" class="nav-link">
                    <i class="link-icon" data-feather="minus-square"></i>
                    <span class="link-title">Pengeluaran</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('laporan-persediaan/*') ? 'active' : '' }}">
                <a href="{{ url('/laporan-persediaan') }}" class="nav-link">
                    <i class="link-icon" data-feather="archive"></i>
                    <span class="link-title">Laporan Persediaan</span>
                </a>
            </li>
            @endcan

            {{-- menu admin --}}
            @can('admin')
            <li class="nav-item {{ request()->is('dashboard-admin/*') ? 'active' : '' }}">
                <a href="{{ url('/dashboard-admin') }}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('data-master') ? 'active' : '' }}">
                <a href="{{ url('/data-master') }}" class="nav-link">
                    <i class="link-icon" data-feather="database"></i>
                    <span class="link-title">Data Master Barang</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('/riwayat-transaksi') ? 'active' : '' }}">
                <a href="{{ url('/riwayat-transaksi') }}" class="nav-link">
                    <i class="link-icon" data-feather="server"></i>
                    <span class="link-title">Riwayat Transaksi</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('saldo-awal/*') ? 'active' : '' }}">
                <a href="{{ url('/saldo-awal') }}" class="nav-link">
                    <i class="link-icon" data-feather="dollar-sign"></i>
                    <span class="link-title">Saldo Awal</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('penerimaan/*') ? 'active' : '' }}">
                <a href="{{ url('/penerimaan') }}" class="nav-link">
                    <i class="link-icon" data-feather="plus-square"></i>
                    <span class="link-title">Penerimaan</span>
                </a>
            </li>
            {{-- <li class="nav-item {{ request()->is('pengeluaran/*') ? 'active' : '' }}">
                <a href="{{ url('/pengeluaran') }}" class="nav-link">
                    <i class="link-icon" data-feather="minus-square"></i>
                    <span class="link-title">Pengeluaran</span>
                </a>
            </li> --}}
            <li class="nav-item {{ request()->is('laporan-persediaan/*') ? 'active' : '' }}">
                <a href="{{ url('/laporan-persediaan') }}" class="nav-link">
                    <i class="link-icon" data-feather="archive"></i>
                    <span class="link-title">Laporan Persediaan</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('stock-opname/*') ? 'active' : '' }}">
                <a href="{{ url('/stock-opname') }}" class="nav-link">
                    <i class="link-icon" data-feather="clipboard"></i>
                    <span class="link-title">Stock Opname</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
</nav>