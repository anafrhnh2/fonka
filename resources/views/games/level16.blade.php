@extends('layouts.game')

@section('game-title', 'Tahap 15: Escape Planet')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@700&display=swap" rel="stylesheet">

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
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#020617] transition-all duration-300">

        <div
            class="absolute inset-0 z-0 pointer-events-none opacity-50 bg-[url('{{ asset('images/games/bg-space.jpg') }}')] bg-cover bg-center">
        </div>
        <div
            class="absolute bottom-0 w-full h-1/2 bg-gradient-to-t from-slate-900/90 to-transparent z-0 pointer-events-none">
        </div>

        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0 z-20">
                <div
                    class="flex items-center bg-slate-800 border-4 border-cyan-500 rounded-full px-4 py-2 shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                    <span class="text-cyan-400 font-black text-sm md:text-xl uppercase tracking-wider font-space-mono">
                        <i class="fa-solid fa-rocket mr-2"></i> Escape Planet
                    </span>
                </div>

                <div class="flex items-center gap-3 md:gap-5 font-lexend">
                    <div
                        class="flex flex-col items-center bg-slate-800 border-4 border-emerald-500 rounded-2xl px-4 py-1 shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                        <span class="text-emerald-400 font-bold text-xs md:text-sm uppercase tracking-wider">Markah</span>
                        <span id="score-display" class="text-white font-black text-xl md:text-2xl">0</span>
                    </div>
                    <div
                        class="flex flex-col items-center bg-slate-800 border-4 border-slate-600 rounded-2xl px-4 py-1 shadow-lg">
                        <span class="text-slate-400 font-bold text-xs md:text-sm uppercase tracking-wider">Pintu</span>
                        <span id="round-display" class="text-white font-black text-xl md:text-2xl">0/10</span>
                    </div>
                     <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                        class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                        <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                    </button>
                </div>
            </div>

            <div id="game-board"
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-slate-700 w-full flex flex-col justify-between items-center bg-transparent pt-4">

                <div
                    class="text-center mt-2 z-10 bg-slate-800/90 px-8 py-3 rounded-full border-2 border-slate-600 shadow-[0_0_15px_rgba(0,0,0,0.5)] backdrop-blur-sm flex items-center gap-4">
                    <h2 class="text-lg md:text-2xl font-black text-cyan-300 tracking-wide font-lexend responsive-text">Susun
                        ayat untuk buka portal!</h2>
                    <button onclick="playSentenceAudio()"
                        class="w-12 h-12 bg-amber-400 text-slate-900 rounded-full flex items-center justify-center text-xl border-4 border-amber-200 shadow-[0_4px_0_#D97706] active:translate-y-1 active:shadow-none transition-all">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>
                </div>

                <div id="robot-portal-wrapper"
                    class="relative w-full max-w-4xl h-32 md:h-48 mt-4 flex items-center justify-between px-8 z-10 overflow-visible transition-all duration-300">

                    <img id="robot" src="{{ asset('images/games/robot-idle.png') }}"
                        class="w-24 h-24 md:w-36 md:h-36 object-contain filter drop-shadow-[0_0_15px_rgba(56,189,248,0.6)] transition-all duration-[1500ms] ease-in-out z-20 hover-animation">

                    <div id="portal-container"
                        class="relative w-28 h-28 md:w-44 md:h-44 flex items-center justify-center transition-all duration-500 drop-shadow-[0_0_30px_rgba(0,0,0,0.8)]">
                        <img id="planet-img" src="{{ asset('images/games/bulan.png') }}"
                            class="w-full h-full object-contain absolute inset-0 z-10 transition-opacity duration-300">
                        <div id="portal-glow"
                            class="absolute inset-0 bg-emerald-400 rounded-full opacity-0 blur-xl transition-opacity duration-500 z-0">
                        </div>
                        <i id="portal-lock"
                            class="fa-solid fa-lock text-3xl md:text-5xl text-slate-100 z-20 transition-all duration-500 bg-slate-800/50 p-4 rounded-full backdrop-blur-sm border-2 border-slate-600"></i>
                    </div>

                </div>

                <div class="w-full flex justify-center items-center gap-2 md:gap-4 px-2 md:px-4 z-10 my-4 responsive-slots">
                    <div id="slot-0" onclick="undoWord(0)" title="Klik untuk buang"
                        class="slot-box flex-1 h-16 md:h-20 bg-slate-800/80 border-2 border-dashed border-cyan-700 rounded-xl flex items-center justify-center shadow-inner transition-all relative cursor-pointer hover:border-red-400 hover:bg-red-900/50">
                        <span class="slot-text text-white font-black text-xl md:text-3xl font-lexend tracking-wide"></span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-cyan-700 text-lg md:text-2xl animate-pulse"></i>

                    <div id="slot-1" onclick="undoWord(1)" title="Klik untuk buang"
                        class="slot-box flex-1 h-16 md:h-20 bg-slate-800/80 border-2 border-dashed border-cyan-700 rounded-xl flex items-center justify-center shadow-inner transition-all relative cursor-pointer hover:border-red-400 hover:bg-red-900/50">
                        <span class="slot-text text-white font-black text-xl md:text-3xl font-lexend tracking-wide"></span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-cyan-700 text-lg md:text-2xl animate-pulse"
                        style="animation-delay: 0.2s"></i>

                    <div id="slot-2" onclick="undoWord(2)" title="Klik untuk buang"
                        class="slot-box flex-1 h-16 md:h-20 bg-slate-800/80 border-2 border-dashed border-cyan-700 rounded-xl flex items-center justify-center shadow-inner transition-all relative cursor-pointer hover:border-red-400 hover:bg-red-900/50">
                        <span class="slot-text text-white font-black text-xl md:text-3xl font-lexend tracking-wide"></span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-cyan-700 text-lg md:text-2xl animate-pulse"
                        style="animation-delay: 0.4s"></i>

                    <div id="slot-3" onclick="undoWord(3)" title="Klik untuk buang"
                        class="slot-box flex-1 h-16 md:h-20 bg-slate-800/80 border-2 border-dashed border-cyan-700 rounded-xl flex items-center justify-center shadow-inner transition-all relative cursor-pointer hover:border-red-400 hover:bg-red-900/50">
                        <span class="slot-text text-white font-black text-xl md:text-3xl font-lexend tracking-wide"></span>
                    </div>
                </div>

                <div
                    class="w-full bg-slate-800/90 p-6 md:p-8 rounded-t-[3rem] border-t-4 border-cyan-800 shadow-[0_-10px_30px_rgba(0,0,0,0.5)] z-20 flex flex-col items-center backdrop-blur-md min-h-[160px]">
                    <div class="flex flex-wrap justify-center gap-3 md:gap-5 w-full max-w-4xl" id="options-container">
                    </div>
                </div>

            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-slate-950/90 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8">
            <div
                class="bg-slate-900 p-6 md:p-10 rounded-[2.5rem] shadow-[0_0_40px_rgba(6,182,212,0.4)] text-center max-w-lg w-full flex flex-col border-[6px] border-cyan-500 font-lexend">
                <div class="text-6xl mb-4 text-cyan-400"><i class="fa-solid fa-rocket"></i></div>
                <h1 class="text-3xl md:text-4xl font-black text-cyan-400 mb-6 font-space-mono tracking-wider">Misi Susun
                    Perkataan</h1>
                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10">
                    <div class="flex items-center gap-4 bg-slate-800 p-4 rounded-xl border-2 border-slate-700 shadow-sm">
                        <div
                            class="w-12 h-12 bg-slate-700 text-amber-400 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-volume-high"></i>
                        </div>
                        <p class="text-slate-300 font-bold text-lg leading-tight">Dengar ayat yang disebut dengan teliti.
                        </p>
                    </div>
                    <div class="flex items-center gap-4 bg-slate-800 p-4 rounded-xl border-2 border-slate-700 shadow-sm">
                        <div
                            class="w-12 h-12 bg-slate-700 text-cyan-400 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                        <p class="text-slate-300 font-bold text-lg leading-tight">Susun perkataan dari <b>kiri ke kanan</b>
                            untuk buka portal!</p>
                    </div>

                </div>
                <button onclick="startGame()"
                    class="btn-3d bg-cyan-600 border-cyan-800 text-white w-full text-2xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3 hover:bg-cyan-500 shadow-[0_0_20px_rgba(6,182,212,0.4)]">
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
    <audio id="sfx-click" src="https://assets.mixkit.com/sfx/preview/mixkit-modern-technology-select-3124.mp3"></audio>
    <audio id="sfx-wrong" src="https://assets.mixkit.com/sfx/preview/mixkit-system-fault-36.mp3"></audio>
    <audio id="sfx-portal" src="https://assets.mixkit.com/sfx/preview/mixkit-ethereal-fairy-win-sound-2019.mp3"></audio>
    <audio id="sfx-destroy" src="https://assets.mixkit.com/sfx/preview/mixkit-explosion-with-rocks-1702.mp3"></audio>

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

        /* BUTANG PERKATAAN */
        .word-btn {
            background-color: #1E293B;
            border-color: #0F172A;
            border-top-color: #334155;
            border-left-color: #334155;
            color: #38BDF8;
            padding: 0 20px;
            height: 60px;
            font-size: 1.5rem;
            font-weight: 800;
            border-radius: 1rem;
            text-transform: lowercase;
        }

        @media (min-width: 768px) {
            .word-btn {
                padding: 0 30px;
                height: 80px;
                font-size: 2.2rem;
            }
        }

        .word-btn:not(:disabled):hover {
            transform: scale(1.05) translateY(-4px);
            box-shadow: 0 8px 15px rgba(6, 182, 212, 0.3);
            background-color: #334155;
            color: #BAE6FD;
            border-color: #1E293B;
        }

        .word-btn.selected {
            transform: scale(0.8);
            opacity: 0.3;
            pointer-events: none;
            filter: grayscale(100%);
        }

        /* KOTAK SLOT AYAT */
        .slot-box {
            transition: all 0.2s;
        }

        .slot-box.filled {
            background-color: #164E63;
            border-color: #0EA5E9;
            border-style: solid;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.3);
        }

        .slot-box.error {
            background-color: #7F1D1D;
            border-color: #EF4444;
            border-style: solid;
        }

        .slot-box.success {
            background-color: #064E3B;
            border-color: #10B981;
            border-style: solid;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.5);
        }

        /* KESAN PORTAL & ROBOT */
        .hover-animation {
            animation: hover-float 3s ease-in-out infinite;
        }

        .portal-open {
            border-color: #10B981 !important;
            box-shadow: 0 0 50px rgba(16, 185, 129, 0.8) !important;
        }

        .portal-open #portal-glow {
            opacity: 1 !important;
            animation: pulse-glow 1s infinite alternate;
        }

        .portal-open #portal-lock {
            color: #A7F3D0 !important;
            transform: scale(1.2);
            border-color: transparent;
        }

        .robot-escape {
            transform: translateX(350px) scale(0.5) rotate(15deg) !important;
            opacity: 0;
        }

        @media (min-width: 768px) {
            .robot-escape {
                transform: translateX(600px) scale(0.5) rotate(15deg) !important;
            }
        }

        .planet-destroyed {
            animation: pop-sparkle 0.5s forwards;
            filter: grayscale(100%) brightness(0.5);
        }

        /* CSS UNTUK DUIT SYILING JATUH */
        .falling-coin {
            position: absolute;
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

        /* RESPONSIF FULLSCREEN */
        .is-fullscreen .responsive-text {
            font-size: 2.5rem !important;
        }

        .is-fullscreen .word-btn {
            height: 90px !important;
            padding: 0 40px !important;
            font-size: 2.8rem !important;
            border-radius: 1.5rem;
        }

        /* 1. Kotak jawapan (slots) dikecilkan sedikit di fullscreen */
        .is-fullscreen .responsive-slots .slot-box {
            height: 75px !important;
            border-width: 2px !important;
        }

        .is-fullscreen .responsive-slots .slot-text {
            font-size: 2.2rem !important;
        }

        /* 2. Robot & Planet dibesarkan di fullscreen */
        .is-fullscreen #robot-portal-wrapper {
            height: 240px !important;
            overflow: visible !important;
            margin-top: 2rem !important;
            margin-bottom: 1rem !important;
        }

        .is-fullscreen #robot {
            width: 220px !important;
            height: 220px !important;
        }

        .is-fullscreen #portal-container {
            width: 240px !important;
            height: 240px !important;
        }

        .is-fullscreen #portal-lock {
            font-size: 4.5rem !important;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-8px);
            }

            75% {
                transform: translateX(8px);
            }
        }

        .shake {
            animation: shake 0.4s ease-in-out;
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

        @keyframes hover-float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes pulse-glow {
            0% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes pop-sparkle {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            100% {
                transform: scale(2.5);
                opacity: 0;
            }
        }

        .sparkle-effect {
            position: absolute;
            font-size: 4rem;
            pointer-events: none;
            animation: pop-sparkle 0.8s ease-out forwards;
            z-index: 100;
            color: #FDE047;
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

        const clickSound = document.getElementById('sfx-click');
        const correctSound = new Audio('{{ asset('audio/bagus.mp3') }}');
        const wrongSound = new Audio('{{ asset('audio/cubalagi.mp3') }}');
        const winSound = new Audio('{{ asset('audio/tahniah.mp3') }}');
        const portalSound = document.getElementById('sfx-portal');
        const destroySound = document.getElementById('sfx-destroy');

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
        // --- DATA AYAT (4 Perkataan) ---
        const gameData = [{
                words: ['Ali', 'baca', 'buku', 'biru'],
                audio: '{{ asset('audio/ali_baca_buku_biru.mp3') }}'
            },
            {
                words: ['Ibu', 'beli', 'baju', 'baru'],
                audio: '{{ asset('audio/ibu_beli_baju_baru.mp3') }}'
            },
            {
                words: ['Sani', 'main', 'bola', 'sepak'],
                audio: '{{ asset('audio/sani_main_bola_sepak.mp3') }}'
            },
            {
                words: ['Bapa', 'cuci', 'lori', 'kayu'],
                audio: '{{ asset('audio/bapa_cuci_lori_kayu.mp3') }}'
            },
            {
                words: ['Kucing', 'kejar', 'tikus', 'itu'],
                audio: '{{ asset('audio/kucing_kejar_tikus_itu.mp3') }}'
            },
            {
                words: ['Raju', 'suka', 'susu', 'lembu'],
                audio: '{{ asset('audio/raju_suka_susu_lembu.mp3') }}'
            },
            {
                words: ['Nani', 'sapu', 'roti', 'kaya'],
                audio: '{{ asset('audio/nani_sapu_roti_kaya.mp3') }}'
            },
            {
                words: ['Kami', 'tanam', 'pokok', 'bunga'],
                audio: '{{ asset('audio/kami_tanam_pokok_bunga.mp3') }}'
            },
            {
                words: ['Siti', 'beli', 'sate', 'ayam'],
                audio: '{{ asset('audio/siti_beli_sate_ayam.mp3') }}'
            },
            {
                words: ['Adik', 'minum', 'jus', 'oren'],
                audio: '{{ asset('audio/adik_minum_jus_oren.mp3') }}'
            }
        ];

        const MAX_ROUNDS = 10;
        let shuffledData = [];
        let currentRound = 0;
        let score = 0;
        let currentData = {};

        let selectedWords = [];
        let isWaiting = false;
        let sessionMistakes = {}; 

        const optionsContainer = document.getElementById('options-container');
        const robot = document.getElementById('robot');
        const portal = document.getElementById('portal-container');
        const portalLock = document.getElementById('portal-lock');
        const planetImg = document.getElementById('planet-img');

        const slots = [
            document.getElementById('slot-0'),
            document.getElementById('slot-1'),
            document.getElementById('slot-2'),
            document.getElementById('slot-3')
        ];

        const imageAssets = {
            robotIdle: '{{ asset('images/games/robot-idle.png') }}',
            robotFly: '{{ asset('images/games/robot-fly.png') }}',
            planetNormal: '{{ asset('images/games/bulan.png') }}',
            planetDestroyed: '{{ asset('images/games/bulan.png') }}'
        };

        document.addEventListener('DOMContentLoaded', () => {
            Object.values(imageAssets).forEach(src => {
                const img = new Image();
                img.src = src;
            });
        });


        function playPhonicsTTS(text, rate = 0.8) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ms-MY';
            utterance.rate = rate;
            window.speechSynthesis.speak(utterance);
        }

        function playSentenceAudio() {
            if (isWaiting) return;
            audioPlayer.src = currentData.audio;
            audioPlayer.play().catch(e => {
                playPhonicsTTS(currentData.words.join(' '), 0.85);
            });
        }

        // --- KAWALAN PERMAINAN ---
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
            document.getElementById('score-display').innerText = score;
            document.getElementById('round-display').innerText = `${currentRound + 1}/${MAX_ROUNDS}`;
        }

        function loadQuestion() {
            if (currentRound >= MAX_ROUNDS) {
                endGame();
                return;
            }

            isWaiting = false;
            selectedWords = [];
            currentData = shuffledData[currentRound];
            updateUI();

            // Reset Robot & Planet Image
            robot.src = imageAssets.robotIdle;
            robot.className =
                "w-24 h-24 md:w-36 md:h-36 object-contain filter drop-shadow-[0_0_15px_rgba(56,189,248,0.6)] transition-all duration-1000 ease-in-out z-20 hover-animation";

            planetImg.src = imageAssets.planetNormal;
            planetImg.classList.remove('planet-destroyed');

            portal.classList.remove('portal-open');
            portalLock.className =
                "fa-solid fa-lock text-3xl md:text-5xl text-slate-100 z-20 transition-all duration-500 bg-slate-800/50 p-4 rounded-full backdrop-blur-sm border-2 border-slate-600";

            // Reset Slots
            slots.forEach(slot => {
                slot.classList.remove('filled', 'error', 'success');
                slot.querySelector('.slot-text').innerText = "";
            });

            generateOptions();

            // Auto sebut ayat
            setTimeout(() => playSentenceAudio(), 600);
        }

        function generateOptions() {
            optionsContainer.innerHTML = '';

            let options = [...currentData.words].sort(() => Math.random() - 0.5);

            options.forEach((word, index) => {
                const btn = document.createElement('button');
                btn.className = 'btn-3d word-btn font-lexend transition-all';
                btn.innerText = word;
                btn.id = `btn-${index}`;

                btn.onclick = () => selectWord(word, btn);
                optionsContainer.appendChild(btn);
            });
        }

        // FUNGSI ANIMASI DUIT SYILING JATUH
        function spawnCoins() {
            const container = document.getElementById('game-fullscreen-container');
            for (let i = 0; i < 15; i++) {
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

        // FUNGSI UNTUK UNDO/PADAM PERKATAAN DARI SLOT
        function undoWord(index) {
            // Jangan benarkan undo jika sistem sedang menyemak jawapan atau jika slot kosong
            if (isWaiting || index >= selectedWords.length) return;

            // Mainkan bunyi klik
            if (clickSound) {
                clickSound.currentTime = 0;
                clickSound.play().catch(e => {});
            }

            // Dapatkan ID butang asal dari array
            const wordData = selectedWords[index];
            const btnElement = document.getElementById(wordData.btnId);

            // Aktifkan kembali butang di bahagian pilihan
            if (btnElement) {
                btnElement.classList.remove('selected');
                btnElement.disabled = false;
            }

            // Buang perkataan tersebut dari array berdasarkan indeksnya
            selectedWords.splice(index, 1);

            // Kemas kini visual slot
            updateSlotVisuals();
        }

        // LOGIK MENGISI SLOT (Satu Percubaan Sahaja)
        function selectWord(word, btnElement) {
            if (isWaiting || selectedWords.length >= 4) return;

            clickSound.currentTime = 0;
            clickSound.play().catch(e => {});

            selectedWords.push({
                text: word,
                btnId: btnElement.id
            });
            btnElement.classList.add('selected');

            updateSlotVisuals();

            // Semak bila penuh
            if (selectedWords.length === 4) {
                checkAnswer();
            }
        }

        function updateSlotVisuals() {
            slots.forEach((slot, index) => {
                const textSpan = slot.querySelector('.slot-text');
                if (index < selectedWords.length) {
                    textSpan.innerText = selectedWords[index].text.toLowerCase();
                    slot.classList.add('filled');
                } else {
                    textSpan.innerText = "";
                    slot.classList.remove('filled');
                }
            });
        }

        function checkAnswer() {
            isWaiting = true;

            // Kunci sisa butang
            document.querySelectorAll('.word-btn').forEach(btn => btn.disabled = true);

            const userSentence = selectedWords.map(a => a.text.toLowerCase()).join(' ');
            const correctSentence = currentData.words.join(' ').toLowerCase();

            if (userSentence === correctSentence) {
                // JAWAPAN BETUL
                score += 4;
                updateUI();
                correctSound.pause();
                correctSound.currentTime = 0;
                correctSound.play();
                portalSound.currentTime = 0;
                portalSound.play().catch(e => {});

                slots.forEach(slot => {
                    slot.classList.replace('filled', 'success');
                });

                portal.classList.add('portal-open');
                portalLock.className =
                    "fa-solid fa-unlock text-3xl md:text-5xl text-emerald-100 z-20 transition-all duration-500 drop-shadow-[0_0_10px_rgba(255,255,255,0.8)] bg-transparent border-transparent";

                robot.src = imageAssets.robotFly;

                spawnCoins();
                playSentenceAudio();

                // Animasi Robot Lari ke dalam portal
                setTimeout(() => {
                    robot.classList.remove('hover-animation');
                    robot.classList.add('robot-escape');

                    setTimeout(() => {
                        currentRound++;
                        loadQuestion();
                    }, 1500);
                }, 1000);

            } else {
                // JAWAPAN SALAH - Hancurkan Planet
                wrongSound.pause();
                wrongSound.currentTime = 0;
                wrongSound.play();
                const slotsContainer = slots[0].parentElement;
                slotsContainer.classList.add('shake');
                slots.forEach(slot => {
                    slot.classList.replace('filled', 'error');
                });

                let mistakeKey = `${correctSentence}_${userSentence}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                setTimeout(() => {
                    destroySound.currentTime = 0;
                    destroySound.play().catch(e => {});

                    // Planet Bertukar hancur
                    planetImg.src = imageAssets.planetDestroyed;
                    planetImg.classList.add('planet-destroyed');

                    // Terus bergerak ke soalan seterusnya selepas lihat hancur (Tiada Ulang)
                    setTimeout(() => {
                        slotsContainer.classList.remove('shake');
                        currentRound++;
                        loadQuestion();
                    }, 2000);

                }, 500); // Masa untuk gegar dulu
            }
        }

        // Render Bintang
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
            return score >= 32 ? 3 : (score >= 20 ? 2 : 1);
        }

        function endGame() {
            document.getElementById('final-score').innerText = score;
            const earnedStars = calculateStars();
            renderStars(earnedStars);
            winSound.currentTime = 0;
            winSound.play();

            const winOverlay = document.getElementById('win-overlay');
            winOverlay.classList.remove('hidden');
            window.speechSynthesis.cancel();


        }

        function restartGame() {
            startGame();
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
