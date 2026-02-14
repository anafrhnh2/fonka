@extends('layouts.game')

@section('game-title', $level->name . ' Activities')

@section('content')
    <div class="h-full w-full flex flex-col items-center justify-center">
        
        <h2 class="text-3xl font-bold text-indigo-600 mb-6 animate-bounce-slow">
            Hi {{ $child->name }}! Pilih satu permainan 👇
        </h2>

        @if($activities->isEmpty())
            <div class="bg-red-100 text-red-500 p-6 rounded-xl text-xl font-bold">
                Maaf, tiada permainan untuk umur {{ $child->age }} tahun di tahap ini.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full max-w-4xl px-4">
                @foreach($activities as $activity)
                    <a href="{{ route('games.play', $activity->id) }}" class="group bg-white rounded-3xl p-6 shadow-lg border-b-8 border-indigo-200 hover:border-indigo-400 hover:scale-105 transition-all flex flex-col items-center">
                        
                        <div class="text-6xl mb-4 transition transform group-hover:rotate-12">
                            @if($activity->type == 'video') 📺 
                            @elseif($activity->type == 'quiz') ❓ 
                            @else 🎮 
                            @endif
                        </div>

                        <h3 class="text-2xl font-bold text-gray-700 group-hover:text-indigo-600 text-center">
                            {{ $activity->title }}
                        </h3>
                        
                        <span class="mt-2 bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-xs font-bold uppercase">
                            Sesuai untuk umur {{ $activity->min_age }}+
                        </span>

                        <div class="mt-6 w-full bg-indigo-500 text-white py-2 rounded-full font-bold text-center shadow group-hover:bg-indigo-600">
                            Main Sekarang!
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection