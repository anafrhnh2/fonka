@extends('layouts.app')

@section('content')

    <header class="w-full bg-gradient-to-b from-[#E0F2FE] to-white pt-20 pb-24 text-center px-6 relative overflow-hidden">
        
        <div class="absolute top-10 left-10 w-32 h-32 bg-yellow-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"></div>
        <div class="absolute top-10 right-10 w-32 h-32 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse" style="animation-delay: 2s"></div>

        <div class="max-w-4xl mx-auto relative z-10">
           

            <h1 class="font-fredoka text-5xl md:text-7xl font-bold text-slate-800 mb-6 leading-tight">
                Reading is hard. <br>
                <span class="text-brand-blue relative">
                    We make it fun.
                    <svg class="absolute w-full h-4 -bottom-2 left-0 text-yellow-400" viewBox="0 0 100 10" preserveAspectRatio="none">
                        <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="8" fill="none" />
                    </svg>
                </span>
            </h1>

            <p class="font-lexend text-xl md:text-2xl text-slate-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                A science-based learning game designed specifically for <strong class="text-slate-700">dyslexic minds</strong>. Master phonics through sound, sight, and play.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-brand-orange text-white text-xl font-bold rounded-2xl shadow-[0_4px_0_rgb(194,65,12)] hover:shadow-[0_2px_0_rgb(194,65,12)] hover:translate-y-[2px] transition-all flex items-center justify-center gap-2">
                    <span>🚀</span> Start Learning Free
                </a>
                <a href="#how-it-works" class="w-full sm:w-auto px-8 py-4 bg-white text-slate-600 text-xl font-bold rounded-2xl border-2 border-slate-200 hover:border-brand-blue hover:text-brand-blue transition-all flex items-center justify-center gap-2">
                    <span>▶</span> See How It Works
                </a>
            </div>
        </div>
    </header>

    <section class="py-20 bg-slate-50 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="font-fredoka text-4xl font-bold text-slate-800 mb-4">Why Fonka?</h2>
                <p class="font-lexend text-lg text-slate-500 max-w-2xl mx-auto">Traditional schools rely on memorization. We rely on <span class="text-brand-blue font-bold">Multisensory Learning</span>—connecting sound, sight, and touch.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-white p-8 rounded-[2rem] border-b-8 border-blue-100 hover:border-blue-300 transition-colors group">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                        👁️
                    </div>
                    <h3 class="font-fredoka text-2xl font-bold text-slate-700 mb-3">Dyslexia-Friendly UI</h3>
                    <p class="font-lexend text-slate-500 leading-relaxed">
                        We use OpenDyslexic and Fredoka fonts, high contrast colors, and zero visual clutter to reduce reading anxiety.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-[2rem] border-b-8 border-green-100 hover:border-green-300 transition-colors group">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                        👂
                    </div>
                    <h3 class="font-fredoka text-2xl font-bold text-slate-700 mb-3">Phonics & Sound</h3>
                    <p class="font-lexend text-slate-500 leading-relaxed">
                        Children learn to connect sounds to letters using their own voice. Our AI listens and gives gentle feedback.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-[2rem] border-b-8 border-orange-100 hover:border-orange-300 transition-colors group">
                    <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                        🏆
                    </div>
                    <h3 class="font-fredoka text-2xl font-bold text-slate-700 mb-3">Stress-Free Gaming</h3>
                    <p class="font-lexend text-slate-500 leading-relaxed">
                        No timers. No "game overs". Just rewards, badges, and progress. Learning feels like playing.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <section class="py-20 bg-white px-6 overflow-hidden">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-12 md:gap-20">
            
            <div class="w-full md:w-1/2 relative">
                <div class="absolute inset-0 bg-brand-blue opacity-10 rounded-full filter blur-3xl transform scale-90"></div>
                
                <div class="relative bg-gradient-to-tr from-blue-50 to-white border-4 border-slate-100 rounded-[3rem] p-8 shadow-2xl rotate-2 hover:rotate-0 transition-transform duration-500">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <div class="text-sm text-slate-400 font-bold uppercase tracking-wider">Today's Progress</div>
                            <div class="text-3xl font-fredoka font-bold text-slate-800">15 Words Learned</div>
                        </div>
                        <div class="bg-green-100 text-green-700 font-bold px-3 py-1 rounded-full text-sm">Excellent!</div>
                    </div>
                    <div class="flex items-end gap-3 h-32 mb-4">
                        <div class="w-1/5 bg-blue-200 rounded-t-lg h-[40%]"></div>
                        <div class="w-1/5 bg-blue-300 rounded-t-lg h-[60%]"></div>
                        <div class="w-1/5 bg-brand-blue rounded-t-lg h-[80%] relative group">
                             <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition">Today</div>
                        </div>
                        <div class="w-1/5 bg-slate-100 rounded-t-lg h-[20%]"></div>
                        <div class="w-1/5 bg-slate-100 rounded-t-lg h-[20%]"></div>
                    </div>
                    <p class="text-center text-slate-400 text-sm font-bold">Weekly Activity</p>
                </div>
            </div>

            <div class="w-full md:w-1/2 text-left">
                <span class="inline-block py-1 px-3 rounded-full bg-purple-100 text-purple-600 text-sm font-bold mb-4">For Parents</span>
                <h2 class="font-fredoka text-4xl md:text-5xl font-bold text-slate-800 mb-6">Track progress, <br>not mistakes.</h2>
                <p class="font-lexend text-lg text-slate-500 mb-8 leading-relaxed">
                    We know it can be frustrating when your child struggles. Fonka gives you a simple, clear dashboard to see exactly what they are learning.
                </p>
                
                <ul class="space-y-4 font-lexend text-slate-600">
                    <li class="flex items-start gap-3">
                        <div class="bg-green-100 p-1 rounded-full text-green-600 mt-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                        <span>See which sounds they mastered.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="bg-green-100 p-1 rounded-full text-green-600 mt-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                        <span>Identify tricky words needing practice.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="bg-green-100 p-1 rounded-full text-green-600 mt-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                        <span>Get email summaries (no login needed).</span>
                    </li>
                </ul>
            </div>

        </div>
    </section>

    <section class="py-20 px-4">
        <div class="w-full bg-[#0F172A] text-white mx-auto max-w-6xl rounded-[2.5rem] shadow-2xl overflow-hidden relative">
            
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#334155 1px, transparent 1px); background-size: 20px 20px;"></div>

            <div class="flex flex-col md:flex-row items-center relative z-10">
                
                <div class="p-10 md:p-16 md:w-1/2 text-left">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-brand-blue flex items-center justify-center font-bold text-xs">F</div>
                        <span class="font-bold tracking-widest text-xs text-blue-200 uppercase">Our Mission</span>
                    </div>
                    <h2 class="font-fredoka text-3xl md:text-4xl font-bold mb-6 leading-tight">
                        Dyslexic Thinking is a Superpower.
                    </h2>
                    <p class="font-lexend text-slate-300 mb-8 leading-relaxed">
                        We aren't trying to "fix" your child. We are giving them the tools to decode the world so their brilliance can shine.
                    </p>
                    <button class="bg-white text-slate-900 font-bold px-6 py-3 rounded-xl hover:bg-blue-50 transition flex items-center gap-2">
                        <span>▶</span> Watch Our Story
                    </button>
                </div>

                <div class="w-full md:w-1/2 h-80 md:h-[500px] bg-slate-800 relative group cursor-pointer overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=2022&auto=format&fit=crop" 
                         alt="Happy child learning" 
                         class="w-full h-full object-cover opacity-60 group-hover:opacity-40 transition duration-500">
                    
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-20 h-20 bg-brand-blue rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition duration-300">
                            <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path></svg>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-24 text-center px-6">
        <h2 class="font-fredoka text-4xl md:text-5xl font-bold text-slate-800 mb-6">Ready to start the adventure?</h2>
        <p class="font-lexend text-xl text-slate-500 mb-10">No credit card required. Cancel anytime.</p>
        
        <div class="inline-block p-2 bg-white rounded-3xl shadow-xl border border-slate-100">
            <a href="{{ route('register') }}" class="block w-full sm:w-auto px-12 py-5 bg-brand-orange text-white text-2xl font-bold rounded-2xl shadow-lg hover:bg-orange-600 transition hover:scale-105">
                Join Fonka for Free
            </a>
        </div>
    </section>

@endsection