@extends('layouts.game')

@section('game-title', 'Belajar Huruf Konsonan')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <script>
        window.moduleConfig = {
            saveUrl: "{{ route('reading.save_progress') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            moduleId: {{ $module->id ?? 3 }},
            backUrl: "{{ route('reading.index') }}",
            savedPercentage: {{ $currentProgress ?? 0 }}
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#F0F9FF] transition-all duration-300">

        <button onclick="toggleFullScreen()"
            class="absolute bottom-6 right-6 z-50 w-14 h-14 bg-slate-800/80 text-sky-400 rounded-full flex items-center justify-center hover:bg-slate-700 hover:scale-110 active:scale-95 transition-all shadow-[0_4px_10px_rgba(0,0,0,0.5)] border-4 border-sky-500 backdrop-blur-sm">
            <i id="fullscreen-icon" class="fas fa-expand text-2xl"></i>
        </button>

        <div class="flex flex-wrap justify-between items-center mb-2 px-4 py-4 shrink-0 z-20 w-full max-w-5xl mx-auto">
            <button onclick="saveAndExitEarly()"
                class="bg-white border-4 border-slate-200 text-slate-600 font-bold px-6 py-2 rounded-full hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> <span id="btn-back-text">Simpan & Kembali</span>
            </button>

            <div class="flex items-center gap-3 md:gap-5 font-lexend">
                <div class="flex flex-col items-center bg-white border-4 border-sky-200 rounded-2xl px-4 py-1 shadow-sm">
                    <span class="text-sky-500 font-bold text-xs md:text-sm uppercase tracking-wider">Tahap</span>
                    <span id="round-display" class="text-sky-700 font-black text-xl md:text-2xl">1/21</span>
                </div>
            </div>
        </div>

        <div class="flex-1 w-full max-w-4xl mx-auto flex flex-col items-center justify-center relative z-10 px-4 pb-10">

            <div class="w-full max-w-md bg-slate-200 rounded-full h-3 mb-4 overflow-hidden shadow-inner">
                <div id="progress-bar" class="bg-sky-500 h-3 rounded-full transition-all duration-500" style="width: 0%">
                </div>
            </div>

            <div id="flashcard"
                class="bg-white border-8 border-sky-100 rounded-[3rem] shadow-2xl w-full max-w-md flex flex-col items-center p-8 relative transition-all duration-500">

                <button onclick="playExample()"
                    class="absolute top-6 left-6 w-12 h-12 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center text-xl border-4 border-amber-200 hover:bg-amber-200 active:scale-95 transition-all"
                    title="Dengar Contoh">
                    <i class="fa-solid fa-volume-high"></i>
                </button>

                <h1 id="letter-display"
                    class="text-[6rem] md:text-[8rem] font-black text-sky-500 font-lexend leading-none mt-4 drop-shadow-sm">
                    B</h1>

                <div
                    class="bg-sky-50 border-4 border-sky-100 w-full rounded-2xl py-4 flex flex-col items-center justify-center mb-4 mt-2 shadow-inner">
                    <p class="text-slate-400 font-bold text-sm uppercase tracking-widest mb-1">Perkataan <span
                            id="word-count">1/3</span></p>
                    <h2 id="word-display" class="text-4xl md:text-5xl font-black text-slate-700 font-lexend capitalize">Bola
                    </h2>
                </div>

                <div
                    class="w-full h-12 flex items-center justify-center bg-slate-50 rounded-xl border-2 border-slate-100 mb-2 px-2">
                    <span id="transcript-display"
                        class="text-slate-400 font-bold text-sm italic text-center w-full truncate">Sistem
                        bersedia...</span>
                </div>

                <p id="status-text" class="text-slate-600 font-bold text-lg text-center mt-2 h-8 transition-colors">Tekan
                    mic dan sebut perkataan di atas</p>

                <button id="btn-mic" onclick="toggleListening()"
                    class="mt-4 w-20 h-20 bg-sky-500 text-white rounded-full flex items-center justify-center text-4xl border-b-8 border-sky-700 active:border-b-0 active:translate-y-2 transition-all shadow-[0_10px_20px_rgba(14,165,233,0.4)] relative z-20">
                    <i class="fa-solid fa-microphone"></i>
                </button>

                <div id="mic-ripple"
                    class="absolute bottom-10 w-20 h-20 bg-sky-400 rounded-full opacity-0 pointer-events-none z-10"></div>

                <div id="success-overlay"
                    class="absolute inset-0 bg-slate-900/60 rounded-[2.5rem] flex flex-col items-center justify-center opacity-0 pointer-events-none transition-all duration-300 z-30 backdrop-blur-sm overflow-hidden">

                    <div class="absolute inset-0 flex items-center justify-center opacity-30">
                        <div
                            class="w-[200%] h-[200%] bg-[conic-gradient(from_0deg,transparent_0_15deg,#bae6fd_15deg_30deg,transparent_30deg_45deg,#bae6fd_45deg_60deg,transparent_60deg_75deg,#bae6fd_75deg_90deg,transparent_90deg_105deg,#bae6fd_105deg_120deg,transparent_120deg_135deg,#bae6fd_135deg_150deg,transparent_150deg_165deg,#bae6fd_165deg_180deg,transparent_180deg_195deg,#bae6fd_195deg_210deg,transparent_210deg_225deg,#bae6fd_225deg_240deg,transparent_240deg_255deg,#bae6fd_255deg_270deg,transparent_270deg_285deg,#bae6fd_285deg_300deg,transparent_300deg_315deg,#bae6fd_315deg_330deg,transparent_330deg_345deg,#bae6fd_345deg_360deg)] animate-[spin_10s_linear_infinite]">
                        </div>
                    </div>

                    <div id="success-content"
                        class="relative z-10 flex flex-col items-center justify-center scale-50 opacity-0 transition-all duration-500 ease-[cubic-bezier(0.175,0.885,0.32,1.275)]">

                        <div class="flex justify-center items-end gap-1 mb-2 drop-shadow-[0_10px_15px_rgba(0,0,0,0.5)]">
                            <i
                                class="fa-solid fa-star text-[6rem] md:text-[7rem] text-amber-400 z-10 drop-shadow-[0_0_20px_rgba(251,191,36,0.8)]"></i>
                        </div>

                        <div
                            class="bg-gradient-to-b from-green-400 to-green-600 px-10 py-3 rounded-2xl border-4 border-white shadow-[0_8px_0_#166534,0_15px_20px_rgba(0,0,0,0.4)] transform -rotate-3 hover:rotate-0 transition-transform cursor-default">
                            <span
                                class="text-white font-black text-4xl md:text-5xl font-lexend uppercase tracking-widest drop-shadow-[0_4px_2px_rgba(0,0,0,0.5)]">Betul!</span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center w-full max-w-md mt-6 px-4">
                <button onclick="skipWord()" id="btn-skip"
                    class="">
                </button>
            </div>

        </div>

        <div id="win-overlay"
            class="hidden absolute inset-0 bg-slate-900/90 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8">
            <div
                class="bg-white p-8 rounded-[2.5rem] shadow-2xl text-center max-w-md w-full animate-bounce-in border-[6px] border-sky-400 font-lexend relative my-auto">
                <div
                    class="absolute -top-12 left-1/2 transform -translate-x-1/2 text-7xl drop-shadow-lg text-amber-400 bg-white rounded-full p-2 border-4 border-amber-200 w-32 h-32 flex justify-center items-center">
                    <i class="fa-solid fa-trophy"></i>
                </div>

                <h1 class="text-4xl font-black text-sky-600 mb-2 mt-8">Terbaik!</h1>
                <p class="text-slate-500 font-bold mb-6 text-lg">Awak dah mahir semua huruf konsonan.</p>

                <div class="bg-sky-50 p-5 rounded-2xl mb-8 border-4 border-sky-200 shadow-inner">
                    <p class="font-bold text-sky-700 uppercase tracking-widest mb-1">Skor Sebutan</p>
                    <p class="text-6xl font-black text-sky-500"><span id="final-score">0</span></p>

                    <div
                        class="mt-4 inline-block bg-amber-100 border-2 border-amber-300 px-4 py-2 rounded-full animate-pulse">
                        <p class="text-amber-600 font-black text-lg">
                            <i class="fa-solid fa-coins text-amber-500 mr-1"></i> +50 Mata Diperoleh!
                        </p>
                    </div>
                </div>

                <button onclick="saveAndFinish(event)"
                    class="btn-3d bg-sky-500 border-sky-700 text-white w-full text-xl font-bold py-4 rounded-[1.5rem] relative inline-flex items-center justify-center border-b-[6px] active:border-b-[2px] active:translate-y-[4px] transition-all">
                    Selesai & Simpan ▶
                </button>
            </div>
        </div>

    </div>

    <audio id="sfx-correct" src="https://assets.mixkit.com/sfx/preview/mixkit-correct-answer-tone-2870.mp3"></audio>
    <audio id="sfx-wrong" src="https://assets.mixkit.com/sfx/preview/mixkit-buzzer-14.mp3"></audio>
    <audio id="sfx-skip" src="https://assets.mixkit.com/sfx/preview/mixkit-light-click-1136.mp3"></audio>
    <audio id="phonicAudio" preload="auto"></audio>

    <style>
        .font-lexend {
            font-family: 'Lexend', sans-serif;
        }

        @keyframes flipIn {
            0% {
                transform: rotateY(-90deg);
                opacity: 0;
            }

            100% {
                transform: rotateY(0);
                opacity: 1;
            }
        }

        .flip-animation {
            animation: flipIn 0.4s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            100% {
                transform: scale(2.5);
                opacity: 0;
            }
        }

        .is-listening #mic-ripple {
            animation: ripple 1.5s infinite;
        }

        .is-listening #btn-mic {
            background-color: #EF4444;
            border-color: #991B1B;
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.6);
        }

        .wrong-state {
            animation: shake 0.4s;
            border-color: #EF4444 !important;
            background-color: #FEF2F2 !important;
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

        .is-fullscreen #flashcard {
            transform: scale(1.1);
            margin-top: 2rem;
        }
    </style>

    <script>
        const fsContainer = document.getElementById('game-fullscreen-container');
        const fullscreenIcon = document.getElementById('fullscreen-icon');

        window.addEventListener('resize', () => {

            if (window.innerHeight === screen.height) {
                fsContainer.classList.add('is-fullscreen');
            } else {
                fsContainer.classList.remove('is-fullscreen');
            }

        });

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

        const consonantData = [{
                letter: 'B',
                phonic: 'beh',
                words: [{
                    word: 'Bola',
                    expected: ['bola'],
                    audio: "{{ asset('audio/Bola.mp3') }}"
                }, {
                    word: 'Baju',
                    expected: ['baju'],
                    audio: "{{ asset('audio/Baju2.mp3') }}"
                }, {
                    word: 'Buku',
                    expected: ['buku'],
                    audio: "{{ asset('audio/Buku2.mp3') }}"
                }]
            },
            {
                letter: 'C',
                phonic: 'ceh',
                words: [{
                    word: 'Cawan',
                    expected: ['cawan'],
                    audio: "{{ asset('audio/Cawan.mp3') }}"
                }, {
                    word: 'Cacing',
                    expected: ['cacing'],
                    audio: "{{ asset('audio/Cacing.mp3') }}"
                }, {
                    word: 'Cuti',
                    expected: ['cuti'],
                    audio: "{{ asset('audio/Cuti.mp3') }}"
                }]
            },
            {
                letter: 'D',
                phonic: 'deh',
                words: [{
                    word: 'Daun',
                    expected: ['daun'],
                    audio: "{{ asset('audio/Daun.mp3') }}"
                }, {
                    word: 'Dadu',
                    expected: [ 'dadu'],
                    audio: "{{ asset('audio/Dadu.mp3') }}"
                }, {
                    word: 'Duri',
                    expected: ['duri'],
                    audio: "{{ asset('audio/Duri.mp3') }}"
                }]
            },
            {
                letter: 'F',
                phonic: 'feh',
                words: [{
                    word: 'Feri',
                    expected: ['feri'],
                    audio: "{{ asset('audio/Feri.mp3') }}"
                }, {
                    word: 'Foto',
                    expected: ['foto'],
                    audio: "{{ asset('audio/Foto.mp3') }}"
                }, {
                    word: 'Fikir',
                    expected: ['fikir'],
                    audio: "{{ asset('audio/Fikir.mp3') }}"
                }]
            },
            {
                letter: 'G',
                phonic: 'geh',
                words: [{
                    word: 'Gajah',
                    expected: ['gajah'],
                    audio: "{{ asset('audio/Gajah.mp3') }}"
                }, {
                    word: 'Gitar',
                    expected: ['gitar'],
                    audio: "{{ asset('audio/Gitar.mp3') }}"
                }, {
                    word: 'Guli',
                    expected: ['guli'],
                    audio: "{{ asset('audio/guli.mp3') }}"
                }]
            },
            {
                letter: 'H',
                phonic: 'heh',
                words: [{
                    word: 'Hutan',
                    expected: ['hutan'],
                    audio: "{{ asset('audio/Hutan.mp3') }}"
                }, {
                    word: 'Hati',
                    expected: ['hati'],
                    audio: "{{ asset('audio/Hati.mp3') }}"
                }, {
                    word: 'Hari',
                    expected: ['hari'],
                    audio: "{{ asset('audio/Hari.mp3') }}"
                }]
            },
            {
                letter: 'J',
                phonic: 'jeh',
                words: [{
                    word: 'Jam',
                    expected: ['Jam'],
                    audio: "{{ asset('audio/Jam.mp3') }}"
                }, {
                    word: 'Jambu',
                    expected: ['jambu'],
                    audio: "{{ asset('audio/Jus.mp3') }}"
                }, {
                    word: 'Jalan',
                    expected: ['jalan'],
                    audio: "{{ asset('audio/jalan.mp3') }}"
                }]
            },
            {
                letter: 'K',
                phonic: 'keh',
                words: [{
                    word: 'Kucing',
                    expected: ['kucing'],
                    audio: "{{ asset('audio/kucing.mp3') }}"
                }, {
                    word: 'Kasut',
                    expected: ['kasut'],
                    audio: "{{ asset('audio/kasut.mp3') }}"
                }, {
                    word: 'Kayu',
                    expected: ['kayu'],
                    audio: "{{ asset('audio/kayu.mp3') }}"
                }]
            },
            {
                letter: 'L',
                phonic: 'el',
                words: [{
                    word: 'Lampu',
                    expected: ['lampu'],
                    audio: "{{ asset('audio/lampu.mp3') }}"
                }, {
                    word: 'Lari',
                    expected: ['lari'],
                    audio: "{{ asset('audio/lari.mp3') }}"
                }, {
                    word: 'Lori',
                    expected: ['lori'],
                    audio: "{{ asset('audio/lori.mp3') }}"
                }]
            },
            {
                letter: 'M',
                phonic: 'em',
                words: [{
                    word: 'Mata',
                    expected: [ 'mata'],
                    audio: "{{ asset('audio/mata.mp3') }}"
                }, {
                    word: 'Meja',
                    expected: ['meja'],
                    audio: "{{ asset('audio/meja.mp3') }}"
                }, {
                    word: 'Makan',
                    expected: ['makan'],
                    audio: "{{ asset('audio/makan.mp3') }}"
                }]
            },
            {
                letter: 'N',
                phonic: 'en',
                words: [{
                    word: 'Nasi',
                    expected: ['nasi'],
                    audio: "{{ asset('audio/nasi.mp3') }}"
                }, {
                    word: 'Nenek',
                    expected: ['nenek'],
                    audio: "{{ asset('audio/nenek.mp3') }}"
                }, {
                    word: 'Nama',
                    expected: ['nama'],
                    audio: "{{ asset('audio/nama.mp3') }}"
                }]
            },
            {
                letter: 'P',
                phonic: 'peh',
                words: [{
                    word: 'Paku',
                    expected: ['paku'],
                    audio: "{{ asset('audio/paku.mp3') }}"
                }, {
                    word: 'Pasu',
                    expected: ['pasu'],
                    audio: "{{ asset('audio/pasu.mp3') }}"
                }, {
                    word: 'Pintu',
                    expected: ['pintu'],
                    audio: "{{ asset('audio/pintu.mp3') }}"
                }]
            },
            {
                letter: 'Q',
                phonic: 'qeh',
                words: [{
                    word: 'Qari',
                    expected: ['qari'],
                    audio: "{{ asset('audio/qari.mp3') }}"
                }, {
                    word: 'Quran',
                    expected: ['quran'],
                    audio: "{{ asset('audio/quran.mp3') }}"
                }, {
                    word: 'Qada',
                    expected: ['qada'],
                    audio: "{{ asset('audio/qada.mp3') }}"
                }]
            },
            {
                letter: 'R',
                phonic: 'er',
                words: [{
                    word: 'Rusa',
                    expected: ['rusa'],
                    audio: "{{ asset('audio/rusa.mp3') }}"
                }, {
                    word: 'Roda',
                    expected: ['roda'],
                    audio: "{{ asset('audio/roda.mp3') }}"
                }, {
                    word: 'Roti',
                    expected: ['roti'],
                    audio: "{{ asset('audio/roti.mp3') }}"
                }]
            },
            {
                letter: 'S',
                phonic: 'es',
                words: [{
                    word: 'Susu',
                    expected: ['susu'],
                    audio: "{{ asset('audio/susu.mp3') }}"
                }, {
                    word: 'Satu',
                    expected: ['satu'],
                    audio: "{{ asset('audio/satu.mp3') }}"
                }, {
                    word: 'Siku',
                    expected: ['siku'],
                    audio: "{{ asset('audio/siku.mp3') }}"
                }]
            },
            {
                letter: 'T',
                phonic: 'teh',
                words: [{
                    word: 'Topi',
                    expected: ['topi'],
                    audio: "{{ asset('audio/topi.mp3') }}"
                }, {
                    word: 'Tali',
                    expected: ['tali'],
                    audio: "{{ asset('audio/tali.mp3') }}"
                }, {
                    word: 'Tiga',
                    expected: ['tiga'],
                    audio: "{{ asset('audio/tiga.mp3') }}"
                }]
            },
            {
                letter: 'V',
                phonic: 'veh',
                words: [{
                    word: 'Van',
                    expected: ['van'],
                    audio: "{{ asset('audio/van.mp3') }}"
                }, {
                    word: 'Video',
                    expected: ['video'],
                    audio: "{{ asset('audio/video.mp3') }}"
                }, {
                    word: 'Virus',
                    expected: ['virus'],
                    audio: "{{ asset('audio/virus.mp3') }}"
                }]
            },
            {
                letter: 'W',
                phonic: 'weh',
                words: [{
                    word: 'Wau',
                    expected: ['wau'],
                    audio: "{{ asset('audio/wau.mp3') }}"
                }, {
                    word: 'Wang',
                    expected: [ 'wang'],
                    audio: "{{ asset('audio/wang.mp3') }}"
                }, {
                    word: 'Warna',
                    expected: ['warna'],
                    audio: "{{ asset('audio/warna.mp3') }}"
                }]
            },
            {
                letter: 'X',
                phonic: 'eks',
                words: [{
                    word: 'X-ray',
                    expected: [ 'x-ray', 'xray'],
                    audio: "{{ asset('audio/x-ray.mp3') }}"
                }, {
                    word: 'Xilem',
                    expected: ['xilem'],
                    audio: "{{ asset('audio/xilem.mp3') }}"
                }, {
                    word: 'Xilofon',
                    expected: ['xilofon'],
                    audio: "{{ asset('audio/xilofon.mp3') }}"
                }]
            },
            {
                letter: 'Y',
                phonic: 'yeh',
                words: [{
                    word: 'Yoyo',
                    expected: [ 'yoyo'],
                    audio: "{{ asset('audio/yoyo.mp3') }}"
                }, {
                    word: 'Yis',
                    expected: [ 'yis'],
                    audio: "{{ asset('audio/yis.mp3') }}"
                }, {
                    word: 'Yang',
                    expected: ['yang'],
                    audio: "{{ asset('audio/yang.mp3') }}"
                }]
            },
            {
                letter: 'Z',
                phonic: 'zeh',
                words: [{
                    word: 'Zip',
                    expected: [ 'zip'],
                    audio: "{{ asset('audio/zip.mp3') }}"
                }, {
                    word: 'Zoo',
                    expected: ['zoo'],
                    audio: "{{ asset('audio/zoo.mp3') }}"
                }, {
                    word: 'Zirafah',
                    expected: [ 'zirafah'],
                    audio: "{{ asset('audio/zirafah.mp3') }}"
                }]
            }
        ];

        // MENCARI INDEX MULA YANG TEPAT BERDASARKAN DATABASE (PROGRESS)
        let allWords = [];
        consonantData.forEach((c, cIdx) => {
            c.words.forEach((w, wIdx) => {
                allWords.push({ cIdx: cIdx, wIdx: wIdx });
            });
        });

        let savedIndex = Math.floor((window.moduleConfig.savedPercentage / 100) * allWords.length);
        if (savedIndex >= allWords.length) savedIndex = 0;

        let currentConsonantIndex = allWords[savedIndex].cIdx;
        let currentWordIndex = allWords[savedIndex].wIdx;
        let score = 0; // Skor ini untuk paparan current session sahaja (jika perlu boleh tarik dari DB)
        
        let isListening = false;
        let isProcessing = false;
        let recognition;

        const letterDisplay = document.getElementById('letter-display');
        const wordDisplay = document.getElementById('word-display');
        const wordCount = document.getElementById('word-count');

        const statusText = document.getElementById('status-text');
        const transcriptDisplay = document.getElementById('transcript-display');
        const roundDisplay = document.getElementById('round-display');
        const progressBar = document.getElementById('progress-bar');
        const flashcard = document.getElementById('flashcard');
        const btnMic = document.getElementById('btn-mic');
        const btnSkip = document.getElementById('btn-skip');
        const successOverlay = document.getElementById('success-overlay');

        const correctSound = new Audio('{{ asset('audio/bagus.mp3') }}');
        const wrongSound = new Audio('{{ asset('audio/cubalagi.mp3') }}');
        const winSound = new Audio('{{ asset('audio/tahniah.mp3') }}');
        
        // Element Audio utk Mainkan Example (Diletak HTML, akses by JS)
        const phonicAudio = document.getElementById('phonicAudio');

        function setupSpeechRecognition() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            if (!SpeechRecognition) {
                transcriptDisplay.innerText = "Pelayar tidak menyokong mic.";
                statusText.innerText = "Gunakan Google Chrome untuk main.";
                statusText.className = "text-red-500 font-bold text-lg text-center mt-4 h-8";
                btnMic.disabled = true;
                return;
            }

            recognition = new SpeechRecognition();
            recognition.lang = 'ms-MY';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            recognition.onstart = function() {
                isListening = true;
                flashcard.classList.add('is-listening');
                statusText.innerText = "Sedang mendengar... Sebut sekarang!";
                statusText.className = "text-red-500 font-bold text-lg text-center mt-4 h-8 animate-pulse";
                transcriptDisplay.innerText = "Mendengar...";
                transcriptDisplay.className = "text-amber-500 font-bold text-lg italic animate-pulse";
            };

            recognition.onresult = function(event) {
                if (isProcessing) return;
                const transcript = event.results[0][0].transcript.toLowerCase().trim();
                transcriptDisplay.innerText = `"${transcript}"`;
                transcriptDisplay.className = "text-slate-700 font-bold text-xl truncate";
                checkPronunciation(transcript);
            };

            recognition.onend = function() {
                if (isListening && !isProcessing) {
                    transcriptDisplay.innerText = "Suara tak jelas.";
                    const currentWord = consonantData[currentConsonantIndex].words[currentWordIndex].word;
                    statusText.innerText = `Sebut perkataan: "${currentWord}"`;
                    statusText.className = "text-orange-500 font-bold text-lg text-center mt-4 h-8";
                }
                isListening = false;
                flashcard.classList.remove('is-listening');
            };

            recognition.onerror = function(event) {
                isListening = false;
                flashcard.classList.remove('is-listening');
                statusText.innerText = (event.error === 'not-allowed') ? "Sila benarkan akses mikrofon." : "Ralat: " +
                    event.error;
                statusText.className = "text-orange-500 font-bold text-lg text-center mt-4 h-8";
            };
        }

        function toggleListening() {
            if (isProcessing) return;
            if (isListening) {
                recognition.stop();
            } else {
                try {
                    const currentWord = consonantData[currentConsonantIndex].words[currentWordIndex].word;
                    statusText.innerText = `Sebut: "${currentWord}"`;
                    statusText.className = "text-slate-600 font-bold text-lg text-center mt-4 h-8";
                    recognition.start();
                } catch (e) {
                    recognition.stop();
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            setupSpeechRecognition();
            loadConsonantCard();
        });

        function loadConsonantCard() {
            isProcessing = false;
            const data = consonantData[currentConsonantIndex];
            const wordData = data.words[currentWordIndex];

            // Kiraan Percentage yang tepat untuk array words didalam consonant
            let totalWordsPassed = 0;
            for(let i=0; i < currentConsonantIndex; i++) {
                totalWordsPassed += consonantData[i].words.length;
            }
            totalWordsPassed += currentWordIndex;
            
            const percent = (totalWordsPassed / allWords.length) * 100;
            progressBar.style.width = `${percent}%`;

            flashcard.classList.remove('flip-animation', 'wrong-state');
            successOverlay.classList.replace('opacity-100', 'opacity-0');
            document.getElementById('success-content').classList.replace('scale-100', 'scale-50');
            document.getElementById('success-content').classList.replace('opacity-100', 'opacity-0');
            void flashcard.offsetWidth;
            flashcard.classList.add('flip-animation');

            letterDisplay.innerText = data.letter;
            wordDisplay.innerText = wordData.word;
            wordCount.innerText = `${currentWordIndex + 1}/3`;
            roundDisplay.innerText = `${currentConsonantIndex + 1}/${consonantData.length}`;

            transcriptDisplay.innerText = "Tekan mic dan sedia...";
            transcriptDisplay.className = "text-slate-400 font-bold text-sm italic";

            statusText.innerText = `Sebut perkataan: "${wordData.word}"`;
            statusText.className = "text-slate-600 font-bold text-lg text-center mt-4 h-8";

            btnMic.classList.remove('opacity-0', 'pointer-events-none');
            btnSkip.disabled = false;
        }

        function checkPronunciation(spokenWord) {
            isProcessing = true;
            recognition.stop();

            const wordData = consonantData[currentConsonantIndex].words[currentWordIndex];

            // Gunakan boundary (\b) untuk pastikan padanan yg logik
            const isCorrect = wordData.expected.some(exp => {
                const regex = new RegExp(`\\b${exp}\\b`, 'i');
                return regex.test(spokenWord);
            });

            if (isCorrect) {
                score += 10;
                correctSound.currentTime = 0;
                correctSound.play();

                successOverlay.classList.replace('opacity-0', 'opacity-100');
                document.getElementById('success-content').classList.remove('scale-50', 'opacity-0');
                document.getElementById('success-content').classList.add('scale-100', 'opacity-100');
                btnMic.classList.add('opacity-0', 'pointer-events-none');
                btnSkip.disabled = true;

                setTimeout(() => {
                    proceedToNextWord();
                }, 1500);
            } else {
                wrongSound.currentTime = 0;
                wrongSound.play();
                flashcard.classList.add('wrong-state');
                statusText.innerText = `Kurang tepat. Sebut "${wordData.word}".`;
                statusText.className = "text-red-500 font-bold text-lg text-center mt-4 h-8";

                setTimeout(() => {
                    flashcard.classList.remove('wrong-state');
                    isProcessing = false;
                }, 1500);
            }
        }

       function playExample() {
            if (isProcessing || isListening) return;

            const data = consonantData[currentConsonantIndex];
            const wordData = data.words[currentWordIndex];

            phonicAudio.pause();
            phonicAudio.src = wordData.audio;

            phonicAudio.play().catch(error => {
                console.error("Audio playback failed:", error);
                playPhonicsTTS(data.phonic, 0.6);
            });
        }

        function playPhonicsTTS(text, rate = 0.8) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ms-MY';
            utterance.rate = rate;
            window.speechSynthesis.speak(utterance);
        }

        function skipWord() {
            if (isProcessing || isListening) return;
            sfxSkip.currentTime = 0;
            sfxSkip.play();
            proceedToNextWord();
        }

        function proceedToNextWord() {
            currentWordIndex++;

            if (currentWordIndex >= consonantData[currentConsonantIndex].words.length) {
                currentWordIndex = 0;
                currentConsonantIndex++;

                if (currentConsonantIndex < consonantData.length) {
                    loadConsonantCard();
                } else {
                    endGame();
                }
            } else {
                loadConsonantCard();
            }
        }

        function endGame() {
            progressBar.style.width = `100%`;
            document.getElementById('final-score').innerText = score;
            document.getElementById('win-overlay').classList.remove('hidden');
            
        }

        function submitProgress(isComplete, redirectUrl) {
            let totalWordsPassed = 0;
            for(let i=0; i < currentConsonantIndex; i++) {
                totalWordsPassed += consonantData[i].words.length;
            }
            totalWordsPassed += currentWordIndex;
            
            let newPercentage = Math.round((totalWordsPassed / allWords.length) * 100);
            
            if (isComplete) newPercentage = 100;
            if (!isComplete && newPercentage === 0 && totalWordsPassed > 0) newPercentage = 1;

            let pointsEarned = isComplete ? 50 : 0;

            if (!window.moduleConfig.childId || window.moduleConfig.childId === 'null') {
                alert("Ralat: Profil pelajar tidak dikesan. Progress tidak dapat disimpan.");
                window.location.href = redirectUrl;
                return;
            }

            fetch(window.moduleConfig.saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        child_id: window.moduleConfig.childId,
                        reading_module_id: window.moduleConfig.moduleId,
                        progress_percentage: newPercentage,
                        is_completed: isComplete,
                        score: pointsEarned 
                    })
                })
                .then(async response => {
                    const textResponse = await response.text();
                    try {
                        const data = JSON.parse(textResponse);
                        window.location.href = redirectUrl;
                    } catch (jsonError) {
                        window.location.href = redirectUrl;
                    }
                })
                .catch(error => {
                    window.location.href = redirectUrl;
                });
        }

        function saveAndExitEarly() {
            document.getElementById('btn-back-text').innerText = "Menyimpan...";
            submitProgress(false, window.moduleConfig.backUrl);
        }

        function saveAndFinish(event) {
            const btn = event.target;
            btn.disabled = true;
            btn.innerText = "Menyimpan...";
            submitProgress(true, window.moduleConfig.backUrl);
        }
    </script>
@endsection