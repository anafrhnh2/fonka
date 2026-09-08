@extends('layouts.admin')

@section('title', 'Overview')

@section('page_title')
    Welcome back, <span class="text-[#ff99d6]">{{ auth()->user()->name ?? 'Admin' }}</span>
@endsection

@section('page_subtitle', "Here's what's happening with Fonka today.")

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="stat-card neon-border rounded-2xl p-6 animate-enter delay-100">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Total Parents</p>
                    <h3 class="text-4xl font-bold text-white">{{ number_format($totalUsers) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-user-group text-2xl text-blue-400"
                        style="filter: drop-shadow(0 0 8px rgba(59, 130, 246, 0.8));"></i>
                </div>
            </div>
            <div class="text-sm font-medium text-gray-500">Registered parents accounts</div>
        </div>

        <div class="stat-card neon-border rounded-2xl p-6 animate-enter delay-100">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Total Children</p>
                    <h3 class="text-4xl font-bold text-white">{{ number_format($totalChildren) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[#ff2a85]/10 flex items-center justify-center">
                    <i class="fa-solid fa-children text-2xl text-[#ff2a85] icon-glow-pink"></i>
                </div>
            </div>
            <div class="text-sm font-medium text-gray-500">Active children</div>
        </div>

        <div class="stat-card neon-border rounded-2xl p-6 animate-enter delay-200">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Read Modules</p>
                    <h3 class="text-4xl font-bold text-white">{{ number_format($totalReadModules) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[#9D4EDD]/10 flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-2xl text-[#9D4EDD] icon-glow-purple"></i>
                </div>
            </div>
            <div class="text-sm font-medium text-gray-500">Reading modules available</div>
        </div>

        <div class="stat-card neon-border rounded-2xl p-6 animate-enter delay-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Game Levels</p>
                    <h3 class="text-4xl font-bold text-white">{{ number_format($totalGameLevels) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-cyan-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-gamepad text-2xl text-cyan-400"
                        style="filter: drop-shadow(0 0 8px rgba(34, 211, 238, 0.8));"></i>
                </div>
            </div>
            <div class="text-sm font-medium text-gray-500">Game levels available</div>
        </div>
    </div>

    <div class="bg-[#121212] rounded-2xl border border-[#333] p-6 animate-enter delay-300">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold">Recent Registrations</h3>
            <a href="{{ route('admin.manage-users') }}"
                class="text-sm font-bold text-[#ff2a85] hover:text-[#ff99d6] transition-colors flex items-center gap-1">
                View All &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 text-sm uppercase border-b border-[#333]">
                        <th class="pb-3 font-semibold">User</th>
                        <th class="pb-3 font-semibold">Email</th>
                        <th class="pb-3 font-semibold">Children</th>
                        <th class="pb-3 font-semibold">Joined Date</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($recentUsers as $user)
                        <tr class="border-b border-[#222]/40 hover:bg-[#1a1a1a] transition-colors last:border-none">
                            <td class="py-4 flex items-center gap-3">
                                @if ($user->profile)
                                    <img src="{{ asset('storage/' . $user->profile) }}" alt="Profile"
                                        class="w-8 h-8 rounded-full object-cover border border-[#ff2a85]/30">
                                @else
                                    <div
                                        class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold uppercase">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="font-medium text-white">{{ $user->name }}</span>
                            </td>
                            <td class="py-4 text-gray-400">{{ $user->email }}</td>

                            <td class="py-4 text-gray-300">
                                @if ($user->children_count > 0)
                                    <span
                                        class="px-2.5 py-1 rounded-md bg-[#ff2a85]/10 text-[#ff99d6] text-xs font-bold border border-[#ff2a85]/20">
                                        {{ $user->children_count }} Child(ren)
                                    </span>
                                @else
                                    <span class="text-gray-600 text-xs">-</span>
                                @endif
                            </td>

                            <td class="py-4 text-gray-400">
                                {{ $user->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500 font-medium">
                                No recent registered accounts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
