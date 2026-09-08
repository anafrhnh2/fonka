@extends('layouts.game')

@section('game-title', 'Tahap 12: Uji Ingatan')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 12 }},
            nextLevelUrl: "{{ route('games.levels') }}",
              audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#0F172A] transition-all duration-300">


        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0 z-20">
                <div class="flex items-center bg-slate-800 border-4 border-indigo-500 rounded-full px-4 py-2 shadow-lg">
                    <span class="text-indigo-400 font-black text-sm md:text-xl uppercase tracking-wider font-orbitron">
                        <i class="fa-solid fa-microchip mr-2"></i> Uji Ingatan
                    </span>
                </div>

                <div class="flex items-center gap-3 md:gap-5 font-lexend">
                    <div
                        class="flex flex-col items-center bg-slate-800 border-4 border-slate-600 rounded-2xl px-4 py-1 shadow-lg">
                        <span class="text-slate-400 font-bold text-xs md:text-sm uppercase tracking-wider">Pusingan</span>
                        <span id="round-display" class="text-white font-black text-xl md:text-2xl">0/10</span>
                    </div>

                    <div
                        class="flex flex-col items-center bg-slate-800 border-4 border-emerald-500 rounded-2xl px-4 py-1 shadow-lg">
                        <span class="text-emerald-400 font-bold text-xs md:text-sm uppercase tracking-wider">Markah</span>
                        <span id="score-display" class="text-white font-black text-xl md:text-2xl">0</span>
                    </div>

                      <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                        class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                        <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                    </button>
                </div>
            </div>

            <div id="game-board"
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-indigo-900 w-full flex flex-col items-center bg-[#1E293B] pt-4">

                <div
                    class="absolute inset-0 z-0 pointer-events-none opacity-20 bg-[url('https://www.transparenttextures.com/patterns/circuit-board.png')]">
                </div>

                <div
                    class="text-center z-10 bg-slate-900/80 px-8 py-2 md:py-3 rounded-full border-2 border-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.3)] backdrop-blur-sm mb-4">
                    <h2 id="instruction-text"
                        class="text-lg md:text-2xl font-black text-indigo-300 tracking-wide font-lexend transition-all">
                        Ingat perkataan ini!</h2>
                </div>

                <div id="memorize-phase"
                    class="flex flex-col items-center justify-center w-full max-w-3xl flex-1 z-10 transition-all duration-500">
                    <div
                        class="bg-slate-800 border-4 border-slate-600 rounded-3xl p-6 md:p-10 shadow-2xl flex flex-col items-center w-[90%] md:w-[80%]">
                        <div class="flex flex-wrap gap-3 md:gap-6 justify-center mb-6 w-full" id="target-syllables-display">
                        </div>
                        <div class="w-full h-4 md:h-6 bg-slate-900 rounded-full overflow-hidden border-2 border-slate-700">
                            <div id="timer-bar"
                                class="h-full bg-emerald-400 w-full rounded-full transition-all ease-linear"></div>
                        </div>
                        <p class="text-slate-400 font-lexend font-bold mt-3 animate-pulse"><i
                                class="fa-regular fa-clock mr-2"></i>Masa untuk ingat...</p>
                    </div>
                </div>

                <div id="recall-phase"
                    class="hidden flex-col items-center justify-between w-full flex-1 z-10 pb-6 transition-all duration-500">
                    <div class="flex flex-wrap justify-center gap-2 md:gap-4 mt-8 w-full px-4" id="answer-slots">
                        <div id="slot-0"
                            class="w-16 h-20 md:w-28 md:h-32 bg-slate-900 border-4 border-dashed border-indigo-500 rounded-2xl flex items-center justify-center shadow-inner transition-colors">
                        </div>
                        <div id="slot-1"
                            class="w-16 h-20 md:w-28 md:h-32 bg-slate-900 border-4 border-dashed border-indigo-500 rounded-2xl flex items-center justify-center shadow-inner transition-colors">
                        </div>
                        <div id="slot-2"
                            class="w-16 h-20 md:w-28 md:h-32 bg-slate-900 border-4 border-dashed border-indigo-500 rounded-2xl flex items-center justify-center shadow-inner transition-colors">
                        </div>
                    </div>

                    <button id="btn-hint" onclick="useHint()"
                        class="mt-6 bg-slate-800 text-amber-400 font-lexend font-bold px-6 py-2 rounded-full border-2 border-amber-500/50 hover:bg-slate-700 hover:border-amber-500 active:scale-95 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-eye"></i> Tunjuk Semula (-1 Markah)
                    </button>

                    <div
                        class="w-full bg-slate-800/90 p-4 md:p-8 rounded-t-[3rem] border-t-4 border-indigo-600 shadow-[0_-10px_30px_rgba(0,0,0,0.5)] mt-auto flex flex-col items-center backdrop-blur-md">
                        <div class="flex flex-wrap justify-center gap-3 md:gap-6 w-full max-w-4xl" id="options-container">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-slate-900/90 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8">
            <div
                class="bg-slate-800 p-6 md:p-10 rounded-[2.5rem] shadow-[0_0_40px_rgba(99,102,241,0.5)] text-center max-w-lg w-full flex flex-col border-[6px] border-indigo-500 font-lexend">
                <div class="text-6xl mb-4 text-indigo-400"><i class="fa-solid fa-brain"></i></div>
                <h1 class="text-3xl md:text-4xl font-black text-indigo-300 mb-6 font-orbitron tracking-wider">Uji Ingatan
                </h1>
                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10">
                    <div class="flex items-center gap-4 bg-slate-700 p-4 rounded-xl border-2 border-slate-600 shadow-sm">
                        <div
                            class="w-12 h-12 bg-slate-800 text-emerald-400 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <p class="text-slate-300 font-bold text-lg leading-tight">Ingat susunan suku kata dalam 4 saat.</p>
                    </div>
                    <div class="flex items-center gap-4 bg-slate-700 p-4 rounded-xl border-2 border-slate-600 shadow-sm">
                        <div
                            class="w-12 h-12 bg-slate-800 text-amber-400 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-puzzle-piece"></i>
                        </div>
                        <p class="text-slate-300 font-bold text-lg leading-tight">Susun semula dari ingatan. Setiap pusingan
                            bernilai 3 markah!</p>
                    </div>
                </div>
                <button onclick="startGame()"
                    class="btn-3d bg-indigo-600 border-indigo-800 text-white w-full text-2xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3">
                    <i class="fa-solid fa-play"></i> Mula
                </button>
            </div>
        </div>

        <div id="win-overlay"
            class="hidden absolute inset-0 bg-black/80 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8 overflow-y-auto">

            <div
                class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full animate-bounce-in border-[6px] border-indigo-200 relative my-auto">

                <div
                    class="absolute -top-12 left-1/2 transform -translate-x-1/2 text-7xl drop-shadow-lg text-yellow-400 bg-white rounded-full p-2 border-4 border-yellow-200 w-32 h-32 flex items-center justify-center">
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
    <audio id="sfx-tick" src="https://assets.mixkit.com/sfx/preview/mixkit-tick-tock-clock-timer-1045.mp3"></audio>
    <audio id="sfx-pop" src="https://assets.mixkit.com/sfx/preview/mixkit-modern-technology-select-3124.mp3"></audio>
    <audio id="sfx-wrong" src="https://assets.mixkit.com/sfx/preview/mixkit-system-fault-36.mp3"></audio>
    <audio id="sfx-success" src="https://assets.mixkit.com/sfx/preview/mixkit-software-interface-start-2574.mp3"></audio>

    <style>
        .font-lexend {
            font-family: 'Lexend', sans-serif;
        }

        .font-orbitron {
            font-family: 'Orbitron', sans-serif;
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

        /* Box pada waktu Remember Phase */
        .mem-box {
            width: 70px;
            height: 80px;
            background: linear-gradient(145deg, #4F46E5, #3730A3);
            border: 4px solid #818CF8;
            border-radius: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            font-weight: 900;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            flex-shrink: 0;
        }

        @media (min-width: 768px) {
            .mem-box {
                width: 120px;
                height: 140px;
                font-size: 4rem;
            }
        }

        /* Butang jawapan di Recall Phase */
        .opt-btn {
            background-color: #1E293B;
            border-color: #0F172A;
            border-top-color: #334155;
            border-left-color: #334155;
            color: #38BDF8;
            width: 75px;
            height: 75px;
            font-size: 1.5rem;
            font-weight: 800;
            border-radius: 1rem;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        @media (min-width: 768px) {
            .opt-btn {
                width: 110px;
                height: 100px;
                font-size: 2.5rem;
                border-radius: 1.5rem;
            }
        }

        .opt-btn:not(:disabled):hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 10px 20px rgba(56, 189, 248, 0.2);
            background-color: #334155;
            color: #BAE6FD;
        }

        .opt-btn.selected {
            transform: scale(0);
            opacity: 0;
            pointer-events: none;
        }

        /* Text dalam kotak jawapan */
        .ans-slot {
            font-size: 2rem;
            font-weight: 900;
            color: white;
            text-transform: uppercase;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        @media (min-width: 768px) {
            .ans-slot {
                font-size: 4rem;
            }
        }

        .slot-filled {
            background-color: #312E81 !important;
            border-color: #6366F1 !important;
            border-style: solid !important;
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.5);
        }

        .slot-error {
            background-color: #7F1D1D !important;
            border-color: #EF4444 !important;
            border-style: solid !important;
        }

        .slot-success {
            background-color: #064E3B !important;
            border-color: #10B981 !important;
            border-style: solid !important;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.6);
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

        /* CSS Tambahan Fullscreen (Supaya box muat) */
        .is-fullscreen .opt-btn {
            width: 100px;
            height: 90px;
            font-size: 2.2rem;
        }

        .is-fullscreen .mem-box {
            width: 100px;
            height: 120px;
            font-size: 3.5rem;
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
        const tickSound = document.getElementById('sfx-tick');
        const popSound = document.getElementById('sfx-pop');
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
                word: 'Kelapa',
                syllables: ['KE', 'LA', 'PA'],
                distractors: ['KA', 'LI', 'PU'],
                audio: '{{ asset('audio/Kelapa.mp3') }}',
                img: '{{ asset('images/games/kelapa.png') }}'
            },
            {
                word: 'Kereta',
                syllables: ['KE', 'RE', 'TA'],
                distractors: ['KA', 'RI', 'TI'],
                audio: '{{ asset('audio/Kereta.mp3') }}',
                img: '{{ asset('images/games/kereta.png') }}'
            },
            {
                word: 'Kamera',
                syllables: ['KA', 'ME', 'RA'],
                distractors: ['KE', 'MA', 'RU'],
                audio: '{{ asset('audio/Kamera.mp3') }}',
                img: '{{ asset('images/games/kamera.png') }}'
            },
            {
                word: 'Tomato',
                syllables: ['TO', 'MA', 'TO'],
                distractors: ['TA', 'MI', 'TU'],
                audio: '{{ asset('audio/Tomato.mp3') }}',
                img: '{{ asset('images/games/tomato.png') }}'
            },
            {
                word: 'Perigi',
                syllables: ['PE', 'RI', 'GI'],
                distractors: ['PA', 'RU', 'GU'],
                audio: '{{ asset('audio/Perigi.mp3') }}',
                img: '{{ asset('images/games/perigi.png') }}'
            },
            {
                word: 'Kepala',
                syllables: ['KE', 'PA', 'LA'],
                distractors: ['GE', 'PU', 'RA'],
                audio: '{{ asset('audio/Kepala.mp3') }}',
                img: '{{ asset('images/games/kepala.png') }}'
            },
            {
                word: 'Kerusi',
                syllables: ['KE', 'RU', 'SI'],
                distractors: ['KA', 'RI', 'CI'],
                audio: '{{ asset('audio/Kerusi.mp3') }}',
                img: '{{ asset('images/games/kerusi.png') }}'
            },
            {
                word: 'Petani',
                syllables: ['PE', 'TA', 'NI'],
                distractors: ['PA', 'TI', 'NU'],
                audio: '{{ asset('audio/Petani.mp3') }}',
                img: '{{ asset('images/games/petani.png') }}'
            },
            {
                word: 'Menara',
                syllables: ['ME', 'NA', 'RA'],
                distractors: ['MA', 'NI', 'RU'],
                audio: '{{ asset('audio/Menara.mp3') }}',
                img: '{{ asset('images/games/menara.png') }}'
            },
            {
                word: 'Pelita',
                syllables: ['PE', 'LI', 'TA'],
                distractors: ['PA', 'LU', 'TI'],
                audio: '{{ asset('audio/Pelita.mp3') }}',
                img: '{{ asset('images/games/pelita.png') }}'
            }
        ];

        const MAX_ROUNDS = 10;
        const MEMORIZE_TIME_MS = 4000;

        let shuffledData = [];
        let currentRound = 0;
        let score = 0;
        let currentData = {};
        let answersArr = [];
        let isWaiting = false;
        let sessionMistakes = {};
        let timerTimeout;

        const instructionText = document.getElementById('instruction-text');
        const memPhase = document.getElementById('memorize-phase');
        const recallPhase = document.getElementById('recall-phase');
        const targetDisplay = document.getElementById('target-syllables-display');
        const timerBar = document.getElementById('timer-bar');
        const optionsContainer = document.getElementById('options-container');
        const btnHint = document.getElementById('btn-hint');

        const slots = [
            document.getElementById('slot-0'),
            document.getElementById('slot-1'),
            document.getElementById('slot-2')
        ];

        function playPhonicsTTS(text, rate = 0.8) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ms-MY';
            utterance.rate = rate;
            window.speechSynthesis.speak(utterance);
        }

        function playWordAudio() {
            if (isWaiting) return;

            const currentItem = shuffledData[currentIndex];

            if (currentItem) {
                audioPlayer.src = currentItem.audio;
                audioPlayer.currentTime = 0;
                audioPlayer.play().catch(e => console.log("Audio play error:", e));
            }
        }

        function playFeedback(type) {
            if (type === 'wrong') wrongSound.play();
            if (type === 'win') winSound.play();
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

            // Check markah untuk aktifkan/matikan butang hint
            if (score < 1) {
                btnHint.style.opacity = "0.5";
                btnHint.style.cursor = "not-allowed";
            } else {
                btnHint.style.opacity = "1";
                btnHint.style.cursor = "pointer";
            }
        }

        function loadQuestion() {
            if (currentRound >= MAX_ROUNDS) {
                endGame();
                return;
            }
            isWaiting = true;
            answersArr = [];
            currentData = shuffledData[currentRound];
            updateUI();

            instructionText.innerText = "Ingat susunan ini!";
            instructionText.className =
                "text-lg md:text-2xl font-black text-amber-400 tracking-wide font-lexend animate-pulse";

            recallPhase.classList.add('hidden');
            memPhase.classList.remove('hidden');
            memPhase.classList.add('flex');

            slots.forEach(slot => {
                slot.innerHTML = '';
                slot.className =
                    "w-16 h-20 md:w-28 md:h-32 bg-slate-900 border-4 border-dashed border-indigo-500 rounded-2xl flex items-center justify-center shadow-inner transition-colors";
            });

            targetDisplay.innerHTML = '';
            currentData.syllables.forEach(s => {
                targetDisplay.innerHTML += `<div class="mem-box">${s}</div>`;
            });

            timerBar.style.transition = 'none';
            timerBar.style.width = '100%';
            timerBar.style.backgroundColor = '#34D399';

            setTimeout(() => {
                audioPlayer.src = currentData.audio;
                audioPlayer.play().catch(e => {
                    playPhonicsTTS(currentData.word, 0.8);
                });

                setTimeout(() => {
                    timerBar.style.transition =
                        `width ${MEMORIZE_TIME_MS}ms linear, background-color ${MEMORIZE_TIME_MS}ms linear`;
                    timerBar.style.width = '0%';
                    timerBar.style.backgroundColor = '#EF4444';
                    tickSound.currentTime = 0;
                    tickSound.play().catch(e => {});

                    timerTimeout = setTimeout(() => {
                        tickSound.pause();
                        startRecallPhase();
                    }, MEMORIZE_TIME_MS);
                }, 500);
            }, 500);
        }

        function startRecallPhase() {
            memPhase.classList.remove('flex');
            memPhase.classList.add('hidden');
            recallPhase.classList.remove('hidden');
            recallPhase.classList.add('flex');
            instructionText.innerText = "Susun semula perkataan tadi!";
            instructionText.className = "text-lg md:text-2xl font-black text-cyan-300 tracking-wide font-lexend";
            generateOptions();
            isWaiting = false;
        }

        // --- HINT LOGIC ---
        function useHint() {
            if (isWaiting || score < 1) return;
            isWaiting = true;

            // TOLAK MARKAH SEMASA
            score -= 1;
            updateUI();

            recallPhase.classList.remove('flex');
            recallPhase.classList.add('hidden');
            memPhase.classList.remove('hidden');
            memPhase.classList.add('flex');

            timerBar.style.transition = 'none';
            timerBar.style.width = '100%';
            timerBar.style.backgroundColor = '#FBBF24';

            setTimeout(() => {
                memPhase.classList.remove('flex');
                memPhase.classList.add('hidden');
                recallPhase.classList.remove('hidden');
                recallPhase.classList.add('flex');
                isWaiting = false;
            }, 1500);
        }

        function generateOptions() {
            optionsContainer.innerHTML = '';
            let options = [...currentData.syllables, ...currentData.distractors.slice(0, 3)].sort(() => Math.random() -
                0.5);
            options.forEach((syllable, index) => {
                const btn = document.createElement('button');
                btn.className = 'btn-3d opt-btn font-lexend transition-all';
                btn.innerText = syllable;
                btn.id = `opt-${index}`;
                btn.onclick = () => selectSyllable(syllable, btn);
                optionsContainer.appendChild(btn);
            });
        }

        function selectSyllable(syllable, btnElement) {
            if (isWaiting || answersArr.length >= 3) return;
            popSound.currentTime = 0;
            popSound.play().catch(e => {});
            answersArr.push({
                text: syllable,
                btnId: btnElement.id
            });
            btnElement.classList.add('selected');
            let currentIndex = answersArr.length - 1;
            let currentSlot = slots[currentIndex];
            currentSlot.classList.add('slot-filled');
            currentSlot.innerHTML = `<span class="ans-slot animate-bounce-in">${syllable}</span>`;
            if (answersArr.length === 3) checkAnswer();
        }

        function checkAnswer() {
            isWaiting = true;
            const userWord = answersArr.map(a => a.text).join('');
            const correctWord = currentData.syllables.join('');
            const slotContainer = document.getElementById('answer-slots');

            if (userWord === correctWord) {
                // JAWAPAN BETUL: TAMBAH 3 MARKAH
                score += 3;
                updateUI();
                correctSound.pause();
                correctSound.currentTime = 0;
                correctSound.play();
                instructionText.className = "text-lg md:text-2xl font-black text-emerald-400 tracking-wide font-lexend";
                slots.forEach(slot => slot.classList.replace('slot-filled', 'slot-success'));
                audioPlayer.src = currentData.audio;
                audioPlayer.play().catch(e => {
                    playPhonicsTTS(currentData.word, 1);
                });
                setTimeout(() => {
                    currentRound++;
                    loadQuestion();
                }, 2500);
            } else {
                // JAWAPAN SALAH
                wrongSound.pause();
                wrongSound.currentTime = 0;
                wrongSound.play();

                instructionText.className = "text-lg md:text-2xl font-black text-red-400 tracking-wide font-lexend";
                slotContainer.classList.add('shake');
                slots.forEach(slot => slot.classList.replace('slot-filled', 'slot-error'));
                let mistakeKey = `${correctWord}_${userWord}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                setTimeout(() => {
                    slotContainer.classList.remove('shake');
                    instructionText.innerText = "Susun semula perkataan tadi!";
                    instructionText.className =
                        "text-lg md:text-2xl font-black text-cyan-300 tracking-wide font-lexend";
                    slots.forEach(slot => {
                        slot.className =
                            "w-16 h-20 md:w-28 md:h-32 bg-slate-900 border-4 border-dashed border-indigo-500 rounded-2xl flex items-center justify-center shadow-inner transition-colors";
                        slot.innerHTML = '';
                    });
                    answersArr.forEach(ans => document.getElementById(ans.btnId).classList.remove('selected'));
                    answersArr = [];
                    isWaiting = false;
                }, 1500);
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
            return score >= 25 ? 3 : (score >= 15 ? 2 : 1);
        }

        function endGame() {
            document.getElementById('final-score').innerText = score;
            const earnedStars = calculateStars();
            renderStars(earnedStars);
            playFeedback('win');

            const winOverlay = document.getElementById('win-overlay');
            winOverlay.classList.remove('hidden');

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
