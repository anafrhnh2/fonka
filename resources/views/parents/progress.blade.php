@extends('layouts.parent')

@section('title', 'Learning Progress - Fonka')

@section('styles')
    <style>
        /* --- CSS Specific to the Learning Progress Table --- */
        .table-custom {
            margin-bottom: 0;
        }

        .table-custom thead th {
            background-color: #f8fafc;
            color: var(--text-muted);
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }

        .table-custom tbody td {
            padding: 1.25rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
            color: var(--text-main);
        }

        .table-custom tbody tr:hover {
            background-color: #f8fafc;
            transition: background-color 0.2s ease;
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        .level-badge {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
        }

        /* Custom Toggle Navigation */
        .progress-nav {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            display: inline-flex;
            padding: 5px;
            margin-bottom: 1.5rem;
        }

        .progress-nav .btn {
            border-radius: 50px;
            font-weight: 700;
            padding: 0.5rem 1.5rem;
            color: var(--text-muted);
            transition: all 0.3s ease;
        }

        .progress-nav .btn.active {
            background-color: var(--primary, #0d6efd);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('content')

    <header class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <div>
                <h2 class="mb-1 fw-black text-slate">Learning Progress</h2>
                <p class="text-muted mb-0"><b>{{ $child->name }}</b> learning progress</p>
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
    </header>

    <div class="progress-nav">
        <button class="btn active" id="btn-game" onclick="switchTab('game')">
            <i class="fa-solid fa-gamepad me-2"></i> Game Progress
        </button>
        <button class="btn" id="btn-read" onclick="switchTab('read')">
            <i class="fa-solid fa-book-open me-2"></i> Read Progress
        </button>
    </div>

    <div class="card-custom overflow-hidden">

        <div id="game-progress-container" class="table-responsive">
            <table class="table table-custom table-borderless">
                <thead>
                    <tr>
                        <th class="text-center" width="80">Level</th>
                        <th>Topic</th>
                        <th class="text-center">Stars Earned</th>
                        <th class="text-center">Score</th>
                        <th class="text-center">Mistakes Count</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($levels as $level)
                        @php
                            $modProgress = $progress[$level->id] ?? null;
                            $stars = $modProgress ? $modProgress->stars_earned : 0;
                            $score = $modProgress ? $modProgress->score : 0;
                            $maxStars = $level->max_stars ?? 3;

                            $levelWeaknesses = collect($weaknesses ?? [])->where('game_level_id', $level->id);
                            $totalMistakes = $levelWeaknesses->sum('wrong_count');
                        @endphp

                        <tr>
                            <td class="text-center">
                                <div
                                    class="level-badge {{ $modProgress ? 'bg-primary text-white shadow-sm' : 'bg-light text-muted' }} mx-auto">
                                    {{ $level->level_number }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if ($level->image)
                                        <img src="{{ asset('images/games/thumbnail/' . $level->image) }}"
                                            alt="{{ $level->name }}" class="rounded" width="40" height="40"
                                            style="object-fit: cover;">
                                    @endif
                                    <p class="mb-0 fs-5">{{ $level->name }}</p>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    @for ($i = 0; $i < $stars; $i++)
                                        <i class="fa-solid fa-star text-warning fs-5"></i>
                                    @endfor
                                    @for ($i = 0; $i < $maxStars - $stars; $i++)
                                        <i class="fa-regular fa-star text-black-50 opacity-25 fs-5"></i>
                                    @endfor
                                </div>
                            </td>
                            <td class="text-center">
                                <span
                                    class="badge {{ $score > 0 ? 'bg-success text-success bg-opacity-10 border border-success border-opacity-25' : 'bg-light text-muted' }} px-3 py-2 rounded-pill fs-6">
                                    {{ $score > 0 ? $score . ' Pts' : '-' }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if ($totalMistakes > 0)
                                    <button type="button" class="btn btn-sm btn-danger rounded-pill px-3"
                                        data-bs-toggle="modal" data-bs-target="#mistakeModal{{ $level->id }}">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $totalMistakes }}
                                    </button>

                                    <div class="modal fade" id="mistakeModal{{ $level->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Level
                                                        {{ $level->level_number }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6 class="text-muted mb-3 small text-uppercase fw-bold">List of
                                                        Mistakes</h6>
                                                    <div class="list-group list-group-flush">

                                                        @if ($level->id == 4)
                                                            @php
                                                                $terlepas = $levelWeaknesses->where(
                                                                    'item_name',
                                                                    'Terlepas',
                                                                );
                                                                $others = $levelWeaknesses->where(
                                                                    'item_name',
                                                                    '!=',
                                                                    'Terlepas',
                                                                );
                                                            @endphp

                                                            @if ($terlepas->count() > 0)
                                                                <div class="list-group-item border-0 ps-0">
                                                                    <span class="text-danger fw-bold">Missed:</span>
                                                                    {{ $terlepas->pluck('the_question')->implode(', ') }}
                                                                </div>
                                                            @endif

                                                            @foreach ($others as $w)
                                                                <div class="list-group-item border-0 ps-0">

                                                                    Question: <strong>{{ $w->the_question }}</strong> |
                                                                    Child answered: <span
                                                                        class="text-danger">{{ $w->item_name }}</span>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            @foreach ($levelWeaknesses as $w)
                                                                @php
                                                                    $name = $w->item_name;
                                                                    if ($level->id == 3) {
                                                                        $name =
                                                                            strtolower($name) == 'lembut'
                                                                                ? 'E-pepet'
                                                                                : (strtolower($name) == 'jelas'
                                                                                    ? 'E-taling'
                                                                                    : $name);
                                                                    }
                                                                @endphp
                                                                <div class="list-group-item border-0 ps-0">

                                                                    Question: <strong>{{ $w->the_question }}</strong> |
                                                                    Child answered: <span
                                                                        class="text-danger">{{ $name }}</span>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($stars > 0)
                                    <span class="badge bg-primary px-3 py-2 rounded-pill">Completed</span>
                                @elseif($modProgress && $score > 0)
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">In Progress</span>
                                @else
                                    <span class="badge bg-light text-muted px-3 py-2 rounded-pill border">Locked</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-5 text-muted">No levels found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="read-progress-container" class="table-responsive d-none">
            <table class="table table-custom table-borderless">
                <thead>
                    <tr>
                        <th class="text-center" width="80">Module</th>
                        <th>Reading Topic</th>
                        <th class="text-center" width="250">Progress</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($readingModules as $module)
                        @php
                            $rProgress = $readProgresses[$module->id] ?? null;
                            $percentage = $rProgress ? $rProgress->progress_percentage : 0;
                            $isCompleted = $rProgress ? $rProgress->is_completed : 0;
                        @endphp

                        <tr>
                            <td>
                                <div
                                    class="level-badge {{ $percentage > 0 ? 'bg-success text-white shadow-sm' : 'bg-light text-muted' }} mx-auto">
                                    {{ $module->module_number }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if ($module->image)
                                        <img src="{{ asset('images/games/thumbnail/' . $module->image) }}"
                                            alt="{{ $module->title }}" class="rounded" width="40" height="40"
                                            style="object-fit: cover;">
                                    @endif
                                    <p class="mb-0 fs-5">{{ $module->title }}</p>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="progress"
                                    style="height: 10px; border-radius: 10px; background-color: #e2e8f0;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted d-block mt-1 fw-bold">{{ $percentage }}%</small>
                            </td>
                            <td class="text-center">
                                @if ($isCompleted)
                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                        <i class="fa-solid fa-check-double me-1"></i> Finished
                                    </span>
                                @elseif($percentage > 0)
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                        <i class="fa-solid fa-book-open-reader me-1"></i> Reading
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted px-3 py-2 rounded-pill border">
                                        <i class="fa-solid fa-lock me-1"></i> Not Started
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center p-5 text-muted">
                                <div class="fs-1 mb-3"><i class="fa-solid fa-book opacity-25"></i></div>
                                <h4>No reading modules found</h4>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


    </div>

@endsection

@section('scripts')
    <script>
        function switchTab(tab) {
            const gameBtn = document.getElementById('btn-game');
            const readBtn = document.getElementById('btn-read');

            const gameContainer = document.getElementById('game-progress-container');
            const readContainer = document.getElementById('read-progress-container');


            [gameBtn, readBtn].forEach(btn => btn.classList.remove('active'));
            [gameContainer, readContainer].forEach(cont => cont.classList.add('d-none'));

            if (tab === 'game') {
                gameBtn.classList.add('active');
                gameContainer.classList.remove('d-none');
            } else if (tab === 'read') {
                readBtn.classList.add('active');
                readContainer.classList.remove('d-none');
            }
        }
    </script>
@endsection
