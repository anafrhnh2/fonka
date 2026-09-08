@extends('layouts.parent')

@section('title', 'Official Report - Fonka')

@section('styles')
    <style>
        /* --- CSS Specific to the Report Page --- */
        .insight-box {
            border-left: 4px solid var(--primary);
            background-color: #f8fafc;
            padding: 1.5rem;
            border-radius: 0 1rem 1rem 0;
            margin-bottom: 1rem;
        }

        .insight-box.warning {
            border-left-color: #f97316;
        }

        /* --- PRINT STYLES (Hides Sidebar and UI when printing) --- */
        @media print {
            body {
                background-color: #fff;
            }

            .sidebar,
            .top-navbar,
            .btn,
            .hamburger,
            .sidebar-overlay {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }

            .card-custom {
                box-shadow: none !important;
                border: 1px solid #e2e8f0 !important;
                break-inside: avoid;
            }

            .print-header {
                display: block !important;
                margin-bottom: 2rem;
                text-align: center;
            }
        }

        .print-header {
            display: none;
        }
    </style>
@endsection

@section('content')

    <header class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <button class="hamburger" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div>
                <h2 class="fw-bold mb-1">Report</h2>
                {{-- <p class="text-muted mb-0">Manage settings for <b>{{ $child->name }}</b></p> --}}
            </div>
        </div>
        <div class="d-flex gap-2">
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
                    {{ $child->name }}
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
            <button onclick="window.print()" class="btn btn-primary fw-bold rounded-pill shadow-sm px-4"
                style="background-color: red; border:none;">
                <i class="fa-solid fa-print me-2"></i> Print Report
            </button>
        </div>
    </header>

    <div class="print-header">
        <h2 class="fw-black mb-1" style="color: var(--primary);"><i class="fa-solid fa-puzzle-piece"></i> Fonka Learning
            System</h2>
        <p class="text-muted">Official Student Progress Report</p>
        <hr class="mb-4">
    </div>

    <div class="row g-4">
        <div class="glass-card profile-hero shadow-sm position-relative overflow-hidden">
    <div class="position-absolute top-0 end-0 p-4 opacity-10 d-none d-md-block" style="transform: translate(15%, -20%); pointer-events: none;">
        <i class="fa-solid fa-medal text-primary" style="font-size: 10rem;"></i>
    </div>

    <div class="row align-items-center position-relative z-1">
        
        <div class="col-12 col-md-auto text-center text-md-start mb-4 mb-md-0">
            @php
                $avatar = \Illuminate\Support\Facades\DB::table('avatars')
                    ->where('id', $child->avatar_id)
                    ->first();
                $avatarPath = $avatar
                    ? asset($avatar->image)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($child->name) . '&background=8b5cf6&color=fff&size=140';
            @endphp
            <img src="{{ $avatarPath }}" class="avatar-main shadow-lg" alt="Avatar">
        </div>

        <div class="col-12 col-md text-center text-md-start">
            
            <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-3 mb-3 flex-wrap">
                <h1 class="fw-900 mb-0 text-dark">{{ $child->name }}</h1>
            </div>

            <div class="d-flex justify-content-center justify-content-md-start gap-4">
                <div>
                    <small class="text-muted d-block text-uppercase fw-800" style="font-size: 0.65rem; letter-spacing: 0.5px;">Active Time</small>
                    <span class="fw-bold text-dark">
                        <i class="fa-regular fa-clock text-primary me-1 opacity-75"></i> 12.5 Hours
                    </span>
                </div>
                <div class="border-start ps-4">
                    <small class="text-muted d-block text-uppercase fw-800" style="font-size: 0.65rem; letter-spacing: 0.5px;">Age</small>
                    <span class="fw-bold text-dark">
                        <i class="fa-solid fa-cake-candles text-info me-1 opacity-75"></i> {{ $child->age }} Years Old
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-auto mt-4 mt-xl-0 text-center text-xl-end">
            <div class="p-3 rounded-4 bg-light" style="border: 2px dashed #cbd5e1;">
                <p class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                    <i class="fa-regular fa-calendar-check text-success me-1"></i> Report Generated
                </p>
                <h5 class="fw-900 text-dark mb-0">{{ now()->format('d F Y') }}</h5>
            </div>
        </div>

    </div>
</div>

        <div class="col-12">
            <h5 class="fw-bold mb-3 mt-2"><i class="fa-solid fa-chart-simple text-secondary me-2"></i> Performance Summary
            </h5>
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="card-custom p-4 text-center">
                        <i class="fa-solid fa-gamepad text-primary fs-2 mb-2"></i>
                        <h3 class="fw-black text-dark mb-0">{{ $completedLevels }}</h3>
                        <p class="text-muted small mb-0 fw-bold">Completed Game Levels</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card-custom p-4 text-center">
                        <i class="fa-solid fa-star text-warning fs-2 mb-2"></i>
                        <h3 class="fw-black text-dark mb-0">{{ $totalStars }}</h3>
                        <p class="text-muted small mb-0 fw-bold">Total Stars Earned</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card-custom p-4 text-center">
                        <i class="fa-solid fa-bullseye text-danger fs-2 mb-2"></i>
                        <h3 class="fw-black text-dark mb-0">{{ $totalScore }}</h3>
                        <p class="text-muted small mb-0 fw-bold">Total Points</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card-custom p-4 text-center">
                        <i class="fa-solid fa-book-open text-success fs-2 mb-2"></i>
                        <h3 class="fw-black text-dark mb-0">{{ $completedReadingModules }}</h3>
                        <p class="text-muted small mb-0 fw-bold">Completed Modules</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold mb-4"><i class="fa-solid fa-lightbulb text-warning me-2"></i> Learning Insights</h5>

                <div class="insight-box">
                    <h6 class="fw-bold text-dark"><i class="fa-solid fa-thumbs-up text-success me-2"></i> Strengths</h6>
                    <p class="text-muted mb-0 small">{{ $child->name ?? 'The student' }} has shown excellent progress in
                        <strong>Letter Recognition</strong> and <strong>Phonics Sounds</strong>. Accuracy in identifying
                        vowels is very high (95%).</p>
                </div>

                <div class="insight-box warning">
                    <h6 class="fw-bold text-dark"><i class="fa-solid fa-seedling text-warning me-2"></i> Areas for Growth
                    </h6>
                    <p class="text-muted mb-0 small">Slight difficulty detected in <strong>Word Blending (KV + KV)</strong>.
                        Struggles occasionally with blending consonants like 'B' and 'D'. We recommend re-playing Level 6
                        and Level 8.</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold mb-4"><i class="fa-solid fa-clock-rotate-left text-info me-2"></i> Recent Sessions</h5>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead class="border-bottom">
                            <tr class="text-muted small text-uppercase">
                                <th>Date</th>
                                <th>Level</th>
                                <th class="text-end">Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $activity)
                                <tr>
                                    <td class="fw-bold text-dark small">{{ $activity->updated_at->format('d M') }}</td>
                                    <td class="text-muted small">Level {{ $activity->game_level_id }}</td>
                                    <td class="text-end">
                                        @if ($activity->stars_earned == 3)
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">Excellent</span>
                                        @elseif($activity->stars_earned > 0)
                                            <span
                                                class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1">Good</span>
                                        @else
                                            <span
                                                class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1">Tried</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted small py-3">No recent activities
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
@endsection
