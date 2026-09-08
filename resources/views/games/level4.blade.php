@extends('layouts.game')

@section('game-title', 'Tahap 4: Tekan Huruf Konsonan')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        #game-fullscreen-container {
            transition:
                transform 0.25s ease,
                opacity 0.25s ease;
            will-change: transform;
        }

        #game-fullscreen-container.is-fullscreen {
            position: fixed !important;
            inset: 0;
            width: 100vw;
            height: 100dvh;
            z-index: 9999;
            background: #d5d5d5;
        }

        .fullscreen-btn-mobile {
            z-index: 99999 !important;
            pointer-events: auto !important;
            touch-action: manipulation !important;
            -webkit-tap-highlight-color: transparent;
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

        #game-board {
            cursor: url("{{ asset('images/games/hammer.png') }}") 10 10, crosshair !important;
        }

        #game-board:active {
            cursor: url("{{ asset('images/games/hammer.png') }}") 10 40, crosshair !important;
        }

        .mole {
            cursor: inherit;
        }

        .mole.up {
            bottom: 0 !important;
        }

        @keyframes pop-sparkle {
            0% {
                transform: scale(0.5);
                opacity: 1;
            }

            50% {
                transform: scale(1.5);
                opacity: 0.8;
            }

            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        .sparkle-effect {
            position: absolute;
            font-size: 3.5rem;
            pointer-events: none;
            animation: pop-sparkle 0.5s ease-out forwards;
            z-index: 20;
        }

        .btn-3d {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border-bottom-width: 6px;
            border-top-width: 2px;
            border-left-width: 2px;
            border-right-width: 2px;
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

        .btn-green {
            background-color: #4CAF50;
            border-color: #2E7D32;
            border-top-color: #81C784;
            border-left-color: #81C784;
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
            animation: bounce-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
    </style>

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 4 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container" class="w-full h-full flex flex-col relative font-sans overflow-hidden">


        <div class="w-full h-full flex flex-col relative z-0">

            <div class="flex flex-wrap justify-between items-center mb-4 md:mb-5 px-1 md:px-4 gap-y-3 shrink-0">
                <div class="flex items-center bg-indigo-50 border-4 border-indigo-200 rounded-full pl-4 pr-4 py-2 shadow-sm">
                    <span class="text-indigo-800 font-black text-sm md:text-xl uppercase tracking-wider">
                        <i class="fa-solid fa-hammer mr-2"></i> Ketuk Konsonan
                    </span>
                </div>

                <div class="flex items-center gap-3 md:gap-5">
                    <div
                        class="flex flex-col items-center bg-green-50 border-4 border-green-200 rounded-2xl px-4 py-1 shadow-sm">
                        <span class="text-green-600 font-bold text-xs md:text-sm uppercase tracking-wider">Markah</span>
                        <span id="score-display" class="text-green-700 font-black text-xl md:text-2xl">0</span>
                    </div>

                    <div
                        class="flex flex-col items-center bg-orange-50 border-4 border-orange-200 rounded-2xl px-4 py-1 shadow-sm">
                        <span class="text-orange-500 font-bold text-xs md:text-sm uppercase tracking-wider">Pusingan</span>
                        <span id="round-display" class="text-orange-700 font-black text-xl md:text-2xl">0/10</span>
                    </div>

                    <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                        class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                        <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                    </button>
                </div>
            </div>

            <div id="game-board"
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-[#E0E7FF] w-full flex flex-col justify-center items-center">
                <img src="{{ asset('images/games/bg2.png') }}" alt="Background"
                    class="absolute inset-0 w-full h-full object-cover z-0 pointer-events-none opacity-80">

                <div
                    class="text-center mb-8 z-10 bg-white/80 px-6 py-2 rounded-full border-2 border-white shadow-sm backdrop-blur-sm">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-700 tracking-wide">Tekan huruf yang keluar!</h2>
                </div>

                <div class="grid grid-cols-3 gap-6 md:gap-12 w-full max-w-2xl px-4 z-10">
                    @for ($i = 0; $i < 6; $i++)
                        <div
                            class="hole relative w-24 h-24 md:w-32 md:h-32 mx-auto bg-[#8B5A2B] rounded-[50%] shadow-[inset_0_15px_25px_rgba(0,0,0,0.6)] border-b-[8px] border-[#5C4033] overflow-hidden flex items-end justify-center">
                            <div class="mole absolute bottom-[-100%] w-20 h-20 md:w-28 md:h-28 bg-[#FDE047] rounded-t-[45%] flex items-center justify-center cursor-pointer border-4 border-[#EAB308] shadow-lg transition-all duration-400 ease-out"
                                data-index="{{ $i }}">
                                <span
                                    class="font-fredoka text-5xl md:text-6xl font-black text-[#854D0E] pointer-events-none">B</span>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-[#0B172A]/80 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">
            <div
                class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full flex flex-col transform transition-all scale-100 border-[6px] border-indigo-100">
                <h1 class="text-3xl md:text-4xl font-bold text-indigo-600 mb-6 font-sans tracking-wide">Cara Bermain</h1>

                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10 flex-1">
                    <div class="flex items-center gap-4 bg-orange-50 p-4 rounded-xl border-2 border-orange-100 shadow-sm">
                        <div
                            class="w-12 h-12 bg-orange-200 text-orange-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <p class="text-gray-600 font-bold text-lg md:text-xl leading-tight">Perhatikan lubang.</p>
                    </div>

                    <div class="flex items-center gap-4 bg-green-50 p-4 rounded-xl border-2 border-green-100 shadow-sm">
                        <div
                            class="w-12 h-12 bg-green-200 text-green-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-hammer"></i>
                        </div>
                        <p class="text-gray-600 font-bold text-lg md:text-xl leading-tight">Ketuk huruf dengan pantas!</p>
                    </div>
                </div>

                <button onclick="startGame()"
                    class="btn-3d btn-green w-full text-white text-2xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3">
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
                    <div class="flex justify-center gap-2 mb-4" id="star-container"></div>
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

    <script>
        const fullscreenIcon = document.getElementById('fullscreen-icon');

        function toggleFullScreen() {
            const fsContainer = document.getElementById('game-fullscreen-container');

            const isFullscreen = fsContainer.classList.contains('is-fullscreen');

            if (!isFullscreen) {

                // Native fullscreen for Android
                if (fsContainer.requestFullscreen) {
                    fsContainer.requestFullscreen().catch(() => {});
                }

                requestAnimationFrame(() => {
                    fsContainer.classList.add('is-fullscreen');
                });

                fullscreenIcon.classList.replace('fa-expand', 'fa-compress');

            } else {

                if (document.fullscreenElement) {
                    document.exitFullscreen().catch(() => {});
                }

                fsContainer.classList.remove('is-fullscreen');

                fullscreenIcon.classList.replace('fa-compress', 'fa-expand');
            }
        }
        const audioCache = {};

        function preloadAudio() {

            const allAudios = [
                'B', 'C', 'D', 'F', 'G', 'H', 'J',
                'K', 'L', 'M', 'N', 'P', 'Q',
                'R', 'S', 'T', 'V', 'W', 'X',
                'Y', 'Z',
                'bagus',
                'tahniah'
            ];

            allAudios.forEach(name => {

                const audio = new Audio(
                    `${window.gameConfig.audioBaseUrl}/${name}.mp3`
                );

                audio.preload = 'auto';

                audioCache[name] = audio;
            });
        }

        function playAudio(fileName) {

            const originalAudio = audioCache[fileName];

            if (!originalAudio) return;

            const sound = originalAudio.cloneNode();

            sound.play().catch(error => {
                console.error("Audio error:", error);
            });
        }

        const MAX_ROUNDS = 10;
        const consonants = ['B', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X',
            'Y', 'Z'
        ];

        let score = 0;
        let currentRound = 0;
        let lastHole;
        let timeUp = false;
        let gameInterval;
        let sessionMistakes = {};

        const holes = document.querySelectorAll('.hole');
        const moles = document.querySelectorAll('.mole');
        const scoreDisplay = document.getElementById('score-display');
        const roundDisplay = document.getElementById('round-display');
        const startOverlay = document.getElementById('start-overlay');
        const winOverlay = document.getElementById('win-overlay');

        function randomHole(holes) {
            const idx = Math.floor(Math.random() * holes.length);
            const hole = holes[idx];
            if (hole === lastHole) return randomHole(holes);
            lastHole = hole;
            return hole;
        }

        function randomTime(min, max) {
            return Math.round(Math.random() * (max - min) + min);
        }

        function peep() {
            if (timeUp) return;

            if (currentRound >= MAX_ROUNDS) {
                endGame();
                return;
            }

            const time = randomTime(1200, 2200);
            const hole = randomHole(holes);
            const mole = hole.querySelector('.mole');
            const moleText = mole.querySelector('span');

            const randomLetter = consonants[Math.floor(Math.random() * consonants.length)];
            moleText.innerText = randomLetter;
            mole.dataset.letter = randomLetter;
            mole.dataset.whacked = 'false';

            playAudio(randomLetter);
            mole.classList.add('up');

            currentRound++;
            roundDisplay.innerText = `${currentRound}/${MAX_ROUNDS}`;

            gameInterval = setTimeout(() => {
                mole.classList.remove('up');

                if (mole.dataset.whacked === 'false' && !timeUp) {
                    let mistakeKey = `${randomLetter}_Terlepas`;
                    sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                }

                if (!timeUp && currentRound < MAX_ROUNDS) {
                    gameInterval = setTimeout(peep, 800);
                } else if (currentRound >= MAX_ROUNDS) {
                    setTimeout(endGame, 1000);
                }
            }, time);
        }

        function startGame() {
            preloadAudio();
            score = 0;
            currentRound = 0;
            timeUp = false;
            sessionMistakes = {};
            scoreDisplay.innerText = 0;
            roundDisplay.innerText = `0/${MAX_ROUNDS}`;

            startOverlay.style.display = 'none';
            winOverlay.classList.add('hidden');
            clearTimeout(gameInterval);

            moles.forEach(mole => mole.classList.remove('up'));
            setTimeout(peep, 1000);
        }

        function restartGame() {
            startGame();
        }

        function renderStars(starCount) {
            const container = document.getElementById('star-container');
            container.innerHTML = '';
            for (let i = 0; i < 3; i++) {
                const isEarned = i < starCount;
                const starColor = isEarned ? 'text-yellow-400 drop-shadow-md' : 'text-slate-300 drop-shadow-none';
                const starSize = i === 1 ? 'text-6xl -mt-4' : 'text-5xl';
                container.innerHTML += `<i class="fa-solid fa-star ${starColor} ${starSize} transition-all"></i>`;
            }
        }

        function endGame() {
            timeUp = true;
            document.getElementById('final-score').innerText = score;
            const earnedStars = calculateStars();
            renderStars(earnedStars);

            winOverlay.classList.remove('hidden');
            playAudio('tahniah');
        }

        function whack(e) {
            if (!e.isTrusted) return;

            if (this.classList.contains('up')) {
                this.dataset.whacked = 'true';
                score++;
                this.classList.remove('up');
                scoreDisplay.innerText = score;

                playAudio('bagus');

                const sparkle = document.createElement('div');
                sparkle.classList.add('sparkle-effect');
                sparkle.innerHTML = '<i class="fa-solid fa-star text-yellow-400 drop-shadow-md"></i>';

                const rect = this.getBoundingClientRect();
                sparkle.style.left = (e.clientX - rect.left - 20) + 'px';
                sparkle.style.top = (e.clientY - rect.top - 20) + 'px';

                this.parentElement.appendChild(sparkle);
                setTimeout(() => sparkle.remove(), 500);
            }
        }

        moles.forEach(mole => mole.addEventListener('pointerdown', whack));

        function calculateStars() {
            if (score >= 8) return 3;
            if (score >= 5) return 2;
            return 1;
        }

        function saveAndExit(event) {
            const btn = event.currentTarget;
            if (btn.disabled) return;

            btn.disabled = true;
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

            fetch(window.gameConfig.saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        child_id: window.gameConfig.childId,
                        game_level_id: window.gameConfig.levelId,
                        score: score,
                        stars_earned: calculateStars(),
                        is_completed: true,
                        mistakes: sessionMistakes
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.href = window.gameConfig.nextLevelUrl;
                    } else {
                        alert("Ralat: " + (data.message || "Gagal menyimpan progress"));
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Ralat rangkaian. Sila semak sambungan internet anda.");
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                });
        }
    </script>
@endsection
