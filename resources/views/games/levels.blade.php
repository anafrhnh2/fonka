@extends('layouts.game')

{{-- Dynamic Title based on Game Type --}}
@section('game-title', 'Pilih Tahap ' . ucfirst($gameType) . ' 🚀')

@section('content')
    <div class="h-full w-full flex flex-col items-center justify-start overflow-y-auto no-scrollbar py-4">
        
        <div class="w-full max-w-4xl mb-8 flex justify-between items-end px-4">
            <div class="text-left">
                <h2 class="text-3xl font-bold text-indigo-600 mb-1">Jom Mula!</h2>
                <p class="text-gray-500 font-medium">Selesaikan tahap untuk buka kunci seterusnya.</p>
            </div>
            <div class="hidden md:block w-1/3">
                <div class="flex justify-between text-sm font-bold text-gray-400 mb-1">
                    <span>Tahap {{ $currentLevel }}</span>
                    <span>Max {{ $totalLevels }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4 border-2 border-gray-300">
                    <div class="bg-green-400 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ ($currentLevel / $totalLevels) * 100 }}%"></div>
                </div>
            </div>
        </div>

       <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 w-full max-w-5xl px-4 pb-20">
            
            @foreach($levels as $level)
                @php
                    // Check database progress
                    $levelProgress = $progress[$level->id] ?? null;
                    
                    // Logic: Unlocked if it's the current level OR already completed
                    $isUnlocked = $level->level_number <= $currentLevel;
                    $isCompleted = $levelProgress && $levelProgress->is_completed;
                    $isNext = $level->level_number == $currentLevel;
                    
                    // Get stars (default to 0 if no record)
                    $stars = $levelProgress ? $levelProgress->stars_earned : 0;
                @endphp

                <div class="relative group">
                    
                    @if($isUnlocked)
                        <a href="{{ route('games.activities', ['gameType' => $gameType, 'levelNumber' => $level->level_number]) }}" 
                           class="flex flex-col items-center justify-center w-full aspect-square rounded-3xl shadow-lg border-b-8 transition-all transform hover:scale-105 active:scale-95
                           {{ $isNext ? 'bg-yellow-100 border-yellow-300 hover:bg-yellow-200 animate-pulse-slow ring-4 ring-yellow-200' : 'bg-white border-blue-200 hover:border-blue-400' }}">
                            
                            @if($isCompleted)
                                <div class="absolute top-2 right-2 flex gap-0.5">
                                    @for($s=0; $s < $stars; $s++) 
                                        <span class="text-sm">⭐</span>
                                    @endfor
                                </div>
                            @endif

                            <span class="text-4xl md:text-5xl font-bold {{ $isNext ? 'text-yellow-600' : 'text-blue-500' }}">
                                {{ $level->level_number }}
                            </span>
                            
                            <span class="mt-2 text-xs md:text-sm font-bold uppercase tracking-wider {{ $isNext ? 'text-yellow-600' : 'text-gray-400' }}">
                                {{ $isNext ? 'Mula!' : 'Siap' }}
                            </span>
                        </a>
                    @else
                        <div class="flex flex-col items-center justify-center w-full aspect-square rounded-3xl bg-gray-100 border-4 border-gray-200 shadow-inner opacity-70 cursor-not-allowed">
                            <span class="text-4xl md:text-5xl opacity-50 grayscale">🔒</span>
                            <span class="mt-2 text-xs font-bold text-gray-400 uppercase">Kunci</span>
                        </div>
                    @endif

                    @if(!$loop->last)
                        <div class="hidden md:block absolute top-1/2 -right-4 w-4 h-2 bg-gray-200 rounded-full z-0 transform -translate-y-1/2"></div>
                    @endif
                </div>
            @endforeach

        </div>
    </div>

    @push('styles')
    <style>
        @keyframes pulse-slow {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); box-shadow: 0 0 15px rgba(253, 224, 71, 0.6); }
        }
        .animate-pulse-slow {
            animation: pulse-slow 2s infinite;
        }
    </style>
    @endpush
@endsection

{{-- Instructions specific to Level Selection --}}
@section('instructions')
    <div class="flex flex-col items-center gap-4 text-center">
        <div class="text-6xl">🔒</div>
        <p>Tahap yang ada <b>mangga kunci</b> belum boleh main lagi.</p>
        <p>Habiskan tahap nombor <b>{{ $currentLevel }}</b> dulu untuk buka kunci seterusnya!</p>
    </div>
@endsection