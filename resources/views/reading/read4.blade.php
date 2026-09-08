@extends('layouts.game')

@section('game-title', 'Huruf Kembar')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        window.moduleConfig = {
            saveUrl: "{{ route('reading.save_progress') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            moduleId: {{ $module->id ?? 4 }},
            backUrl: "{{ route('reading.index') }}",
            savedPercentage: {{ $currentProgress ?? 0 }}
        };
    </script>

    <div id="game-fullscreen-container"
        class="absolute inset-0 flex flex-col font-sans overflow-hidden bg-[#FFF1F2] transition-all duration-300 z-10 rounded-[25px]">

        <button onclick="toggleFullScreen()"
            class="absolute bottom-6 right-6 z-50 w-14 h-14 bg-slate-800/80 text-rose-400 rounded-full flex items-center justify-center hover:bg-slate-700 hover:scale-110 active:scale-95 transition-all shadow-[0_4px_10px_rgba(0,0,0,0.5)] border-4 border-rose-500 backdrop-blur-sm">
            <i id="fullscreen-icon" class="fas fa-expand text-2xl"></i>
        </button>

        <div class="flex flex-wrap justify-between items-center mb-2 px-4 py-4 shrink-0 z-20 w-full max-w-5xl mx-auto">
            <button onclick="saveAndExitEarly()"
                class="bg-white border-4 border-slate-200 text-slate-600 font-bold px-6 py-2 rounded-full hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> <span id="btn-back-text">Simpan & Kembali</span>
            </button>

            <div class="flex items-center gap-3 md:gap-5 font-lexend">
                <div class="flex flex-col items-center bg-white border-4 border-rose-200 rounded-2xl px-4 py-1 shadow-sm">
                    <span class="text-rose-500 font-bold text-xs md:text-sm uppercase tracking-wider">Perkataan</span>
                    <span id="round-display" class="text-rose-700 font-black text-xl md:text-2xl">1/24</span>
                </div>
            </div>
        </div>

        <div class="flex-1 w-full max-w-4xl mx-auto flex flex-col items-center justify-center relative z-10 px-4 pb-10">

            <div class="w-full max-w-2xl bg-slate-200 rounded-full h-3 mb-4 overflow-hidden shadow-inner">
                <div id="progress-bar" class="bg-rose-500 h-3 rounded-full transition-all duration-500" style="width: 0%">
                </div>
            </div>

            <div id="flashcard"
                class="bg-white border-[8px] border-rose-100 rounded-[2.5rem] shadow-2xl w-full max-w-2xl flex flex-col items-center p-6 relative transition-all duration-500">

                <button onclick="playExample()"
                    class="absolute top-5 left-5 w-12 h-12 bg-sky-100 text-sky-500 rounded-full flex items-center justify-center text-xl border-4 border-sky-200 hover:bg-sky-200 active:scale-95 transition-all z-30 shadow-md"
                    title="Dengar Contoh">
                     <i class="fa-solid fa-volume-high"></i>
                </button>

                <span id="group-label"
                    class="absolute top-5 right-5 bg-rose-100 text-rose-700 font-bold px-4 py-1.5 rounded-full text-sm md:text-base border-4 border-rose-300 shadow-sm">
                    Beza b dan d
                </span>

                <div class="flex flex-row items-center justify-center gap-6 md:gap-8 mt-10 w-full">
                    <h1 id="letter-display"
                        class="text-[6rem] md:text-[7.5rem] font-black text-rose-600 font-lexend leading-none drop-shadow-md lowercase">
                        b</h1>

                    <div
                        class="w-28 h-28 md:w-36 md:h-36 bg-rose-50 rounded-[1.5rem] border-[4px] border-rose-200 flex items-center justify-center overflow-hidden shadow-inner">
                        <img id="image-display" src="" alt="Gambar" class="w-full h-full object-cover">
                    </div>
                </div>

                <div
                    class="bg-rose-50 border-4 border-rose-100 w-[80%] rounded-[1.5rem] py-2 md:py-3 flex flex-col items-center justify-center mb-3 mt-5 shadow-inner">
                    <h2 id="word-display"
                        class="text-3xl md:text-4xl font-black text-slate-700 font-lexend capitalize tracking-wide">Buku
                    </h2>
                </div>

                <div
                    class="w-[80%] h-10 md:h-12 flex items-center justify-center bg-slate-50 rounded-xl border-2 border-slate-200 mb-1 px-4 shadow-inner">
                    <span id="transcript-display"
                        class="text-slate-400 font-bold text-sm md:text-base italic text-center w-full truncate">Sistem
                        bersedia...</span>
                </div>

                <p id="status-text"
                    class="text-slate-600 font-bold text-base md:text-lg text-center mt-1 h-6 transition-colors">Tekan mic
                    dan sebut perkataan</p>

                <button id="btn-mic" onclick="toggleListening()"
                    class="mt-4 w-20 h-20 bg-rose-500 text-white rounded-full flex items-center justify-center text-4xl border-b-[6px] border-rose-700 active:border-b-0 active:translate-y-2 transition-all shadow-[0_15px_30px_rgba(244,63,94,0.4)] relative z-20">
                    <i class="fa-solid fa-microphone"></i>
                </button>

                <div id="mic-ripple"
                    class="absolute bottom-10 w-20 h-20 bg-rose-400 rounded-full opacity-0 pointer-events-none z-10"></div>

                <div id="success-overlay"
                    class="absolute inset-0 bg-slate-900/60 rounded-[2.5rem] flex flex-col items-center justify-center opacity-0 pointer-events-none transition-all duration-300 z-30 backdrop-blur-sm overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center opacity-30">
                        <div
                            class="w-[200%] h-[200%] bg-[conic-gradient(from_0deg,transparent_0_15deg,#fecdd3_15deg_30deg,transparent_30deg_45deg,#fecdd3_45deg_60deg,transparent_60deg_75deg,#fecdd3_75deg_90deg,transparent_90deg_105deg,#fecdd3_105deg_120deg,transparent_120deg_135deg,#fecdd3_135deg_150deg,transparent_150deg_165deg,#fecdd3_165deg_180deg,transparent_180deg_195deg,#fecdd3_195deg_210deg,transparent_210deg_225deg,#fecdd3_225deg_240deg,transparent_240deg_255deg,#fecdd3_255deg_270deg,transparent_270deg_285deg,#fecdd3_285deg_300deg,transparent_300deg_315deg,#fecdd3_315deg_330deg,transparent_330deg_345deg,#fecdd3_345deg_360deg)] animate-[spin_10s_linear_infinite]">
                        </div>
                    </div>
                    <div id="success-content"
                        class="relative z-10 flex flex-col items-center justify-center scale-50 opacity-0 transition-all duration-500 ease-[cubic-bezier(0.175,0.885,0.32,1.275)]">
                        <div class="flex justify-center items-end gap-1 mb-2 drop-shadow-[0_10px_15px_rgba(0,0,0,0.5)]">
                            <i
                                class="fa-solid fa-star text-[5rem] md:text-[6rem] text-amber-400 z-10 drop-shadow-[0_0_20px_rgba(251,191,36,0.8)]"></i>
                        </div>
                        <div
                            class="bg-gradient-to-b from-green-400 to-green-600 px-8 py-2 md:py-3 rounded-2xl border-4 border-white shadow-[0_8px_0_#166534,0_15px_20px_rgba(0,0,0,0.4)] transform -rotate-3 hover:rotate-0 transition-transform cursor-default">
                            <span
                                class="text-white font-black text-3xl md:text-4xl font-lexend uppercase tracking-widest drop-shadow-[0_4px_2px_rgba(0,0,0,0.5)]">Bagus!</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div id="win-overlay"
            class="hidden absolute inset-0 bg-slate-900/90 z-50 flex flex-col justify-center items-center backdrop-blur-sm">
            <div
                class="bg-white p-12 rounded-[3rem] shadow-2xl text-center max-w-2xl w-full animate-bounce-in border-[8px] border-rose-400 font-lexend">
                <div class="text-8xl mb-6">🎉</div>
                <h1 class="text-5xl font-black text-rose-600 mb-4">Tahniah!</h1>
                <p class="text-slate-500 font-bold mb-8 text-2xl">Awak berjaya bezakan huruf-huruf ini.</p>

                <div class="bg-rose-50 p-8 rounded-3xl mb-10 border-4 border-rose-200 shadow-inner">
                    <p class="font-bold text-rose-700 uppercase tracking-widest mb-2 text-xl">Skor Markah</p>
                    <p class="text-7xl font-black text-rose-500"><span id="final-score">0</span></p>
                </div>

                <button onclick="saveAndFinish(event)"
                    class="btn-3d bg-rose-500 border-rose-700 text-white w-full text-2xl font-bold py-6 rounded-[2rem] relative inline-flex items-center justify-center border-b-[8px] active:border-b-[2px] active:translate-y-[6px] transition-all shadow-xl">
                    Selesai & Simpan ▶
                </button>
            </div>
        </div>

    </div>

    <audio id="phonicAudio" preload="auto"></audio>
    <audio id="sfx-correct" src="https://assets.mixkit.com/sfx/preview/mixkit-correct-answer-tone-2870.mp3"></audio>
    <audio id="sfx-wrong" src="https://assets.mixkit.com/sfx/preview/mixkit-buzzer-14.mp3"></audio>

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
    </style>

    <script>
        const confusingData = [

            // Beza b dan d
            {
                title: "Beza b dan d",
                letter: 'b',
                word: 'Bola',
                phonic: 'beh',
                expected: ['bola'],
                img: "{{ asset('images/games/bola.png') }}",
                audio: "{{ asset('audio/Bola.mp3') }}"
            },
            {
                title: "Beza b dan d",
                letter: 'd',
                word: 'Dadu',
                phonic: 'deh',
                expected: ['dadu'],
                img: "{{ asset('images/games/dadu.png') }}",
                audio: "{{ asset('audio/Dadu.mp3') }}"
            },
            {
                title: "Beza b dan d",
                letter: 'b',
                word: 'Baju',
                phonic: 'beh',
                expected: ['baju'],
                img: "{{ asset('images/games/baju.png') }}",
                audio: "{{ asset('audio/Baju2.mp3') }}"
            },
            {
                title: "Beza b dan d",
                letter: 'd',
                word: 'Daun',
                phonic: 'deh',
                expected: ['daun'],
                img: "{{ asset('images/games/daun.png') }}",
                audio: "{{ asset('audio/Daun.mp3') }}"
            },
            {
                title: "Beza b dan d",
                letter: 'b',
                word: 'Buku',
                phonic: 'beh',
                expected: ['buku'],
                img: "{{ asset('images/games/buku.png') }}",
                audio: "{{ asset('audio/Buku2.mp3') }}"
            },
            {
                title: "Beza b dan d",
                letter: 'd',
                word: 'Duri',
                phonic: 'deh',
                expected: ['duri'],
                img: "{{ asset('images/games/duri.png') }}",
                audio: "{{ asset('audio/Duri.mp3') }}"
            },

            // Beza p dan q
            {
                title: "Beza p dan q",
                letter: 'p',
                word: 'Paku',
                phonic: 'peh',
                expected: ['paku'],
                img: "{{ asset('images/games/paku.png') }}",
                audio: "{{ asset('audio/Paku2.mp3') }}"
            },
            {
                title: "Beza p dan q",
                letter: 'p',
                word: 'Pintu',
                phonic: 'peh',
                expected: ['pintu'],
                img: "{{ asset('images/games/pintu.png') }}",
                audio: "{{ asset('audio/Pintu.mp3') }}"
            },
            {
                title: "Beza p dan q",
                letter: 'p',
                word: 'Peta',
                phonic: 'peh',
                expected: ['peta'],
                img: "{{ asset('images/games/peta.png') }}",
                audio: "{{ asset('audio/Peta.mp3') }}"
            },
            {
                title: "Beza p dan q",
                letter: 'q',
                word: 'Qari',
                phonic: 'qeh',
                expected: ['qari'],
                img: "{{ asset('images/games/qari.png') }}",
                audio: "{{ asset('audio/Qari.mp3') }}"
            },
            {
                title: "Beza p dan q",
                letter: 'q',
                word: 'Qiam',
                phonic: 'qeh',
                expected: ['qiam'],
                img: "{{ asset('images/games/qiam.png') }}",
                audio: "{{ asset('audio/Qiam.mp3') }}"
            },
            {
                title: "Beza p dan q",
                letter: 'q',
                word: 'Quokka',
                phonic: 'qeh',
                expected: ['quokka'],
                img: "{{ asset('images/games/quokka.png') }}",
                audio: "{{ asset('audio/Quokka.mp3') }}"
            },

            // Beza m dan n
            {
                title: "Beza m dan n",
                letter: 'm',
                word: 'Meja',
                phonic: 'em',
                expected: ['meja'],
                img: "{{ asset('images/games/meja.png') }}",
                audio: "{{ asset('audio/Meja2.mp3') }}"
            },
            {
                title: "Beza m dan n",
                letter: 'm',
                word: 'Muka',
                phonic: 'em',
                expected: ['muka'],
                img: "{{ asset('images/games/muka.png') }}",
                audio: "{{ asset('audio/Muka.mp3') }}"
            },
            {
                title: "Beza m dan n",
                letter: 'n',
                word: 'Nasi',
                phonic: 'en',
                expected: ['nasi'],
                img: "{{ asset('images/games/nasi.png') }}",
                audio: "{{ asset('audio/Nasi.mp3') }}"
            },
            {
                title: "Beza m dan n",
                letter: 'n',
                word: 'Nenek',
                phonic: 'en',
                expected: ['nenek'],
                img: "{{ asset('images/games/nenek.png') }}",
                audio: "{{ asset('audio/Nenek.mp3') }}"
            },
            {
                title: "Beza m dan n",
                letter: 'n',
                word: 'Nenas',
                phonic: 'en',
                expected: ['nenas'],
                img: "{{ asset('images/games/nenas.png') }}",
                audio: "{{ asset('audio/Nenas.mp3') }}"
            },

            // Beza w dan u
            {
                title: "Beza w dan u",
                letter: 'w',
                word: 'Wanita',
                phonic: 'weh',
                expected: ['wanita'],
                img: "{{ asset('images/games/wanita.png') }}",
                audio: "{{ asset('audio/Wanita.mp3') }}"
            },
            {
                title: "Beza w dan u",
                letter: 'w',
                word: 'Wang',
                phonic: 'weh',
                expected: ['wang'],
                img: "{{ asset('images/games/wang.png') }}",
                audio: "{{ asset('audio/Wang.mp3') }}"
            },
            {
                title: "Beza w dan u",
                letter: 'u',
                word: 'Ubat',
                phonic: 'u',
                expected: ['ubat'],
                img: "{{ asset('images/games/ubat.png') }}",
                audio: "{{ asset('audio/Ubat.mp3') }}"
            },
            {
                title: "Beza w dan u",
                letter: 'u',
                word: 'Ular',
                phonic: 'u',
                expected: ['ular'],
                img: "{{ asset('images/games/ular.png') }}",
                audio: "{{ asset('audio/Ular.mp3') }}"
            }

        ];

        let savedIndex = Math.round((window.moduleConfig.savedPercentage / 100) * confusingData.length);
        if (savedIndex >= confusingData.length) savedIndex = 0;

        let currentIndex = savedIndex;
        let score = savedIndex; // 1 markah per soalan, bermula pada tahap progress

        let isListening = false;
        let isProcessing = false;
        let recognition;

        const letterDisplay = document.getElementById('letter-display');
        const wordDisplay = document.getElementById('word-display');
        const imageDisplay = document.getElementById('image-display');
        const groupLabel = document.getElementById('group-label');

        const statusText = document.getElementById('status-text');
        const transcriptDisplay = document.getElementById('transcript-display');
        const roundDisplay = document.getElementById('round-display');
        const progressBar = document.getElementById('progress-bar');

        const flashcard = document.getElementById('flashcard');
        const btnMic = document.getElementById('btn-mic');
        const successOverlay = document.getElementById('success-overlay');
        const successContent = document.getElementById('success-content');

        const correctSound = new Audio('{{ asset('audio/bagus.mp3') }}');
        const wrongSound = new Audio('{{ asset('audio/cubalagi.mp3') }}');
        const winSound = new Audio('{{ asset('audio/tahniah.mp3') }}');
        const phonicAudio = document.getElementById('phonicAudio'); // Reference audio player
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


        function setupSpeechRecognition() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SpeechRecognition) {
                transcriptDisplay.innerText = "Pelayar tidak menyokong mic.";
                statusText.innerText = "Gunakan Google Chrome.";
                statusText.className = "text-red-500 font-bold text-xl text-center mt-2 h-8";
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
                statusText.className = "text-red-500 font-bold text-xl text-center mt-2 h-8 animate-pulse";
                transcriptDisplay.innerText = "Mendengar...";
                transcriptDisplay.className =
                "text-amber-500 font-bold text-lg italic animate-pulse text-center w-full";
            };

            recognition.onresult = function(event) {
                if (isProcessing) return;
                // Menambah baik pengecaman dengan membuang tanda baca secara automatik
                let transcript = event.results[0][0].transcript.toLowerCase().trim();
                transcript = transcript.replace(/[.,!?]/g, "");

                transcriptDisplay.innerText = `"${transcript}"`;
                transcriptDisplay.className = "text-slate-700 font-bold text-2xl text-center w-full truncate";
                checkPronunciation(transcript);
            };

            recognition.onend = function() {
                if (isListening && !isProcessing) {
                    transcriptDisplay.innerText = "Suara tak jelas.";
                    transcriptDisplay.className = "text-slate-400 font-bold text-lg italic text-center w-full";
                    const currentWord = confusingData[currentIndex].word;
                    statusText.innerText = `Sebut perkataan: "${currentWord}"`;
                    statusText.className = "text-orange-500 font-bold text-xl text-center mt-2 h-8";
                }
                isListening = false;
                flashcard.classList.remove('is-listening');
            };

            recognition.onerror = function(event) {
                isListening = false;
                flashcard.classList.remove('is-listening');
                statusText.innerText = (event.error === 'not-allowed') ? "Sila benarkan akses mikrofon." : "Ralat: " +
                    event.error;
                statusText.className = "text-orange-500 font-bold text-xl text-center mt-2 h-8";
            };
        }

        function toggleListening() {
            if (isProcessing) return;
            if (isListening) {
                recognition.stop();
            } else {
                try {
                    const currentWord = confusingData[currentIndex].word;
                    statusText.innerText = `Sebut: "${currentWord}"`;
                    statusText.className = "text-slate-600 font-bold text-xl text-center mt-2 h-8";
                    recognition.start();
                } catch (e) {
                    recognition.stop();
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            setupSpeechRecognition();
            loadCard();
        });

        function loadCard() {
            isProcessing = false;
            const data = confusingData[currentIndex];

            const percent = (currentIndex / confusingData.length) * 100;
            progressBar.style.width = `${percent}%`;

            flashcard.classList.remove('flip-animation', 'wrong-state');

            // Reset state untuk design success-overlay baharu
            successOverlay.classList.replace('opacity-100', 'opacity-0');
            successContent.classList.remove('scale-100', 'opacity-100');
            successContent.classList.add('scale-50', 'opacity-0');

            void flashcard.offsetWidth;
            flashcard.classList.add('flip-animation');

            letterDisplay.innerText = data.letter;
            wordDisplay.innerText = data.word;
            imageDisplay.src = data.img;
            groupLabel.innerText = data.title;
            roundDisplay.innerText = `${currentIndex + 1}/${confusingData.length}`;

            transcriptDisplay.innerText = "Tekan mic dan sedia...";
            transcriptDisplay.className = "text-slate-400 font-bold text-lg italic text-center w-full";

            statusText.innerText = `Sebut perkataan: "${data.word}"`;
            statusText.className = "text-slate-600 font-bold text-xl text-center mt-2 h-8";

            btnMic.classList.remove('opacity-0', 'pointer-events-none');
        }

        function checkPronunciation(spokenWord) {
            isProcessing = true;
            recognition.stop();

            const data = confusingData[currentIndex];
            const isCorrect = data.expected.some(exp => {
                const regex = new RegExp(`\\b${exp}\\b`, 'i');
                return regex.test(spokenWord) || spokenWord.includes(exp);
            });

            if (isCorrect) {
                score += 5; 
                if (correctSound) {
                    correctSound.currentTime = 0;
                    correctSound.play();
                }

                successOverlay.classList.replace('opacity-0', 'opacity-100');
                setTimeout(() => {
                    successContent.classList.remove('scale-50', 'opacity-0');
                    successContent.classList.add('scale-100', 'opacity-100');
                }, 50);

                btnMic.classList.add('opacity-0', 'pointer-events-none');

                setTimeout(() => {
                    proceedToNextWord();
                }, 2000); 
            } else {
                if (wrongSound) {
                    wrongSound.currentTime = 0;
                    wrongSound.play();
                }
                flashcard.classList.add('wrong-state');
                statusText.innerText = `Kurang tepat. Sebut "${data.word}".`;
                statusText.className = "text-red-500 font-bold text-xl text-center mt-2 h-8";

                setTimeout(() => {
                    flashcard.classList.remove('wrong-state');
                    isProcessing = false;
                }, 1500);
            }
        }

        function playExample() {
            if (isProcessing || isListening) return;
            const data = confusingData[currentIndex];

            phonicAudio.pause();
            phonicAudio.src = data.audio;

            // Mainkan! Jika file tak jumpa, gunakan sistem fallback ke Text-to-Speech
            phonicAudio.play().catch(error => {
                console.log("Audio MP3 gagal dimainkan, menggunakan TTS sebagai ganti.");
                playPhonicsTTS(`${data.phonic}... ${data.word}`, 0.6);
            });
        }

        function playPhonicsTTS(text, rate = 0.8) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ms-MY';
            utterance.rate = rate;
            window.speechSynthesis.speak(utterance);
        }

        function proceedToNextWord() {
            currentIndex++;
            if (currentIndex < confusingData.length) {
                loadCard();
            } else {
                endGame();
            }
        }

        function endGame() {
            progressBar.style.width = `100%`;
            document.getElementById('final-score').innerText = score;
            document.getElementById('win-overlay').classList.remove('hidden');
            
        }

        function submitProgress(isComplete, redirectUrl) {
            let newPercentage = Math.round((currentIndex / confusingData.length) * 100);
            if (isComplete) newPercentage = 100;

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
                        score: score,
                        is_completed: isComplete
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
