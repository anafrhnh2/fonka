<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Fonka - Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Poppins', sans-serif;
        }

        /* Custom Green from image */
        .bg-brand-green {
            background-color: #66CC00;
        }

        .text-brand-green {
            color: #66CC00;
        }

        .border-brand-green {
            border-color: #66CC00;
        }

        .btn-green {
            background-color: #66CC00;
        }

        .btn-green:hover {
            background-color: #55aa00;
        }

        /* Custom Purple from image */
        .bg-brand-purple {
            background-color: #9D4EDD;
        }

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
            background: transparent;
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

<body class="min-h-screen text-gray-800 antialiased">

    <div class="flex flex-col-reverse md:flex-row min-h-screen w-full relative overflow-x-hidden">

        <div class="w-full md:w-[55%] lg:w-[60%] flex items-center justify-center p-6 sm:p-8 lg:p-16 z-20">

            <div class="bg-white w-full max-w-md p-6 sm:p-8 rounded-[30px] shadow-2xl relative border border-gray-100 mt-8 md:mt-0">
                
                <div class="card-accent"></div>

                <h2 class="text-2xl font-bold text-gray-800 mb-6 pl-4">Create Parent Account</h2>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div> 
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Name</label>
                        <div class="form-input-container">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                autofocus autocomplete="name" class="form-input" placeholder="Enter your Name">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1 ml-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <div class="form-input-container">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                autocomplete="username" class="form-input" placeholder="Enter email address">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1 ml-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                        <div class="form-input-container">
                            <input type="password" name="password" id="password" required autocomplete="new-password"
                                class="form-input" placeholder="Password">
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1 ml-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password</label>
                        <div class="form-input-container">             
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                autocomplete="new-password" class="form-input" placeholder="Confirm Password">
                        </div>
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1 ml-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-start sm:items-center ml-2 pt-2">
                        <input id="terms" type="checkbox"
                            class="w-4 h-4 mt-1 sm:mt-0 text-purple-600 border-gray-300 rounded focus:ring-purple-500 cursor-pointer">
                        <label for="terms" class="ml-3 block text-sm text-gray-500 cursor-pointer leading-tight sm:leading-normal">
                            By signing up you agree to our <a href="#" class="text-brand-purple font-bold hover:underline">Terms of Use</a>.
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full btn-green text-white font-bold py-3.5 rounded-2xl shadow-lg transform transition hover:-translate-y-1 text-lg">
                            Sign Up
                        </button>
                    </div>

                    <div class="text-center mt-6">
                        <a href="{{ route('login') }}"
                            class="text-sm text-gray-500 font-medium hover:text-brand-purple transition-colors">
                            Already registered? <span class="text-brand-purple font-bold underline">Log In</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="w-full md:w-[45%] lg:w-[40%] bg-[#84CC16] text-white flex flex-col items-center justify-center p-8 md:p-12 min-h-[35vh] md:min-h-screen z-10 relative">
            
            <div class="hidden md:block absolute top-0 bottom-0 -left-24 w-24 h-full pointer-events-none text-[#84CC16] overflow-hidden transform -scale-x-100">
                <svg viewBox="0 0 100 1000" preserveAspectRatio="none" class="w-full h-full" fill="currentColor">
                    <path d="M0,0 L0,1000 L20,1000 C100,750 -20,250 20,0 Z"></path>
                </svg>
            </div>

            <div class="relative z-20 flex flex-col items-center text-center max-w-sm mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold mb-6 tracking-wide">Join Fonka and help your child learn with confidence.</h2>

              
            </div>
        </div>

    </div>

</body>

</html>