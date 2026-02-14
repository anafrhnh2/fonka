<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Fredoka', sans-serif;
            background-color: #f3f4f6;
        }
        /* Custom Green from image */
        .bg-brand-green { background-color: #66CC00; }
        .text-brand-green { color: #66CC00; }
        .border-brand-green { border-color: #66CC00; }
        .btn-green { background-color: #66CC00; }
        .btn-green:hover { background-color: #55aa00; }
        
        /* Custom Purple from image */
        .bg-brand-purple { background-color: #9D4EDD; }
        
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
                 <img src="https://cdn-icons-png.flaticon.com/512/3406/3406960.png" alt="Learning Fun" class="w-2/3 object-contain drop-shadow-2xl relative z-10 animate-bounce-slow">
            </div>
        </div>

        <div class="w-full lg:w-[45%] h-full flex items-center justify-center z-10 lg:pl-20">
            
            <div class="bg-white w-full max-w-md p-8 rounded-[30px] shadow-2xl relative border border-gray-100">
                
                <div class="card-accent"></div>

                <h2 class="text-2xl font-bold text-gray-800 mb-6 pl-4">Create an Account</h2>

                <div class="flex bg-gray-100 p-1.5 rounded-2xl mb-6">
                    <button type="button" class="w-1/2 bg-brand-purple text-white py-2.5 rounded-xl shadow-md font-bold flex items-center justify-center gap-2 transition-transform transform active:scale-95">
                        <span>👨‍👩‍👧</span> Parent
                    </button>
                    <button type="button" class="w-1/2 text-gray-500 py-2.5 font-semibold hover:text-gray-700 flex items-center justify-center gap-2">
                        <span>🎓</span> Learner
                    </button>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <div class="form-input-container">
                            <span class="text-gray-400 text-xl">👤</span>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                                class="form-input" placeholder="Enter Full Name">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1 ml-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="form-input-container">
                            <span class="text-gray-400 text-xl">✉️</span>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="username"
                                class="form-input" placeholder="Enter Email Address">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1 ml-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="form-input-container">
                            <span class="text-gray-400 text-xl">🔒</span>
                            <input type="password" name="password" id="password" required autocomplete="new-password"
                                class="form-input" placeholder="Password">
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1 ml-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="form-input-container">
                            <span class="text-gray-400 text-xl">🔐</span>
                            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                                class="form-input" placeholder="Confirm Password">
                        </div>
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1 ml-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center ml-2">
                        <input id="terms" type="checkbox" class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <label for="terms" class="ml-2 block text-sm text-gray-500">
                            By signing up you agree to our <a href="#" class="text-brand-purple font-bold hover:underline">Terms of Use</a>.
                        </label>
                    </div>

                    <button type="submit" class="w-full btn-green text-white font-bold py-3.5 rounded-2xl shadow-lg transform transition hover:-translate-y-1 text-lg">
                        Sign Up
                    </button>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-500 font-medium hover:text-brand-purple">
                            Already registered? <span class="text-brand-purple font-bold underline">Log In</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>