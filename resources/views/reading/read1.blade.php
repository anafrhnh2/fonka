@extends('layouts.game')

@section('game-title', 'Sebut Bunyi Fonik A-Z')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <script>
        window.moduleConfig = {
            saveUrl: "{{ route('reading.save_progress') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            moduleId: {{ $module->id ?? 1 }},
            backUrl: "{{ route('reading.index') }}",
            savedPercentage: {{ $currentProgress ?? 0 }}
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#F0FDF4] transition-all duration-300">

        <button onclick="toggleFullScreen()"
            class="absolute bottom-6 right-6 z-50 w-14 h-14 bg-slate-800/80 text-cyan-400 rounded-full flex items-center justify-center hover:bg-slate-700 hover:scale-110 active:scale-95 transition-all shadow-[0_4px_10px_rgba(0,0,0,0.5)] border-4 border-cyan-500 backdrop-blur-sm">
            <i id="fullscreen-icon" class="fas fa-expand text-2xl"></i>
        </button>

        <div class="flex flex-wrap justify-between items-center mb-2 px-4 py-4 shrink-0 z-20 w-full max-w-5xl mx-auto">
            <button onclick="saveAndExitEarly()"
                class="bg-white border-4 border-slate-200 text-slate-600 font-bold px-6 py-2 rounded-full hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> <span id="btn-back-text">Simpan & Kembali</span>
            </button>

            <div class="flex items-center gap-3 md:gap-5 font-lexend">
                <div class="flex flex-col items-center bg-white border-4 border-green-200 rounded-2xl px-4 py-1 shadow-sm">
                    <span class="text-green-500 font-bold text-xs md:text-sm uppercase tracking-wider">Huruf</span>
                    <span id="round-display" class="text-green-700 font-black text-xl md:text-2xl">1/26</span>
                </div>
            </div>
        </div>

        <div class="flex-1 w-full max-w-4xl mx-auto flex flex-col items-center justify-center relative z-10 px-4 pb-10">

            <div class="w-full max-w-md bg-slate-200 rounded-full h-2 mb-4 overflow-hidden shadow-inner">
                <div id="progress-bar" class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: 0%">
                </div>
            </div>

            <div id="flashcard"
                class="bg-white border-8 border-green-100 rounded-[3rem] shadow-2xl w-full max-w-md flex flex-col items-center p-10 relative transition-all duration-500">

                <button onclick="playExample()"
                    class="absolute top-6 left-6 w-12 h-12 bg-sky-100 text-sky-500 rounded-full flex items-center justify-center text-xl border-4 border-sky-200 hover:bg-sky-200 active:scale-95 transition-all"
                    title="Dengar Contoh">
                    <i class="fa-solid fa-volume-high"></i>
                </button>
                <br>
                <p id="example-display" class="text-gray-600 font-bold text-xl md:text-2xl mt-2">
                </p>

                <h1 id="letter-display"
                    class="text-[7rem] md:text-[9rem] font-black text-green-600 font-lexend leading-none mt-6 mb-2 drop-shadow-sm">
                    Aa</h1>

                <div
                    class="w-full h-12 flex items-center justify-center bg-slate-50 rounded-xl border-2 border-slate-100 mb-2">
                    <span id="transcript-display" class="text-slate-400 font-bold text-sm italic">Sistem bersedia...</span>
                </div>

                <p id="status-text" class="text-slate-600 font-bold text-lg text-center mt-2 h-8 transition-colors">Tekan
                    mic dan sebut perkataan</p>

                <div class="fonik-mic-wrap relative mt-6">
                    <div id="mic-ripple"
                        class="absolute inset-0 bg-green-400 rounded-full opacity-0 pointer-events-none z-10"></div>
                    <button id="btn-mic" onclick="toggleListening()"
                        class="w-20 h-20 md:w-24 md:h-24 bg-green-500 text-white rounded-full flex items-center justify-center text-4xl border-b-8 border-green-700 active:border-b-0 active:translate-y-2 transition-all shadow-[0_10px_20px_rgba(34,197,94,0.4)] relative z-20">
                        <i class="fa-solid fa-microphone"></i>
                    </button>
                </div>

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
                    <p class="font-black text-indigo-800 uppercase tracking-widest mb-1 text-sm">Skor Sebutan</p>
                    <p class="text-6xl font-black text-indigo-600 drop-shadow-sm"><span id="final-score">0</span><span
                            class="text-3xl text-indigo-400">/26</span></p>

                    <div
                        class="mt-4 inline-block bg-yellow-100 border-2 border-yellow-300 px-4 py-2 rounded-full animate-pulse">
                        <p class="text-yellow-600 font-black text-lg">
                            <i class="fa-solid fa-coins text-yellow-500 mr-1"></i> +20 Mata Diperoleh!
                        </p>
                    </div>
                </div>

                <button onclick="saveAndFinish(event)"
                    class="w-full bg-[#10B981] border-b-[6px] border-[#059669] text-white text-xl md:text-2xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3 hover:bg-[#059669] active:border-b-0 active:translate-y-1 transition-all">
                    Simpan & Selesai <i class="fa-solid fa-circle-check"></i>
                </button>
            </div>
        </div>

    </div>

    <audio id="sfx-correct" src="https://assets.mixkit.com/sfx/preview/mixkit-correct-answer-tone-2870.mp3"></audio>
    <audio id="sfx-wrong" src="https://assets.mixkit.com/sfx/preview/mixkit-buzzer-14.mp3"></audio>
    <audio id="sfx-skip" src="https://assets.mixkit.com/sfx/preview/mixkit-light-click-1136.mp3"></audio>

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
            opacity: 0.6;
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

        #progress-bar-container {
            position: relative;
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

        const alphabetData = [{
                letter: 'Aa',
                phonic: 'a',
                example: 'Ayam',
                expected: ['a', 'ah', 'aaa'],
                audio: "{{ asset('audio/A.mp3') }}"
            },
            {
                letter: 'Bb',
                phonic: 'beh',
                example: 'Bola',
                expected: ['b', 'beh', 'be', 'beg', 'bek'],
                audio: "{{ asset('audio/B.mp3') }}"
            },
            {
                letter: 'Cc',
                phonic: 'ceh',
                example: 'Cacing',
                expected: ['c', 'ceh', 'ca', 'cek'],
                audio: "{{ asset('audio/C.mp3') }}"
            },
            {
                letter: 'Dd',
                phonic: 'deh',
                example: 'Dadu',
                expected: ['d', 'deh', 'da', 'dek'],
                audio: "{{ asset('audio/D.mp3') }}"
            },
            {
                letter: 'Ee',
                phonic: 'eh',
                example: 'Emas',
                expected: ['e', 'eh', 'er', 'ek'],
                audio: "{{ asset('audio/E.mp3') }}"
            },
            {
                letter: 'Ff',
                phonic: 'feh',
                example: 'Foto',
                expected: ['f', 'feh', 'fa', 'ef'],
                audio: "{{ asset('audio/F.mp3') }}"
            },
            {
                letter: 'Gg',
                phonic: 'geh',
                example: 'Gajah',
                expected: ['g', 'geh', 'ga'],
                audio: "{{ asset('audio/G.mp3') }}"
            },
            {
                letter: 'Hh',
                phonic: 'heh',
                example: 'Hutan',
                expected: ['h', 'heh', 'ha'],
                audio: "{{ asset('audio/H.mp3') }}"
            },
            {
                letter: 'Ii',
                phonic: 'i',
                example: 'Ikan',
                expected: ['i', 'ih', 'eee'],
                audio: "{{ asset('audio/I.mp3') }}"
            },
            {
                letter: 'Jj',
                phonic: 'jeh',
                example: 'Jambu',
                expected: ['j', 'jeh', 'ja'],
                audio: "{{ asset('audio/J.mp3') }}"
            },
            {
                letter: 'Kk',
                phonic: 'keh',
                example: 'Kucing',
                expected: ['k', 'keh', 'ka', 'kek'],
                audio: "{{ asset('audio/K.mp3') }}"
            },
            {
                letter: 'Ll',
                phonic: 'el',
                example: 'Lembu',
                expected: ['l', 'el', 'la', 'il'],
                audio: "{{ asset('audio/L.mp3') }}"
            },
            {
                letter: 'Mm',
                phonic: 'em',
                example: 'Mata',
                expected: ['m', 'em', 'ma', 'hm'],
                audio: "{{ asset('audio/M.mp3') }}"
            },
            {
                letter: 'Nn',
                phonic: 'en',
                example: 'Naga',
                expected: ['n', 'en', 'na', 'in'],
                audio: "{{ asset('audio/N.mp3') }}"
            },
            {
                letter: 'Oo',
                phonic: 'o',
                example: 'Obor',
                expected: ['o', 'oh'],
                audio: "{{ asset('audio/O.mp3') }}"
            },
            {
                letter: 'Pp',
                phonic: 'peh',
                example: 'Pokok',
                expected: ['p', 'peh', 'pa'],
                audio: "{{ asset('audio/P.mp3') }}"
            },
            {
                letter: 'Qq',
                phonic: 'qeh',
                example: 'Qari',
                expected: ['q', 'qeh', 'qa', 'kiu'],
                audio: "{{ asset('audio/Q.mp3') }}"
            },
            {
                letter: 'Rr',
                phonic: 'er',
                example: 'Rusa',
                expected: ['r', 'er', 'ra', 'ar'],
                audio: "{{ asset('audio/R.mp3') }}"
            },
            {
                letter: 'Ss',
                phonic: 'es',
                example: 'Sayur',
                expected: ['s', 'es', 'sa', 'is'],
                audio: "{{ asset('audio/S.mp3') }}"
            },
            {
                letter: 'Tt',
                phonic: 'teh',
                example: 'Topi',
                expected: ['t', 'teh', 'ta'],
                audio: "{{ asset('audio/T.mp3') }}"
            },

            {
                letter: 'Uu',
                phonic: 'u',
                example: 'Ular',
                expected: ['u', 'uh', 'uu'],
                audio: "{{ asset('audio/U.mp3') }}"
            },
            {
                letter: 'Vv',
                phonic: 'veh',
                example: 'Van',
                expected: ['v', 'veh', 'va'],
                audio: "{{ asset('audio/V.mp3') }}"
            },
            {
                letter: 'Ww',
                phonic: 'weh',
                example: 'Wau',
                expected: ['w', 'weh', 'wa'],
                audio: "{{ asset('audio/W.mp3') }}"
            },
            {
                letter: 'Xx',
                phonic: 'eks',
                example: 'X-ray',
                expected: ['x', 'eks', 'ex'],
                audio: "{{ asset('audio/X.mp3') }}"
            },
            {
                letter: 'Yy',
                phonic: 'yeh',
                example: 'Yoyo',
                expected: ['y', 'yeh', 'ya'],
                audio: "{{ asset('audio/Y.mp3') }}"
            },
            {
                letter: 'Zz',
                phonic: 'zeh',
                example: 'Zebra',
                expected: ['z', 'zeh', 'za', 'zet'],
                audio: "{{ asset('audio/Z.mp3') }}"
            }
        ];

        let startingIndexFromDb = Math.floor((window.moduleConfig.savedPercentage / 100) * alphabetData.length);
        if (startingIndexFromDb >= alphabetData.length) startingIndexFromDb = 0;

        let currentIndex = startingIndexFromDb;
        let score = startingIndexFromDb;
        let isListening = false;
        let isProcessing = false;
        let recognition;
        let phonicAudio = new Audio();
        const letterDisplay = document.getElementById('letter-display');
        const statusText = document.getElementById('status-text');
        const transcriptDisplay = document.getElementById('transcript-display');
        const roundDisplay = document.getElementById('round-display');
        const progressBar = document.getElementById('progress-bar');
        const flashcard = document.getElementById('flashcard');
        const successOverlay = document.getElementById('success-overlay');

        const correctSound = new Audio('{{ asset('audio/bagus.mp3') }}');
        const wrongSound = new Audio('{{ asset('audio/cubalagi.mp3') }}');
        const winSound = new Audio('{{ asset('audio/tahniah.mp3') }}');


        function setupSpeechRecognition() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SpeechRecognition) {
                transcriptDisplay.innerText = "Browser tidak sokong mic.";
                return;
            }
            recognition = new SpeechRecognition();
            recognition.lang = 'ms-MY';
            recognition.interimResults = false;
            recognition.onstart = () => {
                isListening = true;
                fsContainer.classList.add('is-listening');
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
                fsContainer.classList.remove('is-listening');
                if (!isProcessing) statusText.innerText = "Cuba sebut lagi";
            };
        }

        function toggleListening() {
            if (isProcessing) return;
            if (isListening) recognition.stop();
            else recognition.start();
        }

        function loadLetter() {

            isProcessing = false;
            const data = alphabetData[currentIndex];
            const nextAudio = new Audio();
            nextAudio.src = data.audio;
            nextAudio.preload = "auto";
            const percent = (currentIndex / alphabetData.length) * 100;
            progressBar.style.width = `${percent}%`;

            flashcard.classList.remove('flip-animation', 'wrong-state');
            successOverlay.classList.replace('opacity-100', 'opacity-0');
            document.getElementById('success-content').classList.replace('scale-100', 'scale-50');
            document.getElementById('success-content').classList.replace('opacity-100', 'opacity-0');
            void flashcard.offsetWidth;
            flashcard.classList.add('flip-animation');

            letterDisplay.innerText = data.letter;
            document.getElementById('example-display').innerText =
                `Sebut satu perkataan yang bermula dengan huruf:`;
            roundDisplay.innerText = `${currentIndex + 1}/${alphabetData.length}`;
            transcriptDisplay.innerText = "Tekan mic dan sedia...";

            statusText.innerText = `Contoh: ${data.example}`;
        }

        function checkPronunciation(spokenWord) {
            if (!spokenWord) {
                statusText.innerText = "Tidak dapat dengar. Cuba lagi!";
                return;
            }
            isProcessing = true;
            recognition.stop();
            const data = alphabetData[currentIndex];

            console.log("System heard:", spokenWord);
            console.log("Expecting one of:", data.expected);
            const isCorrect = data.expected.some(exp => {
                const regex = new RegExp(`${exp}`, 'i');
                return regex.test(spokenWord) || spokenWord.includes(exp);
            });

            if (isCorrect) {
                score++;
                correctSound.currentTime = 0;
                correctSound.play();
                successOverlay.classList.replace('opacity-0', 'opacity-100');
                document.getElementById('success-content').classList.remove('scale-50', 'opacity-0');
                document.getElementById('success-content').classList.add('scale-100', 'opacity-100');
                setTimeout(proceedNext, 1500);
            } else {
                wrongSound.currentTime = 0;
                wrongSound.play();
                flashcard.classList.add('wrong-state');
                statusText.innerText = "Kurang tepat. Cuba lagi!";
                setTimeout(() => {
                    flashcard.classList.remove('wrong-state');
                    isProcessing = false;
                }, 1500);
            }
        }

        function playExample() {
            if (isProcessing || isListening) return;

            const data = alphabetData[currentIndex];

            phonicAudio.pause();

            phonicAudio.src = data.audio;

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

        function skipLetter() {
            if (isProcessing || isListening) return;
            sfxSkip.currentTime = 0;
            sfxSkip.play();
            proceedNext();
        }

        function proceedNext() {
            if (currentIndex < alphabetData.length - 1) {
                currentIndex++;
                loadLetter();
            } else {
                endGame();
            }
        }

        function renderStars(count) {
            const container = document.getElementById('star-container');
            container.innerHTML = '';
            for (let i = 0; i < 3; i++) {
                const color = i < count ? 'text-yellow-400' : 'text-slate-300';
                container.innerHTML += `<i class="fa-solid fa-star ${color} text-4xl"></i>`;
            }
        }

        function endGame() {
            progressBar.style.width = `100%`;
            document.getElementById('final-score').innerText = score;
            const stars = score >= 20 ? 3 : (score >= 13 ? 2 : 1);
            renderStars(stars);
            document.getElementById('win-overlay').classList.remove('hidden');
            winSound.currentTime = 0;
            winSound.play();
        }

        function submitProgress(isComplete, redirectUrl) {
            let newPercentage = Math.round((score / alphabetData.length) * 100);
            if (isComplete) newPercentage = 100;

            let pointsEarned = isComplete ? 20 : 0;

            fetch(window.moduleConfig.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    child_id: window.moduleConfig.childId,
                    reading_module_id: window.moduleConfig.moduleId,
                    progress_percentage: newPercentage,
                    is_completed: isComplete,
                    score: pointsEarned
                })
            }).then(() => {
                window.location.href = redirectUrl;
            }).catch(() => {
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
            loadLetter();
        });
    </script>
@endsection
