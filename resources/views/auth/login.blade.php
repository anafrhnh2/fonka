<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in to Fonka</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&family=Lexend:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        fredoka: ['Fredoka', 'sans-serif'],
                        lexend: ['Lexend', 'sans-serif'],
                        poppins: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            overflow-x: hidden;
            /* Prevent horizontal scroll from the wave */
        }

        /* Input Styling Matching The Image */
        .input-box {
            width: 100%;
            background-color: #eff6ff;
            border: none;
            border-bottom: 2px solid #bfdbfe;
            /* Light blue border */
            border-radius: 4px 4px 0 0;
            padding: 14px 16px;
            outline: none;
            color: #1e293b;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .input-box:focus {
            border-bottom-color: #2563EB;
            /* Deep blue focus */
            background-color: #e0e7ff;
        }

        .input-box::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* Custom Checkbox */
        .custom-checkbox {
            appearance: none;
            background-color: #fff;
            margin: 0;
            font: inherit;
            color: currentColor;
            width: 1.15em;
            height: 1.15em;
            border: 2px solid #2563EB;
            border-radius: 0.15em;
            display: grid;
            place-content: center;
            cursor: pointer;
        }

        .custom-checkbox::before {
            content: "";
            width: 0.65em;
            height: 0.65em;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em white;
            background-color: #2563EB;
            transform-origin: bottom left;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }

        .custom-checkbox:checked {
            background-color: #2563EB;
        }

        .custom-checkbox:checked::before {
            transform: scale(1);
        }
    </style>
</head>

<body class="min-h-screen bg-white">

    <div class="flex flex-col md:flex-row min-h-screen w-full relative">

        <div
            class="w-full md:w-[45%] bg-[#84CC16] text-white flex flex-col items-center justify-center p-12 min-h-[40vh] md:min-h-screen z-10 relative">

            <div
                class="hidden md:block absolute top-0 bottom-0 -right-24 w-24 h-full pointer-events-none text-[#84CC16] overflow-hidden">
                <svg viewBox="0 0 100 1000" preserveAspectRatio="none" class="w-full h-full" fill="currentColor">
                    <path d="M0,0 L0,1000 L20,1000 C100,750 -20,250 20,0 Z"></path>
                </svg>
            </div>

            <div class="relative z-20 flex flex-col items-center text-center max-w-sm mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold mb-6 tracking-wide">Welcome back! Let's continue learning.
                </h2>

            </div>
        </div>

        <div class="w-full md:w-[55%] bg-white flex flex-col justify-center px-8 py-16 md:p-24 relative z-0">
            <div class="flex justify-center">
                <img src="{{ asset('images/logo-fonka.png') }}" alt="Fonka Logo"
                    class="h-20 md:h-24 w-auto transition-all duration-300 ease-in-out group-hover:-translate-y-2">
            </div>

            <div class="w-full max-w-md mt-8 mx-auto md:ml-20">


                @if (session('status'))
                    <div
                        class="mb-6 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                        <div class="relative">
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="input-box" placeholder="email@gmail.com">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required class="input-box pr-10"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-4 top-3.5 text-blue-300 hover:text-blue-600 transition">
                                <i class="far fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between text-sm pt-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="remember" class="custom-checkbox">
                            <span class="text-slate-500 font-medium group-hover:text-blue-600 transition">Remember
                                me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-[#2563EB] hover:text-blue-700 font-semibold transition">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-4 pt-6">
                        <button type="submit"
                            class="bg-[#84CC16] text-white px-10 py-3 rounded-full font-semibold tracking-wide shadow-[0_8px_20px_rgba(37,99,235,0.25)] hover:bg-[#4F7A0D] hover:-translate-y-0.5 transition-all duration-300">
                            Log In
                        </button>

                        <a href="{{ route('onboarding.child') }}"
                            class="bg-white border-2 border-[#84CC16] text-slate-600 px-10 py-3 rounded-full font-semibold hover:border-[#84CC16] hover:bg-[#4F7A0D] hover:text-white transition-colors duration-300">
                            Sign Up
                        </a>
                    </div>
                </form>

                <div class="mt-16 text-center md:text-left">
                    <a href="{{ url('/') }}"
                        class="text-sm text-slate-400 font-medium hover:text-slate-600 transition flex items-center justify-center md:justify-start gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Back to Home
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
