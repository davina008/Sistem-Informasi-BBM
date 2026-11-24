<!-- Topbar -->

<style>
/* === LIGHT MODE DEFAULT === */
.bg-primary {
    background-color: #1e40af !important;
    transition: background-color 0.3s ease;
}

.navbar .text-white {
    color: #fff !important;
}

/* === DARK MODE === */
body.dark-mode {
    background-color: #1f2937 !important;
    color: #f3f4f6 !important;
    transition: background-color 0.4s, color 0.4s;
}

/* Navbar / Topbar */
body.dark-mode .bg-primary {
    background-color: #111827 !important;
    color: #f3f4f6 !important;
}

/* Sidebar */
body.dark-mode .sidebar {
    background-color: #111827 !important;
}

/* Card, Table, Dropdown */
body.dark-mode .card,
body.dark-mode table,
body.dark-mode .dropdown-menu {
    background-color: #2d3748 !important;
    color: #f3f4f6 !important;
}

/* Texts */
body.dark-mode h1,
body.dark-mode h2,
body.dark-mode h3,
body.dark-mode h4,
body.dark-mode h5,
body.dark-mode h6,
body.dark-mode p,
body.dark-mode small,
body.dark-mode span,
body.dark-mode a {
    color: #f3f4f6 !important;
}

/* === DARK MODE SWITCH TOGGLE === */
.theme-switch-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
}

.theme-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 26px;
}

.theme-switch input {
    display: none;
}

.slider {
    background-color: #ccc;
    border-radius: 34px;
    position: absolute;
    cursor: pointer;
    inset: 0;
    transition: background-color 0.3s ease;
}

.slider:before {
    content: "";
    position: absolute;
    height: 18px;
    width: 18px;
    left: 4px;
    bottom: 4px;
    background-color: #fff;
    border-radius: 50%;
    transition: transform 0.3s ease;
}

/* Warna saat aktif (dark mode) */
input:checked + .slider {
    background-color: #4f46e5;
}

input:checked + .slider:before {
    transform: translateX(24px);
}

/* === Ikon minimalis (SVG) === */
.icon-light,
.icon-dark {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 14px;
    height: 14px;
    transition: opacity 0.3s ease, transform 0.5s ease;
    stroke: currentColor;
    stroke-width: 2;
}

.icon-light {
    left: 6px;
    opacity: 1;
    color: #facc15; /* kuning */
}

.icon-dark {
    right: 6px;
    opacity: 0;
    color: #1e3a8a; /* biru gelap */
}

/* Saat dark mode aktif, tukar ikon dan animasi berputar */
input:checked + .slider .icon-light {
    opacity: 0;
    transform: translateY(-50%) rotate(180deg);
}

input:checked + .slider .icon-dark {
    opacity: 1;
    transform: translateY(-50%) rotate(360deg);
}
</style>

<nav class="navbar navbar-expand navbar-light bg-primary topbar mb-4 static-top shadow">


<!-- Tombol Sidebar (Mobile) -->
<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 text-white">
    <i class="fa fa-bars"></i>
</button>

<!-- Judul Sistem -->
<div class="text-white fw-bold ms-2">
    <i class="fas fa-gas-pump me-2"></i> SIMABBM
    <small class="fw-normal d-none d-sm-inline" style="font-size: 0.9rem;">
        | Sistem Informasi Manajemen Anggaran BBM
    </small>
    <br>
    <small class="fw-normal d-none d-sm-inline" style="font-size: 0.8rem; color: #cfe2ff;">
        Dinas Kebudayaan (Kundha Kabudayan) Kabupaten Sleman
    </small>
</div>

<!-- Spacer kanan -->
<div class="ml-auto d-flex align-items-center">

    <!-- Toggle Dark/Light Mode Switch -->
    <div class="theme-switch-wrapper mr-3">
        <label class="theme-switch" for="darkModeCheckbox" title="Toggle Dark Mode">
            <input type="checkbox" id="darkModeCheckbox" />
            <div class="slider round">
                <!-- Ikon Matahari -->
                <svg class="icon-light" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="5" />
                    <line x1="12" y1="1" x2="12" y2="3" />
                    <line x1="12" y1="21" x2="12" y2="23" />
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                    <line x1="1" y1="12" x2="3" y2="12" />
                    <line x1="21" y1="12" x2="23" y2="12" />
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
                </svg>

                <!-- Ikon Bulan -->
                <svg class="icon-dark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path d="M21 12.79A9 9 0 0 1 11.21 3
                             7 7 0 0 0 12 17
                             9 9 0 0 1 21 12.79z" />
                </svg>
            </div>
        </label>
    </div>

    <!-- Profil Admin -->
    <ul class="navbar-nav">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-white small">{{ Auth::user()->name ?? 'Admin' }}</span>
                <img class="img-profile rounded-circle" src="{{ asset('sb-admin-2/img/undraw_profile.svg') }}" alt="User Profile">
            </a>

            <!-- Dropdown -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('admin.profil') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil
                </a>
                <a class="dropdown-item" href="{{ route('admin.pengaturan') }}">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Pengaturan
                </a>
                <div class="dropdown-divider"></div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</div>


</nav>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('darkModeCheckbox');
        const body = document.body;

        // Load dari localStorage
        if (localStorage.getItem('theme') === 'dark') {
            toggle.checked = true;
            body.classList.add('dark-mode');
        }

        toggle.addEventListener('change', function () {
            if (this.checked) {
                body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
            }
        });
    });
</script>

