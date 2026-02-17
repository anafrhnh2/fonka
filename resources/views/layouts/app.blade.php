<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fonka - Fun Learning</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        lexend: ['Lexend', 'sans-serif'],
                        fredoka: ['Fredoka', 'sans-serif'],
                    },
                    colors: {
                        'brand-blue': '#2D8CFF',
                        'brand-orange': '#EA580C',
                    }
                }
            }
        }
    </script>
</head>

<body class="font-lexend text-slate-600 antialiased flex flex-col min-h-screen">

    <nav class="sticky top-0 z-50 w-full bg-white shadow-sm">
        
        <div class="flex justify-between items-center py-5 px-7 md:px-12">
            
            <a href="{{ url('/') }}" class="flex items-center gap-3 group cursor-pointer decoration-0">
                <div class="font-fredoka text-4xl md:text-5xl font-bold tracking-wide flex select-none">
                    <span class="text-yellow-400 group-hover:-translate-y-2 transition-transform duration-300 ease-in-out" style="transition-delay: 0ms">f</span>
                    <span class="text-blue-500   group-hover:-translate-y-2 transition-transform duration-300 ease-in-out" style="transition-delay: 75ms">o</span>
                    <span class="text-lime-500   group-hover:-translate-y-2 transition-transform duration-300 ease-in-out" style="transition-delay: 150ms">n</span>
                    <span class="text-rose-400   group-hover:-translate-y-2 transition-transform duration-300 ease-in-out" style="transition-delay: 225ms">k</span>
                    <span class="text-orange-500 group-hover:-translate-y-2 transition-transform duration-300 ease-in-out" style="transition-delay: 300ms">a</span>
                </div>
            </a>

            <div class="hidden md:flex gap-6 lg:gap-8 text-sm font-semibold text-slate-600 items-center">
                <a href="#" class="hover:text-brand-blue transition">Home</a>
                <a href="#about" class="hover:text-brand-blue transition">About Us</a>
                <a href="#about" class="hover:text-brand-blue transition">About Dyslexia</a>
                <a href="#about" class="hover:text-brand-blue transition">How it Works</a>
                <a href="{{ route('reading.index') }}" class="hover:text-brand-blue transition">Features</a>
                <a href="{{ route('reading.index') }}" class="hover:text-brand-blue transition">Help</a>
            </div>

            <div class="hidden md:flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-slate-600 hover:text-brand-blue">
                            {{ auth()->user()->name }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-brand-blue">Log in</a>
                        <a href="{{ route('register') }}" class="bg-blue-500  text-white text-sm font-bold px-6 py-2.5 rounded-full shadow-md hover:bg-blue-700  transition transform hover:-translate-y-0.5">
                            Join Free
                        </a>
                    @endauth
                @endif
            </div>

            <button id="mobile-menu-btn" class="md:hidden text-slate-600 focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>

        <div id="mobile-menu" class="hidden md:hidden border-t bg-gray-50 px-6 py-4 shadow-inner">
            <div class="flex flex-col gap-4 text-sm font-semibold text-slate-600">
                <a href="#" class="hover:text-brand-blue">Home</a>
                <a href="#about" class="hover:text-brand-blue">About Us</a>
                <a href="#about" class="hover:text-brand-blue">About Dyslexia</a>
                <a href="#about" class="hover:text-brand-blue">How it Works</a>
                <a href="{{ route('reading.index') }}" class="hover:text-brand-blue">Features</a>
                <a href="{{ route('reading.index') }}" class="hover:text-brand-blue">Help</a>
                
                <hr class="border-gray-300 my-2">

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-brand-blue font-bold">Dashboard ({{ auth()->user()->name }})</a>
                    @else
                        <a href="{{ route('login') }}" class="font-bold">Log in</a>
                        <a href="{{ route('register') }}" class="bg-brand-orange text-white text-center font-bold px-4 py-3 rounded-xl shadow-sm">
                            Join Free
                        </a>
                    @endauth
                @endif
            </div>
        </div>

    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="text-center py-12 text-slate-400 text-sm mt-auto bg-slate-50">
        &copy; {{ date('Y') }} Fonka Dyslexia Learning System
    </footer>

    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>

</body>

</html>