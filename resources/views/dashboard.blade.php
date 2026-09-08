<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Dashboard</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
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
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Inter+Tight:wght@300;400;500;600;700&family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            /* Fallback font family just in case */
            font-family: 'Inter Tight', sans-serif;
            
            /* Updated Gradient using Brand Colors (Blue, Pink, Yellow, Green) */
            background: linear-gradient(-45deg, #E0F2FE, #FCE7F3, #FEF08A, #84CC16);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            
            /* Changed text color to brand-dark for better contrast */
            color: #0C4A6E; 
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Glassmorphism Container */
        .dashboard-container {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border-radius: 2rem;
        }

        /* Generic Card Styling */
        .profile-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid #f1f5f9;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Parent Specific Card */
        .parent-card:hover {
            border-color: #d8b4fe;
            /* Purple */
        }

        .parent-card::before {
            background-color: #9333ea;
            opacity: 0;
        }

        .parent-card:hover::before {
            opacity: 1;
        }

        /* Child Specific Card */
        .child-card:hover {
            border-color: #bef264;
            /* Lime */
        }

        .child-card::before {
            background-color: #84cc16;
            opacity: 0;
        }

        .child-card:hover::before {
            opacity: 1;
        }

        /* Avatars */
        .avatar-box {
            width: 70px;
            height: 70px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            flex-shrink: 0;
            font-weight: bold;
            box-shadow: inset 0 -3px 0 rgba(0, 0, 0, 0.1);
        }

        /* Logout Button */
        .btn-logout {
            background-color: white;
            color: #ef4444;
            border: 2px solid #fee2e2;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background-color: #fef2f2;
            border-color: #fca5a5;
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="font-sans min-h-screen flex items-center justify-center p-4 sm:p-8">

    <div class="fixed top-0 left-0 w-full p-4 sm:p-6 flex justify-between items-center z-50 pointer-events-none">
        <div></div>
        <form method="POST" action="{{ route('logout') }}" class="pointer-events-auto">
            @csrf
            <button type="submit"
                class="btn-logout flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold shadow-sm">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span class="hidden sm:inline">Sign Out</span>
            </button>
        </form>
    </div>

    <main class="dashboard-container w-full max-w-4xl p-8 md:p-12 mt-12 md:mt-0 relative">

        <div class="text-center mb-12">
            <div class="flex justify-center mb-6">
                <a href="{{ url('/') }}"
                    class="flex items-center justify-center group cursor-pointer decoration-0">
                    <img src="{{ asset('images/logo-fonka.png') }}" alt="Fonka Logo"
                        class="h-16 md:h-20 w-auto object-contain group-hover:scale-105 transition-transform duration-300 ease-in-out">
                </a>
            </div>

            <h2 class="text-2xl md:text-2xl font-bold text-brand-dark mb-2 tracking-tight">Choose profile to continue</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">

            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-2 mb-2 px-2">
                    <i class="fa-solid fa-user-shield text-purple-500"></i>
                    <h3 class="text-xl font-bold text-brand-dark tracking-wide">Parent Profile</h3>
                </div>

                <a href="{{ route('parents.index') }}" class="profile-card parent-card group">
                   <div class="avatar-box bg-purple-100 p-1 group-hover:scale-105 transition-transform duration-300 border-2 border-purple-200">
                        @if (Auth::user()->profile)
                            <img src="{{ asset('storage/' . Auth::user()->profile) }}" alt="Profile"
                                class="w-full h-full object-cover rounded-xl drop-shadow-md">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=ede9fe&color=8b5cf6&size=130"
                                alt="Profile" class="w-full h-full object-cover rounded-xl drop-shadow-md">
                        @endif
                    </div>
                    <div class="flex-1 text-left">
                        <h4
                            class="text-xl font-bold text-slate-800 group-hover:text-purple-600 transition-colors  line-clamp-1">
                            {{ Auth::user()->name }}
                        </h4>
                        <p class="text-slate-400 text-sm font-medium mt-1">Track progress</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-full bg-purple-50 text-purple-400 flex items-center justify-center group-hover:bg-purple-500 group-hover:text-white transition-all">
                        <i class="fa-solid fa-chevron-right text-sm"></i>
                    </div>
                </a>
            </div>

            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-2 mb-2 px-2">
                    <i class="fa-solid fa-children text-lime-500"></i>
                    <h3 class="text-xl font-bold text-brand-dark tracking-wide">Child Profile</h3>
                </div>

                @if (isset($children) && count($children) > 0)
                    @foreach ($children as $child)
                        @php
                            $currentAvatar = \Illuminate\Support\Facades\DB::table('avatars')
                                ->where('id', $child->avatar_id)
                                ->first();

                            $avatarPath = $currentAvatar
                                ? asset($currentAvatar->image)
                                : asset('images/default_avatar.png');
                        @endphp

                        <a href="{{ route('child.select', $child->id) }}" wire:navigate
                            class="profile-card child-card group">
                            <div
                                class="avatar-box bg-lime-100 p-1 group-hover:scale-105 transition-transform duration-300 border-2 border-lime-200">
                                <img src="{{ $avatarPath }}" alt="{{ $child->name }}"
                                    class="w-full h-full object-contain drop-shadow-md">
                            </div>

                            <div class="flex-1 text-left">
                                <h4
                                    class="text-xl font-bold text-slate-800 group-hover:text-lime-600 transition-colors capitalize line-clamp-1">
                                    {{ $child->name }}
                                </h4>
                                <div class="flex items-center gap-3 mt-1">
                                    <span
                                        class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-0.5 rounded-full">Tahap
                                        {{ $child->current_level ?? 1 }}</span>
                                    <span class="text-amber-500 text-sm font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-coins"></i> {{ $child->total_point ?? 0 }}
                                    </span>
                                </div>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-lime-50 text-lime-500 flex items-center justify-center group-hover:bg-lime-500 group-hover:text-white transition-all">
                                <i class="fa-solid fa-play text-sm ml-1"></i>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="text-center py-6 px-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50">
                        <i class="fa-solid fa-user-plus text-3xl text-slate-300 mb-2"></i>
                        <p class="text-slate-500 font-medium">Tiada akaun anak lagi.</p>
                        <a href="{{ route('parents.index') }}"
                            class="text-lime-600 hover:text-lime-700 font-bold text-sm mt-1 inline-block">Sila tambah
                            profil di Akaun Ibu Bapa.</a>
                    </div>
                @endif

            </div>

        </div>
    </main>

</body>

</html>