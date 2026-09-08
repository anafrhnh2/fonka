@extends('layouts.game')

@section('game-title', 'Tahap 1: Jejak Huruf A-Z')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800;900&display=swap" rel="stylesheet">

    <style>
        .font-lexend {
            font-family: 'Lexend', sans-serif;
        }

        .touch-none {
            touch-action: none;
        }

        /* Custom Scrollbar untuk grid huruf */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #F1F5F9;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
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

        .btn-3d:active {
            transform: translateY(4px);
            border-bottom-width: 2px;
            margin-top: 4px;
        }

        .grid-btn {
            aspect-ratio: 1/1;
            width: 100%;
            border-radius: 1rem;
            font-size: 2rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            font-family: 'Lexend', sans-serif;
            border-bottom-width: 6px;
            border-top-width: 2px;
            border-left-width: 2px;
            border-right-width: 2px;
            border-style: solid;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }

        @media (min-width: 768px) {
            .grid-btn {
                font-size: 3rem;
                border-radius: 1.5rem;
            }
        }

        .grid-btn:hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .grid-btn:active {
            transform: scale(0.95);
        }

        /* Huruf yang dah disiapkan */
        .grid-btn.completed {
            background-color: #F1F5F9 !important;
            border-color: #CBD5E1 !important;
            color: #94A3B8 !important;
        }

        .badge-tick {
            position: absolute;
            top: 2px;
            right: 5px;
            font-size: 1.2rem;
            color: #10B981;
            filter: drop-shadow(0 2px 2px rgba(0, 0, 0, 0.1));
            animation: bounce-in 0.5s forwards;
        }

        @media (min-width: 768px) {
            .badge-tick {
                font-size: 1.8rem;
                top: 4px;
                right: 8px;
            }
        }

        /* Saiz Huruf Berjaya */
        #success-letter {
            font-size: 100px;
            line-height: 1;
        }

        @media (min-width: 768px) {
            #success-letter {
                font-size: 150px;
            }
        }

        .fullscreen-btn-mobile {
            z-index: 99999 !important;
            pointer-events: auto !important;
            touch-action: manipulation !important;
            -webkit-tap-highlight-color: transparent;
        }

        #game-fullscreen-container {
            isolation: auto;
        }

        .is-fullscreen #tracing-container {
            height: 450px;
        }

        .is-fullscreen #success-letter {
            font-size: 200px;
        }

        .is-fullscreen .grid-btn {
            font-size: 4rem;
        }

        @keyframes bounce-in {
            0% {
                transform: scale(0.8);
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
        window.gameConfig = {
            saveUrl: "{{ route('game.save') }}",
            childId: {{ session('active_child_id') ?? 'null' }},
            levelId: {{ $level->id ?? 1 }},
            nextLevelUrl: "{{ route('games.levels') }}",
            audioBaseUrl: "{{ asset('audio') }}"
        };
    </script>

    <div id="game-fullscreen-container"
        class="w-full h-full flex flex-col relative font-sans overflow-hidden bg-[#FDF8F5] transition-all duration-300">

        <div class="flex flex-wrap justify-between items-center mb-2 px-2 md:px-4 gap-y-3 shrink-0">

            <div class="flex items-center bg-sky-50 border-4 border-sky-200 rounded-full px-4 py-2 shadow-sm">
                <span class="text-sky-800 font-black text-sm md:text-xl uppercase tracking-wider font-lexend">
                    <i class="fa-solid fa-pencil mr-2"></i> Jejak Huruf A-Z
                </span>
            </div>

            <div class="flex items-center gap-2 md:gap-4 font-lexend">

                <div
                    class="flex flex-col items-center bg-green-50 border-4 border-green-200 rounded-2xl px-4 py-1 shadow-sm">
                    <span class="text-green-600 font-bold text-xs md:text-sm uppercase tracking-wider">Markah</span>
                    <div class="flex items-center gap-1">
                        <span id="score-display" class="text-green-700 font-black text-xl md:text-2xl">0</span>
                    </div>
                </div>

                <div
                    class="flex flex-col items-center bg-orange-50 border-4 border-orange-200 rounded-2xl px-4 py-1 shadow-sm">
                    <span class="text-orange-500 font-bold text-xs md:text-sm uppercase tracking-wider">Selesai</span>
                    <span id="round-display" class="text-orange-700 font-black text-xl md:text-2xl">0/26</span>
                </div>

                <button id="fullscreen-btn" onclick="toggleFullScreen(event)"
                    class="w-12 h-12 bg-white text-sky-500 rounded-xl flex items-center justify-center hover:bg-sky-50 active:scale-95 transition-all shadow-sm border-4 border-sky-200 ml-1 md:ml-2 touch-manipulation">
                    <i id="fullscreen-icon" class="fas fa-expand text-xl md:text-2xl pointer-events-none"></i>
                </button>

            </div>
        </div>

        <div id="game-board"
            class="flex-1 rounded-[1.5rem] md:rounded-[2.5rem] relative overflow-hidden shadow-inner border-4 border-slate-200 w-full flex flex-col items-center bg-[#F8FAFC] pt-4 pb-8">

            <div id="selection-screen"
                class="w-full flex flex-col items-center justify-start h-full z-10 overflow-y-auto px-2 pb-10 custom-scrollbar">
                <div
                    class="bg-white/90 px-6 py-3 rounded-full border-2 border-slate-200 shadow-sm backdrop-blur-sm mb-6 mt-2 sticky top-0 z-20">
                    <h2 class="text-xl md:text-4xl font-black text-slate-700 tracking-wide font-lexend text-center">
                        Pilih huruf untuk dilukis!</h2>
                </div><br>

                <div id="letter-grid" class="grid grid-cols-4 md:grid-cols-7 gap-3 md:gap-6 w-full max-w-4xl px-2">
                </div>
            </div>

            <div id="tracing-screen"
                class="hidden w-full flex-col items-center justify-center h-full z-10 transition-opacity duration-300">

                <div class="flex items-center justify-between w-full max-w-2xl px-4 mb-4 z-10">
                    <button onclick="backToMenu()"
                        class="w-12 h-12 bg-white text-slate-500 rounded-full border-4 border-slate-200 shadow-sm hover:bg-slate-50 active:scale-95 transition-all flex items-center justify-center"
                        title="Kembali ke Menu">
                        <i class="fa-solid fa-arrow-left text-lg"></i>
                    </button>

                    <div
                        class="bg-white/90 px-6 py-2 rounded-full border-2 border-slate-100 shadow-sm flex items-center gap-4">
                        <h2 id="instruction-text" class="text-lg md:text-2xl font-black text-slate-700 font-lexend">Ikut
                            garisan</h2>
                        <button onclick="playLetterAudio()"
                            class="w-10 h-10 bg-sky-400 text-white rounded-full flex items-center justify-center border-4 border-sky-200 shadow-[0_4px_0_#0284C7] active:translate-y-1 active:shadow-none transition-all">
                            <i class="fa-solid fa-volume-high"></i>
                        </button>
                    </div>
                </div>

                <div id="tracing-container"
                    class="relative bg-white rounded-[2rem] border-8 border-sky-100 shadow-xl flex flex-col items-center justify-center overflow-hidden z-10 w-[90%] max-w-2xl h-64 md:h-80">

                    <canvas id="bg-canvas" class="absolute top-0 left-0 w-full h-full pointer-events-none"></canvas>
                    <canvas id="draw-canvas"
                        class="absolute top-0 left-0 w-full h-full cursor-crosshair touch-none"></canvas>
                    <canvas id="hidden-canvas" class="hidden"></canvas>

                    <div id="success-layer"
                        class="absolute inset-0 bg-white flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-500 rounded-[1.5rem]">
                        <span id="success-letter" class="font-lexend font-black drop-shadow-md text-sky-400">Aa</span>
                    </div>

                </div>

                <div class="mt-8 h-16 w-full flex justify-center">
                    <button id="btn-next" onclick="backToMenu()"
                        class="hidden btn-3d bg-sky-500 border-sky-700 text-white px-10 py-4 text-2xl font-bold rounded-full font-lexend">
                        Pilih Huruf Lain 
                    </button>
                </div>

            </div>

        </div>
    </div>

    <div id="start-overlay"
        class="absolute inset-0 bg-slate-900/80 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">
        <div
            class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl text-center max-w-lg w-full flex flex-col border-[6px] border-sky-200 font-lexend">
            <h1 class="text-3xl md:text-4xl font-black text-sky-600 mb-6">Buku Rekod Huruf</h1>
            <div class="flex flex-col gap-5 text-left mx-auto w-full md:w-[90%] mb-10">
                <div class="flex items-center gap-4 bg-sky-50 p-4 rounded-xl border-2 border-sky-100 shadow-sm">
                    <div
                        class="w-12 h-12 bg-sky-200 text-sky-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <p class="text-slate-600 font-bold text-lg leading-tight">Pilih mana-mana huruf dari A hingga Z.</p>
                </div>
                <div class="flex items-center gap-4 bg-amber-50 p-4 rounded-xl border-2 border-amber-100 shadow-sm">
                    <div
                        class="w-12 h-12 bg-amber-200 text-amber-700 rounded-full flex items-center justify-center text-2xl shrink-0">
                        <i class="fa-solid fa-palette"></i>
                    </div>
                    <p class="text-slate-600 font-bold text-lg leading-tight">Lukis garisan huruf besar dan kecil.</p>
                </div>
            </div>
            <button onclick="startSelection()"
                class="btn-3d bg-sky-500 border-sky-700 text-white w-full text-2xl font-bold py-4 rounded-[1.5rem] flex justify-center items-center gap-3">
                <i class="fa-solid fa-play"></i> Buka Buku Rekod
            </button>
        </div>
    </div>

    <div id="win-overlay"
        class="hidden absolute inset-0 bg-slate-900/80 z-50 flex flex-col justify-center items-center backdrop-blur-md px-4 py-8">
        <div
            class="bg-white p-8 rounded-[2.5rem] shadow-2xl text-center max-w-md w-full animate-bounce-in border-[6px] border-yellow-300 font-lexend">
            <div class="text-7xl mb-4 text-green-500"><i class="fa-solid fa-circle-check"></i></div>
            <h1 class="text-4xl font-black text-sky-600 mb-2">Hebat!</h1>
            <p class="text-slate-500 font-bold mb-8 text-lg">Awak telah melengkapkan semua 26 huruf.</p>
            <button onclick="saveAndExit(event)"
                class="btn-3d bg-green-500 border-green-700 text-white w-full text-xl font-bold py-4 rounded-[1.5rem]">
                Simpan Markah ▶
            </button>
        </div>
    </div>

    <audio id="sfx-hover" src="https://assets.mixkit.com/sfx/preview/mixkit-pop-click-3104.mp3"></audio>

    <script>
        const fsBtn = document.getElementById('fullscreen-btn');
        const fsContainer = document.getElementById('game-fullscreen-container');
        const fullscreenIcon = document.getElementById('fullscreen-icon');

        function toggleFullScreen(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }

            const isFS = document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement;

            if (!isFS) {
                const requestFS = fsContainer.requestFullscreen || fsContainer.webkitRequestFullscreen || fsContainer
                    .mozRequestFullScreen || fsContainer.msRequestFullscreen;
                if (requestFS) {
                    requestFS.call(fsContainer).catch(err => {
                        console.log("Error full-screen:", err);
                    });
                }
                fullscreenIcon.classList.replace('fa-expand', 'fa-compress');
                fsContainer.classList.add('is-fullscreen');
            } else {
                const exitFS = document.exitFullscreen || document.webkitExitFullscreen || document.mozCancelFullScreen ||
                    document.msExitFullscreen;
                if (exitFS) {
                    exitFS.call(document);
                }
                fullscreenIcon.classList.replace('fa-compress', 'fa-expand');
                fsContainer.classList.remove('is-fullscreen');
            }

            if (!document.getElementById('tracing-screen').classList.contains('hidden')) {
                setTimeout(setupCanvas, 300);
            }
        }

        if (fsBtn) {
            fsBtn.addEventListener('touchend', function(e) {
                toggleFullScreen(e);
            }, {
                passive: false
            });
        }

        const alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");
        const colors = ['#F87171', '#F97316', '#F59E0B', '#10B981', '#06B6D4', '#3B82F6', '#8B5CF6', '#D946EF', '#F43F5E'];

        let letterData = alphabets.map((char, index) => {
            return {
                char: char,
                text: `${char}${char.toLowerCase()}`,
                color: colors[index % colors.length],
                completed: false
            };
        });

        const totalLetters = 26;
        let selectedIndex = -1;
        let isCompleted = false;

        // 🔥 PERBAIKAN UTAMA: Fungsi helper untuk memisahkan progress localStorage setiap anak
        function getStorageKey() {
            return 'game_level1_progress_child_' + window.gameConfig.childId;
        }

        function loadProgress() {
            const savedProgress = localStorage.getItem(getStorageKey()); // Guna dynamic key
            if (savedProgress) {
                const completedChars = JSON.parse(savedProgress);
                letterData.forEach(item => {
                    if (completedChars.includes(item.char)) {
                        item.completed = true;
                    }
                });
            }
        }

        function saveProgressLocal() {
            const completedChars = letterData.filter(item => item.completed).map(item => item.char);
            localStorage.setItem(getStorageKey(), JSON.stringify(completedChars)); // Guna dynamic key
        }

        const selectionScreen = document.getElementById('selection-screen');
        const tracingScreen = document.getElementById('tracing-screen');
        const letterGrid = document.getElementById('letter-grid');

        const container = document.getElementById('tracing-container');
        const bgCanvas = document.getElementById('bg-canvas');
        const drawCanvas = document.getElementById('draw-canvas');
        const hiddenCanvas = document.getElementById('hidden-canvas');

        const bgCtx = bgCanvas.getContext('2d');
        const drawCtx = drawCanvas.getContext('2d');
        const hiddenCtx = hiddenCanvas.getContext('2d', {
            willReadFrequently: true
        });

        const btnNext = document.getElementById('btn-next');
        const roundDisplay = document.getElementById('round-display');
        const scoreDisplay = document.getElementById('score-display');
        const successLayer = document.getElementById('success-layer');
        const successLetter = document.getElementById('success-letter');
        const instructionText = document.getElementById('instruction-text');

        document.addEventListener('DOMContentLoaded', () => {
            loadProgress();
            updateUI();
        });

        function playAudioFile(filename) {
            if (window.currentAudio) {
                window.currentAudio.pause();
                window.currentAudio.currentTime = 0;
            }
            const audio = new Audio(`${window.gameConfig.audioBaseUrl}/${filename}`);
            window.currentAudio = audio;
            audio.play().catch(e => {
                console.error("Audio error", e);
            });
        }

        function playLetterAudio() {
            playAudioFile(`${letterData[selectedIndex].char}.mp3`);
        }

        function startSelection() {
            document.getElementById('start-overlay').style.display = 'none';
            renderSelectionMenu();
        }

        function updateUI() {
            let completedCount = letterData.filter(d => d.completed).length;
            roundDisplay.innerText = `${completedCount}/${totalLetters}`;
            scoreDisplay.innerText = completedCount * 1;
            return completedCount;
        }

        function renderSelectionMenu() {
            letterGrid.innerHTML = '';
            let completedCount = updateUI();

            letterData.forEach((data, index) => {
                const btn = document.createElement('button');
                btn.className = `grid-btn ${data.completed ? 'completed' : ''}`;

                if (!data.completed) {
                    btn.style.backgroundColor = data.color;
                    btn.style.borderColor = adjustColorBrightness(data.color, -30);
                    btn.style.color = '#FFFFFF';
                }

                btn.innerHTML = data.text;

                if (data.completed) {
                    btn.innerHTML += `<div class="badge-tick"><i class="fa-solid fa-circle-check"></i></div>`;
                }

                btn.onclick = () => selectLetter(index);
                letterGrid.appendChild(btn);
            });

            if (completedCount === totalLetters) {
                setTimeout(showFullWin, 800);
            }
        }

        function adjustColorBrightness(color, amount) {
            let usePound = false;
            if (color[0] == "#") {
                color = color.slice(1);
                usePound = true;
            }
            let num = parseInt(color, 16);
            let r = Math.min(255, Math.max(0, (num >> 16) + amount));
            let b = Math.min(255, Math.max(0, ((num >> 8) & 0x00FF) + amount));
            let g = Math.min(255, Math.max(0, (num & 0x0000FF) + amount));
            return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16).padStart(6, '0');
        }

        function selectLetter(index) {
            selectedIndex = index;
            isCompleted = false;

            selectionScreen.classList.add('hidden');
            tracingScreen.classList.remove('hidden');
            tracingScreen.style.display = 'flex';

            successLayer.classList.replace('opacity-100', 'opacity-0');
            btnNext.classList.add('hidden');
            instructionText.innerText = `Jejak huruf ${letterData[index].char}`;
            instructionText.style.color = "#334155";

            setTimeout(() => {
                setupCanvas();
                playLetterAudio();
            }, 100);
        }

        function backToMenu() {
            tracingScreen.classList.add('hidden');
            tracingScreen.style.display = 'none';
            selectionScreen.classList.remove('hidden');
            renderSelectionMenu();
        }

        function setupCanvas() {
            const rect = container.getBoundingClientRect();
            const width = rect.width;
            const height = rect.height;

            [bgCanvas, drawCanvas, hiddenCanvas].forEach(c => {
                c.width = width;
                c.height = height;
            });

            drawCtx.lineCap = 'round';
            drawCtx.lineJoin = 'round';
            drawCtx.lineWidth = width * 0.07;

            const data = letterData[selectedIndex];
            const fontSize = Math.floor(height * 0.65);

            hiddenCtx.clearRect(0, 0, width, height);
            hiddenCtx.font = `800 ${fontSize}px "KG Red Hands"`;
            hiddenCtx.textAlign = "center";
            hiddenCtx.textBaseline = "middle";
            hiddenCtx.fillStyle = "#000000";
            hiddenCtx.fillText(data.text, width / 2, height / 2 + (fontSize * 0.05));

            drawCtx.clearRect(0, 0, width, height);
            drawCtx.strokeStyle = data.color;
            drawCtx.globalAlpha = 0.8;

            drawStaticGuide();
        }

        function drawStaticGuide() {
            const data = letterData[selectedIndex];
            const width = bgCanvas.width;
            const height = bgCanvas.height;
            const fontSize = Math.floor(height * 0.65);

            bgCtx.clearRect(0, 0, width, height);
            bgCtx.setLineDash([10, 10]);
            bgCtx.lineWidth = 2;
            bgCtx.strokeStyle = "#E2E8F0";

            [height * 0.25, height * 0.5, height * 0.75].forEach(y => {
                bgCtx.beginPath();
                bgCtx.moveTo(0, y);
                bgCtx.lineTo(width, y);
                bgCtx.stroke();
            });

            bgCtx.font = `${fontSize}px "KG Red Hands"`;
            bgCtx.textAlign = "center";
            bgCtx.textBaseline = "middle";
            bgCtx.setLineDash([15, 15]);
            bgCtx.strokeStyle = "#CBD5E1";
            bgCtx.lineWidth = 6;
            bgCtx.strokeText(data.text, width / 2, height / 2 + (fontSize * 0.05));
        }

        let isDrawing = false;

        function getPos(e) {
            const rect = drawCanvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return {
                x: clientX - rect.left,
                y: clientY - rect.top
            };
        }

        function startPosition(e) {
            if (isCompleted) return;
            isDrawing = true;
            const pos = getPos(e);
            drawCtx.beginPath();
            drawCtx.moveTo(pos.x, pos.y);
            drawCtx.lineTo(pos.x, pos.y);
            drawCtx.stroke();
            e.preventDefault();
        }

        function draw(e) {
            if (!isDrawing || isCompleted) return;
            const pos = getPos(e);
            drawCtx.lineTo(pos.x, pos.y);
            drawCtx.stroke();
            e.preventDefault();
        }

        function endPosition() {
            if (!isDrawing) return;
            isDrawing = false;
            drawCtx.beginPath();
            checkCompletion();
        }

        drawCanvas.addEventListener('mousedown', startPosition);
        drawCanvas.addEventListener('mousemove', draw);
        drawCanvas.addEventListener('mouseup', endPosition);
        drawCanvas.addEventListener('mouseleave', endPosition);
        drawCanvas.addEventListener('touchstart', startPosition, {
            passive: false
        });
        drawCanvas.addEventListener('touchmove', draw, {
            passive: false
        });
        drawCanvas.addEventListener('touchend', endPosition);

        function checkCompletion() {
            const width = hiddenCanvas.width;
            const height = hiddenCanvas.height;
            const targetData = hiddenCtx.getImageData(0, 0, width, height).data;
            const drawData = drawCtx.getImageData(0, 0, width, height).data;

            let targetPixels = 0,
                coveredPixels = 0;
            for (let i = 3; i < targetData.length; i += 16) {
                if (targetData[i] > 128) {
                    targetPixels++;
                    if (drawData[i] > 50) coveredPixels++;
                }
            }

            if ((targetPixels === 0 ? 0 : coveredPixels / targetPixels) > 0.75) handleSuccess();
        }

        function handleSuccess() {
            isCompleted = true;
            const data = letterData[selectedIndex];
            data.completed = true;
            saveProgressLocal();
            updateUI();

            instructionText.innerText = "Sempurna!";
            instructionText.style.color = data.color;
            successLetter.innerText = data.text;
            successLetter.style.color = data.color;
            successLayer.classList.replace('opacity-0', 'opacity-100');

            playAudioFile('bagus.mp3');
            createSparkle(container);

            setTimeout(() => {
                btnNext.classList.remove('hidden');
                playLetterAudio();
            }, 1000);
        }

        function createSparkle(parent) {
            const sparkle = document.createElement('div');
            sparkle.classList.add('sparkle-effect');
            sparkle.innerHTML = '<i class="fa-solid fa-circle-check text-green-500"></i>';
            const rect = parent.getBoundingClientRect();
            sparkle.style.left = (rect.width / 2 - 40) + 'px';
            sparkle.style.top = (rect.height / 2 - 40) + 'px';
            parent.appendChild(sparkle);
            setTimeout(() => sparkle.remove(), 800);
        }

        function showFullWin() {
            document.getElementById('win-overlay').classList.remove('hidden');
            playAudioFile('tahniah.mp3');
        }

        function saveAndExit(event, forcedScore = null) {
            if (event) event.preventDefault();
            const btn = event.target;
            btn.disabled = true;
            if (btn.innerText) btn.innerText = "Menyimpan...";

            let completedCount = forcedScore !== null ? forcedScore : letterData.filter(d => d.completed).length;
            let calculatedScore = completedCount * 1;
            let stars = completedCount >= 20 ? 3 : (completedCount >= 10 ? 2 : 1);

            fetch(window.gameConfig.saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    child_id: window.gameConfig.childId,
                    game_level_id: window.gameConfig.levelId,
                    score: calculatedScore,
                    stars_earned: stars,
                    is_completed: true
                })
            }).then(r => r.json()).then(data => {
                // 🔥 PERBAIKAN: Padam local storage menggunakan dynamic key yang betul
                localStorage.removeItem(getStorageKey());

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
