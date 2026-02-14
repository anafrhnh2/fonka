@extends('layouts.game')

{{-- 1. Set the Title in the HUD --}}
@section('game-title', 'Menu Permainan 🎮')

{{-- 2. Main Content Area --}}
@section('content')
    <div class="h-full w-full flex flex-col items-center justify-center overflow-y-auto no-scrollbar py-4">
        
        <div class="text-center mb-8 animate-bounce-slow">
            <h2 class="text-3xl md:text-4xl font-bold text-indigo-600 mb-2 dyslexia-text">
                Apa kita nak main hari ini?
            </h2>
            <p class="text-lg text-gray-500 font-medium">Pilih satu aktiviti di bawah!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full max-w-4xl px-4">
            
            <a href="{{ route('games.levels', ['gameType' => 'letter-recognition']) }}" class="group relative bg-white rounded-3xl p-6 shadow-lg border-b-8 border-r-8 border-blue-200 hover:border-blue-400 hover:scale-105 transition-all duration-300 flex flex-col items-center text-center">
                <div class="absolute -top-6 -right-6 bg-yellow-400 text-white font-bold px-3 py-1 rounded-full text-sm shadow-md rotate-12 group-hover:rotate-0 transition">
                    Mudah
                </div>
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center text-5xl mb-4 group-hover:rotate-12 transition">
                    🅰️
                </div>
                <h3 class="text-2xl font-bold text-gray-700 group-hover:text-blue-600 mb-2">Mari Bermain !</h3>
                <p class="text-gray-400 text-sm leading-tight">Belajar bentuk dan bunyi huruf ABC.</p>
                <div class="mt-4 bg-blue-500 text-white px-6 py-2 rounded-full font-bold shadow-md group-hover:bg-blue-600">
                    Mula! ▶
                </div>
            </a>

            <a href="#" class="group relative bg-white rounded-3xl p-6 shadow-lg border-b-8 border-r-8 border-purple-200 hover:border-purple-400 hover:scale-105 transition-all duration-300 flex flex-col items-center text-center">
                <div class="absolute -top-6 -left-6 bg-red-400 text-white font-bold px-3 py-1 rounded-full text-sm shadow-md -rotate-12 group-hover:rotate-0 transition">
                    Mencabar
                </div>
                <div class="w-24 h-24 bg-purple-100 rounded-full flex items-center justify-center text-5xl mb-4 group-hover:scale-110 transition">
                    📖
                </div>
                <h3 class="text-2xl font-bold text-gray-700 group-hover:text-purple-600 mb-2">Jom Membaca</h3>
                <p class="text-gray-400 text-sm leading-tight">Baca ayat mudah dengan lancar.</p>
                <div class="mt-4 bg-purple-500 text-white px-6 py-2 rounded-full font-bold shadow-md group-hover:bg-purple-600">
                    Mula! ▶
                </div>
            </a>

             <a href="#" class="group relative bg-white rounded-3xl p-6 shadow-lg border-b-8 border-r-8 border-orange-200 hover:border-orange-400 hover:scale-105 transition-all duration-300 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center text-5xl mb-4 group-hover:animate-pulse">
                    👂
                </div>
                <h3 class="text-2xl font-bold text-gray-700 group-hover:text-orange-600 mb-2">Uji Pendengaran</h3>
                <p class="text-gray-400 text-sm leading-tight">Dengar bunyi dan pilih jawapan.</p>
                <div class="mt-4 bg-orange-500 text-white px-6 py-2 rounded-full font-bold shadow-md group-hover:bg-orange-600">
                    Mula! ▶
                </div>
            </a>

        </div>
    </div>

    @push('styles')
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(-5%); }
            50% { transform: translateY(0); }
        }
        .animate-bounce-slow {
            animation: bounce-slow 3s infinite;
        }
    </style>
    @endpush
@endsection

{{-- 3. Instructions for the Help Modal --}}
@section('instructions')
    <div class="flex flex-col items-center gap-4">
        <div class="text-6xl">👆</div>
        <p>Adik perlu <b>tekan pada gambar</b> permainan yang adik nak main.</p>
        <p class="text-sm text-gray-400">Pilih "Kenal Huruf" jika baru bermula!</p>
    </div>
@endsection