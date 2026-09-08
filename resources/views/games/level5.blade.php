@extends('layouts.game')

@section('game-title', 'Tahap 5: Gabung Bunyi KV')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 6 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#F0FDF4] transition-all duration-300">


        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0">
                <div class="flex items-center bg-blue-50 border-4 border-blue-200 rounded-full px-4 py-2 shadow-sm">
                    <span class="text-blue-800 font-black text-sm md:text-xl uppercase tracking-wider">
                        <i class="fa-solid fa-puzzle-piece mr-2"></i> Bina Suku Kata KV
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

                <div class="absolute inset-0 bg-blue-50/50 z-0 pointer-events-none"></div>

                <div
                    class="text-center mb-6 z-10 bg-white/90 px-6 py-2 rounded-full border-2 border-white shadow-sm backdrop-blur-sm">
                    <h2 class="text-xl md:text-3xl font-bold text-gray-700 tracking-wide responsive-text">Dengar dan
                        gabungkan huruf!</h2>
                </div>

                <div id="question-container"
                    class="bg-white p-4 md:p-8 rounded-[2.5rem] border-[6px] border-[#93C5FD] shadow-xl flex flex-col items-center justify-center w-[85%] md:w-[60%] max-w-2xl responsive-box relative mb-8 z-10">

                    <button id="btn-replay" onclick="playWordAudio()"
                        class="absolute -top-5 -right-5 w-14 h-14 md:w-16 md:h-16 bg-amber-400 text-white rounded-full flex items-center justify-center text-2xl md:text-3xl border-4 border-amber-200 shadow-[0_4px_0_#D97706] active:translate-y-1 active:shadow-none transition-all z-20">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>

                    <div class="flex gap-4 md:gap-8 justify-center w-full" id="drop-zones-container">

                        <div id="drop-consonant" data-target="" data-type="consonant"
                            class="drop-zone w-24 h-32 md:w-36 md:h-44 bg-gray-100 border-8 border-gray-300 rounded-3xl shadow-inner flex items-center justify-center transition-all relative">
                            <span
                                class="text-gray-300 font-black text-5xl md:text-7xl opacity-50 pointer-events-none drop-placeholder">?</span>
                            <div
                                class="absolute -bottom-5 md:-bottom-6 text-blue-600 font-bold text-xs md:text-sm bg-blue-50 px-3 py-1 rounded-full border-2 border-blue-200 shadow-sm whitespace-nowrap">
                                Konsonan</div>
                        </div>

                        <div class="flex items-center text-4xl text-gray-300 font-black">+</div>

                        <div id="drop-vowel" data-target="" data-type="vowel"
                            class="drop-zone w-24 h-32 md:w-36 md:h-44 bg-gray-100 border-8 border-gray-300 rounded-3xl shadow-inner flex items-center justify-center transition-all relative">
                            <span
                                class="text-gray-300 font-black text-5xl md:text-7xl opacity-50 pointer-events-none drop-placeholder">?</span>
                            <div
                                class="absolute -bottom-5 md:-bottom-6 text-yellow-600 font-bold text-xs md:text-sm bg-yellow-50 px-3 py-1 rounded-full border-2 border-yellow-200 shadow-sm whitespace-nowrap">
                                Vokal</div>
                        </div>

                    </div>

                </div>

                <div
                    class="bg-white/70 p-4 md:p-6 rounded-[2rem] border-4 border-white shadow-lg z-10 w-[90%] max-w-3xl flex flex-col items-center">
                    <p class="text-gray-500 font-bold mb-4 uppercase tracking-widest text-sm md:text-base">Tarik Huruf Ke
                        Atas</p>
                    <div class="flex flex-wrap justify-center gap-4 md:gap-6 w-full" id="letters-container">
                    </div>
                </div>

            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-[#0B172A]/80 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">
            <div
                class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full flex flex-col transform transition-all scale-100 border-[6px] border-blue-100">
                <h1 class="text-3xl md:text-4xl font-bold text-blue-600 mb-6 font-sans tracking-wide">Cara Bermain</h1>
                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10 flex-1">
                    <div class="flex items-center gap-4 bg-amber-50 p-4 rounded-xl border-2 border-amber-100 shadow-sm">
                        <div
                            class="w-12 h-12 bg-amber-200 text-amber-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-ear-listen"></i>
                        </div>
                        <p class="text-gray-600 font-bold text-lg md:text-xl leading-tight">Dengar bunyi suku kata.</p>
                    </div>
                    <div class="flex items-center gap-4 bg-blue-50 p-4 rounded-xl border-2 border-blue-100 shadow-sm">
                        <div
                            class="w-12 h-12 bg-blue-200 text-blue-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-hand-pointer"></i>
                        </div>
                        <p class="text-gray-600 font-bold text-lg md:text-xl leading-tight">Seret huruf <b>Konsonan
                                (Biru)</b> dan <b>Vokal (Kuning)</b> ke kotak kosong.</p>
                    </div>
                </div>
                <button onclick="startGame()"
                    class="btn-3d btn-blue w-full text-white text-2xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3">
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
                        Main Semula<i class="fa-solid fa-rotate-right"> </i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <audio id="audio-player" preload="auto"></audio>

    <style>
        .font-fredoka {
            font-family: 'Fredoka', sans-serif;
        }

        * {
            user-select: none;
            -webkit-user-select: none;
            touch-action: none;
        }

        .btn-3d {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
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

        .btn-green {
            background-color: #4CAF50;
            border-color: #2E7D32;
            border-top-color: #81C784;
            border-left-color: #81C784;
        }

        .btn-blue {
            background-color: #3B82F6;
            border-color: #1D4ED8;
            border-top-color: #93C5FD;
            border-left-color: #93C5FD;
        }

        /* GAYA HURUF MAGNET */
        .magnet-letter {
            width: 70px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 900;
            border-radius: 1rem;
            border-bottom-width: 8px;
            border-top-width: 2px;
            border-left-width: 2px;
            border-right-width: 2px;
            border-style: solid;
            cursor: grab;
            transition: transform 0.1s;
            z-index: 50;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            will-change: transform, left, top;
        }

        @media (min-width: 768px) {
            .magnet-letter {
                width: 90px;
                height: 110px;
                font-size: 3.5rem;
                border-radius: 1.5rem;
            }
        }

        .magnet-letter:active {
            cursor: grabbing;
            transform: scale(1.1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .magnet-consonant {
            background-color: #60A5FA;
            border-color: #2563EB;
            color: white;
            border-top-color: #93C5FD;
            border-left-color: #93C5FD;
        }

        .magnet-vowel {
            background-color: #FDE047;
            border-color: #CA8A04;
            color: #713F12;
            border-top-color: #FEF08A;
            border-left-color: #FEF08A;
        }

        /* KOTAK DROP ZONES */
        .drop-zone.correct-drop {
            background-color: #DCFCE7 !important;
            border-color: #4ADE80 !important;
        }

        .drop-zone.wrong-drop {
            background-color: #FEE2E2 !important;
            border-color: #F87171 !important;
        }

        .drop-zone.drag-over {
            transform: scale(1.05);
            border-color: #93C5FD;
            background-color: #EFF6FF;
        }

        /* KELAS RESPONSIF UNTUK FULLSCREEN */
        .is-fullscreen .responsive-box {
            padding: 3rem !important;
        }

        .is-fullscreen .responsive-box .drop-zone {
            width: 180px !important;
            height: 220px !important;
        }

        .is-fullscreen .responsive-box .drop-zone span {
            font-size: 7rem !important;
        }

        .is-fullscreen .responsive-text {
            font-size: 2.5rem !important;
        }

        .is-fullscreen .magnet-letter {
            width: 120px !important;
            height: 140px !important;
            font-size: 5rem !important;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px) rotate(-2deg);
            }

            75% {
                transform: translateX(10px) rotate(2deg);
            }
        }

        .shake {
            animation: shake 0.4s ease-in-out;
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
            font-size: 6rem;
            pointer-events: none;
            animation: pop-sparkle 0.8s ease-out forwards;
            z-index: 100;
        }

        @media (min-width: 768px) and (max-width: 1024px) {

            .is-fullscreen .responsive-box {
                width: 90% !important;
                padding: 2rem !important;
            }

            .is-fullscreen .drop-zone {
                width: 140px !important;
                height: 170px !important;
            }

            .is-fullscreen .drop-zone span {
                font-size: 5rem !important;
            }

            .is-fullscreen .magnet-letter {
                width: 95px !important;
                height: 110px !important;
                font-size: 4rem !important;
            }

            .is-fullscreen .responsive-text {
                font-size: 2rem !important;
            }

            .is-fullscreen #letters-container {
                gap: 1rem !important;
            }

            .is-fullscreen #game-board {
                padding: 1rem;
            }
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

        const gameData = [{
                syllable: 'BA',
                audio: '{{ asset('audio/Ba.mp3') }}'
            },
            {
                syllable: 'BI',
                audio: '{{ asset('audio/Bi.mp3') }}'
            },
            {
                syllable: 'BU',
                audio: '{{ asset('audio/Bu.mp3') }}'
            },
            {
                syllable: 'MA',
                audio: '{{ asset('audio/Ma.mp3') }}'
            },
            {
                syllable: 'MI',
                audio: '{{ asset('audio/Mi.mp3') }}'
            },
            {
                syllable: 'MU',
                audio: '{{ asset('audio/Mu.mp3') }}'
            },
            {
                syllable: 'SA',
                audio: '{{ asset('audio/Sa.mp3') }}'
            },
            {
                syllable: 'SI',
                audio: '{{ asset('audio/Si.mp3') }}'
            },
            {
                syllable: 'SU',
                audio: '{{ asset('audio/Su.mp3') }}'
            },
            {
                syllable: 'TA',
                audio: '{{ asset('audio/Ta.mp3') }}'
            },
            {
                syllable: 'TI',
                audio: '{{ asset('audio/Ti.mp3') }}'
            },
            {
                syllable: 'TU',
                audio: '{{ asset('audio/Tu.mp3') }}'
            }
        ];

        const allConsonants = ['B', 'M', 'S', 'T'];
        const allVowels = ['A', 'I', 'U'];

        const MAX_ROUNDS = 10;
        let shuffledData = [];
        let currentRound = 0;
        let score = 0;
        let currentData = {};
        let targetC = "";
        let targetV = "";
        let isWaiting = false;
        let sessionMistakes = {};
        let isBox1Filled = false;
        let isBox2Filled = false;

        const roundDisplay = document.getElementById('round-display');
        const scoreDisplay = document.getElementById('score-display');
        const dropConsonant = document.getElementById('drop-consonant');
        const dropVowel = document.getElementById('drop-vowel');
        const lettersContainer = document.getElementById('letters-container');
        const winOverlay = document.getElementById('win-overlay');

        function playLetterAudio(letter) {
            // Create a temporary audio object to play the individual letter sound
            const audioPath = `${window.gameConfig.audioBaseUrl}/${letter.toLowerCase()}.mp3`;
            const sound = new Audio(audioPath);
            sound.play().catch(err => console.log("Letter sound missing:", audioPath));
        }

        // Mainkan audio soalan
        function playWordAudio() {
            if (isWaiting || !currentData.audio) return;

            audioPlayer.pause();
            audioPlayer.currentTime = 0;
            audioPlayer.src = currentData.audio;

            const playPromise = audioPlayer.play();

            if (playPromise !== undefined) {
                playPromise.catch(() => {
                    console.log("MP3 gagal, guna TTS");

                    playPhonicsTTS(ttsFallback[targetC]);
                    setTimeout(() => {
                        playPhonicsTTS(ttsFallback[targetV]);
                    }, 800);
                });
            }
        }

        function playFeedback(type) {
            if (type === 'wrong') wrongSound.play();
            if (type === 'win') winSound.play();
        }

        // --- KAWALAN PERMAINAN ---
        function startGame() {
            document.getElementById('start-overlay').style.display = 'none';
            winOverlay.classList.add('hidden'); // Sembunyikan win overlay apabila mula semula

            shuffledData = [...gameData].sort(() => Math.random() - 0.5);
            if (shuffledData.length > MAX_ROUNDS) shuffledData.length = MAX_ROUNDS;

            score = 0;
            currentRound = 0;
            loadQuestion();
        }

        function restartGame() {
            startGame();
        }

        function loadQuestion() {
            if (currentRound >= MAX_ROUNDS) {
                endGame();
                return;
            }

            isWaiting = false;
            isBox1Filled = false;
            isBox2Filled = false;

            currentData = shuffledData[currentRound];
            targetC = currentData.syllable.charAt(0);
            targetV = currentData.syllable.charAt(1);

            // Reset Drop Zones
            resetDropZone(dropConsonant, targetC, 'Konsonan', 'text-blue-600', 'bg-blue-50', 'border-blue-200');
            resetDropZone(dropVowel, targetV, 'Vokal', 'text-yellow-600', 'bg-yellow-50', 'border-yellow-200');

            generateLetters(targetC, targetV);

            currentRound++;
            roundDisplay.innerText = `${currentRound}/${MAX_ROUNDS}`;
            scoreDisplay.innerText = score;

            // Animasi kotak soalan membesar bila tukar soalan
            const qContainer = document.getElementById('question-container');
            qContainer.style.transform = 'scale(0.8)';
            setTimeout(() => {
                qContainer.style.transform = 'scale(1)';
            }, 50);

            setTimeout(() => playWordAudio(), 600);
        }

        function resetDropZone(zone, targetLetter, labelText, textColor, bgColor, borderColor) {
            zone.dataset.target = targetLetter;
            zone.className =
                "drop-zone w-24 h-32 md:w-36 md:h-44 bg-gray-100 border-8 border-gray-300 rounded-3xl shadow-inner flex items-center justify-center transition-all relative";
            zone.innerHTML = `
                <span class="text-gray-300 font-black text-5xl md:text-7xl opacity-50 pointer-events-none drop-placeholder">?</span>
                <div class="absolute -bottom-5 md:-bottom-6 ${textColor} font-bold text-xs md:text-sm ${bgColor} px-3 py-1 rounded-full border-2 ${borderColor} shadow-sm whitespace-nowrap">${labelText}</div>
            `;
        }

        function generateLetters(correctC, correctV) {
            lettersContainer.innerHTML = '';
            let choices = [];

            choices.push({
                letter: correctC,
                type: 'consonant'
            });
            choices.push({
                letter: correctV,
                type: 'vowel'
            });

            let wrongC = allConsonants.filter(c => c !== correctC);
            let wrongV = allVowels.filter(v => v !== correctV);
            choices.push({
                letter: wrongC[Math.floor(Math.random() * wrongC.length)],
                type: 'consonant'
            });
            choices.push({
                letter: wrongV[Math.floor(Math.random() * wrongV.length)],
                type: 'vowel'
            });

            choices.sort(() => Math.random() - 0.5);

            choices.forEach(item => {
                let div = document.createElement('div');
                div.className = `magnet-letter ${item.type === 'consonant' ? 'magnet-consonant' : 'magnet-vowel'}`;
                div.innerText = item.letter;
                div.dataset.letter = item.letter;
                div.dataset.type = item.type;

                div.addEventListener('pointerdown', startDrag);
                lettersContainer.appendChild(div);
            });
        }

        // --- SISTEM DRAG AND DROP KUSTOM (POINTER EVENTS) ---
        let activeItem = null;
        let isDragging = false;
        let startX, startY;
        let containerRect;
        let dropZones = [dropConsonant, dropVowel];

        document.addEventListener('pointermove', drag);
        document.addEventListener('pointerup', endDrag);

        function startDrag(e) {
            if (isWaiting) return;
            if ((e.target.dataset.type === 'consonant' && isBox1Filled) || (e.target.dataset.type === 'vowel' &&
                    isBox2Filled)) return;

            activeItem = e.target;
            isDragging = true;

            const rect = activeItem.getBoundingClientRect();
            containerRect = fsContainer.getBoundingClientRect();

            startX = e.clientX - rect.left;
            startY = e.clientY - rect.top;

            activeItem.style.transition = 'none';
            activeItem.style.position = 'absolute';
            activeItem.style.zIndex = '1000';
            activeItem.style.transform = 'scale(1.1)';

            activeItem.style.left = (rect.left - containerRect.left) + 'px';
            activeItem.style.top = (rect.top - containerRect.top) + 'px';

            fsContainer.appendChild(activeItem);
        }

        function drag(e) {
            if (!isDragging || !activeItem) return;
            e.preventDefault();

            const xInside = e.clientX - containerRect.left;
            const yInside = e.clientY - containerRect.top;

            activeItem.style.left = (xInside - startX) + 'px';
            activeItem.style.top = (yInside - startY) + 'px';

            checkHoverOverZone(e.clientX, e.clientY);
        }

        function checkHoverOverZone(x, y) {
            dropZones.forEach(zone => {
                const rect = zone.getBoundingClientRect();
                if (x >= rect.left && x <= rect.right && y >= rect.top && y <= rect.bottom) {
                    zone.classList.add('drag-over');
                } else {
                    zone.classList.remove('drag-over');
                }
            });
        }

        function endDrag(e) {
            if (!isDragging || !activeItem) return;
            isDragging = false;

            activeItem.style.transform = 'scale(1)';
            activeItem.style.transition = 'all 0.2s ease';

            const droppedZone = getDropZoneUnderPointer(e.clientX, e.clientY);
            dropZones.forEach(z => z.classList.remove('drag-over'));

            if (droppedZone) {
                checkMatch(activeItem, droppedZone);
            } else {
                returnItemToTray(activeItem);
            }

            activeItem = null;
        }

        function getDropZoneUnderPointer(x, y) {
            for (let zone of dropZones) {
                const rect = zone.getBoundingClientRect();
                if (x >= rect.left && x <= rect.right && y >= rect.top && y <= rect.bottom) return zone;
            }
            return null;
        }

        function returnItemToTray(item) {
            item.style.position = 'relative';
            item.style.left = '0px';
            item.style.top = '0px';
            lettersContainer.appendChild(item);
        }

        // --- LOGIK SEMAKAN JAWAPAN ---
        function checkMatch(draggedElement, dropZone) {
            const letter = draggedElement.dataset.letter;
            const type = draggedElement.dataset.type;
            const targetLetter = dropZone.dataset.target;
            const targetType = dropZone.dataset.type;

            if (type !== targetType) {
                returnItemToTray(draggedElement);
                return;
            }

            if (letter === targetLetter) {
                score += 1;
                scoreDisplay.innerText = score;
                correctSound.play();
                draggedElement.style.position = 'relative';
                draggedElement.style.left = '0';
                draggedElement.style.top = '0';
                draggedElement.style.boxShadow = 'none';
                draggedElement.style.borderBottomWidth = '2px';
                draggedElement.removeEventListener('pointerdown', startDrag);

                const placeholder = dropZone.querySelector('.drop-placeholder');
                if (placeholder) placeholder.style.display = 'none';

                dropZone.appendChild(draggedElement);
                dropZone.classList.add('correct-drop');
                createSparkle(dropZone);

                if (targetType === 'consonant') isBox1Filled = true;
                if (targetType === 'vowel') isBox2Filled = true;

                if (isBox1Filled && isBox2Filled) {
                    isWaiting = true;

                    // ... inside the if (isBox1Filled && isBox2Filled) block ...
                    setTimeout(() => {
                        // 1. Construct the filename. 
                        // Use .toUpperCase() or .toLowerCase() to match your actual file exactly
                        const fileName = `${currentData.syllable}(op).mp3`;
                        const audioUrl = `${window.gameConfig.audioBaseUrl}/${fileName}`;

                        // 2. Update the player
                        audioPlayer.src = audioUrl;
                        audioPlayer.load(); // Force the browser to recognize the new source

                        // 3. Play the audio
                        audioPlayer.play().catch(err => {
                            console.error("Playback failed for:", audioUrl, err);

                            // Optional: Fallback if the (op) file is missing
                            playWordAudio();
                        });

                        setTimeout(loadQuestion, 2500);
                    }, 800);
                }
            } else {
                playFeedback('wrong');
                dropZone.classList.add('wrong-drop', 'shake');
                let mistakeKey = `${currentData.syllable}_${letter}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;

                returnItemToTray(draggedElement);

                setTimeout(() => {
                    dropZone.classList.remove('wrong-drop', 'shake');
                }, 600);
            }
        }

        function createSparkle(parent) {
            const sparkle = document.createElement('div');
            sparkle.classList.add('sparkle-effect');
            sparkle.innerHTML = '<i class="fa-solid fa-star text-yellow-400 drop-shadow-md"></i>';
            const rect = parent.getBoundingClientRect();
            sparkle.style.left = (rect.width / 2 - 40) + 'px';
            sparkle.style.top = (rect.height / 2 - 40) + 'px';
            parent.appendChild(sparkle);
            setTimeout(() => sparkle.remove(), 800);
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
            // Markah penuh sekarang adalah 20 (10 Pusingan * 2 huruf)
            return score >= 16 ? 3 : (score >= 10 ? 2 : 1);
        }

        function endGame() {
            document.getElementById('final-score').innerText = score;
            const earnedStars = calculateStars();
            renderStars(earnedStars);

            winOverlay.classList.remove('hidden');

            playFeedback('win'); // guna function yang memang wujud
        }

        function saveAndExit(event) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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
