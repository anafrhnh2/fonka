@extends('layouts.game')

@section('game-title', 'Tahap 6: Suku Kata KV + K')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

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
                <div class="flex items-center bg-[#F0FDF4] border-4 border-[#BEF264] rounded-full px-4 py-2 shadow-sm">
                    <span class="text-[#4F7A0D] font-black text-sm md:text-xl uppercase tracking-wider font-lexend">
                        <i class="fa-solid fa-shapes mr-2"></i> Bina Suku Kata KV + K
                    </span>
                </div>

                <div class="flex items-center gap-3 md:gap-5 font-lexend">
                    <div
                        class="flex flex-col items-center bg-white border-4 border-[#BEF264] rounded-2xl px-4 py-1 shadow-sm">
                        <span class="text-[#84CC16] font-bold text-xs md:text-sm uppercase tracking-wider">Markah</span>
                        <span id="score-display" class="text-[#4F7A0D] font-black text-xl md:text-2xl">0</span>
                    </div>

                    <div
                        class="flex flex-col items-center bg-[#FDF7FF] border-4 border-[#DDD6FE] rounded-2xl px-4 py-1 shadow-sm">
                        <span class="text-[#8B5CF6] font-bold text-xs md:text-sm uppercase tracking-wider">Pusingan</span>
                        <span id="round-display" class="text-[#7C3AED] font-black text-xl md:text-2xl">0/10</span>
                    </div>

                    <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                        class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                        <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                    </button>
                </div>
            </div>

            <div id="game-board"
                class="flex-1 rounded-[2rem] md:rounded-[3rem] relative overflow-hidden shadow-inner border-4 border-[#BEF264] w-full flex flex-col justify-between items-center bg-white">


                <div
                    class="text-center mt-6 z-10 bg-[#F8FAF4] px-8 py-3 rounded-full border-2 border-[#BEF264] shadow-sm flex flex-col gap-1">
                    <h2 class="text-xl md:text-2xl font-black text-slate-700 tracking-wide">
                        Lengkapkan Ejaan
                    </h2>
                    <p class="text-xs md:text-sm font-bold text-[#84CC16] uppercase">Klik huruf terakhir yang betul</p>
                </div>

                <div id="question-container"
                    class="bg-white p-6 md:p-8 rounded-[3rem] border-[8px] border-[#EDE9FE] shadow-2xl flex flex-col items-center justify-center min-w-[280px] md:min-w-[400px] relative mb-4 transition-transform z-10">

                    <button id="btn-replay" onclick="playQuestionAudio()"
                        class="absolute -top-5 -right-5 w-14 h-14 md:w-16 md:h-16 bg-[#8B5CF6] text-white rounded-full flex items-center justify-center text-2xl md:text-3xl border-4 border-white shadow-xl active:translate-y-1 active:shadow-none transition-all z-20">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>

                    <img id="question-img" src="" alt="Soalan"
                        class="w-32 h-32 md:w-48 md:h-48 object-contain mb-6 drop-shadow-xl transition-all duration-500">

                    <div class="flex items-center justify-center gap-3 md:gap-5">
                        <div id="kv-box"
                            class="px-6 py-3 bg-[#F0FDF4] border-4 border-[#84CC16] text-[#4F7A0D] rounded-3xl font-black text-4xl md:text-7xl shadow-sm">
                            BA
                        </div>
                        <div id="plus-sign" class="text-4xl md:text-5xl font-black text-slate-200">+</div>
                        <div id="k-box"
                            class="w-20 h-20 md:w-28 md:h-28 bg-slate-50 border-4 border-dashed border-slate-300 text-slate-300 rounded-3xl font-black text-4xl md:text-7xl flex items-center justify-center transition-all duration-300">
                            ?
                        </div>
                    </div>

                    <div id="feedback-badge"
                        class="absolute inset-0 flex items-center justify-center bg-white/90 rounded-[2.5rem] opacity-0 pointer-events-none transition-opacity duration-300 z-30">
                        <span id="feedback-icon" class="text-8xl"></span>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center gap-4 md:gap-6 w-full px-4 mb-10 z-10" id="answer-buttons">
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
                        <p class="text-gray-600 font-bold text-lg md:text-xl leading-tight">Dengar bunyi suku kata KV + K
                        </p>
                    </div>
                    <div class="flex items-center gap-4 bg-blue-50 p-4 rounded-xl border-2 border-blue-100 shadow-sm">
                        <div
                            class="w-12 h-12 bg-blue-200 text-blue-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-hand-pointer"></i>
                        </div>
                        <p class="text-gray-600 font-bold text-lg md:text-xl leading-tight">
                            Pilih jawapan yang betul.</p>
                    </div>
                </div>
                <button onclick="startGame()"
                    class="btn-3d bg-[#84CC16] border-[#4F7A0D] text-white w-full text-2xl font-bold py-5 rounded-2xl flex justify-center items-center gap-3">
                    <i class="fa-solid fa-play"></i> Mula Belajar
                </button>
            </div>
        </div>

        <div id="win-overlay"
            class="hidden absolute inset-0 bg-black/80 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">
            <div
                class="bg-white p-8 md:p-10 rounded-[3rem] shadow-2xl text-center max-w-lg w-full animate-bounce-in border-[8px] border-[#EDE9FE] relative my-auto">
                <div
                    class="absolute -top-12 left-1/2 transform -translate-x-1/2 text-7xl drop-shadow-lg text-yellow-400 bg-white rounded-full p-3 border-4 border-[#EDE9FE]">
                    <i class="fa-solid fa-award"></i>
                </div>

                <h1 class="text-4xl md:text-5xl font-black text-[#7C3AED] mb-2 mt-8">Tahniah!</h1>
                <div class="bg-[#FDF7FF] p-6 rounded-[2.5rem] mb-8 border-4 border-[#EDE9FE] shadow-inner">
                    <div class="flex justify-center gap-2 mb-4" id="star-container"></div>
                    <p class="font-black text-[#8B5CF6] uppercase tracking-widest mb-1 text-sm">Markah Terkumpul</p>
                    <p class="text-7xl font-black text-[#7C3AED] drop-shadow-sm" id="final-score">0</p>
                </div>

                <div class="flex flex-col gap-4">
                    <button onclick="saveAndExit(event)"
                        class="btn-3d w-full bg-[#84CC16] border-[#4F7A0D] text-white text-xl md:text-2xl font-bold py-4 rounded-2xl flex justify-center items-center gap-3">
                        Simpan & Teruskan <i class="fa-solid fa-chevron-right"></i>
                    </button>

                    <button onclick="restartGame()"
                        class="btn-3d w-full bg-slate-200 border-slate-300 text-slate-700 text-lg md:text-xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3 hover:bg-slate-300 hover:border-slate-400">
                        Main Semula <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <audio id="audio-player"></audio>

    <style>
        .font-lexend {
            font-family: 'Lexend', sans-serif;
        }

        .btn-3d {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.1s ease;
            cursor: pointer;
            border-bottom-width: 6px;
            border-top-width: 2px;
            border-left-width: 2px;
            border-right-width: 2px;
            border-style: solid;
        }

        .btn-3d:active {
            transform: translateY(4px);
            border-bottom-width: 2px;
            margin-top: 4px;
        }

        .ans-btn {
            background-color: #FDF7FF;
            border-color: #DDD6FE;
            color: #7C3AED;
            width: 80px;
            height: 80px;
            font-size: 3rem;
            font-weight: 900;
            border-radius: 1.5rem;
            box-shadow: 0 4px 0 #DDD6FE;
        }

        @media (min-width: 768px) {
            .ans-btn {
                width: 110px;
                height: 110px;
                font-size: 4.5rem;
            }
        }

        .ans-btn:hover {
            background-color: #EDE9FE;
            transform: scale(1.05);
        }

        .correct-choice {
            background-color: #84CC16 !important;
            border-color: #4F7A0D !important;
            color: white !important;
            box-shadow: 0 4px 0 #4F7A0D !important;
        }

        .wrong-choice {
            background-color: #EF4444 !important;
            border-color: #991B1B !important;
            color: white !important;
            box-shadow: 0 4px 0 #991B1B !important;
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
                transform: scale(0.3);
                opacity: 0;
            }

            50% {
                transform: scale(1.05);
                opacity: 1;
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
            }
        }

        .animate-bounce-in {
            animation: bounce-in 0.6s forwards;
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
        const audioPlayer = document.getElementById('audio-player');
        const correctSound = new Audio('{{ asset('audio/bagus.mp3') }}');
        const wrongSound = new Audio('{{ asset('audio/cubalagi.mp3') }}');
        const winSound = new Audio('{{ asset('audio/tahniah.mp3') }}');
        const gameData = [{
                word: 'bas',
                kv: 'ba',
                k: 's',
                options: ['s', 'm', 't'],
                img: '{{ asset('images/games/bas.png') }}',
                audio: '{{ asset('audio/Bas.mp3') }}'
            },
            {
                word: 'bom',
                kv: 'bo',
                k: 'm',
                options: ['m', 'n', 'p'],
                img: '{{ asset('images/games/bom.png') }}',
                audio: '{{ asset('audio/Bom.mp3') }}'
            },
            {
                word: 'bot',
                kv: 'bo',
                k: 't',
                options: ['t', 'l', 'k'],
                img: '{{ asset('images/games/bot.png') }}',
                audio: '{{ asset('audio/Bot.mp3') }}'
            },
            {
                word: 'zip',
                kv: 'zi',
                k: 'p',
                options: ['p', 'r', 'l'],
                img: '{{ asset('images/games/zip.png') }}',
                audio: '{{ asset('audio/Zip.mp3') }}'
            },
            {
                word: 'gas',
                kv: 'ga',
                k: 's',
                options: ['s', 'm', 'n'],
                img: '{{ asset('images/games/gas.png') }}',
                audio: '{{ asset('audio/Gas.mp3') }}'
            },
            {
                word: 'gam',
                kv: 'ga',
                k: 'm',
                options: ['m', 't', 'l'],
                img: '{{ asset('images/games/gam.png') }}',
                audio: '{{ asset('audio/Gam.mp3') }}'
            },
            {
                word: 'jam',
                kv: 'ja',
                k: 'm',
                options: ['m', 'n', 's'],
                img: '{{ asset('images/games/jam.png') }}',
                audio: '{{ asset('audio/Jam.mp3') }}'
            },
            {
                word: 'jus',
                kv: 'ju',
                k: 's',
                options: ['s', 't', 'k'],
                img: '{{ asset('images/games/jus.png') }}',
                audio: '{{ asset('audio/Jus.mp3') }}'
            },
            {
                word: 'jet',
                kv: 'je',
                k: 't',
                options: ['t', 'n', 'm'],
                img: '{{ asset('images/games/jet.png') }}',
                audio: '{{ asset('audio/Jet.mp3') }}'
            },
            {
                word: 'kek',
                kv: 'ke',
                k: 'k',
                options: ['k', 't', 'm'],
                img: '{{ asset('images/games/kek.png') }}',
                audio: '{{ asset('audio/Kek.mp3') }}'
            }
        ];

        const MAX_ROUNDS = 10;
        let shuffledData = [];
        let currentRound = 0;
        let score = 0;
        let isWaiting = false;
        let sessionMistakes = {};

        function toggleFullScreen() {
            const fsContainer = document.getElementById('game-fullscreen-container');
            const fullscreenIcon = document.getElementById('fullscreen-icon');

            // iPad / Safari support
            if (fsContainer.requestFullscreen) {
                fsContainer.requestFullscreen();
            } else if (fsContainer.webkitRequestFullscreen) {
                fsContainer.webkitRequestFullscreen();
            } else if (fsContainer.webkitEnterFullscreen) {
                fsContainer.webkitEnterFullscreen();
            } else {
                alert("Fullscreen tidak disokong pada iPad Safari.");
            }

            // Toggle icon
            if (
                document.fullscreenElement ||
                document.webkitFullscreenElement
            ) {
                fullscreenIcon.classList.remove('fa-expand');
                fullscreenIcon.classList.add('fa-compress');
            } else {
                fullscreenIcon.classList.remove('fa-compress');
                fullscreenIcon.classList.add('fa-expand');
            }
        }

        document.addEventListener('fullscreenchange', updateFullscreenIcon);
        document.addEventListener('webkitfullscreenchange', updateFullscreenIcon);

        function updateFullscreenIcon() {
            const fullscreenIcon = document.getElementById('fullscreen-icon');

            if (
                document.fullscreenElement ||
                document.webkitFullscreenElement
            ) {
                fullscreenIcon.classList.remove('fa-expand');
                fullscreenIcon.classList.add('fa-compress');
            } else {
                fullscreenIcon.classList.remove('fa-compress');
                fullscreenIcon.classList.add('fa-expand');
            }
        }

        function startGame() {
            document.getElementById('start-overlay').style.display = 'none';
            document.getElementById('win-overlay').classList.add('hidden');

            shuffledData = [...gameData].sort(() => Math.random() - 0.5);
            if (shuffledData.length > MAX_ROUNDS) shuffledData.length = MAX_ROUNDS;

            score = 0;
            currentRound = 0;
            sessionMistakes = {};
            loadRound();
        }

        function loadRound() {
            if (currentRound >= MAX_ROUNDS) {
                endGame();
                return;
            }

            isWaiting = false;
            const data = shuffledData[currentRound];

            document.getElementById('question-img').src = data.img;
            document.getElementById('kv-box').innerText = data.kv.toUpperCase();
            document.getElementById('k-box').innerText = "?";

            // Reset box classes for a new round
            document.getElementById('k-box').className =
                "w-20 h-20 md:w-28 md:h-28 bg-slate-50 border-4 border-dashed border-slate-300 text-slate-300 rounded-3xl font-black text-4xl md:text-7xl flex items-center justify-center transition-all duration-300";
            document.getElementById('kv-box').classList.remove('scale-110');
            document.getElementById('plus-sign').style.display = "block";
            document.getElementById('k-box').style.display = "flex";

            document.getElementById('round-display').innerText = `${currentRound + 1}/${MAX_ROUNDS}`;
            document.getElementById('score-display').innerText = score;

            document.getElementById('feedback-badge').className =
                "absolute inset-0 flex items-center justify-center bg-white/90 rounded-[2.5rem] opacity-0 pointer-events-none transition-opacity duration-300 z-30";

            renderButtons(data);
            setTimeout(() => playQuestionAudio(), 500);
        }

        function renderButtons(data) {
            const container = document.getElementById('answer-buttons');
            container.innerHTML = '';

            // Render buttons based on current data options
            const options = [...data.options].sort(() => Math.random() - 0.5);
            options.forEach(letter => {
                const btn = document.createElement('button');
                btn.className = 'btn-3d ans-btn uppercase font-lexend mx-2';
                btn.innerText = letter;
                btn.onclick = () => checkAnswer(letter, btn);
                container.appendChild(btn);
            });
        }

        function checkAnswer(selected, btn) {
            if (isWaiting) return;
            isWaiting = true;

            const correctLetter = shuffledData[currentRound].k;
            const word = shuffledData[currentRound].word;
            const allBtns = document.querySelectorAll('.ans-btn');

            allBtns.forEach(b => b.disabled = true);

            if (selected.toLowerCase() === correctLetter.toLowerCase()) {
                score += 1;
                document.getElementById('score-display').innerText = score;
                btn.classList.add('correct-choice');
                correctSound.pause();
                correctSound.currentTime = 0;
                correctSound.play();


                // Visual Equation update
                document.getElementById('k-box').innerText = selected.toUpperCase();
                document.getElementById('k-box').classList.replace('bg-slate-50', 'bg-[#F0FDF4]');
                document.getElementById('k-box').classList.replace('border-slate-300', 'border-[#84CC16]');
                document.getElementById('k-box').classList.replace('text-slate-300', 'text-[#4F7A0D]');

                document.getElementById('feedback-icon').innerHTML =
                    '<i class="fa-solid fa-circle-check text-[#84CC16]"></i>';

                setTimeout(() => {
                    document.getElementById('plus-sign').style.display = "none";
                    document.getElementById('k-box').style.display = "none";
                    document.getElementById('kv-box').innerText = word.toUpperCase();
                    document.getElementById('kv-box').classList.add('scale-110');
                }, 600);


                setTimeout(() => {
                    currentRound++;
                    loadRound();
                }, 2000);

            } else {
                btn.classList.add('wrong-choice');
                document.getElementById('question-container').classList.add('shake');
                wrongSound.play();

                let mistakeKey = `${word}_${selected.toLowerCase()}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;

                allBtns.forEach(b => {
                    if (b.innerText.toLowerCase() === correctLetter.toLowerCase()) b.classList.add(
                        'correct-choice');
                });

                document.getElementById('feedback-icon').innerHTML =
                    '<i class="fa-solid fa-circle-xmark text-red-500"></i>';
                document.getElementById('feedback-badge').classList.replace('opacity-0', 'opacity-100');

                setTimeout(() => {
                    document.getElementById('question-container').classList.remove('shake');
                    currentRound++;
                    loadRound();
                }, 2500);
            }
        }

        function playQuestionAudio() {
            const currentData = shuffledData[currentRound];

            audioPlayer.pause();
            audioPlayer.currentTime = 0;

            audioPlayer.src = currentData.audio;

            audioPlayer.play().catch(() => {
                const utter = new SpeechSynthesisUtterance(currentData.word);
                utter.lang = 'ms-MY';
                window.speechSynthesis.speak(utter);
            });
        }

        function endGame() {
            document.getElementById('final-score').innerText = score;
            const stars = score >= 10 ? 3 : (score >= 5 ? 2 : 1);

            const starContainer = document.getElementById('star-container');
            starContainer.innerHTML = '';
            for (let i = 0; i < 3; i++) {
                const color = i < stars ? 'text-yellow-400' : 'text-slate-200';
                starContainer.innerHTML += `<i class="fa-solid fa-star ${color} text-5xl mx-1 drop-shadow-md"></i>`;
            }

            document.getElementById('win-overlay').classList.remove('hidden');
            const winSound = new Audio('{{ asset('audio/tahniah.mp3') }}');
            winSound.play();
        }

        function restartGame() {
            startGame();
        }

        function saveAndExit(event) {
            const btn = event.target;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Menyimpan...';

            fetch(window.gameConfig.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    child_id: window.gameConfig.childId,
                    game_level_id: window.gameConfig.levelId,
                    score: score,
                    stars_earned: score >= 10 ? 3 : (score >= 5 ? 2 : 1),
                    is_completed: true,
                    mistakes: sessionMistakes
                })
            }).then(r => r.json()).then(data => {
                if (data.success) window.location.href = window.gameConfig.nextLevelUrl;
            }).catch(e => {
                alert("Ralat rangkaian.");
                btn.disabled = false;
            });
        }
    </script>
@endsection
