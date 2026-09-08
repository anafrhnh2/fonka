@extends('layouts.game')

@section('game-title', 'Tahap 9: Kenali Huruf m, n, w dan u')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 9 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-gradient-to-b from-emerald-50 to-teal-100 transition-all duration-300">

        <div class="w-full relative z-10 p-4 shrink-0 flex flex-wrap justify-between items-center px-2 md:px-6 gap-y-3">
            <div class="flex items-center bg-white border-4 border-teal-200 rounded-2xl px-4 md:px-6 py-2 shadow-md">
                <span class="text-teal-800 font-black text-sm md:text-xl tracking-wider font-lexend">
                    <i class="fa-solid fa-spell-check mr-2 text-teal-500"></i> Kenali m, n, w dan u
                </span>
            </div>

            <div class="flex gap-3 md:gap-4 font-lexend">
                <div class="flex flex-col items-center bg-green-50 border-4 border-green-200 rounded-2xl px-4 md:px-5 py-1 shadow-sm">
                    <span class="text-green-600 font-bold text-xs uppercase">Markah</span>
                    <span id="score-display" class="text-green-700 font-black text-xl md:text-2xl">0</span>
                </div>
                <div class="flex flex-col items-center bg-orange-50 border-4 border-orange-200 rounded-2xl px-4 md:px-5 py-1 shadow-sm">
                    <span class="text-orange-500 font-bold text-xs uppercase">Soalan</span>
                    <span id="round-display" class="text-orange-700 font-black text-xl md:text-2xl">0/10</span>
                </div>

                  <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                        class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                        <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                    </button>
            </div>
        </div>

        <div id="game-board" class="flex-1 flex flex-col justify-center items-center relative z-10 px-4">
            <div id="main-card"
                class="bg-white rounded-[3rem] shadow-2xl border-8 border-teal-50 p-6 md:p-8 mb-8 md:mb-12 max-w-2xl w-full flex flex-col items-center transition-all duration-500 scale-100 relative">
                <div class="text-center mb-6 flex flex-col items-center">
                    <h2 class="text-xl md:text-2xl font-black text-slate-700 font-lexend mb-4">Dengar dan pilih huruf awalan yang betul</h2>
                    <button onclick="playWordAudio()"
                        class="w-16 h-16 md:w-20 md:h-20 bg-teal-500 text-white rounded-full flex items-center justify-center text-2xl md:text-3xl border-4 md:border-8 border-teal-200 shadow-lg hover:bg-teal-600 active:translate-y-1 transition-all z-20">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-4 md:gap-8 mb-4 w-full relative">
                    <div class="w-52 h-52 md:w-52 md:h-52 bg-slate-50 rounded-3xl border-4 border-dashed border-slate-300 flex items-center justify-center relative overflow-hidden">
                        <img id="question-img" src="" class="w-[100%] h-[100%] object-contain drop-shadow-md transition-opacity">
                        <div id="word-overlay" class="absolute inset-0 bg-teal-600 flex items-center justify-center opacity-0 transition-opacity duration-300 pointer-events-none">
                            <span id="word-text" class="text-white font-black text-2xl md:text-4xl uppercase font-lexend tracking-widest"></span>
                        </div>
                    </div>
                    <div class="text-5xl md:text-8xl font-black text-teal-100 font-lexend select-none hidden md:block">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                    <div id="answer-slot" class="w-24 h-24 md:w-32 md:h-32 bg-teal-50 border-8 border-teal-200 rounded-2xl flex items-center justify-center transition-all relative">
                        <span id="slot-text" class="text-6xl md:text-7xl font-black text-teal-600 opacity-20 font-lexend">?</span>
                    </div>
                </div>
            </div>

            <div class="w-full max-w-4xl bg-white/60 backdrop-blur-md p-6 md:p-8 rounded-t-[4rem] border-t-8 border-white shadow-[0_-10px_30px_rgba(0,0,0,0.05)] flex flex-wrap justify-center gap-8 md:gap-12" id="options-container">
            </div>
        </div>

        <div id="start-overlay" class="absolute inset-0 bg-teal-900/90 z-50 flex flex-col justify-center items-center backdrop-blur-lg px-4">
            <div class="bg-white p-8 md:p-12 rounded-[3rem] shadow-2xl text-center max-w-md w-full border-8 border-teal-200">
                <h1 class="text-3xl md:text-4xl font-black text-teal-600 mb-8 font-lexend">Cabaran m, n, w dan u</h1>
                <div class="flex flex-col gap-4 text-left mx-auto w-full mb-8">
                    <div class="flex items-center gap-4 bg-teal-50 p-4 rounded-xl border-2 border-teal-100">
                        <div class="w-12 h-12 bg-teal-200 text-teal-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-ear-listen"></i>
                        </div>
                        <p class="text-slate-600 font-bold text-lg leading-tight">Dengar bunyi perkataan dengan teliti.</p>
                    </div>
                    <div class="flex items-center gap-4 bg-orange-50 p-4 rounded-xl border-2 border-orange-100">
                        <div class="w-12 h-12 bg-orange-200 text-orange-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <p class="text-slate-600 font-bold text-lg leading-tight">Pilih huruf awalan yang betul: <b>m, n, w atau u</b></p>
                    </div>
                </div>
                <button onclick="startGame()" class="btn-3d bg-teal-500 border-teal-700 text-white w-full text-2xl font-bold py-4 rounded-3xl flex justify-center items-center gap-3">
                    <i class="fa-solid fa-play"></i> Mula
                </button>
            </div>
        </div>

        <div id="win-overlay" class="hidden absolute inset-0 bg-black/80 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8 overflow-y-auto">
            <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full animate-bounce-in border-[6px] border-teal-200 relative my-auto">
                <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 text-7xl drop-shadow-lg text-yellow-400 bg-white rounded-full p-2 border-4 border-yellow-200">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-teal-600 mb-2 mt-6">Tahap Selesai!</h1>
                <div class="bg-teal-50 p-6 rounded-[2rem] mb-8 border-4 border-teal-100 shadow-inner">
                    <div class="flex justify-center gap-2 mb-4" id="star-container"></div>
                    <p class="font-black text-teal-800 uppercase tracking-widest mb-1 text-sm">Jumlah Markah</p>
                    <p class="text-6xl font-black text-teal-600 drop-shadow-sm" id="final-score">0</p>
                </div>
                <div class="flex flex-col gap-4">
                    <button onclick="saveAndExit(event)" class="btn-3d w-full bg-[#10B981] border-[#059669] text-white text-xl md:text-2xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3 hover:bg-[#059669]">
                        Tahap Seterusnya <i class="fa-solid fa-forward-step"></i>
                    </button>
                    <button onclick="restartGame()" class="btn-3d w-full bg-slate-200 border-slate-300 text-slate-700 text-lg md:text-xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3 hover:bg-slate-300">
                        Main Semula <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <audio id="audio-player" preload="auto"></audio>

    <style>
        .font-lexend { font-family: 'Lexend', sans-serif; }
        * { user-select: none; -webkit-user-select: none; touch-action: manipulation; }
        .btn-3d { position: relative; display: inline-flex; align-items: center; justify-content: center; transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; border-bottom-width: 6px; border-top-width: 2px; border-left-width: 2px; border-right-width: 2px; border-style: solid; }
        .btn-3d:not(:disabled):active { transform: translateY(4px); border-bottom-width: 2px; margin-top: 4px; }
        .option-btn { width: 100px; height: 110px; font-size: 4rem; background: white; border-radius: 1.5rem; font-weight: 800; }
        
        /* Warna untuk m, n, w, u */
        .btn-color-m { border-color: #6366f1; border-top-color: #e0e7ff; color: #3730a3; }
        .btn-color-n { border-color: #f59e0b; border-top-color: #fef3c7; color: #92400e; }
        .btn-color-w { border-color: #ec4899; border-top-color: #fdf2f8; color: #9d174d; }
        .btn-color-u { border-color: #14b8a6; border-top-color: #f0fdfa; color: #0f766e; }

        .btn-3d:not(:disabled):hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        @media (min-width: 768px) { .option-btn { width: 140px; height: 140px; font-size: 6rem; border-radius: 2rem; } }
        .slot-filled { background-color: #ccfbf1 !important; border-color: #14b8a6 !important; transform: scale(1.1); }
        .slot-error { background-color: #fee2e2 !important; border-color: #ef4444 !important; animation: shake 0.4s; }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 75% { transform: translateX(10px); } }
        .sparkle-effect { position: absolute; font-size: 4rem; pointer-events: none; animation: sparkle-anim 0.8s forwards; z-index: 100; }
        @keyframes sparkle-anim { 0% { transform: scale(0); opacity: 1; } 100% { transform: scale(2); opacity: 0; } }
        .animate-bounce-in { animation: bounce-in 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
        @keyframes bounce-in { 0% { transform: scale(0.5); opacity: 0; } 60% { transform: scale(1.1); opacity: 1; } 100% { transform: scale(1); opacity: 1; } }
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
        const gameData = [
            { word: 'meja', letter: 'm', img: '{{ asset('images/games/meja.png') }}', audio: '{{ asset('audio/Meja2.mp3') }}' },
            { word: 'nasi', letter: 'n', img: '{{ asset('images/games/nasi.png') }}', audio: '{{ asset('audio/Nasi.mp3') }}' },
            { word: 'wanita', letter: 'w', img: '{{ asset('images/games/wanita.png') }}', audio: '{{ asset('audio/Wanita.mp3') }}' },
            { word: 'ubat', letter: 'u', img: '{{ asset('images/games/ubat.png') }}', audio: '{{ asset('audio/Ubat.mp3') }}' },
            { word: 'nenek', letter: 'n', img: '{{ asset('images/games/nenek.png') }}', audio: '{{ asset('audio/Nenek.mp3') }}' },
            { word: 'ubat', letter: 'u', img: '{{ asset('images/games/ubat.png') }}', audio: '{{ asset('audio/Ubat.mp3') }}' },
            { word: 'muka', letter: 'm', img: '{{ asset('images/games/muka.png') }}', audio: '{{ asset('audio/Muka.mp3') }}' },
            { word: 'nenas', letter: 'n', img: '{{ asset('images/games/nenas.png') }}', audio: '{{ asset('audio/Nenas.mp3') }}' },
            { word: 'wang', letter: 'w', img: '{{ asset('images/games/wang.png') }}', audio: '{{ asset('audio/Wang.mp3') }}' },
            { word: 'ular', letter: 'u', img: '{{ asset('images/games/ular.png') }}', audio: '{{ asset('audio/Ular.mp3') }}' }
        ];

        const targetLetters = ['m', 'n', 'w', 'u'];
        const MAX_ROUNDS = 10;
        let shuffledData = [];
        let currentRound = 0;
        let score = 0;
        let isWaiting = false;
        let currentItem = {};
        let sessionMistakes = {}; 

        function startGame() {
            document.getElementById('start-overlay').classList.add('hidden');
            document.getElementById('win-overlay').classList.add('hidden');
            shuffledData = [...gameData].sort(() => Math.random() - 0.5).slice(0, MAX_ROUNDS);
            currentRound = 0;
            score = 0;
            sessionMistakes = {};
            loadQuestion();
        }

        function loadQuestion() {
            if (currentRound >= MAX_ROUNDS) { endGame(); return; }
            isWaiting = false;
            currentItem = shuffledData[currentRound];
            document.getElementById('question-img').src = currentItem.img;
            document.getElementById('round-display').innerText = `${currentRound + 1}/${MAX_ROUNDS}`;
            document.getElementById('score-display').innerText = score;
            document.getElementById('word-overlay').classList.replace('opacity-100', 'opacity-0');
            const slotText = document.getElementById('slot-text');
            slotText.innerText = '?';
            slotText.style.opacity = '0.2';
            document.getElementById('answer-slot').className = 'w-24 h-24 md:w-32 md:h-32 bg-teal-50 border-8 border-teal-200 rounded-2xl flex items-center justify-center transition-all relative';
            generateOptions();
            setTimeout(playWordAudio, 800);
        }

        function generateOptions() {
            const container = document.getElementById('options-container');
            container.innerHTML = '';
            [...targetLetters].sort(() => Math.random() - 0.5).forEach(letter => {
                const btn = document.createElement('button');
                btn.className = `btn-3d option-btn flex items-center justify-center btn-color-${letter}`;
                btn.innerText = letter;
                btn.onclick = () => checkAnswer(letter, btn);
                container.appendChild(btn);
            });
        }

        function checkAnswer(selected, btnElement) {
            if (isWaiting) return;
            isWaiting = true;
            document.querySelectorAll('.option-btn').forEach(btn => btn.disabled = true);
            const slotText = document.getElementById('slot-text');
            const slot = document.getElementById('answer-slot');
            slotText.innerText = selected;
            slotText.style.opacity = '1';

            if (selected === currentItem.letter) {
                score++;
                slot.classList.add('slot-filled');
                correctSound.play();
                showFullWord();
                setTimeout(() => { currentRound++; loadQuestion(); }, 2500);
            } else {
                slot.classList.add('slot-error');
                wrongSound.play();
                let mistakeKey = `${currentItem.word}_${selected}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                setTimeout(() => { currentRound++; loadQuestion(); }, 1500);
            }
        }

        function showFullWord() {
            const wordOverlay = document.getElementById('word-overlay');
            document.getElementById('word-text').innerText = currentItem.word.toUpperCase();
            wordOverlay.classList.replace('opacity-0', 'opacity-100');
            setTimeout(playWordAudio, 600);
        }

        function playWordAudio() {
            audioPlayer.src = currentItem.audio;
            audioPlayer.play();
        }

        function endGame() {
            document.getElementById('final-score').innerText = score;
            renderStars(score >= 8 ? 3 : (score >= 5 ? 2 : 1));
            document.getElementById('win-overlay').classList.remove('hidden');
            winSound.play();
        }

        function renderStars(count) {
            const container = document.getElementById('star-container');
            container.innerHTML = '';
            for (let i = 0; i < 3; i++) {
                const color = i < count ? 'text-yellow-400' : 'text-slate-300';
                container.innerHTML += `<i class="fa-solid fa-star ${color} text-5xl mx-1"></i>`;
            }
        }

        function restartGame() { startGame(); }

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
                    stars_earned: score >= 8 ? 3 : (score >= 5 ? 2 : 1),
                    is_completed: true,
                    mistakes: sessionMistakes 
                })
            }).then(r => r.json()).then(data => {
                if (data.success) window.location.href = window.gameConfig.nextLevelUrl;
            });
        }
    </script>
@endsection