<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex flex-column align-items-center justify-content-center py-3" href="{{ url('/') }}">
        <div class="d-flex justify-content-center align-items-center gap-2">
            <!-- Simpan dua logo di folder public/logo -->
            <img src="{{ asset('logo/LOGO_SLEMAN.png') }}" alt="LOGO KABUPATEN SLEMAN, YOGYAKARTA" style="height: 45px;">
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Master Data Heading -->
    <div class="sidebar-heading">
        Master Data
    </div>

    <li class="nav-item {{ request()->is('divisi*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/divisi') }}">
            <i class="fas fa-building"></i>
            <span>Divisi</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('penanggung-jawab*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/penanggung-jawab') }}">
            <i class="fas fa-user-tie"></i>
            <span>Penanggung Jawab</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('kendaraan*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/kendaraan') }}">
            <i class="fas fa-car"></i>
            <span>Kendaraan</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('rekap*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/rekap') }}">
            <i class="fas fa-clipboard-list"></i>
            <span>Rekap</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('anggaran*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/anggaran') }}">
            <i class="fas fa-money-bill"></i>
            <span>Anggaran</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- 🔹 Menu Khusus Super Admin -->
    @can('isSuperAdmin')
        <div class="sidebar-heading">
            Super Admin
        </div>

        <li class="nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <i class="fas fa-user-shield"></i>
                <span>Manajemen User</span>
            </a>
        </li>

        <hr class="sidebar-divider">
    @endcan

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
