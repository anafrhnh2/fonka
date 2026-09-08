@extends('layouts.app')
    <title>{{ __('Fonka - About Dyslexia') }}</title>

@section('content')
    <div class="relative bg-gradient-to-b from-sky-50 to-white min-h-screen py-16 md:py-24 overflow-hidden">

        <div class="relative container mx-auto mt-20 px-4 max-w-4xl text-center mb-20 z-10">
            
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-800 mb-6 tracking-tight leading-tight">
                {{ __('Understanding') }} <span class="text-brand-green">{{ __('Dyslexia') }}</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                {{ __('Dyslexia is a learning disorder that affects the ability to read, spell, write, and speak.') }}
            </p>
        </div>

        <div class="relative container mx-auto px-4 max-w-5xl space-y-16 md:space-y-28 pb-20 z-10">

            <div class="flex flex-col md:flex-row items-center gap-10 lg:gap-16 group cursor-default">
                <div class="w-full md:w-1/2 relative">
                    <div
                        class="relative w-full aspect-square md:aspect-auto md:h-96 rounded-[30%_70%_70%_30%/30%_30%_70%_70%] overflow-hidden shadow-2xl transition-all duration-700 group-hover:rounded-[50%_50%_40%_60%/60%_40%_60%_40%]">
                        <img src="{{ asset('images/dyslexia.jpg') }}" alt="Understanding Dyslexia"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-sky-400 rounded-full -z-10 opacity-50"></div>
                </div>

                <div class="w-full md:w-1/2 space-y-6">
                    <h3 class="text-3xl md:text-4xl font-extrabold text-slate-800 leading-tight">{{ __('What is Dyslexia?') }}</h3>
                    <p class="text-slate-600 text-lg leading-relaxed">
                        {{ __('It is not a disease, but rather a difference in how the brain processes information related to sounds and letters. The first step to helping your child is understanding their world of thought.') }}
                    </p>

                    <div
                        class="grid grid-rows-[0fr] opacity-0 group-hover:grid-rows-[1fr] group-hover:opacity-100 transition-all duration-500 ease-in-out">
                        <div class="overflow-hidden">
                            <ul class="space-y-3 mt-4 text-slate-600 font-medium pb-2 border-t-2 border-sky-100 pt-4">
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-circle-check text-sky-500 mt-1"></i>
                                    <span>{{ __('Confusing letters that look similar in shape, such as b & d, p & q, or m & w.') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-circle-check text-sky-500 mt-1"></i>
                                    <span>{{ __('Taking a longer time to read compared to peers and often stuttering.') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-circle-check text-sky-500 mt-1"></i>
                                    <span>{{ __('Frequently reversing syllables when spelling, even after being taught repeatedly.') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row-reverse items-center gap-10 lg:gap-16 group cursor-default">
                <div class="w-full md:w-1/2 relative">
                    <div
                        class="relative w-full aspect-square md:aspect-auto md:h-96 rounded-[60%_40%_30%_70%/60%_30%_70%_40%] overflow-hidden shadow-2xl transition-all duration-700 group-hover:rounded-[40%_60%_70%_30%/40%_50%_50%_60%] border-4 border-emerald-50">
                        <img src="{{ asset('images/sokongan.jpg') }}" alt="How to Help"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-emerald-400 rounded-full -z-10 opacity-50"></div>
                </div>

                <div class="w-full md:w-1/2 space-y-6">
                    <h3 class="text-3xl md:text-4xl font-extrabold text-slate-800 leading-tight">{{ __('Support at Home') }}</h3>
                    <p class="text-slate-600 text-lg leading-relaxed text-justify">
                        {{ __('Emotional support is very important. To ensure they can reach their full potential in learning, specific and suitable teaching techniques need to be introduced.') }}
                    </p>

                    <div
                        class="grid grid-rows-[0fr] opacity-0 group-hover:grid-rows-[1fr] group-hover:opacity-100 transition-all duration-500 ease-in-out">
                        <div class="overflow-hidden">
                            <ul class="space-y-3 mt-4 text-slate-600 font-medium pb-2 border-t-2 border-emerald-100 pt-4">
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-check-circle text-emerald-500 mt-1"></i>
                                    <span><strong>{{ __('Multisensory Approach:') }}</strong> {{ __('Use approaches involving various senses such as visual, auditory, and kinesthetic.') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-check-circle text-emerald-500 mt-1"></i>
                                    <span><strong>{{ __('Constant Repetition:') }}</strong> {{ __('Provide exercises that involve constant repetition to strengthen reading and spelling skills.') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-check-circle text-emerald-500 mt-1"></i>
                                    <span><strong>{{ __('Learning Through Songs and Rhythms:') }}</strong> {{ __('Use songs or rhythms to help students remember words and concepts.') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-check-circle text-emerald-500 mt-1"></i>
                                    <span><strong>{{ __('Visualization:') }}</strong> {{ __('Help students associate words with pictures or visual representations.') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="flex flex-col md:flex-row items-center gap-10 lg:gap-16 group cursor-default">
                <div class="w-full md:w-1/2 relative">
                    <div
                        class="relative w-full aspect-square md:aspect-auto md:h-96 rounded-[40%_60%_60%_40%/70%_30%_70%_30%] overflow-hidden shadow-2xl transition-all duration-700 group-hover:rounded-[70%_30%_40%_60%/30%_70%_30%_70%] border-4 border-rose-50">
                        <img src="https://placehold.co/800x600/FFE4E6/E11D48?text=Free+Resources" alt="Learning Resources"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-rose-400 rounded-full -z-10 opacity-30"></div>
                </div>

                <div class="w-full md:w-1/2 space-y-6">
                    <div
                        class="w-14 h-14 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm rotate-3 group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-gamepad"></i>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-slate-800 leading-tight">{{ __('Learning Through Play') }}
                    </h3>
                    <p class="text-slate-600 text-lg leading-relaxed">
                        {{ __('We provide interactive modules, voice-based spelling exercises, and special visual games. Your child can learn without feeling pressured.') }}
                    </p>

                    <div
                        class="grid grid-rows-[0fr] opacity-0 group-hover:grid-rows-[1fr] group-hover:opacity-100 transition-all duration-500 ease-in-out">
                        <div class="overflow-hidden">
                            <ul class="space-y-3 mt-4 text-slate-600 font-medium pb-2 border-t-2 border-rose-100 pt-4">
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-puzzle-piece text-rose-500 mt-1"></i>
                                    <span>{{ __('Interactive Vowel & Consonant Recognition Module.') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-microphone text-rose-500 mt-1"></i>
                                    <span>{{ __('Voice Recognition (Speech Recognition) exercises to train correct pronunciation.') }}</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-star text-rose-500 mt-1"></i>
                                    <span>{{ __('A tiered reward system to maintain their motivation.') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>

    </div>

    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
@endsection