@extends('layouts.game')

@section('game-title', 'Tahap 17: Lompatan Si Katak')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 17 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#ECFEFF] transition-all duration-300">

        <div
            class="absolute inset-0 z-0 pointer-events-none opacity-80 bg-[url('{{ asset('images/games/bg-swamp.png') }}')] bg-cover bg-center">
        </div>
        <div
            class="absolute bottom-0 w-full h-1/2 bg-gradient-to-t from-sky-500/30 to-transparent z-0 pointer-events-none animate-pulse">
        </div>

        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0 z-20">
                <div class="flex items-center bg-emerald-100 border-4 border-emerald-400 rounded-full px-4 py-2 shadow-sm">
                    <span class="text-emerald-800 font-black text-sm md:text-xl uppercase tracking-wider font-lexend">
                        <i class="fa-solid fa-frog mr-2"></i> Lompatan Katak
                    </span>
                </div>

                <div class="flex items-center gap-3 md:gap-5 font-lexend">
                    <div
                        class="flex flex-col items-center bg-white border-4 border-emerald-200 rounded-2xl px-4 py-1 shadow-sm">
                        <span class="text-emerald-500 font-bold text-xs md:text-sm uppercase tracking-wider">Markah</span>
                        <span id="score-display" class="text-emerald-700 font-black text-xl md:text-2xl">0</span>
                    </div>
                    <div
                        class="flex flex-col items-center bg-white border-4 border-sky-200 rounded-2xl px-4 py-1 shadow-sm">
                        <span class="text-sky-500 font-bold text-xs md:text-sm uppercase tracking-wider">Pusingan</span>
                        <span id="round-display" class="text-sky-700 font-black text-xl md:text-2xl">0/10</span>
                    </div>
                    <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                        class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                        <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                    </button>
                </div>
            </div>

            <div id="game-board"
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-sky-300 w-full flex flex-col items-center bg-transparent pt-4 pb-4">

                <div
                    class="text-center z-10 bg-white/90 px-6 py-2 rounded-full border-2 border-sky-200 shadow-md backdrop-blur-sm flex items-center gap-4 mt-2">
                    <button onclick="playTargetAudio()"
                        class="w-12 h-12 bg-amber-400 text-white rounded-full flex items-center justify-center text-xl border-4 border-amber-200 shadow-[0_4px_0_#D97706] active:translate-y-1 active:shadow-none transition-all pulse-btn-audio">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>
                    <h2 class="text-base md:text-xl font-black text-sky-800 tracking-wide font-lexend responsive-text">Susun
                        4 suku kata!</h2>
                </div>

                <div class="relative w-full max-w-4xl flex flex-col items-center mt-2 md:mt-6 z-10 flex-1 justify-center">

                    <div class="w-28 h-28 md:w-40 md:h-40 bg-white border-8 border-sky-200 rounded-[2rem] flex items-center justify-center shadow-[0_10px_20px_rgba(0,0,0,0.1)] transition-all mb-8 relative"
                        id="image-screen">
                        <img id="question-img" src=""
                            class="w-[80%] h-[80%] object-contain filter grayscale opacity-50 transition-all duration-500 z-0">

                        <div id="success-badge"
                            class="absolute -bottom-6 bg-emerald-500 text-white px-6 py-1 md:py-2 rounded-full font-black text-lg md:text-2xl border-4 border-emerald-300 opacity-0 transform scale-50 transition-all duration-500 shadow-lg z-20">
                            Berjaya !
                        </div>
                    </div>

                    <div
                        class="relative w-[95%] md:w-[85%] h-24 md:h-32 flex items-center justify-center gap-2 md:gap-6 px-2">

                        <div id="frog-character"
                            class="absolute left-0 bottom-4 w-16 h-16 md:w-24 md:h-24 transition-all duration-500 ease-in-out z-20 drop-shadow-lg">
                            <img src="{{ asset('images/games/frog.png') }}" class="w-full h-full object-contain">
                        </div>

                        <div class="flex gap-2 md:gap-4 z-10 w-full justify-center ml-10 md:ml-16 pl-4">
                            <div id="slot-0"
                                class="slot-box w-14 h-14 md:w-24 md:h-24 bg-emerald-400/50 border-4 border-dashed border-emerald-600 rounded-full flex items-center justify-center shadow-inner transition-all relative">
                                <span class="slot-text text-emerald-900 font-black text-lg md:text-3xl font-lexend">_</span>
                            </div>
                            <div id="slot-1"
                                class="slot-box w-14 h-14 md:w-24 md:h-24 bg-emerald-400/50 border-4 border-dashed border-emerald-600 rounded-full flex items-center justify-center shadow-inner transition-all relative">
                                <span class="slot-text text-emerald-900 font-black text-lg md:text-3xl font-lexend">_</span>
                            </div>
                            <div id="slot-2"
                                class="slot-box w-14 h-14 md:w-24 md:h-24 bg-emerald-400/50 border-4 border-dashed border-emerald-600 rounded-full flex items-center justify-center shadow-inner transition-all relative">
                                <span class="slot-text text-emerald-900 font-black text-lg md:text-3xl font-lexend">_</span>
                            </div>
                            <div id="slot-3"
                                class="slot-box w-14 h-14 md:w-24 md:h-24 bg-emerald-400/50 border-4 border-dashed border-emerald-600 rounded-full flex items-center justify-center shadow-inner transition-all relative">
                                <span class="slot-text text-emerald-900 font-black text-lg md:text-3xl font-lexend">_</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="w-full bg-[#854D0E] p-6 md:p-8 rounded-t-[3rem] border-t-8 border-[#713F12] shadow-[0_-10px_30px_rgba(0,0,0,0.2)] z-20 flex flex-col items-center mt-4 min-h-[180px] relative">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 w-full max-w-3xl z-10"
                        id="options-container">
                    </div>
                </div>

            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-slate-900/80 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">
            <div
                class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full flex flex-col border-[6px] border-emerald-400 font-lexend">
                <div class="text-6xl mb-4 text-emerald-500"><i class="fa-solid fa-frog"></i></div>
                <h1 class="text-3xl md:text-4xl font-black text-emerald-600 mb-6">Lompatan Katak</h1>
                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10">
                    <div class="flex items-center gap-4 bg-sky-50 p-4 rounded-xl border-2 border-sky-200 shadow-sm">
                        <div
                            class="w-12 h-12 bg-sky-200 text-sky-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-volume-high"></i>
                        </div>
                        <p class="text-slate-700 font-bold text-lg leading-tight">Dengar perkataan panjang dengan teliti.
                        </p>
                    </div>
                    <div class="flex items-center gap-4 bg-amber-50 p-4 rounded-xl border-2 border-amber-200 shadow-sm">
                        <div
                            class="w-12 h-12 bg-amber-200 text-amber-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-leaf"></i>
                        </div>
                        <p class="text-slate-700 font-bold text-lg leading-tight">Pilih <b>daun teratai</b> mengikut urutan.
                    </div>
                </div>
                <button onclick="startGame()"
                    class="btn-3d bg-emerald-500 border-emerald-700 text-white w-full text-2xl font-black py-4 rounded-[1.5rem] flex justify-center items-center gap-3">
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
    <audio id="sfx-jump"
        src="https://assets.mixkit.com/sfx/preview/mixkit-player-jumping-in-a-video-game-2043.mp3"></audio>
    <audio id="sfx-splash" src="https://assets.mixkit.com/sfx/preview/mixkit-water-splash-1311.mp3"></audio>
    <audio id="sfx-success" src="https://assets.mixkit.com/sfx/preview/mixkit-fairy-arcade-sparkle-866.mp3"></audio>
    <audio id="sfx-coin" src="https://assets.mixkit.com/sfx/preview/mixkit-coins-handling-1939.mp3"></audio>

    <style>
        .font-lexend {
            font-family: 'Lexend', sans-serif;
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

        /* BUTANG BATU SUKU KATA */
        .block-btn {
            background-color: #94A3B8;
            border-color: #475569;
            border-top-color: #CBD5E1;
            border-left-color: #CBD5E1;
            color: #0F172A;
            width: 100%;
            height: 60px;
            font-size: 1.5rem;
            font-weight: 900;
            border-radius: 2rem;
            text-transform: uppercase;
        }

        @media (min-width: 768px) {
            .block-btn {
                height: 80px;
                font-size: 2.2rem;
            }
        }

        .block-btn:not(:disabled):hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
            background-color: #CBD5E1;
        }

        .wrong-btn {
            background-color: #EF4444 !important;
            border-color: #991B1B !important;
            color: #FFF !important;
            transform: scale(0.9) !important;
        }

        /* STATUS DAUN TERATAI */
        .slot-active {
            border-color: #10B981 !important;
            border-style: solid !important;
            background-color: #34D399 !important;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
            transform: scale(1.1);
            z-index: 10;
        }

        .slot-filled {
            border-color: #059669 !important;
            border-style: solid !important;
            background-color: #10B981 !important;
        }

        .slot-filled .slot-text {
            color: white !important;
        }

        .slot-success {
            border-color: #047857 !important;
            border-style: solid !important;
            background-color: #059669 !important;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.8);
        }

        /* KESAN SKRIN BERJAYA */
        .screen-success {
            border-color: #10B981 !important;
            box-shadow: 0 0 40px rgba(16, 185, 129, 0.6) !important;
            background-color: #ECFDF5 !important;
        }

        .screen-success img {
            filter: grayscale(0%) !important;
            opacity: 1 !important;
            transform: scale(1.1);
        }

        /* CSS DUIT SYILING JATUH */
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

        /* ANIMASI */
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
            animation: shake 0.3s ease-in-out;
        }

        @keyframes pulse-btn-audio {
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

        .pulse-btn-audio {
            animation: pulse-btn-audio 2s infinite;
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

        @keyframes pop-sparkle {
            0% {
                transform: scale(0.5);
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

        /* Responsif Skrin Penuh */
        .is-fullscreen .responsive-text {
            font-size: 2.5rem !important;
        }

        .is-fullscreen #image-screen {
            width: 250px !important;
            height: 250px !important;
        }

        .is-fullscreen .slot-box {
            width: 100px !important;
            height: 100px !important;
        }

        .is-fullscreen .slot-text {
            font-size: 2.5rem !important;
        }

        .is-fullscreen #frog-character {
            width: 110px !important;
            height: 110px !important;
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
        const jumpSound = document.getElementById('sfx-jump');
        const splashSound = document.getElementById('sfx-splash');
        const successSound = document.getElementById('sfx-success');
        const coinSound = document.getElementById('sfx-coin');

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
        // --- DATA SOALAN 4 SUKU KATA ---
        const gameData = [{
                word: 'Matahari',
                syllables: ['MA', 'TA', 'HA', 'RI'],
                img: '{{ asset('images/games/matahari.png') }}',
                audio: '{{ asset('audio/Matahari.mp3') }}'
            },
            {
                word: 'Jururawat',
                syllables: ['JU', 'RU', 'RA', 'WAT'],
                img: '{{ asset('images/games/jururawat.png') }}',
                audio: '{{ asset('audio/Jururawat.mp3') }}'
            },
            {
                word: 'Jurutera',
                syllables: ['JU', 'RU', 'TE', 'RA'],
                img: '{{ asset('images/games/jurutera.png') }}',
                audio: '{{ asset('audio/Jurutera.mp3') }}'
            },
            {
                word: 'Keluarga',
                syllables: ['KE', 'LU', 'AR', 'GA'],
                img: '{{ asset('images/games/keluarga.png') }}',
                audio: '{{ asset('audio/Keluarga.mp3') }}'
            },
            {
                word: 'Olahraga',
                syllables: ['O', 'LAH', 'RA', 'GA'],
                img: '{{ asset('images/games/olahraga.png') }}',
                audio: '{{ asset('audio/Olahraga.mp3') }}'
            },
            {
                word: 'Mahasiswa',
                syllables: ['MA', 'HA', 'SIS', 'WA'],
                img: '{{ asset('images/games/mahasiswa.png') }}',
                audio: '{{ asset('audio/Mahasiswa.mp3') }}'
            },
            {
                word: 'Kemalangan',
                syllables: ['KE', 'MA', 'LA', 'NGAN'],
                img: '{{ asset('images/games/kemalangan.png') }}',
                audio: '{{ asset('audio/Kemalangan.mp3') }}'
            },
            {
                word: 'Pemakanan',
                syllables: ['PE', 'MA', 'KA', 'NAN'],
                img: '{{ asset('images/games/pemakanan.png') }}',
                audio: '{{ asset('audio/Pemakanan.mp3') }}'
            },
            {
                word: 'Pelajaran',
                syllables: ['PE', 'LA', 'JA', 'RAN'],
                img: '{{ asset('images/games/pelajaran.png') }}',
                audio: '{{ asset('audio/Pelajaran.mp3') }}'
            },
            {
                word: 'Kebakaran',
                syllables: ['KE', 'BA', 'KA', 'RAN'],
                img: '{{ asset('images/games/kebakaran.png') }}',
                audio: '{{ asset('audio/Kebakaran.mp3') }}'
            }
        ];

        const MAX_ROUNDS = 10;
        let shuffledData = [];
        let currentRound = 0;
        let score = 0;
        let currentData = {};

        let expectedIndex = 0;
        let isWaiting = false;
        let sessionMistakes = {};
        const imgScreen = document.getElementById('image-screen');
        const questionImg = document.getElementById('question-img');
        const successBadge = document.getElementById('success-badge');
        const optionsContainer = document.getElementById('options-container');
        const frog = document.getElementById('frog-character');

        const slots = [
            document.getElementById('slot-0'),
            document.getElementById('slot-1'),
            document.getElementById('slot-2'),
            document.getElementById('slot-3')
        ];

        // Preload imej 
        document.addEventListener('DOMContentLoaded', () => {
            gameData.forEach(data => {
                if (data.img.includes('{{ asset')) data.img =
                    `https://placehold.co/300x300/0284C7/FFF?text=${data.word}`;
                const img = new Image();
                img.src = data.img;
            });
        });

        // --- SISTEM AUDIO (TTS) ---
        function playPhonicsTTS(text, rate = 0.8) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ms-MY';
            utterance.rate = rate;
            window.speechSynthesis.speak(utterance);
        }

        function playTargetAudio() {
            if (isWaiting) return;

            // Jika ada fail audio, mainkan fail tersebut
            if (audioPlayer && currentData.audio) {
                audioPlayer.src = currentData.audio;
                audioPlayer.play().catch(e => {
                    // JIKA GAGAL (fail tiada/disekat pelayar), guna TTS sebagai ganti
                    let phonicText = currentData.syllables.join('... ');
                    playPhonicsTTS(phonicText, 0.7);
                });
            } else {
                // Jika data audio tiada langsung, terus guna TTS
                let phonicText = currentData.syllables.join('... ');
                playPhonicsTTS(phonicText, 0.7);
            }
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
            expectedIndex = 0;
            currentData = shuffledData[currentRound];

            questionImg.src = currentData.img;
            updateUI();

            // Reset UI Visual
            imgScreen.className =
                "w-28 h-28 md:w-40 md:h-40 bg-white border-8 border-sky-200 rounded-[2rem] flex items-center justify-center shadow-[0_10px_20px_rgba(0,0,0,0.1)] transition-all mb-8 relative";
            successBadge.classList.replace('opacity-100', 'opacity-0');
            successBadge.classList.replace('scale-100', 'scale-50');

            // Reset Posisi Katak
            frog.style.transform = 'translate(0px, 0px) scale(1)';
            frog.style.opacity = '1';

            // Reset Daun Teratai (Slots)
            slots.forEach((slot) => {
                slot.className =
                    "slot-box w-14 h-14 md:w-24 md:h-24 bg-emerald-400/50 border-4 border-dashed border-emerald-600 rounded-full flex items-center justify-center shadow-inner transition-all relative";
                slot.querySelector('.slot-text').innerText = "_";
                slot.querySelector('.slot-text').classList.remove('text-white');
            });

            // Aktifkan teratai pertama
            slots[0].classList.add('slot-active');

            generateOptions();

            setTimeout(() => playTargetAudio(), 800);
        }

        function generateOptions() {
            optionsContainer.innerHTML = '';

            let options = [...currentData.syllables].sort(() => Math.random() - 0.5);

            options.forEach((syllable) => {
                const btn = document.createElement('button');
                btn.className = 'btn-3d block-btn transition-all';
                btn.innerText = syllable;

                btn.onclick = () => selectSyllable(syllable, btn);
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

        function selectSyllable(syllable, btnElement) {
            if (isWaiting || expectedIndex >= 4) return;

            const expectedSyllable = currentData.syllables[expectedIndex];

            const allBtns = document.querySelectorAll('.block-btn');
            allBtns.forEach(btn => btn.disabled = true);

            if (syllable === expectedSyllable) {
                // JAWAPAN BETUL: KATAK LOMPAT KE TERATAI
                jumpSound.currentTime = 0;
                jumpSound.play().catch(e => {});
                correctSound.pause();
                correctSound.currentTime = 0;
                correctSound.play();
                let targetSlot = slots[expectedIndex];
                let frogRect = frog.parentElement.getBoundingClientRect();
                let slotRect = targetSlot.getBoundingClientRect();

                let moveX = (slotRect.left + slotRect.width / 2) - (frogRect.left + 30);
                let moveY = -20;

                frog.style.transform = `translate(${moveX}px, ${moveY}px) scale(1.2)`;

                targetSlot.classList.remove('slot-active', 'border-dashed');
                targetSlot.classList.add('slot-filled');
                targetSlot.querySelector('.slot-text').innerText = syllable;

                // Sembunyikan batu di bawah dengan efek
                btnElement.style.transform = "scale(0)";
                btnElement.style.opacity = "0";

                expectedIndex++;

                if (expectedIndex < 4) {
                    // Masih ada slot, lepaskan kunci butang untuk seterusnya
                    slots[expectedIndex].classList.add('slot-active');

                    // Unlock butang kembali untuk soalan seterusnya dalam ayat ini
                    setTimeout(() => {
                        allBtns.forEach(btn => {
                            if (btn.style.opacity !== "0") btn.disabled = false;
                        });
                    }, 500);

                } else {
                    // LENGKAP 4 SUKU KATA! (Skor hanya diberi jika lengkap penuh)
                    handleWordComplete();
                }

            } else {
                // JAWAPAN SALAH (JATUH AIR)
                isWaiting = true;
                splashSound.currentTime = 0;
                splashSound.play().catch(e => {});
                wrongSound.pause();
                wrongSound.currentTime = 0;
                wrongSound.play();
                btnElement.classList.add('shake', 'wrong-btn');
                let mistakeKey = `${currentData.word}_${syllable}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                // Katak jatuh air (Hilang)
                frog.style.transform = `translateY(100px) scale(0.5) rotate(45deg)`;
                frog.style.opacity = '0';

                // Warnakan slot yang salah
                let targetSlot = slots[expectedIndex];
                targetSlot.classList.replace('border-emerald-600', 'border-red-500');
                targetSlot.classList.replace('bg-emerald-400/50', 'bg-red-400/50');

                // Terus lompat ke soalan seterusnya tanpa peluang kedua
                setTimeout(() => {
                    currentRound++;
                    loadQuestion();
                }, 2000);
            }
        }

        function handleWordComplete() {
            isWaiting = true;
            score += 2; // Beri 2 markah untuk kejayaan 1 soalan
            updateUI();

            coinSound.currentTime = 0;
            coinSound.play().catch(e => {});
            spawnCoins();

            setTimeout(() => {
                successSound.currentTime = 0;
                successSound.play().catch(e => {});

                // Katak lompat ke destinasi
                frog.style.transform =
                    `translate(${frog.style.transform.split(',')[0].split('(')[1]}, -60px) scale(1.5)`;

                slots.forEach(slot => {
                    slot.classList.replace('slot-filled', 'slot-success');
                });

                imgScreen.classList.add('screen-success');
                successBadge.classList.replace('opacity-0', 'opacity-100');
                successBadge.classList.replace('scale-50', 'scale-100');
                createSparkle(imgScreen);

                audioPlayer.src = currentData.audio;
                audioPlayer.play().catch(e => {
                    playPhonicsTTS(currentData.word, 0.9);
                });

                setTimeout(() => {
                    currentRound++;
                    loadQuestion();
                }, 3500);

            }, 500);
        }

        function createSparkle(parent) {
            const sparkle = document.createElement('div');
            sparkle.classList.add('sparkle-effect');
            sparkle.innerHTML = '<i class="fa-solid fa-sparkles"></i>';

            const parentRect = parent.getBoundingClientRect();
            sparkle.style.left = (parentRect.width / 2 - 30) + 'px';
            sparkle.style.top = (parentRect.height / 2 - 30) + 'px';

            parent.appendChild(sparkle);
            setTimeout(() => sparkle.remove(), 1000);
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
            // Max score is 20 (10 rounds * 2 score)
            return score >= 16 ? 3 : (score >= 10 ? 2 : 1);
        }

        function endGame() {
            document.getElementById('final-score').innerText = score;
            const earnedStars = calculateStars();
            renderStars(earnedStars);

            const winOverlay = document.getElementById('win-overlay');
            winOverlay.classList.remove('hidden');
            window.speechSynthesis.cancel();
            winSound.pause();
            winSound.currentTime = 0;
            winSound.play();
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
