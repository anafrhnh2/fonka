<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Tahap Permainan</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        body {
            margin: 0;
            overflow: hidden;
            background: linear-gradient(180deg, #74ebd5 0%, #9face6 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Responsif Scroll Container */
        .map-scroll-container {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }

        /* Telefon Mudah Alih (Menegak) */
        @media (max-width: 767px) {
            .map-scroll-container {
                overflow-x: hidden;
                overflow-y: auto;
                height: 100vh;
                padding-top: 100px;
                padding-bottom: 120px;
            }

            .map-scroll-container::-webkit-scrollbar {
                width: 8px;
            }
        }

        /* Skrin Besar/Tablet (Mendatar) */
        @media (min-width: 768px) {
            .map-scroll-container {
                overflow-x: auto;
                overflow-y: hidden;
                height: auto;
            }

            .map-scroll-container::-webkit-scrollbar {
                height: 16px;
            }
        }

        .map-scroll-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            margin: 0 20px;
        }

        .map-scroll-container::-webkit-scrollbar-thumb {
            background: #FF9800;
            border-radius: 10px;
            border: 3px solid rgba(255, 255, 255, 0.5);
        }

        .map-scroll-container::-webkit-scrollbar-thumb:hover {
            background: #F57C00;
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

          .btn-nature { background-color: #4caf50; color: white; border-bottom: 5px solid #2e7d32; }
        .btn-danger { background-color: #ff5252; color: white; border-bottom: 5px solid #c62828; }
        .btn-audio { background-color: #4fc3f7; color: white; border-bottom: 5px solid #0288d1; }
        .btn-orange { background-color: #ff9800; color: white; border-bottom: 5px solid #e65100; }
        .btn-orange i, .btn-orange span { color: white; }

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

        .btn-bubbly:hover {
            transform: scale(1.05);
        }

        .btn-bubbly:active {
            top: 4px;
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.15);
        }

        /* Navigasi Kiri & Kanan (Hanya untuk Desktop) */
        .btn-nav {
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
            z-index: 100;
            width: 80px;
            height: 80px;
            background: linear-gradient(180deg, #4FC3F7 0%, #0288D1 100%);
            border: 4px solid #01579B;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.1s ease;
            box-shadow: 0 8px 0px #014377, 0 12px 20px rgba(0, 0, 0, 0.3);
        }

        .btn-nav::before {
            content: "";
            position: absolute;
            top: 4px;
            left: 10%;
            width: 80%;
            height: 40%;
            background: rgba(255, 255, 255, 0.35);
            border-radius: 15px 15px 50% 50%;
            pointer-events: none;
        }

        .btn-nav i {
            color: white;
            font-size: 2.5rem;
            filter: drop-shadow(0 0 2px #01579B);
            -webkit-text-stroke: 2px #01579B;
            z-index: 2;
        }

        .btn-nav:hover {
            filter: brightness(1.1);
            transform: translateY(-55%);
        }

        .btn-nav:active {
            transform: translateY(-45%);
            box-shadow: 0 2px 0px #014377, 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .left-4 {
            left: 1.5rem;
        }

        .right-4 {
            right: 1.5rem;
        }

        .btn-push {
            transition: transform 0.1s ease-in-out;
        }

        .btn-push:active {
            transform: translateY(4px);
        }
    </style>
</head>

<body class="text-white relative">

    <img src="{{ asset('images/game-bg.png') }}" alt="Background Latar Belakang"
        class="fixed top-0 left-0 w-[100vw] h-[100vh] object-cover z-[-10] pointer-events-none">
    <div class="fixed top-0 left-0 w-full h-full pointer-events-none bg-gradient-to-b from-transparent to-blue-900/20">
    </div>

    <div class="z-50 relative">
        @include('layouts.header-game')
    </div>

    <button onclick="scrollMap(-400)" class="btn-nav left-4 hidden md:flex">
        <i class="fa-solid fa-chevron-left"></i>
    </button>

    <button onclick="scrollMap(400)" class="btn-nav right-4 hidden md:flex">
        <i class="fa-solid fa-chevron-right"></i>
    </button>

    <main class="flex-grow w-full relative z-10 flex flex-col justify-center">

        <div class="text-center w-full absolute top-4 left-0 right-0 z-20 pointer-events-none">
            <h1 class="text-3xl md:text-5xl font-black text-gray-100 drop-shadow-[0_4px_4px_rgba(0,0,0,0.3)]">Pilih
                Tahap</h1><br>
        </div><br>

        <div id="scrollContainer" class="map-scroll-container w-full md:py-20 md:pb-32">

            <div
                class="flex flex-col md:flex-row items-center px-4 md:px-32 w-full md:min-w-max h-auto md:h-full relative mt-4 md:mt-16">

                @foreach ($levels as $index => $level)
                    @php
                        $levelProgress = $progress[$level->id] ?? null;
                        $isUnlocked = $level->level_number <= $currentLevel;
                        $isCompleted = $levelProgress && $levelProgress->is_completed;
                        $isCurrent = $level->level_number == $currentLevel;
                        $stars = $levelProgress ? $levelProgress->stars_earned : 0;
                        $imagePath = $level->image
                            ? asset('images/games/thumbnail/' . $level->image)
                            : asset('images/games/thumbnails/default.png');

                        $isOdd = $loop->iteration % 2 != 0;

                        // Kedudukan zig-zag responsif (PC: Atas Bawah | Mobile: Kiri Kanan)
                        $offsetYDesktop = $isOdd ? 'md:-translate-y-16' : 'md:translate-y-16';
                        $offsetXMobile = $isOdd ? '-translate-x-8 md:translate-x-0' : 'translate-x-8 md:translate-x-0';
                    @endphp

                    <div
                        class="relative flex flex-col items-center group z-10 {{ $offsetYDesktop }} {{ $offsetXMobile }}">

                        @if ($isCurrent)
                            <div class="absolute -top-16 animate-bounce z-40 pointer-events-none">
                                <i
                                    class="fa-solid fa-location-dot text-6xl text-[#FF5252] drop-shadow-[0_4px_4px_rgba(0,0,0,0.4)] border-white border-[4px] rounded-full bg-white"></i>
                            </div>
                        @endif

                        <div class="absolute -top-8 w-full flex justify-center gap-1 pointer-events-none z-30">
                            @if ($isUnlocked)
                                @for ($i = 0; $i < 3; $i++)
                                    <i
                                        class="fa-solid fa-star transition-all 
                                        {{ $i < $stars ? 'text-[#FFD700] [-webkit-text-stroke:2px_#B45309] drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)]' : 'text-gray-200 [-webkit-text-stroke:2px_#9CA3AF] drop-shadow-sm' }} 
                                        {{ $i == 1 ? '-mt-4 text-[52px]' : 'text-[42px]' }} ">
                                    </i>
                                @endfor
                            @endif
                        </div>

                        @if ($isUnlocked)
                            <a href="{{ route('games.activities', ['levelNumber' => $level->level_number]) }}"
                                class="relative w-48 h-36 md:w-60 md:h-44 flex items-center justify-center btn-push z-20 group cursor-pointer">

                                <div
                                    class="w-full h-full bg-white rounded-[2rem] p-2 shadow-[0_15px_30px_rgba(0,0,0,0.2)] border-b-[8px] border-[#84CC16] transition-all duration-200 group-hover:scale-105 group-hover:border-[#65A30D]">
                                    <div class="w-full h-full rounded-[1.5rem] overflow-hidden relative bg-gray-100">
                                        <img src="{{ $imagePath }}" class="w-full h-full object-cover">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent pointer-events-none">
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @else
                            <div
                                class="relative w-48 h-36 md:w-60 md:h-44 flex items-center justify-center z-20 opacity-90 cursor-not-allowed">
                                <div
                                    class="w-full h-full bg-gray-400 rounded-[2rem] p-2 shadow-[0_10px_20px_rgba(0,0,0,0.3)] border-b-[8px] border-gray-600">
                                    <div class="w-full h-full rounded-[1.5rem] overflow-hidden relative bg-gray-800">
                                        <img src="{{ $imagePath }}"
                                            class="w-full h-full object-cover opacity-30 grayscale blur-[2px]">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div
                                                class="w-16 h-16 bg-gray-700/80 rounded-full flex items-center justify-center border-4 border-gray-400 shadow-lg">
                                                <i class="fa-solid fa-lock text-3xl text-gray-200"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div
                            class="absolute -bottom-6 w-16 h-16 bg-white text-[#0C4A6E] rounded-full flex items-center justify-center font-black text-3xl border-b-[6px] border-[#4ade80] shadow-xl z-30 pointer-events-none">
                            {{ $level->level_number }}
                        </div>
                    </div>

                    @if (!$loop->last)
                        <div
                            class="w-24 h-36 md:w-80 md:h-32 flex items-center justify-center -my-6 md:-my-0 md:-mx-6 z-0 pointer-events-none">

                            <svg class="hidden md:block w-full h-full drop-shadow-md" viewBox="0 0 100 100"
                                preserveAspectRatio="none">
                                @if ($isOdd)
                                    <path d="M0,0 C50,0 50,100 100,100" stroke="#CA8A04" stroke-width="12"
                                        fill="none" stroke-linecap="round" />
                                    <path d="M0,0 C50,0 50,100 100,100" stroke="#FDE047" stroke-width="6" fill="none"
                                        stroke-dasharray="10,15" stroke-linecap="round" />
                                @else
                                    <path d="M0,100 C50,100 50,0 100,0" stroke="#CA8A04" stroke-width="12"
                                        fill="none" stroke-linecap="round" />
                                    <path d="M0,100 C50,100 50,0 100,0" stroke="#FDE047" stroke-width="6" fill="none"
                                        stroke-dasharray="10,15" stroke-linecap="round" />
                                @endif
                            </svg>

                            <svg class="block md:hidden w-full h-full drop-shadow-md" viewBox="0 0 100 100"
                                preserveAspectRatio="none">
                                @if ($isOdd)
                                    <path d="M0,0 C0,50 100,50 100,100" stroke="#CA8A04" stroke-width="12"
                                        fill="none" stroke-linecap="round" />
                                    <path d="M0,0 C0,50 100,50 100,100" stroke="#FDE047" stroke-width="6" fill="none"
                                        stroke-dasharray="10,15" stroke-linecap="round" />
                                @else
                                    <path d="M100,0 C100,50 0,50 0,100" stroke="#CA8A04" stroke-width="12"
                                        fill="none" stroke-linecap="round" />
                                    <path d="M100,0 C100,50 0,50 0,100" stroke="#FDE047" stroke-width="6" fill="none"
                                        stroke-dasharray="10,15" stroke-linecap="round" />
                                @endif
                            </svg>

                        </div>
                    @endif
                @endforeach

                <div
                    class="w-24 h-24 md:w-80 md:h-32 flex items-center justify-center -my-6 md:-my-0 md:-mx-6 z-0 pointer-events-none">
                    <svg class="hidden md:block w-full h-full drop-shadow-md" viewBox="0 0 100 100"
                        preserveAspectRatio="none">
                        @php $lastIsOdd = count($levels) % 2 != 0; @endphp
                        @if ($lastIsOdd)
                            <path d="M0,0 C50,0 50,50 100,50" stroke="#CA8A04" stroke-width="12" fill="none"
                                stroke-linecap="round" />
                            <path d="M0,0 C50,0 50,50 100,50" stroke="#FDE047" stroke-width="6" fill="none"
                                stroke-dasharray="10,15" stroke-linecap="round" />
                        @else
                            <path d="M0,100 C50,100 50,50 100,50" stroke="#CA8A04" stroke-width="12" fill="none"
                                stroke-linecap="round" />
                            <path d="M0,100 C50,100 50,50 100,50" stroke="#FDE047" stroke-width="6" fill="none"
                                stroke-dasharray="10,15" stroke-linecap="round" />
                        @endif
                    </svg>

                    <svg class="block md:hidden w-full h-full drop-shadow-md" viewBox="0 0 100 100"
                        preserveAspectRatio="none">
                        @if ($lastIsOdd)
                            <path d="M0,0 C0,50 50,50 50,100" stroke="#CA8A04" stroke-width="12" fill="none"
                                stroke-linecap="round" />
                            <path d="M0,0 C0,50 50,50 50,100" stroke="#FDE047" stroke-width="6" fill="none"
                                stroke-dasharray="10,15" stroke-linecap="round" />
                        @else
                            <path d="M100,0 C100,50 50,50 50,100" stroke="#CA8A04" stroke-width="12" fill="none"
                                stroke-linecap="round" />
                            <path d="M100,0 C100,50 50,50 50,100" stroke="#FDE047" stroke-width="6" fill="none"
                                stroke-dasharray="10,15" stroke-linecap="round" />
                        @endif
                    </svg>
                </div>

                <div class="relative flex flex-col items-center justify-center z-10 pb-16 md:pb-0">
                    <i
                        class="fa-solid fa-flag-checkered text-[80px] md:text-[100px] text-white drop-shadow-[0_10px_10px_rgba(0,0,0,0.4)]"></i>
                </div>

            </div>
        </div>

    </main>

    <script>
        function scrollMap(distance) {
            const container = document.getElementById('scrollContainer');
            if (!container) {
                console.error("Scroll container not found!");
                return;
            }

            // Pada telefon, scroll paksi Y. Pada komputer, scroll paksi X.
            if (window.innerWidth < 768) {
                container.scrollBy({
                    top: distance,
                    behavior: 'smooth'
                });
            } else {
                container.scrollBy({
                    left: distance,
                    behavior: 'smooth'
                });
            }
        }
    </script>
</body>

</html>
