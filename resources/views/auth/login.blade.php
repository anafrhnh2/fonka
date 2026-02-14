<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - Fonka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Fredoka', sans-serif;
            background-color: #f3f4f6;
        }
        /* Custom Green */
        .bg-brand-green { background-color: #66CC00; }
        .text-brand-green { color: #66CC00; }
        .btn-green { background-color: #66CC00; }
        .btn-green:hover { background-color: #55aa00; }
        
        /* Custom Purple */
        .text-brand-purple { color: #9D4EDD; }
        .focus-ring-purple:focus { ring-color: #9D4EDD; }

        .form-input-container {
            display: flex;
            align-items: center;
            border: 2px solid #E5E7EB;
            border-radius: 15px; /* Super rounded */
            padding: 0.5rem 1rem;
            transition: all 0.3s;
            background: white;
        }
        .form-input-container:focus-within {
            border-color: #9D4EDD;
            box-shadow: 0 0 0 3px rgba(157, 78, 221, 0.2);
        }
        .form-input {
            width: 100%;
            outline: none;
            border: none;
            color: #4B5563;
            font-weight: 500;
            margin-left: 10px;
        }
        /* The green tab on the left of the card */
        .card-accent {
            position: absolute;
            left: 0;
            top: 40px;
            bottom: 40px;
            width: 8px;
            background-color: #66CC00;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 lg:p-0 bg-white lg:bg-transparent">

    <div class="w-full h-screen flex overflow-hidden relative">
        
        <div class="hidden lg:block absolute top-0 right-0 w-[60%] h-full bg-brand-green z-0" style="border-top-left-radius: 200px;">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 2px, transparent 2px); background-size: 30px 30px;"></div>
            
            <div class="h-full w-full flex items-center justify-center relative">
                 <img src="https://cdn-icons-png.flaticon.com/512/4140/4140047.png" alt="Welcome Back" class="w-2/3 object-contain drop-shadow-2xl relative z-10 animate-pulse">
            </div>
        </div>

        <div class="w-full lg:w-[45%] h-full flex items-center justify-center z-10 lg:pl-20">
            
            <div class="bg-white w-full max-w-md p-8 rounded-[30px] shadow-2xl relative border border-gray-100">
                
                <div class="card-accent"></div>

                <div class="mb-6 pl-4">
                    <h2 class="text-3xl font-bold text-gray-800">Welcome Back! 👋</h2>
                    <p class="text-gray-400 font-medium mt-1">Let's continue learning.</p>
                </div>

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-brand-green bg-green-50 p-3 rounded-xl border border-green-200">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <div class="form-input-container">
                            <span class="text-gray-400 text-xl">✉️</span>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                class="form-input" placeholder="Enter Email Address">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1 ml-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="form-input-container">
                            <span class="text-gray-400 text-xl">🔑</span>
                            <input type="password" name="password" id="password" required autocomplete="current-password"
                                class="form-input" placeholder="Password">
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1 ml-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between ml-2">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500" name="remember">
                            <span class="ml-2 text-sm text-gray-500 font-medium">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-brand-purple font-bold hover:underline" href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full btn-green text-white font-bold py-3.5 rounded-2xl shadow-lg transform transition hover:-translate-y-1 text-lg">
                        Log In
                    </button>

                    <div class="text-center mt-6">
                        <a href="{{ route('register') }}" class="text-sm text-gray-500 font-medium hover:text-brand-purple">
                            Don't have an account? <span class="text-brand-purple font-bold underline">Register Now</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>