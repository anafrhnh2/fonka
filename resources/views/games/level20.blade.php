@extends('layouts.game')

@section('game-title', 'Tahap 20: Latihan Mendengar')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 20 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#F0FDF4] transition-all duration-300">



        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0 z-20">
                <div class="flex items-center bg-emerald-100 border-4 border-emerald-300 rounded-full px-4 py-2 shadow-sm">
                    <span class="text-emerald-800 font-black text-sm md:text-xl uppercase tracking-wider font-lexend">
                        <i class="fa-solid fa-ear-listen mr-2"></i> Latihan Mendengar
                    </span>
                </div>
                <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                    class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                    <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                </button>
            </div>

            <div id="game-board"
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-emerald-200 w-full flex flex-col justify-center items-center bg-[#D1FAE5] p-4">
                <div
                    class="absolute inset-0 z-0 pointer-events-none opacity-30 bg-[url('https://www.transparenttextures.com/patterns/wood-pattern.png')]">
                </div>

                <div class="flex flex-col md:flex-row w-full h-full max-w-6xl z-10 gap-4">

                    <div
                        class="w-full md:w-2/5 bg-[#FFFDF0] border-8 border-emerald-400 rounded-3xl p-6 flex flex-col items-center justify-center shadow-lg relative shrink-0">
                        <h2 class="text-emerald-800 font-black text-2xl mb-8 text-center uppercase tracking-widest">Dengar
                            Audio</h2>

                        <button id="btn-play-pause" onclick="toggleAudio()"
                            class="w-32 h-32 md:w-48 md:h-48 bg-[#10B981] text-white rounded-[2.5rem] flex items-center justify-center text-7xl shadow-[0_8px_0_#047857] hover:translate-y-1 hover:shadow-[0_4px_0_#047857] active:translate-y-2 active:shadow-none transition-all mb-12">
                            <i class="fa-solid fa-volume-high" id="audio-icon"></i>
                        </button>

                        <div class="w-full max-w-[90%] bg-emerald-200 h-4 rounded-full mb-4 overflow-hidden relative shadow-inner cursor-pointer"
                            id="progress-container" onclick="seekAudio(event)">
                            <div id="audio-progress" class="bg-[#F59E0B] h-full w-0 transition-all duration-75"></div>
                        </div>
                        <div class="text-[#047857] font-black text-2xl tracking-widest font-mono">
                            <span id="time-current">00:00</span> / <span id="time-total">00:00</span>
                        </div>
                    </div>

                    <div
                        class="w-full md:w-3/5 bg-[#FFFDF0] border-8 border-amber-300 rounded-3xl p-6 md:p-8 flex flex-col shadow-lg relative">

                        <div id="step-indicator"
                            class="flex items-center mb-6 w-full overflow-x-auto custom-scrollbar pb-2 font-lexend px-2">
                        </div>

                        <div id="question-container"
                            class="flex-1 flex flex-col font-lexend overflow-y-auto custom-scrollbar pr-4 gap-8 pb-4">
                        </div>

                        <div class="mt-4 pt-4 border-t-4 border-amber-100 flex justify-between shrink-0">
                            <button id="btn-prev" onclick="prevBabak()"
                                class="bg-slate-400 text-white font-black text-xl px-6 py-3 rounded-full border-b-[6px] border-slate-600 hover:bg-slate-500 active:translate-y-2 active:border-b-0 transition-all shadow-lg flex items-center gap-2"
                                style="display: none;">
                                <i class="fa-solid fa-chevron-left"></i> Kembali
                            </button>

                            <button id="btn-next" onclick="handleNextAction()"
                                class="bg-[#3B82F6] text-white font-black text-xl px-8 py-3 rounded-full border-b-[6px] border-[#2563EB] hover:bg-[#2563EB] active:translate-y-2 active:border-b-0 transition-all shadow-lg flex items-center gap-2 ml-auto">
                                Semak Jawapan <i class="fa-solid fa-check"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-emerald-900/80 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">
            <div
                class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full flex flex-col border-[6px] border-emerald-500 font-lexend relative overflow-hidden">
                <i class="fa-solid fa-leaf absolute -top-4 -left-4 text-6xl text-emerald-100 rotate-45"></i>
                <i class="fa-solid fa-leaf absolute -bottom-4 -right-4 text-6xl text-emerald-100 -rotate-45"></i>

                <div class="text-7xl mb-2 text-emerald-600 z-10"><i class="fa-solid fa-tree"></i></div>
                <h1 class="text-3xl md:text-4xl font-black text-emerald-700 mb-2 z-10">Kisah Sang Kancil</h1>
                <p class="text-amber-600 font-bold text-lg mb-8 z-10">Ujian Pemahaman Audio</p>

                <div class="flex flex-col gap-4 text-left mx-auto w-full md:w-[95%] mb-10 z-10">
                    <div class="flex items-center gap-4 bg-emerald-50 p-4 rounded-xl border-2 border-emerald-200 shadow-sm">
                        <div
                            class="w-12 h-12 bg-emerald-200 text-emerald-700 rounded-full flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-headphones"></i>
                        </div>
                        <p class="text-slate-700 font-bold text-[15px] leading-tight">Dengar cerita Sang Kancil dengan
                            teliti</p>
                    </div>
                    <div class="flex items-center gap-4 bg-amber-50 p-4 rounded-xl border-2 border-amber-200 shadow-sm">
                        <div
                            class="w-12 h-12 bg-amber-200 text-amber-700 rounded-full flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <p class="text-slate-700 font-bold text-[15px] leading-tight">Pilih jawapan yang betul berdasarkan
                            babak</p>
                    </div>
                </div>

                <button onclick="startGame()"
                    class="bg-amber-500 text-white w-full text-2xl font-black py-4 rounded-[1.5rem] flex justify-center items-center gap-3 border-b-[6px] border-amber-700 hover:bg-amber-400 active:translate-y-2 active:border-b-0 transition-all z-10">
                    <i class="fa-solid fa-play"></i> Mula
                </button>
            </div>
        </div>

        <div id="win-overlay"
            class="hidden absolute inset-0 bg-black/80 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8 overflow-y-auto">
            <div
                class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full animate-bounce-in border-[6px] border-amber-200 relative my-auto">
                <div
                    class="absolute -top-12 left-1/2 transform -translate-x-1/2 text-7xl drop-shadow-lg text-yellow-400 bg-white rounded-full p-2 border-4 border-yellow-200 w-32 h-32 flex justify-center items-center">
                    <i class="fa-solid fa-trophy"></i>
                </div>

                <h1 class="text-4xl md:text-5xl font-black text-emerald-600 mb-2 mt-6">Tahniah!</h1>
                <div class="bg-emerald-50 p-6 rounded-[2rem] mb-8 border-4 border-emerald-100 shadow-inner">
                    <div class="flex justify-center gap-2 mb-4" id="star-container"></div>
                    <p class="font-black text-emerald-800 uppercase tracking-widest mb-1 text-sm">Jumlah Markah</p>
                    <p class="text-6xl font-black text-emerald-600 drop-shadow-sm" id="final-score">0</p>
                </div>

                <div class="flex flex-col gap-4">
                    <button onclick="saveAndExit(event)"
                        class="w-full bg-[#10B981] text-white text-xl md:text-2xl font-bold py-4 rounded-[1.5rem] border-b-[6px] border-[#059669] hover:bg-[#059669] active:translate-y-2 active:border-b-0 transition-all">
                        Tahap Seterusnya <i class="fa-solid fa-forward-step"></i>
                    </button>
                    <button onclick="restartGame()"
                        class="w-full bg-slate-200 text-slate-700 text-lg md:text-xl font-bold py-4 rounded-[1.5rem] border-b-[6px] border-slate-300 hover:bg-slate-300 active:translate-y-2 active:border-b-0 transition-all">
                        <i class="fa-solid fa-rotate-right"></i> Main Semula
                    </button>
                </div>
            </div>
        </div>

    </div>

    <audio id="main-audio" src=""></audio>
    <audio id="sfx-click" src="https://assets.mixkit.com/sfx/preview/mixkit-pop-click-3104.mp3"></audio>
    <audio id="sfx-success" src="https://assets.mixkit.com/sfx/preview/mixkit-winning-chimes-2015.mp3"></audio>

    <style>
        .font-lexend {
            font-family: 'Lexend', sans-serif;
        }

        * {
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #E2E8F0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #94A3B8;
            border-radius: 10px;
        }

        .opt-btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .opt-btn:active {
            transform: scale(0.98);
        }

        /* Kelas Untuk Tunjuk Jawapan */
        .selected-opt {
            border-color: #3B82F6 !important;
            background-color: #EFF6FF !important;
        }

        .selected-opt .letter-box {
            background-color: #3B82F6 !important;
        }

        .correct-opt {
            border-color: #10B981 !important;
            background-color: #D1FAE5 !important;
        }

        .correct-opt .letter-box {
            background-color: #10B981 !important;
        }

        .wrong-opt {
            border-color: #EF4444 !important;
            background-color: #FEE2E2 !important;
        }

        .wrong-opt .letter-box {
            background-color: #EF4444 !important;
        }

        .disabled-opt {
            pointer-events: none;
            opacity: 0.8;
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
    </style>

    <script>
        // ==========================================
        // TETAPAN DATA: Audio berbeza setiap babak
        // ==========================================
        const gameData = {
            scenes: [{
                    audioUrl: '{{ asset('audio/Scene1.mp3') }}',
                    questions: [{
                            question: "1. Dimanakah Sang Kancil berjalan?",
                            options: ["Taman", "Hutan", "Padang"],
                            answer: 1
                        },
                        {
                            question: "2. Burung-burung ___ riang",
                            options: ["Terbang", "Bersiul", "Berkicau"],
                            answer: 2
                        }
                    ]
                },
                {
                    audioUrl: '{{ asset('audio/Scene2.mp3') }}',
                    questions: [{
                            question: "1. Sang kancil terhidu bau yang sangat?",
                            options: ["Wangi", "Busuk", "Sedap"],
                            answer: 0
                        },
                        {
                            question: "2. Bau itu membuatkan sang kancil rasa?",
                            options: ["Gembira", "Lapar", "Takut"],
                            answer: 1
                        }
                    ]
                },
                {
                    audioUrl: '{{ asset('audio/Scene3.mp3') }}',
                    questions: [{
                        question: "Apakah buah yang Sang Kancil hidu?",
                        options: ["Betik", "Rambutan", "Jambu"],
                        answer: 2
                    }]
                },
                {
                    audioUrl: '{{ asset('audio/Scene4.mp3') }}',
                    questions: [{
                        question: "Dimanakan buah itu berada?",
                        options: ["Tepi sungai", "Seberang sungai", "Atas pokok"],
                        answer: 1
                    }]
                },
                {
                    audioUrl: '{{ asset('audio/Scene5.mp3') }}',
                    questions: [{
                        question: "Haiwan apa yang ada didalam sungai itu?",
                        options: ["Buaya", "Ikan", "Ular"],
                        answer: 0
                    }]
                },
                {
                    audioUrl: '{{ asset('audio/Scene6.mp3') }}',
                    questions: [{
                        question: "Mengapakah Sang Kancil tersenyum kecil selepas mendapat akal yang bijak?",
                        options: ["Kerana dia ingin tidur",
                            "Kerana dia mempunyai rancangan untuk menyeberangi sungai",
                            "Kerana dia takut kepada buaya"
                        ],
                        answer: 1
                    }]
                },
                {
                    audioUrl: '{{ asset('audio/Scene7.mp3') }}',
                    questions: [{
                            question: "1. Apakah rancangan yang dibuat oleh Sang Kancil untuk menyeberangi sungai?",
                            options: ["Membina jambatan daripada kayu", "Berenang sendiri ke seberang",
                                "Meminta buaya beratur untuk dilompat"
                            ],
                            answer: 2
                        },
                        {
                            question: "2. Mengapa buaya-buaya itu percaya kepada kata-kata Sang Kancil dan beratur?",
                            options: ["Kerana mereka ingin bermain",
                                "Kerana mereka percaya akan mendapat makanan daripada raja hutan",
                                "Kerana mereka takut kepada Sang Kancil"
                            ],
                            answer: 1
                        }
                    ]
                },
                {
                    audioUrl: '{{ asset('audio/Scene8.mp3') }}',
                    questions: [{
                            question: "1. Apakah yang dilakukan oleh Sang Kancil untuk menyeberangi sungai?",
                            options: ["Berenang perlahan-lahan", "Melompat dari satu buaya ke buaya lain",
                                "Terbang ke seberang sungai"
                            ],
                            answer: 1
                        },
                        {
                            question: "2. Bagaimanakah cara Sang Kancil melompat?",
                            options: ["Dengan perlahan dan cuai", "Dengan pantas dan berhati-hati",
                                "Dengan takut dan ragu-ragu"
                            ],
                            answer: 1
                        },
                        {
                            question: "3. Apakah yang dilakukan oleh buaya-buaya semasa Sang Kancil melompat?",
                            options: ["Menyerang Sang Kancil", "Berenang menjauh", "Mendiamkan diri"],
                            answer: 3
                        }
                    ]
                },
                {
                    audioUrl: '{{ asset('audio/Scene9.mp3') }}',
                    questions: [{
                        question: "Apakah yang dilakukan oleh Sang Kancil sambil melompat di atas buaya",
                        options: ["Mengira jumlah buaya", "Menyanyi dengan perlahan",
                            "Memanggil kawan-kawannya"
                        ],
                        answer: 0
                    }]
                },
                {
                    audioUrl: '{{ asset('audio/Scene10.mp3') }}',
                    questions: [{
                        question: "Apakah yang berlaku selepas Sang Kancil sampai di seberang sungai?",
                        options: ["Dia terus pulang ke hutan", "Dia tidur di bawah pokok",
                            "Dia makan buah jambu yang masak"
                        ],
                        answer: 2
                    }]
                }
            ]
        };
        const fsContainer = document.getElementById('game-fullscreen-container');
        const fullscreenIcon = document.getElementById('fullscreen-icon');
        window.addEventListener('resize', () => {

            if (window.innerHeight === screen.height) {
                fsContainer.classList.add('is-fullscreen');
            } else {
                fsContainer.classList.remove('is-fullscreen');
            }

        });
        let currentSceneIndex = 0;
        let userAnswers = {};
        let sceneSubmitted = {};
        const letters = ['A', 'B', 'C', 'D'];


        const mainAudio = document.getElementById('main-audio');
        const audioIcon = document.getElementById('audio-icon');
        const progressFill = document.getElementById('audio-progress');
        const timeCurrent = document.getElementById('time-current');
        const timeTotal = document.getElementById('time-total');
        const sfxClick = document.getElementById('sfx-click');
        const sfxSuccess = document.getElementById('sfx-success');
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

        function formatTime(seconds) {
            if (isNaN(seconds)) return "00:00";
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = Math.floor(seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        mainAudio.addEventListener('loadedmetadata', () => {
            timeTotal.innerText = formatTime(mainAudio.duration);
        });
        mainAudio.addEventListener('timeupdate', () => {
            timeCurrent.innerText = formatTime(mainAudio.currentTime);
            progressFill.style.width = `${(mainAudio.currentTime / mainAudio.duration) * 100}%`;
        });
        mainAudio.addEventListener('ended', () => {
            audioIcon.classList.replace('fa-pause', 'fa-volume-high');
        });

        function toggleAudio() {
            if (mainAudio.paused) {
                mainAudio.play();
                audioIcon.classList.replace('fa-volume-high', 'fa-pause');
            } else {
                mainAudio.pause();
                audioIcon.classList.replace('fa-pause', 'fa-volume-high');
            }
        }

        function seekAudio(e) {
            const rect = document.getElementById('progress-container').getBoundingClientRect();
            mainAudio.currentTime = ((e.clientX - rect.left) / rect.width) * mainAudio.duration;
        }

        function startGame() {
            document.getElementById('start-overlay').style.display = 'none';
            currentSceneIndex = 0;
            userAnswers = {};
            sceneSubmitted = {}; // Reset status
            renderBabak();
        }

        function renderBabak() {
            const currentScene = gameData.scenes[currentSceneIndex];
            const totalScenes = gameData.scenes.length;
            const isSubmitted = sceneSubmitted[currentSceneIndex] || false;

            // Tukar Audio berdasarkan babak
            if (!mainAudio.src.includes(currentScene.audioUrl)) {
                mainAudio.src = currentScene.audioUrl;
                mainAudio.load();
                audioIcon.classList.replace('fa-pause', 'fa-volume-high');
                progressFill.style.width = '0%';
                timeCurrent.innerText = "00:00";
            }

            let stepsHTML = '';
            for (let i = 0; i < totalScenes; i++) {
                let bgColor = i === currentSceneIndex ? 'bg-[#10B981] text-white border-4 border-emerald-200' :
                    (i < currentSceneIndex ? 'bg-[#D97706] text-white' : 'bg-[#FBBF24] text-white');
                stepsHTML +=
                    `<div class="w-10 h-10 min-w-[2.5rem] rounded-full ${bgColor} flex items-center justify-center font-black z-10 shadow-sm text-lg">${i + 1}</div>`;
                if (i < totalScenes - 1) {
                    let lineColor = i < currentSceneIndex ? 'bg-[#D97706]' : 'bg-[#FBBF24]';
                    stepsHTML += `<div class="h-2 min-w-[2rem] flex-1 ${lineColor} -mx-1 z-0"></div>`;
                }
            }
            document.getElementById('step-indicator').innerHTML = stepsHTML;

            let qHTML = '';
            currentScene.questions.forEach((q, qIndex) => {
                qHTML +=
                    `<div class="mb-2"><h3 class="font-black text-slate-800 text-xl md:text-2xl mb-4 leading-tight">${q.question}</h3><div class="flex flex-col gap-3">`;

                const hasAnswered = userAnswers[currentSceneIndex] && userAnswers[currentSceneIndex][qIndex] !==
                    undefined;
                const chosenAnswer = hasAnswered ? userAnswers[currentSceneIndex][qIndex] : null;

                q.options.forEach((opt, oIndex) => {
                    let extraClasses = '';

                    if (hasAnswered) {
                        if (isSubmitted) {
                            // Tunjuk jawapan betul/salah hanya jika sudah disemak
                            extraClasses += ' disabled-opt';
                            if (oIndex === q.answer) {
                                extraClasses += ' correct-opt';
                            } else if (chosenAnswer === oIndex && oIndex !== q.answer) {
                                extraClasses += ' wrong-opt';
                            }
                        } else {
                            // Tunjuk status dipilih sahaja sebelum disemak
                            if (chosenAnswer === oIndex) {
                                extraClasses += ' selected-opt';
                            }
                        }
                    }

                    qHTML += `
                        <button id="btn-opt-${qIndex}-${oIndex}" onclick="selectOption(${qIndex}, ${oIndex})" class="opt-btn opt-group-${qIndex} flex w-full bg-white border-[4px] border-slate-200 rounded-2xl overflow-hidden hover:border-slate-300 ${extraClasses}">
                            <div class="letter-box bg-[#F87171] text-white text-xl font-black w-14 py-3 flex items-center justify-center shrink-0 transition-colors">
                                ${letters[oIndex]}
                            </div>
                            <div class="p-3 text-left font-bold text-slate-700 text-lg flex-1 flex items-center">
                                ${opt}
                            </div>
                        </button>
                    `;
                });
                qHTML += `</div></div>`;
            });
            document.getElementById('question-container').innerHTML = qHTML;

            // Logik paparan butang kembali & seterusnya
            const btnPrev = document.getElementById('btn-prev');
            if (currentSceneIndex > 0) {
                btnPrev.style.display = 'flex';
            } else {
                btnPrev.style.display = 'none';
            }

            const btnNext = document.getElementById('btn-next');
            if (!isSubmitted) {
                // Keadaan sebelum semak jawapan
                btnNext.innerHTML = 'Semak Jawapan <i class="fa-solid fa-check"></i>';
                btnNext.className =
                    "bg-[#3B82F6] text-white font-black text-xl px-8 py-3 rounded-full border-b-[6px] border-[#2563EB] hover:bg-[#2563EB] active:translate-y-2 active:border-b-0 transition-all shadow-lg flex items-center gap-2 ml-auto";
            } else {
                // Keadaan selepas semak jawapan (mahu ke babak seterusnya atau habis)
                if (currentSceneIndex === totalScenes - 1) {
                    btnNext.innerHTML = 'Semak Keputusan <i class="fa-solid fa-check-double"></i>';
                } else {
                    btnNext.innerHTML = 'Seterusnya <i class="fa-solid fa-chevron-right"></i>';
                }
                btnNext.className =
                    "bg-[#F59E0B] text-white font-black text-xl px-8 py-3 rounded-full border-b-[6px] border-[#B45309] hover:bg-[#D97706] active:translate-y-2 active:border-b-0 transition-all shadow-lg flex items-center gap-2 ml-auto";
            }
        }

        function selectOption(qIndex, oIndex) {
            // Jika babak dah disemak, jangan benarkan ubah
            if (sceneSubmitted[currentSceneIndex]) return;

            if (!userAnswers[currentSceneIndex]) {
                userAnswers[currentSceneIndex] = {};
            }

            sfxClick.currentTime = 0;
            sfxClick.play().catch(e => {});

            userAnswers[currentSceneIndex][qIndex] = oIndex;

            // Tunjuk status dipilih (warna biru), buang status pada pilihan lain untuk soalan yang sama
            const buttons = document.querySelectorAll(`.opt-group-${qIndex}`);
            buttons.forEach((btn, i) => {
                btn.classList.remove('selected-opt');
                if (i === oIndex) {
                    btn.classList.add('selected-opt');
                }
            });
        }

        function handleNextAction() {
            if (!sceneSubmitted[currentSceneIndex]) {
                checkAnswers();
            } else {
                submitBabak();
            }
        }

        function checkAnswers() {
            const currentSceneQuestions = gameData.scenes[currentSceneIndex].questions;
            const answeredInScene = userAnswers[currentSceneIndex] ? Object.keys(userAnswers[currentSceneIndex]).length : 0;

            if (answeredInScene < currentSceneQuestions.length) {
                alert("Sila pilih jawapan untuk semua soalan dalam babak ini terlebih dahulu!");
                return;
            }

            let correctCount = 0;

            currentSceneQuestions.forEach((q, qIndex) => {
                if (userAnswers[currentSceneIndex][qIndex] === q.answer) {
                    correctCount++;
                }
            });

            if (correctCount === currentSceneQuestions.length) {
                correctSound.currentTime = 0;
                correctSound.play().catch(() => {});
            } else {
                wrongSound.currentTime = 0;
                wrongSound.play().catch(() => {});
            }

            // mark submitted
            sceneSubmitted[currentSceneIndex] = true;
            renderBabak();
        }

        function prevBabak() {
            if (currentSceneIndex > 0) {
                currentSceneIndex--;
                renderBabak();
            }
        }

        function submitBabak() {
            // Bergerak ke babak seterusnya atau tamatkan game
            if (currentSceneIndex < gameData.scenes.length - 1) {
                currentSceneIndex++;
                renderBabak();
            } else {
                finishGame();
            }
        }

        function finishGame() {
            mainAudio.pause();
            let correctCount = 0;
            let totalQ = 0;

            gameData.scenes.forEach((scene, sIndex) => {
                scene.questions.forEach((q, qIndex) => {
                    totalQ++;
                    if (userAnswers[sIndex] && userAnswers[sIndex][qIndex] === q.answer) {
                        correctCount++;
                    }
                });
            });

            showResult(correctCount, totalQ);
        }

        function showResult(correctCount, totalQ) {
            winSound.currentTime = 0;
            winSound.play().catch(() => {});
            const finalScore = Math.round((correctCount / totalQ) * 100);

            document.getElementById('final-score').innerText = finalScore;

            let stars = finalScore >= 80 ? 3 : (finalScore >= 50 ? 2 : 1);
            const container = document.getElementById('star-container');
            container.innerHTML = '';
            for (let i = 0; i < 3; i++) {
                const starColor = i < stars ? 'text-yellow-400 drop-shadow-md' : 'text-slate-200';
                const starSize = i === 1 ? 'text-6xl -mt-4' : 'text-5xl';
                container.innerHTML += `<i class="fa-solid fa-star ${starColor} ${starSize} transition-all"></i>`;
            }

            document.getElementById('win-overlay').classList.remove('hidden');
        }

        function restartGame() {
            mainAudio.currentTime = 0;
            document.getElementById('win-overlay').classList.add('hidden');
            startGame();
        }

        function saveAndExit(event) {
            const btn = event.target;
            btn.disabled = true;
            btn.innerText = "Menyimpan...";

            let correctCount = 0;
            let totalQ = 0;
            gameData.scenes.forEach((s, sIndex) => {
                s.questions.forEach((q, qIndex) => {
                    totalQ++;
                    if (userAnswers[sIndex] && userAnswers[sIndex][qIndex] === q.answer) correctCount++;
                });
            });

            // total markah = 100
            // 3 Bintang: Jika markah diperoleh > 80% (Betul 12 soalan ke atas)
            // 2 Bintang: Jika markah diperoleh di antara 50% - 79% (Betul 8 hingga 11 soalan)
            // 1 Bintang: Jika markah diperoleh di bawah 50% (Betul kurang daripada 8 soalan).

            const finalScore = Math.round((correctCount / totalQ) * 100);
            let stars = finalScore >= 80 ? 3 : (finalScore >= 50 ? 2 : 1);

            fetch(window.gameConfig.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    child_id: window.gameConfig.childId,
                    game_level_id: window.gameConfig.levelId,
                    score: finalScore,
                    stars_earned: stars,
                    is_completed: true
                })
            }).then(r => r.json()).then(data => {
                if (data.success) window.location.href = window.gameConfig.nextLevelUrl;
                else {
                    alert("Ralat: " + data.message);
                    btn.disabled = false;
                    btn.innerHTML = "Cuba Lagi";
                }
            }).catch(e => {
                alert("Ralat rangkaian.");
                btn.disabled = false;
                btn.innerHTML = "Cuba Lagi";
            });
        }
    </script>
@endsection
