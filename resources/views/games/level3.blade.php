@extends('layouts.game')

@section('game-title', 'Tahap 3: Pokok Bunyi E')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 3 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container" class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#F0FDF4]">

        <div class="w-full h-full flex flex-col relative z-0 p-2 md:p-4">

            <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0">
                <div class="flex items-center bg-green-50 border-4 border-green-200 rounded-full px-4 py-2 shadow-sm">
                    <span class="text-green-800 font-black text-sm md:text-xl uppercase tracking-wider">
                        <i class="fa-solid fa-tree mr-2"></i> Asingkan Bunyi E
                    </span>
                </div>

                <div class="flex items-center gap-3 md:gap-5">
                    <div
                        class="flex flex-col items-center bg-green-50 border-4 border-green-200 rounded-2xl px-4 py-1 shadow-sm">
                        <span class="text-green-600 font-bold text-xs md:text-sm uppercase tracking-wider">Markah</span>
                        <div class="flex items-center gap-1">
                            <span id="score-display" class="text-green-700 font-black text-xl md:text-2xl">0</span>
                        </div>
                    </div>
                    <div
                        class="flex flex-col items-center bg-orange-50 border-4 border-orange-200 rounded-2xl px-4 py-1 shadow-sm">
                        <span class="text-orange-500 font-bold text-xs md:text-sm uppercase tracking-wider">Pusingan</span>
                        <span id="round-display" class="text-orange-700 font-black text-xl md:text-2xl">0/6</span>
                    </div>
                   <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                            class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                            <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                        </button>
                </div>
            </div>

            <div id="game-board"
                class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-green-100 w-full flex flex-col bg-[#E8F5E9]">
                <img src="{{ asset('images/games/bg3.png') }}" alt="Background"
                    class="absolute inset-0 w-full h-full object-cover z-0 pointer-events-none opacity-80">

                <div class="w-full flex-1 flex justify-around items-end pb-32 md:pb-40 relative z-10 px-2 md:px-8">

                    <div class="flex flex-col items-center tree-container w-[48%] max-w-[450px]">
                        <div class="tree-dropzone relative w-full aspect-square flex flex-col items-center justify-end cursor-pointer transition-transform z-20 pb-8"
                            id="tree-lembut" data-type="lembut" onclick="playTreeSound('lembut')">
                            <button type="button" onclick="playTreeSound('lembut')"
                                class="absolute left-2 top-2 w-12 h-12 md:w-14 md:h-14 bg-amber-400 text-white rounded-full flex items-center justify-center text-xl md:text-2xl border-4 border-amber-200 shadow-[0_4px_0_#D97706] active:translate-y-1 active:shadow-none transition-all z-50">
                                <i class="fa-solid fa-volume-high"></i>
                            </button>
                            <img src="{{ asset('images/games/pokok-jelas.png') }}" alt="Pokok Lembut"
                                class="absolute inset-0 w-full h-full object-contain pointer-events-none drop-shadow-2xl">

                            <div
                                class="relative z-10 bg-white/90 backdrop-blur-sm border-4 border-green-200 px-4 py-2 rounded-2xl shadow-lg text-center pointer-events-none mb-4">
                                <span class="text-green-700 font-black text-lg md:text-xl block">E Pepet</span>
                            </div>


                        </div>
                    </div>

                    <div class="flex flex-col items-center tree-container w-[48%] max-w-[450px]">
                        <div class="tree-dropzone relative w-full aspect-square flex flex-col items-center justify-end cursor-pointer transition-transform z-20 pb-8"
                            id="tree-jelas" data-type="jelas" onclick="playTreeSound('jelas')">
                            <button type="button" onclick="playTreeSound('jelas')"
                                class="absolute left-2 top-2 w-12 h-12 md:w-14 md:h-14 bg-amber-400 text-white rounded-full flex items-center justify-center text-xl md:text-2xl border-4 border-amber-200 shadow-[0_4px_0_#D97706] active:translate-y-1 active:shadow-none transition-all z-50">
                                <i class="fa-solid fa-volume-high"></i>
                            </button>
                            <img src="{{ asset('images/games/pokok-jelas.png') }}" alt="Pokok Jelas"
                                class="absolute inset-0 w-full h-full object-contain pointer-events-none drop-shadow-2xl">

                            <div
                                class="relative z-10 bg-white/90 backdrop-blur-sm border-4 border-blue-200 px-4 py-2 rounded-2xl shadow-lg text-center pointer-events-none mb-4">
                                <span class="text-blue-700 font-black text-lg md:text-xl block">E Taling</span>
                            </div>


                        </div>
                    </div>

                </div>

                <div
                    class="absolute bottom-0 left-0 w-full h-32 md:h-40 flex justify-center items-end pb-4 bg-gradient-to-t from-white/10 to-transparent z-30 pointer-events-none">

                    <div class="relative flex justify-center items-center pointer-events-auto" id="drag-container">

                        <button onclick="playWordAudio()"
                            class="absolute -left-16 md:-left-20 top-1/2 -translate-y-1/2 w-12 h-12 md:w-14 md:h-14 bg-amber-400 text-white rounded-full flex items-center justify-center text-xl md:text-2xl border-4 border-amber-200 shadow-[0_4px_0_#D97706] active:translate-y-1 active:shadow-none transition-all z-20">
                            <i class="fa-solid fa-volume-high"></i>
                        </button>

                        <div id="card-placeholder" class="w-42 h-42 md:w-52 md:h-52 relative">
                            <div id="draggable-card"
                                class="w-42 h-42 md:w-52 md:h-52 absolute top-0 left-0 bg-white rounded-3xl border-[6px] border-yellow-200 shadow-[0_10px_20px_rgba(0,0,0,0.15)] flex flex-col items-center justify-center cursor-grab active:cursor-grabbing touch-none z-50">
                                <img id="card-img" src="" alt="Soalan"
                                    class="w-3/4 h-3/4 object-contain pointer-events-none mb-1">
                                <span id="card-word"
                                    class="font-black text-gray-700 text-lg md:text-xl uppercase pointer-events-none">EMAS</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div id="start-overlay"
            class="absolute inset-0 bg-[#0B172A]/80 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">
            <div
                class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full flex flex-col transform transition-all scale-100 border-[6px] border-green-100">

                <h1 class="text-3xl md:text-4xl font-bold text-green-600 mb-6 font-sans tracking-wide">
                    Cara Bermain
                </h1>

                <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10 flex-1">
                    <div class="flex items-center gap-4 bg-blue-50 p-4 rounded-xl border-2 border-blue-100 shadow-sm">
                        <div
                            class="w-12 h-12 bg-blue-200 text-blue-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-ear-listen"></i>
                        </div>
                        <p class="text-gray-600 font-bold text-lg md:text-xl leading-tight">Dengar bunyi perkataan.</p>
                    </div>

                    <div class="flex items-center gap-4 bg-orange-50 p-4 rounded-xl border-2 border-orange-100 shadow-sm">
                        <div
                            class="w-12 h-12 bg-orange-200 text-orange-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-solid fa-hand-pointer"></i>
                        </div>
                        <p class="text-gray-600 font-bold text-lg md:text-xl leading-tight">Seret gambar ke pokok yang
                            betul!</p>
                    </div>
                </div>

                <button onclick="startGame()"
                    class="w-full bg-[#22C55E] hover:bg-[#16A34A] text-white text-2xl font-bold py-5 rounded-[1.5rem] shadow-[0_6px_0_#15803D] active:translate-y-[6px] active:shadow-none transition-all flex justify-center items-center gap-3 mt-auto shrink-0">
                    <i class="fa-solid fa-play"></i> Mula
                </button>
            </div>
        </div>

        <div id="win-overlay"
            class="hidden absolute inset-0 bg-black/80 z-50 flex flex-col justify-center items-center backdrop-blur-sm px-4 py-8 overflow-y-auto">
            <div
                class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full animate-bounce-in border-[6px] border-indigo-200 relative my-auto">
                <div
                    class="absolute -top-12 left-1/2 transform -translate-x-1/2 text-7xl drop-shadow-lg text-yellow-400 bg-white rounded-full p-2 border-4 border-yellow-200">
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
                        Main Semula <i class="fa-solid fa-rotate-right"> </i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <style>
        /* disable text highlight */
        * {
            user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }

        /* disable image drag default */
        img {
            pointer-events: none;
            user-drag: none;
            -webkit-user-drag: none;
        }

        body {
            overflow-y: auto;
        }

        #game-container {
            width: 100vw;
            height: 100vh;
            position: relative;
        }

        .tree-dropzone.drag-over {
            transform: scale(1.1);
            filter: drop-shadow(0 0 20px rgba(253, 224, 71, 0.8));
            /* Sinaran warna kuning/emas */
        }

        .tree-dropzone.drag-over img {
            filter: brightness(1.1);
        }

           .fullscreen-btn-mobile {
            z-index: 99999 !important;
            pointer-events: auto !important;
            touch-action: manipulation !important;
            -webkit-tap-highlight-color: transparent;
        }

        #game-fullscreen-container {
            isolation: auto;
            /* auto lebih selamat untuk tak clash dengan absolute buttons */
        }

        /* Add to your existing <style> block */
        .fixed.inset-0 {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100dvh;
            z-index: 9999;
        }

        .btn-3d {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
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

        .btn-green {
            background-color: #4CAF50;
            border-color: #2E7D32;
            border-top-color: #81C784;
            border-left-color: #81C784;
        }

        .btn-blue {
            background-color: #0EA5E9;
            border-color: #0369A1;
            border-top-color: #7DD3FC;
            border-left-color: #7DD3FC;
        }

        #draggable-card {
            will-change: transform;
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
                transform: scale(0.8);
                opacity: 0;
            }

            60% {
                transform: scale(1.05);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-bounce-in {
            animation: bounce-in 0.5s forwards;
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
        function toggleFullScreen() {
            const fsContainer = document.getElementById('game-fullscreen-container');

            // Check if we are currently in either native OR fake fullscreen
            const isCurrentlyFullscreen = document.fullscreenElement || fsContainer.classList.contains('fixed');

            if (!isCurrentlyFullscreen) {
                // Attempt native fullscreen (this will be ignored by iOS)
                if (fsContainer.requestFullscreen) {
                    fsContainer.requestFullscreen().catch(err => {
                        console.log("Browser blocked native fullscreen.");
                    });
                }

                // Apply "fake" fullscreen styling
                fsContainer.classList.add('fixed', 'inset-0', 'z-[9999]');
                fullscreenIcon.classList.replace('fa-expand', 'fa-compress');
            } else {
                // Exit native fullscreen
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                }

                // Remove "fake" fullscreen styling
                fsContainer.classList.remove('fixed', 'inset-0', 'z-[9999]');
                fullscreenIcon.classList.replace('fa-compress', 'fa-expand');
            }
        }
        // --- DATA SOALAN ---
        const gameData = [{
                word: 'emas',
                type: 'lembut',
                img: '{{ asset('images/games/emas.png') }}'
            },
            {
                word: 'enam',
                type: 'lembut',
                img: '{{ asset('images/games/enam.png') }}'
            },
            {
                word: 'emak',
                type: 'lembut',
                img: '{{ asset('images/games/emak.png') }}'
            },
            {
                word: 'epal',
                type: 'jelas',
                img: '{{ asset('images/games/epal.png') }}'
            },
            {
                word: 'ekor',
                type: 'jelas',
                img: '{{ asset('images/games/ekor.png') }}'
            },
            {
                word: 'enak',
                type: 'jelas',
                img: '{{ asset('images/games/enak.png') }}'
            }
        ];

        function preloadImages() {
            gameData.forEach(data => {
                const img = new Image();
                img.src = data.img;
            });
        }
        document.addEventListener('DOMContentLoaded', preloadImages);

        let shuffledData = [];
        let currentIndex = 0;
        let isDragging = false;
        let startX, startY;
        let containerRect;

        // --- VARIABLE MARKAH ---
        let correctAnswersCount = 0;
        let currentScore = 0;
        let gameInterval;
        const card = document.getElementById('draggable-card');
        const dragContainer = document.getElementById('drag-container');
        const trees = document.querySelectorAll('.tree-dropzone');
        const roundDisplay = document.getElementById('round-display');
        const scoreDisplay = document.getElementById('score-display');
        const fullscreenContainer = document.getElementById('game-fullscreen-container');

        function playAudio(filename) {
            if (window.currentAudio) {
                window.currentAudio.pause();
                window.currentAudio.currentTime = 0;
            }

            const audio = new Audio(`${window.gameConfig.audioBaseUrl}/${filename}`);
            window.currentAudio = audio;

            audio.play().catch(e => console.log("Audio error:", e));
        }


        function playWordAudio() {
            const word = shuffledData[currentIndex].word;
            playAudio(`${word}.mp3`);
        }

        function playTreeSound(type) {
            if (type === 'lembut') playAudio('E.mp3');
            else playAudio('E nak.mp3');
        }

        function playFeedback(type) {
            if (type === 'correct') playAudio('bagus.mp3');
            if (type === 'wrong') playAudio('cubalagi.mp3');
            if (type === 'win') playAudio('tahniah.mp3');
        }

        function startGame() {
            document.getElementById('start-overlay').style.display = 'none';
            document.getElementById('win-overlay').classList.add('hidden'); // Sembunyikan jika restart

            shuffledData = [...gameData].sort(() => Math.random() - 0.5);
            currentIndex = 0;

            // Reset Variable Markah
            correctAnswersCount = 0;
            currentScore = 0;
            scoreDisplay.innerText = currentScore;

            loadWord();
        }

        function restartGame() {
            startGame();
        }

        function loadWord() {
            if (currentIndex >= gameData.length) {
                endGame();
                return;
            }

            const data = shuffledData[currentIndex];
            document.getElementById('card-img').src = data.img;
            document.getElementById('card-word').innerText = data.word;
            roundDisplay.innerText = `${currentIndex + 1}/${gameData.length}`;

            // Reset styles completely
            card.style.transition = 'none'; // No animation while jumping back
            card.style.position = 'relative';
            card.style.left = '0px';
            card.style.top = '0px';
            card.style.transform = 'scale(1)';

            // Play audio
            setTimeout(() => playWordAudio(), 500);
        }


        // --- KAWALAN DRAG & DROP CUSTOM ---
        card.addEventListener('pointerdown', startDrag);
        document.addEventListener('pointermove', drag);
        document.addEventListener('pointerup', endDrag);

        function startDrag(e) {
            isDragging = true;

            const rect = card.getBoundingClientRect();
            containerRect = fullscreenContainer.getBoundingClientRect();

            startX = e.clientX - rect.left;
            startY = e.clientY - rect.top;

            card.style.transition = 'none';
            card.style.position = 'absolute';
            card.style.zIndex = '1000';
            card.style.transform = 'scale(1.1)';

            card.style.left = (rect.left - containerRect.left) + 'px';
            card.style.top = (rect.top - containerRect.top) + 'px';

            fullscreenContainer.appendChild(card);
        }

        function drag(e) {
            if (!isDragging) return;
            e.preventDefault();

            const xInsideContainer = e.clientX - containerRect.left;
            const yInsideContainer = e.clientY - containerRect.top;

            card.style.left = (xInsideContainer - startX) + 'px';
            card.style.top = (yInsideContainer - startY) + 'px';

            checkHoverOverTree(e.clientX, e.clientY);
        }

        function endDrag(e) {
            if (!isDragging) return;
            isDragging = false;

            card.style.transform = 'scale(1)';
            card.style.transition = 'all 0.3s ease';

            const dropTree = getTreeUnderPointer(e.clientX, e.clientY);
            trees.forEach(t => t.classList.remove('drag-over'));

            if (dropTree) {
                checkAnswer(dropTree.dataset.type, dropTree);
            } else {
                returnCardToCenter();
            }
        }

        function getTreeUnderPointer(x, y) {
            let found = null;
            trees.forEach(tree => {
                const rect = tree.getBoundingClientRect();
                if (x >= rect.left && x <= rect.right && y >= rect.top && y <= rect.bottom) found = tree;
            });
            return found;
        }

        function checkHoverOverTree(x, y) {
            trees.forEach(tree => {
                const rect = tree.getBoundingClientRect();
                if (x >= rect.left && x <= rect.right && y >= rect.top && y <= rect.bottom) {
                    tree.classList.add('drag-over');
                } else {
                    tree.classList.remove('drag-over');
                }
            });
        }

        function returnCardToCenter() {
            const placeholder = document.getElementById('card-placeholder');
            placeholder.appendChild(card);

            card.style.position = 'absolute';
            card.style.left = '0px';
            card.style.top = '0px';
        }

        // 1. ADD THIS at the top of your script block (if not already there)
        let sessionMistakes = {};

        function checkAnswer(selectedType, treeElement) {
            // Prevent multiple triggers while processing the current card
            if (isDragging) return;
            isDragging = false;

            const currentWordData = shuffledData[currentIndex];

            if (selectedType === currentWordData.type) {
                // --- CASE: BETUL (Correct) ---
                correctAnswersCount++;
                currentScore = correctAnswersCount * 2;
                scoreDisplay.innerText = currentScore;

                playFeedback('correct');
                createSparkle(treeElement);
            } else {
                playFeedback('wrong');
                card.classList.add('shake');

                let mistakeKey = `${currentWordData.word}_${selectedType}`;
                sessionMistakes[mistakeKey] = (sessionMistakes[mistakeKey] || 0) + 1;
                treeElement.classList.add('bg-red-100');
                setTimeout(() => treeElement.classList.remove('bg-red-100'), 500);
            }

            // --- SHARED LOGIC: MOVE TO NEXT WORD REGARDLESS OF RIGHT/WRONG ---
            // This timer ensures the child sees the shake/sparkle before the card resets
            setTimeout(() => {
                card.classList.remove('shake');

                // CRITICAL: Move to the next index even if they failed
                currentIndex++;

                // Reset card position and show the next word
                returnCardToCenter();
                loadWord();
            }, 1000);
        }

        function createSparkle(parent) {
            const sparkle = document.createElement('div');
            sparkle.classList.add('sparkle-effect');
            // Menukar Emoji kepada ikon FontAwesome
            sparkle.innerHTML = '<i class="fa-solid fa-star text-yellow-400 drop-shadow-md"></i>';
            const rect = parent.getBoundingClientRect();
            sparkle.style.left = (rect.left + rect.width / 2 - 30) + 'px';
            sparkle.style.top = (rect.top + rect.height / 2 - 30) + 'px';
            document.body.appendChild(sparkle);
            setTimeout(() => sparkle.remove(), 800);
        }

        // Render Bintang dalam Win Overlay
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

        // FUNGSI INI DITAMBAH DAN DIKEMASKINI SUPAYA PAPAR MARKAH DAN BINTANG
        function endGame() {
            // Paparkan markah
            document.getElementById('final-score').innerText = currentScore;

            // Kira dan paparkan bintang
            const earnedStars = calculateStars();
            renderStars(earnedStars);

            document.getElementById('win-overlay').classList.remove('hidden');
            playFeedback('win');
        }

        // --- SIMPAN SKOR ---
        function calculateStars() {
            // Berdasarkan correctAnswersCount (jumlah maksima: 6)
            return correctAnswersCount >= 6 ? 3 : (correctAnswersCount >= 4 ? 2 : 1);
        }

        function saveAndExit(event) {
            const btn = event.target;
            btn.disabled = true;
            btn.innerText = "Menyimpan...";

            fetch(window.gameConfig.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    child_id: window.gameConfig.childId,
                    game_level_id: window.gameConfig.levelId,
                    score: currentScore, // Markah penuh = 12 (6 soalan x 2 markah)
                    stars_earned: calculateStars(),
                    is_completed: true,
                    mistakes: sessionMistakes
                })
            }).then(r => r.json()).then(data => {
                if (data.success) window.location.href = window.gameConfig.nextLevelUrl;
                else {
                    alert("Ralat: " + data.message);
                    btn.disabled = false;
                    btn.innerHTML = 'Tahap Seterusnya <i class="fa-solid fa-forward-step"></i>';
                }
            }).catch(e => {
                alert("Ralat rangkaian.");
                btn.disabled = false;
                btn.innerHTML = 'Tahap Seterusnya <i class="fa-solid fa-forward-step"></i>';
            });
        }
    </script>
@endsection
