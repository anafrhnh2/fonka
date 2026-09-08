<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Avatar Saya</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
     @font-face {
        font-family: 'KG Red Hands';
        src: url('{{ asset('fonts/KGRedHands.ttf') }}') format('truetype');
        font-weight: normal;
            font-style: normal;
        }

        *:not(.fa):not(.fas):not(.far):not(.fal):not(.fab):not(.fa-solid):not(.fa-regular):not(.fa-brands) {
            font-family: 'KG Red Hands', sans-serif !important;
            user-select: none !important;
                letter-spacing: 1.7px;
            -webkit-user-select: none !important;
            -ms-user-select: none !important;
        }

        body {
            background-image: url('{{ asset('images/game-bg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-color: #E0F2FE;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* Gamified Buttons */

        .btn-bubbly {
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: 0 6px 0 rgba(0, 0, 0, 0.15);
            position: relative;
            top: 0;
        }

        .btn-bubbly:hover {
            transform: scale(1.05);
        }

        .btn-bubbly:active {
            top: 4px;
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.15);
        }

        .btn-nature {
            background-color: #4caf50;
            color: white;
            border-bottom: 5px solid #2e7d32;
        }

        .btn-danger {
            background-color: #ff5252;
            color: white;
            border-bottom: 5px solid #c62828;
        }

        .btn-audio {
            background-color: #4fc3f7;
            color: white;
            border-bottom: 5px solid #0288d1;
        }

        .btn-orange {
            background-color: #ff9800;
            color: white;
            border-bottom: 5px solid #e65100;
        }

        .btn-orange i,
        .btn-orange span {
            color: white;
        }

        .btn-circle-green {
            background-color: #4caf50;
            color: white;
            border-bottom: 5px solid #2e7d32;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .dropdown-menu {
            transition: all 0.3s ease;
            transform-origin: top right;
        }

        .hidden-dropdown {
            opacity: 0;
            transform: scale(0.95);
            pointer-events: none;
        }
        /* Avatar Card States */
        .avatar-card {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }

        .avatar-card:hover {
            transform: translateY(-8px) scale(1.05);
        }

        /* Selected State */
        .avatar-card.selected {
            border-color: #FBBF24 !important; /* Yellow-400 */
            background-color: #FFFBEB !important;
            box-shadow: 0 0 20px 5px rgba(251, 191, 36, 0.6);
            transform: scale(1.1);
            animation: pulse-glow 2s infinite;
        }

        .avatar-card.selected img {
            transform: scale(1.15);
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 15px rgba(251, 191, 36, 0.5); }
            50% { box-shadow: 0 0 25px 8px rgba(251, 191, 36, 0.8); }
        }

        /* Sparkle effect */
        .sparkle-container {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 50;
            display: none;
        }
        .sparkle-container.active { display: block; animation: fade-out 1s forwards; }
        @keyframes fade-out { 0% { opacity: 1; } 100% { opacity: 0; } }

        /* Pop animation for success/error modal */
        .animate-pop {
            animation: pop-in 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        @keyframes pop-in {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Hide scrollbar for neatness */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="min-h-screen flex flex-col no-scrollbar">

    @include('layouts.header-game')

    <div class="flex-grow w-full flex flex-col items-center py-6 px-4">

        {{-- Top Stats Bar --}}
        <div class="w-full max-w-5xl bg-white border-b-8 border-gray-200 rounded-[2rem] shadow-xl p-4 mb-8 flex flex-wrap md:flex-nowrap items-center justify-between gap-4 z-20">
            
            <div class="flex items-center gap-3 bg-yellow-50 px-5 py-2 rounded-2xl border-4 border-yellow-200">
                <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center border-2 border-yellow-500 shadow-inner">
                    <i class="fa-solid fa-coins text-2xl text-white drop-shadow-md"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-yellow-600 uppercase tracking-wider mb-0">Jumlah poin</p>
                    <h3 class="text-2xl font-black text-yellow-500 leading-none">{{ $userPoints }}</h3>
                </div>
            </div>

            <div class="flex items-center gap-3 bg-blue-50 px-5 py-2 rounded-2xl border-4 border-blue-200">
                <div class="w-12 h-12 bg-blue-400 rounded-full flex items-center justify-center border-2 border-blue-500 shadow-inner">
                    <i class="fa-solid fa-user-astronaut text-2xl text-white drop-shadow-md"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-0">Koleksi</p>
                    <h3 class="text-2xl font-black text-blue-500 leading-none">{{ count($ownedAvatars) }} / {{ count($ownedAvatars) + count($shopAvatars) }}</h3>
                </div>
            </div>

            <div class="flex items-center gap-3 bg-green-50 px-5 py-2 rounded-2xl border-4 border-green-200">
                <p class="text-sm font-bold text-green-600 uppercase tracking-wider text-right">{{ $activeChild->name ?? 'Kawan' }}<br></p>
                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center border-4 border-green-400 overflow-hidden shadow-sm">
                    <img src="{{ asset(collect($ownedAvatars)->firstWhere('id', $currentAvatarId)['path'] ?? 'images/default_avatar.png') }}" alt="Current" class="w-full h-full object-cover">
                </div>
            </div>

        </div>

        {{-- Main Collection/Shop Area --}}
        <div class="w-full max-w-5xl bg-[#4A90E2] border-4 border-[#3B73B5] rounded-[3rem] shadow-2xl p-6 md:p-10 relative z-10">
            
            {{-- Owned Avatars Form --}}
            <form action="{{ route('avatar.update') }}" method="POST" id="selectAvatarForm">
                @csrf
                <input type="hidden" name="selected_avatar" id="selectedAvatarInput" value="{{ $currentAvatarId }}">

                <div class="flex justify-between items-end mb-6 border-b-4 border-blue-300 pb-2">
                    <h2 class="text-3xl font-black text-white tracking-widest drop-shadow-md" style="-webkit-text-stroke: 1px #1e3a8a;">
                        <i class="fa-solid fa-backpack text-yellow-300 mr-2"></i> Avatar Saya
                    </h2>
                    <button type="submit" id="saveButton" disabled class="btn-bubbly bg-[#6EE744] border-[#4ca82b] text-white px-8 py-2 text-lg opacity-50 cursor-not-allowed">
                        Simpan
                    </button>
                </div>

                <div class="grid grid-cols-3 md:grid-cols-6 gap-4 md:gap-6 w-full mb-10">
                    @forelse($ownedAvatars as $avatar)
                        <div class="avatar-card cursor-pointer flex flex-col items-center bg-white border-4 border-gray-200 rounded-[1.5rem] p-3 aspect-square relative {{ $currentAvatarId == $avatar['id'] ? 'selected' : '' }}" 
                             data-id="{{ $avatar['id'] }}" data-type="owned">
                            
                            <div class="checkmark-icon absolute -top-3 -right-3 w-8 h-8 bg-green-500 border-2 border-white rounded-full flex items-center justify-center shadow-md {{ $currentAvatarId == $avatar['id'] ? 'block' : 'hidden' }} z-10">
                                <i class="fa-solid fa-check text-white text-sm"></i>
                            </div>

                            <img src="{{ asset($avatar['path']) }}" alt="{{ $avatar['name'] }}" class="w-full h-full object-contain transition-transform duration-300 pointer-events-none">
                            
                            <div class="absolute bottom-[-10px] bg-blue-500 text-white text-[10px] font-bold px-3 py-1 rounded-full border-2 border-white shadow-sm whitespace-nowrap">
                                {{ strtoupper($avatar['name']) }}
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-white/80 font-bold py-4">
                            You don't own any avatars yet!
                        </div>
                    @endforelse
                </div>
            </form>

            {{-- Shop Section --}}
            <div class="mb-6 border-b-4 border-blue-300 pb-2 mt-8">
                <h2 class="text-3xl font-black text-white tracking-widest drop-shadow-md" style="-webkit-text-stroke: 1px #1e3a8a;">
                    <i class="fa-solid fa-store text-yellow-300 mr-2"></i> Beli Avatar
                </h2>
            </div>

            <div class="grid grid-cols-3 md:grid-cols-6 gap-4 md:gap-6 w-full">
                @forelse($shopAvatars as $avatar)
                    {{-- Removed overflow-hidden so the absolute elements can overlap properly --}}
                    <div class="group flex flex-col items-center bg-slate-200 border-4 border-slate-300 rounded-[1.5rem] p-3 relative">
                        
                        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center z-10 rounded-[1.2rem] transition-opacity pointer-events-none">
                            <i class="fa-solid fa-lock text-4xl text-white drop-shadow-lg mb-6"></i>
                        </div>

                        {{-- Added pb-5 to push the image up slightly avoiding the buy button block --}}
                        <img src="{{ asset($avatar['path']) }}" alt="{{ $avatar['name'] }}" class="w-full h-full object-contain grayscale opacity-60 pb-5">

                        <div class="absolute bottom-3 left-0 right-0 px-2 z-20 flex justify-center">
                            <button class="btn-bubbly btn-buy w-full bg-yellow-400 border-yellow-600 text-yellow-900 text-[11px] py-1.5 px-1 shadow-md hover:bg-yellow-300"
                                    data-id="{{ $avatar['id'] }}" 
                                    data-name="{{ $avatar['name'] }}" 
                                    data-price="{{ $avatar['price'] }}"
                                    data-image="{{ asset($avatar['path']) }}">
                                BELI <i class="fa-solid fa-coins mx-1 text-yellow-700"></i> {{ $avatar['price'] }}
                            </button>
                        </div>

                        {{-- NEW NAME TAG FOR SHOP ITEMS --}}
                        <div class="absolute bottom-[-10px] bg-slate-600 text-white text-[10px] font-bold px-3 py-1 rounded-full border-2 border-white shadow-sm whitespace-nowrap z-30">
                            {{ strtoupper($avatar['name']) }}
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-white/80 font-bold py-4">
                        You've unlocked everything in the shop!
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- Purchase Modal --}}
    <div id="purchaseModal" class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center backdrop-blur-sm px-4">
        <div class="bg-white w-full max-w-sm rounded-[3rem] border-8 border-yellow-400 p-8 flex flex-col items-center text-center shadow-2xl transform scale-95 transition-transform duration-300">
            
            <div class="w-24 h-24 bg-blue-100 rounded-full border-4 border-blue-400 p-2 mb-4 -mt-16 shadow-lg relative bg-white">
                <img id="modalAvatarImg" src="" alt="Avatar" class="w-full h-full object-contain">
                <div class="sparkle-container text-yellow-400 text-2xl absolute -top-4 -right-4"><i class="fa-solid fa-sparkles"></i></div>
            </div>

            <h3 class="text-2xl font-black text-gray-800 mb-2">Beli Avatar ini?</h3>
            <p class="text-gray-500 font-bold mb-6">Awak nak beli <span id="modalAvatarName" class="text-blue-500">Nama</span> </p>
            
            <div class="flex items-center justify-center gap-2 bg-yellow-100 rounded-xl px-6 py-3 border-2 border-yellow-300 mb-8">
                <i class="fa-solid fa-coins text-3xl text-yellow-500 drop-shadow-sm"></i>
                <span id="modalAvatarPrice" class="text-3xl font-black text-yellow-600">0</span>
            </div>

            <form action="{{ route('avatar.purchase') }}" method="POST" class="w-full flex gap-4">
                @csrf
                <input type="hidden" name="avatar_id" id="modalAvatarId">
                
                <button type="button" id="closeModalBtn" class="flex-1 btn-bubbly bg-red-400 border-red-600 text-white py-3">
                    Tidak
                </button>
                <button type="submit" class="flex-1 btn-bubbly bg-green-400 border-green-600 text-white py-3 text-lg">
                    Beli
                </button>
            </form>
        </div>
    </div>

    {{-- INSUFFICIENT POINTS MODAL (NEW) --}}
   <div id="insufficientPointsModal" class="fixed inset-0 bg-black/60 z-[60] hidden flex items-center justify-center backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-sm rounded-[3rem] border-8 border-red-400 p-8 flex flex-col items-center text-center shadow-2xl animate-pop">
        
        <div class="w-24 h-24 bg-red-100 rounded-full border-4 border-red-500 p-2 mb-4 -mt-16 shadow-lg flex items-center justify-center">
            <i class="fa-solid fa-triangle-exclamation text-5xl text-red-500"></i>
        </div>

        <h3 class="text-3xl font-black text-red-500 mb-2">
            Alamak !
        </h3>

        <p class="text-gray-700 font-bold mb-2 text-lg leading-relaxed">
             Point tidak cukup
        </p>
        <p class="text-gray-600 font-semibold mb-6 text-base">
            Main lagi untuk kumpul point 
        </p>

        <button type="button" id="closeErrorBtn" class="w-full btn-bubbly bg-red-500 border-red-700 text-white py-3 text-xl tracking-widest shadow-md">
            OK 
        </button>
    </div>
