@extends('layouts.game')

@section('game-title', 'Juara Fonka')

@section('content')
    <div class="h-full w-full flex flex-col items-center justify-start overflow-y-auto no-scrollbar py-8 px-4">

        <div class="text-center mb-10">
            <h2 class="text-5xl font-black text-slate-700 mb-2 drop-shadow-sm">Carta Juara</h2>
            <p class="text-xl text-slate-500 font-bold">Siapakah yang paling banyak kumpul mata?</p>
        </div>

        <div class="w-full max-w-3xl flex flex-col gap-4 pb-12">

            @foreach ($topPlayers as $index => $player)
                @php
                    // Set warna berbeza untuk Top 3
                    $rank = $index + 1;
                    $bgClass = 'bg-white border-gray-200';
                    $rankColor = 'text-gray-400';
                    $icon = 'fa-medal';

                    if ($rank == 1) {
                        $bgClass = 'bg-yellow-50 border-yellow-400 shadow-[0_8px_0_#facc15]';
                        $rankColor = 'text-yellow-500';
                        $icon = 'fa-trophy';
                    } elseif ($rank == 2) {
                        $bgClass = 'bg-gray-50 border-gray-300 shadow-[0_8px_0_#cbd5e1]';
                        $rankColor = 'text-gray-400';
                    } elseif ($rank == 3) {
                        $bgClass = 'bg-orange-50 border-orange-300 shadow-[0_8px_0_#fdba74]';
                        $rankColor = 'text-orange-400';
                    } else {
                        $bgClass = 'bg-white border-sky-200 shadow-[0_8px_0_#bae6fd]';
                        $rankColor = 'text-sky-400';
                    }

                    // Tanda highlight jika ini adalah akaun anak yang sedang main
                    $isMe = $player->id == $activeChildId;
                    if ($isMe) {
                        $bgClass = 'bg-green-100 border-green-400 shadow-[0_8px_0_#4ade80] scale-[1.02] z-10';
                    }
                @endphp

                <div
                    class="flex items-center justify-between p-4 md:p-6 rounded-[2rem] border-4 {{ $bgClass }} transition-transform hover:-translate-y-1">

                    <div class="flex items-center gap-4 md:gap-6">
                        <div class="w-12 text-center">
                            <h3 class="text-3xl font-black {{ $rankColor }} m-0">
                                @if ($rank <= 3)
                                    <i class="fa-solid {{ $icon }}"></i>
                                @else
                                    #{{ $rank }}
                                @endif
                            </h3>
                        </div>

                        @php
                            $avatar = DB::table('avatars')->where('id', $player->avatar_id)->first();
                        @endphp

                        <div
                            class="w-16 h-16 md:w-20 md:h-20 rounded-full border-4 border-white shadow-inner overflow-hidden bg-gray-100">
                            @if ($avatar)
                                <img src="{{ asset($avatar->image) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('images/default_avatar.png') }}" alt="Avatar"
                                    class="w-full h-full object-cover">
                            @endif
                        </div>

                        <div>
                            <h4
                                class="text-2xl font-black {{ $isMe ? 'text-green-700' : 'text-slate-700' }} capitalize m-0 leading-none">
                                {{ $player->name }}
                                @if ($isMe)
                                    <span
                                        class="text-sm bg-green-500 text-white px-3 py-1 rounded-full ml-2 align-middle">SAYA</span>
                                @endif
                            </h4>
                        </div>
                    </div>

                    <div
                        class="bg-yellow-400 text-white px-5 py-2 md:py-3 rounded-full shadow-inner border-b-4 border-yellow-600 flex items-center gap-2">
                        <span class="text-xl md:text-2xl font-black">{{ $player->total_point ?? 0 }}</span>
                        <span class="text-sm font-bold text-yellow-100 uppercase hidden md:inline">Pts</span>
                    </div>

                </div>
            @endforeach

        </div>
    </div>

    @push('styles')
        <style>
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
    @endpush
@endsection
