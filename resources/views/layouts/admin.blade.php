<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Fonka Admin - @yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #050505;
            color: #ffffff;
        }

        @keyframes slideInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulseGlow {

            0%,
            100% {
                box-shadow: 0 0 10px rgba(255, 42, 133, 0.4);
            }

            50% {
                box-shadow: 0 0 20px rgba(255, 42, 133, 0.8), inset 0 0 10px rgba(255, 42, 133, 0.2);
            }
        }

        @keyframes borderSweep {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 200% 50%;
            }
        }

        .animate-enter {
            opacity: 0;
            animation: slideInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        .delay-300 {
            animation-delay: 300ms;
        }

        /* --- Tema Neon Blackpink --- */
        .neon-text {
            text-shadow: 0 0 10px rgba(255, 42, 133, 0.6), 0 0 20px rgba(255, 42, 133, 0.3);
        }

        .neon-border {
            border: 1px solid rgba(255, 42, 133, 0.3);
            transition: all 0.3s ease;
        }

        .neon-border:hover {
            border-color: #ff2a85;
            box-shadow: 0 0 15px rgba(255, 42, 133, 0.2);
            transform: translateY(-5px);
        }

        .sidebar {
            background: rgba(18, 18, 18, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 42, 133, 0.2);
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-closed {
            margin-left: -16rem;
        }

        .nav-item {
            position: relative;
            transition: all 0.3s ease;
            color: #a1a1aa;
        }

        .nav-item:hover,
        .nav-item.active {
            color: #ffffff;
            background: linear-gradient(90deg, rgba(255, 42, 133, 0.15) 0%, rgba(18, 18, 18, 0) 100%);
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: #ff2a85;
            transform: scaleY(0);
            transition: transform 0.3s ease;
            box-shadow: 0 0 10px #ff2a85;
        }

        .nav-item:hover::before,
        .nav-item.active::before {
            transform: scaleY(1);
        }

        .stat-card {
            background: linear-gradient(145deg, #121212 0%, #0a0a0a 100%);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #ff2a85, #9D4EDD, transparent);
            background-size: 200% 100%;
            animation: borderSweep 3s linear infinite;
        }

        .icon-glow-pink {
            filter: drop-shadow(0 0 8px rgba(255, 42, 133, 0.8));
        }

        .icon-glow-purple {
            filter: drop-shadow(0 0 8px rgba(157, 78, 221, 0.8));
        }
    </style>
    @stack('styles')
</head>

<body class="flex h-screen overflow-hidden relative">

    <div
        class="absolute top-0 left-[20%] w-[40rem] h-[40rem] bg-[#ff2a85] rounded-full blur-[180px] opacity-10 pointer-events-none z-0">
    </div>

    <aside id="sidebar" class="sidebar w-64 h-full flex flex-col flex-shrink-0 z-30 relative">
        <div class="h-20 flex items-center px-6 border-b border-[#333]">
            <a href="{{ url('/') }}" class="inline-block text-decoration-none">
                <img src="{{ asset('images/logo-fonka.png') }}" alt="Fonka Logo"
                    class="sidebar-logo h-12 w-auto object-contain transition-transform duration-300 hover:scale-105">
            </a>
        </div>

        <nav class="flex-1 py-6 space-y-2 px-3 overflow-y-auto">
            <p class="text-xs font-bold text-[#ff99d6] uppercase tracking-wider px-3 mb-4 opacity-70">Main Menu</p>

            <a href="{{ route('admin.dashboard') }}" @class([
                'nav-item flex items-center gap-4 px-4 py-3 rounded-lg font-medium',
                'active' => request()->routeIs('admin.dashboard'),
            ])>
                <i class="fa-solid fa-border-all w-5"></i> Dashboard
            </a>

            <a href="{{ route('admin.manage-users') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-lg font-medium">
                <i class="fa-solid fa-users w-5"></i> Manage Users
            </a>

            <a href="{{ route('admin.manage-game-levels') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-lg font-medium">
                <i class="fa-solid fa-book-open w-5"></i> Game Modules
            </a>

            <a href="{{ route('admin.manage-reading') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-lg font-medium">
                <i class="fa-solid fa-book-open w-5"></i> Reading Modules
            </a>

            <a href="{{ route('admin.manage-avatars') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-lg font-medium">
                <i class="fa-solid fa-user-astronaut w-5"></i> Manage Game Avatar
            </a>
        </nav>

        <div class="p-4 border-t border-[#333]">
            {{-- <div class="bg-[#0a0a0a] rounded-xl p-3 border border-[#ff2a85]/30 flex items-center gap-3 mb-3">
                <div
                    class="w-10 h-10 rounded-full bg-[#ff2a85]/20 flex items-center justify-center border border-[#ff2a85]">
                    <i class="fa-solid fa-user-astronaut text-[#ff99d6]"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Super Admin' }}</p>
                </div>
            </div> --}}

            <form method="POST" action="/admin/logout">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg text-sm font-bold text-white bg-red-900/40 hover:bg-[#ff2a85] transition-colors duration-300 border border-red-500/30 hover:border-[#ff2a85]">
                    <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full relative z-10 overflow-y-auto transition-all duration-300">

        <header
            class="h-20 px-8 flex items-center justify-between border-b border-[#333] bg-[#121212]/80 backdrop-blur-sm sticky top-0 z-20">
            <div class="flex items-center gap-5">

                <button id="toggleSidebar"
                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-[#2a2a2a] hover:bg-[#ff2a85]/20 hover:text-[#ff2a85] text-white transition-all neon-border focus:outline-none">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div>
                    <h2 class="text-xl font-bold hidden sm:block">@yield('page_title', 'Welcome back')</h2>
                    <p class="text-sm text-gray-400 hidden sm:block">@yield('page_subtitle', "Here's what's happening today.")</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <p class="text-sm font-medium text-gray-400 hidden md:block">
                    <i class="fa-regular fa-calendar text-[#9D4EDD] me-2"></i> {{ now()->format('l, d M Y') }}
                </p>
    
            </div>
        </header>

        <div class="p-8 max-w-7xl mx-auto w-full">
            @yield('content')
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('sidebar-closed');
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
