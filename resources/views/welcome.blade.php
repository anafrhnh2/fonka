<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Fonka - Fun Learning</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Fredoka', 'sans-serif'],
                        },
                        colors: {
                            'fun-blue': '#E0F2FE',
                            'fun-yellow': '#FEF08A',
                            'fun-pink': '#FCE7F3',
                            'brand-blue': '#0EA5E9',
                            'brand-dark': '#0C4A6E',
                        },
                        boxShadow: {
                            'pop': '0px 6px 0px 0px rgba(0,0,0,0.1)',
                            'pop-hover': '0px 3px 0px 0px rgba(0,0,0,0.1)',
                        }
                    }
                }
            }
        </script>

        <style>
            /* Custom Background Pattern */
            body {
                background-color: #E0F2FE;
                background-image: radial-gradient(#bae6fd 2px, transparent 2px);
                background-size: 32px 32px;
            }
            .blob {
                position: absolute;
                filter: blur(40px);
                z-index: -1;
                opacity: 0.6;
            }
        </style>
    </head>
    <body class="min-h-screen flex flex-col font-sans text-slate-700 relative overflow-x-hidden">

        <div class="blob bg-yellow-200 w-96 h-96 rounded-full top-0 -left-20 animate-pulse"></div>
        <div class="blob bg-pink-200 w-80 h-80 rounded-full bottom-0 -right-20 animate-pulse" style="animation-delay: 1s;"></div>

        <nav class="w-full max-w-6xl mx-auto p-6 flex justify-between items-center z-10">
            <div class="flex items-center gap-3 bg-white px-5 py-2 rounded-full shadow-sm border-2 border-white">
                <div class="bg-brand-blue text-white w-10 h-10 flex items-center justify-center rounded-full text-xl font-bold">
                    F
                </div>
                <span class="text-2xl font-bold text-brand-dark tracking-wide">Fonka</span>
            </div>

            @if (Route::has('login'))
                <div class="flex gap-4">
    @auth
        <a href="{{ url('/dashboard') }}" class="bg-white ...">
            {{ optional(auth()->user())->name }}
        </a>
    @else
        <a href="{{ route('login') }}" class="hidden md:inline-block ...">Log in</a>

        <a href="{{ route('onboarding.child') }}" class="bg-yellow-400 ...">
            Join for Free!
        </a>
    @endauth
</div>

            @endif
        </nav>

        <main class="flex-grow flex flex-col items-center justify-center px-4 text-center z-10 mt-4 mb-12">
            
            <div class="max-w-3xl mx-auto bg-white/60 backdrop-blur-sm p-8 rounded-[3rem] border-4 border-white shadow-xl mb-12">
                <span class="inline-block py-1 px-3 rounded-full bg-green-100 text-green-700 text-sm font-bold mb-4 tracking-wide">
                    ✨ Specifically designed for Dyslexic Learners
                </span>
                <h1 class="text-5xl md:text-7xl font-bold text-brand-dark mb-6 leading-tight">
                    Let's Learn to Read <br/> 
                    <span class="text-brand-blue">The Fun Way!</span>
                </h1>
                <p class="text-xl text-slate-600 mb-8 leading-relaxed max-w-2xl mx-auto font-medium">
                    Fonka helps you play with letters, sounds, and words. <br class="hidden md:block">
                    No stress, just fun games and big stars! 🌟
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-brand-blue text-white text-2xl font-bold rounded-2xl border-b-[6px] border-sky-700 hover:border-sky-600 hover:translate-y-1 active:border-0 transition-all shadow-xl flex items-center justify-center gap-2">
                            <span>🚀</span> Start Adventure
                        </a>
                    @endif
                    <a href="#about" class="w-full sm:w-auto px-8 py-4 bg-white text-brand-blue text-xl font-bold rounded-2xl border-b-[6px] border-slate-200 hover:border-slate-300 hover:translate-y-1 active:border-0 transition-all shadow-lg">
                        Learn More
                    </a>
                </div>
            </div>

            <div id="about" class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl w-full">
                
                <div class="bg-white p-6 rounded-[2rem] border-4 border-transparent hover:border-green-200 hover:scale-105 transition-all duration-300 shadow-md flex flex-col items-center text-center group">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center text-4xl mb-4 group-hover:rotate-12 transition-transform">
                        📖
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Easy Reading</h3>
                    <p class="text-slate-500 font-medium leading-relaxed">
                        We use special letters and colors to make reading super clear and easy for your eyes.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border-4 border-transparent hover:border-purple-200 hover:scale-105 transition-all duration-300 shadow-md flex flex-col items-center text-center group">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center text-4xl mb-4 group-hover:-rotate-12 transition-transform">
                        🎮
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Cool Games</h3>
                    <p class="text-slate-500 font-medium leading-relaxed">
                        Don't just study! Play fun mini-games to unlock new levels and characters.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border-4 border-transparent hover:border-orange-200 hover:scale-105 transition-all duration-300 shadow-md flex flex-col items-center text-center group">
                    <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center text-4xl mb-4 group-hover:scale-110 transition-transform">
                        🏆
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Win Badges</h3>
                    <p class="text-slate-500 font-medium leading-relaxed">
                        Earn gold stars and awesome badges every time you learn a new word!
                    </p>
                </div>

            </div>

        </main>

        <footer class="text-center py-8 text-slate-500 font-medium text-sm z-10">
            <p>Made with ❤️ for smart kids everywhere.</p>
            <p class="opacity-60">&copy; {{ date('Y') }} Fonka Dyslexia System</p>
        </footer>

    </body>
</html>