<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&family=Lexend:wght@300;400;600;700&family=Inter+Tight:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter Tight"', 'sans-serif'],
                        lexend: ['Lexend', 'sans-serif'],
                        fredoka: ['Fredoka', 'sans-serif'],
                    },
                    colors: {
                        'brand-blue': '#E0F2FE',
                        'brand-yellow': '#FEF08A',
                        'brand-pink': '#FCE7F3',
                        'brand-green': '#84CC16',
                        'brand-dark': '#0C4A6E',
                    }
                }
            }
        }
    </script>
</head>

<body class="font-lexend text-slate-600 antialiased flex flex-col min-h-screen">

    <nav id="navbar" class="fixed top-0 z-50 w-full transition-all duration-300 bg-transparent">

        <div class="flex justify-between items-center py-5 px-7 md:px-12">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group cursor-pointer decoration-0">
                <div class="transition-transform duration-300 ease-in-out group-hover:-translate-y-1">
                    <img src="{{ asset('images/logo-fonka.png') }}" alt="Fonka Logo"
                        class="h-10 md:h-12 w-auto object-contain">
                </div>
            </a>

            <div class="hidden md:flex items-center gap-8 lg:gap-12">
                <div class="flex gap-6 lg:gap-8 text-sm font-semibold text-slate-600 items-center">
                    <a href="{{ url('/') }}" class="hover:text-brand-green transition">{{ __('Home') }}</a>
                    <a href="{{ route('about-dys.index') }}"
                        class="hover:text-brand-green transition">{{ __('About Dyslexia') }}</a>
                </div>

                <div class="flex items-center gap-6">



                    <div class="flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="text-sm font-bold text-slate-600 hover:text-brand-green">
                                    {{ auth()->user()->name }}
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-sm font-bold text-slate-600 hover:text-brand-green">
                                    {{ __('Log In') }}
                                </a>
                                <a href="{{ route('onboarding.child') }}"
                                    class="bg-brand-green text-white text-sm font-bold px-6 py-2.5 rounded-full shadow-md hover:bg-brand-green-700 transition transform hover:-translate-y-0.5">
                                    {{ __('Sign Up') }}
                                </a>
                            @endauth
                        @endif
                    </div>
                    <div class="flex items-center gap-0.5 bg-slate-100/70 p-0.5 rounded-lg border border-slate-200/60">
                        <div class="px-1.5 text-slate-400 flex items-center justify-center">
                            <i class="fa-solid fa-language text-lg"></i>
                        </div>

                        <a href="{{ url('lang/ms') }}"
                            class="px-2 py-1 text-xs font-bold uppercase tracking-wider rounded-md transition-all duration-200 {{ app()->getLocale() == 'ms' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">
                            MS
                        </a>

                        <a href="{{ url('lang/en') }}"
                            class="px-2 py-1 text-xs font-bold uppercase tracking-wider rounded-md transition-all duration-200 {{ app()->getLocale() == 'en' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">
                            EN
                        </a>
                    </div>
                </div>
            </div>

            <button id="mobile-menu-btn" class="md:hidden text-slate-600 focus:outline-none z-[60]">
                <svg id="menu-icon" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </button>
        </div>

        <div id="mobile-menu-overlay"
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[55] hidden transition-opacity duration-300 opacity-0">
        </div>
        <div id="mobile-menu"
            class="fixed top-0 right-0 h-screen w-[280px] bg-white z-[58] shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out p-8 flex flex-col">
            <div class="mt-12 flex flex-col gap-6 text-lg font-bold">

                <div class="flex items-center justify-center gap-3 bg-slate-50 py-3 rounded-xl mb-2">
                    <a href="?lang=ms"
                        class="{{ app()->getLocale() == 'ms' ? 'text-brand-green' : 'text-slate-400' }} text-sm  tracking-wider">Malay</a>
                    <span class="text-slate-300">|</span>
                    <a href="?lang=en"
                        class="{{ app()->getLocale() == 'en' ? 'text-brand-green' : 'text-slate-400' }} text-sm  tracking-wider">Eng
                        UK</a>
                </div>

                <a href="{{ route('about-dys.index') }}"
                    class="text-slate-800 hover:text-brand-green ">{{ __('About Dyslexia') }}</a>
                <a href="{{ route('about-us.index') }}"
                    class="text-slate-800 hover:text-brand-green ">{{ __('About Us') }}</a>

                <hr class="border-slate-100">

                @auth
                    <a href="{{ url('/dashboard') }}" class="text-brand-green">{{ auth()->user()->name }}</a>
                @else
                    <a href="{{ route('login') }}" class="text-slate-800 ">{{ __('Log In') }}</a>
                    <a href="{{ route('onboarding.child') }}"
                        class="bg-brand-green text-white text-center py-3 rounded-full shadow-lg">{{ __('Sign Up') }}</a>
                @endauth
            </div>
        </div>

    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

   <footer class="bg-brand-blue border-t border-slate-100 pt-16 pb-8 px-7 md:px-12">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">

            <div class="col-span-1 lg:col-span-1">
                <a href="{{ url('/') }}" class="flex items-left gap-2 mb-6 group">
                    <div class="transition-transform duration-300 ease-in-out group-hover:-translate-y-1">
                        <img src="{{ asset('images/logo-fonka.png') }}" alt="Fonka Logo"
                            class="h-10 md:h-12 w-auto object-contain">
                    </div>
                </a>
                <p class="text-slate-500 leading-relaxed font-lexend text-justify text-sm">
                    {{ __('An interactive Malay phonics learning system specially designed to help children master reading skills.') }}
                </p>
            </div>
            
            <div>
                <h4 class="font-bold text-slate-800 mb-6 uppercase tracking-wider text-sm">
                    {{ __('Navigation') }}
                </h4>
                <ul class="space-y-4 text-sm font-medium">
                    <li>
                        <a href="{{ url('/') }}" class="text-slate-500 hover:text-brand-green transition">
                            {{ __('Home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about-dys.index') }}" class="text-slate-500 hover:text-brand-green transition">
                            {{ __('About Dyslexia') }}
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-slate-800 mb-6 uppercase tracking-wider text-sm">
                    {{ __('Contact Us') }}
                </h4>
                <p class="text-slate-500 text-sm mb-4 leading-relaxed">
                    {{ __('Have questions? We are here to help.') }}
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-brand-green hover:border-brand-green transition shadow-sm">
                        <span class="sr-only">Facebook</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-brand-green hover:border-brand-green transition shadow-sm">
                        <span class="sr-only">Instagram</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

       
    </div>
</footer>

    <script>
        const navbar = document.getElementById('navbar');
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-menu-overlay');
        const menuIcon = document.getElementById('menu-icon');

        // Logic Scroll 
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('bg-white', 'shadow-md', 'py-3');
                navbar.classList.remove('bg-transparent', 'py-5');
            } else {
                navbar.classList.remove('bg-white', 'shadow-md', 'py-3');
                navbar.classList.add('bg-transparent', 'py-5');
            }
        });

        // Logic Sidebar Toggle
        function toggleMenu() {
            const isOpen = !menu.classList.contains('translate-x-full');

            if (!isOpen) {
                menu.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
                menuIcon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
                document.body.style.overflow = 'hidden';
            } else {
                menu.classList.add('translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300);
                menuIcon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>';
                document.body.style.overflow = 'auto';
            }
        }

        btn.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
    </script>

</body>

</html>
