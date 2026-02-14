<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Fonka') }} - Let's Play!</title>

    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Fredoka', sans-serif;
            /* Dyslexia-Friendly: Soft cream background reduces visual stress/glare */
            background-color: #FFF9E6; 
            /* Subtle pattern to make it vibrant but not distracting */
            background-image: radial-gradient(#FDE68A 2px, transparent 2px);
            background-size: 40px 40px;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        /* Dyslexia-Friendly Text Overrides */
        .dyslexia-text {
            letter-spacing: 0.05em; /* Slightly wider spacing helps decode words */
            line-height: 1.6;
            color: #374151; /* Dark Grey (Soft Black) is easier on eyes */
        }

        /* The Game Container (The "Whiteboard" area) */
        .game-canvas {
            background: white;
            border-radius: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border: 4px solid #E5E7EB; /* Soft border definition */
            position: relative;
            overflow: hidden;
        }

        /* Buttons */
        .btn-game {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .btn-game:hover {
            transform: scale(1.1);
        }
        .btn-game:active {
            transform: scale(0.95);
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen flex flex-col">

    <header class="w-full max-w-7xl mx-auto px-4 py-4 flex justify-between items-center z-50">
        
        <a href="{{ route('dashboard') }}" class="btn-game bg-red-400 hover:bg-red-500 text-white rounded-full px-6 py-2 shadow-lg border-b-4 border-red-600 active:border-b-0 active:translate-y-1 flex items-center gap-2">
            <span class="text-2xl font-bold">⬅</span>
            <span class="text-lg font-bold">Exit</span>
        </a>

        <div class="hidden md:block bg-white px-8 py-2 rounded-full border-4 border-indigo-100 shadow-sm">
            <h1 class="text-2xl font-bold text-indigo-600 tracking-wide">
                @yield('game-title', 'Fun Learning Game')
            </h1>
        </div>

        <div class="flex items-center gap-4">
            <button id="toggleMusic" class="btn-game bg-blue-100 text-blue-500 p-3 rounded-full hover:bg-blue-200">
                🔊
            </button>

            <div class="bg-yellow-400 text-white px-6 py-2 rounded-full shadow-lg border-b-4 border-yellow-600 flex items-center gap-2">
                <span class="text-2xl">⭐</span>
                <span class="text-xl font-bold" id="scoreDisplay">0</span>
            </div>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center p-4 relative">
        
        <div class="absolute top-10 left-10 text-6xl opacity-20 pointer-events-none animate-bounce" style="animation-duration: 3s">☁️</div>
        <div class="absolute bottom-20 right-10 text-6xl opacity-20 pointer-events-none animate-bounce" style="animation-duration: 4s">☁️</div>

        <div class="w-full max-w-5xl aspect-video game-canvas p-6 md:p-10 relative">
            
            @yield('content')

        </div>
    </main>

    <div id="helpModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-3xl p-8 max-w-lg w-full text-center border-8 border-indigo-200 shadow-2xl">
            <h2 class="text-3xl font-bold text-indigo-600 mb-4">How to Play 🎮</h2>
            <p class="text-xl text-gray-600 mb-8 dyslexia-text">
                @yield('instructions', 'Listen to the sound and click the matching letter!')
            </p>
            <button onclick="document.getElementById('helpModal').classList.add('hidden')" class="bg-green-500 text-white text-xl font-bold px-8 py-3 rounded-full shadow-lg hover:bg-green-600 transition w-full">
                Okay, I'm Ready! 👍
            </button>
        </div>
    </div>

    <script>
        // Simple Audio Toggle Logic
        const musicBtn = document.getElementById('toggleMusic');
        let isMuted = false;
        
        musicBtn.addEventListener('click', () => {
            isMuted = !isMuted;
            musicBtn.innerText = isMuted ? '🔇' : '🔊';
            // Add logic here to mute your game sounds
        });
    </script>

    @stack('scripts')
</body>
</html>