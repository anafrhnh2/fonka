<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Fonka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Fredoka', sans-serif;
            background-color: #F0F4F8;
            background-image: radial-gradient(#E2E8F0 2px, transparent 2px);
            background-size: 30px 30px;
        }

        /* Profile Card Styling */
        .profile-card {
            background: white;
            border-radius: 25px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
            border: 3px solid transparent;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            cursor: pointer;
            margin-bottom: 20px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #A78BFA; /* Light Purple Highlight */
        }

        /* Avatar Circles */
        .avatar-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            flex-shrink: 0;
            font-weight: bold;
        }

        /* Add Student Button (Dotted Border) */
        .add-student-card {
            border: 3px dashed #CBD5E1;
            background: rgba(255, 255, 255, 0.5);
            justify-content: center;
            color: #64748B;
        }
        .add-student-card:hover {
            border-color: #818CF8;
            color: #818CF8;
            background: white;
        }
    </style>
</head>
<body class="min-h-screen">

    <nav class="bg-white border-b-4 border-indigo-100 py-4 px-8 mb-10">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="text-3xl">🚀</span> 
                <h1 class="text-3xl font-bold text-indigo-600 tracking-wide">Fonka</h1>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="text-gray-500 font-medium">Hello, {{ Auth::user()->name }}!</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-100 text-red-500 px-4 py-2 rounded-full font-bold hover:bg-red-200 transition">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </nav>

   <main class="px-4 pb-20">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-bold text-gray-800 mb-2">Who is learning today?</h2>
            <p class="text-xl text-gray-400">Select a profile to start playing!</p>
        </div>

        <a href="{{ route('profile.edit') }}" class="profile-card group">
            <div class="avatar-circle bg-purple-500 shadow-lg group-hover:scale-110 transition-transform">
                <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-1">
                <h3 class="text-2xl font-bold text-gray-800 group-hover:text-purple-600 uppercase">
                    {{ Auth::user()->name }}
                </h3>
                <p class="text-gray-400 text-lg">Parent Dashboard • Settings</p>
            </div>
            <div class="text-gray-300 group-hover:text-purple-500 text-2xl">➜</div>
        </a>

        @foreach($children as $child)
            <a href="#" class="profile-card group">
                
                <div class="avatar-circle overflow-hidden shadow-lg border-4 border-green-200 group-hover:scale-110 transition-transform bg-green-100">
                    @if($child->avatar && $child->avatar !== 'default_avatar.png')
                        <img src="{{ asset('storage/' . $child->avatar) }}" alt="{{ $child->name }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://cdn-icons-png.flaticon.com/512/2922/2922510.png" alt="Default Avatar" class="w-full h-full object-cover">
                    @endif
                </div>

                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-800 group-hover:text-green-500 capitalize">
                        {{ $child->name }}
                    </h3>
                    <p class="text-gray-400 text-lg">
                        Level {{ $child->current_level }} • {{ $child->stars }} Stars 🌟
                    </p>
                </div>
                <div class="text-gray-300 group-hover:text-green-500 text-2xl">➜</div>
            </a>
        @endforeach

        <a href="" class="profile-card add-student-card">
            <div class="text-4xl mr-3 font-light">+</div>
            <span class="text-xl font-bold">Add New Student</span>
        </a>

    </main>

</body>
</html>