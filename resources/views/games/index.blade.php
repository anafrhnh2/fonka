<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Menu Utama</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

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

        /* --- 2. Body Settings --- */
        body {
            background-color: #E0F2FE;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- 3. Image Background Styles --- */
        .image-background {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -2;
            object-fit: cover;
            pointer-events: none;
        }

        /* --- 4. HUD/Header Utilities --- */
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

        .btn-bubbly:hover { transform: scale(1.05); }
        .btn-bubbly:active { top: 4px; box-shadow: 0 2px 0 rgba(0, 0, 0, 0.15); }

        /* Game Card & Custom Elements (Mobile First CSS) */
        .game-card {
            display: block;
            border-radius: 2rem;
            border-bottom-width: 10px;
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-decoration: none;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .game-card:hover {
            transform: translateY(-6px);
            border-bottom-width: 14px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.15);
        }

        .game-card:active {
            transform: translateY(8px);
            border-bottom-width: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.1s;
        }

        /* Saiz lalai untuk Telefon Mudah Alih */
        .card-inner {
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .icon-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 4px solid white;
        }

        .card-title {
            font-size: 1.75rem;
            font-weight: 900;
            text-align: center;
            margin-bottom: 1.25rem;
            line-height: 1.2;
            letter-spacing: 0.025em;
        }

        .action-pill {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .game-card:hover .action-pill { transform: scale(1.1); }

        /* Saiz untuk Skrin Besar (Tablet & Desktop) */
        @media (min-width: 768px) {
            .game-card { border-radius: 2.5rem; border-bottom-width: 14px; }
            .game-card:hover { border-bottom-width: 18px; transform: translateY(-8px); }
            
            .card-inner { padding: 2.5rem 1.5rem; }
            .icon-circle { width: 140px; height: 140px; margin-bottom: 2rem; border-width: 6px; }
            .card-title { font-size: 2.25rem; margin-bottom: 1.5rem; }
            .action-pill { width: 70px; height: 70px; border-width: 4px; }
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body>

    <img src="{{ asset('images/game-bg.png') }}" alt="Background Latar Belakang" class="image-background">

    <div class="overlay"></div>

    @php
        $activeChild = \App\Models\Child::find(session('active_child_id'));
        $totalPoints = $activeChild ? $activeChild->total_point : 0;

        $currentAvatarId = $activeChild->avatar_id ?? null;

        $avatarPath = asset('images/default_avatar.png');

        if ($currentAvatarId && isset($ownedAvatars)) {
            $foundAvatar = collect($ownedAvatars)->firstWhere('id', $currentAvatarId);

            if ($foundAvatar) {
                $avatarPath = asset($foundAvatar->path);
            }
        }
    @endphp

    @include('layouts.header-game')

    <main class="flex-grow flex flex-col items-center justify-start md:justify-center p-4 md:p-8 w-full z-10 overflow-hidden">

        <div class="h-full w-full flex flex-col items-center justify-start md:justify-center overflow-y-auto no-scrollbar pb-16 pt-4 md:pt-0">

            <div class="text-center mb-8 md:mb-14 w-full max-w-4xl mt-4 md:mt-0">
                <h1 class="text-4xl md:text-7xl font-black text-orange-400 drop-shadow-[0_4px_0_#ef4444] md:drop-shadow-[0_6px_0_#ef4444] mb-3 md:mb-4 tracking-tight">
                    Mula Belajar
                </h1>
                <p class="text-lg md:text-2xl text-slate-700 font-bold bg-white/70 backdrop-blur-md border-2 border-white/80 inline-block px-6 py-2 md:px-8 md:py-3 rounded-full shadow-sm">
                    Pilih kotak di bawah untuk mula.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-12 w-full max-w-6xl px-2 md:px-4">

                <a href="{{ route('games.levels') }}" class="game-card group bg-green-100 hover:bg-green-200 border-green-400">
                    <div class="card-inner">
                        <div class="icon-circle bg-green-400 text-white group-hover:scale-110 group-hover:rotate-[-10deg] shadow-[0_6px_0_rgb(74,222,128)] md:shadow-[0_8px_0_rgb(74,222,128)]">
                            <i class="fas fa-gamepad text-5xl md:text-7xl"></i>
                        </div>
                        <h3 class="card-title text-green-800">Mari Bermain</h3>
                        <div class="action-pill bg-green-400 text-white">
                            <i class="fa-solid fa-play text-xl md:text-2xl"></i>
                        </div>
                    </div>
                </a>

                <a href="{{ route('reading.index') }}" class="game-card group bg-sky-100 hover:bg-sky-200 border-sky-400">
                    <div class="card-inner">
                        <div class="icon-circle bg-sky-400 text-white group-hover:scale-110 group-hover:rotate-[10deg] shadow-[0_6px_0_rgb(2,132,199)] md:shadow-[0_8px_0_rgb(2,132,199)]">
                            <i class="fa-solid fa-book-open text-5xl md:text-7xl"></i>
                        </div>
                        <h3 class="card-title text-sky-800">Jom Membaca</h3>
                        <div class="action-pill bg-sky-400 text-white">
                            <i class="fa-solid fa-play text-xl md:text-2xl"></i>
                        </div>
                    </div>
                </a>

                <a href="{{ route('achievement.index') }}" class="game-card group bg-amber-100 hover:bg-amber-200 border-amber-400">
                    <div class="card-inner">
                        <div class="icon-circle bg-amber-400 text-white group-hover:scale-110 group-hover:animate-bounce shadow-[0_6px_0_rgb(217,119,6)] md:shadow-[0_8px_0_rgb(217,119,6)]">
                            <i class="fa-solid fa-trophy text-5xl md:text-7xl"></i>
                        </div>
                        <h3 class="card-title text-amber-800">Pencapaian</h3>
                        <div class="action-pill bg-amber-400 text-white">
                            <i class="fa-solid fa-star text-xl md:text-2xl"></i>
                        </div>
                    </div>
                </a>

            </div>
        </div>

    </main>

    @if (View::hasSection('instructions'))
        <div id="helpModalContent" class="hidden">
            @yield('instructions')
        </div>
    @endif

</body>

</html>