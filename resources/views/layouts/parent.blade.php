<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Parent Portal')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- Color Palette & Base Styles --- */
        :root {
            --primary: #84CC16;
            --primary-light: #ede9fe;
            --secondary: #3b82f6;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #334155;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* --- Sidebar Navigation --- */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--card-bg);
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.03);
            transition: transform 0.3s ease;
            z-index: 1040;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.5rem 1.5rem;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand i {
            background: var(--primary-light);
            padding: 10px;
            border-radius: 12px;
        }

        /* Sidebar Logo Sizing */
        .sidebar-logo-wrapper {
            padding: 2rem 1.5rem 1rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
        }

        .sidebar-logo {
            max-width: 150px;
            max-height: 60px;
            width: 100%;
            height: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .sidebar-logo:hover {
            transform: scale(1.05);
        }

        .nav-link-custom {
            color: var(--text-muted);
            border-radius: 12px;
            margin: 0.3rem 1.25rem;
            padding: 0.8rem 1.25rem;
            font-weight: 700;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-link-custom i {
            width: 25px;
            font-size: 1.1rem;
        }

        .nav-link-custom:hover,
        .nav-link-custom.active {
            background-color: var(--primary-light);
            color: var(--primary);
            transform: translateX(5px);
        }

        /* --- Main Content --- */
        .main-content {
            margin-left: 260px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            padding: 2rem;
        }

        /* --- GLOBAL TOP BAR --- */
        .global-topbar {
            background-color: var(--card-bg);
            margin: -2rem -2rem 2rem -2rem;
            padding: 1rem 2.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            position: relative;
        }

        .top-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .hamburger {
            display: block;
            /* Visible on all screen sizes now */
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-main);
            cursor: pointer;
            padding: 0.5rem;
            transition: color 0.3s ease;
        }

        .hamburger:hover {
            color: var(--primary);
        }

        .card-custom {
            background-color: var(--card-bg);
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        /* Additional color classes */
        .bg-purple-soft {
            background-color: #ede9fe;
            color: #8b5cf6;
        }

        .bg-blue-soft {
            background-color: #dbeafe;
            color: #3b82f6;
        }

        .bg-green-soft {
            background-color: #dcfce7;
            color: #22c55e;
        }

        .bg-orange-soft {
            background-color: #ffedd5;
            color: #f97316;
        }

        /* --- Desktop Toggle Responsiveness --- */
        @media (min-width: 993px) {
            .sidebar.collapsed {
                transform: translateX(-100%);
            }

            .main-content.expanded {
                margin-left: 0;
            }
        }

        /* --- Mobile Responsiveness --- */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1030;
            backdrop-filter: blur(2px);
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1.5rem;
            }

            .global-topbar {
                margin: -1.5rem -1.5rem 1.5rem -1.5rem;
                padding: 1rem 1.5rem;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Styling tambahan untuk Dropdown Profil */
        .parent-profile-dropdown .dropdown-toggle::after {
            display: none;
        }

        .parent-profile-dropdown .dropdown-menu {
            min-width: 200px;
            border-radius: 1rem;
            margin-top: 10px !important;
        }

        .parent-profile-dropdown .dropdown-item {
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            color: var(--text-main);
            transition: all 0.2s;
        }

        .parent-profile-dropdown .dropdown-item:hover {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        /* Other utility styles */
        .profile-hero {
            background: white;
            border-radius: 2rem;
            border: 1px solid var(--card-border);
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        }

        .avatar-main {
            width: 140px;
            height: 140px;
            border-radius: 2.5rem;
            object-fit: cover;
            border: 6px solid #fff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .glass-card {
            background: white;
            border-radius: 1.5rem;
            border: 1px solid rgb(191, 191, 191);
            padding: 2rem;
            height: 100%;
            transition: 0.3s;
        }

        .glass-card:hover {
            border-color: var(--primary);
        }

        .form-control-modern {
            border-radius: 1rem;
            padding: 0.85rem 1.2rem;
            border: 2px solid rgb(191, 191, 191);
            background: var(--input-bg);
            font-weight: 600;
            transition: 0.3s;
        }

        .form-control-modern:focus {
            background: white;
            border-color: var(--primary);
            outline: none;
        }

        .pref-item {
            background: var(--input-bg);
            border-radius: 1.25rem;
            padding: 1.2rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-modern {
            border-radius: 2rem;
            padding: 0.8rem 2rem;
            font-weight: 700;
            transition: 0.3s;
        }

        .btn-primary-modern {
            background: var(--primary);
            color: white;
            border: none;
        }

        .btn-primary-modern:hover {
            transform: translateY(-3px);
            color: white;
            background-color: #4F7A0D;
        }

        .stat-pill {
            background: #fff;
            border: 1px solid var(--card-border);
            padding: 0.5rem 1.2rem;
            border-radius: 50rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
        }

        @yield('styles')
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <nav class="sidebar flex flex-col h-screen" id="sidebar">

        <div class="sidebar-logo-wrapper">
            <a href="{{ url('/') }}" class="d-inline-block text-decoration-none">
                <img src="{{ asset('images/logo-fonka.png') }}" alt="Fonka Logo" class="sidebar-logo">
            </a>
        </div>

        <div class="d-flex flex-column gap-1 px-3">
            <a href="{{ route('parents.index') }}" @class([
                'nav-link-custom',
                'active' => request()->routeIs('parents.index'),
            ])>
                <i class="fa-solid fa-chart-pie w-6"></i> Overview
            </a>

            <a href="{{ route('parents.child-profile') }}" @class([
                'nav-link-custom',
                'active' => request()->routeIs('parents.child-profile'),
            ])>
                <i class="fa-solid fa-child-reaching w-6"></i> Child Profile
            </a>

            <a href="{{ route('parents.progress') }}" @class([
                'nav-link-custom',
                'active' => request()->routeIs('parents.progress'),
            ])>
                <i class="fa-solid fa-arrow-trend-up w-6"></i> Learning Progress
            </a>

            <a href="{{ route('parents.achievement') }}" @class([
                'nav-link-custom',
                'active' => request()->routeIs('parents.achievement'),
            ])>
                <i class="fa-solid fa-medal w-6"></i> Achievement
            </a>

        </div>

        <div class="mt-auto p-4 border-top" style="border-color: rgba(0,0,0,0.05);">
            <a href="{{ route('dashboard') }}"
                class="btn btn-light w-100 rounded-4 fw-bold text-muted shadow-sm border py-2 d-flex align-items-center justify-content-center gap-2 text-decoration-none"
                style="background-color: #f8fafc; border-color: #e2e8f0; transition: all 0.3s ease;"
                onmouseover="this.style.backgroundColor='#e2e8f0'; this.style.color='#334155';"
                onmouseout="this.style.backgroundColor='#f8fafc'; this.style.color='#6c757d';">
                <i class="fa-solid fa-gauge transition-transform"></i> Switch to Dashboard
            </a>
        </div>
    </nav>

    <main class="main-content">

        <div class="global-topbar d-print-none">

            <button class="hamburger" id="sidebarToggle" aria-label="Toggle Navigation">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="dropdown parent-profile-dropdown ms-auto">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                    id="parentProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">

                    <div class="me-3 text-end d-none d-sm-block">
                        <span class="d-block fw-bold text-dark lh-1" style="font-size: 0.95rem;">
                            {{ Auth::user()->name ?? 'Parent Account' }}
                        </span>
                    </div>

                    @if (Auth::user()->profile)
                        <img src="{{ asset('storage/' . Auth::user()->profile) }}" alt="Profile"
                            class="rounded-circle shadow-sm border border-2 border-white"
                            style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Parent') }}&background=ede9fe&color=8b5cf6&size=128"
                            alt="Profile" class="rounded-circle shadow-sm border border-2 border-white"
                            style="width: 50px; height: 50px; object-fit: cover;">
                    @endif
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2"
                    aria-labelledby="parentProfileDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('parents.account') }}"> <i
                                class="fa-solid fa-user me-2 text-primary"></i> My Account
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider opacity-25">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger fw-bold">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Universal Sidebar Toggle Logic for Desktop & Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const mainContent = document.querySelector('.main-content');

        function toggleMenu() {
            // Check window width to determine if we apply mobile or desktop behavior
            if (window.innerWidth <= 992) {
                // Mobile Behavior: Show sidebar and overlay
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                // Desktop Behavior: Collapse sidebar and stretch main content
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        }

        if (sidebarToggle && sidebar && overlay) {
            sidebarToggle.addEventListener('click', toggleMenu);
            overlay.addEventListener('click', toggleMenu); // Clicking overlay on mobile closes sidebar
        }
    </script>

    @yield('scripts')

</body>

</html>
