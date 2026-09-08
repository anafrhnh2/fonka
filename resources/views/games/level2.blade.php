@extends('layouts.game')

@section('game-title', 'Tahap 2: Kenal Huruf Vokal')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') }},
            levelId: {{ $level->id }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-[calc(100vh-60px)] md:h-full flex flex-col bg-[#F5F1E8] relative font-sans overflow-hidden transition-colors duration-300">

        <div class="flex-1 w-full max-w-6xl mx-auto px-1 py-2 md:p-2 flex flex-col min-h-0 relative z-0">
            <div
                class="bg-white rounded-[2rem] md:rounded-[3rem] p-3 md:p-4 shadow-xl flex flex-col h-full border-[6px] border-white ring-1 ring-gray-100 relative">

                <div class="flex flex-wrap justify-between items-center mb-4 md:mb-5 px-1 md:px-4 gap-y-3">

                    <div
                        class="flex items-center bg-indigo-50 border-4 border-indigo-200 rounded-full pl-4 pr-2 py-1 shadow-sm">
                        <span class="text-indigo-800 font-black text-sm md:text-xl uppercase tracking-wider mr-3">
                            Cari Huruf
                        </span>
                        <div id="target-display"
                            class="w-12 h-12 md:w-16 md:h-16 bg-white rounded-full flex items-center justify-center text-3xl md:text-5xl font-black text-indigo-600 border-4 border-indigo-400 shadow-inner drop-shadow-md">
                            ?
                        </div>

                        <button onclick="replayTargetAudio()" id="replay-audio-btn"
                            class="ml-2 w-10 h-10 md:w-12 md:h-12 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-full flex items-center justify-center border-2 border-blue-300 transition-transform active:scale-95 shadow-sm"
                            title="Dengar Semula">
                            <i class="fa-solid fa-volume-high text-lg md:text-xl"></i>
                        </button>
                    </div>

                    <div class="flex items-center gap-3 md:gap-5">
                        <div
                            class="flex flex-col items-center bg-green-50 border-4 border-green-200 rounded-2xl px-4 py-1 shadow-sm">
                            <span class="text-green-600 font-bold text-xs md:text-sm uppercase tracking-wider">Markah</span>
                            <span id="current-game-score" class="text-green-700 font-black text-xl md:text-2xl">0</span>
                        </div>

                        <div
                            class="flex flex-col items-center bg-orange-50 border-4 border-orange-200 rounded-2xl px-4 py-1 shadow-sm">
                            <span
                                class="text-orange-500 font-bold text-xs md:text-sm uppercase tracking-wider">Pusingan</span>
                            <span id="round-display" class="text-orange-700 font-black text-xl md:text-2xl">0/5</span>
                        </div>

                        <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                            class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                            <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                        </button>

                    </div>

                </div>

                <div id="game-board"
                    class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-[#E0E7FF]">

                    <video autoplay muted loop playsinline
                        class="absolute inset-0 w-full h-full object-cover z-0 pointer-events-none">
                        <source src="{{ asset('images/games/bg.mp4') }}" type="video/mp4">
                    </video>

                    <div id="letters-container" class="absolute inset-0 z-10"></div>

                </div>
            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-[#0B172A]/80 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">

            <div
                class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl text-center 
                        max-w-lg w-full max-h-[80vh] 
                        flex flex-col justify-between 
                        transform transition-all scale-100 
                        border-[6px] border-green-100 
                        overflow-y-auto custom-scrollbar">

                <h1 class="text-3xl md:text-4xl font-bold text-blue-600 mb-6 font-sans tracking-wide">
                    Cara Bermain
                </h1>
                <div class="flex flex-col gap-6 text-left mx-auto w-full md:w-[85%] mb-10 flex-1">
                    <div class="flex items-center gap-4 bg-blue-50 p-5 rounded-xl border-2 border-blue-100 shadow-sm">
                        <div
                            class="w-12 h-12 bg-blue-200 text-blue-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <p class="text-gray-600 font-bold text-lg md:text-xl leading-tight">Lihat & dengar huruf.</p>
                    </div>

                    <div class="flex items-center gap-4 bg-green-50 p-5 rounded-xl border-2 border-green-100 shadow-sm">
                        <div
                            class="w-12 h-12 bg-green-200 text-green-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-hand-pointer"></i>
                        </div>
                        <p class="text-gray-600 font-bold text-lg md:text-xl leading-tight">Tekan buih yang betul!</p>
                    </div>
                </div>

                <button onclick="startGame()"
                    class="w-full bg-[#22C55E] hover:bg-[#16A34A] text-white text-2xl font-bold py-5 rounded-[1.5rem] shadow-[0_6px_0_#15803D] active:translate-y-[6px] active:shadow-none transition-all flex justify-center items-center gap-3 mt-auto shrink-0">
                    <i class="fa-solid fa-play"></i> Mula
                </button>
            </div>
        </div>

        <div id="win-overlay"
            class="hidden absolute inset-0 bg-black/80 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8 overflow-y-auto">
            <div
                class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full animate-bounce-in border-[6px] border-indigo-200 relative my-auto">
                <div
                    class="absolute -top-12 left-1/2 transform -translate-x-1/2 text-7xl drop-shadow-lg text-yellow-400 bg-white rounded-full p-2 border-4 border-yellow-200">
                    <i class="fa-solid fa-trophy"></i>
                </div>

                <h1 class="text-4xl md:text-5xl font-black text-indigo-600 mb-2 mt-6">Tahap Selesai!</h1>
                <div class="bg-indigo-50 p-6 rounded-[2rem] mb-8 border-4 border-indigo-100 shadow-inner">

                    <div class="flex justify-center gap-2 mb-4" id="star-container">
                    </div>

                    <p class="font-black text-indigo-800 uppercase tracking-widest mb-1 text-sm">Jumlah Markah</p>
                    <p class="text-6xl font-black text-indigo-600 drop-shadow-sm" id="final-score">0</p>
                </div>

                <div class="flex flex-col gap-4">
                    <button onclick="saveAndExit(event)"
                        class="btn-3d w-full bg-[#10B981] border-[#059669] text-white text-xl md:text-2xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3 hover:bg-[#059669]">
                        Tahap Seterusnya <i class="fa-solid fa-forward-step"></i>
                    </button>

                    <button onclick="restartGame()"
                        class="btn-3d w-full bg-slate-200 border-slate-300 text-slate-700 text-lg md:text-xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3 hover:bg-slate-300 hover:border-slate-400">
                        Main Semula <i class="fa-solid fa-rotate-right"> </i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <style>
        #game-fullscreen-container.is-fullscreen {
            background-color: #d5d5d5 !important;
        }

        .fullscreen-btn-mobile {
            z-index: 99999 !important;
            pointer-events: auto !important;
            touch-action: manipulation !important;
            -webkit-tap-highlight-color: transparent;
        }

        #game-fullscreen-container {
            isolation: auto;
            /* auto lebih selamat untuk tak clash dengan absolute buttons */
        }

        /* Add to your existing <style> block */
        .fixed.inset-0 {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100dvh;
            z-index: 9999;
        }

        .btn-3d {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border-bottom-width: 6px;
            border-style: solid;
        }

        .btn-3d:not(:disabled):active {
            transform: translateY(4px);
            border-bottom-width: 2px;
            margin-top: 4px;
        }

        .btn-3d:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        .letter-bubble {
            position: absolute;
            font-family: 'Verdana', sans-serif;
            font-weight: bold;

            /* Warna Teks Berubah Mengikut Javascript */
            color: var(--font-color, #1e3a8a);

            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.95), var(--bubble-color, rgba(173, 216, 230, 0.6)));

            border: 2px solid rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow:
                inset 0 0 20px rgba(255, 255, 255, 0.5),
                inset 10px 10px 20px rgba(255, 255, 255, 0.5),
                inset -5px -5px 15px rgba(0, 0, 0, 0.1),
                5px 5px 15px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(3px);
            transition: transform 0.1s;
            width: 70px;
            height: 70px;
            font-size: 2.5rem;
            cursor: pointer;
        }

        .letter-bubble::after {
            content: '';
            position: absolute;
            top: 15%;
            left: 20%;
            width: 25%;
            height: 15%;
            border-radius: 50%;
            background: radial-gradient(ellipse at center, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0) 70%);
            transform: rotate(-45deg);
        }

        @media (min-width: 768px) {
            .letter-bubble {
                width: 90px;
                height: 90px;
                font-size: 3.5rem;
            }
        }

        .letter-bubble:active {
            transform: scale(0.9);
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .shake {
            animation: shake 0.3s;
        }

        @keyframes bounce-in {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            60% {
                transform: scale(1.05);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-bounce-in {
            animation: bounce-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        video {
            pointer-events: none;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <script>
        const MAX_ROUNDS = 10;
        let currentRound = 0;

        const vowels = ['A', 'I', 'O', 'U', 'E'];
        const alphabet = 'BCDFGHJKLMNPQRSTVWXYZ';
        let score = 0;
        let currentTarget = '';
        let lettersElements = [];
        let gameInterval;
        let sessionMistakes = {};

        const bubbleStyles = [{
                bg: 'rgba(255, 182, 193, 0.8)',
                font: '#9F1239'
            },
            {
                bg: 'rgba(173, 216, 230, 0.8)',
                font: '#1E3A8A'
            },
            {
                bg: 'rgba(167, 243, 208, 0.8)',
                font: '#065F46'
            },
            {
                bg: 'rgba(253, 230, 138, 0.9)',
                font: '#92400E'
            },
            {
                bg: 'rgba(221, 160, 221, 0.8)',
                font: '#4C1D95'
            },
            {
                bg: 'rgba(255, 218, 185, 0.9)',
                font: '#9A3412'
            }
        ];

        const container = document.getElementById('game-board');
        const scoreDisplayTop = document.getElementById('current-game-score');
        const targetDisplay = document.getElementById('target-display');
        const roundDisplay = document.getElementById('round-display');
        const overlay = document.getElementById('start-overlay');
        const winOverlay = document.getElementById('win-overlay');
        const fsContainer = document.getElementById('game-fullscreen-container');
        const lettersContainer = document.getElementById('letters-container');
        const fullscreenIcon = document.getElementById('fullscreen-icon');

        function toggleFullScreen() {
            const fsContainer = document.getElementById('game-fullscreen-container');

            // Check if we are currently in either native OR fake fullscreen
            const isCurrentlyFullscreen = document.fullscreenElement || fsContainer.classList.contains('fixed');

            if (!isCurrentlyFullscreen) {
                // Attempt native fullscreen (this will be ignored by iOS)
                if (fsContainer.requestFullscreen) {
                    fsContainer.requestFullscreen().catch(err => {
                        console.log("Browser blocked native fullscreen.");
                    });
                }

                // Apply "fake" fullscreen styling
                fsContainer.classList.add('fixed', 'inset-0', 'z-[9999]');
                fullscreenIcon.classList.replace('fa-expand', 'fa-compress');
            } else {
                // Exit native fullscreen
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                }

                // Remove "fake" fullscreen styling
                fsContainer.classList.remove('fixed', 'inset-0', 'z-[9999]');
                fullscreenIcon.classList.replace('fa-compress', 'fa-expand');
            }
        }

        let currentAudio = new Audio();

        function playAudio(type, fileName) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
            const audioUrl = `${window.gameConfig.audioBaseUrl}/${fileName}.mp3`;
            let audioUrl = '';
            if (type === 'letter') {
                audioUrl = `/audio/${fileName}.mp3`;
            } else if (type === 'feedback') {
                audioUrl = `/audio/${fileName}.mp3`;
            }

            currentAudio.src = audioUrl;
            currentAudio.play().catch(error => {
                console.error("Gagal memainkan audio:", error);
            });
        }

        function replayTargetAudio() {
            if (currentTarget && currentTarget !== '?') {
                playAudio('letter', currentTarget);

                // Add a quick pulse animation to the icon when clicked
                const icon = document.querySelector('#replay-audio-btn i');
                icon.classList.add('animate-pulse');
                setTimeout(() => icon.classList.remove('animate-pulse'), 500);
            }
        }

        function startGame() {
            overlay.style.display = 'none';
            winOverlay.classList.add('hidden');
            score = 0;
            currentRound = 0;
            updateUI();
            nextRound();

            if (gameInterval) clearInterval(gameInterval);
            gameInterval = setInterval(moveLetters, 30);
        }

        function restartGame() {
            startGame();
        }

        function updateUI() {
            scoreDisplayTop.innerText = score;
            roundDisplay.innerText = currentRound + "/" + MAX_ROUNDS;
        }

        function nextRound() {
            if (currentRound >= MAX_ROUNDS) {
                finishGame();
                return;
            }

            currentRound++;
            updateUI();
            lettersContainer.innerHTML = '';
            lettersElements = [];

            currentTarget = vowels[Math.floor(Math.random() * vowels.length)];
            targetDisplay.innerText = currentTarget;

            setTimeout(() => playAudio('letter', currentTarget), 300);

            spawnLetter(currentTarget, true);
            for (let i = 0; i < 8; i++) {
                let randomChar = alphabet[Math.floor(Math.random() * alphabet.length)];
                spawnLetter(randomChar, false);
            }
        }

        function calculateStars() {
            if (score >= 10) return 3;
            if (score >= 5) return 2;
            return 1;
        }

        function renderStars(starCount) {
            const container = document.getElementById('star-container');
            container.innerHTML = ''; // Kosongkan
            for (let i = 0; i < 3; i++) {
                const isEarned = i < starCount;
                const starColor = isEarned ? 'text-yellow-400 drop-shadow-md' : 'text-slate-300 drop-shadow-none';
                const starSize = i === 1 ? 'text-6xl -mt-4' : 'text-5xl'; // Bintang tengah besar sikit
                container.innerHTML += `<i class="fa-solid fa-star ${starColor} ${starSize} transition-all"></i>`;
            }
        }

        function finishGame() {
            clearInterval(gameInterval);
            lettersContainer.innerHTML = '';

            document.getElementById('final-score').innerText = score;
            const earnedStars = calculateStars();
            renderStars(earnedStars);

            winOverlay.classList.remove('hidden');
            playAudio('feedback', 'tahniah');
        }

        function saveAndExit(event) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const stars = calculateStars();

            const btn = event.target;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

            fetch(window.gameConfig.saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        child_id: window.gameConfig.childId,
                        game_level_id: window.gameConfig.levelId,
                        score: score,
                        mistakes: sessionMistakes,
                        stars_earned: stars,
                        is_completed: true
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = window.gameConfig.nextLevelUrl;
                    } else {
                        alert("Ralat: " + data.message);
                        btn.disabled = false;
                        btn.innerHTML = 'Tahap Seterusnya <i class="fa-solid fa-forward-step"></i>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Ralat rangkaian. Sila semak sambungan internet anda.");
                    btn.disabled = false;
                    btn.innerHTML = 'Tahap Seterusnya <i class="fa-solid fa-forward-step"></i>';
                });
        }

        function spawnLetter(char, isTarget) {
            const el = document.createElement('div');
            el.classList.add('letter-bubble');
            el.innerText = char;

            const randomStyle = bubbleStyles[Math.floor(Math.random() * bubbleStyles.length)];
            el.style.setProperty('--bubble-color', randomStyle.bg);
            el.style.setProperty('--font-color', randomStyle.font);

            const maxX = container.clientWidth - 90;
            const maxY = container.clientHeight - 90;
            let x = Math.random() * maxX;
            let y = Math.random() * maxY;

            el.style.left = x + 'px';
            el.style.top = y + 'px';

            let dx = (Math.random() - 0.5) * 2;
            let dy = (Math.random() - 0.5) * 2;

            el.onclick = () => {
                if (isTarget) {
                    score += 1;
                    updateUI();
                    el.style.setProperty('--bubble-color', 'rgba(134, 239, 172, 0.9)');
                    el.style.setProperty('--font-color', '#064E3B');
                    el.style.borderColor = '#22C55E';
                    playAudio('feedback', 'bagus');
                    setTimeout(nextRound, 1000);
                } else {
                    el.classList.add('shake');
                    el.style.setProperty('--bubble-color', 'rgba(252, 165, 165, 0.9)');
                    el.style.setProperty('--font-color', '#7F1D1D');
                    el.style.borderColor = '#EF4444';
                    playAudio('feedback', 'cubalagi');
                    let itemName = char;
                    let mistakeKey = `${currentTarget}_${itemName}`;
                    sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                    setTimeout(() => {
                        el.classList.remove('shake');
                        el.style.setProperty('--bubble-color', randomStyle.bg);
                        el.style.setProperty('--font-color', randomStyle.font);
                        el.style.borderColor = 'rgba(255, 255, 255, 0.8)';
                    }, 500);
                }
            };

            lettersElements.push({
                element: el,
                x,
                y,
                dx,
                dy
            });
            lettersContainer.appendChild(el);
        }

        function moveLetters() {
            const width = container.clientWidth - (window.innerWidth >= 768 ? 90 : 70);
            const height = container.clientHeight - (window.innerWidth >= 768 ? 90 : 70);

            lettersElements.forEach(item => {
                item.x += item.dx;
                item.y += item.dy;
                if (item.x <= 0 || item.x >= width) item.dx *= -1;
                if (item.y <= 0 || item.y >= height) item.dy *= -1;
                item.x = Math.max(0, Math.min(item.x, width));
                item.y = Math.max(0, Math.min(item.y, height));
                item.element.style.left = item.x + 'px';
                item.element.style.top = item.y + 'px';
            });
        }

        document.addEventListener('fullscreenchange', (event) => {
            if (!document.fullscreenElement) {
                fullscreenIcon.classList.remove('fa-compress');
                fullscreenIcon.classList.add('fa-expand');
                fsContainer.classList.remove('is-fullscreen');
            }
        });
    </script>
@endsection
