@extends('layouts.game')

@section('game-title', 'Tahap 8: Kenali Huruf b,d,p,q')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 8 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-gradient-to-b from-indigo-50 to-purple-100 transition-all duration-300">

        <div class="w-full relative z-10 p-4 shrink-0 flex flex-wrap justify-between items-center px-2 md:px-6 gap-y-3">
            <div class="flex items-center bg-white border-4 border-indigo-200 rounded-2xl px-4 md:px-6 py-2 shadow-md">
                <span class="text-indigo-800 font-black text-sm md:text-xl tracking-wider font-lexend">
                    <i class="fa-solid fa-spell-check mr-2 text-indigo-500"></i> Kenali b, d, p dan q
                </span>
            </div>

            <div class="flex gap-3 md:gap-4 font-lexend">
                <div
                    class="flex flex-col items-center bg-green-50 border-4 border-green-200 rounded-2xl px-4 md:px-5 py-1 shadow-sm">
                    <span class="text-green-600 font-bold text-xs uppercase">Markah</span>
                    <span id="score-display" class="text-green-700 font-black text-xl md:text-2xl">0</span>
                </div>
                <div
                    class="flex flex-col items-center bg-orange-50 border-4 border-orange-200 rounded-2xl px-4 md:px-5 py-1 shadow-sm">
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
                class="bg-white rounded-[3rem] shadow-2xl border-8 border-indigo-50 p-6 md:p-8 mb-8 md:mb-12 max-w-2xl w-full flex flex-col items-center transition-all duration-500 scale-100 relative">
                <div class="text-center mb-6 flex flex-col items-center">
                    <h2 class="text-xl md:text-2xl font-black text-slate-700 font-lexend mb-4">Dengar dan pilih huruf awalan
                        yang betul</h2>
                    <button onclick="playWordAudio()"
                        class="w-16 h-16 md:w-20 md:h-20 bg-indigo-500 text-white rounded-full flex items-center justify-center text-2xl md:text-3xl border-4 md:border-8 border-indigo-200 shadow-lg hover:bg-indigo-600 active:translate-y-1 transition-all z-20">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-4 md:gap-8 mb-4 w-full relative">
                    <div
                        class="w-52 h-52 md:w-52 md:h-52 bg-slate-50 rounded-3xl border-4 border-dashed border-slate-300 flex items-center justify-center relative overflow-hidden">

                        <img id="question-img" src=""
                            class="w-[100%] h-[100%] object-contain drop-shadow-md transition-opacity">

                        <div id="word-overlay"
                            class="absolute inset-0 bg-indigo-600 flex items-center justify-center opacity-0 transition-opacity duration-300 pointer-events-none">
                            <span id="word-text"
                                class="text-white font-black text-2xl md:text-4xl uppercase font-lexend tracking-widest"></span>
                        </div>
                    </div>

                    <div class="text-5xl md:text-8xl font-black text-indigo-200 font-lexend select-none hidden md:block">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>

                    <div id="answer-slot"
                        class="w-24 h-24 md:w-32 md:h-32 bg-indigo-50 border-8 border-indigo-200 rounded-2xl flex items-center justify-center transition-all relative">
                        <span id="slot-text"
                            class="text-6xl md:text-7xl font-black text-indigo-600 opacity-20 font-lexend">?</span>
                    </div>
                </div>
            </div>

            <div class="w-full max-w-4xl bg-white/60 backdrop-blur-md p-6 md:p-8 rounded-t-[4rem] border-t-8 border-white shadow-[0_-10px_30px_rgba(0,0,0,0.05)] flex flex-wrap justify-center gap-8 md:gap-12"
                id="options-container">
            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-indigo-900/90 z-50 flex flex-col justify-center items-center backdrop-blur-lg px-4">
            <div
                class="bg-white p-8 md:p-12 rounded-[3rem] shadow-2xl text-center max-w-md w-full border-8 border-indigo-200">
                <h1 class="text-3xl md:text-4xl font-black text-indigo-600 mb-8 font-lexend">Cabaran b dan d</h1>

                <div class="flex flex-col gap-4 text-left mx-auto w-full mb-8">
                    <div class="flex items-center gap-4 bg-indigo-50 p-4 rounded-xl border-2 border-indigo-100">
                        <div
                            class="w-12 h-12 bg-indigo-200 text-indigo-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-ear-listen"></i>
                        </div>
                        <p class="text-slate-600 font-bold text-lg leading-tight">Dengar bunyi perkataan dengan teliti.</p>
                    </div>
                    <div class="flex items-center gap-4 bg-purple-50 p-4 rounded-xl border-2 border-purple-100">
                        <div
                            class="w-12 h-12 bg-purple-200 text-purple-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <p class="text-slate-600 font-bold text-lg leading-tight">Pilih huruf awalan yang betul<b> <br>b atau
                                d  <br>p atau q</b> </p>
                    </div>
                </div>

                <button onclick="startGame()"
                    class="btn-3d bg-indigo-500 border-indigo-700 text-white w-full text-2xl font-bold py-4 rounded-3xl flex justify-center items-center gap-3">
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
                        Main Semula <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <audio id="audio-player" preload="auto"></audio>

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
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        .option-btn {
            width: 100px;
            height: 110px;
            font-size: 4rem;
            background: white;
            border-radius: 1.5rem;
            font-weight: 800;
            font-family: 'Lexend', sans-serif;
        }

        /* Warna khusus untuk 'b' (Biru) */
        .btn-color-b {
            border-color: #3b82f6;
            border-top-color: #dbeafe;
            border-left-color: #dbeafe;
            color: #1e40af;
        }

        .btn-color-b:not(:disabled):hover {
            background: #eff6ff;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2);
        }

        /* Warna khusus untuk 'd' (Oren/Kuning) */
        .btn-color-d {
            border-color: #f59e0b;
            border-top-color: #fef3c7;
            border-left-color: #fef3c7;
            color: #92400e;
        }

        .btn-color-d:not(:disabled):hover {
            background: #fffbeb;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.2);
        }

        /* Warna khusus untuk 'p' (Pink/Merah Jambu) */
        .btn-color-p {
            border-color: #ec4899;
            border-top-color: #fdf2f8;
            border-left-color: #fdf2f8;
            color: #9d174d;
        }

        .btn-color-p:not(:disabled):hover {
            background: #fdf2f8;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(236, 72, 153, 0.2);
        }

        /* Warna khusus untuk 'q' (Teal/Hijau Kebiruan) */
        .btn-color-q {
            border-color: #14b8a6;
            border-top-color: #f0fdfa;
            border-left-color: #f0fdfa;
            color: #0f766e;
        }

        .btn-color-q:not(:disabled):hover {
            background: #f0fdfa;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(20, 184, 166, 0.2);
        }


        @media (min-width: 768px) {
            .option-btn {
                width: 140px;
                height: 140px;
                font-size: 6rem;
                border-radius: 2rem;
            }
        }

        .slot-filled {
            background-color: #e0e7ff !important;
            border-color: #6366f1 !important;
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
        }

        .slot-error {
            background-color: #fee2e2 !important;
            border-color: #ef4444 !important;
            animation: shake 0.4s;
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

        .sparkle-effect {
            position: absolute;
            font-size: 4rem;
            pointer-events: none;
            animation: sparkle-anim 0.8s forwards;
            z-index: 100;
        }

        @keyframes sparkle-anim {
            0% {
                transform: scale(0) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: scale(2) rotate(45deg);
                opacity: 0;
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
            animation: bounce-in 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
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
                word: 'bola',
                letter: 'b',
                img: '{{ asset('images/games/bola.png') }}',
                audio: '{{ asset('audio/Bola.mp3') }}'
            },
            {
                word: 'dadu',
                letter: 'd',
                img: '{{ asset('images/games/dadu.png') }}',
                audio: '{{ asset('audio/Dadu.mp3') }}'
            },
            {
                word: 'baju',
                letter: 'b',
                img: '{{ asset('images/games/baju.png') }}',
                audio: '{{ asset('audio/Baju2.mp3') }}'
            },
            {
                word: 'daun',
                letter: 'd',
                img: '{{ asset('images/games/daun.png') }}',
                audio: '{{ asset('audio/Daun.mp3') }}'
            },
            {
                word: 'paku',
                letter: 'p',
                img: '{{ asset('images/games/paku.png') }}',
                audio: '{{ asset('audio/Paku2.mp3') }}'
            }, // Baru
            {
                word: 'pintu',
                letter: 'p',
                img: '{{ asset('images/games/pintu.png') }}',
                audio: '{{ asset('audio/Pintu.mp3') }}'
            }, // Baru
            {
                word: 'qari',
                letter: 'q',
                img: '{{ asset('images/games/qari.png') }}',
                audio: '{{ asset('audio/Qari.mp3') }}'
            }, // Baru
            {
                word: 'qiam',
                letter: 'q',
                img: '{{ asset('images/games/qiam.png') }}',
                audio: '{{ asset('audio/Qiam.mp3') }}'
            }, // Baru
            {
                word: 'buku',
                letter: 'b',
                img: '{{ asset('images/games/buku.png') }}',
                audio: '{{ asset('audio/Buku2.mp3') }}'
            },
            {
                word: 'duri',
                letter: 'd',
                img: '{{ asset('images/games/duri.png') }}',
                audio: '{{ asset('audio/Duri.mp3') }}'
            },
            {
                word: 'peta',
                letter: 'p',
                img: '{{ asset('images/games/peta.png') }}',
                audio: '{{ asset('audio/Peta.mp3') }}'
            }, // Baru
            {
                word: 'quokka',
                letter: 'q',
                img: '{{ asset('images/games/quokka.png') }}',
                audio: '{{ asset('audio/Quokka.mp3') }}'
            } // Baru
        ];

        const targetLetters = ['b', 'd', 'p', 'q'];
        const MAX_ROUNDS = 10;
        let shuffledData = [];
        let currentRound = 0;
        let score = 0;
        let isWaiting = false;
        let currentItem = {};
        let sessionMistakes = {};

        document.addEventListener('DOMContentLoaded', () => {
            gameData.forEach(data => {
                const img = new Image();
                img.src = data.img;
            });
        });

        function playPhonicsTTS(text, rate = 0.7) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ms-MY';
            utterance.rate = rate;
            window.speechSynthesis.speak(utterance);
        }

        function playWordAudio() {
            if (isWaiting) return;

            audioPlayer.src = currentItem.audio;
            audioPlayer.currentTime = 0;
            audioPlayer.play();
        }

        function playFeedback(type) {
            if (type === 'wrong') wrongSound.play();
            if (type === 'win') winSound.play();
        }

        function startGame() {
            document.getElementById('start-overlay').classList.add('hidden');
            document.getElementById('win-overlay').classList.add('hidden');

            shuffledData = [...gameData].sort(() => Math.random() - 0.5);
            if (shuffledData.length > MAX_ROUNDS) shuffledData.length = MAX_ROUNDS;

            currentRound = 0;
            score = 0;
            sessionMistakes = {};
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
            currentItem = shuffledData[currentRound];

            document.getElementById('question-img').src = currentItem.img;
            document.getElementById('round-display').innerText = `${currentRound + 1}/${MAX_ROUNDS}`;
            document.getElementById('score-display').innerText = score;

            // Sembunyikan overlay perkataan
            const wordOverlay = document.getElementById('word-overlay');
            wordOverlay.classList.replace('opacity-100', 'opacity-0');

            const slotText = document.getElementById('slot-text');
            const slot = document.getElementById('answer-slot');
            slotText.innerText = '?';
            slotText.style.opacity = '0.2';
            slot.className =
                'w-24 h-24 md:w-32 md:h-32 bg-indigo-50 border-8 border-indigo-200 rounded-2xl flex items-center justify-center transition-all relative';

            // Animasi masuk soalan
            const mainCard = document.getElementById('main-card');
            mainCard.style.transform = 'scale(0.9)';
            mainCard.style.opacity = '0';
            setTimeout(() => {
                mainCard.style.transform = 'scale(1)';
                mainCard.style.opacity = '1';
            }, 50);

            generateOptions();
            setTimeout(playWordAudio, 800);
        }

        function generateOptions() {
            const container = document.getElementById('options-container');
            container.innerHTML = '';

            // Rawakkan susunan jawapan b, d, p dan q
            const shuffledOptions = [...targetLetters].sort(() => Math.random() - 0.5);

            shuffledOptions.forEach(letter => {
                const btn = document.createElement('button');

                // Logik pemilihan warna berdasarkan huruf
                let colorClass = '';
                if (letter === 'b') colorClass = 'btn-color-b';
                else if (letter === 'd') colorClass = 'btn-color-d';
                else if (letter === 'p') colorClass = 'btn-color-p';
                else if (letter === 'q') colorClass = 'btn-color-q';

                btn.className = `btn-3d option-btn flex items-center justify-center ${colorClass}`;
                btn.innerText = letter;
                btn.onclick = () => checkAnswer(letter, btn);
                container.appendChild(btn);
            });
        }

        function checkAnswer(selected, btnElement) {
            if (isWaiting) return;
            isWaiting = true;

            const allBtns = document.querySelectorAll('.option-btn');
            allBtns.forEach(btn => btn.disabled = true);

            const slotText = document.getElementById('slot-text');
            const slot = document.getElementById('answer-slot');

            slotText.innerText = selected;
            slotText.style.opacity = '1';

            if (selected === currentItem.letter) {
                // JAWAPAN BETUL: Tambah 1 markah
                score++;
                document.getElementById('score-display').innerText = score;

                slot.classList.add('slot-filled');
                btnElement.style.backgroundColor = '#4ADE80';
                btnElement.style.borderColor = '#16A34A';
                btnElement.style.color = 'white';

                correctSound.pause();
                correctSound.currentTime = 0;
                correctSound.play();
                createSparkle(slot);

                showFullWord();

                setTimeout(() => {
                    currentRound++;
                    loadQuestion();
                }, 2500); // Masa diperpanjang sedikit untuk baca perkataan penuh
            } else {
                slot.classList.add('slot-error');
                btnElement.style.backgroundColor = '#FEE2E2';
                btnElement.style.borderColor = '#EF4444';
                btnElement.style.color = '#B91C1C';

                wrongSound.pause();
                wrongSound.currentTime = 0;
                wrongSound.play();

                let mistakeKey = `${currentItem.word}_${selected}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;

                setTimeout(() => {
                    slot.classList.remove('slot-error');
                    // Terus bergerak ke soalan seterusnya tanpa bagi peluang kedua
                    currentRound++;
                    loadQuestion();
                }, 1500);
            }
        }

        function showFullWord() {
            const wordOverlay = document.getElementById('word-overlay');
            const wordText = document.getElementById('word-text');

            // Masukkan ejaan (besarkan huruf)
            wordText.innerText = currentItem.word.toUpperCase();

            // Paparkan overlay
            wordOverlay.classList.replace('opacity-0', 'opacity-100');

            // Mainkan sebutan perkataan penuh
            setTimeout(() => {
                audioPlayer.src = currentItem.audio;
                audioPlayer.currentTime = 0;
                audioPlayer.play();
            }, 600);
        }

        function createSparkle(parent) {
            const sparkle = document.createElement('div');
            sparkle.classList.add('sparkle-effect');
            sparkle.innerHTML = '<i class="fa-solid fa-star text-yellow-400 drop-shadow-md"></i>';
            const rect = parent.getBoundingClientRect();
            sparkle.style.left = (rect.width / 2 - 20) + 'px';
            sparkle.style.top = (rect.height / 2 - 20) + 'px';
            parent.appendChild(sparkle);
            setTimeout(() => sparkle.remove(), 800);
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

            document.getElementById('win-overlay').classList.remove('hidden');
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
