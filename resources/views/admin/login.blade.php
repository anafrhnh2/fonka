<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login 🖤💖</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #050505; /* Hitam yang lebih pekat untuk kontras neon */
        }

        /* --- Vanilla CSS Animations --- */
        @keyframes fadeSlideUp {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes floatBlob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }

        @keyframes shine {
            0% { left: -100%; }
            20%, 100% { left: 200%; }
        }

        /* --- Component Styling --- */
        .login-card {
            /* Animasi masuk semasa page load */
            animation: fadeSlideUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            background: rgba(18, 18, 18, 0.8);
            backdrop-filter: blur(20px); /* Kesan kaca moden */
        }

        /* Animasi Latar Belakang */
        .floating-blob {
            animation: floatBlob 15s infinite ease-in-out alternate;
        }

        .floating-blob-delayed {
            animation: floatBlob 18s infinite ease-in-out alternate-reverse;
            animation-delay: 2s;
        }

        .neon-text {
            text-shadow: 0 0 10px rgba(255, 42, 133, 0.6), 0 0 25px rgba(255, 42, 133, 0.4);
        }

        /* --- Moden Floating Input Labels --- */
        .input-group {
            position: relative;
            margin-top: 1.5rem;
        }

        .input-neon {
            background: rgba(0, 0, 0, 0.4);
            border: 2px solid #2a2a2a;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-neon:focus {
            border-color: #ff2a85;
            box-shadow: 0 0 15px rgba(255, 42, 133, 0.15), inset 0 0 10px rgba(255, 42, 133, 0.05);
        }

        .input-label {
            position: absolute;
            left: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            transition: all 0.3s ease;
            pointer-events: none;
            padding: 0 0.5rem;
            background: transparent;
            font-weight: 600;
        }

        /* Trik Vanilla CSS: Naikkan label apabila input di-klik ATAU apabila input mempunyai teks */
        .input-neon:focus ~ .input-label,
        .input-neon:not(:placeholder-shown) ~ .input-label {
            top: 0;
            transform: translateY(-50%) scale(0.85);
            color: #ff99d6;
            background: #121212; /* Warna selari dengan kad login */
        }

        /* --- Butang Shine Effect --- */
        .btn-shiny {
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(255, 42, 133, 0.3);
            transition: all 0.3s ease;
        }

        .btn-shiny:hover {
            box-shadow: 0 0 25px rgba(255, 42, 133, 0.6);
            transform: translateY(-3px);
        }

        /* Jalur cahaya bergerak */
        .btn-shiny::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-25deg);
            animation: shine 5s infinite; /* Berulang setiap 5 saat */
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Bebola Cahaya Animasi Latar Belakang -->
    <div class="floating-blob absolute top-[10%] left-[20%] w-[30rem] h-[30rem] bg-[#ff2a85] rounded-full blur-[130px] opacity-20 pointer-events-none"></div>
    <div class="floating-blob-delayed absolute bottom-[5%] right-[15%] w-[25rem] h-[25rem] bg-[#9D4EDD] rounded-full blur-[120px] opacity-15 pointer-events-none"></div>

    <!-- Kotak Log Masuk -->
    <div class="login-card border border-[#333] rounded-[2.5rem] p-10 w-full max-w-md relative z-10 shadow-2xl">
        
        <!-- Tajuk -->
        <div class="text-center mb-8">
            <h2 class="text-4xl font-bold text-white tracking-widest uppercase mb-1 neon-text">Admin</h2>
            <p class="text-[#ff99d6] font-semibold text-xs tracking-[0.2em] uppercase opacity-80">Access Restricted to Aina only</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-950/40 border border-red-500/50 text-red-300 px-4 py-3 rounded-2xl mb-6 text-sm text-center font-bold" style="animation: fadeSlideUp 0.4s ease-out;">
                 {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/admin/login" class="space-y-6">
            @csrf

            <div class="input-group">
                <input type="email" name="email" id="email" required placeholder=" "
                       class="input-neon w-full text-white px-6 py-4 rounded-2xl focus:outline-none font-medium">
                <label for="email" class="input-label">Email</label>
            </div>

            <div class="input-group">
                <input type="password" name="password" id="password" required placeholder=" "
                       class="input-neon w-full text-white px-6 py-4 rounded-2xl focus:outline-none font-medium">
                <label for="password" class="input-label">Password</label>
            </div>

            <div class="pt-6">
                <button type="submit"
                        class="btn-shiny w-full bg-[#ff2a85] text-white font-bold text-lg py-4 rounded-2xl uppercase tracking-widest flex justify-center items-center gap-2">
                    Login <span class="text-xl"></span>
                </button>
            </div>
        </form>
        
    </div>

</body>
</html>