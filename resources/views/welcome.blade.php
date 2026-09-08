@extends('layouts.app')

@section('content')
    <title>{{ __('Fonka - Home') }}</title>

    <style>
        .hero {
            background-color: #F0F9FF;
            /* Changed to a soft child-friendly sky blue (Tailwind sky-50) */
        }

        @keyframes blob-bounce {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        .animate-blob-slow {
            animation: blob-bounce 12s infinite alternate ease-in-out;
        }

        @keyframes morph {

            0%,
            100% {
                border-radius: 40% 60% 70% 30% / 40% 40% 60% 50%;
            }

            34% {
                border-radius: 70% 30% 50% 50% / 30% 30% 70% 70%;
            }

            67% {
                border-radius: 100% 60% 60% 100% / 100% 100% 60% 60%;
            }
        }

        .morph-shape {
            animation: morph 8s infinite alternate ease-in-out;
        }
    </style>

    <section id="hero"
        class="relative w-full min-h-screen flex items-center pt-20 pb-32 px-6 overflow-hidden bg-[#7ba4f4]">
        <div class="absolute inset-0 top-[20%] lg:top-[15%] bg-[#ffe26f] transform -skew-y-6 origin-top-left z-0"></div>

        <div class="absolute inset-x-0 bottom-0 h-[40%] bg-[#ff7b93] transform -skew-y-6 origin-bottom-right z-0"></div>

        <div class="absolute inset-x-0 bottom-0 h-[10%] bg-[#ff7b93] z-0"></div>

        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0] z-20 pointer-events-none">
            <svg class="relative block w-full h-[60px] md:h-[100px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120"
                preserveAspectRatio="none">
                <path
                    d="M0,60L48,65.3C96,71,192,82,288,78.7C384,75,480,57,576,46.2C672,35,768,32,864,38.2C960,44,1056,59,1152,65.3C1248,71,1344,68,1392,66.7L1440,65.3V120H1392C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120H0V60Z"
                    fill="#f0f9ff"></path>
            </svg>
        </div>

        <div
            class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10 w-full mt-10 lg:mt-0">

            <div class="text-left order-2 lg:order-1 drop-shadow-sm">
                <h1 class="text-5xl md:text-5xl font-extrabold text-gray-800 leading-[1.1] mb-6 tracking-tight">
                    {{ __('Learn by') }} <br class="hidden md:block" />
                    <span id="typed-text" class="text-brand-green inline-block min-h-[1em]"></span>
                </h1>
                <p class="font-lexend text-lg md:text-xl text-gray-800 leading-relaxed max-w-xl mb-10">
                    {{ __('Helping children, including') }} <span class="font-bold">{{ __('children with dyslexia') }}</span>{{ __(', master phonics and writing in Malay through interactive learning.') }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('onboarding.child') }}"
                        class="px-8 py-3.5 bg-brand-green text-white text-lg font-bold rounded-full shadow-lg hover:-translate-y-1 bg-[#4F7A0D] transition-all duration-300">
                        {{ __('Get Started') }}
                    </a>
                </div>
            </div>
            <div class="relative flex justify-center items-center order-1 lg:order-2">
                <div class="absolute w-72 h-72 bg-brand-green rounded-full blur-3xl animate-blob-slow"></div>
                <div class="relative w-full max-w-md aspect-square">
                    <div class="absolute inset-0 bg-brand-green morph-shape"></div>
                    <div class="absolute inset-6 overflow-hidden morph-shape shadow-2xl bg-white border-[6px] border-white">
                        <img src="{{ asset('images/hero.png') }}" alt="Hero Image" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-sky-50 px-6 text-center ">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-4xl font-extrabold text-gray-800 mb-4 tracking-tight">{{ __('Why Fonka?') }}</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 ">
                {{-- 1 --}}
                <div
                    class="relative bg-white p-10 rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-slate-100 group overflow-hidden cursor-default text-center">
                    <div
                        class="absolute inset-x-0 bottom-0 h-1 bg-[#ff7b93] transition-all duration-500 group-hover:h-2 opacity-50 group-hover:opacity-100">
                    </div>
                    <div
                        class="mx-auto w-16 h-16 bg-[#ff7b93]/20 text-[#ff7b93] rounded-2xl flex items-center justify-center text-3xl mb-8 transition-all duration-500 group-hover:bg-[#ff7b93] group-hover:text-white group-hover:scale-110 group-hover:rotate-6 shadow-sm">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <h3
                        class="text-xl font-bold text-slate-800 mb-4 tracking-tight transition-colors duration-300 group-hover:text-[#ff7b93]">
                        {{ __('Interactive Learning') }}</h3>
                    <p class="text-slate-600 text-lg leading-relaxed font-lexend">
                        {{ __('Letter sounds, syllables, and spelling exercises.') }}
                    </p>
                </div>

                {{-- 2 --}}
                <div
                    class="relative bg-white p-10 rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-slate-100 group overflow-hidden cursor-default text-center">
                    <div
                        class="absolute inset-x-0 bottom-0 h-1 bg-brand-green transition-all duration-500 group-hover:h-2 opacity-50 group-hover:opacity-100">
                    </div>
                    <div
                        class="mx-auto w-16 h-16 bg-brand-green/20 text-brand-green rounded-2xl flex items-center justify-center text-3xl mb-8 transition-all duration-500 group-hover:bg-brand-green group-hover:text-white group-hover:scale-110 group-hover:-rotate-6 shadow-sm">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3
                        class="text-xl font-bold text-slate-800 mb-4 tracking-tight transition-colors duration-300 group-hover:text-brand-green">
                        {{ __('Reading Modules') }}</h3>
                    <p class="text-slate-600 text-lg leading-relaxed font-lexend">
                        {{ __('Children learn letter sounds using their own voice. The system listens to their pronunciation and provides feedback.') }}
                    </p>
                </div>

                {{-- 3 --}}
                <div
                    class="relative bg-white p-10 rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-slate-100 group overflow-hidden cursor-default text-center">
                    <div
                        class="absolute inset-x-0 bottom-0 h-1 bg-amber-400 transition-all duration-500 group-hover:h-2 opacity-50 group-hover:opacity-100">
                    </div>
                    <div
                        class="mx-auto w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-3xl mb-8 transition-all duration-500 group-hover:bg-amber-500 group-hover:text-white group-hover:scale-110 group-hover:rotate-12 shadow-sm">
                        <i class="fas fa-hands-holding-child"></i>
                    </div>
                    <h3
                        class="text-xl font-bold text-slate-800 mb-4 tracking-tight transition-colors duration-300 group-hover:text-amber-600">
                        {{ __('Parent Mode') }}</h3>
                    <p class="text-slate-600 text-lg leading-relaxed font-lexend">
                        {{ __('Progress reports and the ability to create multiple child profiles.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-20">
            <div class="w-full lg:w-1/2 relative group">
                <div
                    class="absolute -inset-4 bg-gradient-to-r from-purple-200 via-pink-200 to-yellow-200 rounded-[3.5rem] blur-2xl opacity-50 group-hover:opacity-70 transition duration-500">
                </div>
                <div class="relative bg-white border border-slate-100 rounded-[2rem] p-4 md:p-6 shadow-2xl">

                  

                    <div class="relative rounded-xl overflow-hidden border border-slate-200 shadow-inner bg-slate-50">
                        <img src="{{ asset('images/laporan.png') }}" alt="Fonka Parent Dashboard Example"
                            class="w-full h-auto object-cover transform hover:scale-[1.02] transition-transform duration-500 ease-in-out">

                        <div class="absolute inset-0 ring-1 ring-inset ring-black/5 rounded-xl pointer-events-none"></div>
                    </div>

                    <p class="text-center text-slate-400 font-medium font-lexend text-sm mt-5">{{ __('Interface for real-time monitoring of your child\'s progress') }}</p>

                </div>
            </div>

            <div class="w-full lg:w-1/2 text-left">
                <h2 class=" text-4xl md:text-6xl font-bold text-slate-900 mb-8 leading-tight">{{ __('Clear Reports,') }} <br>{{ __('Stress-Free.') }}</h2>
                <p class="font-lexend text-lg text-slate-600 mb-10 leading-relaxed">
                    {{ __('We understand the challenges of educating special needs children. Fonka provides a simple dashboard so you know where they excel and where they need support.') }}
                </p>

                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <li class="flex items-center gap-4 group">
                        <div
                            class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-500 text-white flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="font-bold text-slate-800">{{ __('Create multiple child profile') }}</span>
                    </li>
                    <li class="flex items-center gap-4 group">
                        <div
                            class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-500 text-white flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="font-bold text-slate-800">{{ __('Identify Difficult Words') }}</span>
                    </li>
                    <li class="flex items-center gap-4 group">
                        <div
                            class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-500 text-white flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="font-bold text-slate-800">{{ __('Child Achievement Tracking') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="py-24 bg-amber-50 px-6 overflow-hidden relative">
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-96 h-96 bg-amber-200/50 rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row-reverse items-center gap-16 relative z-10">
            
            <div class="w-full lg:w-1/2 relative">
                <div class="relative flex justify-center items-center">
                    <div class="relative bg-slate-800 p-3 rounded-[2.5rem] shadow-2xl border-[8px] border-slate-700 w-full max-w-sm transform -rotate-3 hover:rotate-0 transition-transform duration-700">
                        <div class="bg-white rounded-[1.5rem] overflow-hidden aspect-[4/3]">
                             <img src="{{ asset('images/hero.png') }}" alt="Fonka on Tablet" class="w-full h-full object-cover opacity-90">
                        </div>
                        <div class="absolute bottom-2 left-1/2 -translate-x-1/2 w-10 h-1 bg-slate-600 rounded-full"></div>
                    </div>
                    
                    <div class="absolute -bottom-6 -right-4 md:right-10 bg-white p-6 rounded-3xl shadow-xl border border-amber-100 transform rotate-6 hidden sm:block">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-2xl">
                                <i class="fa-solid fa-laptop"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ __('Laptop Ready') }}</p>
                                <p class="text-xs text-slate-500">{{ __('Mouse & Keyboard') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/2 text-left">
             
                <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 leading-tight">
                    {{ __('Play on Tablet') }} <span class="text-amber-500">{{ __('or Laptop.') }}</span>
                </h2>
                
                <p class="font-lexend text-lg text-slate-600 mb-8 leading-relaxed">
                    {{ __('Fonka is designed to be responsive and accessible. Whether your child prefers the tactile touch of a tablet or the larger screen of a laptop, the learning experience remains seamless and fun.') }}
                </p>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="mt-1 w-6 h-6 rounded-full bg-green-500 text-white flex-shrink-0 flex items-center justify-center">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">{{ __('Optimized for Touch') }}</h4>
                            <p class="text-slate-500 text-sm">{{ __('Big buttons and easy drag-and-drop for little fingers.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="mt-1 w-6 h-6 rounded-full bg-green-500 text-white flex-shrink-0 flex items-center justify-center">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">{{ __('Web Browser Based') }}</h4>
                            <p class="text-slate-500 text-sm">{{ __('No heavy downloads. Just log in and start learning instantly.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="py-32 bg-[#7ba4f4] text-center px-6 relative overflow-hidden rounded-[4rem] mx-6 mb-12">
        <div class="absolute top-0 left-1/4 w-64 h-64 bg-[#7ba4f4] opacity-40 blur-[120px]"></div>
        <div class="relative z-10">
            <h2 class=" text-5xl md:text-5xl font-bold text-white mb-8">{{ __('Start Learning with Fonka.') }}</h2>
            <p class="font-lexend text-xl text-sky-100 mb-12 max-w-2xl mx-auto">{{ __('Join thousands of other parents in helping children read with joy.') }}</p>

        </div>
    </section>

    <script>
        // JS FOR TYPING EFFECT IN HERO SECTION
        // Using Blade {{ __('...') }} so the words translate in Javascript
        const words = [
            "{{ __('Reading.') }}", 
            "{{ __('Listening.') }}", 
            "{{ __('Playing.') }}", 
            "{{ __('Speaking.') }}"
        ];
        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        const typingDelay = 150;
        const erasingDelay = 100;
        const newWordDelay = 2000;
        const typedTextSpan = document.getElementById("typed-text");

        function type() {
            const currentWord = words[wordIndex];

            if (isDeleting) {
                typedTextSpan.textContent = currentWord.substring(0, charIndex - 1);
                charIndex--;
            } else {
                typedTextSpan.textContent = currentWord.substring(0, charIndex + 1);
                charIndex++;
            }

            typedTextSpan.innerHTML = typedTextSpan.textContent + '<span class="animate-pulse">|</span>';

            if (!isDeleting && charIndex === currentWord.length) {
                isDeleting = true;
                setTimeout(type, newWordDelay);
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                setTimeout(type, 500);
            } else {
                setTimeout(type, isDeleting ? erasingDelay : typingDelay);
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            setTimeout(type, newWordDelay);
        });
    </script>
@endsection