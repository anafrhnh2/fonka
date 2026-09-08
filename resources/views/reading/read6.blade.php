@extends('layouts.game')

@section('game-title', 'Dua Suku Kata')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        window.moduleConfig = {
            saveUrl: "{{ route('reading.save_progress') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            moduleId: {{ $module->id ?? 6 }},
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

        <div class="flex flex-wrap justify-between items-center mb-4 shrink-0 w-full max-w-6xl mx-auto z-20">
            <button onclick="saveAndExitEarly()"
                class="bg-white border-4 border-slate-200 text-slate-600 font-bold px-6 py-3 rounded-full hover:bg-slate-50 transition-all shadow-md flex items-center gap-2 text-lg">
                <i class="fa-solid fa-arrow-left"></i> <span id="btn-back-text">Simpan & Kembali</span>
            </button>

            <div class="flex items-center gap-3 font-lexend">
                <div class="flex flex-col items-center bg-white border-4 border-purple-200 rounded-2xl px-6 py-2 shadow-md">
                    <span class="text-purple-500 font-bold text-sm uppercase tracking-wider">Perkataan</span>
                    <span id="round-display" class="text-purple-700 font-black text-2xl">1/18</span>
                </div>
            </div>
        </div>

        <div class="flex-1 w-full max-w-5xl mx-auto flex flex-col items-center justify-center relative z-10 pb-10">

            <div class="w-full bg-slate-200 rounded-full h-4 mb-6 overflow-hidden shadow-inner">
                <div id="progress-bar" class="bg-purple-500 h-4 rounded-full transition-all duration-500" style="width: 0%">
                </div>
            </div>

            <div id="flashcard"
                class="bg-white border-[6px] border-rose-100 rounded-[2rem] shadow-2xl w-full max-w-md flex flex-col items-center p-4 relative transition-all duration-500">

                <button onclick="playExample()"
                    class="absolute top-6 left-6 w-12 h-12 bg-sky-100 text-sky-500 rounded-full flex items-center justify-center text-2xl border-4 border-sky-200 hover:bg-sky-200 active:scale-95 transition-all z-30 shadow-sm"
                    title="Eja dan Sebut">
                    <i class="fa-solid fa-volume-high"></i>
                </button>

                <div
                    class="w-24 h-24 md:w-32 md:h-32 bg-orange-50 rounded-2xl border-4 border-orange-200 flex items-center justify-center overflow-hidden shadow-inner mt-12 md:mt-10">
                    <img id="image-display" src="" alt="Gambar" class="w-full h-full object-cover">
                </div>

                <div class="flex flex-col items-center justify-center mt-4 w-full">
                    <h2 id="spelling-display"
                        class="text-2xl md:text-3xl font-bold text-slate-400 font-lexend tracking-[0.3em] uppercase mb-2">BA
                        - JU</h2>

                    <div
                        class="bg-purple-50 border-4 border-purple-200 w-full max-w-sm rounded-[2rem] py-2 flex flex-row items-center justify-center shadow-inner gap-1">
                        <h1 id="suku1-display"
                            class="text-5xl md:text-6xl font-black text-rose-500 font-lexend capitalize tracking-wide">Ba
                        </h1>
                        <h1 id="suku2-display"
                            class="text-5xl md:text-6xl font-black text-sky-500 font-lexend lowercase tracking-wide">ju</h1>
                    </div>
                </div>

                <div
                    class="w-full max-w-sm h-10 flex items-center justify-center bg-slate-50 rounded-lg border-2 border-slate-200 mb-2 px-3 shadow-inner mt-4">
                    <span id="transcript-display"
                        class="text-slate-400 font-bold text-base md:text-lg italic text-center w-full truncate">Sistem
                        bersedia...</span>
                </div>

                <p id="status-text" class="text-slate-600 font-bold text-xl text-center mt-2 mb-4 h-8 transition-colors">
                    Tekan mic dan sebut perkataan</p>

                <button id="btn-mic" onclick="toggleListening()"
                    class="mt-2 w-20 h-20 bg-purple-500 text-white rounded-full flex items-center justify-center text-4xl border-b-[6px] border-purple-700 active:border-b-0 active:translate-y-2 transition-all shadow-[0_15px_30px_rgba(168,85,247,0.4)] relative z-20">
                    <i class="fa-solid fa-microphone"></i>
                </button>

                <div id="mic-ripple"
                    class="absolute bottom-6 w-16 h-16 bg-orange-400 rounded-full opacity-0 pointer-events-none z-10"></div>

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
                                class="fa-solid fa-star text-[4rem] text-amber-400 z-10 drop-shadow-[0_0_20px_rgba(251,191,36,0.8)]"></i>
                        </div>
                        <div
                            class="bg-gradient-to-b from-green-400 to-green-600 px-8 py-2 rounded-2xl border-4 border-white shadow-[0_8px_0_#166534,0_15px_20px_rgba(0,0,0,0.4)] transform -rotate-3 hover:rotate-0 transition-transform cursor-default">
                            <span
                                class="text-white font-black text-3xl md:text-4xl font-lexend uppercase tracking-widest drop-shadow-[0_4px_2px_rgba(0,0,0,0.5)]">Betul!</span>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div id="win-overlay"
            class="hidden absolute inset-0 bg-slate-900/90 z-50 flex flex-col justify-center items-center backdrop-blur-sm">
            <div
                class="bg-white p-12 rounded-[3rem] shadow-2xl text-center max-w-2xl w-full animate-bounce-in border-[8px] border-purple-400 font-lexend">
                <div class="text-8xl mb-6">🏆</div>
                <h1 class="text-5xl font-black text-purple-600 mb-4">Hebat!</h1>
                <p class="text-slate-500 font-bold mb-8 text-2xl">Awak dah pandai baca dua suku kata.</p>

                <div class="bg-purple-50 p-8 rounded-3xl mb-10 border-4 border-purple-200 shadow-inner">
                    <p class="font-bold text-purple-700 uppercase tracking-widest mb-2 text-xl">Skor Markah</p>
                    <p class="text-7xl font-black text-purple-500"><span id="final-score">0</span></p>
                </div>

                <button onclick="saveAndFinish(event)"
                    class="btn-3d bg-purple-500 border-purple-700 text-white w-full text-2xl font-bold py-6 rounded-[2rem] relative inline-flex items-center justify-center border-b-[8px] active:border-b-[2px] active:translate-y-[6px] transition-all shadow-xl">
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
    </style>

    <script>
        // Data Dua Suku Kata
        const sukuKataData = [{
                suku1: 'Ba',
                suku2: 'ju',
                word: 'Baju',
                expected: ['baju'],
                img: "{{ asset('images/games/baju.png') }}",
                audio: "{{ asset('audio/Baju2.mp3') }}"
            },
            {
                suku1: 'Bo',
                suku2: 'la',
                word: 'Bola',
                expected: ['bola'],
                img: "{{ asset('images/games/bola.png') }}",
                audio: "{{ asset('audio/Bola.mp3') }}"
            },
            {
                suku1: 'Ay',
                suku2: 'am',
                word: 'Ayam',
                expected: ['ayam'],
                img: "{{ asset('images/games/ayam.png') }}",
                audio: "{{ asset('audio/Ayam.mp3') }}"
            },
            {
                suku1: 'Ma',
                suku2: 'ta',
                word: 'Mata',
                expected: ['mata'],
                img: "{{ asset('images/games/mata.png') }}",
                audio: "{{ asset('audio/Mata.mp3') }}"
            },
            {
                suku1: 'Su',
                suku2: 'su',
                word: 'Susu',
                expected: ['susu'],
                img: "{{ asset('images/games/susu.png') }}",
                audio: "{{ asset('audio/Susu.mp3') }}"
            },
            {
                suku1: 'Ub',
                suku2: 'at',
                word: 'Ubat',
                expected: ['ubat'],
                img: "{{ asset('images/games/ubat.png') }}",
                audio: "{{ asset('audio/Ubat.mp3') }}"
            },
            {
                suku1: 'Pe',
                suku2: 'ta',
                word: 'Peta',
                expected: ['peta'],
                img: "{{ asset('images/games/peta.png') }}",
                audio: "{{ asset('audio/Peta.mp3') }}"
            },
            {
                suku1: 'Me',
                suku2: 'ja',
                word: 'Meja',
                expected: ['meja'],
                img: "{{ asset('images/games/meja.png') }}",
                audio: "{{ asset('audio/Meja.mp3') }}"
            },
            {
                suku1: 'Ro',
                suku2: 'ti',
                word: 'Roti',
                expected: ['roti'],
                img: "{{ asset('images/games/roti.png') }}",
                audio: "{{ asset('audio/Roti.mp3') }}"
            },
            {
                suku1: 'Da',
                suku2: 'du',
                word: 'Dadu',
                expected: ['dadu'],
                img: "{{ asset('images/games/dadu.png') }}",
                audio: "{{ asset('audio/Dadu.mp3') }}"
            },
            {
                suku1: 'Bu',
                suku2: 'ku',
                word: 'Buku',
                expected: ['buku'],
                img: "{{ asset('images/games/buku.png') }}",
                audio: "{{ asset('audio/Buku2.mp3') }}"
            },
            {
                suku1: 'Pa',
                suku2: 'su',
                word: 'Pasu',
                expected: ['pasu'],
                img: "{{ asset('images/games/pasu.png') }}",
                audio: "{{ asset('audio/Pasu.mp3') }}"
            },
            {
                suku1: 'Na',
                suku2: 'si',
                word: 'Nasi',
                expected: ['nasi'],
                img: "{{ asset('images/games/nasi.png') }}",
                audio: "{{ asset('audio/Nasi.mp3') }}"
            },
            {
                suku1: 'Pa',
                suku2: 'ku',
                word: 'Paku',
                expected: ['paku'],
                img: "{{ asset('images/games/paku.png') }}",
                audio: "{{ asset('audio/Paku.mp3') }}"
            },
            {
                suku1: 'Ob',
                suku2: 'or',
                word: 'Obor',
                expected: ['Obor'],
                img: "{{ asset('images/games/obor.png') }}",
                audio: "{{ asset('audio/Obor.mp3') }}"
            }
        ];

        let recognition;

        let startingIndexFromDb = Math.round((window.moduleConfig.savedPercentage / 100) * sukuKataData.length);
        if (startingIndexFromDb >= sukuKataData.length) startingIndexFromDb = 0;
        let currentIndex = startingIndexFromDb;
        let isListening = false;
        let isProcessing = false;
        let score = currentIndex * 10;

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
                statusText.className = "text-red-500 font-bold text-2xl text-center mt-2 mb-6 h-8 animate-pulse";
                transcriptDisplay.innerText = "Mendengar...";
                transcriptDisplay.className =
                    "text-amber-500 font-bold text-xl italic animate-pulse text-center w-full";
            };

            recognition.onresult = function(event) {
                if (isProcessing) return;
                const transcript = event.results[0][0].transcript.toLowerCase().trim();
                transcriptDisplay.innerText = `"${transcript}"`;
                transcriptDisplay.className = "text-slate-700 font-bold text-2xl text-center w-full truncate";
                checkPronunciation(transcript);
            };

            recognition.onend = function() {
                if (isListening && !isProcessing) {
                    transcriptDisplay.innerText = "Suara tak jelas.";
                    transcriptDisplay.className = "text-slate-400 font-bold text-xl italic text-center w-full";
                    const currentWord = sukuKataData[currentIndex].word;
                    statusText.innerText = `Sebut perkataan: "${currentWord}"`;
                    statusText.className = "text-orange-500 font-bold text-2xl text-center mt-2 mb-6 h-8";
                }
                isListening = false;
                flashcard.classList.remove('is-listening');
            };

            recognition.onerror = function(event) {
                isListening = false;
                flashcard.classList.remove('is-listening');
                statusText.innerText = (event.error === 'not-allowed') ? "Sila benarkan akses mikrofon." : "Ralat: " +
                    event.error;
                statusText.className = "text-orange-500 font-bold text-2xl text-center mt-2 mb-6 h-8";
            };
        }

        function toggleListening() {
            if (isProcessing) return;
            if (isListening) {
                recognition.stop();
            } else {
                try {
                    const currentWord = sukuKataData[currentIndex].word;
                    statusText.innerText = `Sebut: "${currentWord}"`;
                    statusText.className = "text-slate-600 font-bold text-2xl text-center mt-2 mb-6 h-8";
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
            const data = sukuKataData[currentIndex];

            const percent = (currentIndex / sukuKataData.length) * 100;
            progressBar.style.width = `${percent}%`;

            flashcard.classList.remove('flip-animation', 'wrong-state');

            // Sembunyikan Overlay dan Content secara manual
            successOverlay.classList.replace('opacity-100', 'opacity-0');
            successContent.classList.replace('opacity-100', 'opacity-0');
            successContent.classList.replace('scale-100', 'scale-50');

            void flashcard.offsetWidth;
            flashcard.classList.add('flip-animation');

            suku1Display.innerText = data.suku1;
            suku2Display.innerText = data.suku2;

            spellingDisplay.innerText = `${data.suku1.toUpperCase()} - ${data.suku2.toUpperCase()}`;

            imageDisplay.src = data.img;
            roundDisplay.innerText = `${currentIndex + 1}/${sukuKataData.length}`;

            transcriptDisplay.innerText = "Tekan mic dan sedia...";
            transcriptDisplay.className = "text-slate-400 font-bold text-xl italic text-center w-full";

            statusText.innerText = `Sebut perkataan: "${data.word}"`;
            statusText.className = "text-slate-600 font-bold text-2xl text-center mt-2 mb-6 h-8";

            btnMic.classList.remove('opacity-0', 'pointer-events-none');
          
        }

        function checkPronunciation(spokenWord) {
            isProcessing = true;
            recognition.stop();

            const data = sukuKataData[currentIndex];
            const isCorrect = data.expected.some(exp => {
                const regex = new RegExp(`\\b${exp}\\b`, 'i');
                return regex.test(spokenWord) || spokenWord.includes(exp);
            });

            if (isCorrect) {
                score += 5;
                correctSound.currentTime = 0;
                correctSound.play();

                // Munculkan overlay dan besarkan teks
                successOverlay.classList.replace('opacity-0', 'opacity-100');
                successContent.classList.replace('opacity-0', 'opacity-100');
                successContent.classList.replace('scale-50', 'scale-100');

                btnMic.classList.add('opacity-0', 'pointer-events-none');
              

                setTimeout(() => {
                    proceedToNextWord();
                }, 1500);
            } else {
                wrongSound.currentTime = 0;
                wrongSound.play();
                flashcard.classList.add('wrong-state');
                statusText.innerText = `Kurang tepat. Sebut "${data.word}".`;
                statusText.className = "text-red-500 font-bold text-2xl text-center mt-2 mb-6 h-8";

                setTimeout(() => {
                    flashcard.classList.remove('wrong-state');
                    isProcessing = false;
                }, 1500);
            }
        }

        function playExample() {
            if (isProcessing || isListening) return;
            const data = sukuKataData[currentIndex];

            phonicAudio.pause();
            phonicAudio.src = data.audio;
            phonicAudio.load();

            phonicAudio.play().catch(error => {
                console.log("Audio gagal, guna fallback TTS");
                playPhonicsTTS(`${data.word}`, 0.6); // Changed to just the word to sound more natural
            });
        }

        function playPhonicsTTS(text, rate = 0.7) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ms-MY';
            utterance.rate = rate;
            window.speechSynthesis.speak(utterance);
        }

        function proceedToNextWord() {
            currentIndex++;
            if (currentIndex < sukuKataData.length) {
                loadCard();
            } else {
                endGame();
            }
        }

        function endGame() {
            progressBar.style.width = `100%`;
            document.getElementById('final-score').innerText = score;
            document.getElementById('win-overlay').classList.remove('hidden');
            winSound.currentTime = 0;
            winSound.play();
        }

        function submitProgress(isComplete, redirectUrl) {
            // --- 2. CALCULATE PERCENTAGE CORRECTLY ---
            let newPercentage = Math.round((currentIndex / sukuKataData.length) * 100);
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
