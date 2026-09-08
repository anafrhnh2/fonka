@extends('layouts.game')

@section('game-title', 'Tahap 19: Susun Ayat')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 19 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#F0FDF4] transition-all duration-300">

        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0 z-20">
                <div class="flex items-center bg-emerald-100 border-4 border-emerald-400 rounded-full px-4 py-2 shadow-sm">
                    <span class="text-emerald-800 font-black text-sm md:text-xl uppercase tracking-wider ">
                        <i class="fa-solid fa-bridge-water mr-2"></i> Susun ayat
                    </span>
                </div>

                <div class="flex items-center gap-3 md:gap-5 ">

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
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-sky-300 w-full flex flex-col items-center bg-[#BAE6FD] pt-4 pb-4">

                <div
                    class="absolute inset-0 z-0 pointer-events-none opacity-40 bg-[url('https://www.transparenttextures.com/patterns/water.png')]">
                </div>
                <div
                    class="absolute top-0 w-full h-32 bg-gradient-to-b from-sky-400/50 to-transparent z-0 pointer-events-none">
                </div>

                <div
                    class="text-center z-10 bg-white/90 px-6 py-2 rounded-full border-2 border-sky-200 shadow-md backdrop-blur-sm flex items-center gap-4 mt-2">
                    <button onclick="playSentenceAudio()"
                        class="w-12 h-12 bg-amber-400 text-white rounded-full flex items-center justify-center text-xl border-4 border-amber-200 shadow-[0_4px_0_#D97706] active:translate-y-1 active:shadow-none transition-all pulse-btn-audio">
                        <i class="fa-solid fa-volume-high"></i>
                    </button>
                    <h2 class="text-base md:text-xl font-black text-sky-800 tracking-wide font-lexend responsive-text">
                        Dengar dan susun ayat ini!</h2>
                </div>

                <div class="relative w-full max-w-5xl flex flex-col items-center mt-6 z-10 flex-1 justify-start px-4">

                    <div id="bridge-container"
                        class="w-full bg-[#854D0E]/20 p-4 md:p-8 rounded-3xl border-4 border-dashed border-[#A16207] min-h-[150px] md:min-h-[200px] flex flex-wrap justify-center content-start gap-2 md:gap-3 transition-all duration-500 shadow-inner">
                    </div>

                    <div id="feedback-badge"
                        class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white/95 px-8 py-4 rounded-[2rem] opacity-0 pointer-events-none transition-all duration-300 z-30 shadow-2xl flex flex-col items-center border-4 border-emerald-400 scale-50">
                        <span id="feedback-icon" class="text-6xl md:text-8xl drop-shadow-lg mb-2 text-emerald-500"><i
                                class="fa-solid fa-circle-check"></i></span>
                        <span id="feedback-text"
                            class="font-lexend font-black text-3xl md:text-4xl text-emerald-600">Sempurna!</span>
                    </div>

                </div>

                <div
                    class="w-full bg-[#FDE68A] p-6 md:p-8 rounded-t-[3rem] border-t-8 border-[#F59E0B] shadow-[0_-10px_30px_rgba(0,0,0,0.15)] z-20 flex flex-col items-center mt-4">

                    <div class="flex flex-wrap justify-center gap-3 md:gap-4 w-full max-w-5xl " id="options-container">
                    </div>
                </div>

            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-slate-900/80 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">
            <div
                class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full flex flex-col border-[6px] border-emerald-400 font-lexend">
                <div class="text-6xl mb-4 text-slate-800"><i class="fa-solid fa-bridge"></i></div>
                <h1 class="text-3xl md:text-4xl font-black text-emerald-600 mb-6">Pembina Jambatan</h1>
                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10">
                    <div class="flex items-center gap-4 bg-sky-50 p-4 rounded-xl border-2 border-sky-200 shadow-sm">
                        <div
                            class="w-12 h-12 bg-sky-200 text-sky-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-volume-high"></i>
                        </div>
                        <p class="text-slate-700 font-bold text-lg leading-tight">Dengar ayat yang panjang ini dengan
                            teliti.</p>
                    </div>
                    <div class="flex items-center gap-4 bg-amber-50 p-4 rounded-xl border-2 border-amber-200 shadow-sm">
                        <div
                            class="w-12 h-12 bg-amber-200 text-amber-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <p class="text-slate-700 font-bold text-lg leading-tight">Pilih papan perkataan satu persatu untuk
                            menyusun ayat yang betul.</p>
                    </div>
                </div>
                <button onclick="startGame()"
                    class="btn-3d bg-emerald-500 border-emerald-700 text-white w-full text-2xl font-black py-4 rounded-[1.5rem] flex justify-center items-center gap-3 hover:bg-emerald-400">
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
    <audio id="sfx-click" src="https://assets.mixkit.com/sfx/preview/mixkit-wooden-gavel-hit-3112.mp3"></audio>
    <audio id="sfx-remove" src="https://assets.mixkit.com/sfx/preview/mixkit-light-click-1136.mp3"></audio>
    <audio id="sfx-wrong" src="https://assets.mixkit.com/sfx/preview/mixkit-buzzer-14.mp3"></audio>
    <audio id="sfx-success" src="https://assets.mixkit.com/sfx/preview/mixkit-magical-coin-win-1936.mp3"></audio>

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

        /* BUTANG PILIHAN (PAPAN KAYU) */
        .wood-btn {
            background-color: #D97706;
            /* Warna Kayu */
            border-color: #713F12;
            border-top-color: #FBBF24;
            border-left-color: #FBBF24;
            color: #FEF3C7;
            padding: 0 15px;
            height: 50px;
            font-size: 1.2rem;
            font-weight: 800;
            border-radius: 0.8rem;
            text-transform: lowercase;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        @media (min-width: 768px) {
            .wood-btn {
                padding: 0 25px;
                height: 65px;
                font-size: 1.8rem;
                border-radius: 1rem;
            }
        }

        .wood-btn:not(:disabled):hover {
            transform: scale(1.05) translateY(-3px);
            box-shadow: 0 8px 15px rgba(113, 63, 18, 0.3);
            background-color: #F59E0B;
            color: #FFFBEB;
        }

        .wood-btn.selected {
            transform: scale(0.5);
            opacity: 0;
            pointer-events: none;
            position: absolute;
        }

        /* SLOT AYAT DI JAMBATAN */
        .slot-box {
            background-color: rgba(255, 255, 255, 0.5);
            border: 2px dashed #A16207;
            height: 50px;
            min-width: 80px;
            padding: 0 15px;
            border-radius: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        @media (min-width: 768px) {
            .slot-box {
                height: 65px;
                min-width: 100px;
                padding: 0 20px;
                border-radius: 1rem;
            }
        }

        .slot-box.filled {
            background-color: #D97706;
            border-color: #713F12;
            border-style: solid;
            box-shadow: inset 0 2px 0 #FBBF24, 0 4px 6px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .slot-box.filled:hover {
            filter: brightness(1.1);
            transform: translateY(-2px);
        }

        .slot-box.filled .slot-text {
            color: #FEF3C7;
            font-weight: 800;
            font-size: 1.2rem;
            text-transform: lowercase;
        }

        @media (min-width: 768px) {
            .slot-box.filled .slot-text {
                font-size: 1.8rem;
            }
        }

        /* Status Check */
        .bridge-success {
            background-color: #10B981 !important;
            border-color: #047857 !important;
            border-style: solid !important;
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.5) !important;
        }

        .bridge-success .slot-box {
            background-color: #059669 !important;
            border-color: #064E3B !important;
            box-shadow: none !important;
        }

        .bridge-error {
            animation: shake 0.4s ease-in-out;
            border-color: #EF4444 !important;
            background-color: rgba(239, 68, 68, 0.2) !important;
        }

        .bridge-error .slot-box.filled {
            background-color: #DC2626 !important;
            border-color: #991B1B !important;
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
            font-size: 5rem;
            pointer-events: none;
            animation: pop-sparkle 0.8s ease-out forwards;
            z-index: 100;
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
        const removeSound = document.getElementById('sfx-remove');
        const correctSound = new Audio("{{ asset('audio/bagus.mp3') }}");
        const wrongSound = new Audio("{{ asset('audio/cubalagi.mp3') }}");
        const winSound = new Audio("{{ asset('audio/tahniah.mp3') }}");

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

        // --- DATA SOALAN (Ayat Panjang 7-10 Perkataan) ---
        const gameData = [{
                words: ['Ayah', 'bawa', 'kami', 'pergi', 'ke', 'taman', 'pada', 'waktu', 'petang'],
                audio: '{{ asset('audio/Lvl19(1).mp3') }}'
            },
            {
                words: ['Kucing', 'comel', 'itu', 'sedang', 'tidur', 'lena', 'di', 'bawah', 'kerusi'],
                audio: '{{ asset('audio/Lvl19(2).mp3') }}'
            },
            {
                words: ['Ibu', 'beli', 'ikan', 'segar', 'dan', 'sayur', 'hijau', 'di', 'pasar'],
                audio: '{{ asset('audio/Lvl19(3).mp3') }}'
            },
            {
                words: ['Abang', 'main', 'layang', 'layang', 'di', 'padang', 'yang', 'sangat', 'luas'],
                audio: '{{ asset('audio/Lvl19(4).mp3') }}'
            },
            {
                words: ['Adik', 'lukis', 'gambar', 'pemandangan', 'yang', 'sangat', 'cantik', 'dan', 'kemas'],
                audio: '{{ asset('audio/Lvl19(5).mp3') }}'
            },
            {
                words: ['Atok', 'tanam', 'pokok', 'buah', 'rambutan', 'di', 'belakang', 'rumah', 'kami'],
                audio: '{{ asset('audio/Lvl19(6).mp3') }}'
            },
            {
                words: ['Cikgu', 'Aina', 'ajar', 'murid', 'baca', 'buku', 'cerita', 'dengan', 'lancar'],
                audio: '{{ asset('audio/Lvl19(7).mp3') }}'
            },
            {
                words: ['Kami', 'makan', 'nasi', 'lemak', 'sedap', 'yang', 'ibu', 'masak', 'tadi'],
                audio: '{{ asset('audio/Lvl19(8).mp3') }}'
            },
            {
                words: ['Kakak', 'tolong', 'ibu', 'basuh', 'pinggan', 'mangkuk', 'di', 'singki', 'dapur'],
                audio: '{{ asset('audio/Lvl19(9).mp3') }}'
            },
            {
                words: ['Burung', 'merpati', 'itu', 'terbang', 'tinggi', 'di', 'udara', 'mencari', 'makanan'],
                audio: '{{ asset('audio/Lvl19(10).mp3') }}'
            }
        ];

        const MAX_ROUNDS = 10;
        let shuffledData = [];
        let currentRound = 0;
        let score = 0;
        let currentData = {};

        let selectedWords = [];
        let isWaiting = false;
        let sessionMistakes = {}; // 1. TAMBAH BARIS INI
        const bridgeContainer = document.getElementById('bridge-container');
        const optionsContainer = document.getElementById('options-container');
        const scoreDisplay = document.getElementById('score-display');
        const roundDisplay = document.getElementById('round-display');
        const feedbackBadge = document.getElementById('feedback-badge');
        const feedbackIcon = document.getElementById('feedback-icon');
        const feedbackText = document.getElementById('feedback-text');

        // --- SISTEM AUDIO (TTS) ---
        function playPhonicsTTS(text, rate = 0.8) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ms-MY';
            utterance.rate = rate;
            window.speechSynthesis.speak(utterance);
        }

        function playSentenceAudio() {
            if (isWaiting) return;
            const fullSentence = currentData.words.join(' ');
            audioPlayer.src = currentData.audio;
            audioPlayer.play().catch(e => {
                playPhonicsTTS(fullSentence, 0.85);
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
            sessionMistakes = {}; // 2. TAMBAH BARIS INI
            updateUI();
            loadQuestion();
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
            selectedWords = [];
            currentData = shuffledData[currentRound];
            updateUI();

            bridgeContainer.classList.remove('bridge-success', 'bridge-error');
            feedbackBadge.classList.replace('opacity-100', 'opacity-0');
            feedbackBadge.classList.replace('scale-100', 'scale-50');
            feedbackBadge.classList.replace('border-red-400', 'border-emerald-400');

            generateBridgeSlots();
            generateOptions();

            setTimeout(() => playSentenceAudio(), 800);
        }

        function generateBridgeSlots() {
            bridgeContainer.innerHTML = '';
            // Bina slot kosong berdasarkan jumlah perkataan
            currentData.words.forEach((_, index) => {
                const slot = document.createElement('div');
                slot.className = 'slot-box';
                slot.id = `slot-${index}`;
                slot.onclick = () => undoWord(index);

                const span = document.createElement('span');
                span.className = 'slot-text font-lexend';
                slot.appendChild(span);

                bridgeContainer.appendChild(slot);
            });
        }

        function generateOptions() {
            optionsContainer.innerHTML = '';

            // Shuffle perkataan
            let options = [...currentData.words].sort(() => Math.random() - 0.5);

            options.forEach((word, index) => {
                const btn = document.createElement('button');
                btn.className = 'btn-3d wood-btn font-lexend transition-all';
                btn.innerText = word;
                btn.id = `opt-${index}`;

                btn.onclick = () => selectWord(word, btn);
                optionsContainer.appendChild(btn);
            });
        }


        function selectWord(word, btnElement) {
            if (isWaiting || selectedWords.length >= currentData.words.length) return;

            clickSound.currentTime = 0;
            clickSound.play().catch(e => {});

            // Simpan ke array
            selectedWords.push({
                text: word,
                btnId: btnElement.id
            });

            // Sembunyikan butang pilihan (jangan buang dari DOM supaya susunan tak lari)
            btnElement.style.visibility = 'hidden';

            updateBridgeVisuals();

            // Auto check jika semua slot penuh
            if (selectedWords.length === currentData.words.length) {
                checkAnswer();
            }
        }

        function undoWord(index) {
            if (isWaiting || index >= selectedWords.length) return;

            removeSound.currentTime = 0;
            removeSound.play().catch(e => {});

            // ambil data perkataan
            const wordData = selectedWords[index];
            const btnElement = document.getElementById(wordData.btnId);

            // show balik button
            if (btnElement) {
                btnElement.style.visibility = 'visible';
                btnElement.disabled = false;
            }

            // buang dari array
            selectedWords.splice(index, 1);

            // update UI
            updateBridgeVisuals();
        }

        // KEMASKINI SLOT VISUAL (Serta merta di UI)
        function updateBridgeVisuals() {
            const slots = bridgeContainer.querySelectorAll('.slot-box');

            slots.forEach((slot, index) => {
                const textSpan = slot.querySelector('.slot-text');
                if (index < selectedWords.length) {
                    textSpan.innerText = selectedWords[index].text;
                    slot.classList.add('filled');
                } else {
                    textSpan.innerText = "";
                    slot.classList.remove('filled');
                }
            });
        }

        // SEMAK JAWAPAN & 1 PERCUBAAN SAHAJA
        function checkAnswer() {
            isWaiting = true;

            const userSentence = selectedWords.map(a => a.text.toLowerCase()).join(' ');
            const correctSentence = currentData.words.join(' ').toLowerCase();

            if (userSentence === correctSentence) {
                // JAWAPAN BETUL
                score += 5; // 2 Markah setiap pusingan
                updateUI();

                correctSound.currentTime = 0;
                correctSound.play().catch(e => {});

                bridgeContainer.classList.add('bridge-success');

                feedbackIcon.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
                feedbackIcon.className = "text-6xl md:text-8xl drop-shadow-lg mb-2 text-emerald-500";
                feedbackText.innerText = 'Betul!';
                feedbackText.className = "font-lexend font-black text-3xl md:text-4xl text-emerald-600";

                feedbackBadge.classList.replace('border-red-400', 'border-emerald-400');
                feedbackBadge.classList.replace('opacity-0', 'opacity-100');
                feedbackBadge.classList.replace('scale-50', 'scale-100');

                createSparkle(bridgeContainer);
                playSentenceAudio();

                setTimeout(() => {
                    currentRound++;
                    loadQuestion();
                }, 3000);

            } else {
                // JAWAPAN SALAH
                wrongSound.currentTime = 0;
                wrongSound.play().catch(e => {});

                bridgeContainer.classList.add('bridge-error');

                feedbackIcon.innerHTML = '<i class="fa-solid fa-circle-xmark"></i>';
                feedbackIcon.className = "text-6xl md:text-8xl drop-shadow-lg mb-2 text-red-500";
                feedbackText.innerText = 'Salah!';
                feedbackText.className = "font-lexend font-black text-3xl md:text-4xl text-red-600";

                feedbackBadge.classList.replace('border-emerald-400', 'border-red-400');
                feedbackBadge.classList.replace('opacity-0', 'opacity-100');
                feedbackBadge.classList.replace('scale-50', 'scale-100');

                let mistakeKey = `${correctSentence}_${userSentence}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                // Bergerak terus ke soalan seterusnya tanpa mengulangi pusingan
                setTimeout(() => {
                    bridgeContainer.classList.remove('bridge-error');
                    feedbackBadge.classList.replace('opacity-100', 'opacity-0');
                    feedbackBadge.classList.replace('scale-100', 'scale-50');

                    currentRound++;
                    loadQuestion();
                }, 2500);
            }
        }

        function createSparkle(parent) {
            const sparkle = document.createElement('div');
            sparkle.classList.add('sparkle-effect');
            sparkle.innerHTML = '<i class="fa-solid fa-sparkles text-yellow-400"></i>';

            const rect = parent.getBoundingClientRect();
            sparkle.style.left = (rect.width / 2 - 30) + 'px';
            sparkle.style.top = (rect.height / 2 - 30) + 'px';

            parent.appendChild(sparkle);
            setTimeout(() => sparkle.remove(), 1000);
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

        // 10 pusingan * 5 markah = Maksimum 50 markah
        function calculateStars() {
            return score >= 40 ? 3 : (score >= 25 ? 2 : 1);
        }

        function endGame() {
            document.getElementById('final-score').innerText = score;
            const earnedStars = calculateStars();
            renderStars(earnedStars);

            const winOverlay = document.getElementById('win-overlay');
            winOverlay.classList.remove('hidden');

            window.speechSynthesis.cancel();
            winSound.currentTime = 0;
            winSound.play().catch(e => {});
        }

        function restartGame() {
            startGame();
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
                    btn.innerText = "Cuba Lagi";
                }
            }).catch(e => {
                alert("Ralat rangkaian.");
                btn.disabled = false;
                btn.innerText = "Cuba Lagi";
            });
        }
    </script>
@endsection
