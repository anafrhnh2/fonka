@extends('layouts.game')

@section('game-title', 'Tahap 14: Piano Suku Kata')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 14 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"

        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#18181B] transition-all duration-300">

        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0 z-20">
                <div
                    class="flex items-center bg-slate-800 border-4 border-fuchsia-500 rounded-full px-4 py-2 shadow-[0_0_15px_rgba(217,70,239,0.4)]">
                    <span class="text-fuchsia-400 font-black text-sm md:text-xl uppercase tracking-wider font-lexend">
                        <i class="fa-solid fa-music mr-2"></i> Tap Piano
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
                        <span class="text-slate-400 font-bold text-xs md:text-sm uppercase tracking-wider">Pusingan</span>
                        <span id="round-display" class="text-white font-black text-xl md:text-2xl">0/10</span>
                    </div>
                    <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                        class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                        <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                    </button>

                </div>
            </div>

            <div id="game-board"
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-slate-700 w-full flex flex-col items-center bg-[#FEF08A] pt-4 pb-2">

                <div
                    class="absolute inset-0 z-0 pointer-events-none opacity-20 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]">
                </div>
                <div
                    class="absolute top-0 w-full h-full bg-gradient-to-b from-fuchsia-900/20 to-cyan-900/20 z-0 pointer-events-none">
                </div>

                <div
                    class="text-center z-10 bg-slate-900/90 px-8 py-2 rounded-full border-2 border-slate-600 shadow-md backdrop-blur-sm flex items-center gap-4 mt-2">
                    <button onclick="playTargetAudio()"
                        class="w-10 h-10 md:w-12 md:h-12 bg-fuchsia-500 text-white rounded-full flex items-center justify-center text-lg md:text-xl border-4 border-fuchsia-300 shadow-[0_4px_0_#A21CAF] active:translate-y-1 active:shadow-none transition-all pulse-btn-audio">
                        <i class="fa-solid fa-play"></i>
                    </button>
                    <h2 class="text-base md:text-xl font-black text-fuchsia-300 tracking-wide font-lexend responsive-text">
                        Tekan suku kata yang betul!</h2>
                </div>

                <div class="relative z-10 flex items-center gap-6 mt-4 w-full justify-center px-4">
                    <div class="w-20 h-20 md:w-32 md:h-32 bg-slate-800 border-4 border-slate-600 rounded-2xl flex items-center justify-center shadow-lg transition-all"
                        id="image-box">
                        <img id="question-img" src=""
                            class="w-[100%] h-[100%] object-contain transition-all duration-500">
                    </div>

                    <div class="flex flex-col gap-2">
                        <span class="text-slate-400 font-bold text-sm uppercase tracking-widest text-center">Sasaran:</span>
                        <div class="flex gap-2 text-2xl md:text-4xl font-black font-lexend">
                            <div id="text-slot-1"
                                class="w-16 md:w-24 h-12 md:h-16 bg-slate-800 border-b-4 border-slate-600 rounded-lg flex items-center justify-center text-slate-500 transition-colors">
                                _ _</div>
                            <div id="text-slot-2"
                                class="w-16 md:w-24 h-12 md:h-16 bg-slate-800 border-b-4 border-slate-600 rounded-lg flex items-center justify-center text-slate-500 transition-colors">
                                _ _</div>
                        </div>
                    </div>
                </div>

                <div id="piano-board"
                    class="w-[90%] max-w-2xl flex-1 bg-slate-900 border-x-4 border-t-4 border-slate-600 rounded-t-3xl relative overflow-hidden mt-6 shadow-[0_-10px_30px_rgba(0,0,0,0.5)]">

                    <div class="absolute inset-0 flex">
                        <div class="flex-1 border-r-2 border-slate-700/50 relative piano-lane" id="lane-0"></div>
                        <div class="flex-1 border-r-2 border-slate-700/50 relative piano-lane" id="lane-1"></div>
                        <div class="flex-1 relative piano-lane" id="lane-2"></div>
                    </div>

                    <div
                        class="absolute bottom-0 w-full h-24 bg-gradient-to-t from-fuchsia-600/30 to-transparent border-b-8 border-fuchsia-500 pointer-events-none z-20">
                    </div>

                    <div id="success-badge"
                        class="absolute inset-0 bg-slate-900/80 flex flex-col items-center justify-center z-30 opacity-0 pointer-events-none transition-opacity duration-500 backdrop-blur-sm">
                        <span class="text-7xl mb-2"><i class="fa-solid fa-star text-yellow-400"></i></span>
                        <span class="font-lexend font-black text-4xl md:text-5xl text-emerald-400 drop-shadow-md"></span>
                    </div>
                </div>

            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-slate-950/90 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8">
            <div
                class="bg-slate-900 p-6 md:p-10 rounded-[2.5rem] shadow-[0_0_40px_rgba(217,70,239,0.4)] text-center max-w-lg w-full flex flex-col border-[6px] border-fuchsia-500 font-lexend">
                <div class="text-6xl mb-4 text-fuchsia-400"><i class="fa-solid fa-music"></i></div>
                <h1 class="text-3xl md:text-4xl font-black text-fuchsia-400 mb-6 tracking-wider">Tap Piano Suku Kata</h1>
                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10">
                    <div class="flex items-center gap-4 bg-slate-800 p-4 rounded-xl border-2 border-slate-700 shadow-sm">
                        <div
                            class="w-12 h-12 bg-slate-700 text-cyan-400 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <p class="text-slate-300 font-bold text-lg leading-tight">Lihat suku kata yang jatuh dari atas.</p>
                    </div>
                    <div class="flex items-center gap-4 bg-slate-800 p-4 rounded-xl border-2 border-slate-700 shadow-sm">
                        <div
                            class="w-12 h-12 bg-slate-700 text-fuchsia-400 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-hand-pointer"></i>
                        </div>
                        <p class="text-slate-300 font-bold text-lg leading-tight">Tepuk (Tekan) suku kata yang betul
                            mengikut <b>urutan perkataan!</b></p>
                    </div>
                </div>
                <button onclick="startGame()"
                    class="btn-3d bg-fuchsia-600 border-fuchsia-800 text-white w-full text-2xl font-black py-4 rounded-[1.5rem] flex justify-center items-center gap-3 hover:bg-fuchsia-500">
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
                        Main Semula <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <audio id="audio-player" preload="auto"></audio>
    <audio id="sfx-beat-1" src="https://assets.mixkit.com/sfx/preview/mixkit-modern-technology-select-3124.mp3"></audio>
    <audio id="sfx-beat-2" src="https://assets.mixkit.com/sfx/preview/mixkit-musical-reveal-902.mp3"></audio>
    <audio id="sfx-wrong" src="https://assets.mixkit.com/sfx/preview/mixkit-buzzer-14.mp3"></audio>

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

        .piano-tile {
            position: absolute;
            width: 80%;
            /* Lebar dalam lane */
            left: 10%;
            height: 70px;
            background-color: #38BDF8;
            /* Cyan */
            border: 4px solid #0284C7;
            border-bottom-width: 8px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-family: 'Lexend', sans-serif;
            font-size: 1.8rem;
            color: #0F172A;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: slideDown linear forwards;
            transition: transform 0.1s, background-color 0.2s, opacity 0.2s;
            z-index: 10;
        }

        @media (min-width: 768px) {
            .piano-tile {
                height: 90px;
                font-size: 2.5rem;
                border-radius: 16px;
            }
        }

        .piano-tile:active {
            transform: scale(0.95);
            border-bottom-width: 4px;
            margin-top: 4px;
        }

        /* Animasi Jatuh (Piano) */
        @keyframes slideDown {
            0% {
                top: -20%;
            }

            100% {
                top: 110%;
            }
        }

        /* STATUS TEKS SLOT JAWAPAN */
        .text-success-cyan {
            background-color: #0891B2 !important;
            border-color: #22D3EE !important;
            color: white !important;
            box-shadow: 0 0 15px rgba(34, 211, 238, 0.5);
        }

        .text-success-fuchsia {
            background-color: #C026D3 !important;
            border-color: #E879F9 !important;
            color: white !important;
            box-shadow: 0 0 15px rgba(232, 121, 249, 0.5);
        }

        /* IMAGE SUCCESS STATE */
        .img-success {
            filter: grayscale(0%) !important;
            opacity: 1 !important;
            border-color: #34D399 !important;
            box-shadow: 0 0 40px rgba(52, 211, 153, 0.6);
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

        .shake {
            animation: shake 0.3s ease-in-out;
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

        @keyframes pulse-btn-audio {
            0% {
                transform: scale(1);
                box-shadow: 0 4px 0 #A21CAF, 0 0 0 0 rgba(217, 70, 239, 0.7);
            }

            70% {
                transform: scale(1.1);
                box-shadow: 0 4px 0 #A21CAF, 0 0 0 15px rgba(217, 70, 239, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 4px 0 #A21CAF, 0 0 0 0 rgba(217, 70, 239, 0);
            }
        }

        .pulse-btn-audio {
            animation: pulse-btn-audio 2s infinite;
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

        const beat1Sound = document.getElementById('sfx-beat-1');
        const beat2Sound = document.getElementById('sfx-beat-2');
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
        // --- DATA SOALAN KVK+KV & KV+KVK DENGAN DISTRACTORS ---
        const gameData = [{
                word: 'Lampu',
                syl1: 'LAM',
                syl2: 'PU',
                syl1Audio: 'Lam',
                syl2Audio: 'PU(op)',
                distractors: ['BOM', 'TU', 'PIN', 'BA'],
                img: '{{ asset('images/games/lampu.png') }}',
                audio: 'Lampu'
            },
            {
                word: 'Pintu',
                syl1: 'PIN',
                syl2: 'TU',
                syl1Audio: 'Pin',
                syl2Audio: 'TU(op)',
                distractors: ['LAM', 'PU', 'KUN', 'CI'],
                img: '{{ asset('images/games/pintu.png') }}',
                audio: 'Pintu'
            },
            {
                word: 'Bomba',
                syl1: 'BOM',
                syl2: 'BA',
                syl1Audio: 'Bom',
                syl2Audio: 'BA(op)',
                distractors: ['LEM', 'BU', 'HU', 'TAN'],
                img: '{{ asset('images/games/bomba.png') }}',
                audio: 'Bomba'
            },
            {
                word: 'Lembu',
                syl1: 'LEM',
                syl2: 'BU',
                syl1Audio: 'Lem',
                syl2Audio: 'BU(op)',
                distractors: ['BOM', 'BA', 'SI', 'PUT'],
                img: '{{ asset('images/games/lembu.png') }}',
                audio: 'Lembu'
            },
            {
                word: 'Kunci',
                syl1: 'KUN',
                syl2: 'CI',
                syl1Audio: 'Kun',
                syl2Audio: 'ci',
                distractors: ['PIN', 'TU', 'GI', 'TAR'],
                img: '{{ asset('images/games/kunci.png') }}',
                audio: 'Kunci'
            },
            {
                word: 'Hutan',
                syl1: 'HU',
                syl2: 'TAN',
                syl1Audio: 'Hu',
                syl2Audio: 'Tan',
                distractors: ['BU', 'LAN', 'LI', 'LIN'],
                img: '{{ asset('images/games/hutan.png') }}',
                audio: 'Hutan'
            },
            {
                word: 'Bulan',
                syl1: 'BU',
                syl2: 'LAN',
                syl1Audio: 'BU(op)',
                syl2Audio: 'Lan',
                distractors: ['HU', 'TAN', 'SI', 'PUT'],
                img: '{{ asset('images/games/bulan.png') }}',
                audio: 'Bulan'
            },
            {
                word: 'Lilin',
                syl1: 'LI',
                syl2: 'LIN',
                syl1Audio: 'LI(op)',
                syl2Audio: 'Lin',
                distractors: ['GI', 'TAR', 'LAM', 'PU'],
                img: '{{ asset('images/games/lilin.png') }}',
                audio: 'Lilin'
            },
            {
                word: 'Gitar',
                syl1: 'GI',
                syl2: 'TAR',
                syl1Audio: 'GI(op)',
                syl2Audio: 'Tar',
                distractors: ['LI', 'LIN', 'KUN', 'CI'],
                img: '{{ asset('images/games/gitar.png') }}',
                audio: 'Gitar'
            },
            {
                word: 'Siput',
                syl1: 'SI',
                syl2: 'PUT',
                syl1Audio: 'SI(op)',
                syl2Audio: 'Put',
                distractors: ['LEM', 'BU', 'BU', 'LAN'],
                img: '{{ asset('images/games/siput.png') }}',
                audio: 'Siput'
            }
        ];

        const MAX_ROUNDS = 10;
        let shuffledData = [];
        let currentRound = 0;
        let score = 0;
        let currentData = {};

        let currentStep = 1; // 1 = cari syl1, 2 = cari syl2
        let isWaiting = false;
        let spawnTimer;
        let sessionMistakes = {};
        const imgBox = document.getElementById('image-box');
        const questionImg = document.getElementById('question-img');
        const successBadge = document.getElementById('success-badge');

        const textSlot1 = document.getElementById('text-slot-1');
        const textSlot2 = document.getElementById('text-slot-2');

        document.addEventListener('DOMContentLoaded', () => {
            gameData.forEach(data => {
                if (data.img.includes('{{ asset')) data.img =
                    `https://placehold.co/300x300/D946EF/FFF?text=${data.word}`;
                const img = new Image();
                img.src = data.img;
            });
        });

        function playAudio(fileName) {

            audioPlayer.pause();
            audioPlayer.currentTime = 0;

            // USE Laravel asset base path
            audioPlayer.src = `${window.gameConfig.audioBaseUrl}/${fileName}.mp3`;

            // Important for iPad Safari
            audioPlayer.load();

            const playPromise = audioPlayer.play();

            if (playPromise !== undefined) {
                playPromise.catch(err => {
                    console.log("Audio blocked:", err);

                    // fallback speech
                    const utter = new SpeechSynthesisUtterance(fileName);
                    utter.lang = 'ms-MY';
                    speechSynthesis.speak(utter);
                });
            }
        }

        function playTargetAudio() {
            if (isWaiting && currentStep > 2) return;

            let audioFile = currentStep === 1 ?
                currentData.syl1Audio :
                currentData.syl2Audio;

            playAudio(audioFile);
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
            document.getElementById('score-display').innerText = score;
            document.getElementById('round-display').innerText = `${currentRound + 1}/${MAX_ROUNDS}`;
        }

        function loadQuestion() {
            if (currentRound >= MAX_ROUNDS) {
                endGame();
                return;
            }

            clearInterval(spawnTimer);
            clearLanes();

            isWaiting = false;
            currentStep = 1;
            currentData = shuffledData[currentRound];

            questionImg.src = currentData.img;

            textSlot1.innerText = currentData.syl1.replace(/./g, '_ ');
            textSlot2.innerText = currentData.syl2.replace(/./g, '_ ');

            imgBox.classList.remove('img-success');
            successBadge.classList.replace('opacity-100', 'opacity-0');

            textSlot1.className =
                "w-16 md:w-24 h-12 md:h-16 bg-slate-800 border-b-4 border-cyan-400 rounded-lg flex items-center justify-center text-cyan-500 transition-colors animate-pulse";
            textSlot2.className =
                "w-16 md:w-24 h-12 md:h-16 bg-slate-800 border-b-4 border-slate-600 rounded-lg flex items-center justify-center text-slate-500 transition-colors";

            updateUI();

            setTimeout(() => {
                playTargetAudio();
                startSpawning();
            }, 800);
        }

        function clearLanes() {
            document.getElementById('lane-0').innerHTML = '';
            document.getElementById('lane-1').innerHTML = '';
            document.getElementById('lane-2').innerHTML = '';
        }

        function startSpawning() {
            spawnTimer = setInterval(() => {
                if (isWaiting) return;

                const laneIdx = Math.floor(Math.random() * 3);
                const lane = document.getElementById(`lane-${laneIdx}`);
                const tile = document.createElement('div');
                tile.className = 'piano-tile';

                let text = "";
                const expectedText = currentStep === 1 ? currentData.syl1 : currentData.syl2;

                if (Math.random() < 0.5) {
                    text = expectedText;
                } else {
                    text = currentData.distractors[Math.floor(Math.random() * currentData.distractors.length)];
                }
                tile.innerText = text;

                tile.style.animationDuration = "4.5s";

                const tapHandler = (e) => {
                    e.preventDefault();
                    handleTileTap(tile, text, expectedText);
                };
                tile.addEventListener('pointerdown', tapHandler);

                tile.addEventListener('animationend', () => {
                    if (tile.parentNode) tile.parentNode.removeChild(tile);
                });

                lane.appendChild(tile);
            }, 1200);
        }

        function handleTileTap(tileElement, text, expectedText) {
            if (tileElement.dataset.tapped == "true" || isWaiting) return;
            tileElement.dataset.tapped = "true";

            if (text === expectedText) {
                // JAWAPAN BETUL
                tileElement.style.backgroundColor = "#10B981";
                tileElement.style.borderColor = "#059669";
                tileElement.style.color = "#FFFFFF";
                tileElement.style.transform = "scale(1.1)";
                correctSound.currentTime = 0;
                correctSound.play().catch(e => {});
                setTimeout(() => {
                    tileElement.style.opacity = "0";
                    setTimeout(() => tileElement.remove(), 200);
                }, 150);

                if (currentStep === 1) {
                    beat1Sound.currentTime = 0;
                    beat1Sound.play().catch(e => {});

                    textSlot1.innerText = text;
                    textSlot1.className =
                        "w-16 md:w-24 h-12 md:h-16 flex items-center justify-center rounded-lg transition-colors text-success-cyan font-black text-2xl md:text-3xl";

                    currentStep = 2;
                    textSlot2.className =
                        "w-16 md:w-24 h-12 md:h-16 bg-slate-800 border-b-4 border-fuchsia-400 rounded-lg flex items-center justify-center text-fuchsia-500 transition-colors animate-pulse";

                    setTimeout(() => playTargetAudio(), 300);

                } else if (currentStep === 2) {
                    isWaiting = true;
                    clearInterval(spawnTimer);
                    beat2Sound.currentTime = 0;
                    beat2Sound.play().catch(e => {});

                    textSlot2.innerText = text;
                    textSlot2.className =
                        "w-16 md:w-24 h-12 md:h-16 flex items-center justify-center rounded-lg transition-colors text-success-fuchsia font-black text-2xl md:text-3xl";

                    score += 1;
                    updateUI();

                    setTimeout(() => {
                        handleWordComplete();
                    }, 500);
                }

            } else {
                // JAWAPAN SALAH
                wrongSound.currentTime = 0;
                wrongSound.play().catch(e => {});

                tileElement.style.backgroundColor = "#EF4444";
                tileElement.style.borderColor = "#B91C1C";
                tileElement.style.color = "#FFFFFF";
                tileElement.classList.add('shake');
                let mistakeKey = `${currentData.word}_${text}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                setTimeout(() => {
                    tileElement.style.opacity = "0";
                    setTimeout(() => tileElement.remove(), 200);
                }, 300);
            }
        }

        function handleWordComplete() {
            clearLanes();

            imgBox.classList.add('img-success');
            successBadge.classList.replace('opacity-0', 'opacity-100');

            playAudio(currentData.audio);

            setTimeout(() => {
                currentRound++;
                loadQuestion();
            }, 3000);
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
            clearInterval(spawnTimer);
            document.getElementById('final-score').innerText = score;
            const earnedStars = calculateStars();
            renderStars(earnedStars);

            const winOverlay = document.getElementById('win-overlay');
            winOverlay.classList.remove('hidden');
            window.speechSynthesis.cancel();
            playAudio('tahniah');
        }

        function restartGame() {
            startGame();
        }

        function saveAndExit(event) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const btn = event.target.closest('button');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

            fetch(window.gameConfig.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
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
