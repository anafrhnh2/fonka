<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Fonka - Assessment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&family=Lexend:wght@300;400;600;700&family=Inter+Tight:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter Tight"', 'sans-serif'],
                        lexend: ['Lexend', 'sans-serif'],
                        fredoka: ['Fredoka', 'sans-serif'],
                    },
                    colors: {
                        'brand-blue': '#E0F2FE',
                        'brand-yellow': '#FEF08A',
                        'brand-pink': '#FCE7F3',
                        'brand-green': '#84CC16',
                        'brand-dark': '#0C4A6E',
                    },
                    animation: {
                        slideUpFade: 'slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1)',
                    },
                    keyframes: {
                        slideUpFade: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans bg-slate-100 text-slate-800 min-h-screen flex items-center justify-center p-4 sm:p-5">

    <div class="w-full max-w-[650px]">
        
        <!-- PROGRES BAR: 6 LANGKAH -->
        <div class="relative mb-8 md:mb-10 px-2 md:px-4 flex justify-between items-center">
            <div class="absolute top-1/2 left-[15px] right-[15px] md:left-[30px] md:right-[30px] h-1 bg-[#CFD8DC] z-0 -translate-y-1/2 rounded-sm"></div>
            <div class="absolute top-1/2 left-[15px] md:left-[30px] h-1 bg-[#26A69A] z-0 -translate-y-1/2 rounded-sm transition-all duration-500 ease-in-out w-0" id="stepLineProgress"></div>
            
            <div class="step-circle active w-8 h-8 md:w-10 md:h-10 rounded-full bg-white border-[2px] md:border-[3px] border-[#CFD8DC] text-[#90A4AE] flex justify-center items-center font-semibold text-sm md:text-lg transition-all duration-400 relative z-10 [&.active]:border-[#26A69A] [&.active]:text-[#26A69A] [&.active]:shadow-[0_0_0_4px_rgba(38,166,154,0.15)] [&.active]:scale-110 [&.completed]:bg-[#26A69A] [&.completed]:border-[#26A69A] [&.completed]:text-white" id="indicator-1">1</div>
            <div class="step-circle w-8 h-8 md:w-10 md:h-10 rounded-full bg-white border-[2px] md:border-[3px] border-[#CFD8DC] text-[#90A4AE] flex justify-center items-center font-semibold text-sm md:text-lg transition-all duration-400 relative z-10 [&.active]:border-[#26A69A] [&.active]:text-[#26A69A] [&.active]:shadow-[0_0_0_4px_rgba(38,166,154,0.15)] [&.active]:scale-110 [&.completed]:bg-[#26A69A] [&.completed]:border-[#26A69A] [&.completed]:text-white" id="indicator-2">2</div>
            <div class="step-circle w-8 h-8 md:w-10 md:h-10 rounded-full bg-white border-[2px] md:border-[3px] border-[#CFD8DC] text-[#90A4AE] flex justify-center items-center font-semibold text-sm md:text-lg transition-all duration-400 relative z-10 [&.active]:border-[#26A69A] [&.active]:text-[#26A69A] [&.active]:shadow-[0_0_0_4px_rgba(38,166,154,0.15)] [&.active]:scale-110 [&.completed]:bg-[#26A69A] [&.completed]:border-[#26A69A] [&.completed]:text-white" id="indicator-3">3</div>
            <div class="step-circle w-8 h-8 md:w-10 md:h-10 rounded-full bg-white border-[2px] md:border-[3px] border-[#CFD8DC] text-[#90A4AE] flex justify-center items-center font-semibold text-sm md:text-lg transition-all duration-400 relative z-10 [&.active]:border-[#26A69A] [&.active]:text-[#26A69A] [&.active]:shadow-[0_0_0_4px_rgba(38,166,154,0.15)] [&.active]:scale-110 [&.completed]:bg-[#26A69A] [&.completed]:border-[#26A69A] [&.completed]:text-white" id="indicator-4">4</div>
            <div class="step-circle w-8 h-8 md:w-10 md:h-10 rounded-full bg-white border-[2px] md:border-[3px] border-[#CFD8DC] text-[#90A4AE] flex justify-center items-center font-semibold text-sm md:text-lg transition-all duration-400 relative z-10 [&.active]:border-[#26A69A] [&.active]:text-[#26A69A] [&.active]:shadow-[0_0_0_4px_rgba(38,166,154,0.15)] [&.active]:scale-110 [&.completed]:bg-[#26A69A] [&.completed]:border-[#26A69A] [&.completed]:text-white" id="indicator-5">5</div>
            <div class="step-circle w-8 h-8 md:w-10 md:h-10 rounded-full bg-white border-[2px] md:border-[3px] border-[#CFD8DC] text-[#90A4AE] flex justify-center items-center font-semibold text-sm md:text-lg transition-all duration-400 relative z-10 [&.active]:border-[#26A69A] [&.active]:text-[#26A69A] [&.active]:shadow-[0_0_0_4px_rgba(38,166,154,0.15)] [&.active]:scale-110 [&.completed]:bg-[#26A69A] [&.completed]:border-[#26A69A] [&.completed]:text-white" id="indicator-6">6</div>
        </div>

        <form action="{{ route('onboarding.child.store') }}" method="POST" id="wizardForm">
            @csrf

            <!-- STEP 1: UMUR -->
            <div class="step-card active hidden [&.active]:block [&.active]:animate-slideUpFade rounded-[20px] md:rounded-[30px] shadow-[0_15px_35px_rgba(0,0,0,0.05)] bg-white border-4 md:border-8 border-[#84CC16] p-6 md:p-10 text-center" id="step1">
                <h3 class="font-semibold text-2xl md:text-3xl mb-4 md:mb-6 text-slate-800">Berapa umur anak anda?</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-3 mb-8 md:mb-10">
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] p-4 transition-all bg-white flex flex-col items-center justify-center hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'age')">
                        <input type="radio" name="age" value="4" class="hidden" required>
                        <div class="text-4xl md:text-5xl font-bold text-[#FF7043]">4</div>
                        <span class="text-slate-500 text-xs md:text-sm font-medium">Tahun</span>
                    </label>
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] p-4 transition-all bg-white flex flex-col items-center justify-center hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'age')">
                        <input type="radio" name="age" value="5" class="hidden" required>
                        <div class="text-4xl md:text-5xl font-bold text-[#FFCA28]">5</div>
                        <span class="text-slate-500 text-xs md:text-sm font-medium">Tahun</span>
                    </label>
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] p-4 transition-all bg-white flex flex-col items-center justify-center hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'age')">
                        <input type="radio" name="age" value="6" class="hidden" required>
                        <div class="text-4xl md:text-5xl font-bold text-[#66BB6A]">6</div>
                        <span class="text-slate-500 text-xs md:text-sm font-medium">Tahun</span>
                    </label>
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] p-4 transition-all bg-white flex flex-col items-center justify-center hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'age')">
                        <input type="radio" name="age" value="7" class="hidden" required>
                        <div class="text-4xl md:text-5xl font-bold text-[#26C6DA]">7</div>
                        <span class="text-slate-500 text-xs md:text-sm font-medium">Tahun</span>
                    </label>
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] p-4 transition-all bg-white flex flex-col items-center justify-center hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'age')">
                        <input type="radio" name="age" value="8" class="hidden" required>
                        <div class="text-4xl md:text-5xl font-bold text-[#ff7b93]">8</div>
                        <span class="text-slate-500 text-xs md:text-sm font-medium">Tahun</span>
                    </label>
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] p-4 transition-all bg-white flex flex-col items-center justify-center hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'age')">
                        <input type="radio" name="age" value="9" class="hidden" required>
                        <div class="text-4xl md:text-5xl font-bold text-[#AB47BC]">9</div>
                        <span class="text-slate-500 text-xs md:text-sm font-medium">Tahun</span>
                    </label>
                </div>
                
                <div class="flex justify-end">
                    <button type="button" class="bg-[#84CC16] text-white rounded-full px-6 py-2.5 md:px-8 md:py-3 text-sm md:text-[1.15rem] font-medium transition-all shadow-md hover:bg-[#55860c] hover:-translate-y-0.5" onclick="nextStep(2)">Seterusnya ➜</button>
                </div>
            </div>

            <!-- STEP 2: NAMA SAHAJA -->
            <div class="step-card hidden [&.active]:block [&.active]:animate-slideUpFade rounded-[20px] md:rounded-[30px] shadow-[0_15px_35px_rgba(0,0,0,0.05)] bg-white border-4 md:border-8 border-[#84CC16] p-6 md:p-10 text-center" id="step2">
                <h3 class="font-semibold text-2xl md:text-3xl mb-4 md:mb-6 text-slate-800">Nama Anak Anda</h3>
                
                <div class="mb-10 md:mb-12 text-left">
                    <label class="block font-semibold text-slate-500 ml-2 mb-2 text-sm md:text-base">Nama:</label>
                    <input type="text" name="name" class="w-full rounded-[15px] md:rounded-[18px] border-2 border-slate-200 px-4 py-4 md:px-5 md:py-5 text-lg md:text-xl transition-all focus:outline-none focus:border-[#26A69A] focus:bg-[#E0F2F1]" placeholder="Sila masukkan nama..." required>
                </div>
                
                <div class="flex justify-between mt-4">
                    <button type="button" class="bg-slate-50 text-slate-500 rounded-full px-5 py-2.5 md:px-8 md:py-3 text-sm md:text-[1.15rem] font-medium border border-slate-200 hover:bg-slate-200" onclick="prevStep(1)">Kembali</button>
                    <button type="button" class="bg-[#84CC16] text-white rounded-full px-6 py-2.5 md:px-8 md:py-3 text-sm md:text-[1.15rem] font-medium shadow-md hover:bg-[#55860c] hover:-translate-y-0.5" onclick="nextStep(3)">Seterusnya ➜</button>
                </div>
            </div>

            <!-- STEP 3: KEKELIRUAN HURUF (Q1) -->
            <div class="step-card hidden [&.active]:block [&.active]:animate-slideUpFade rounded-[20px] md:rounded-[30px] shadow-[0_15px_35px_rgba(0,0,0,0.05)] bg-white border-4 md:border-8 border-[#84CC16] p-6 md:p-10 text-center" id="step3">
                <h4 class="font-semibold text-lg md:text-2xl mb-6 md:mb-8 text-slate-800">Adakah anak anda keliru dengan huruf seperti <b>b</b> dan <b>d</b>? </h4>
                
                <div class="flex flex-col gap-3 md:gap-4 mb-8 md:mb-10">
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] px-4 py-4 md:px-6 md:py-5 transition-all bg-white flex flex-row items-center justify-between hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'q1')">
                        <span class="text-base md:text-xl font-medium">Ya, kadang-kadang</span>
                        <input type="radio" name="confuses_letters" value="yes" class="hidden" required>
                    </label>
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] px-4 py-4 md:px-6 md:py-5 transition-all bg-white flex flex-row items-center justify-between hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'q1')">
                        <span class="text-base md:text-xl font-medium">Tidak, tak pernah</span>
                        <input type="radio" name="confuses_letters" value="no" class="hidden" required>
                    </label>
                </div>
                
                <div class="flex justify-between items-center mt-4">
                    <button type="button" class="bg-slate-50 text-slate-500 rounded-full px-5 py-2.5 md:px-8 md:py-3 text-sm md:text-[1.15rem] font-medium border border-slate-200 transition-all hover:bg-slate-200" onclick="prevStep(2)">Kembali</button>
                    <button type="button" class="bg-[#84CC16] text-white rounded-full px-5 py-2.5 md:px-8 md:py-3 text-sm md:text-[1.15rem] font-medium shadow-md hover:bg-[#55860c] hover:-translate-y-0.5" onclick="nextStep(4)">Seterusnya ➜</button>
                </div>
            </div>

            <!-- STEP 4: BUNYI ASAS (Q2) -->
            <div class="step-card hidden [&.active]:block [&.active]:animate-slideUpFade rounded-[20px] md:rounded-[30px] shadow-[0_15px_35px_rgba(0,0,0,0.05)] bg-white border-4 md:border-8 border-[#84CC16] p-6 md:p-10 text-center" id="step4">
                <h4 class="font-semibold text-lg md:text-2xl mb-6 md:mb-8 text-slate-800">Adakah anak anda tahu membunyikan huruf asas? <br><small class="text-slate-500 text-sm md:text-lg font-normal mt-1 block">(Contoh: 'S' berbunyi /sss/)</small></h4>

                <div class="flex flex-col gap-3 md:gap-4 mb-8 md:mb-10">
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] px-4 py-4 md:px-6 md:py-5 transition-all bg-white flex flex-row items-center justify-between hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'q2')">
                        <span class="text-base md:text-xl font-medium">Ya, tahu</span>
                        <input type="radio" name="knows_sounds" value="yes" class="hidden" required>
                    </label>
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] px-4 py-4 md:px-6 md:py-5 transition-all bg-white flex flex-row items-center justify-between hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'q2')">
                        <span class="text-base md:text-xl font-medium">Belum tahu</span>
                        <input type="radio" name="knows_sounds" value="no" class="hidden" required>
                    </label>
                </div>
                
                <div class="flex justify-between items-center mt-4">
                    <button type="button" class="bg-slate-50 text-slate-500 rounded-full px-5 py-2.5 md:px-8 md:py-3 text-sm md:text-[1.15rem] font-medium border border-slate-200 transition-all hover:bg-slate-200" onclick="prevStep(3)">Kembali</button>
                    <button type="button" class="bg-[#84CC16] text-white rounded-full px-5 py-2.5 md:px-8 md:py-3 text-sm md:text-[1.15rem] font-medium shadow-md hover:bg-[#55860c] hover:-translate-y-0.5" onclick="nextStep(5)">Seterusnya ➜</button>
                </div>
            </div>

            <!-- STEP 5: PENGGABUNGAN BUNYI (Q3) -->
            <div class="step-card hidden [&.active]:block [&.active]:animate-slideUpFade rounded-[20px] md:rounded-[30px] shadow-[0_15px_35px_rgba(0,0,0,0.05)] bg-white border-4 md:border-8 border-[#84CC16] p-6 md:p-10 text-center" id="step5">
                <h4 class="font-semibold text-lg md:text-2xl mb-6 md:mb-8 text-slate-800">Bolehkah anak anda menggabungkan bunyi menjadi perkataan? <br><small class="text-slate-500 text-sm md:text-lg font-normal mt-1 block">(Contoh: b-a ba, j-u ju ➔ baju)</small></h4>

                <div class="flex flex-col gap-3 md:gap-4 mb-8 md:mb-10">
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] px-4 py-4 md:px-6 md:py-5 transition-all bg-white flex flex-row items-center justify-between hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'q3')">
                        <span class="text-base md:text-xl font-medium">Ya, boleh</span>
                        <input type="radio" name="can_blend" value="yes" class="hidden" required>
                    </label>
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] px-4 py-4 md:px-6 md:py-5 transition-all bg-white flex flex-row items-center justify-between hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'q3')">
                        <span class="text-base md:text-xl font-medium">Belum boleh</span>
                        <input type="radio" name="can_blend" value="no" class="hidden" required>
                    </label>
                </div>
                
                <div class="flex justify-between items-center mt-4">
                    <button type="button" class="bg-slate-50 text-slate-500 rounded-full px-5 py-2.5 md:px-8 md:py-3 text-sm md:text-[1.15rem] font-medium border border-slate-200 transition-all hover:bg-slate-200" onclick="prevStep(4)">Kembali</button>
                    <button type="button" class="bg-[#84CC16] text-white rounded-full px-5 py-2.5 md:px-8 md:py-3 text-sm md:text-[1.15rem] font-medium shadow-md hover:bg-[#55860c] hover:-translate-y-0.5" onclick="nextStep(6)">Seterusnya ➜</button>
                </div>
            </div>

            <!-- STEP 6: MEMBACA (Q4) -->
            <div class="step-card hidden [&.active]:block [&.active]:animate-slideUpFade rounded-[20px] md:rounded-[30px] shadow-[0_15px_35px_rgba(0,0,0,0.05)] bg-white border-4 md:border-8 border-[#84CC16] p-6 md:p-10 text-center" id="step6">
                <h4 class="font-semibold text-lg md:text-2xl mb-6 md:mb-8 text-slate-800">Bolehkah anak anda membaca perkataan pendek dengan sendiri? <br><small class="text-slate-500 text-sm md:text-lg font-normal mt-1 block">(Tanpa perlu mengeja dahulu)</small></h4>

                <div class="flex flex-col gap-3 md:gap-4 mb-8 md:mb-10">
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] px-4 py-4 md:px-6 md:py-5 transition-all bg-white flex flex-row items-center justify-between hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'q4')">
                        <span class="text-base md:text-xl font-medium">Boleh baca</span>
                        <input type="radio" name="reads_words" value="yes" class="hidden" required>
                    </label>
                    <label class="option-card cursor-pointer border-2 border-slate-200 rounded-[15px] md:rounded-[20px] px-4 py-4 md:px-6 md:py-5 transition-all bg-white flex flex-row items-center justify-between hover:border-[#B2DFDB] hover:bg-[#F8FDFD] hover:-translate-y-1 [&.selected]:border-[#26A69A] [&.selected]:bg-[#E0F2F1] [&.selected]:shadow-md [&.selected]:-translate-y-1" onclick="selectOption(this, 'q4')">
                        <span class="text-base md:text-xl font-medium">Belum lagi</span>
                        <input type="radio" name="reads_words" value="no" class="hidden" required>
                    </label>
                </div>
                
                <div class="flex justify-between items-center mt-4">
                    <button type="button" class="bg-slate-50 text-slate-500 rounded-full px-5 py-2.5 md:px-8 md:py-3 text-sm md:text-[1.15rem] font-medium border border-slate-200 transition-all hover:bg-slate-200" onclick="prevStep(5)">Kembali</button>
                    <button type="submit" class="bg-[#4CAF50] text-white rounded-full px-6 py-2.5 md:px-10 md:py-3 text-sm md:text-[1.2rem] font-semibold shadow-md hover:bg-[#43A047] hover:-translate-y-0.5">Selesai & Mula</button>
                </div>
            </div>

        </form>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 6;

        function showStep(step) {
            document.querySelectorAll('.step-card').forEach(card => card.classList.remove('active'));
            document.getElementById('step' + step).classList.add('active');
            
            document.querySelectorAll('.step-circle').forEach((circle, index) => {
                const stepNum = index + 1;
                circle.classList.remove('active', 'completed');
                
                if (stepNum === step) {
                    circle.classList.add('active');
                } else if (stepNum < step) {
                    circle.classList.add('completed');
                }
            });
            
            const progressPercentage = ((step - 1) / (totalSteps - 1)) * 100;
            document.getElementById('stepLineProgress').style.width = progressPercentage + '%';
        }

        function nextStep(targetStep) {
            const currentCard = document.getElementById('step' + currentStep);
            const inputs = currentCard.querySelectorAll('input[required]');
            let valid = true;

            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    valid = false;
                }
            });

            if (valid) {
                currentStep = targetStep;
                showStep(currentStep);
            }
        }

        function prevStep(targetStep) {
            currentStep = targetStep;
            showStep(currentStep);
        }

        function selectOption(element, groupName) {
            const group = element.closest('.step-card').querySelectorAll('.option-card');
            group.forEach(card => card.classList.remove('selected'));
            element.classList.add('selected');

            const radio = element.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        }
    </script>

</body>
</html>