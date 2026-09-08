@extends('layouts.parent')

@section('title', 'Achievements - Fonka')

@section('styles')
    <style>
        /* --- Modern Badge Styling --- */
        .badge-card {
            text-align: center;
            padding: 2.5rem 1.5rem;
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            height: 100%;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .badge-icon-wrapper {
            width: 100px;
            height: 100px;
            margin: 0 auto 1.5rem auto;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            position: relative;
            z-index: 2;
            /* Default background in case DB colors fail */
            background-color: #8b5cf6;
            color: white;
        }

        /* Unlocked State - Premium & Playful */
        .badge-card.unlocked {
            background: linear-gradient(145deg, #ffffff, #fcfaff);
            border: 2px solid #ede9fe;
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.08);
        }

        .badge-card.unlocked:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: #c4b5fd;
            box-shadow: 0 20px 40px rgba(139, 92, 246, 0.15);
        }

        .badge-card.unlocked .badge-icon-wrapper {
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1), inset 0 -5px 15px rgba(0, 0, 0, 0.1);
            border: 4px solid #fff;
        }

        /* Locked State - Frosted Glass Look */
        .badge-card.locked {
            background-color: #f8fafc;
            border: 2px dashed #e2e8f0;
            box-shadow: none;
            opacity: 0.85;
        }

        .badge-card.locked:hover {
            transform: translateY(-3px);
            opacity: 1;
            border-color: #cbd5e1;
        }

        .badge-card.locked .badge-icon-wrapper {
            background: #f1f5f9 !important;
            color: #cbd5e1 !important;
            box-shadow: inset 0 4px 10px rgba(0, 0, 0, 0.05);
            border: 4px solid #fff;
        }

        .badge-card.locked .text-dark {
            color: #64748b !important;
        }

        .lock-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #cbd5e1;
            font-size: 1.2rem;
            background: white;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .progress-custom {
            height: 12px;
            border-radius: 20px;
            background-color: #e2e8f0;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .progress-custom .progress-bar {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            border-radius: 20px;
            transition: width 1s ease-in-out;
        }

        /* --- Dynamic Color Fallbacks --- */
        /* If your DB uses these names, these classes will catch them */
        .bg-blue {
            background-color: #3b82f6 !important;
        }

        .bg-yellow {
            background-color: #eab308 !important;
        }

        .bg-green {
            background-color: #22c55e !important;
        }

        .bg-purple {
            background-color: #a855f7 !important;
        }

        .bg-red {
            background-color: #ef4444 !important;
        }

        .bg-orange {
            background-color: #f97316 !important;
        }

        .bg-pink {
            background-color: #ec4899 !important;
        }
    </style>
@endsection