</div>

    {{-- SUCCESS POPUP MODAL --}}
    @if(session('success'))
    <div id="successModal" class="fixed inset-0 bg-black/60 z-[60] flex items-center justify-center backdrop-blur-sm px-4">
        <div class="bg-white w-full max-w-sm rounded-[3rem] border-8 border-green-400 p-8 flex flex-col items-center text-center shadow-2xl animate-pop">
            
            <div class="w-24 h-24 bg-green-100 rounded-full border-4 border-green-500 p-2 mb-4 -mt-16 shadow-lg relative flex items-center justify-center bg-white">
                <i class="fa-solid fa-check text-5xl text-green-500"></i>
                <div class="sparkle-container active text-green-400 text-3xl absolute -top-2 -right-4"><i class="fa-solid fa-sparkles"></i></div>
            </div>

            <h3 class="text-3xl font-black text-green-500 mb-2 drop-shadow-sm">SUCCESS</h3>
            <p class="text-gray-600 font-bold mb-8 text-lg">{{ session('success') }}</p>
            
            <button type="button" id="closeSuccessBtn" class="w-full btn-bubbly bg-green-500 border-green-700 text-white py-3 text-xl tracking-widest shadow-md">
                Okay
            </button>
        </div>
    </div>
    @endif

    {{-- BACKEND ERROR POPUP MODAL (FALLBACK) --}}
    @if(session('error'))
    <div id="backendErrorModal" class="fixed inset-0 bg-black/60 z-[60] flex items-center justify-center backdrop-blur-sm px-4">
        <div class="bg-white w-full max-w-sm rounded-[3rem] border-8 border-red-400 p-8 flex flex-col items-center text-center shadow-2xl animate-pop">
            <div class="w-24 h-24 bg-red-100 rounded-full border-4 border-red-500 p-2 mb-4 -mt-16 shadow-lg relative flex items-center justify-center bg-white">
                <i class="fa-solid fa-triangle-exclamation text-5xl text-red-500"></i>
            </div>
            <h3 class="text-3xl font-black text-red-500 mb-2 drop-shadow-sm">ERROR</h3>
            <p class="text-gray-600 font-bold mb-8 text-lg">{{ session('error') }}</p>
            <button type="button" id="closeBackendErrorBtn" class="w-full btn-bubbly bg-red-500 border-red-700 text-white py-3 text-xl tracking-widest shadow-md">
                OKAY
            </button>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Store User Points from Laravel variable
            const userPoints = parseInt('{{ $userPoints ?? 0 }}');

            // --- SELECTION LOGIC (OWNED AVATARS) ---
            const ownedCards = document.querySelectorAll('.avatar-card[data-type="owned"]');
            const hiddenInput = document.getElementById('selectedAvatarInput');
            const saveButton = document.getElementById('saveButton');

            ownedCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Reset all
                    ownedCards.forEach(c => {
                        c.classList.remove('selected');
                        c.querySelector('.checkmark-icon').classList.add('hidden');
                    });
                    
                    // Set new selection
                    this.classList.add('selected');
                    this.querySelector('.checkmark-icon').classList.remove('hidden');
                    
                    // Update form values
                    const avatarId = this.getAttribute('data-id');
                    hiddenInput.value = avatarId;
                    
                    // Enable save button with bounce
                    saveButton.disabled = false;
                    saveButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    saveButton.classList.add('animate-bounce');
                    
                    // Remove bounce after 1 second so it doesn't get annoying
                    setTimeout(() => saveButton.classList.remove('animate-bounce'), 1000);
                });
            });

            // --- MODAL LOGIC (SHOP AVATARS) ---
            const purchaseModal = document.getElementById('purchaseModal');
            const insufficientModal = document.getElementById('insufficientPointsModal');
            const buyButtons = document.querySelectorAll('.btn-buy');
            
            // Purchase Modal Elements
            const closeModalBtn = document.getElementById('closeModalBtn');
            const mImage = document.getElementById('modalAvatarImg');
            const mName = document.getElementById('modalAvatarName');
            const mPrice = document.getElementById('modalAvatarPrice');
            const mId = document.getElementById('modalAvatarId');

            buyButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault(); 
                    
                    const price = parseInt(this.getAttribute('data-price'));

                    // CHECK POINTS BEFORE OPENING MODAL
                    if (userPoints < price) {
                        // User doesn't have enough points - show error modal
                        insufficientModal.classList.remove('hidden');
                    } else {
                        // User has enough points - show purchase confirmation modal
                        mId.value = this.getAttribute('data-id');
                        mName.textContent = this.getAttribute('data-name');
                        mPrice.textContent = price;
                        mImage.src = this.getAttribute('data-image');
                        
                        purchaseModal.classList.remove('hidden');
                        setTimeout(() => {
                            purchaseModal.firstElementChild.classList.remove('scale-95');
                            purchaseModal.firstElementChild.classList.add('scale-100');
                        }, 10);
                    }
                });
            });

            // Close Purchase Modal
            closeModalBtn.addEventListener('click', function() {
                purchaseModal.firstElementChild.classList.remove('scale-100');
                purchaseModal.firstElementChild.classList.add('scale-95');
                setTimeout(() => purchaseModal.classList.add('hidden'), 200);
            });

            // Close purchase modal on outside click
            purchaseModal.addEventListener('click', function(e) {
                if(e.target === purchaseModal) closeModalBtn.click();
            });

            // --- INSUFFICIENT POINTS MODAL LOGIC ---
            const closeErrorBtn = document.getElementById('closeErrorBtn');
            if(closeErrorBtn) {
                closeErrorBtn.addEventListener('click', function() {
                    insufficientModal.classList.add('hidden');
                });
                
                insufficientModal.addEventListener('click', function(e) {
                    if(e.target === insufficientModal) closeErrorBtn.click();
                });
            }

            // --- SUCCESS MODAL LOGIC ---
            const successModal = document.getElementById('successModal');
            const closeSuccessBtn = document.getElementById('closeSuccessBtn');

            if (successModal && closeSuccessBtn) {
                closeSuccessBtn.addEventListener('click', function() {
                    successModal.classList.add('hidden');
                });
                
                successModal.addEventListener('click', function(e) {
                    if(e.target === successModal) closeSuccessBtn.click();
                });
            }

            // --- BACKEND ERROR MODAL LOGIC ---
            const backendErrorModal = document.getElementById('backendErrorModal');
            const closeBackendErrorBtn = document.getElementById('closeBackendErrorBtn');

            if (backendErrorModal && closeBackendErrorBtn) {
                closeBackendErrorBtn.addEventListener('click', function() {
                    backendErrorModal.classList.add('hidden');
                });
                
                backendErrorModal.addEventListener('click', function(e) {
                    if(e.target === backendErrorModal) closeBackendErrorBtn.click();
                });
            }
        });
    </script>
</body>

</html>