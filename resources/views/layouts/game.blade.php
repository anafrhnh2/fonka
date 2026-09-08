<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>@yield('game-title', config('game.title'))</title>

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

        /* Butang Animasi */
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

        .dropdown-menu { transition: all 0.3s ease; transform-origin: top right; }
        .hidden-dropdown { opacity: 0; transform: scale(0.95); pointer-events: none; }
    </style>
    @stack('styles')
</head>

<body class="min-h-screen flex flex-col">

    @php
        $activeChild = \App\Models\Child::find(session('active_child_id'));
        $currentStars = $activeChild ? $activeChild->stars : 0;

        // Padanan Avatar Berasaskan Logik Pangkalan Data Terbaru
        $currentAvatar = null;
        if($activeChild) {
            $currentAvatar = \Illuminate\Support\Facades\DB::table('avatars')->where('id', $activeChild->avatar_id)->first();
        }
        $avatarPath = $currentAvatar ? asset($currentAvatar->image) : asset('images/default_avatar.png');
    @endphp

    @include('layouts.header-game')

    <main class="flex-grow flex items-center justify-center p-2 sm:p-4 md:p-6 relative w-full overflow-hidden">
        
        <div class="w-full max-w-7xl aspect-video bg-white shadow-[0_10px_30px_rgba(0,0,0,0.15)] 
                    border-[6px] sm:border-[8px] md:border-[12px] border-[#84CC16] 
                    rounded-[1rem] sm:rounded-[1.5rem] md:rounded-[20px] 
                    relative overflow-hidden flex flex-col">
            
            <div class="w-full h-full relative p-1 sm:p-2 md:p-4">
                @yield('content')
            </div>

        </div>

    </main>

    @stack('scripts')
</body>

</html>