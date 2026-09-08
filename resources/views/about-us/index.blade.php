@extends('layouts.app')

@section('content')
<style>
    /* Subtle floating animation */
    @keyframes float-slow {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(2deg); }
    }
    .animate-float-slow {
        animation: float-slow 7s ease-in-out infinite;
    }
</style>

<div class="bg-slate-50 min-h-screen pb-24 font-lexend overflow-hidden">
    
    <!-- HEADER / HERO SECTION -->
    <section class="relative pt-24 pb-20 px-6 bg-gradient-to-b from-yellow-50 to-slate-50 mb-16 border-b border-yellow-100">
        <!-- Decorative Elements -->
        <div class="absolute top-12 left-10 w-32 h-32 bg-yellow-200 rounded-full mix-blend-multiply filter blur-2xl opacity-60 animate-float-slow"></div>
        <div class="absolute bottom-12 right-10 w-40 h-40 bg-pink-200 rounded-full mix-blend-multiply filter blur-2xl opacity-60 animate-float-slow" style="animation-delay: 2s;"></div>
        <div class="absolute top-20 right-1/4 text-4xl opacity-50 animate-bounce" style="animation-duration: 3s;">✨</div>

        <div class="container mx-auto max-w-4xl text-center relative z-10">
            <div class="inline-flex items-center gap-2 bg-white text-orange-500 px-5 py-2 rounded-full font-bold text-sm mb-8 tracking-wide shadow-sm border border-orange-100">
                <span></span> Selamat Datang ke Fonka
            </div>
            
            <h1 class="font-fredoka text-5xl md:text-6xl font-extrabold text-slate-900 mb-6 tracking-tight leading-tight">
                Misi Kami: <br>
                <span class="text-brand-green">Membaca Dengan Gembira.</span>
            </h1>
            
            <p class="text-lg md:text-2xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                Platform pembelajaran interaktif yang direka khusus untuk mengubah cara kanak-kanak, terutamanya mereka yang mempunyai disleksia, menguasai Bahasa Melayu.
            </p>
        </div>
    </section>

    <div class="container mx-auto px-6 max-w-6xl">
        
        <!-- SECTION: KISAH FONKA (OUR STORY) -->
        <div class="bg-white rounded-[3rem] p-10 md:p-16 shadow-sm border border-slate-100 mb-20 relative overflow-hidden">
            <!-- Subtle background blob -->
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-sky-50 rounded-full blur-3xl z-0"></div>
            
            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-sky-500 font-bold uppercase tracking-widest text-sm mb-3 block">Cerita Kami</span>
                    <h2 class="font-fredoka text-3xl md:text-4xl font-bold text-slate-900 mb-6">Kenapa Fonka Dicipta?</h2>
                    <div class="space-y-4 text-slate-600 text-lg leading-relaxed">
                        <p>
                            Membaca sepatutnya menjadi satu pengembaraan yang menyeronokkan, bukan satu bebanan. Namun, bagi kanak-kanak disleksia, sistem pembelajaran tradisional sering kali membuatkan mereka rasa tertinggal.
                        </p>
                        <p>
                            <strong class="text-slate-800">Fonka lahir daripada satu impian mudah:</strong> mencipta ruang yang inklusif, selamat, dan bebas tekanan. Kami menggabungkan kaedah pendidikan fonik yang diiktiraf dengan teknologi moden dan elemen permainan (gamification) supaya setiap anak dapat bersinar dengan cara mereka tersendiri.
                        </p>
                    </div>
                </div>
                
                <div class="relative flex justify-center">
                    <div class="w-full max-w-sm aspect-square bg-sky-100 rounded-[2rem] rotate-3 p-2 relative">
                        <!-- Replace this with an actual screenshot or image of your system -->
                        <div class="w-full h-full bg-white rounded-[1.5rem] overflow-hidden border-4 border-white shadow-inner flex items-center justify-center">
                            <span class="text-8xl">🚀</span>
                        </div>
                        <!-- Floating Badge -->
                        <div class="absolute -bottom-6 -left-6 bg-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 transform -rotate-6">
                            <span class="text-2xl">❤️</span>
                            <div>
                                <p class="text-sm font-bold text-slate-800 leading-none">Dibina dengan</p>
                                <p class="text-xs text-slate-400 mt-1">Kasih Sayang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION: NILAI TERAS (CORE PILLARS) -->
        <div class="text-center mb-12">
            <h2 class="font-fredoka text-3xl md:text-4xl font-bold text-slate-900 mb-4">Cara Fonka Berfungsi</h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto">Kami memfokuskan kepada tiga elemen utama untuk memastikan kejayaan pembelajaran anak anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
            <!-- Card 1 -->
            <div class="bg-white p-10 rounded-3xl shadow-sm border border-slate-100 text-center hover:-translate-y-2 transition duration-300">
                <div class="mx-auto w-20 h-20 bg-pink-100 text-pink-500 rounded-full flex items-center justify-center text-4xl mb-6">
                    🅰️
                </div>
                <h3 class="font-bold text-2xl text-slate-800 mb-4">Sistem Fonik</h3>
                <p class="text-slate-600 leading-relaxed">
                    Belajar membaca bermula dengan bunyi, bukan ejaan kosong. Kami membantu kanak-kanak mengenal pasti dan menggabungkan bunyi huruf dengan berkesan.
                </p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-10 rounded-3xl shadow-sm border border-slate-100 text-center hover:-translate-y-2 transition duration-300">
                <div class="mx-auto w-20 h-20 bg-sky-100 text-sky-500 rounded-full flex items-center justify-center text-4xl mb-6">
                    🎮
                </div>
                <h3 class="font-bold text-2xl text-slate-800 mb-4">Bebas Tekanan</h3>
                <p class="text-slate-600 leading-relaxed">
                    Tiada markah merah, tiada "Game Over". Platform kami meraikan kemajuan kecil dan menggunakan lencana serta ganjaran untuk memotivasikan anak anda.
                </p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-10 rounded-3xl shadow-sm border border-slate-100 text-center hover:-translate-y-2 transition duration-300">
                <div class="mx-auto w-20 h-20 bg-brand-green/20 text-brand-green rounded-full flex items-center justify-center text-4xl mb-6">
                    📊
                </div>
                <h3 class="font-bold text-2xl text-slate-800 mb-4">Sokongan Ibubapa</h3>
                <p class="text-slate-600 leading-relaxed">
                    Papan pemuka (dashboard) khas membolehkan ibubapa memantau kemajuan, mengenal pasti kawasan kelemahan, dan menyokong pembelajaran secara berterusan.
                </p>
            </div>
        </div>

        <!-- CALL TO ACTION -->
        <div class="bg-slate-900 rounded-[3rem] p-12 md:p-16 text-center relative overflow-hidden">
            <!-- Decorative Glow -->
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full h-full bg-brand-green opacity-20 blur-[100px] pointer-events-none"></div>
            
            <div class="relative z-10">
                <h2 class="font-fredoka text-3xl md:text-5xl font-bold text-white mb-6">Sedia untuk melihat mereka berjaya?</h2>
                <p class="text-lg text-slate-400 mb-10 max-w-2xl mx-auto">Sertai komuniti Fonka hari ini dan jadikan proses belajar membaca satu pengalaman yang indah.</p>
                
                <a href="{{ route('onboarding.child') }}" class="inline-block px-10 py-5 bg-brand-green text-white text-xl font-bold rounded-2xl shadow-xl shadow-green-900/50 hover:bg-green-600 hover:scale-105 transition-all duration-300">
                    Mulakan Pengembaraan
                </a>
            </div>
        </div>

    </div>
</div>
@endsection