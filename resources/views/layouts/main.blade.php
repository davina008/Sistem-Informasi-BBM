<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMABBM')</title>

    <!-- SB Admin 2 CSS -->
    <link href="{{ asset('sb-admin-2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('sb-admin-2/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    @stack('styles')

    <style>
        /* ===== GLOBAL ===== */
        html, body {
            height: 100%;
            font-family: 'Nunito', sans-serif;
        }

        body {
            background-color: #f0f4ff;
            color: #1f2937;
        }

        #content-wrapper {
            background-color: #f8f9fc;
            min-height: 100vh;
        }

        /* ===== DARK MODE GLOBAL ===== */
        body.dark-mode {
            background-color: #0f172a !important;
            color: #f3f4f6 !important;
        }

        body.dark-mode #content-wrapper {
            background-color: #0f172a !important;
        }

        /* ===== SIDEBAR ===== */
        body.dark-mode .sidebar {
            background: linear-gradient(180deg, #1e3a8a, #1e40af) !important;
        }

        /* ===== TOPBAR ===== */
        body.dark-mode .topbar {
            background-color: #111827 !important;
        }

        /* ===== CARD & TABLE ===== */
        body.dark-mode .card,
        body.dark-mode table,
        body.dark-mode th,
        body.dark-mode td {
            background-color: #1f2937 !important;
            color: #f3f4f6 !important;
        }

        /* ===== FOOTER (FIX MASALAH UTAMA) ===== */
        .sticky-footer {
            background-color: #f8f9fc;
            color: #1f2937;
            border-top: 1px solid #e5e7eb;
            font-size: 0.85rem;
        }

        body.dark-mode .sticky-footer {
            background-color: #020617 !important;
            color: #e5e7eb !important;
            border-top: 1px solid #1e293b;
        }

        body.dark-mode .sticky-footer span {
            color: #e5e7eb !important;
        }

        /* ===== DARK MODE TOGGLE ===== */
        #darkModeToggle {
            border: none;
            background: transparent;
            font-size: 1.3rem;
            cursor: pointer;
            color: #fff;
        }
    </style>
</head>

<body id="page-top">

<div id="wrapper">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    <div id="content-wrapper" class="d-flex flex-column">

        {{-- Topbar --}}
        @include('partials.topbar')

        {{-- Konten --}}
        <div class="container-fluid py-4 flex-grow-1">
            @yield('content')
        </div>

        {{-- Footer --}}
        @include('partials.footer')

    </div>
</div>

<!-- SB Admin 2 Scripts -->
<script src="{{ asset('sb-admin-2/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('sb-admin-2/js/sb-admin-2.min.js') }}"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')

<!-- ================= SWEETALERT FLASH MESSAGE ================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });

    @if (session('success'))
        Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
    @endif

    @if (session('error'))
        Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
    @endif

    @if (session('warning'))
        Toast.fire({ icon: 'warning', title: "{{ session('warning') }}" });
    @endif

    @if (session('info'))
        Toast.fire({ icon: 'info', title: "{{ session('info') }}" });
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            Toast.fire({ icon: 'error', title: "{{ $error }}" });
        @endforeach
    @endif
});
</script>

<!-- ================= DARK MODE SCRIPT ================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("darkModeToggle");
    const isDark = localStorage.getItem("darkMode") === "true";

    if (isDark) {
        document.body.classList.add("dark-mode");
        toggleBtn && (toggleBtn.textContent = "🌞");
    } else {
        toggleBtn && (toggleBtn.textContent = "🌙");
    }

    toggleBtn?.addEventListener("click", function () {
        document.body.classList.toggle("dark-mode");
        const dark = document.body.classList.contains("dark-mode");
        toggleBtn.textContent = dark ? "🌞" : "🌙";
        localStorage.setItem("darkMode", dark);
    });
});
</script>

</body>
</html>
