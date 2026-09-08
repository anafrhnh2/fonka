@extends('layouts.game')

@section('game-title', 'Tahap 6: Cantum Perkataan')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800&display=swap" rel="stylesheet">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 10 }},
            nextLevelUrl: "{{ route('games.levels') }}",
             audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#FFF7ED] transition-all duration-300">

        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0">
                <div class="flex items-center bg-orange-50 border-4 border-orange-200 rounded-full px-4 py-2 shadow-sm">
                    <span class="text-orange-800 font-black text-sm md:text-xl uppercase tracking-wider font-lexend">
                        <i class="fa-solid fa-puzzle-piece mr-2"></i> Cantum Perkataan
                    </span>
                </div>

                <div class="flex items-center gap-3 md:gap-5 font-lexend">
                    <div
                        class="flex flex-col items-center bg-green-50 border-4 border-green-200 rounded-2xl px-4 py-1 shadow-sm">
                        <span class="text-green-600 font-bold text-xs md:text-sm uppercase tracking-wider">Markah</span>
                        <span id="score-display" class="text-green-700 font-black text-xl md:text-2xl">0</span>
                    </div>

                    <div
                        class="flex flex-col items-center bg-blue-50 border-4 border-blue-200 rounded-2xl px-4 py-1 shadow-sm">
                        <span class="text-blue-500 font-bold text-xs md:text-sm uppercase tracking-wider">Pusingan</span>
                        <span id="round-display" class="text-blue-700 font-black text-xl md:text-2xl">0/10</span>
                    </div>

                     <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                        class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                        <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                    </button>
                </div>
            </div>

            <div id="game-board"
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-orange-100 w-full flex flex-col justify-between items-center bg-[#FFEDD5]">

                <div
                    class="absolute inset-0 z-0 pointer-events-none opacity-20 bg-[url('https://www.transparenttextures.com/patterns/shattered-island.png')]">
                </div>

                <div
                    class="text-center mt-6 z-10 bg-white/90 px-8 py-3 rounded-full border-2 border-white shadow-sm backdrop-blur-sm flex items-center gap-4">
                    <div class="text-center flex flex-col items-center gap-1 md:gap-2">

                        <h2 class="text-2xl md:text-3xl font-black text-slate-700 tracking-wide">
                            Cantumkan perkataan!
                        </h2>

                        <p class="text-sm md:text-lg font-semibold text-slate-500">
                            Dengar sebutan perkataan dahulu
                        </p>

                    </div>
                    <button onclick="playWordAudio()"
                        class="w-12 h-12 bg-amber-400 text-white rounded-full flex items-center justify-center text-xl border-4 border-amber-200 shadow-[0_4px_0_#D97706] active:translate-y-1 active:shadow-none transition-all">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>
                </div>

                <div class="relative z-10 flex flex-col items-center gap-6 md:gap-8 transition-transform duration-500 ease-in-out mb-4 responsive-box"
                    id="puzzle-area">

                    <div
                        class="bg-white border-8 border-orange-300 rounded-[2.5rem] w-40 h-40 md:w-52 md:h-52 flex flex-col items-center justify-center shadow-xl relative z-10">
                        <img id="question-img" src="" class="w-[100%] h-[100%] object-contain pointer-events-none">

                        <div id="feedback-badge"
                            class="absolute inset-0 flex items-center justify-center bg-white/90 rounded-[2rem] opacity-0 pointer-events-none transition-opacity duration-300 z-30">
                            <span id="feedback-icon" class="text-6xl drop-shadow-lg"><i
                                    class="fa-solid fa-check text-green-500"></i></span>
                        </div>
                    </div>

                    <div id="slots-container" class="flex justify-center transition-all duration-300 gap-3 md:gap-5">

                        <div id="slot-1"
                            class="slot-box w-28 h-28 sm:w-32 sm:h-32 md:w-40 md:h-36 lg:w-52 lg:h-48 bg-cyan-50 border-[6px] border-dashed border-cyan-400 rounded-l-2xl rounded-r-md flex items-center justify-center shadow-inner transition-all relative">
                            <span class="text-cyan-400 font-black text-3xl md:text-5xl slot-text opacity-50">?</span>
                        </div>

                        <div id="slot-2"
                            class="slot-box w-28 h-28 sm:w-32 sm:h-32 md:w-40 md:h-36 lg:w-52 lg:h-48 bg-fuchsia-50 border-[6px] border-dashed border-fuchsia-400 rounded-r-2xl rounded-l-md flex items-center justify-center shadow-inner transition-all relative">
                            <span class="text-fuchsia-400 font-black text-3xl md:text-5xl slot-text opacity-50">?</span>
                        </div>

                    </div>
                </div>

                <div
                    class="w-full bg-white/80 p-6 md:p-8 rounded-t-[3rem] border-t-8 border-orange-200 shadow-[0_-10px_30px_rgba(0,0,0,0.05)] z-20 flex flex-col items-center min-h-[180px]">
                    <div class="flex flex-nowrap justify-center gap-3 md:gap-5 w-full overflow-x-auto scale-[0.95] md:scale-100"
                        id="options-container">
                    </div>
                </div>

            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-slate-900/80 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">
            <div
                class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full flex flex-col border-[6px] border-orange-300 font-lexend">
                <h1 class="text-3xl md:text-4xl font-black text-orange-500 mb-6">Puzzle Perkataan</h1>
                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10 flex-1">
                    <div class="flex items-center gap-4 bg-amber-50 p-4 rounded-xl border-2 border-amber-100 shadow-sm">
                        <div
                            class="w-12 h-12 bg-amber-200 text-amber-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-ear-listen"></i></div>
                        <p class="text-slate-600 font-bold text-lg md:text-xl leading-tight">Dengar suku kata</p>
                    </div>
                    <div class="flex items-center gap-4 bg-sky-50 p-4 rounded-xl border-2 border-sky-100 shadow-sm">
                        <div
                            class="w-12 h-12 bg-sky-200 text-sky-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-hand-pointer"></i></div>
                        <p class="text-slate-600 font-bold text-lg md:text-xl leading-tight">Pilih dua kepingan suku kata di
                            bawah untuk mencantumkannya dengan betul.</p>
                    </div>
                </div>
                <button onclick="startGame()"
                    class="btn-3d bg-orange-500 border-orange-700 text-white w-full text-2xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3">
                    <i class="fa-solid fa-play"></i> Mula
                </button>
            </div>
        </div>

        <div id="win-overlay"
            class="hidden absolute inset-0 bg-black/80 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8 overflow-y-auto">

            <div
                class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full animate-bounce-in border-[6px] border-indigo-200 relative my-auto">

                <div
                    class="absolute -top-12 left-1/2 transform -translate-x-1/2 text-7xl drop-shadow-lg text-yellow-400 bg-white rounded-full p-2 border-4 border-yellow-200 flex items-center justify-center">
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
    <audio id="sfx-hover" src="https://assets.mixkit.com/sfx/preview/mixkit-pop-click-3104.mp3"></audio>
    <audio id="sfx-snap" src="https://assets.mixkit.com/sfx/preview/mixkit-modern-click-box-check-1120.mp3"></audio>

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

        /* Butang Pilihan (Kepingan puzzle) */
        .option-btn {
            background-color: #BAE6FD;
            border-color: #0284C7;
            border-top-color: #E0F2FE;
            border-left-color: #E0F2FE;
            color: #0F172A;
            width: 100px;
            height: 75px;
            font-size: 2rem;
            font-weight: 900;
            border-radius: 1.2rem;
        }

        @media (min-width: 768px) {
            .option-btn {
                width: 130px;
                height: 90px;
                font-size: 3rem;
            }
        }

        .option-btn:not(:disabled):hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 10px 20px rgba(2, 132, 199, 0.2);
            background-color: #7DD3FC;
        }

        .option-btn.selected {
            transform: scale(0);
            opacity: 0;
            pointer-events: none;
        }

        /* Status Kotak Puzzle */
        .slot-box {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Warna spesifik untuk Slot 1 & Slot 2 bila diisi (sebelum check) */
        #slot-1.filled {
            background-color: #CFFAFE !important;
            border-color: #06B6D4 !important;
            border-style: solid !important;
        }

        #slot-2.filled {
            background-color: #FCE7F3 !important;
            border-color: #EC4899 !important;
            border-style: solid !important;
        }

        .slot-box.error {
            background-color: #FEE2E2 !important;
            border-color: #EF4444 !important;
            border-style: solid !important;
        }

        .slot-text.filled-text {
            opacity: 1;
            color: #0F172A !important;
        }

        /* ANIMASI CANTUMAN (SNAP) BILA BETUL */
        #slots-container.joined {
            gap: 0 !important;
        }

        #slots-container.joined #slot-1 {
            border-right-width: 0 !important;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            background-color: #4ADE80 !important;
            /* Hijau */
            border-color: #16A34A !important;
        }

        #slots-container.joined #slot-2 {
            border-left-width: 0 !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            background-color: #4ADE80 !important;
            /* Hijau */
            border-color: #16A34A !important;
        }

        #slots-container.joined .slot-text {
            color: white !important;
        }

        /* Responsif Skrin Penuh Fix */
        .is-fullscreen .responsive-box {
            transform: scale(1.1);
            transform-origin: center;
        }

        .is-fullscreen .responsive-text {
            font-size: 2.5rem !important;
        }

        .is-fullscreen .option-btn {
            width: 140px !important;
            height: 100px !important;
            font-size: 3.5rem !important;
            border-radius: 1.5rem;
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
            animation: bounce-in 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
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
        const hoverSound = document.getElementById('sfx-hover');
        const snapSound = document.getElementById('sfx-snap');
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

        // --- DATA SOALAN KV+KV ---
        const gameData = [{
                word: 'Buku',
                syllables: ['BU', 'KU'],
                distractors: ['BI', 'BA', 'JU', 'KA'],
                img: '{{ asset('images/games/buku.png') }}',
                audio: '{{ asset('audio/Buku.mp3') }}'
            },
            {
                word: 'Baju',
                syllables: ['BA', 'JU'],
                distractors: ['BU', 'BI', 'JO', 'JA'],
                img: '{{ asset('images/games/baju.png') }}',
                audio: '{{ asset('audio/Baju.mp3') }}'
            },
            {
                word: 'Meja',
                syllables: ['ME', 'JA'],
                distractors: ['MA', 'MI', 'JU', 'JI'],
                img: '{{ asset('images/games/meja.png') }}',
                audio: '{{ asset('audio/Meja.mp3') }}'
            },
            {
                word: 'Susu',
                syllables: ['SU', 'SU'],
                distractors: ['SA', 'SO', 'SE', 'SI'],
                img: '{{ asset('images/games/susu.png') }}',
                audio: '{{ asset('audio/Susu.mp3') }}'
            },
            {
                word: 'Tali',
                syllables: ['TA', 'LI'],
                distractors: ['TI', 'TU', 'LE', 'LA'],
                img: '{{ asset('images/games/tali.png') }}',
                audio: '{{ asset('audio/Tali.mp3') }}'
            },
            {
                word: 'Mata',
                syllables: ['MA', 'TA'],
                distractors: ['MI', 'ME', 'TO', 'TU'],
                img: '{{ asset('images/games/mata.png') }}',
                audio: '{{ asset('audio/Mata.mp3') }}'
            },
            {
                word: 'Paku',
                syllables: ['PA', 'KU'],
                distractors: ['PI', 'PE', 'KO', 'KA'],
                img: '{{ asset('images/games/paku.png') }}',
                audio: '{{ asset('audio/Paku.mp3') }}'
            },
            {
                word: 'Kopi',
                syllables: ['KO', 'PI'],
                distractors: ['KA', 'KU', 'qI', 'PU'],
                img: '{{ asset('images/games/kopi.png') }}',
                audio: '{{ asset('audio/Kopi.mp3') }}'
            },
            {
                word: 'Sudu',
                syllables: ['SU', 'DU'],
                distractors: ['SA', 'SO', 'DO', 'DI'],
                img: '{{ asset('images/games/sudu.png') }}',
                audio: '{{ asset('audio/Sudu.mp3') }}'
            },
            {
                word: 'Lori',
                syllables: ['LO', 'RI'],
                distractors: ['LA', 'LU', 'RE', 'RU'],
                img: '{{ asset('images/games/lori.png') }}',
                audio: '{{ asset('audio/Lori.mp3') }}'
            }
        ];

        const MAX_ROUNDS = 10;
        let shuffledData = [];
        let currentRound = 0;
        let score = 0;
        let currentData = {};

        let answersArr = [];
        let isWaiting = false;

        const puzzleArea = document.getElementById('puzzle-area');
        const questionImg = document.getElementById('question-img');
        const optionsContainer = document.getElementById('options-container');
        const slotsContainer = document.getElementById('slots-container');
        const scoreDisplay = document.getElementById('score-display');
        const roundDisplay = document.getElementById('round-display');

        const slot1 = document.getElementById('slot-1');
        const slot1Text = slot1.querySelector('.slot-text');
        const slot2 = document.getElementById('slot-2');
        const slot2Text = slot2.querySelector('.slot-text');
        const feedbackBadge = document.getElementById('feedback-badge');
        const feedbackIcon = document.getElementById('feedback-icon');

        document.addEventListener('DOMContentLoaded', () => {
            gameData.forEach(data => {
                if (data.img.includes('{{ asset')) data.img =
                    `https://placehold.co/200x200/FDBA74/FFF?text=${data.word}`;
                const img = new Image();
                img.src = data.img;
            });
        });

        // --- SISTEM AUDIO ---
        function playPhonicsTTS(text, rate = 0.8) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ms-MY';
            utterance.rate = rate;
            window.speechSynthesis.speak(utterance);
        }

        function playWordAudio() {
            if (isWaiting) return;
            audioPlayer.src = currentData.audio;
            audioPlayer.play().catch(e => {
                playPhonicsTTS(currentData.word, 0.8);
            });
        }

        function playFeedback(type) {
            if (type === 'wrong') wrongSound.play();
            if (type === 'win') winSound.play();
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

        function restartGame() {
            startGame();
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
            answersArr = [];
            currentData = shuffledData[currentRound];

            questionImg.src = currentData.img;
            updateUI();

            // Reset Puzzle Slots
            slotsContainer.classList.remove('joined');
            resetSlot(slot1, slot1Text);
            resetSlot(slot2, slot2Text);

            feedbackBadge.classList.replace('opacity-100', 'opacity-0');

            puzzleArea.classList.remove('shake');
            puzzleArea.style.transform = 'scale(0.8)';
            setTimeout(() => {
                puzzleArea.style.transform = 'scale(1)';
            }, 50);

            generateOptions();
            setTimeout(() => playWordAudio(), 800);
        }

        function resetSlot(slot, textElement) {
            slot.classList.remove('filled', 'error');
            textElement.classList.remove('filled-text');
            textElement.innerText = '?';
        }

        function generateOptions() {
            optionsContainer.innerHTML = '';

            // Gabungkan suku kata betul dan distractors, kemudian shuffle
            let options = [...currentData.syllables, ...currentData.distractors].sort(() => Math.random() - 0.5);

            options.forEach((syllable, index) => {
                const btn = document.createElement('button');
                btn.className = 'btn-3d option-btn font-lexend transition-all';
                btn.innerText = syllable;
                btn.id = `opt-${index}`;

                btn.addEventListener('mouseenter', () => {
                    if (!isWaiting && !btn.disabled) {
                        hoverSound.currentTime = 0;
                        hoverSound.play().catch(e => {});
                    }
                });

                btn.onclick = () => selectPiece(syllable, btn);
                optionsContainer.appendChild(btn);
            });
        }

        // LOGIK KLIK UNTUK ISI PUZZLE
        function selectPiece(syllable, btnElement) {
            if (isWaiting || answersArr.length >= 2) return;

            answersArr.push({
                text: syllable,
                btnId: btnElement.id
            });

            btnElement.classList.add('selected'); // Sembunyikan kepingan bawah
            audioPlayer.pause();
            audioPlayer.currentTime = 0;
            audioPlayer.src = `/audio/${syllable}(op).mp3`;
            audioPlayer.play();

            if (answersArr.length === 1) {
                slot1Text.innerText = syllable;
                slot1Text.classList.add('filled-text');
                slot1.classList.add('filled');
            } else if (answersArr.length === 2) {
                slot2Text.innerText = syllable;
                slot2Text.classList.add('filled-text');
                slot2.classList.add('filled');

                checkAnswer();
            }
        }

        function checkAnswer() {
            isWaiting = true;

            const userWord = answersArr.map(a => a.text).join('');
            const correctWord = currentData.syllables.join('');

            if (userWord === correctWord) {
                // JAWAPAN BETUL: ANIMASI CANTUM (SNAP)
                score += 1;
                updateUI();

                setTimeout(() => {
                    snapSound.currentTime = 0;
                    snapSound.play().catch(e => {});
                    correctSound.play();

                    // Cantumkan Puzzle secara CSS
                    slotsContainer.classList.add('joined');
                    createSparkle(slotsContainer);

                    feedbackIcon.innerHTML = '<i class="fa-solid fa-check text-green-500"></i>';
                    feedbackBadge.classList.replace('opacity-0', 'opacity-100');

                    // Audio perkataan penuh
                    setTimeout(() => playWordAudio(), 500);

                    setTimeout(() => {
                        currentRound++;
                        loadQuestion();
                    }, 2500); // Masa untuk murid lihat perkataan lengkap

                }, 400);

            } else {
                // JAWAPAN SALAH
                playFeedback('wrong');
                slot1.classList.add('error');
                slot2.classList.add('error');
                puzzleArea.classList.add('shake');

                feedbackIcon.innerHTML = '<i class="fa-solid fa-xmark text-red-500"></i>';
                feedbackBadge.classList.replace('opacity-0', 'opacity-100');
                let mistakeKey = `${correctWord}_${userWord}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;

                setTimeout(() => {
                    puzzleArea.classList.remove('shake');
                    feedbackBadge.classList.replace('opacity-100', 'opacity-0');

                    resetSlot(slot1, slot1Text);
                    resetSlot(slot2, slot2Text);

                    // Kembalikan butang ke bawah
                    answersArr.forEach(ans => {
                        document.getElementById(ans.btnId).classList.remove('selected');
                    });

                    answersArr = [];
                    isWaiting = false;
                }, 1200);
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
            return score >= 8 ? 3 : (score >= 5 ? 2 : 1);
        }

        function endGame() {
            document.getElementById('final-score').innerText = score;
            const earnedStars = calculateStars();
            renderStars(earnedStars);

            const winOverlay = document.getElementById('win-overlay');
            winOverlay.classList.remove('hidden');
            playFeedback('win');
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
