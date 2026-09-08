@extends('layouts.game')

@section('game-title', 'Tahap 15: Kunci Harta Karun Digraf')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 15 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#FEF3C7] transition-all duration-300">

        <div
            class="absolute inset-0 z-0 pointer-events-none opacity-40 bg-[url('{{ asset('images/games/bg-treasure.png') }}')] bg-cover bg-center">
        </div>
        <div
            class="absolute bottom-0 w-full h-1/2 bg-gradient-to-t from-amber-900/80 to-transparent z-0 pointer-events-none">
        </div>

        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0 z-20">
                <div class="flex items-center bg-amber-100 border-4 border-amber-400 rounded-full px-4 py-2 shadow-sm">
                    <span class="text-amber-800 font-black text-sm md:text-xl uppercase tracking-wider font-lexend">
                        <i class="fa-solid fa-key mr-2"></i> Huruf Digraf
                    </span>
                </div>

                <div class="flex items-center gap-3 md:gap-5 font-lexend">
                    <div
                        class="flex flex-col items-center bg-white border-4 border-emerald-300 rounded-2xl px-4 py-1 shadow-sm">
                        <span class="text-emerald-500 font-bold text-xs md:text-sm uppercase tracking-wider">Markah</span>
                        <span id="score-display" class="text-emerald-700 font-black text-xl md:text-2xl">0</span>
                    </div>

                    <div
                        class="flex flex-col items-center bg-white border-4 border-amber-300 rounded-2xl px-4 py-1 shadow-sm">
                        <span class="text-amber-600 font-bold text-xs md:text-sm uppercase tracking-wider">Peti</span>
                        <span id="round-display" class="text-amber-800 font-black text-xl md:text-2xl">0/10</span>
                    </div>
                    <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                        class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                        <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                    </button>
                </div>
            </div>

            <div id="game-board"
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-amber-300 w-full flex flex-col items-center bg-transparent pt-4 pb-4">

                <div
                    class="text-center z-10 bg-white/90 px-6 py-2 rounded-full border-2 border-amber-200 shadow-md backdrop-blur-sm flex items-center gap-4 mt-2">
                    <button onclick="playTargetAudio()"
                        class="w-12 h-12 bg-amber-400 text-white rounded-full flex items-center justify-center text-xl border-4 border-amber-500 shadow-[0_4px_0_#B45309] active:translate-y-1 active:shadow-none transition-all pulse-btn">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>
                    <h2 class="text-base md:text-xl font-black text-amber-800 tracking-wide font-lexend responsive-text">
                        Pilih kunci yang betul!</h2>
                </div>

                <div class="relative w-full max-w-3xl flex flex-col items-center mt-4 z-10 flex-1 justify-start px-4">

                    <div class="flex items-center justify-center gap-2 md:gap-4 mb-4 bg-slate-800/90 px-6 md:px-10 py-4 md:py-6 rounded-3xl border-4 border-slate-600 shadow-xl z-20 transition-all duration-300 mt-4"
                        id="word-lock-container">
                        <span id="word-prefix"
                            class="text-white font-black text-4xl md:text-6xl font-lexend uppercase tracking-widest">BU</span>

                        <div id="digraph-slot"
                            class="w-20 h-16 md:w-28 md:h-20 bg-slate-900 border-4 border-dashed border-amber-400 rounded-xl flex items-center justify-center shadow-inner transition-all relative overflow-hidden">
                            <i id="lock-icon" class="fa-solid fa-lock text-amber-500/50 text-3xl"></i>
                            <span id="slot-text"
                                class="absolute inset-0 flex items-center justify-center text-amber-400 font-black text-4xl md:text-6xl opacity-0 transform scale-50 transition-all duration-300 uppercase">NG</span>
                        </div>

                        <span id="word-suffix"
                            class="text-white font-black text-4xl md:text-6xl font-lexend uppercase tracking-widest">A</span>
                    </div>

                    <div class="relative flex justify-center items-end mt-auto mb-4" id="treasure-container">
                        <img id="chest-closed" src="{{ asset('images/games/chest-closed.png') }}"
                            class="w-40 h-40 md:w-56 md:h-56 object-contain filter drop-shadow-[0_20px_20px_rgba(0,0,0,0.5)] transition-all duration-300 z-20">

                        <img id="chest-open" src="{{ asset('images/games/chest-open.png') }}"
                            class="hidden w-44 h-44 md:w-60 md:h-60 object-contain filter drop-shadow-[0_20px_20px_rgba(0,0,0,0.5)] transition-all duration-300 z-10">

                        <div id="hidden-image-container"
                            class="absolute bottom-16 md:bottom-20 z-30 opacity-0 transform translate-y-10 scale-50 transition-all duration-700 flex flex-col items-center pointer-events-none">
                            <div class="bg-white p-2 rounded-2xl border-4 border-amber-300 shadow-2xl">
                                <img id="question-img" src="" class="w-56 h-56 md:w-72 md:h-72 object-contain">
                            </div>
                        </div>
                    </div>

                </div>

                <div
                    class="w-full bg-[#B45309] p-6 md:p-8 rounded-t-[3rem] border-t-8 border-[#78350F] shadow-[0_-10px_30px_rgba(0,0,0,0.5)] z-30 flex flex-col items-center min-h-[160px] relative">

                    <div class="flex flex-wrap justify-center gap-4 md:gap-8 w-full max-w-3xl" id="options-container">
                    </div>
                </div>

            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-slate-900/90 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">
            <div
                class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full flex flex-col border-[6px] border-amber-400 font-lexend">
                <div class="text-6xl mb-4 text-slate-800"><i class="fa-solid fa-skull-crossbones"></i></div>
                <h1 class="text-3xl md:text-4xl font-black text-amber-600 mb-6">Misi Digraf</h1>
                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10">
                    <div class="flex items-center gap-4 bg-sky-50 p-4 rounded-xl border-2 border-sky-200 shadow-sm">
                        <div
                            class="w-12 h-12 bg-sky-200 text-sky-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-volume-high"></i>
                        </div>
                        <p class="text-slate-700 font-bold text-lg leading-tight">Dengar perkataan yang mempunyai bunyi
                            gabungan (ng, ny, sy, kh).</p>
                    </div>
                    <div class="flex items-center gap-4 bg-red-50 p-4 rounded-xl border-2 border-red-200 shadow-sm">
                        <div
                            class="w-12 h-12 bg-red-200 text-red-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <p class="text-slate-700 font-bold text-lg leading-tight">Pilih <b>huruf digraf</b> yang betul untuk
                            membuka peti harta.</b></p>
                    </div>
                </div>
                <button onclick="startGame()"
                    class="btn-3d bg-amber-500 border-amber-700 text-white w-full text-2xl font-black py-4 rounded-[1.5rem] flex justify-center items-center gap-3 hover:bg-amber-400">
                    <i class="fa-solid fa-play"></i> Mula
                </button>
            </div>
        </div>

        <div id="win-overlay"
            class="hidden absolute inset-0 bg-black/80 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8 overflow-y-auto">

            <div
                class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full animate-bounce-in border-[6px] border-indigo-200 relative my-auto">

                <div
                    class="absolute -top-12 left-1/2 transform -translate-x-1/2 text-7xl drop-shadow-lg text-yellow-400 bg-white rounded-full p-2 border-4 border-yellow-200 w-32 h-32 flex justify-center items-center">
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
                        <i class="fa-solid fa-rotate-right"></i> Main Semula
                    </button>
                </div>
            </div>
        </div>

    </div>

    <audio id="audio-player" preload="auto"></audio>


    <style>
        * {
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .btn-3d {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        /* BUTANG KUNCI DIGRAF */
        .key-btn {
            background: linear-gradient(to bottom, #FDE047, #F59E0B);
            border-color: #B45309;
            border-top-color: #FEF08A;
            border-left-color: #FEF08A;
            color: #78350F;
            width: 100px;
            height: 80px;
            font-size: 2.2rem;
            font-weight: 900;
            border-radius: 1.5rem;
            text-transform: uppercase;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        @media (min-width: 768px) {
            .key-btn {
                width: 130px;
                height: 100px;
                font-size: 3rem;
            }
        }

        .key-btn:not(:disabled):hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.5);
            filter: brightness(1.1);
        }

        /* STATUS JAWAPAN MANGGA (LOCK) */
        .lock-success {
            border-color: #10B981 !important;
            background-color: #059669 !important;
            border-style: solid !important;
        }

        .lock-error {
            animation: shake 0.4s ease-in-out;
            border-color: #EF4444 !important;
            background-color: #7F1D1D !important;
            border-style: solid !important;
        }

        .word-success {
            border-color: #10B981 !important;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.5) !important;
        }

        /* CSS UNTUK DUIT SYILING JATUH */
        .falling-coin {
            position: fixed;
            top: -50px;
            font-size: 2rem;
            color: #FBBF24;
            z-index: 100;
            animation: coin-drop linear forwards;
            pointer-events: none;
        }

        @keyframes coin-drop {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(110vh) rotate(360deg);
                opacity: 0;
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
            }
        }

        @keyframes pulse-btn {
            0% {
                transform: scale(1);
                box-shadow: 0 4px 0 #B45309, 0 0 0 0 rgba(245, 158, 11, 0.7);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 4px 0 #B45309, 0 0 0 15px rgba(245, 158, 11, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 4px 0 #B45309, 0 0 0 0 rgba(245, 158, 11, 0);
            }
        }

        .pulse-btn {
            animation: pulse-btn 2s infinite;
        }

        @keyframes bounce-in {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            60% {
                transform: scale(1.1);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-bounce-in {
            animation: bounce-in 0.5s forwards;
        }

        /* Responsif Skrin Penuh */
        .is-fullscreen .responsive-text {
            font-size: 2.5rem !important;
        }

        .is-fullscreen .key-btn {
            width: 160px !important;
            height: 120px !important;
            font-size: 4rem !important;
        }
    </style>

    <script>
          window.addEventListener('resize', () => {

            if (window.innerHeight === screen.height) {
                fsContainer.classList.add('is-fullscreen');
            } else {
                fsContainer.classList.remove('is-fullscreen');
            }

        });
        const fsContainer = document.getElementById('game-fullscreen-container');
        const fullscreenIcon = document.getElementById('fullscreen-icon');
        const audioPlayer = document.getElementById('audio-player');

        const keySound = document.getElementById('sfx-key');
        const unlockSound = document.getElementById('sfx-unlock');
        const correctSound = new Audio('{{ asset('audio/bagus.mp3') }}');
        const wrongSound = new Audio('{{ asset('audio/cubalagi.mp3') }}');
        const winSound = new Audio('{{ asset('audio/tahniah.mp3') }}');

       function toggleFullScreen() {

            const isFullscreen = fsContainer.classList.contains('is-fullscreen');

            if (!isFullscreen) {

                fsContainer.classList.add('is-fullscreen');

                if (fsContainer.requestFullscreen) {
                    fsContainer.requestFullscreen().catch(() => {});
                } else if (fsContainer.webkitRequestFullscreen) {
                    fsContainer.webkitRequestFullscreen();
                }

                fullscreenIcon.classList.replace('fa-expand', 'fa-compress');

            } else {

                fsContainer.classList.remove('is-fullscreen');

                if (document.fullscreenElement) {
                    document.exitFullscreen().catch(() => {});
                } else if (document.webkitFullscreenElement) {
                    document.webkitExitFullscreen();
                }

                fullscreenIcon.classList.replace('fa-compress', 'fa-expand');
            }
        }

        // --- DATA SOALAN DIGRAF (NG, NY, SY, KH) ---
        const gameData = [{
                word: 'Bunga',
                prefix: 'BU',
                digraph: 'NG',
                suffix: 'A',
                distractors: ['NY', 'SY', 'KH'],
                img: '{{ asset('images/games/bunga.png') }}',
                audio: '{{ asset('audio/Bunga.mp3') }}'
            },
            {
                word: 'Singa',
                prefix: 'SI',
                digraph: 'NG',
                suffix: 'A',
                distractors: ['NY', 'SY', 'KH'],
                img: '{{ asset('images/games/singa.png') }}',
                audio: '{{ asset('audio/Singa.mp3') }}'
            },
            {
                word: 'Penyu',
                prefix: 'PE',
                digraph: 'NY',
                suffix: 'U',
                distractors: ['NG', 'SY', 'KH'],
                img: '{{ asset('images/games/penyu.png') }}',
                audio: '{{ asset('audio/Penyu2.mp3') }}'
            },
            {
                word: 'Nyamuk',
                prefix: '',
                digraph: 'NY',
                suffix: 'AMUK',
                distractors: ['NG', 'SY', 'KH'],
                img: '{{ asset('images/games/nyamuk.png') }}',
                audio: '{{ asset('audio/Nyamuk.mp3') }}'
            },
            {
                word: 'Syampu',
                prefix: '',
                digraph: 'SY',
                suffix: 'AMPU',
                distractors: ['NY', 'NG', 'KH'],
                img: '{{ asset('images/games/syampu.png') }}',
                audio: '{{ asset('audio/Syampu.mp3') }}'
            },
            {
                word: 'Angsa',
                prefix: 'A',
                digraph: 'NG',
                suffix: 'SA',
                distractors: ['NY', 'SY', 'NG'],
                img: '{{ asset('images/games/angsa.png') }}',
                audio: '{{ asset('audio/Angsa.mp3') }}'
            },
            {
                word: 'Telinga',
                prefix: 'TELI',
                digraph: 'NG',
                suffix: 'A',
                distractors: ['NY', 'SY', 'KH'],
                img: '{{ asset('images/games/telinga.png') }}',
                audio: '{{ asset('audio/Telinga.mp3') }}'
            },
            {
                word: 'Kucing',
                prefix: 'KUCI',
                digraph: 'NG',
                suffix: '',
                distractors: ['NJ', 'NO', 'NK'],
                img: '{{ asset('images/games/kucing.png') }}',
                audio: '{{ asset('audio/Kucing(1).mp3') }}'
            },
            {
                word: 'Pinggan',
                prefix: 'PI',
                digraph: 'NG',
                suffix: 'GAN',
                distractors: ['NO', 'NK', 'NA'],
                img: '{{ asset('images/games/pinggan.png') }}',
                audio: '{{ asset('audio/Pinggan.mp3') }}'
            },
            {
                word: 'Monyet',
                prefix: 'MO',
                digraph: 'NY',
                suffix: 'ET',
                distractors: ['NG', 'SY', 'KH'],
                img: '{{ asset('images/games/monyet.png') }}',
                audio: '{{ asset('audio/Monyet.mp3') }}'
            }
        ];

        const MAX_ROUNDS = 10;
        let shuffledData = [];
        let currentRound = 0;
        let score = 0;
        let currentData = {};
        let isWaiting = false;
        let sessionMistakes = {};

        const wordLockContainer = document.getElementById('word-lock-container');
        const prefixElem = document.getElementById('word-prefix');
        const suffixElem = document.getElementById('word-suffix');
        const digraphSlot = document.getElementById('digraph-slot');
        const slotText = document.getElementById('slot-text');
        const lockIcon = document.getElementById('lock-icon');

        const chestClosed = document.getElementById('chest-closed');
        const chestOpen = document.getElementById('chest-open');
        const questionImg = document.getElementById('question-img');
        const hiddenImgContainer = document.getElementById('hidden-image-container');

        const optionsContainer = document.getElementById('options-container');
        const scoreDisplay = document.getElementById('score-display');
        const roundDisplay = document.getElementById('round-display');

        // Preload imej 
        document.addEventListener('DOMContentLoaded', () => {
            gameData.forEach(data => {
                if (data.img.includes('{{ asset')) data.img =
                    `https://placehold.co/300x300/FBBF24/FFF?text=${data.word}`;
                const img = new Image();
                img.src = data.img;
            });
        });

        function playAudio(fileName) {
            audioPlayer.src = `/audio/${fileName}.mp3`;
            audioPlayer.play().catch(err => {
                console.error("Audio error:", err);
            });
        }

        function playTargetAudio() {
            if (isWaiting) return;
            audioPlayer.src = currentData.audio;
            audioPlayer.play().catch(err => console.log(err));
        }

        function startGame() {
            document.getElementById('start-overlay').style.display = 'none';
            document.getElementById('win-overlay').classList.add('hidden');
            shuffledData = [...gameData].sort(() => Math.random() - 0.5);
            if (shuffledData.length > MAX_ROUNDS) shuffledData.length = MAX_ROUNDS;

            score = 0;
            currentRound = 0;
            sessionMistakes = {};
            updateUI();
            loadQuestion();
        }

        function updateUI() {
            scoreDisplay.innerText = score;
            roundDisplay.innerText = `${currentRound + 1}/${MAX_ROUNDS}`;
        }

        function loadQuestion() {
            if (currentRound >= MAX_ROUNDS) {
                endGame();
                return;
            }

            isWaiting = false;
            currentData = shuffledData[currentRound];

            prefixElem.innerText = currentData.prefix;
            suffixElem.innerText = currentData.suffix;


            hiddenImgContainer.style.transition = "none";
            hiddenImgContainer.style.opacity = "0";
            hiddenImgContainer.style.transform = "translate(0, 20px) scale(0.5)";

            questionImg.src = currentData.img;

            setTimeout(() => {
                hiddenImgContainer.style.transition = "";
            }, 50);

            updateUI();

            wordLockContainer.classList.remove('word-success');
            digraphSlot.className =
                "w-20 h-16 md:w-28 md:h-20 bg-slate-900 border-4 border-dashed border-amber-400 rounded-xl flex items-center justify-center shadow-inner transition-all relative overflow-hidden";
            lockIcon.className = "fa-solid fa-lock text-amber-500/50 text-3xl transition-all duration-300";
            lockIcon.style.opacity = "1";
            lockIcon.style.transform = "scale(1)";

            slotText.innerText = currentData.digraph;
            slotText.className =
                "absolute inset-0 flex items-center justify-center text-amber-400 font-black text-4xl md:text-6xl opacity-0 transform scale-50 transition-all duration-300 uppercase";

            chestClosed.classList.remove('hidden');
            chestOpen.classList.add('hidden');


            generateOptions();

            setTimeout(() => playTargetAudio(), 800);
        }

        function generateOptions() {
            optionsContainer.innerHTML = '';

            let options = [currentData.digraph, ...currentData.distractors].sort(() => Math.random() - 0.5);

            options.forEach((digraph) => {
                const btn = document.createElement('button');
                btn.className = 'btn-3d key-btn transition-all flex flex-col items-center justify-center';

                btn.innerHTML = `
                    <span class="text-xl md:text-2xl -mb-1 opacity-80"><i class="fa-solid fa-key"></i></span>
                    <span>${digraph}</span>
                `;

                btn.onclick = () => selectKey(digraph, btn);
                optionsContainer.appendChild(btn);
            });
        }

        function spawnCoins() {
            const container = document.getElementById('game-fullscreen-container');

            for (let i = 0; i < 20; i++) {
                setTimeout(() => {
                    const coin = document.createElement('i');
                    coin.className = 'fa-solid fa-coins falling-coin text-yellow-400 drop-shadow-md';
                    coin.style.left = (Math.random() * 100) + 'vw';
                    coin.style.fontSize = (Math.random() * 2 + 1.5) + 'rem';
                    coin.style.animationDuration = (Math.random() * 1.5 + 1.5) + 's';

                    container.appendChild(coin);
                    setTimeout(() => coin.remove(), 3500);
                }, i * 100);
            }
        }

        function selectKey(selectedDigraph, btnElement) {
            if (isWaiting) return;
            isWaiting = true; // Kunci permainan

            const allBtns = document.querySelectorAll('.key-btn');
            allBtns.forEach(btn => btn.disabled = true);

            // --- TAMBAH IF DI SINI ---
            if (keySound) {
                keySound.currentTime = 0;
                keySound.play().catch(e => {});
            }

            if (selectedDigraph === currentData.digraph) {
                // JAWAPAN BETUL
                score += 1;
                updateUI();

                digraphSlot.classList.add('lock-success');
                digraphSlot.classList.remove('border-dashed');
                lockIcon.classList.replace('fa-lock', 'fa-unlock');
                lockIcon.style.transform = "scale(1.5)";
                lockIcon.style.opacity = "0";

                if (correctSound) {
                    correctSound.currentTime = 0;
                    correctSound.play().catch(e => {});
                }

                setTimeout(() => {
                    slotText.classList.replace('opacity-0', 'opacity-100');
                    slotText.classList.replace('scale-50', 'scale-100');
                    slotText.classList.add('text-white');
                    wordLockContainer.classList.add('word-success');
                }, 300);

                setTimeout(() => {
                    // --- TAMBAH IF DI SINI ---
                    if (unlockSound) {
                        unlockSound.currentTime = 0;
                        unlockSound.play().catch(e => {});
                    }

                    chestClosed.classList.add('hidden');
                    chestOpen.classList.remove('hidden');

                    hiddenImgContainer.style.opacity = "1";
                    hiddenImgContainer.style.transform = "translate(0, -60px) scale(1)";

                    spawnCoins();


                    setTimeout(() => {
                        currentRound++;
                        loadQuestion();
                    }, 4000);

                }, 800);

            } else {
                // JAWAPAN SALAH
                if (wrongSound) {
                    wrongSound.currentTime = 0;
                    wrongSound.play().catch(e => {});
                }

                digraphSlot.classList.add('lock-error');

                btnElement.style.background = "linear-gradient(to bottom, #EF4444, #B91C1C)";
                btnElement.style.borderColor = "#7F1D1D";
                btnElement.style.color = "#FECACA";
                let mistakeKey = `${currentData.word}_${selectedDigraph}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                // Terus skip soalan selepas melihat salah
                setTimeout(() => {
                    currentRound++;
                    loadQuestion();
                }, 2000);
            }
        }
        // Render Bintang dalam Win Overlay
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

        function calculateStars() {
            return score >= 8 ? 3 : (score >= 5 ? 2 : 1);
        }

        function endGame() {
            document.getElementById('final-score').innerText = score;
            const earnedStars = calculateStars();
            renderStars(earnedStars);

            const winOverlay = document.getElementById('win-overlay');
            winOverlay.classList.remove('hidden');
            window.speechSynthesis.cancel();
            playAudio('tahniah');
        }

        function saveAndExit(event) {
            const btn = event.target.closest('button');
            btn.disabled = true;
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
            }).then(r => r.json()).then(data => {
                if (data.success) window.location.href = window.gameConfig.nextLevelUrl;
                else {
                    alert("Ralat: " + data.message);
                    btn.disabled = false;
                    btn.innerHTML = "Tahap Seterusnya <i class='fa-solid fa-forward-step ml-2'></i>";
                }
            }).catch(e => {
                alert("Ralat rangkaian.");
                btn.disabled = false;
                btn.innerHTML = "Tahap Seterusnya <i class='fa-solid fa-forward-step ml-2'></i>";
            });
        }
    </script>
@endsection
