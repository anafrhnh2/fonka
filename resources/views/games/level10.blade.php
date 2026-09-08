@extends('layouts.game')

@section('game-title', 'Tahap 10: Teka Bunyi Awal')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 5 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#F0FDF4] transition-all duration-300">

        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0">

                <div class="flex items-center bg-green-50 border-4 border-green-200 rounded-full px-4 py-2 shadow-sm">
                    <span class="text-green-800 font-black text-sm md:text-xl uppercase tracking-wider font-lexend">
                        <i class="fa-solid fa-ear-listen mr-2"></i> Teka Bunyi Awal
                    </span>
                </div>

                <div class="flex items-center gap-3 md:gap-5 font-lexend">

                    <div
                        class="flex flex-col items-center bg-green-50 border-4 border-green-200 rounded-2xl px-4 py-1 shadow-sm">
                        <span class="text-green-600 font-bold text-xs md:text-sm uppercase tracking-wider">Markah</span>
                        <div class="flex items-center gap-1">
                            <span id="score-display" class="text-green-700 font-black text-xl md:text-2xl">0</span>
                        </div>
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
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-[#E0E7FF] w-full flex flex-col justify-center items-center bg-green-50/50">

                <div
                    class="text-center mb-6 z-10 bg-white/80 px-6 py-2 rounded-full border-2 border-white shadow-sm backdrop-blur-sm mt-4">
                    <h2 class="text-xl md:text-3xl font-bold text-gray-700 tracking-wide font-lexend responsive-text">Pilih
                        huruf untuk bunyi awal!</h2>
                </div>

                <div id="question-container"
                    class="bg-white p-4 md:p-6 rounded-[2.5rem] border-[6px] border-[#FEF08A] shadow-xl flex flex-col items-center justify-center w-64 h-64 md:w-72 md:h-72 responsive-box relative mb-10 transition-transform z-10">

                    <button id="btn-replay" onclick="playWordAudio()"
                        class="absolute -top-5 -right-5 w-14 h-14 md:w-16 md:h-16 bg-amber-400 text-white rounded-full flex items-center justify-center text-2xl md:text-3xl border-4 border-amber-200 shadow-[0_4px_0_#D97706] active:translate-y-1 active:shadow-none transition-all z-20">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>

                    <img id="question-img" src="" alt="Soalan"
                        class="w-[80%] h-[80%] object-contain pointer-events-none mb-2 transition-opacity">
                    <span id="question-word"
                        class="font-black text-purple-600 text-xl md:text-5xl font-lexend uppercase tracking-widest mt-1"></span>

                    <div id="feedback-badge"
                        class="absolute inset-0 flex items-center justify-center bg-white/90 rounded-[2rem] opacity-0 pointer-events-none transition-opacity duration-300 z-30">
                        <span id="feedback-icon" class="text-8xl drop-shadow-lg">
                            <i class="fa-solid fa-circle-check text-green-500"></i>
                        </span>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center gap-4 md:gap-8 w-full px-4 z-10" id="options-container">
                </div>

            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-[#0B172A]/80 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">
            <div
                class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full flex flex-col transform transition-all scale-100 border-[6px] border-green-200 font-lexend">
                <h1 class="text-3xl md:text-4xl font-black text-green-600 mb-6 tracking-wide">Teka Bunyi Awal</h1>
                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10 flex-1">
                    <div class="flex items-center gap-4 bg-amber-50 p-4 rounded-xl border-2 border-amber-100 shadow-sm">
                        <div
                            class="w-12 h-12 bg-amber-200 text-amber-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-ear-listen"></i>
                        </div>
                        <p class="text-gray-600 font-bold text-lg md:text-xl leading-tight">Dengar bunyi awalan gambar.</p>
                    </div>
                    <div class="flex items-center gap-4 bg-blue-50 p-4 rounded-xl border-2 border-blue-100 shadow-sm">
                        <div
                            class="w-12 h-12 bg-blue-200 text-blue-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-hand-pointer"></i>
                        </div>
                        <p class="text-gray-600 font-bold text-lg md:text-xl leading-tight">Pilih huruf awalan yang betul.
                            <b>Hanya 1 peluang!</b>
                        </p>
                    </div>
                </div>
                <button onclick="startGame()"
                    class="btn-3d bg-green-500 border-green-700 text-white w-full text-2xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3">
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
                        Main Semula <i class="fa-solid fa-rotate-right"> </i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <audio id="audio-player" preload="auto"></audio>
    <audio id="sfx-hover" src="https://images.mixkit.com/sfx/preview/mixkit-pop-click-3104.mp3"></audio>

    <style>
        .font-lexend {
            font-family: 'Lexend', sans-serif;
        }

        * {
            user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            touch-action: manipulation;
        }

        img {
            pointer-events: none;
            user-drag: none;
            -webkit-user-drag: none;
        }

        .btn-3d {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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

        .btn-3d:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* GAYA KOTAK JAWAPAN DINAMIK */
        .ans-btn {
            background-color: #E0F2FE;
            border-color: #0284C7;
            color: #0C4A6E;
            border-top-color: #F0F9FF;
            border-left-color: #F0F9FF;
            width: 90px;
            height: 90px;
            font-size: 3rem;
            font-weight: 900;
            border-radius: 1.5rem;
        }

        @media (min-width: 768px) {
            .ans-btn {
                width: 120px;
                height: 120px;
                font-size: 4rem;
            }
        }

        .ans-btn:not(:disabled):hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 10px 20px rgba(2, 132, 199, 0.2);
            background-color: #BAE6FD;
        }

        .wrong-choice {
            opacity: 0.3 !important;
            filter: grayscale(100%);
            background-color: #F1F5F9 !important;
            border-color: #CBD5E1 !important;
        }

        .correct-choice {
            transform: scale(1.1) !important;
            background-color: #4ADE80 !important;
            border-color: #16A34A !important;
            color: white !important;
            box-shadow: 0 0 20px rgba(74, 222, 128, 0.6) !important;
        }

        /* KELAS RESPONSIF UNTUK FULLSCREEN */
        .is-fullscreen .responsive-box {
            width: 350px !important;
            height: 350px !important;
            border-width: 10px;
        }

        .is-fullscreen .responsive-text {
            font-size: 2.5rem !important;
        }

        .is-fullscreen .ans-btn {
            width: 150px !important;
            height: 150px !important;
            font-size: 5rem !important;
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
            z-index: 50;
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

        // --- DATA SOALAN GABUNGAN VOKAL & KONSONAN ---
        const gameData = [{
                word: 'Ayam',
                ans: 'A',
                soundTarget: 'aaaaa',
                audio: '{{ asset('audio/Ayam.mp3') }}',
                distractors: ['I', 'U'],
                img: '{{ asset('images/games/ayam.png') }}'
            },
            {
                word: 'Api',
                ans: 'A',
                soundTarget: 'aaaaa',
                audio: '{{ asset('audio/Api.mp3') }}',
                distractors: ['E', 'O'],
                img: '{{ asset('images/games/api.png') }}'
            },
            {
                word: 'Ikan',
                ans: 'I',
                soundTarget: 'iiiii',
                audio: '{{ asset('audio/Ikan.mp3') }}',
                distractors: ['A', 'U'],
                img: '{{ asset('images/games/ikan.png') }}'
            },
            {
                word: 'Obor',
                ans: 'O',
                soundTarget: 'ooooo',
                audio: '{{ asset('audio/Obor.mp3') }}',
                distractors: ['A', 'E'],
                img: '{{ asset('images/games/obor.png') }}'
            },
            {
                word: 'Ular',
                ans: 'U',
                soundTarget: 'uuuuu',
                audio: '{{ asset('audio/Ular.mp3') }}',
                distractors: ['I', 'O'],
                img: '{{ asset('images/games/ular.png') }}'
            },
            {
                word: 'Buku',
                ans: 'B',
                soundTarget: 'buh',
                audio: '{{ asset('audio/Buku2.mp3') }}',
                distractors: ['P', 'D'],
                img: '{{ asset('images/games/buku.png') }}'
            },
            {
                word: 'Bola',
                ans: 'B',
                soundTarget: 'buh',
                audio: '{{ asset('audio/Bola.mp3') }}',
                distractors: ['M', 'P'],
                img: '{{ asset('images/games/bola.png') }}'
            },
            {
                word: 'Meja',
                ans: 'M',
                soundTarget: 'mmm',
                audio: '{{ asset('audio/Meja2.mp3') }}',
                distractors: ['N', 'B'],
                img: '{{ asset('images/games/meja.png') }}'
            },
            {
                word: 'Susu',
                ans: 'S',
                soundTarget: 'sss',
                audio: '{{ asset('audio/Susu.mp3') }}',
                distractors: ['C', 'T'],
                img: '{{ asset('images/games/susu.png') }}'
            },
            {
                word: 'Tali',
                ans: 'T',
                soundTarget: 'tuh',
                audio: '{{ asset('audio/Tali2.mp3') }}',
                distractors: ['D', 'K'],
                img: '{{ asset('images/games/tali.png') }}'
            },
            {
                word: 'Gajah',
                ans: 'G',
                soundTarget: 'guh',
                audio: '{{ asset('audio/Gajah.mp3') }}',
                distractors: ['K', 'J'],
                img: '{{ asset('images/games/gajah.png') }}'
            },
            {
                word: 'Daun',
                ans: 'D',
                soundTarget: 'duh',
                audio: '{{ asset('audio/Daun.mp3') }}',
                distractors: ['B', 'P'],
                img: '{{ asset('images/games/daun.png') }}'
            },
            {
                word: 'Cawan',
                ans: 'C',
                soundTarget: 'chuh',
                audio: '{{ asset('audio/Cawan.mp3') }}',
                distractors: ['S', 'J'],
                img: '{{ asset('images/games/cawan.png') }}'
            },
            {
                word: 'Paku',
                ans: 'P',
                soundTarget: 'puh',
                audio: '{{ asset('audio/Paku2.mp3') }}',
                distractors: ['B', 'T'],
                img: '{{ asset('images/games/paku.png') }}'
            },
            {
                word: 'Kucing',
                ans: 'K',
                soundTarget: 'kuh',
                audio: '{{ asset('audio/Kucing.mp3') }}',
                distractors: ['G', 'C'],
                img: '{{ asset('images/games/kucing.png') }}'
            }
        ];

        let shuffledData = [];
        let currentIndex = 0;
        let score = 0;
        const MAX_ROUNDS = 10;
        let isWaiting = false;
        let sessionMistakes = {};

        const questionContainer = document.getElementById('question-container');
        const questionImg = document.getElementById('question-img');
        const questionWord = document.getElementById('question-word');
        const optionsContainer = document.getElementById('options-container');
        const scoreDisplay = document.getElementById('score-display');
        const roundDisplay = document.getElementById('round-display');
        const feedbackBadge = document.getElementById('feedback-badge');
        const feedbackIcon = document.getElementById('feedback-icon');
        const winOverlay = document.getElementById('win-overlay');

        document.addEventListener('DOMContentLoaded', () => {
            gameData.forEach(data => {
                if (data.img.includes('{{ asset')) data.img =
                    `https://placehold.co/200x200/4ADE80/FFF?text=${data.word}`;
                const img = new Image();
                img.src = data.img;
            });
        });

        // --- SISTEM AUDIO (TTS Fonik) ---
        function playPhonicsTTS(text, rate = 0.8) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ms-MY';
            utterance.rate = rate;
            window.speechSynthesis.speak(utterance);
        }

        function playWordAudio() {
            if (isWaiting) return;

            // Ambil data dari array shuffledData berdasarkan index semasa
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
            winOverlay.classList.add('hidden'); // Sembunyi win overlay jika restart

            shuffledData = [...gameData].sort(() => Math.random() - 0.5);
            if (shuffledData.length > MAX_ROUNDS) shuffledData.length = MAX_ROUNDS;

            score = 0;
            currentIndex = 0;
            sessionMistakes = {};
            updateUI();
            loadQuestion();
        }

        // FUNGSI MAIN SEMULA
        function restartGame() {
            startGame();
        }

        function updateUI() {
            scoreDisplay.innerText = score;
            roundDisplay.innerText = `${currentIndex + 1}/${MAX_ROUNDS}`;
        }

        function loadQuestion() {
            if (currentIndex >= MAX_ROUNDS) {
                endGame();
                return;
            }

            isWaiting = false;
            const currentData = shuffledData[currentIndex];

            questionImg.src = currentData.img;
            questionWord.innerText = "_" + currentData.word.substring(1).toUpperCase();

            updateUI();

            feedbackBadge.classList.replace('opacity-100', 'opacity-0');
            questionContainer.style.borderColor = '#FEF08A';

            generateOptions();

            questionContainer.style.transform = 'scale(0.8)';
            setTimeout(() => {
                questionContainer.style.transform = 'scale(1)';
            }, 50);

            setTimeout(() => playWordAudio(), 600);
        }

        // --- PENJANAAN BUTANG JAWAPAN DINAMIK ---
        function generateOptions() {
            optionsContainer.innerHTML = '';
            const currentData = shuffledData[currentIndex];

            let options = [currentData.ans, ...currentData.distractors].sort(() => Math.random() - 0.5);

            options.forEach(letter => {
                const btn = document.createElement('button');
                btn.className = 'btn-3d ans-btn font-lexend transition-all';
                btn.innerText = letter;

                btn.addEventListener('mouseenter', () => {
                    if (!btn.disabled && !isWaiting) {
                        hoverSound.currentTime = 0;
                        hoverSound.play().catch(e => {});
                    }
                });

                btn.onclick = () => checkAnswer(letter, btn);
                optionsContainer.appendChild(btn);
            });
        }

        function checkAnswer(selectedLetter, btnElement) {
            if (isWaiting) return;
            isWaiting = true;

            const allBtns = document.querySelectorAll('.ans-btn');
            allBtns.forEach(btn => btn.disabled = true);

            const currentData = shuffledData[currentIndex];

            if (selectedLetter === currentData.ans) {
                // BETUL: Beri 1 markah sahaja
                score += 1;
                updateUI();
                playFeedback('correct');

                questionContainer.style.borderColor = '#22C55E';
                btnElement.classList.add('correct-choice');
                correctSound.pause();
                correctSound.currentTime = 0;
                correctSound.play();
                createSparkle(questionContainer);

                questionWord.innerText = currentData.word.toUpperCase();
                questionWord.classList.add('text-green-600');

                feedbackIcon.innerHTML = '<i class="fa-solid fa-circle-check text-green-500"></i>';
                feedbackBadge.classList.replace('opacity-0', 'opacity-100');

                setTimeout(() => {
                    questionWord.classList.remove('text-green-600');
                    currentIndex++;
                    loadQuestion();
                }, 2000);

            } else {
                // SALAH
                wrongSound.pause();
                wrongSound.currentTime = 0;
                wrongSound.play();

                questionContainer.style.borderColor = '#EF4444';
                questionContainer.classList.add('shake');
                btnElement.classList.add('wrong-choice');
                let mistakeKey = `${currentData.word}_${selectedLetter}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                allBtns.forEach(btn => {
                    if (btn.innerText === currentData.ans) {
                        btn.classList.add('correct-choice');
                    }
                });

                // Gunakan FontAwesome Icon (❌)
                feedbackIcon.innerHTML = '<i class="fa-solid fa-circle-xmark text-red-500"></i>';
                feedbackBadge.classList.replace('opacity-0', 'opacity-100');

                setTimeout(() => {
                    questionContainer.classList.remove('shake');
                    currentIndex++;
                    loadQuestion();
                }, 2500);
            }
        }

        function createSparkle(parent) {
            const sparkle = document.createElement('div');
            sparkle.classList.add('sparkle-effect');
            // Guna FontAwesome Star Icon
            sparkle.innerHTML = '<i class="fa-solid fa-star text-yellow-400 drop-shadow-md"></i>';

            const rect = parent.getBoundingClientRect();
            sparkle.style.left = (rect.width / 2 - 40) + 'px';
            sparkle.style.top = (rect.height / 2 - 40) + 'px';

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

            winOverlay.classList.remove('hidden');
            playFeedback('win');
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
            }).catch(error => {
                alert("Ralat rangkaian.");
                btn.disabled = false;
                btn.innerHTML = 'Tahap Seterusnya <i class="fa-solid fa-forward-step"></i>';
            });
        }
    </script>
@endsection
