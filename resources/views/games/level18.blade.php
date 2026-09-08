@extends('layouts.game')

@section('game-title', 'Tahap 18: Faham Ayat Mudah')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 18 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#FFFBEB] transition-all duration-300">

        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0 z-20">
                <div class="flex items-center bg-amber-100 border-4 border-amber-300 rounded-full px-4 py-2 shadow-sm">
                    <span class="text-amber-800 font-black text-sm md:text-xl uppercase tracking-wider font-lexend">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i> Faham Ayat Mudah
                    </span>
                </div>

                <div class="flex items-center gap-3 md:gap-5 font-lexend">

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
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-amber-200 w-full flex flex-col justify-start items-center bg-[#FEF3C7] pt-4 md:pt-8 pb-4">

                <div
                    class="absolute inset-0 z-0 pointer-events-none opacity-40 bg-[url('https://www.transparenttextures.com/patterns/notebook.png')]">
                </div>

                <div
                    class="relative w-[95%] max-w-3xl bg-white border-4 border-slate-300 rounded-[2rem] shadow-[0_15px_30px_rgba(0,0,0,0.1)] p-6 md:p-10 flex flex-col items-center z-10">

                    <div class="absolute -top-4 w-8 h-8 bg-red-500 rounded-full border-4 border-red-700 shadow-md"></div>

                    <div
                        class="w-full bg-sky-50 rounded-2xl border-2 border-sky-200 p-6 mb-6 flex flex-col items-center gap-4 transition-all">
                        <span class="text-sky-600 font-bold uppercase tracking-widest text-xs">Tekan untuk dengar
                            ayat</span>
                        <button onclick="playSentenceAudio()"
                            class="w-24 h-24 bg-sky-500 text-white rounded-full flex items-center justify-center text-4xl border-8 border-sky-200 shadow-[0_6px_0_#0284C7] active:translate-y-2 active:shadow-none transition-all hover:scale-110">
                            <i class="fa-solid fa-volume-high"></i>
                        </button>
                        <h2 id="sentence-text" class="opacity-0 h-0 overflow-hidden">
                        </h2>
                    </div>

                    <div
                        class="w-full flex flex-col items-center justify-center gap-2 border-t-2 border-dashed border-slate-200 pt-4">
                        <button onclick="playQuestionAudio()"
                            class="w-14 h-14 bg-amber-400 text-white rounded-full flex items-center justify-center text-2xl border-4 border-amber-200 shadow-[0_4px_0_#D97706] active:translate-y-1 active:shadow-none transition-all">
                            <i class="fa-solid fa-circle-question"></i>
                        </button>
                        <h3 id="question-text" class="opacity-0 h-0 overflow-hidden">
                        </h3>
                        <span class="text-amber-600 font-bold text-sm uppercase">Dengar Soalan</span>
                    </div>

                    <div id="feedback-badge"
                        class="absolute inset-0 flex items-center justify-center bg-white/95 rounded-[1.5rem] opacity-0 pointer-events-none transition-opacity duration-300 z-30">
                        <div class="flex flex-col items-center">
                            <span id="feedback-icon" class="text-8xl drop-shadow-lg mb-4 text-green-500"><i
                                    class="fa-solid fa-circle-check"></i></span>
                            <span id="feedback-text" class="text-3xl font-black text-green-600 font-lexend">Bagus!</span>
                        </div>
                    </div>
                </div>

                <div class="w-full flex-1 flex flex-col items-center justify-center mt-6 z-20">
                    <p
                        class="text-slate-500 font-bold mb-4 uppercase tracking-widest text-sm md:text-base bg-white/70 px-4 py-1 rounded-full">
                        Pilih Jawapan Di Bawah</p>
                    <div class="flex flex-wrap justify-center gap-4 md:gap-8 w-full max-w-4xl px-4" id="options-container">
                    </div>
                </div>

            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-slate-900/80 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">
            <div
                class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full flex flex-col border-[6px] border-amber-300 font-lexend">
                <div class="text-6xl mb-4 text-amber-500"><i class="fa-solid fa-user-secret"></i></div>
                <h1 class="text-3xl md:text-4xl font-black text-amber-600 mb-6">Faham Ayat Mudah</h1>
                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10">
                    <div class="flex items-center gap-4 bg-sky-50 p-4 rounded-xl border-2 border-sky-200 shadow-sm">
                        <div
                            class="w-12 h-12 bg-sky-200 text-sky-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <p class="text-slate-700 font-bold text-lg leading-tight">Dengar dan baca ayat dengan teliti.</p>
                    </div>
                    <div class="flex items-center gap-4 bg-amber-50 p-4 rounded-xl border-2 border-amber-200 shadow-sm">
                        <div
                            class="w-12 h-12 bg-amber-200 text-amber-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-person-circle-question"></i>
                        </div>
                        <p class="text-slate-700 font-bold text-lg leading-tight">Dengar soalan dan pilih kad jawapan yang
                            betul!</p>
                    </div>
                </div>
                <button onclick="startGame()"
                    class="btn-3d bg-amber-500 border-amber-700 text-white w-full text-2xl font-black py-4 rounded-[1.5rem] flex justify-center items-center gap-3">
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
                        <i class="fa-solid fa-rotate-right"></i> Main Semula
                    </button>
                </div>
            </div>
        </div>

    </div>

    <audio id="audio-player" preload="auto"></audio>
    <audio id="sfx-hover" src="https://images.mixkit.com/sfx/preview/mixkit-pop-click-3104.mp3"></audio>
    <audio id="sfx-correct" src="https://images.mixkit.com/sfx/preview/mixkit-correct-answer-tone-2870.mp3"></audio>
    <audio id="sfx-wrong" src="https://images.mixkit.com/sfx/preview/mixkit-buzzer-14.mp3"></audio>

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

        /* KAD JAWAPAN */
        .ans-card {
            background-color: #FFFFFF;
            border-color: #CBD5E1;
            border-top-color: #FFFFFF;
            border-left-color: #FFFFFF;
            width: 130px;
            height: 160px;
            border-radius: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
            gap: 10px;
        }

        @media (min-width: 768px) {
            .ans-card {
                width: 160px;
                height: 200px;
                padding: 15px;
            }
        }

        .ans-card:not(:disabled):hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
            background-color: #F8FAFC;
            border-color: #94A3B8;
        }

        .ans-img-container {
            width: 100%;
            height: 60%;
            background-color: #F1F5F9;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border: 2px solid #E2E8F0;
        }

        .ans-img {
            width: 500%;
            height: 500%;
            object-fit: contain;
            pointer-events: none;
        }

        .ans-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: #334155;
            text-transform: capitalize;
            font-family: 'Lexend', sans-serif;
        }

        @media (min-width: 768px) {
            .ans-text {
                font-size: 2rem;
            }
        }

        .wrong-choice {
            opacity: 0.4 !important;
            filter: grayscale(100%);
            background-color: #FEE2E2 !important;
            border-color: #EF4444 !important;
        }

        .correct-choice {
            transform: scale(1.1) !important;
            background-color: #DCFCE7 !important;
            border-color: #10B981 !important;
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.5) !important;
            z-index: 20;
        }

        .correct-choice .ans-img-container {
            background-color: #FFFFFF;
            border-color: #34D399;
        }

        .correct-choice .ans-text {
            color: #047857;
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
        const hoverSound = document.getElementById('sfx-hover');
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
        // --- DATA SOALAN ---
        const gameData = [{
                sen_audio: "{{ asset('audio/Ali_ada_buku_baru.mp3') }}",
                question: 'Apakah yang Ali ada?',
                que_audio: "{{ asset('audio/Apa_yg_ali_ada.mp3') }}",
                ans: 'Buku',
                distractors: ['Baju', 'Bola'],
                ansImg: "{{ asset('images/games/buku.png') }}",
                disImgs: ['{{ asset('images/games/baju.png') }}', '{{ asset('images/games/bola.png') }}']
            },
            {
                sen_audio: "{{ asset('audio/Sani_main_bola_besar.mp3') }}",
                question: 'Sani main apa?',
                que_audio: "{{ asset('audio/Sani_main_apa.mp3') }}",
                ans: 'Bola',
                distractors: ['Buku', 'Lori'],
                ansImg: '{{ asset('images/games/bola.png') }}',
                disImgs: ['{{ asset('images/games/buku.png') }}', '{{ asset('images/games/lori.png') }}']
            },
            {
                sen_audio: "{{ asset('audio/Ayah_cuci_lori.mp3') }}",
                question: 'Apa yang ayah cuci?',
                que_audio: "{{ asset('audio/Apa_yg_ayah_cuci.mp3') }}",
                ans: 'Lori',
                distractors: ['Meja', 'Baju'],
                ansImg: '{{ asset('images/games/lori.png') }}',
                disImgs: ['{{ asset('images/games/meja.png') }}', '{{ asset('images/games/baju.png') }}']
            },
            {
                sen_audio: "{{ asset('audio/Kucing_suka_makan_ikan.mp3') }}",
                question: 'Kucing makan apa?',
                que_audio: "{{ asset('audio/Kucing_makan_apa.mp3') }}",
                ans: 'Ikan',
                distractors: ['Tikus', 'Ayam'],
                ansImg: '{{ asset('images/games/ikan.png') }}',
                disImgs: ['{{ asset('images/games/tikus.png') }}', '{{ asset('images/games/ayam.png') }}']
            },
            {
                sen_audio: "{{ asset('audio/Adik_minum_susu.mp3') }}",
                question: 'Adik minum apa?',
                que_audio: "{{ asset('audio/Adik_minum_apa.mp3') }}",
                ans: 'Susu',
                distractors: ['Air Kosong', 'Kopi'],
                ansImg: '{{ asset('images/games/susu.png') }}',
                disImgs: ['https://placehold.co/200x200/93C5FD/FFF?text=Air', '{{ asset('images/games/kopi.png') }}']
            },
            {
                sen_audio: "{{ asset('audio/Nani_sapu_roti.mp3') }}",
                question: 'Nani sapu apa?',
                que_audio: "{{ asset('audio/Nani_sapu_apa.mp3') }}",
                ans: 'Roti',
                distractors: ['Buku', 'Meja'],
                ansImg: '{{ asset('images/games/roti-kaya.png') }}',
                disImgs: ['{{ asset('images/games/buku.png') }}', '{{ asset('images/games/meja.png') }}']
            },
            {
                sen_audio: "{{ asset('audio/Ibu_beli_baju.mp3') }}",
                question: 'Ibu beli apa?',
                que_audio: "{{ asset('audio/Ibu_beli_apa.mp3') }}",
                ans: 'Baju',
                distractors: ['Tali', 'Sudu'],
                ansImg: '{{ asset('images/games/baju.png') }}',
                disImgs: ['{{ asset('images/games/tali.png') }}', '{{ asset('images/games/sudu.png') }}']
            },
            {
                sen_audio: "{{ asset('audio/Mata_saya_warna_hitam.mp3') }}",
                question: 'Mata warna apa?',
                que_audio: "{{ asset('audio/Mata_warna_apa.mp3') }}",
                ans: 'Hitam',
                distractors: ['Biru', 'Merah'],
                ansImg: 'https://placehold.co/200x200/1E293B/FFF?text=Hitam',
                disImgs: ['https://placehold.co/200x200/3B82F6/FFF?text=Biru',
                    'https://placehold.co/200x200/EF4444/FFF?text=Merah'
                ]
            },
            {
                sen_audio: "{{ asset('audio/Siti_susun_meja.mp3') }}",
                question: 'Siti susun apa?',
                que_audio: "{{ asset('audio/Siti_susun_apa.mp3') }}",
                ans: 'Meja',
                distractors: ['Paku', 'Buku'],
                ansImg: '{{ asset('images/games/meja.png') }}',
                disImgs: ['{{ asset('images/games/paku.png') }}', '{{ asset('images/games/buku.png') }}']
            },
            {
                sen_audio: "{{ asset('audio/Raju_pakai_kasut.mp3') }}",
                question: 'Raju pakai apa?',
                que_audio: "{{ asset('audio/Raju_pakai_apa.mp3') }}",
                ans: 'Kasut',
                distractors: ['Baju', 'Tali'],
                ansImg: '{{ asset('images/games/kasut.png') }}',
                disImgs: ['{{ asset('images/games/baju.png') }}', '{{ asset('images/games/tali.png') }}']
            }
        ];

        const MAX_ROUNDS = 10;
        let shuffledData = [];
        let currentRound = 0;
        let score = 0;
        let currentData = {};
        let isWaiting = false;
        let sessionMistakes = {};

        const sentenceText = document.getElementById('sentence-text');
        const questionText = document.getElementById('question-text');
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

            // Setkan apa yang patut berlaku apabila audio fail MP3 selesai
            audioPlayer.onended = function() {
                // Selepas ayat habis, tunggu 500ms (rehat sekejap) kemudian main soalan
                setTimeout(() => playQuestionAudio(), 500);
            };

            if (currentData.sen_audio) {
                audioPlayer.src = currentData.sen_audio;
                audioPlayer.play().catch(e => {
                    // Jika fail MP3 gagal, guna TTS
                    playTTSSentenceWithCallback(currentData.sentence);
                });
            } else {
                // Jika tiada fail MP3 langsung, guna TTS
                playTTSSentenceWithCallback(currentData.sentence);
            }
        }

        function playQuestionAudio() {
            if (isWaiting) return;

            // Pastikan audioPlayer bersih dari arahan 'onended' sebelumnya
            audioPlayer.onended = null;

            if (currentData.que_audio) {
                audioPlayer.src = currentData.que_audio;
                audioPlayer.play().catch(e => {
                    playPhonicsTTS(currentData.question, 0.9);
                });
            } else {
                playPhonicsTTS(currentData.question, 0.9);
            }
        }

        // --- KAWALAN PERMAINAN ---
        function startGame() {
            document.getElementById('start-overlay').style.display = 'none';
            shuffledData = [...gameData].sort(() => Math.random() - 0.5);
            if (shuffledData.length > MAX_ROUNDS) shuffledData.length = MAX_ROUNDS;

            score = 0;
            currentRound = 0;
            sessionMistakes = {};
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
            currentData = shuffledData[currentRound];

            // Set Teks
            sentenceText.innerText = currentData.sentence;
            questionText.innerText = currentData.question;
            updateUI();

            // Reset Feedback
            feedbackBadge.classList.remove('opacity-100');
            feedbackBadge.classList.add('opacity-0');

            generateCards();

            // Auto Play Audio Ayat selepas 1 saat
            setTimeout(() => playSentenceAudio(), 1000);
        }

        function generateCards() {
            optionsContainer.innerHTML = '';

            // Satukan jawapan betul & salah jadi bentuk array object
            let options = [{
                    word: currentData.ans,
                    img: currentData.ansImg,
                    isCorrect: true
                },
                {
                    word: currentData.distractors[0],
                    img: currentData.disImgs[0],
                    isCorrect: false
                },
                {
                    word: currentData.distractors[1],
                    img: currentData.disImgs[1],
                    isCorrect: false
                }
            ];

            // Fallback placeholder imej jika asset tiada
            options.forEach(opt => {
                if (opt.img.includes('{{ asset') || opt.img === '') {
                    opt.img = `https://placehold.co/200x200/CBD5E1/475569?text=${opt.word}`;
                }
            });

            // Shuffle kad
            options.sort(() => Math.random() - 0.5);

            options.forEach(opt => {
                const btn = document.createElement('button');
                btn.className = 'btn-3d ans-card transition-all';
                btn.dataset.correct = opt.isCorrect;

                btn.innerHTML = `
                    <div class="ans-img-container">
                        <img src="${opt.img}" class="ans-img" alt="${opt.word}">
                    </div>
                    <span class="ans-text">${opt.word}</span>
                `;

                btn.addEventListener('mouseenter', () => {
                    if (!isWaiting) {
                        hoverSound.currentTime = 0;
                        hoverSound.play().catch(e => {});
                    }
                });

                btn.onclick = () => checkAnswer(opt.isCorrect, btn, opt.word);
                optionsContainer.appendChild(btn);
            });
        }

        function checkAnswer(isCorrect, btnElement, selectedWord) {
            if (isWaiting) return;
            isWaiting = true;

            // Kunci kesemua butang agar pemain hanya boleh menjawab 1 kali
            const allBtns = document.querySelectorAll('.ans-card');
            allBtns.forEach(btn => btn.disabled = true);

            if (isCorrect === "true" || isCorrect === true) {
                // JAWAPAN BETUL
                score += 2; // 2 Markah setiap pusingan
                updateUI();

                correctSound.currentTime = 0;
                correctSound.play().catch(e => {});

                btnElement.classList.add('correct-choice');
                createSparkle(btnElement);

                feedbackIcon.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
                feedbackIcon.className = "text-8xl drop-shadow-lg mb-4 text-green-500";
                feedbackText.innerText = 'Betul!';
                feedbackText.className = "text-3xl font-black text-green-600 font-lexend";
                feedbackBadge.classList.replace('opacity-0', 'opacity-100');


                setTimeout(() => {
                    currentRound++;
                    loadQuestion();
                }, 2500);

            } else {
                // JAWAPAN SALAH
                wrongSound.currentTime = 0;
                wrongSound.play().catch(e => {});

                btnElement.classList.add('wrong-choice');
                btnElement.classList.add('shake');

                let mistakeKey = `${currentData.ans}_${selectedWord}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                // Serlahkan butang yang SEPATUTNYA betul supaya mereka belajar
                allBtns.forEach(btn => {
                    if (btn.dataset.correct === "true") {
                        btn.classList.add('correct-choice');
                    }
                });

                feedbackIcon.innerHTML = '<i class="fa-solid fa-circle-xmark"></i>';
                feedbackIcon.className = "text-8xl drop-shadow-lg mb-4 text-red-500";
                feedbackText.innerText = 'Cuba Lagi';
                feedbackText.className = "text-3xl font-black text-red-600 font-lexend";
                feedbackBadge.classList.replace('opacity-0', 'opacity-100');

                // Bergerak terus ke soalan seterusnya tanpa mengulangi pusingan
                setTimeout(() => {
                    btnElement.classList.remove('shake');
                    currentRound++;
                    loadQuestion();
                }, 3000);
            }
        }

        function createSparkle(parent) {
            const sparkle = document.createElement('div');
            sparkle.classList.add('sparkle-effect');
            sparkle.innerHTML = '<i class="fa-solid fa-sparkles text-yellow-400"></i>';
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

        // Disebabkan ada 10 soalan x 2 markah = Maksimum 20 markah
        function calculateStars() {
            return score >= 16 ? 3 : (score >= 10 ? 2 : 1);
        }

        function endGame() {
            document.getElementById('final-score').innerText = score;
            const earnedStars = calculateStars();
            renderStars(earnedStars);
            winSound.currentTime = 0;
            winSound.play().catch(e => {});

            const winOverlay = document.getElementById('win-overlay');
            winOverlay.classList.remove('hidden');

            window.speechSynthesis.cancel();

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
