<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pencapaian Saya</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        @font-face {
            font-family: 'OpenDyslexic';
            src: url('{{ asset('fonts/opendyslexicmono-regular-webfont.woff2') }}') format('woff2');
            font-weight: normal;
            font-style: normal;
        }

        *:not(.fa):not(.fas):not(.far):not(.fal):not(.fab):not(.fa-solid):not(.fa-regular):not(.fa-brands) {
            font-family: 'OpenDyslexic', sans-serif !important;
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

        .btn-nature {
            background-color: #4caf50;
            color: white;
            border-bottom: 5px solid #2e7d32;
        }

        .btn-danger {
            background-color: #ff5252;
            color: white;
            border-bottom: 5px solid #c62828;
        }

        .btn-audio {
            background-color: #4fc3f7;
            color: white;
            border-bottom: 5px solid #0288d1;
        }

        .btn-orange {
            background-color: #ff9800;
            color: white;
            border-bottom: 5px solid #e65100;
        }

        .btn-orange i,
        .btn-orange span {
            color: white;
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

        .dropdown-menu {
            transition: all 0.3s ease;
            transform-origin: top right;
        }

        .hidden-dropdown {
            opacity: 0;
            transform: scale(0.95);
            pointer-events: none;
        }

        .game-canvas {
            background: #ffffff;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 12px solid #84CC16;
            position: relative;
            border-radius: 20px;
            overflow: hidden;
        }
    </style>
    @stack('styles')
</head>

<body class="min-h-screen flex flex-col">

    @php
        $activeChild = \App\Models\Child::find(session('active_child_id'));
        $currentStars = $activeChild ? $activeChild->stars : 0;

        $avatarPath =
            $activeChild && $activeChild->avatar && $activeChild->avatar !== 'default_avatar.png'
                ? asset('images/' . $activeChild->avatar)
                : asset('images/default_avatar.png');
    @endphp

    @include('layouts.header-game')

    <div class="h-full w-full flex flex-col items-center justify-start overflow-y-auto no-scrollbar py-8 px-4">

        <div class="w-full max-w-5xl bg-white rounded-[3rem] p-8 shadow-xl border-b-8 border-gray-200 mb-10 flex flex-col md:flex-row items-center justify-around gap-6 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 text-9xl text-gray-50 opacity-50"><i class="fa-solid fa-trophy"></i></div>

            <div class="flex items-center gap-6 z-10">
                <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center border-4 border-yellow-400 shadow-inner">
                    <i class="fa-solid fa-coins text-5xl text-yellow-500"></i>
                </div>
                <div>
                    <p class="text-2xl text-gray-500 font-bold mb-0">Jumlah Poin</p>
                    <h2 class="text-6xl font-black text-yellow-500 drop-shadow-sm">{{ $totalScore }}</h2>
                </div>
            </div>

            <div class="hidden md:block w-2 h-24 bg-gray-100 rounded-full z-10"></div>

            <div class="flex items-center gap-6 z-10">
                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center border-4 border-orange-400 shadow-inner">
                    <i class="fa-solid fa-star text-5xl text-orange-500"></i>
                </div>
                <div>
                    <p class="text-2xl text-gray-500 font-bold mb-0">Jumlah Bintang</p>
                    <h2 class="text-6xl font-black text-orange-500 drop-shadow-sm">{{ $totalStars }}</h2>
                </div>
            </div>
        </div>

        <div class="w-full max-w-5xl bg-[#FFFBF0] border-4 border-[#FDE08B] rounded-3xl p-6 shadow-xl relative mt-4">

            <div class="flex flex-col w-full mt-4">

                @foreach ($badges as $index => $badge)
                    <div class="flex flex-row items-center w-full py-4 border-b-2 border-dashed border-orange-200 last:border-none {{ $badge['unlocked'] ? '' : 'opacity-60 grayscale' }}">

                        <div class="w-20 h-20 flex-shrink-0 rounded-[1rem] flex items-center justify-center {{ $badge['unlocked'] ? $badge['bg'] : 'bg-gray-300' }} shadow-md border-2 border-white relative">
                            <i class="fa-solid {{ $badge['unlocked'] ? $badge['icon'] : 'fa-lock' }} text-4xl text-white drop-shadow-md"></i>

                            @if ($badge['unlocked'])
                                <div class="absolute -top-2 -right-2 bg-yellow-400 text-white text-[10px] font-bold w-6 h-6 flex items-center justify-center rounded-full shadow border-2 border-white">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col flex-grow px-6 justify-center">

                            <h4 class="text-xl font-bold text-orange-500 leading-tight">
                                {{ $badge['title'] }}
                            </h4>

                            <p class="text-sm font-semibold text-gray-500 mb-2">
                                {{ $badge['description'] }}
                            </p>

                            <div class="relative w-full h-6 bg-[#5D4037] rounded-full overflow-hidden shadow-inner border-[3px] border-[#5D4037]">
                                <div class="h-full bg-cyan-400"
                                    style="width: {{ $badge['required'] > 0 ? min(100, ($badge['progress'] / $badge['required']) * 100) : 0 }}%">
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-xs font-bold text-white tracking-widest drop-shadow-md">
                                        {{ $badge['progress'] }} / {{ $badge['required'] }}
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div class="flex-shrink-0 ml-4 flex items-center justify-center min-w-[80px]">

                            @if ($badge['unlocked'] && empty($badge['reward_claimed']))
                                <form method="POST" action="{{ route('achievement.claim', $badge['id']) }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="group relative flex flex-col items-center justify-center bg-[#84CC16] hover:bg-[#65A30D] text-white rounded-[1rem] border-b-[5px] border-[#4D7C0F] shadow-md transition-all active:translate-y-1 active:border-b-0 w-[72px] h-[76px] animate-bounce">
                                        <span class="text-[11px] font-black uppercase tracking-wider mb-0.5 drop-shadow-sm">
                                            Claim!
                                        </span>
                                        <div class="flex items-center justify-center bg-black/20 px-2 py-0.5 rounded-full shadow-inner">
                                            @if (($badge['reward'] ?? 200) >= 200)
                                                <i class="fa-solid fa-coins text-yellow-300 text-[10px] mr-1 drop-shadow"></i>
                                            @else
                                                <i class="fa-solid fa-gem text-red-400 text-[10px] mr-1 drop-shadow"></i>
                                            @endif
                                            <span class="text-xs font-bold text-white drop-shadow-sm">+{{ $badge['reward'] ?? 200 }}</span>
                                        </div>
                                    </button>
                                </form>
                            @elseif(!empty($badge['reward_claimed']))
                                <div class="w-[72px] h-[76px] bg-[#DCFCE7] rounded-[1rem] flex flex-col items-center justify-center border-2 border-[#86EFAC] shadow-inner">
                                    <div class="bg-[#22C55E] text-white rounded-full w-7 h-7 flex items-center justify-center mb-1 shadow-sm">
                                        <i class="fa-solid fa-check text-sm drop-shadow-sm"></i>
                                    </div>
                                    <span class="text-[#166534] font-bold text-[10px] uppercase tracking-wide">Claimed</span>
                                </div>
                            @else
                                <div class="w-[72px] h-[76px] bg-[#A69C91] rounded-[1rem] flex flex-col items-center justify-center border-b-[5px] border-[#8E8377] shadow-sm">
                                    <div class="mb-1">
                                        @if (($badge['reward'] ?? 200) >= 200)
                                            <i class="fa-solid fa-coins text-yellow-400 text-2xl drop-shadow-md"></i>
                                        @else
                                            <i class="fa-solid fa-gem text-red-500 text-2xl drop-shadow-md"></i>
                                        @endif
                                    </div>
                                    <span class="text-white font-black text-sm drop-shadow-sm">
                                        +{{ $badge['reward'] ?? 200 }}
                                    </span>
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
    @endpush
</body>

</html>