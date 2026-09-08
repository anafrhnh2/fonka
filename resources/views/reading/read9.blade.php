@extends('layouts.game')

@section('game-title', 'Akhiran Konsonan (KVK)')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <script>
        window.moduleConfig = {
            saveUrl: "{{ route('reading.save_progress') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            moduleId: {{ $module->id ?? 9 }},
            backUrl: "{{ route('reading.index') }}",
            savedPercentage: {{ $currentProgress ?? 0 }}
        };
    </script>

    <div id="game-fullscreen-container"
        class="absolute inset-0 flex flex-col font-sans overflow-hidden bg-[#EFF6FF] transition-all duration-300 z-10 rounded-[25px]">

        <button onclick="toggleFullScreen()"
            class="absolute bottom-6 right-6 z-50 w-14 h-14 bg-slate-800/80 text-blue-400 rounded-full flex items-center justify-center hover:bg-slate-700 hover:scale-110 active:scale-95 transition-all shadow-[0_4px_10px_rgba(0,0,0,0.5)] border-4 border-blue-500 backdrop-blur-sm">
            <i id="fullscreen-icon" class="fas fa-expand text-2xl"></i>
        </button>

        <div class="flex flex-wrap justify-between items-center mb-4 shrink-0 w-full max-w-6xl mx-auto p-4 md:p-8">
            <button onclick="saveAndExitEarly()"
                class="bg-white border-4 border-slate-200 text-slate-600 font-bold px-6 py-3 rounded-full hover:bg-slate-50 transition-all shadow-md flex items-center gap-2 text-lg">
                <i class="fa-solid fa-arrow-left"></i> <span id="btn-back-text">Simpan & Kembali</span>
            </button>

            <div class="flex items-center gap-3 font-lexend">
                <div class="flex flex-col items-center bg-white border-4 border-blue-200 rounded-2xl px-6 py-2 shadow-md">
                    <span class="text-blue-500 font-bold text-sm uppercase tracking-wider">Perkataan</span>
                    <span id="round-display" class="text-blue-700 font-black text-2xl">1/10</span>
                </div>
            </div>
        </div>

        <div class="flex-1 w-full max-w-5xl mx-auto flex flex-col items-center justify-center relative z-10 px-4">

            <div class="w-full bg-slate-200 rounded-full h-4 mb-6 overflow-hidden shadow-inner">
                <div id="progress-bar" class="bg-blue-500 h-4 rounded-full transition-all duration-500" style="width: 0%">
                </div>
            </div>

            <div id="flashcard"
                class="bg-white border-[6px] border-blue-100 rounded-[2rem] shadow-2xl w-full max-w-md flex flex-col items-center p-4 relative transition-all duration-500">

                <button onclick="playExample()"
                    class="absolute top-6 left-6 w-12 h-12 bg-sky-100 text-sky-500 rounded-full flex items-center justify-center text-2xl border-4 border-sky-200 hover:bg-sky-200 active:scale-95 transition-all z-30 shadow-sm">
                     <i class="fa-solid fa-volume-high"></i>
                </button>

                <span id="group-label"
                    class="absolute top-6 right-6 bg-blue-100 text-blue-700 font-bold px-4 py-1.5 rounded-full text-sm border-4 border-blue-300 shadow-sm">
                    KVK + KV
                </span>

                <div
                    class="w-24 h-24 md:w-32 md:h-32 bg-blue-50 rounded-2xl border-4 border-blue-200 flex items-center justify-center overflow-hidden shadow-inner mt-12 md:mt-10">
                    <img id="image-display" src="" alt="Gambar" class="w-full h-full object-cover">
                </div>

                <div class="flex flex-col items-center justify-center mt-4 w-full">
                    <h2 id="spelling-display"
                        class="text-2xl md:text-3xl font-bold text-slate-400 font-lexend tracking-[0.2em] uppercase mb-2">
                        LAM - PU</h2>

                    <div
                        class="bg-blue-50 border-4 border-blue-200 w-full max-w-sm rounded-[2rem] py-4 flex flex-row items-center justify-center shadow-inner gap-2">
                        <h1 id="suku1-display"
                            class="text-4xl md:text-5xl font-black text-rose-500 font-lexend capitalize tracking-wide">Lam
                        </h1>
                        <h1 id="suku2-display"
                            class="text-4xl md:text-5xl font-black text-blue-600 font-lexend lowercase tracking-wide">pu
                        </h1>
                    </div>
                </div>

                <div
                    class="w-full max-w-sm h-10 flex items-center justify-center bg-slate-50 rounded-lg border-2 border-slate-200 mb-2 px-3 shadow-inner mt-4">
                    <span id="transcript-display"
                        class="text-slate-400 font-bold text-base md:text-lg italic text-center w-full truncate">Sedia
                        mendengar...</span>
                </div>

                <p id="status-text" class="text-slate-600 font-bold text-xl text-center mt-2 mb-4 h-8">Tekan mic dan sebut
                </p>

                <button id="btn-mic" onclick="toggleListening()"
                    class="mt-2 w-20 h-20 bg-blue-500 text-white rounded-full flex items-center justify-center text-4xl border-b-[6px] border-blue-700 active:border-b-0 active:translate-y-2 transition-all shadow-[0_15px_30px_rgba(59,130,246,0.4)] relative z-20">
                    <i class="fa-solid fa-microphone"></i>
                </button>

                <div id="mic-ripple"
                    class="absolute bottom-6 w-16 h-16 bg-teal-400 rounded-full opacity-0 pointer-events-none z-10"></div>

                <div id="success-overlay"
                    class="absolute inset-0 bg-slate-900/60 rounded-[2rem] flex flex-col items-center justify-center opacity-0 pointer-events-none transition-all duration-300 z-30 backdrop-blur-sm overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center opacity-30">
                        <div
                            class="w-[200%] h-[200%] bg-[conic-gradient(from_0deg,transparent_0_15deg,#fecdd3_15deg_30deg,transparent_30deg_45deg,#fecdd3_45deg_60deg,transparent_60deg_75deg,#fecdd3_75deg_90deg,transparent_90deg_105deg,#fecdd3_105deg_120deg,transparent_120deg_135deg,#fecdd3_135deg_150deg,transparent_150deg_165deg,#fecdd3_165deg_180deg,transparent_180deg_195deg,#fecdd3_195deg_210deg,transparent_210deg_225deg,#fecdd3_225deg_240deg,transparent_240deg_255deg,#fecdd3_255deg_270deg,transparent_270deg_285deg,#fecdd3_285deg_300deg,transparent_300deg_315deg,#fecdd3_315deg_330deg,transparent_330deg_345deg,#fecdd3_345deg_360deg)] animate-[spin_10s_linear_infinite]">
                        </div>
                    </div>
                    <div id="success-content"
                        class="relative z-10 flex flex-col items-center justify-center scale-50 opacity-0 transition-all duration-500 ease-[cubic-bezier(0.175,0.885,0.32,1.275)]">
                        <div class="flex justify-center items-end gap-1 mb-2 drop-shadow-[0_10px_15px_rgba(0,0,0,0.5)]">
                            <i
                                class="fa-solid fa-star text-[3.5rem] text-amber-400 z-10 drop-shadow-[0_0_20px_rgba(251,191,36,0.8)]"></i>
                        </div>
                        <div
                            class="bg-gradient-to-b from-green-400 to-green-600 px-8 py-2 rounded-2xl border-4 border-white shadow-[0_8px_0_#166534,0_15px_20px_rgba(0,0,0,0.4)] transform -rotate-3 hover:rotate-0 transition-transform cursor-default">
                            <span
                                class="text-white font-black text-3xl md:text-4xl font-lexend uppercase tracking-widest drop-shadow-[0_4px_2px_rgba(0,0,0,0.5)]">Betul!</span>
                        </div>
                    </div>
                </div>

            </div>

            <div id="win-overlay"
                class="hidden absolute inset-0 bg-slate-900/95 z-50 flex flex-col justify-center items-center backdrop-blur-md">
                <div
                    class="bg-white p-12 rounded-[3rem] shadow-2xl text-center max-w-2xl w-full border-[8px] border-blue-400 font-lexend mx-4">
                    <div class="text-8xl mb-6 animate-bounce">🏆</div>
                    <h1 class="text-5xl font-black text-blue-600 mb-4">Hebat!</h1>
                    <p class="text-slate-500 font-bold mb-8 text-2xl">Awak dah pakar Akhiran Konsonan.</p>

                    <div class="bg-blue-50 p-8 rounded-3xl mb-10 border-4 border-blue-200 shadow-inner">
                        <p class="font-bold text-blue-700 uppercase tracking-widest mb-2 text-xl">Skor Kamu</p>
                        <p class="text-7xl font-black text-blue-500"><span id="final-score">0</span></p>
                    </div>

                    <button onclick="saveAndFinish(event)"
                        class="bg-blue-500 border-blue-700 text-white w-full text-2xl font-bold py-6 rounded-[2rem] border-b-[8px] active:border-b-[2px] active:translate-y-[6px] transition-all shadow-xl">
                        Simpan & Tamat ▶
                    </button>
                </div>
            </div>
        </div>

        <audio id="sfx-correct" src="https://assets.mixkit.com/sfx/preview/mixkit-correct-answer-tone-2870.mp3"></audio>
        <audio id="sfx-wrong" src="https://assets.mixkit.com/sfx/preview/mixkit-buzzer-14.mp3"></audio>
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
            }

            .wrong-state {
                animation: shake 0.4s;
                border-color: #EF4444 !important;
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
        </style>

        <script>
            const sukuKataData = [{
                    suku1: 'Lam',
                    suku2: 'pu',
                    word: 'Lampu',
                    expected: ['lampu'],
                    img: "{{ asset('images/games/lampu.png') }}",
                    audio: "{{ asset('audio/Lampu.mp3') }}"
                },
                {
                    suku1: 'Pin',
                    suku2: 'tu',
                    word: 'Pintu',
                    expected: ['pintu'],
                    img: "{{ asset('images/games/pintu.png') }}",
                    audio: "{{ asset('audio/Pintu.mp3') }}"
                },
                {
                    suku1: 'Kas',
                    suku2: 'ut',
                    word: 'Kasut',
                    expected: ['kasut'],
                    img: "{{ asset('images/games/kasut.png') }}",
                    audio: "{{ asset('audio/Kasut.mp3') }}"
                },
                {
                    suku1: 'Bom',
                    suku2: 'ba',
                    word: 'Bomba',
                    expected: ['bomba'],
                    img: "{{ asset('images/games/bomba.png') }}",
                    audio: "{{ asset('audio/Bomba.mp3') }}"
                },
                {
                    suku1: 'Ca',
                    suku2: 'wan',
                    word: 'Cawan',
                    expected: ['cawan'],
                    img: "{{ asset('images/games/cawan.png') }}",
                    audio: "{{ asset('audio/Cawan.mp3') }}"
                },
                {
                    suku1: 'Bo',
                    suku2: 'tol',
                    word: 'Botol',
                    expected: ['botol'],
                    img: "{{ asset('images/games/botol.png') }}",
                    audio: "{{ asset('audio/Botol.mp3') }}"
                },
                {
                    suku1: 'Ka',
                    suku2: 'sut',
                    word: 'Kasut',
                    expected: ['kasut'],
                    img: "{{ asset('images/games/kasut.png') }}",
                    audio: "{{ asset('audio/Kasut.mp3') }}"
                },
            ];

            let startingIndexFromDb = Math.round((window.moduleConfig.savedPercentage / 100) * sukuKataData.length);
            if (startingIndexFromDb >= sukuKataData.length) startingIndexFromDb = 0;
            let currentIndex = startingIndexFromDb;
            let score = currentIndex * 10;
            let isListening = false;
            let isProcessing = false;
            let recognition;
            // Elements
            const suku1Display = document.getElementById('suku1-display');
            const suku2Display = document.getElementById('suku2-display');
            const spellingDisplay = document.getElementById('spelling-display');
            const imageDisplay = document.getElementById('image-display');
            const roundDisplay = document.getElementById('round-display');
            const statusText = document.getElementById('status-text');
            const transcriptDisplay = document.getElementById('transcript-display');
            const progressBar = document.getElementById('progress-bar');
            const flashcard = document.getElementById('flashcard');
            const btnMic = document.getElementById('btn-mic');
            const successOverlay = document.getElementById('success-overlay');
            const successContent = document.getElementById('success-content');

            const correctSound = new Audio('{{ asset('audio/bagus.mp3') }}');
            const wrongSound = new Audio('{{ asset('audio/cubalagi.mp3') }}');
            const winSound = new Audio('{{ asset('audio/tahniah.mp3') }}');
            const sfxCorrect = document.getElementById('sfx-correct');
            const sfxWrong = document.getElementById('sfx-wrong');
            const sfxSkip = document.getElementById('sfx-skip');
            const phonicAudio = document.getElementById('phonicAudio');

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
                    transcriptDisplay.innerText = "Mic tidak disokong.";
                    return;
                }
                recognition = new SpeechRecognition();
                recognition.lang = 'ms-MY';
                recognition.onstart = () => {
                    isListening = true;
                    flashcard.classList.add('is-listening');
                    statusText.innerText = "Mendengar...";
                };
                recognition.onresult = (event) => {
                    if (isProcessing) return;
                    const transcript = event.results[0][0].transcript.toLowerCase().trim();
                    transcriptDisplay.innerText = `"${transcript}"`;
                    checkPronunciation(transcript);
                };
                recognition.onend = () => {
                    isListening = false;
                    flashcard.classList.remove('is-listening');
                };
            }

            function toggleListening() {
                if (isProcessing) return;
                if (isListening) recognition.stop();
                else recognition.start();
            }

            function loadCard() {
                isProcessing = false;
                const data = sukuKataData[currentIndex];
                progressBar.style.width = `${(currentIndex / sukuKataData.length) * 100}%`;

                // Reset Overlay
                successOverlay.classList.replace('opacity-100', 'opacity-0');
                successContent.classList.replace('opacity-100', 'opacity-0');
                successContent.classList.replace('scale-100', 'scale-50');
                flashcard.classList.remove('flip-animation', 'wrong-state');

                void flashcard.offsetWidth;
                flashcard.classList.add('flip-animation');

                suku1Display.innerText = data.suku1;
                suku2Display.innerText = data.suku2;
                spellingDisplay.innerText = data.suku2 ? `${data.suku1} - ${data.suku2}` : data.suku1;
                imageDisplay.src = data.img;
                roundDisplay.innerText = `${currentIndex + 1}/${sukuKataData.length}`;
                statusText.innerText = `Sebut: ${data.word}`;
                btnMic.classList.remove('opacity-0', 'pointer-events-none');
            }

            function checkPronunciation(spokenWord) {
                isProcessing = true;
                const data = sukuKataData[currentIndex];
                const isCorrect = data.expected.some(exp => spokenWord.includes(exp));

                if (isCorrect) {
                    score += 5;
                    correctSound.currentTime = 0;
                    correctSound.play();
                    successOverlay.classList.replace('opacity-0', 'opacity-100');
                    successContent.classList.replace('opacity-0', 'opacity-100');
                    successContent.classList.replace('scale-50', 'scale-100');
                    setTimeout(proceedToNextWord, 1500);
                } else {
                    wrongSound.currentTime = 0;
                    wrongSound.play();
                    flashcard.classList.add('wrong-state');
                    setTimeout(() => {
                        flashcard.classList.remove('wrong-state');
                        isProcessing = false;
                        statusText.innerText = "Cuba lagi!";
                    }, 1000);
                }
            }

            function proceedToNextWord() {
                currentIndex++;
                if (currentIndex < sukuKataData.length) loadCard();
                else endGame();
            }

            function endGame() {
                document.getElementById('final-score').innerText = score;
                document.getElementById('win-overlay').classList.remove('hidden');
                winSound.play();
            }

            function playExample() {
                if (isProcessing || isListening) return;

                const data = sukuKataData[currentIndex];

                if (phonicAudio) {
                    phonicAudio.pause();
                    phonicAudio.src = data.audio;
                    phonicAudio.load();

                    phonicAudio.play().catch(error => {
                        console.log("Audio gagal, guna fallback TTS");
                        playPhonicsTTS(`${data.suku1} ... ${data.suku2} ... ${data.word}`, 0.6);
                    });
                } else {
                    playPhonicsTTS(`${data.suku1} ... ${data.suku2} ... ${data.word}`, 0.6);
                }
            }

            function submitProgress(isComplete, redirectUrl) {
                let pct = isComplete ? 100 : Math.round((currentIndex / sukuKataData.length) * 100);
                fetch(window.moduleConfig.saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        child_id: window.moduleConfig.childId,
                        reading_module_id: window.moduleConfig.moduleId,
                        progress_percentage: pct,
                        score: score,
                        is_completed: isComplete
                    })
                }).finally(() => {
                    window.location.href = redirectUrl;
                });
            }

            function saveAndExitEarly() {
                submitProgress(false, window.moduleConfig.backUrl);
            }

            function saveAndFinish() {
                submitProgress(true, window.moduleConfig.backUrl);
            }

            document.addEventListener('DOMContentLoaded', () => {
                setupSpeechRecognition();
                loadCard();
            });
        </script>
    @endsection
