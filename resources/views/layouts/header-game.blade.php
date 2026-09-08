@php
    $activeChild = \App\Models\Child::find(session('active_child_id'));
    $totalPoints = $activeChild ? $activeChild->total_point : 0;

    $child = \App\Models\Child::find(session('active_child_id'));

    $currentAvatar = DB::table('avatars')->where('id', $child->avatar_id)->first();

    $avatarPath = $currentAvatar ? asset($currentAvatar->image) : asset('images/default_avatar.png');
@endphp

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
</style>

<header class="w-full max-w-7xl mx-auto px-3 sm:px-6 py-3 sm:py-4 flex justify-between items-center z-50 relative rounded-b-[2rem] shadow-md mb-4 gap-2">
    
    <div class="flex items-center gap-2 sm:gap-3">
        @if (!request()->routeIs('games.index'))
            <a href="{{ url()->previous() }}" class="btn-bubbly btn-orange px-3 py-2 sm:px-6 sm:py-3 flex items-center gap-2">
                <i class="fa fa-arrow-left text-base sm:text-xl" aria-hidden="true"></i>
                <span class="hidden sm:inline text-lg font-black tracking-wide">Kembali</span>
            </a>

            <a href="{{ route('games.index') }}" wire:navigate class="btn-bubbly btn-circle-green shadow-lg !w-10 !h-10 sm:!w-[56px] sm:!h-[56px]" title="Laman Utama">
                <i class="fa fa-home text-base sm:text-2xl" aria-hidden="true"></i>
            </a>
        @endif
    </div>

    <div class="flex items-center gap-2 sm:gap-4">

        <a href="{{ route('leaderboard.index') }}"
            class="w-10 h-10 sm:w-14 sm:h-14 bg-yellow-400 text-yellow-900 rounded-full flex items-center justify-center text-lg sm:text-2xl border-2 border-b-[4px] sm:border-b-[6px] border-yellow-600 hover:bg-yellow-300 hover:-translate-y-1 active:border-b-2 active:translate-y-[4px] transition-all cursor-pointer shadow-sm"
            title="Lihat Carta Juara">
            <i class="fa-solid fa-trophy drop-shadow-sm"></i>
        </a>

        <div class="flex items-center bg-yellow-50 border-2 border-b-[4px] sm:border-b-[6px] border-yellow-400 rounded-full h-10 sm:h-14 shadow-sm px-3 sm:px-5 transition-all">
            <div class="flex flex-col items-start leading-none">
                <span class="hidden sm:block text-[10px] font-black text-yellow-700 uppercase tracking-widest">Poin</span>
                <span class="text-sm sm:text-xl font-black text-yellow-600 tracking-wider flex items-center gap-1">
                    <i class="fa-solid fa-coins sm:hidden text-yellow-500"></i> {{ $totalPoints }}
                </span>
            </div>
        </div>

        <div class="relative" id="avatar-container">
            <button id="avatarBtn"
                class="w-12 h-12 sm:w-16 sm:h-16 rounded-full border-2 sm:border-4 border-white shadow-xl overflow-hidden btn-bubbly ring-2 sm:ring-4 ring-green-400 focus:outline-none transition-transform hover:scale-110 active:scale-95 bg-slate-100">
                <img src="{{ $avatarPath }}" alt="Avatar" class="w-full h-full object-contain scale-110 mt-1">
            </button>

            <div id="avatarDropdown"
                class="absolute right-0 mt-3 sm:mt-4 w-56 sm:w-64 bg-white rounded-[2rem] shadow-2xl border-4 border-b-8 border-green-400 overflow-hidden z-[60] transform scale-0 opacity-0 transition-all duration-300 origin-top-right">

                <div class="px-4 sm:px-5 py-3 sm:py-4 border-b-4 border-green-100 bg-green-50 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-green-300 shadow-inner bg-slate-100 flex items-center justify-center flex-shrink-0">
                        <img src="{{ $avatarPath }}" alt="Avatar" class="w-8 h-8 object-contain">
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-base sm:text-lg text-green-700 font-black leading-none truncate">
                            {{ $activeChild->name ?? 'Kawan' }}
                        </p>
                    </div>
                </div>

                <div class="py-2">
                    <a href="{{ route('avatar.edit') }}"
                        class="flex items-center gap-3 px-4 sm:px-5 py-3 text-gray-600 hover:bg-green-100 hover:text-green-700 font-black transition-colors text-base sm:text-lg">
                        <div class="w-8 flex justify-center"><i class="fa-solid fa-user-astronaut text-xl sm:text-2xl text-green-500"></i></div>
                        Tukar Avatar
                    </a>

                    <a href="{{ route('games.levels') }}"
                        class="flex items-center gap-3 px-4 sm:px-5 py-3 text-gray-600 hover:bg-orange-100 hover:text-orange-700 font-black transition-colors text-base sm:text-lg">
                        <div class="w-8 flex justify-center"><i class="fas fa-map text-xl sm:text-2xl text-orange-400"></i></div>
                        Peta Tahap
                    </a>

                    <div class="h-1 w-full bg-gray-100 my-1"></div>

                    <a href="{{ route('dashboard') }}"
                        class="w-full flex items-center gap-3 px-4 sm:px-5 py-3 text-gray-500 hover:bg-gray-100 hover:text-gray-700 font-black transition-colors text-base sm:text-lg text-left">
                        <div class="w-8 flex justify-center"><i class="fa-solid fa-door-open text-xl sm:text-2xl"></i></div>
                        Keluar
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const avatarBtn = document.getElementById('avatarBtn');
        const avatarDropdown = document.getElementById('avatarDropdown');

        // Toggle dropdown on avatar click
        avatarBtn.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent click from bubbling to document

            // Toggle scale and opacity classes for animation
            if (avatarDropdown.classList.contains('scale-0')) {
                avatarDropdown.classList.remove('scale-0', 'opacity-0');
                avatarDropdown.classList.add('scale-100', 'opacity-100');
            } else {
                avatarDropdown.classList.remove('scale-100', 'opacity-100');
                avatarDropdown.classList.add('scale-0', 'opacity-0');
            }
        });

        // Close dropdown when clicking anywhere else on the screen
        document.addEventListener('click', function(event) {
            if (!avatarBtn.contains(event.target) && !avatarDropdown.contains(event.target)) {
                avatarDropdown.classList.remove('scale-100', 'opacity-100');
                avatarDropdown.classList.add('scale-0', 'opacity-0');
            }
        });
    });
</script>