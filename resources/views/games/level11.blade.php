@extends('layouts.game')

@section('game-title', 'Tahap 11: Uji Pendengaran')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@700&display=swap" rel="stylesheet">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 11 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#0F172A] transition-all duration-300">
        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0 z-20">
                <div
                    class="flex items-center bg-slate-800 border-4 border-cyan-500 rounded-full px-4 py-2 shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                    <span class="text-cyan-400 font-black text-sm md:text-xl uppercase tracking-wider font-lexend">
                        <i class="fa-solid fa-bolt mr-2"></i> Robot Upgrade
                    </span>
                </div>

                <div class="flex items-center gap-3 md:gap-5 font-lexend">
                    <div
                        class="flex flex-col items-center bg-slate-800 border-4 border-emerald-500 rounded-2xl px-4 py-1 shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                        <span class="text-emerald-400 font-bold text-xs md:text-sm uppercase tracking-wider">Tenaga</span>
                        <span id="score-display" class="text-white font-black text-xl md:text-2xl">0</span>
                    </div>

                    <div
                        class="flex flex-col items-center bg-slate-800 border-4 border-slate-600 rounded-2xl px-4 py-1 shadow-lg">
                        <span class="text-slate-400 font-bold text-xs md:text-sm uppercase tracking-wider">Tahap</span>
                        <span id="round-display" class="text-white font-black text-xl md:text-2xl">0/10</span>
                    </div>

                    <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                        class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                        <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                    </button>
                </div>
            </div>

            <div id="game-board"
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-slate-700 w-full flex flex-col items-center pt-4">

                <div class="absolute inset-0 z-0 pointer-events-none bg-[#1E293B]">
                    <div class="w-full h-full opacity-30"
                        style="background-image: url('{{ asset('images/games/bg-robot.png') }}'); 
                    background-size: cover; 
                    background-position: center;">
                    </div>
                </div>

                <div
                    class="relative z-10 bg-slate-800/90 px-6 py-2 rounded-full border-2 border-slate-600 shadow-md backdrop-blur-sm flex items-center gap-4 mt-2 mb-2">
                    <button onclick="playTargetAudio()"
                        class="w-10 h-10 md:w-12 md:h-12 bg-amber-400 text-slate-900 rounded-full flex items-center justify-center text-lg md:text-xl border-4 border-amber-200 shadow-[0_4px_0_#D97706] active:translate-y-1 active:shadow-none transition-all pulse-btn">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>
                    <h2 class="text-lg md:text-2xl font-black text-cyan-300 tracking-wide font-lexend responsive-text">Cari
                        bunyi akhir untuk aktifkan enjin!</h2>
                </div>

                <div class="relative z-10 flex flex-1 flex-col items-center justify-center transition-transform duration-500 w-full"
                    id="robot-character">

                    <div class="w-2 h-6 md:h-8 bg-slate-500 -mb-1 relative flex justify-center">
                        <div id="robot-antenna-light"
                            class="absolute -top-3 w-4 h-4 rounded-full bg-red-500 shadow-[0_0_10px_#EF4444] transition-colors duration-500">
                        </div>
                    </div>

                    <div
                        class="w-24 h-20 md:w-32 md:h-28 bg-slate-400 border-4 border-slate-600 rounded-2xl flex flex-col items-center justify-center relative z-20 shadow-lg">
                        <div class="absolute -left-3 w-3 h-8 bg-slate-500 rounded-l-md"></div>
                        <div class="absolute -right-3 w-3 h-8 bg-slate-500 rounded-r-md"></div>
                        <div class="flex gap-4">
                            <div
                                class="robot-eye w-6 h-6 md:w-8 md:h-8 bg-slate-800 rounded-full flex items-center justify-center overflow-hidden transition-all">
                                <div class="eye-pupil w-2 h-2 bg-white rounded-full transition-all"></div>
                            </div>
                            <div
                                class="robot-eye w-6 h-6 md:w-8 md:h-8 bg-slate-800 rounded-full flex items-center justify-center overflow-hidden transition-all">
                                <div class="eye-pupil w-2 h-2 bg-white rounded-full transition-all"></div>
                            </div>
                        </div>
                        <div id="robot-mouth" class="w-8 h-2 bg-slate-800 mt-3 rounded-full transition-all duration-300">
                        </div>
                    </div>

                    <div class="w-8 h-4 bg-slate-600 flex justify-center gap-1 z-10"></div>

                    <div
                        class="w-[40vw] h-[45vh] max-w-[320px] max-h-[360px]
            bg-slate-400 border-4 border-slate-600 rounded-[2rem]
            flex flex-col items-center justify-start p-3 shadow-xl">
                        <div
                            class="w-full h-[60%] bg-slate-800 rounded-xl border-4 border-slate-700 flex items-center justify-center overflow-hidden">
                            <img id="question-img" src=""
                                class="w-full h-full object-contain filter grayscale transition-all duration-500 opacity-60">
                        </div>

                        <div id="robot-core"
                            class="mt-4 w-12 h-12 md:w-16 md:h-16 bg-slate-700 rounded-full border-4 border-slate-800 flex items-center justify-center transition-all duration-500 shadow-inner">
                            <i id="core-icon"
                                class="fa-solid fa-bolt text-slate-500 text-xl md:text-3xl transition-all duration-500"></i>
                        </div>

                    </div>
                </div>

                <div
                    class="flex items-center gap-2 md:gap-4 z-20 mt-4 mb-4 bg-slate-900/60 p-4 rounded-3xl border-2 border-slate-700 shadow-lg responsive-equation">
                    <div
                        class="bg-cyan-900 border-4 border-cyan-500 rounded-xl w-14 h-14 md:w-20 md:h-20 flex items-center justify-center shadow-md">
                        <span id="syllable-1"
                            class="text-cyan-300 font-black text-2xl md:text-4xl font-space-mono">BU</span>
                    </div>

                    <span class="text-slate-400 font-black text-2xl md:text-4xl">+</span>

                    <div
                        class="bg-cyan-900 border-4 border-cyan-500 rounded-xl w-14 h-14 md:w-20 md:h-20 flex items-center justify-center shadow-md">
                        <span id="syllable-2"
                            class="text-cyan-300 font-black text-2xl md:text-4xl font-space-mono">KI</span>
                    </div>

                    <span class="text-slate-400 font-black text-2xl md:text-4xl">+</span>

                    <div id="answer-slot"
                        class="bg-slate-800 border-4 border-dashed border-amber-500 rounded-xl w-14 h-14 md:w-20 md:h-20 flex items-center justify-center shadow-inner transition-all duration-300">
                        <span id="slot-text"
                            class="text-amber-500/50 font-black text-2xl md:text-4xl font-space-mono">_</span>
                    </div>
                </div>

                <div
                    class="w-full bg-slate-800 p-4 md:p-6 
            sticky bottom-0 z-30 rounded-t-[3rem] border-t-4 border-cyan-800 shadow-[0_-10px_30px_rgba(0,0,0,0.5)] z-20 flex justify-center items-center shrink-0">
                    <div class="flex flex-wrap justify-center gap-3 md:gap-6 w-full max-w-4xl" id="options-container">
                    </div>
                </div>

            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-slate-950/90 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8">
            <div
                class="bg-slate-900 p-6 md:p-10 rounded-[2.5rem] shadow-[0_0_40px_rgba(6,182,212,0.4)] text-center max-w-lg w-full flex flex-col border-[6px] border-cyan-500 font-lexend">
                <h1 class="text-3xl md:text-4xl font-black text-cyan-400 mb-6 tracking-wider">Lengkapkan Perkataan</h1>
                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10">
                    <div class="flex items-center gap-4 bg-slate-800 p-4 rounded-xl border-2 border-slate-700 shadow-sm">
                        <div
                            class="w-12 h-12 bg-slate-700 text-amber-400 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-puzzle-piece"></i>
                        </div>
                        <p class="text-slate-300 font-bold text-lg leading-tight">Robot hilang huruf akhir (konsonan).</p>
                    </div>
                    <div class="flex items-center gap-4 bg-slate-800 p-4 rounded-xl border-2 border-slate-700 shadow-sm">
                        <div
                            class="w-12 h-12 bg-slate-700 text-cyan-400 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <p class="text-slate-300 font-bold text-lg leading-tight">Pilih huruf yang betul untuk lengkapkan
                            perkataan dan <b>UPGRADE</b> enjin robot!</p>
                    </div>
                </div>
                <button onclick="startGame()"
                    class="btn-3d bg-cyan-600 border-cyan-800 text-white w-full text-2xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3 hover:bg-cyan-500">
                    <i class="fa-solid fa-play"></i> Mula
                </button>
            </div>
        </div>

        <div id="win-overlay"
            class="hidden absolute inset-0 bg-black/80 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8 overflow-y-auto">

            <div
                class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full animate-bounce-in border-[6px] border-indigo-200 relative my-auto">

                <div
                    class="absolute -top-12 left-1/2 transform -translate-x-1/2 text-7xl drop-shadow-lg text-yellow-400 bg-white rounded-full p-2 border-4 border-yellow-200 flex items-center justify-center w-32 h-32">
                    <i class="fa-solid fa-trophy"></i>
                </div>

                <h1 class="text-4xl md:text-5xl font-black text-indigo-600 mb-2 mt-10">Tahap Selesai!</h1>
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
                        Main Semula <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <audio id="audio-player" preload="auto"></audio>
    <audio id="sfx-wrong" src="https://assets.mixkit.com/sfx/preview/mixkit-system-fault-36.mp3"></audio>
    <audio id="sfx-powerup" src="https://assets.mixkit.com/sfx/preview/mixkit-technology-power-up-2804.mp3"></audio>

    <style>
        .font-lexend {
            font-family: 'Lexend', sans-serif;
        }

        .font-space-mono {
            font-family: 'Space Mono', monospace;
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

        /* BUTANG PILIHAN HURUF */
        .letter-btn {
            background-color: #334155;
            border-color: #0F172A;
            border-top-color: #475569;
            border-left-color: #475569;
            color: #38BDF8;
            width: 60px;
            height: 60px;
            font-size: 2rem;
            font-weight: 900;
            border-radius: 1rem;
            font-family: 'Space Mono', monospace;
            flex-shrink: 0;
        }

        @media (min-width: 768px) {
            .letter-btn {
                width: 80px;
                height: 80px;
                font-size: 2.5rem;
                border-radius: 1.2rem;
            }
        }

        .letter-btn:not(:disabled):hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 10px 20px rgba(6, 182, 212, 0.3);
            background-color: #475569;
            color: #BAE6FD;
        }

        .letter-btn.selected {
            transform: scale(0);
            opacity: 0;
            pointer-events: none;
        }

        /* STATUS UPGRADE ROBOT */
        .upgraded #robot-antenna-light {
            background-color: #10B981;
            box-shadow: 0 0 20px #34D399;
        }

        .upgraded .robot-eye {
            background-color: #047857;
        }

        .upgraded .eye-pupil {
            height: 4px;
            border-radius: 10px;
            transform: scaleX(2) translateY(-2px);
            background-color: #A7F3D0;
            box-shadow: 0 0 10px #A7F3D0;
        }

        .upgraded #robot-mouth {
            width: 24px;
            height: 12px;
            border-radius: 0 0 15px 15px;
            background-color: #047857;
            margin-top: 2px;
        }

        .upgraded #robot-core {
            background-color: #A7F3D0;
            border-color: #10B981;
            box-shadow: 0 0 30px #34D399;
        }

        .upgraded #core-icon {
            color: #047857;
            transform: scale(1.3);
        }

        .upgraded #question-img {
            filter: grayscale(0%);
            opacity: 1;
            transform: scale(1.1);
        }

        .robot-error {
            animation: cute-shake 0.4s ease-in-out;
        }

        .robot-error #robot-mouth {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background-color: #7F1D1D;
        }

        .robot-error #robot-antenna-light {
            transform: scale(1.5);
        }

        /* SLOT JAWAPAN BILA BETUL */
        .slot-success {
            background-color: #10B981 !important;
            border-color: #059669 !important;
            border-style: solid !important;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.6);
        }

        .slot-success #slot-text {
            color: white !important;
        }

        #robot-character {
            transform: scale(1);
            transition: transform 0.3s ease;
        }

        /* tablet */
        @media (min-width: 768px) {
            #robot-character {
                transform: scale(1.2);
            }
        }

        /* laptop */
        @media (min-width: 1024px) {
            #robot-character {
                transform: scale(1.1);
            }
        }

        /* monitor besar */
        @media (min-width: 1440px) {
            #robot-character {
                transform: scale(1.6);
            }
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

        @keyframes cute-shake {

            0%,
            100% {
                transform: translateX(0) rotate(0);
            }

            25% {
                transform: translateX(-8px) rotate(-3deg);
            }

            75% {
                transform: translateX(8px) rotate(3deg);
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

        /* Perbaikan Resposive Box ketika Fullscreen */
        .is-fullscreen .responsive-text {
            font-size: 2rem !important;
        }

        .is-fullscreen .responsive-equation {
            transform: scale(0.9);
        }

        .is-fullscreen #robot-character {
            transform: scale(0.9);
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
        const powerupSound = document.getElementById('sfx-powerup');

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

        // --- DATA SOALAN KVK (Bunyi Akhir) ---
        const gameData = [{
                word: 'Rumah',
                syl1: 'RU',
                text2: 'MA',
                ans: 'H',
                distractors: ['M', 'L', 'S', 'K'],
                audio: '{{ asset('audio/Rumah.mp3') }}',
                img: '{{ asset('images/games/rumah.png') }}'
            },
            {
                word: 'Kapal',
                syl1: 'KA',
                text2: 'PA',
                ans: 'L',
                distractors: ['S', 'K', 'R', 'N'],
                audio: '{{ asset('audio/Kapal.mp3') }}',
                img: '{{ asset('images/games/kapal.png') }}'
            },
            {
                word: 'Katak',
                syl1: 'KA',
                text2: 'TA',
                ans: 'K',
                distractors: ['N', 'R', 'P', 'S'],
                audio: '{{ asset('audio/Katak.mp3') }}',
                img: '{{ asset('images/games/katak.png') }}'
            },
            {
                word: 'Tikus',
                syl1: 'TI',
                text2: 'KU',
                ans: 'S',
                distractors: ['M', 'P', 'L', 'K'],
                audio: '{{ asset('audio/Tikus.mp3') }}',
                img: '{{ asset('images/games/tikus.png') }}'
            },
            {
                word: 'Pokok',
                syl1: 'PO',
                text2: 'KO',
                ans: 'K',
                distractors: ['N', 'D', 'T', 'M'],
                audio: '{{ asset('audio/Pokok.mp3') }}',
                img: '{{ asset('images/games/pokok.png') }}'
            },
            {
                word: 'Bulan',
                syl1: 'BU',
                text2: 'LA',
                ans: 'N',
                distractors: ['L', 'M', 'R', 'S'],
                audio: '{{ asset('audio/Bulan.mp3') }}',
                img: '{{ asset('images/games/bulan.png') }}'
            },
            {
                word: 'Bakul',
                syl1: 'BA',
                text2: 'KU',
                ans: 'L',
                distractors: ['R', 'S', 'M', 'K'],
                audio: '{{ asset('audio/Bakul.mp3') }}',
                img: '{{ asset('images/games/bakul.png') }}'
            },
            {
                word: 'Botol',
                syl1: 'BO',
                text2: 'TO',
                ans: 'L',
                distractors: ['K', 'N', 'P', 'S'],
                audio: '{{ asset('audio/Botol.mp3') }}',
                img: '{{ asset('images/games/botol.png') }}'
            },
            {
                word: 'Kipas',
                syl1: 'KI',
                text2: 'PA',
                ans: 'S',
                distractors: ['R', 'T', 'M', 'N'],
                audio: '{{ asset('audio/Kipas.mp3') }}',
                img: '{{ asset('images/games/kipas.png') }}'
            },
            {
                word: 'Siput',
                syl1: 'SI',
                text2: 'PU',
                ans: 'T',
                distractors: ['P', 'M', 'L', 'K'],
                audio: '{{ asset('audio/Siput.mp3') }}',
                img: '{{ asset('images/games/siput.png') }}'
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
        const robot = document.getElementById('robot-character');
        const questionImg = document.getElementById('question-img');

        const syl1Elem = document.getElementById('syllable-1');
        const syl2Elem = document.getElementById('syllable-2');
        const answerSlot = document.getElementById('answer-slot');
        const slotText = document.getElementById('slot-text');

        document.addEventListener('DOMContentLoaded', () => {
            gameData.forEach(data => {
                if (data.img.includes('{{ asset')) data.img =
                    `https://placehold.co/300x300/334155/FFF?text=${data.word}`;
                const img = new Image();
                img.src = data.img;
            });
        });

        function playPhonicsTTS(text, rate = 0.8) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ms-MY';
            utterance.rate = rate;
            window.speechSynthesis.speak(utterance);
        }

        function playTargetAudio() {
            if (isWaiting) return;

            audioPlayer.src = currentData.audio;
            audioPlayer.currentTime = 0;

            audioPlayer.play().catch(err => {
                console.log("Audio tak boleh play:", err);
            });
        }

        function startGame() {
            document.getElementById('start-overlay').style.display = 'none';

            shuffledData = [...gameData].sort(() => Math.random() - 0.5);

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

            questionImg.src = currentData.img;
            syl1Elem.innerText = currentData.syl1;
            syl2Elem.innerText = currentData.text2;
            updateUI();

            robot.classList.remove('upgraded', 'robot-error');

            answerSlot.className =
                "bg-slate-800 border-4 border-dashed border-amber-500 rounded-xl w-14 h-14 md:w-20 md:h-20 flex items-center justify-center shadow-inner transition-all duration-300";
            slotText.innerText = "_";
            slotText.className = "text-amber-500/50 font-black text-2xl md:text-4xl font-space-mono";

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

            if (letter === currentData.ans) {
                score += 1;
                updateUI();

                correctSound.pause();
                correctSound.currentTime = 0;
                correctSound.play();
                slotText.innerText = letter;
                slotText.className = "text-white font-black text-3xl md:text-5xl font-space-mono animate-bounce-in";
                answerSlot.classList.replace('bg-slate-800', 'slot-success');
                answerSlot.classList.remove('border-dashed');

                robot.classList.add('upgraded');

                // Read full word
                setTimeout(() => {
                    audioPlayer.src = currentData.audio;
                    audioPlayer.currentTime = 0;
                    audioPlayer.play().catch(e => console.log(e));
                }, 500);

                setTimeout(() => {
                    currentRound++;
                    loadQuestion();
                }, 2000);

            } else {
                wrongSound.pause();
                wrongSound.currentTime = 0;
                wrongSound.play();

                robot.classList.add('robot-error');

                btnElement.style.backgroundColor = "#7F1D1D";
                btnElement.style.borderColor = "#EF4444";
                btnElement.style.color = "#FECACA";
                let mistakeKey = `${currentData.word}_${letter}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                setTimeout(() => {
                    robot.classList.remove('robot-error');

                    btnElement.style.backgroundColor = "";
                    btnElement.style.borderColor = "";
                    btnElement.style.color = "";

                    allBtns.forEach(btn => btn.disabled = false);
                    isWaiting = false;
                }, 1000);
            }
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

        function calculateStars() {
            return score >= 8 ? 3 : (score >= 5 ? 2 : 1);
        }

        function endGame() {
            document.getElementById('final-score').innerText = score;
            const earnedStars = calculateStars();
            renderStars(earnedStars);

            const winOverlay = document.getElementById('win-overlay');
            winOverlay.classList.remove('hidden');
            winSound.play();
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
                    btn.innerHTML = 'Tahap Seterusnya <i class="fa-solid fa-forward-step"></i>';
                }
            }).catch(e => {
                alert("Ralat rangkaian.");
                btn.disabled = false;
                btn.innerHTML = 'Tahap Seterusnya <i class="fa-solid fa-forward-step"></i>';
            });
        }
    </script>
@endsection