@section('content')
    @php
        $childAchievements = \App\Models\ChildAchievement::with('achievement')->where('child_id', $child->id)->get();

        $totalBadges = $childAchievements->count();
        $unlockedBadges = $childAchievements->where('is_unlocked', 1)->count();
    @endphp

    <header class="top-navbar">
        <div class="d-flex align-items-center gap-3">

             <div>
                <h2 class="mb-1 fw-black text-slate">Child Achievements</h2>
                <p class="text-muted mb-0"><b>{{ $child->name }}</b> learning milestones.</p>
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-light btn-modern border dropdown-toggle d-flex align-items-center gap-2" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                @php
                    $activeAvatar = \Illuminate\Support\Facades\DB::table('avatars')
                        ->where('id', $child->avatar_id)
                        ->first();
                    $activeAvatarPath = $activeAvatar
                        ? asset($activeAvatar->image)
                        : 'https://ui-avatars.com/api/?name=' .
                            urlencode($child->name) .
                            '&background=8b5cf6&color=fff';
                @endphp

                <img src="{{ $activeAvatarPath }}" class="rounded-circle border" width="24" height="24"
                    style="object-fit: cover;">
                <span class="fw-bold">{{ $child->name }}</span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2"
                style="border-radius: 1rem; min-width: 200px;">
                <li class="dropdown-header text-uppercase fw-800 small pb-2">Select Profile</li>

                @foreach ($children as $c)
                    @php
                        $loopAvatar = \Illuminate\Support\Facades\DB::table('avatars')
                            ->where('id', $c->avatar_id)
                            ->first();
                        $loopAvatarPath = $loopAvatar
                            ? asset($loopAvatar->image)
                            : 'https://ui-avatars.com/api/?name=' .
                                urlencode($c->name) .
                                '&background=8b5cf6&color=fff';
                    @endphp
                    <li>
                        <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $c->id == $child->id ? 'active' : '' }}"
                            href="{{ route('parents.set_child', $c->id) }}">

                            <img src="{{ $loopAvatarPath }}" class="rounded-circle" width="24" height="24"
                                style="object-fit: cover;">
                            <span class="fw-bold">{{ $c->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </header>

    <div class="row g-4 mb-5">
        <div class="col-12 col-md-4">
            <div class="card-custom p-4 d-flex align-items-center justify-content-center gap-3 shadow-sm"
                style="background-color: #FEF08A; border: none; border-radius: 1.5rem;">
                <div class="bg-white text-warning rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 65px; height: 65px; font-size: 2rem;">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div>
                    <h2 class="fw-900 text-dark mb-0">{{ $totalStars ?? 0 }}</h2>
                    <p class="mb-0 fw-bold small text-uppercase" style="color: #854d0e; letter-spacing: 0.5px;">Total Stars
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card-custom p-4 d-flex align-items-center justify-content-center gap-3 shadow-sm"
                style="background-color: #84CC16; border: none; border-radius: 1.5rem;">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 65px; height: 65px; font-size: 2rem; color: #84CC16;">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <div>
                    <h2 class="fw-900 text-white mb-0">{{ $totalPoints ?? 0 }}</h2>
                    <p class="text-white-50 mb-0 fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Total Points
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card-custom p-4 d-flex align-items-center justify-content-center gap-3 shadow-sm"
                style="background-color: #E0F2FE; border: none; border-radius: 1.5rem;">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 65px; height: 65px; font-size: 2rem;">
                    <i class="fa-solid fa-medal"></i>
                </div>
                <div>
                    <h2 class="fw-900 text-dark mb-0">{{ $unlockedBadges }} / {{ $totalBadges }}</h2>
                    <p class="mb-0 fw-bold small text-uppercase" style="color: #0369a1; letter-spacing: 0.5px;">Badges
                        Unlocked</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-900 mb-4 text-dark"><i class="fa-solid fa-shield-halved text-primary me-2"></i> Earned Badges</h4>
    <div class="row g-4 mb-5">
        @forelse($childAchievements as $ca)
            @php
                $achievement = $ca->achievement;
                $isUnlocked = $ca->is_unlocked == 1;
                $requiredValue = $achievement->required_value > 0 ? $achievement->required_value : 1;
                $percentage = min(100, ($ca->progress / $requiredValue) * 100);

                $bgColorClass = $achievement->bg_color ?? 'bg-purple';
                if (
                    !in_array($bgColorClass, [
                        'bg-blue',
                        'bg-yellow',
                        'bg-green',
                        'bg-purple',
                        'bg-red',
                        'bg-orange',
                        'bg-pink',
                        'bg-primary',
                        'bg-success',
                        'bg-warning',
                        'bg-danger',
                        'bg-info',
                    ])
                ) {
                    $bgColorClass = 'bg-purple';
                }
            @endphp

            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="card-custom badge-card {{ $isUnlocked ? 'unlocked' : 'locked' }}">

                    @if (!$isUnlocked)
                        <div class="lock-icon">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                    @endif

                    <div class="badge-icon-wrapper {{ $isUnlocked ? $bgColorClass . ' text-white' : '' }}">
                        <i class="fa-solid {{ $achievement->icon ?? 'fa-star' }}"></i>
                    </div>

                    <h5 class="fw-bold text-dark mb-2"
                        style="font-size: 1.15rem; line-height: 1.3; justify-content: center; display: flex; align-items: center; gap: 0.25rem;">
                        {{ $achievement->title }}</h5>
                    <p class="text-muted mb-0 px-2"
                        style="font-size: 0.85rem; font-weight: 500; justify-content: center; display: flex; align-items: center; gap: 0.25rem;">
                        {{ $achievement->description }}</p>

                    <div class="mt-4 pt-3 border-top border-light">
                        @if ($isUnlocked)
                            <div class="d-inline-block bg-success bg-opacity-10 px-4 py-2 rounded-pill shadow-sm">
                                <span class="fw-bold text-success small text-uppercase" style="letter-spacing: 1px;"><i
                                        class="fa-solid fa-check-circle me-1"></i> Unlocked</span>
                            </div>
                        @else
                            <div class="progress-custom mb-2">
                                <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                            </div>
                            <small class="text-muted fw-bold d-block text-uppercase"
                                style="letter-spacing: 0.5px;">{{ $ca->progress }} / {{ $achievement->required_value }} to
                                unlock</small>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="bg-white p-5 rounded-4 shadow-sm border" style="border-color: #f1f5f9;">
                    <i class="fa-solid fa-medal text-muted opacity-25 mb-3" style="font-size: 4rem;"></i>
                    <h5 class="fw-bold text-dark">No achievements found</h5>
                    <p class="text-muted">Start playing learning games to earn badges!</p>
                </div>
            </div>
        @endforelse
    </div>

@endsection
