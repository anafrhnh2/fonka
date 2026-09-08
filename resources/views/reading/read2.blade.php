@extends('layouts.game')

@section('game-title', 'Belajar Huruf Vokal (A, E, I, O, U)')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <script>
        window.moduleConfig = {
            saveUrl: "{{ route('reading.save_progress') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            moduleId: {{ $module->id ?? 2 }},
            backUrl: "{{ route('reading.index') }}",
            savedPercentage: {{ $currentProgress ?? 0 }}
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#EEF2FF] transition-all duration-300">

        <div class="flex flex-wrap justify-between items-center mb-2 px-4 py-4 shrink-0 z-20 w-full max-w-5xl mx-auto">
            <button onclick="saveAndExitEarly()"
                class="bg-white border-4 border-slate-200 text-slate-600 font-bold px-6 py-2 rounded-full hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> <span id="btn-back-text">Simpan & Kembali</span>
            </button>

            <div class="flex items-center gap-3 md:gap-5 font-lexend">
                <div class="flex flex-col items-center bg-white border-4 border-indigo-200 rounded-2xl px-4 py-1 shadow-sm">
                    <span class="text-indigo-500 font-bold text-xs md:text-sm uppercase tracking-wider">Tahap</span>
                    <span id="round-display" class="text-indigo-700 font-black text-xl md:text-2xl">1/6</span>
                </div>
            </div>
        </div>

        <div class="flex-1 w-full max-w-4xl mx-auto flex flex-col items-center justify-center relative z-10 px-4 pb-10">

            <div class="w-full max-w-md bg-slate-200 rounded-full h-3 mb-4 overflow-hidden shadow-inner">
                <div id="progress-bar" class="bg-indigo-500 h-3 rounded-full transition-all duration-500" style="width: 0%">
                    </div>
            </div>

            <div id="flashcard"
                class="bg-white border-8 border-indigo-100 rounded-[3rem] shadow-2xl w-full max-w-md flex flex-col items-center p-8 relative transition-all duration-500">

                <button onclick="playExample()"
                    class="absolute top-6 left-6 w-12 h-12 bg-sky-100 text-sky-500 rounded-full flex items-center justify-center text-xl border-4 border-sky-200 hover:bg-sky-200 active:scale-95 transition-all"
                    title="Dengar Contoh">
                    <i class="fa-solid fa-volume-high"></i>
                </button>

                <span id="vowel-label"
                    class="absolute top-6 right-6 bg-amber-100 text-amber-700 font-bold px-4 py-1 rounded-full text-sm hidden border-2 border-amber-300">
                    E Taling
                </span>

                <h1 id="letter-display"
                    class="text-[6rem] md:text-[8rem] font-black text-indigo-600 font-lexend leading-none mt-4 drop-shadow-sm">
                    A</h1>

                <div
                    class="bg-indigo-50 border-4 border-indigo-100 w-full rounded-2xl py-4 flex flex-col items-center justify-center mb-4 mt-2 shadow-inner">
                    <p class="text-slate-400 font-bold text-sm uppercase tracking-widest mb-1">Perkataan <span
                            id="word-count">1/3</span></p>
                    <h2 id="word-display" class="text-4xl md:text-5xl font-black text-slate-700 font-lexend">Ayam</h2>
                </div>

                <div
                    class="w-full h-12 flex items-center justify-center bg-slate-50 rounded-xl border-2 border-slate-100 mb-2">
                    <span id="transcript-display" class="text-slate-400 font-bold text-sm italic">Sistem bersedia...</span>
                </div>

                <p id="status-text" class="text-slate-600 font-bold text-lg text-center mt-2 h-8 transition-colors">Tekan
                    mic dan sebut perkataan di atas</p>

                <button id="btn-mic" onclick="toggleListening()"
                    class="mt-4 w-20 h-20 bg-indigo-500 text-white rounded-full flex items-center justify-center text-4xl border-b-8 border-indigo-700 active:border-b-0 active:translate-y-2 transition-all shadow-[0_10px_20px_rgba(99,102,241,0.4)] relative z-20">
                    <i class="fa-solid fa-microphone"></i>
                </button>

                <div id="mic-ripple"
                    class="absolute bottom-10 w-20 h-20 bg-indigo-400 rounded-full opacity-0 pointer-events-none z-10">
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

        <button onclick="toggleFullScreen()"
            class="absolute bottom-6 right-6 w-14 h-14 bg-indigo-600 text-white rounded-full flex items-center justify-center text-2xl border-4 border-indigo-300 hover:bg-indigo-700 active:scale-95 transition-all shadow-lg z-40"
            title="Skrin Penuh">
            <i id="fullscreen-icon" class="fa-solid fa-expand"></i>
        </button>

        <div id="win-overlay"
            class="hidden absolute inset-0 bg-slate-900/90 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8">
            <div
                class="bg-white p-8 rounded-[2.5rem] shadow-2xl text-center max-w-md w-full animate-bounce-in border-[6px] border-indigo-400 font-lexend relative pt-12">
                
                <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 text-5xl drop-shadow-lg text-yellow-500 bg-white rounded-full p-2 border-4 border-yellow-200 w-24 h-24 flex justify-center items-center shadow-md animate-pulse">
                    <i class="fa-solid fa-trophy"></i>
                </div>

                <h1 class="text-4xl font-black text-indigo-600 mb-2">Hebat!</h1>
                <p class="text-slate-500 font-bold mb-6 text-lg">Awak dah mahir semua huruf vokal.</p>

                <div class="bg-indigo-50 p-5 rounded-2xl mb-6 border-4 border-indigo-200 shadow-inner">
                    <p class="font-bold text-indigo-700 uppercase tracking-widest mb-1">Skor Vokal</p>
                    <p class="text-6xl font-black text-indigo-500"><span id="final-score">0</span></p>
                </div>

                <div class="flex justify-center gap-2 mb-6" id="star-container">
                    </div>

                <button onclick="saveAndFinish(event)"
                    class="btn-3d bg-indigo-500 border-indigo-700 text-white w-full text-xl font-bold py-4 rounded-[1.5rem] relative inline-flex items-center justify-center border-b-[6px] active:border-b-[2px] active:translate-y-[4px] transition-all">
                    Selesai & Simpan ▶
                </button>
            </div>
        </div>

    </div>

    <audio id="sfx-correct" src="https://assets.mixkit.com/sfx/preview/mixkit-correct-answer-tone-2870.mp3"></audio>
    <audio id="sfx-wrong" src="https://assets.mixkit.com/sfx/preview/mixkit-buzzer-14.mp3"></audio>
    <audio id="sfx-skip" src="https://assets.mixkit.com/sfx/preview/mixkit-light-click-1136.mp3"></audio>

    <style>
        .font-lexend { font-family: 'Lexend', sans-serif; }

        @keyframes flipIn {
            0% { transform: rotateY(-90deg); opacity: 0; }
            100% { transform: rotateY(0); opacity: 1; }
        }
        .flip-animation { animation: flipIn 0.4s ease-out; }

        @keyframes ripple {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(2.5); opacity: 0; }
        }
        .is-listening #mic-ripple { animation: ripple 1.5s infinite; }

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
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        @keyframes bounce-in {
            0% { transform: scale(0.5); opacity: 0; }
            60% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-bounce-in { animation: bounce-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }

        .is-fullscreen #flashcard { transform: scale(1.1); margin-top: 2rem; }
        #progress-bar-container { position: relative; z-index: 50; }
    </style>

    <script>
        const vowelData = [
            {
                letter: 'A', phonic: 'a', type: '',
                words: [
                    { word: 'Ayam', expected: ['ayam'], audio: "{{ asset('audio/Ayam.mp3') }}" },
                    { word: 'Api', expected: ['api'], audio: "{{ asset('audio/Api.mp3') }}" }
                ]
            },
            {
                letter: 'E', phonic: 'eh', type: 'E Pepet',
                words: [
                    { word: 'Emas', expected: ['emas'], audio: "{{ asset('audio/emas.mp3') }}" },
                    { word: 'Enam', expected: ['enam'], audio: "{{ asset('audio/enam.mp3') }}" },
                    { word: 'Empat', expected: ['empat'], audio: "{{ asset('audio/empat.mp3') }}" }
                ]
            },
            {
                letter: 'E', phonic: 'e', type: 'E Taling',
                words: [
                    { word: 'Epal', expected: ['epal'], audio: "{{ asset('audio/epal.mp3') }}" },
                    { word: 'Enak', expected: ['enak'], audio: "{{ asset('audio/enak.mp3') }}" },
                    { word: 'Ekor', expected: ['ekor'], audio: "{{ asset('audio/ekor.mp3') }}" }
                ]
            },
            {
                letter: 'I', phonic: 'i', type: '',
                words: [
                    { word: 'Ikan', expected: ['ikan'], audio: "{{ asset('audio/Ikan.mp3') }}" },
                    { word: 'Ibu', expected: ['ibu'], audio: "{{ asset('audio/Ibu.mp3') }}" },
                    { word: 'Itik', expected: ['itik'], audio: "{{ asset('audio/Itik.mp3') }}" }
                ]
            },
            {
                letter: 'O', phonic: 'o', type: '',
                words: [
                    { word: 'Otak', expected: ['otak'], audio: "{{ asset('audio/Otak.mp3') }}" },
                    { word: 'Oren', expected: ['oren'], audio: "{{ asset('audio/Oren.mp3') }}" },
                    { word: 'Obor', expected: ['obor'], audio: "{{ asset('audio/Obor.mp3') }}" }
                ]
            },
            {
                letter: 'U', phonic: 'u', type: '',
                words: [
                    { word: 'Ular', expected: ['ular'], audio: "{{ asset('audio/Ular.mp3') }}" },
                    { word: 'Ubat', expected: ['ubat'], audio: "{{ asset('audio/Ubat.mp3') }}" },
                    { word: 'Unta', expected: ['unta'], audio: "{{ asset('audio/Unta.mp3') }}" }
                ]
            }
        ];

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

        const fsContainer = document.getElementById('game-fullscreen-container');
        const fullscreenIcon = document.getElementById('fullscreen-icon');

        // FIX FIX: Menggantikan rujukan alphabetData kepada vowelData yang betul
        let startingIndexFromDb = Math.floor((window.moduleConfig.savedPercentage / 100) * vowelData.length);
        if (startingIndexFromDb >= vowelData.length) startingIndexFromDb = 0;

        let currentVowelIndex = startingIndexFromDb;
        let currentWordIndex = 0;
        let score = 0; // Mulakan mata kumpul baharu dari 0
        let isListening = false;
        let isProcessing = false;
        let recognition;

        const letterDisplay = document.getElementById('letter-display');
        const wordDisplay = document.getElementById('word-display');
        const wordCount = document.getElementById('word-count');
        const vowelLabel = document.getElementById('vowel-label');

        const statusText = document.getElementById('status-text');
        const transcriptDisplay = document.getElementById('transcript-display');
        const roundDisplay = document.getElementById('round-display');
        const progressBar = document.getElementById('progress-bar');
        const flashcard = document.getElementById('flashcard');
        const btnMic = document.getElementById('btn-mic');
        const successOverlay = document.getElementById('success-overlay');

        const correctSound = new Audio('{{ asset('audio/bagus.mp3') }}');
        const wrongSound = new Audio('{{ asset('audio/cubalagi.mp3') }}');
        const winSound = new Audio('{{ asset('audio/tahniah.mp3') }}');

        function setupSpeechRecognition() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            if (!SpeechRecognition) {
                transcriptDisplay.innerText = "Web tidak menyokong mic.";
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
                transcriptDisplay.className = "text-slate-700 font-bold text-xl";
                checkPronunciation(transcript);
            };

            recognition.onend = function() {
                if (isListening && !isProcessing) {
                    transcriptDisplay.innerText = "Suara tidak jelas.";
                    const currentWord = vowelData[currentVowelIndex].words[currentWordIndex].word;
                    statusText.innerText = `Sebut perkataan: "${currentWord}"`;
                    statusText.className = "text-orange-500 font-bold text-lg text-center mt-4 h-8";
                }
                isListening = false;
                flashcard.classList.remove('is-listening');
            };

            recognition.onerror = function(event) {
                isListening = false;
                flashcard.classList.remove('is-listening');
                statusText.innerText = (event.error === 'not-allowed') ? "Sila benarkan akses mikrofon." : "Ralat: " + event.error;
                statusText.className = "text-orange-500 font-bold text-lg text-center mt-4 h-8";
            };
        }

        function toggleListening() {
            if (isProcessing) return;
            if (isListening) {
                recognition.stop();
            } else {
                try {
                    const currentWord = vowelData[currentVowelIndex].words[currentWordIndex].word;
                    statusText.innerText = `Sebut: "${currentWord}"`;
                    statusText.className = "text-slate-600 font-bold text-lg text-center mt-4 h-8";
                    recognition.start();
                } catch (e) {
                    recognition.stop();
                }
            }
        }

        function loadVowelCard() {
            isProcessing = false;
            const data = vowelData[currentVowelIndex];
            const wordData = data.words[currentWordIndex];

            const percent = ((currentVowelIndex + (currentWordIndex / data.words.length)) / vowelData.length) * 100;
            progressBar.style.width = `${percent}%`;

            flashcard.classList.remove('flip-animation', 'wrong-state');
            successOverlay.classList.replace('opacity-100', 'opacity-0');
            document.getElementById('success-content').classList.replace('scale-100', 'scale-50');
            document.getElementById('success-content').classList.replace('opacity-100', 'opacity-0');
            void flashcard.offsetWidth;
            flashcard.classList.add('flip-animation');

            letterDisplay.innerText = data.letter;
            wordDisplay.innerText = wordData.word;
            wordCount.innerText = `${currentWordIndex + 1}/${data.words.length}`;
            
            // PUSINGAN DIPERBAIKI (Ia akan dikemaskini dari 1/6 hingga 6/6 dengan lancar sekarang)
            roundDisplay.innerText = `${currentVowelIndex + 1}/${vowelData.length}`;

            if (data.type !== "") {
                vowelLabel.innerText = data.type;
                vowelLabel.classList.remove('hidden');
            } else {
                vowelLabel.classList.add('hidden');
            }

            transcriptDisplay.innerText = "Tekan mic dan sedia...";
            transcriptDisplay.className = "text-slate-400 font-bold text-sm italic";

            statusText.innerText = `Sebut perkataan: "${wordData.word}"`;
            statusText.className = "text-slate-600 font-bold text-lg text-center mt-4 h-8";

            btnMic.classList.remove('opacity-0', 'pointer-events-none');
        }

        function checkPronunciation(spokenWord) {
            isProcessing = true;
            try { recognition.stop(); } catch (e) {}

            const wordData = vowelData[currentVowelIndex].words[currentWordIndex];
            const isCorrect = wordData.expected.some(exp => spokenWord.includes(exp.toLowerCase()));

            if (isCorrect) {
                score += 5; // Setiap perkataan betul diberi 5 mata
                if (correctSound) {
                    correctSound.currentTime = 0;
                    correctSound.play().catch(e => console.log("Audio blocked"));
                }

                successOverlay.classList.replace('opacity-0', 'opacity-100');
                const successContent = document.getElementById('success-content');
                successContent.classList.remove('scale-50', 'opacity-0');
                successContent.classList.add('scale-100', 'opacity-100');

                btnMic.classList.add('opacity-0', 'pointer-events-none');

                setTimeout(() => {
                    proceedToNextWord();
                }, 1500);
            } else {
                if (wrongSound) {
                    wrongSound.currentTime = 0;
                    wrongSound.play().catch(e => console.log("Audio blocked"));
                }

                flashcard.classList.add('wrong-state');
                statusText.innerText = `Cuba lagi! Sebut "${wordData.word}"`;
                statusText.className = "text-red-500 font-bold text-lg text-center mt-4 h-8";

                setTimeout(() => {
                    flashcard.classList.remove('wrong-state');
                    isProcessing = false;
                    isListening = false;
                    transcriptDisplay.innerText = "Sedia untuk cuba lagi...";
                    transcriptDisplay.className = "text-slate-400 font-bold text-sm italic";
                }, 1500);
            }
        }

        function proceedToNextWord() {
            isProcessing = false;
            isListening = false;
            currentWordIndex++;

            if (currentWordIndex >= vowelData[currentVowelIndex].words.length) {
                currentWordIndex = 0;
                currentVowelIndex++;

                if (currentVowelIndex < vowelData.length) {
                    loadVowelCard();
                } else {
                    endGame();
                }
            } else {
                loadVowelCard();
            }
        }

        function playExample() {
            if (isProcessing || isListening) return;
            const wordData = vowelData[currentVowelIndex].words[currentWordIndex];
            const audio = new Audio(wordData.audio);
            audio.play().catch(() => playPhonicsTTS(wordData.word, 0.7));
        }

        function playPhonicsTTS(text, rate = 0.8) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ms-MY';
            utterance.rate = rate;
            window.speechSynthesis.speak(utterance);
        }

        function renderStars(starCount) {
            const container = document.getElementById('star-container');
            container.innerHTML = '';
            for (let i = 0; i < 3; i++) {
                const isEarned = i < starCount;
                const starColor = isEarned ? 'text-yellow-400 drop-shadow-md' : 'text-slate-300 drop-shadow-none';
                const starSize = i === 1 ? 'text-5xl -mt-2' : 'text-4xl';
                container.innerHTML += `<i class="fa-solid fa-star ${starColor} ${starSize} transition-all"></i>`;
            }
        }

        function endGame() {
            progressBar.style.width = `100%`;
            document.getElementById('final-score').innerText = score;

            let stars = score >= 70 ? 3 : (score >= 40 ? 2 : 1);
            renderStars(stars);

            document.getElementById('win-overlay').classList.remove('hidden');
            winSound.currentTime = 0;
            winSound.play();
        }

        async function submitProgress(isComplete, redirectUrl) {
            let newPercentage = Math.round((currentVowelIndex / vowelData.length) * 100);
            if (isComplete) newPercentage = 100;

            const data = {
                child_id: window.moduleConfig.childId,
                reading_module_id: window.moduleConfig.moduleId,
                progress_percentage: newPercentage,
                is_completed: isComplete,
                score: score 
            };

            const btnText = document.getElementById('btn-back-text');
            if (btnText && !isComplete) btnText.innerText = "Menyimpan...";

            try {
                await fetch(window.moduleConfig.saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
            } catch (error) {
                console.error('Ralat ketika menyimpan:', error);
            } finally {
                window.location.href = redirectUrl;
            }
        }

        function saveAndExitEarly() {
            submitProgress(false, window.moduleConfig.backUrl);
        }

        function saveAndFinish(event) {
            if(event) event.preventDefault(); // Menghentikan gangguan submit browser awal
            submitProgress(true, window.moduleConfig.backUrl);
        }

        document.addEventListener('DOMContentLoaded', () => {
            setupSpeechRecognition();
            loadVowelCard();
        });
    </script>
@endsection