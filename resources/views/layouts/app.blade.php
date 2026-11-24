<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMABBM</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f8f9fc;
            color: #111827;
            transition: background-color 0.4s, color 0.4s;
        }

        .sidebar {
            width: 220px;
            position: fixed;
            height: 100%;
            background: #4e73df;
            color: white;
            transition: background-color 0.4s;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
            background-color: #f8f9fc;
            transition: background-color 0.4s, color 0.4s;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background-color: #ffffff;
            transition: background-color 0.4s, color 0.4s;
        }

        /* Tombol dark mode */
        #darkModeToggle {
            border: none;
            background: transparent;
            font-size: 1.4rem;
            cursor: pointer;
            color: #111827;
            transition: transform 0.2s ease, color 0.3s;
        }

        #darkModeToggle:hover {
            transform: scale(1.2);
            color: #2563eb;
        }

        /* === DARK MODE === */
        body.dark-mode {
            background-color: #0f172a !important;
            color: #f3f4f6 !important;
        }

        body.dark-mode .sidebar {
            background-color: #111827 !important;
        }

        body.dark-mode .content {
            background-color: #0f172a !important;
        }

        /* NAVBAR FIX */
        body.dark-mode .navbar {
            background-color: #1e293b !important;
            color: #f3f4f6 !important;
            border-bottom: 1px solid #374151 !important;
        }

        body.dark-mode .navbar span,
        body.dark-mode .navbar #darkModeToggle {
            color: #f3f4f6 !important;
        }

        /* Footer */
        footer {
            background: transparent;
            transition: background-color 0.4s, color 0.4s;
        }

        body.dark-mode footer {
            background-color: #0f172a !important;
            color: #9ca3af !important;
            border-top: 1px solid #1f2937;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        @include('partials.sidebar')

        <div class="content flex-grow-1">
            <nav class="navbar shadow-sm mb-4 p-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold">Admin</span>

                <!-- Tombol Dark Mode -->
                <button id="darkModeToggle" title="Toggle Dark Mode">🌙</button>
            </nav>

            <div class="container-fluid">
                @yield('content')
            </div>

            <footer class="text-center mt-4 mb-2 text-muted py-2">
                © SIMABBM 2025
            </footer>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleButton = document.getElementById("darkModeToggle");
        const body = document.body;

        const isDark = localStorage.getItem("darkMode") === "true";
        if (isDark) {
            body.classList.add("dark-mode");
            toggleButton.textContent = "🌞";
        }

        toggleButton.addEventListener("click", function() {
            body.classList.toggle("dark-mode");
            const dark = body.classList.contains("dark-mode");
            toggleButton.textContent = dark ? "🌞" : "🌙";
            localStorage.setItem("darkMode", dark);
        });
    });
    </script>
</body>
</html>
