@extends('layouts.game')

@section('game-title', 'Tahap 13: Lengkapkan Perkataan')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@700&display=swap" rel="stylesheet">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 13 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-sky-50 transition-all duration-300">

        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0 z-20">
                <div class="flex items-center bg-white border-4 border-blue-300 rounded-full px-4 py-2 shadow-md">
                    <span class="text-blue-600 font-black text-sm md:text-xl uppercase tracking-wider font-lexend">
                        <i class="fa-solid fa-spell-check mr-2"></i> Lengkapkan Ejaan
                    </span>
                </div>

                <div class="flex items-center gap-3 md:gap-5 font-lexend">
                    <div
                        class="flex flex-col items-center bg-white border-4 border-emerald-300 rounded-2xl px-4 py-1 shadow-md">
                        <span class="text-emerald-500 font-bold text-xs md:text-sm uppercase tracking-wider">Markah</span>
                        <span id="score-display" class="text-slate-700 font-black text-xl md:text-2xl">0</span>
                    </div>

                    <div
                        class="flex flex-col items-center bg-white border-4 border-sky-300 rounded-2xl px-4 py-1 shadow-md">
                        <span class="text-sky-500 font-bold text-xs md:text-sm uppercase tracking-wider">Soalan</span>
                        <span id="round-display" class="text-slate-700 font-black text-xl md:text-2xl">0/10</span>
                    </div>
                     <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                        class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                        <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                    </button>
                </div>
            </div>

            <div id="game-board"
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-xl border-4 border-white w-full flex flex-col justify-between items-center bg-blue-50 pt-2">

                <div
                    class="absolute inset-0 z-0 pointer-events-none bg-gradient-to-br from-sky-100 via-white to-blue-100 opacity-90">
                </div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(56,189,248,0.1)_1px,transparent_1px)]"
                    style="background-size: 20px 20px;"></div>

                <div
                    class="text-center z-10 bg-white/90 px-6 py-2 rounded-full border-4 border-amber-200 shadow-sm backdrop-blur-sm flex items-center gap-4 mt-4 md:mt-6">
                    <button onclick="playTargetAudio()"
                        class="w-10 h-10 md:w-12 md:h-12 bg-amber-400 text-white rounded-full flex items-center justify-center text-lg md:text-xl border-4 border-amber-200 shadow-[0_4px_0_#D97706] active:translate-y-1 active:shadow-none transition-all pulse-btn">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>
                    <h2 class="text-lg md:text-2xl font-black text-amber-500 tracking-wide font-lexend responsive-text">
                        Pilih huruf yang betul!</h2>
                </div>

                <div class="relative z-10 mt-4 md:mt-6 flex justify-center">
                    <img id="question-img" src=""
                        class="w-28 h-28 md:w-44 md:h-44 object-contain bg-white rounded-[2rem] p-3 shadow-lg border-4 border-dashed border-sky-300 transition-all duration-300 hover:scale-105"
                        alt="Gambar Soalan">
                </div>

                <div class="relative z-10 flex flex-row flex-wrap items-center justify-center flex-1 w-full gap-2 md:gap-4 my-4 md:my-6 responsive-building"
                    id="question-container">

                    <div id="box-1"
                        class="w-20 h-24 md:w-32 md:h-36 bg-white rounded-2xl flex items-center justify-center shadow-lg border-[6px] border-slate-100 transition-all duration-300 z-10 box-block">
                        <span id="syl-1" class="text-slate-700 font-black text-3xl md:text-5xl font-lexend">PE</span>
                    </div>

                    <div id="box-2"
                        class="w-20 h-24 md:w-32 md:h-36 bg-white rounded-2xl flex items-center justify-center shadow-lg border-[6px] border-slate-100 transition-all duration-300 z-10 box-block">
                        <span id="syl-2" class="text-slate-700 font-black text-3xl md:text-5xl font-lexend">LA</span>
                    </div>

                    <div id="box-3"
                        class="px-3 py-2 md:px-5 h-24 md:h-36 bg-white rounded-2xl flex items-center justify-center shadow-lg border-[6px] border-blue-400 transition-all duration-300 z-20 box-block gap-1 md:gap-2">
                        <span id="syl-3-base" class="text-slate-700 font-black text-3xl md:text-5xl font-lexend">JA</span>
                        <div id="missing-letter-box"
                            class="w-10 h-12 md:w-16 md:h-20 bg-blue-50 border-4 border-dashed border-blue-400 rounded-xl flex items-center justify-center transition-all">
                            <span id="syl-3-ans" class="text-blue-500 font-black text-2xl md:text-4xl opacity-50">_</span>
                        </div>
                    </div>
                </div>

                <div id="success-modal"
                    class="absolute inset-0 bg-white/95 z-40 flex flex-col items-center justify-center opacity-0 pointer-events-none transition-opacity duration-500 backdrop-blur-md">
                    <p class="text-emerald-500 font-bold text-xl md:text-3xl uppercase tracking-widest mb-4 font-lexend">
                        <i class="fa-solid fa-check-circle mr-2"></i> Jawapan Betul!
                    </p>
                    <div class="relative">
                        <div class="absolute inset-0 bg-yellow-300 rounded-full blur-[50px] opacity-40 animate-pulse"></div>
                        <img id="success-img" src=""
                            class="w-64 h-64 md:w-80 md:h-80 object-contain animate-bounce-in drop-shadow-xl border-4 border-emerald-300 bg-white rounded-[2rem] p-4 mb-4 relative z-10">
                    </div>
                    <h1 id="success-word"
                        class="font-lexend font-black text-5xl md:text-7xl text-emerald-600 tracking-widest uppercase drop-shadow-md animate-bounce-in mt-4"
                        style="animation-delay: 0.2s;">PERKATAAN</h1>
                </div>

                <div
                    class="w-full bg-sky-100 p-6 md:p-8 rounded-t-[3rem] border-t-4 border-sky-300 shadow-[0_-10px_20px_rgba(14,165,233,0.1)] z-20 flex flex-col items-center min-h-[160px]">
                    <div class="flex flex-wrap justify-center gap-4 md:gap-8 w-full max-w-3xl" id="options-container">
                    </div>
                </div>

            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-sky-100/90 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8">
            <div
                class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full flex flex-col border-[6px] border-blue-300 font-lexend">
                <div class="text-6xl mb-4 text-blue-500"><i class="fa-solid fa-puzzle-piece"></i></div>
                <h1 class="text-3xl md:text-4xl font-black text-blue-600 mb-6 tracking-wider">Cari Huruf Terakhir</h1>
                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10">
                    <div class="flex items-center gap-4 bg-blue-50 p-4 rounded-xl border-2 border-blue-200 shadow-sm">
                        <div
                            class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-ear-listen"></i>
                        </div>
                        <p class="text-slate-600 font-bold text-lg leading-tight">Dengar perkataan dengan teliti</p>
                    </div>

                    <div class="flex items-center gap-4 bg-amber-50 p-4 rounded-xl border-2 border-amber-200 shadow-sm">
                        <div
                            class="w-12 h-12 bg-amber-400 text-white rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-pencil"></i>
                        </div>
                        <p class="text-slate-600 font-bold text-lg leading-tight">Pilih huruf yang betul untuk ejaan</p>
                    </div>
                </div>
                <button onclick="startGame()"
                    class="btn-3d bg-blue-500 border-blue-700 text-white w-full text-2xl font-black py-4 rounded-[1.5rem] flex justify-center items-center gap-3 hover:bg-blue-400">
                    <i class="fa-solid fa-play"></i> Mula
                </button>
            </div>
        </div>

        <div id="win-overlay"
            class="hidden absolute inset-0 bg-white/90 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8 overflow-y-auto">

            <div
                class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full animate-bounce-in border-[6px] border-indigo-200 relative my-auto">
                <div
                    class="absolute -top-12 left-1/2 transform -translate-x-1/2 text-7xl drop-shadow-lg text-yellow-400 bg-white rounded-full p-2 border-4 border-yellow-200 w-32 h-32 flex justify-center items-center">
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
                        class="btn-3d w-full bg-slate-100 border-slate-300 text-slate-600 text-lg md:text-xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3 hover:bg-slate-200">
                        Main Semula <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <audio id="audio-player" preload="auto"></audio>
    <audio id="sfx-wrong" src="https://assets.mixkit.com/sfx/preview/mixkit-system-fault-36.mp3"></audio>
    <audio id="sfx-build" src="https://assets.mixkit.com/sfx/preview/mixkit-stone-smash-1502.mp3"></audio>
    <audio id="sfx-magic" src="https://assets.mixkit.com/sfx/preview/mixkit-magical-coin-win-1936.mp3"></audio>

    <style>
        .font-lexend {
            font-family: 'Lexend', sans-serif;
        }

        .font-fredoka {
            font-family: 'Fredoka', sans-serif;
        }

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

        /* BUTANG HURUF TEMA CERAH */
        .letter-btn {
            background-color: #ffffff;
            border-color: #bae6fd;
            border-top-color: #e0f2fe;
            border-left-color: #e0f2fe;
            color: #0284c7;
            width: 80px;
            height: 80px;
            font-size: 2.5rem;
            font-weight: 900;
            border-radius: 1.5rem;
            font-family: 'Fredoka', sans-serif;
            flex-shrink: 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        @media (min-width: 768px) {
            .letter-btn {
                width: 110px;
                height: 110px;
                font-size: 3.5rem;
            }
        }

        .letter-btn:not(:disabled):hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 10px 20px rgba(14, 165, 233, 0.2);
            background-color: #f0f9ff;
            color: #0ea5e9;
        }

        /* ANIMASI BACAAN */
        .reading-highlight {
            background-color: #FEF08A !important;
            border-color: #EAB308 !important;
            box-shadow: 0 0 20px rgba(234, 179, 8, 0.6);
            transform: scale(1.1);
            z-index: 40 !important;
        }

        /* STATUS KOTAK BILA SIAP */
        .question-success #box-1,
        .question-success #box-2,
        .question-success #box-3 {
            background-color: #10B981 !important;
            border-color: #34D399 !important;
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.5);
        }

        .question-success span {
            color: white !important;
        }

        .question-success #missing-letter-box {
            background-color: transparent !important;
            border-color: transparent !important;
        }

        .question-success #syl-3-ans {
            opacity: 1 !important;
            color: white !important;
        }

        /* STATUS RALAT */
        .question-error #box-3 {
            animation: heavy-shake 0.4s ease-in-out;
            background-color: #FEE2E2 !important;
            border-color: #EF4444 !important;
        }

        .question-error #missing-letter-box {
            border-color: #EF4444;
            background-color: #FECACA;
        }

        .question-error #syl-3-ans {
            color: #DC2626;
            opacity: 1;
        }

        /* Responsif Skrin Penuh Fix */
        .is-fullscreen .responsive-building {
            transform: scale(1.1);
        }

        .is-fullscreen .responsive-text {
            font-size: 2rem !important;
        }

        .is-fullscreen .letter-btn {
            min-width: 140px !important;
            height: 120px !important;
            font-size: 4.5rem !important;
            border-radius: 2rem;
        }

        @keyframes pulse-btn {
            0% {
                transform: scale(1);
                box-shadow: 0 4px 0 #D97706, 0 0 0 0 rgba(245, 158, 11, 0.7);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 4px 0 #D97706, 0 0 0 15px rgba(245, 158, 11, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 4px 0 #D97706, 0 0 0 0 rgba(245, 158, 11, 0);
            }
        }

        .pulse-btn {
            animation: pulse-btn 2s infinite;
        }

        @keyframes heavy-shake {

            0%,
            100% {
                transform: translateX(0) rotate(0);
            }

            25% {
                transform: translateX(-10px) rotate(-3deg);
            }

            75% {
                transform: translateX(10px) rotate(3deg);
            }
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
            animation: bounce-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
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
        const correctSound = new Audio('{{ asset('audio/bagus.mp3') }}');
        const wrongSound = new Audio('{{ asset('audio/cubalagi.mp3') }}');
        const winSound = new Audio('{{ asset('audio/tahniah.mp3') }}');
        const buildSound = document.getElementById('sfx-build');
        const magicSound = document.getElementById('sfx-magic');

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

        // --- DATA SOALAN ---
        const gameData = [{
                word: 'Pelajar',
                f1: 'PE',
                f2: 'LA',
                f3: 'JA',
                ans: 'R',
                distractors: ['N', 'K'],
                img: '{{ asset('images/games/pelajar.png') }}',
                audio: '{{ asset('audio/Pelajar.mp3') }}'
            },
            {
                word: 'Selipar',
                f1: 'SE',
                f2: 'LI',
                f3: 'PA',
                ans: 'R',
                distractors: ['S', 'L'],
                img: '{{ asset('images/games/selipar.png') }}',
                audio: '{{ asset('audio/Selipar.mp3') }}'
            },
            {
                word: 'Basikal',
                f1: 'BA',
                f2: 'SI',
                f3: 'KA',
                ans: 'L',
                distractors: ['R', 'K'],
                img: '{{ asset('images/games/basikal.png') }}',
                audio: '{{ asset('audio/Basikal.mp3') }}'
            },
            {
                word: 'Penapis',
                f1: 'PE',
                f2: 'NA',
                f3: 'PI',
                ans: 'S',
                distractors: ['Z', 'C'],
                img: '{{ asset('images/games/penapis.png') }}',
                audio: '{{ asset('audio/Penapis.mp3') }}'
            },
            {
                word: 'Nelayan',
                f1: 'NE',
                f2: 'LA',
                f3: 'YA',
                ans: 'N',
                distractors: ['M', 'R'],
                img: '{{ asset('images/games/nelayan.png') }}',
                audio: '{{ asset('audio/Nelayan.mp3') }}'
            },
            {
                word: 'Kelawar',
                f1: 'KE',
                f2: 'LA',
                f3: 'WA',
                ans: 'R',
                distractors: ['L', 'K'],
                img: '{{ asset('images/games/kelawar.png') }}',
                audio: '{{ asset('audio/Kelawar.mp3') }}'
            },
            {
                word: 'Ketupat',
                f1: 'KE',
                f2: 'TU',
                f3: 'PA',
                ans: 'T',
                distractors: ['K', 'P'],
                img: '{{ asset('images/games/ketupat.png') }}',
                audio: '{{ asset('audio/Ketupat.mp3') }}'
            },
            {
                word: 'Lukisan',
                f1: 'LU',
                f2: 'KI',
                f3: 'SA',
                ans: 'N',
                distractors: ['M', 'L'],
                img: '{{ asset('images/games/lukisan.png') }}',
                audio: '{{ asset('audio/Lukisan.mp3') }}'
            },
            {
                word: 'Pawagam',
                f1: 'PA',
                f2: 'WA',
                f3: 'GA',
                ans: 'M',
                distractors: ['N', 'R'],
                img: '{{ asset('images/games/pawagam.png') }}',
                audio: '{{ asset('audio/Pawagam.mp3') }}'
            },
            {
                word: 'Zirafah',
                f1: 'ZI',
                f2: 'RA',
                f3: 'FA',
                ans: 'H',
                distractors: ['K', 'R'],
                img: '{{ asset('images/games/zirafah.png') }}',
                audio: '{{ asset('audio/Zirafah.mp3') }}'
            }
        ];

        const MAX_ROUNDS = 10;
        let shuffledData = [];
        let currentRound = 0;
        let score = 0;
        let currentData = {};
        let isWaiting = false;
        let sessionMistakes = {};

        const optionsContainer = document.getElementById('options-container');
        const questionContainer = document.getElementById('question-container');

        const f1Elem = document.getElementById('syl-1');
        const f2Elem = document.getElementById('syl-2');
        const f3BaseElem = document.getElementById('syl-3-base');
        const missingAnsElem = document.getElementById('syl-3-ans');

        document.addEventListener('DOMContentLoaded', () => {
            gameData.forEach(data => {
                if (data.img.includes('{{ asset')) data.img =
                    `https://placehold.co/300x300/e0f2fe/0284c7?text=${data.word}`;
                const img = new Image();
                img.src = data.img;
            });
        });



        function playTargetAudio() {
            if (isWaiting) return;

            audioPlayer.src = currentData.audio;
            audioPlayer.play().catch(e => console.log("Audio play error:", e));

            const boxes = [
                document.getElementById('box-1'),
                document.getElementById('box-2'),
                document.getElementById('box-3')
            ];

            boxes.forEach((box, i) => {
                setTimeout(() => {
                    if (i > 0) boxes[i - 1].classList.remove('reading-highlight');
                    box.classList.add('reading-highlight');
                    if (i === 2) {
                        setTimeout(() => box.classList.remove('reading-highlight'), 1000);
                    }
                }, i * 800); 
            });
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

        function restartGame() {
            startGame();
        }

        function updateUI() {
            document.getElementById('score-display').innerText = score;
            document.getElementById('round-display').innerText = `${currentRound + 1}/${MAX_ROUNDS}`;
        }

        function loadQuestion() {
            if (currentRound >= MAX_ROUNDS) {
                endGame();
                return;
            }

            isWaiting = false;
            currentData = shuffledData[currentRound];

            // Set gambar soalan
            document.getElementById('question-img').src = currentData.img;

            f1Elem.innerText = currentData.f1;
            f2Elem.innerText = currentData.f2;
            f3BaseElem.innerText = currentData.f3;
            missingAnsElem.innerText = "_";
            missingAnsElem.style.opacity = "0.5";

            updateUI();

            questionContainer.classList.remove('question-success', 'question-error');
            document.getElementById('success-modal').classList.replace('opacity-100', 'opacity-0');
            document.getElementById('success-modal').classList.add('pointer-events-none');

            generateOptions();
            setTimeout(() => playTargetAudio(), 800);
        }

        function generateOptions() {
            optionsContainer.innerHTML = '';
            let options = [currentData.ans, ...currentData.distractors].sort(() => Math.random() - 0.5);

            options.forEach((letter) => {
                const btn = document.createElement('button');
                btn.className = 'btn-3d letter-btn transition-all';
                btn.innerText = letter;
                btn.onclick = () => selectLetter(letter, btn);
                optionsContainer.appendChild(btn);
            });
        }

        function selectLetter(letter, btnElement) {
            if (isWaiting) return;
            isWaiting = true;

            const allBtns = document.querySelectorAll('.letter-btn');
            allBtns.forEach(btn => btn.disabled = true);

            missingAnsElem.innerText = letter;
            missingAnsElem.style.opacity = "1";

            if (letter === currentData.ans) {
                score += 1;
                updateUI();
                buildSound.currentTime = 0;
                buildSound.play().catch(e => {});
                correctSound.pause();
                correctSound.currentTime = 0;
                correctSound.play();

                setTimeout(() => {
                    magicSound.currentTime = 0;
                    magicSound.play().catch(e => {});

                    questionContainer.classList.add('question-success');

                    document.getElementById('success-img').src = currentData.img;
                    document.getElementById('success-word').innerText = currentData.word.toUpperCase();

                    const modal = document.getElementById('success-modal');
                    modal.classList.replace('opacity-0', 'opacity-100');
                    modal.classList.remove('pointer-events-none');

                    audioPlayer.src = currentData.audio;

                    setTimeout(() => {
                        currentRound++;
                        loadQuestion();
                    }, 3500);
                }, 400);

            } else {
                wrongSound.pause();
                wrongSound.currentTime = 0;
                wrongSound.play();

                questionContainer.classList.add('question-error');

                btnElement.style.backgroundColor = "#fee2e2";
                btnElement.style.borderColor = "#f87171";
                btnElement.style.color = "#b91c1c";
let mistakeKey = `${currentData.word}_${letter}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                setTimeout(() => {
                    questionContainer.classList.remove('question-error');
                    currentRound++;
                    loadQuestion();
                }, 1500);
            }
        }

        function renderStars(starCount) {
            const container = document.getElementById('star-container');
            container.innerHTML = '';
            for (let i = 0; i < 3; i++) {
                const isEarned = i < starCount;
                const starColor = isEarned ? 'text-yellow-400 drop-shadow-md' : 'text-slate-200 drop-shadow-none';
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
            winSound.play();

            document.getElementById('win-overlay').classList.remove('hidden');
        }

       function saveAndExit(e) {
            e.preventDefault();
            
            if (!window.gameConfig.childId) {
                window.location.href = window.gameConfig.nextLevelUrl;
                return;
            }

            const nextBtn = document.getElementById('btn-next-level');
            if (nextBtn) {
                nextBtn.disabled = true;
                nextBtn.innerHTML = 'Menyimpan... <i class="fa-solid fa-spinner animate-spin ml-2"></i>';
            }

            fetch(window.gameConfig.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
                if (!response.ok) throw new Error('Network response error');
                return response.json();
            })
            .then(data => {
                window.location.href = window.gameConfig.nextLevelUrl;
            })
            .catch(error => {
                console.error('Error saving game progress:', error);
                window.location.href = window.gameConfig.nextLevelUrl;
            });
        }
    </script>
@endsection
