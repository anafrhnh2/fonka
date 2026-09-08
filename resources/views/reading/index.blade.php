<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Modul Bacaan</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700;800&family=Lexend:wght@400;700;900&display=swap"
        rel="stylesheet">

    <style>
        @font-face {
            font-family: 'KG Red Hands';
            src: url('{{ asset('fonts/KGRedHands.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        *:not(.fa):not(.fas):not(.far):not(.fal):not(.fab):not(.fa-solid):not(.fa-regular):not(.fa-brands) {
            font-family: 'KG Red Hands', sans-serif !important;
            user-select: none !important;
            letter-spacing: 1.7px;
            -webkit-user-select: none !important;
            -ms-user-select: none !important;
        }

        body {
            background-image: url('{{ asset('images/game-bg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-color: #E0F2FE;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .membaca-page-wrapper {
            min-height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(255, 255, 255, 0.15);
        }

        /* Enhanced Card Hover Effects */
        .module-card-container {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .module-card-container:hover {
            transform: translateY(-8px) scale(1.02);
        }

        .module-card-container:active {
            transform: translateY(2px) scale(0.98);
        }

        .module-card-shadow {
            box-shadow: 0 12px 0px 0px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .module-card-container:hover .module-card-shadow {
            box-shadow: 0 18px 0px 0px rgba(0, 0, 0, 0.12);
        }

        .module-card-container:active .module-card-shadow {
            box-shadow: 0 4px 0px 0px rgba(0, 0, 0, 0.2);
        }

        .btn-push {
            transition: all 0.1s ease;
            cursor: pointer;
        }

        .btn-bubbly {
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: 0 6px 0 rgba(0, 0, 0, 0.15);
            position: relative;
            top: 0;
        }

        .btn-bubbly:hover {
            transform: scale(1.05);
        }

        .btn-bubbly:active {
            top: 4px;
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.15);
        }

        .btn-orange {
            background-color: #ff9800;
            color: white;
            border-bottom: 5px solid #e65100;
        }

        .btn-audio {
            background-color: #4fc3f7;
            color: white;
            border-bottom: 5px solid #0288d1;
        }

        .btn-circle-green {
            background-color: #4caf50;
            color: white;
            border-bottom: 5px solid #2e7d32;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .btn-push:active {
            transform: translateY(4px);
        }

        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .header-glass {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            border: 3px solid rgba(255, 255, 255, 0.5);
            border-radius: 2rem;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Loading Overlay Animations */
        #loading-overlay {
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        #loading-overlay.is-active {
            visibility: visible;
            opacity: 1;
        }

        .book-bounce {
            animation: bounceBook 1s infinite alternate cubic-bezier(0.455, 0.03, 0.515, 0.955);
        }

        @keyframes bounceBook {
            0% {
                transform: translateY(0) scale(1);
            }

            100% {
                transform: translateY(-15px) scale(1.1);
                text-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            }
        }
    </style>
</head>

@include('layouts.header-game')

<body class="hide-scroll">

    <div id="loading-overlay"
        class="fixed inset-0 z-[9999] bg-indigo-900/90 backdrop-blur-md flex flex-col items-center justify-center">
        <div class="relative flex flex-col items-center">
            <div class="w-32 h-32 border-[8px] border-indigo-200/20 rounded-full absolute"></div>
            <div
                class="w-32 h-32 border-[8px] border-yellow-400 rounded-full border-t-transparent animate-spin absolute">
            </div>

            <div class="w-32 h-32 flex items-center justify-center text-white text-5xl z-10 book-bounce">
                <i class="fa-solid fa-book-open-reader text-yellow-300"></i>
            </div>
        </div>

        <div class="mt-10 bg-white/20 px-8 py-3 rounded-full border-2 border-white/30 backdrop-blur-sm">
            <h2 class="text-white text-2xl md:text-3xl font-black tracking-widest animate-pulse">
                Memuatkan...
            </h2>
        </div>
    </div>

    <div class="membaca-page-wrapper pt-12 pb-40 w-full">

        <div class="bottom-grass pointer-events-none"></div>

        <div class="relative z-10 w-full flex flex-col items-center justify-start flex-1">

            <div
                class="text-center mb-16 px-4 header-glass transform hover:scale-[1.02] transition-transform duration-300">
                <h1
                    class="text-5xl md:text-7xl font-black text-orange-400 drop-shadow-[0_6px_0_#ef4444] mb-4 tracking-tight">
                    Buku Bacaan
                </h1>
                <div class="inline-block bg-indigo-600 px-8 py-3 rounded-full shadow-lg border-2 border-indigo-400">
                    <p class="text-lg md:text-xl text-white font-bold tracking-wide">Pilih modul untuk mula belajar!</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-24 w-full max-w-7xl px-6 md:px-16">

                @foreach ($modules as $index => $module)
                    @php
                        $colors = ['4ade80', '60a5fa', 'f472b6', 'fbbf24', 'a78bfa'];
                        $bgColor = $colors[$index % count($colors)];

                        $imagePath = !empty($module->image)
                            ? asset('images/games/thumbnail/' . $module->image)
                            : "https://placehold.co/400x300/{$bgColor}/FFFFFF?text=" .
                                urlencode($module->title ?? 'Modul');

                        $moduleProgress = $progress[$module->id] ?? null;
                        $percentage = $moduleProgress ? $moduleProgress->progress_percentage : 0;
                    @endphp

                    <div class="relative flex flex-col items-center module-card-container">

                        <a href="{{ route('reading.learn', ['module' => $module->id]) }}"
                            class="relative w-full aspect-[4/3] flex items-center justify-center z-20 trigger-loader">

                            <div
                                class="w-full h-full bg-white rounded-[2rem] p-3 module-card-shadow border-b-[6px] border-slate-300">
                                <div class="w-full h-full rounded-[1.2rem] overflow-hidden relative bg-gray-100">

                                    <img src="{{ $imagePath }}"
                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                                        alt="Module Image">

                                    {{-- <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/90 via-black/20 to-transparent"></div> --}}

                                    <div
                                        class="absolute bottom-0 left-0 w-full h-6 bg-slate-900/50 rounded-full border-2 border-slate-900/30 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-1000 relative
        {{ $percentage == 100 ? 'bg-gradient-to-b from-emerald-300 to-emerald-600 shadow-[0_0_15px_#4ade80,inset_0_-4px_0_#059669]' : 'bg-gradient-to-b from-green-400 to-green-700 shadow-[inset_0_-4px_0_#15803d]' }}"
                                            style="width: {{ $percentage }}%; box-shadow: inset 0 -4px 0 rgba(0,0,0,0.2), inset 0 4px 0 rgba(255,255,255,0.2);">

                                            <div class="absolute top-1 left-1 right-1 h-[35%] bg-white/40 rounded-full">
                                            </div>
                                        </div>
                                    </div>

                                    @if ($percentage > 0)
                                        <div
                                            class="absolute top-3 right-3 bg-white text-[10px] md:text-xs font-black px-3 py-1.5 rounded-full shadow-lg z-10 {{ $percentage == 100 ? 'text-green-600 border-2 border-green-200' : 'text-orange-500 border-2 border-orange-200' }}">
                                            @if ($percentage == 100)
                                                <i class="fa-solid fa-check-circle"></i> SIAP
                                            @else
                                                <i class="fa-solid fa-spinner fa-spin-pulse mr-1"></i>
                                                {{ $percentage }}%
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>

                        <div
                            class="absolute -bottom-6 w-14 h-14 bg-white text-indigo-600 rounded-2xl flex items-center justify-center font-black text-2xl border-[4px] border-indigo-400 shadow-[0_4px_10px_rgba(0,0,0,0.2)] z-30 transform rotate-3">
                            {{ $module->module_number ?? $loop->iteration }}
                        </div>

                    </div>
                @endforeach

            </div>

            <div class="mt-28 mb-10 z-20">
                <a href="{{ route('games.levels') }}"
                    class="btn-push bg-indigo-600 border-b-[6px] border-indigo-900 hover:bg-indigo-500 text-white font-black px-12 py-4 rounded-full shadow-2xl inline-flex items-center gap-4 text-xl tracking-wider trigger-loader">
                    <i class="fa-solid fa-house text-2xl"></i>
                    KEMBALI KE MENU
                </a>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loadingOverlay = document.getElementById('loading-overlay');
            const loadTriggers = document.querySelectorAll('.trigger-loader');

            loadTriggers.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Prevent default immediate navigation
                    e.preventDefault();
                    const destination = this.getAttribute('href');

                    // Show the overlay
                    loadingOverlay.classList.add('is-active');

                    // Navigate after a tiny delay so the UI thread can paint the overlay
                    setTimeout(() => {
                        window.location.href = destination;
                    }, 150);
                });
            });
        });

        // Ensure loader is hidden when navigating back via browser history (BFCache)
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                document.getElementById('loading-overlay').classList.remove('is-active');
            }
        });
    </script>

</body>

</html>
