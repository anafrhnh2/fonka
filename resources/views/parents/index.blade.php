@extends('layouts.parent')

@section('title', 'Parent Dashboard - Fonka')

@section('styles')
<style>
    :root {
        --primary: #84CC16;       /* Lime Green */
        --primary-light: #ede9fe; /* Light Purple */
        --purple-main: #8b5cf6;   /* Deep Purple */
        --card-bg: #ffffff;
        --border-soft: #f1f5f9;
    }

    .card-custom { 
        background-color: var(--card-bg); 
        border: 1px solid var(--border-soft); 
        border-radius: 1.5rem; 
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02); 
        transition: transform 0.3s ease, box-shadow 0.3s ease; 
        height: 100%; 
    }
    
    .card-custom:hover { 
        transform: translateY(-4px); 
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.04); 
    }

    .fw-black { font-weight: 900; }
    .text-slate { color: #334155; }

    .icon-box { 
        width: 55px; height: 55px; 
        border-radius: 1rem; 
        display: flex; align-items: center; justify-content: center; 
        font-size: 1.5rem; 
    }
    
    .theme-brand { background-color: var(--primary-light); color: var(--primary); }
    .theme-blue { background-color: #e0e7ff; color: #6366f1; }
    .theme-orange { background-color: #ffedd5; color: #f97316; }
    .theme-purple { background-color: #fae8ff; color: #d946ef; }

    .progress-track { 
        height: 12px; border-radius: 10px; 
        background-color: var(--border-soft); 
        margin-top: 8px; overflow: hidden; 
    }
    
    .progress-fill { 
        height: 100%; border-radius: 10px; 
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1); 
    }
    
    .fill-lime { background-color: var(--primary) !important; }
    .fill-purple { background-color: var(--purple-main) !important; }
    .fill-blue { background-color: #3b82f6 !important; }
    .fill-orange { background-color: #f97316 !important; }

    .activity-timeline { 
        border-left: 2px solid var(--border-soft); 
        margin-left: 1rem; padding-left: 1.5rem; position: relative; 
    }
    .activity-item { margin-bottom: 1.5rem; position: relative; }
    .activity-item:last-child { margin-bottom: 0; }
    
    .activity-dot { 
        width: 16px; height: 16px; 
        background: var(--primary); 
        border: 3px solid #fff; border-radius: 50%; 
        position: absolute; left: -1.95rem; top: 4px; 
        box-shadow: 0 0 0 4px var(--primary-light); 
    }
    .activity-dot.dot-purple {
        background: var(--purple-main);
        box-shadow: 0 0 0 4px #f3e8ff;
    }
</style>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-black text-slate">Welcome back, {{ Auth::user()->name ?? 'Parent Account' }}</h2>
            <p class="text-muted mb-0">Monitoring <strong class="text-slate">{{ $child->name }}'s</strong> progress • 
                <span class="badge rounded-pill ms-1" style="background-color: var(--primary-light); color: var(--purple-main);">
                    Level {{ $child->current_level }}
                </span>
            </p>
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
                        : 'https://ui-avatars.com/api/?name=' . urlencode($child->name) . '&background=8b5cf6&color=fff';
                @endphp

                <img src="{{ $activeAvatarPath }}" class="rounded-circle border" width="24" height="24" style="object-fit: cover;">
                {{ $child->name }}
            </button>

            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" style="border-radius: 1rem; min-width: 200px;">
                <li class="dropdown-header text-uppercase fw-800 small pb-2">Select Profile</li>

                @foreach ($children as $c)
                    @php
                        $loopAvatar = \Illuminate\Support\Facades\DB::table('avatars')
                            ->where('id', $c->avatar_id)
                            ->first();
                        $loopAvatarPath = $loopAvatar
                            ? asset($loopAvatar->image)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($c->name) . '&background=8b5cf6&color=fff';
                    @endphp
                    <li>
                        <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $c->id == $child->id ? 'active' : '' }}"
                            href="{{ route('parents.set_child', $c->id) }}">
                            <img src="{{ $loopAvatarPath }}" class="rounded-circle" width="24" height="24" style="object-fit: cover;">
                            <span class="fw-bold">{{ $c->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-custom p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted fw-bold mb-1" style="font-size: 0.9rem;">Overall Mastery</p>
                        <h3 class="fw-black text-slate mb-0">{{ $overallProgress }}%</h3>
                    </div>
                    <div class="icon-box theme-brand"><i class="fa-solid fa-chart-pie"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-custom p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted fw-bold mb-1" style="font-size: 0.9rem;">Lessons Passed</p>
                        <h3 class="fw-black text-slate mb-0">{{ $completedLessonsCount }}</h3>
                    </div>
                    <div class="icon-box theme-blue"><i class="fa-solid fa-book-open"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-custom p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted fw-bold mb-1" style="font-size: 0.9rem;">Total Points</p>
                        <h3 class="fw-black text-slate mb-0">{{ number_format($child->total_point) }}</h3>
                    </div>
                    <div class="icon-box theme-orange"><i class="fa-solid fa-fire"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card-custom p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted fw-bold mb-1" style="font-size: 0.9rem;">Child Age</p>
                        <h3 class="fw-black text-slate mb-0">{{ $child->age }} Years</h3>
                    </div>
                    <div class="icon-box theme-purple"><i class="fa-solid fa-child"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-7">
            <div class="card-custom p-4">
                <!-- PENAMBAHAN TARIKH MINGGUAN DI SINI -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold text-slate mb-0">Weekly Learning Activity</h5>
                    <span class="badge border bg-light text-muted rounded-pill px-3 py-2 small fw-bold">
                        {{ now()->subDays(6)->translatedFormat('d M') }} - {{ now()->translatedFormat('d M Y') }}
                    </span>
                </div>
                <div style="height: 300px;">
                    <canvas id="progressChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card-custom p-4">
                <h5 class="fw-bold text-slate mb-4">Skills Overview</h5>

                @php
                    $skillColors = [
                        'Letter Recognition' => 'fill-lime',
                        'Phonics Sounds'     => 'fill-purple',
                        'Word Blending'      => 'fill-blue',
                        'Sentence Reading'   => 'fill-orange'
                    ];
                @endphp

                @foreach($skillPercentages as $name => $percent)
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-bold text-slate">{{ $name }}</span>
                        <span class="text-muted fw-bold">{{ $percent }}%</span>
                    </div>
                    <div class="progress-track">
                        <div class="progress-fill {{ $skillColors[$name] ?? 'fill-lime' }}" style="width: {{ $percent }}%;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card-custom p-4">
                <h5 class="fw-bold text-slate mb-4">Recent Activity</h5>
                <div class="activity-timeline">
                    @forelse($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="activity-dot {{ $loop->even ? 'dot-purple' : '' }}"></div>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold text-slate mb-1">Completed Level {{ $activity->game_level_id }}</h6>
                                <p class="text-muted small mb-0">Earned {{ $activity->stars_earned }} stars with a score of {{ $activity->score }}.</p>
                            </div>
                            <span class="badge border text-muted mt-2 mt-md-0 rounded-pill px-3 py-2" style="background: #f8fafc;">
                                {{ $activity->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No activities recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('progressChart');
        if(!ctx) return;
        
        const context = ctx.getContext('2d');
        let gradient = context.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, '#84CC16'); 
        gradient.addColorStop(1, '#d9f99d'); 

        // 🔥 LOGIK UTAMA: Bina senarai tarikh 7 hari lepas secara dinamik sepadan dengan array controller
        const targetDates = [];
        const monthNames = ["Jan", "Feb", "Mac", "Apr", "Mei", "Jun", "Jul", "Ogo", "Sep", "Okt", "Nov", "Dis"];
        
        for (let i = 6; i >= 0; i--) {
            const d = new Date();
            d.setDate(d.getDate() - i);
            targetDates.push(`${d.getDate()} ${monthNames[d.getMonth()]}`);
        }

        // Gabungkan nama hari dari controller dengan tarikh baharu (Contoh: "Mon (15 Jun)")
        const baseDays = {!! json_encode($days) !!};
        const finalLabels = baseDays.map((day, idx) => `${day} (${targetDates[idx]})`);

        new Chart(context, {
            type: 'bar',
            data: {
                labels: finalLabels, // Menggunakan label gabungan hari + tarikh
                datasets: [{
                    label: 'Lessons Completed',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: gradient,
                    borderRadius: 8,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f1f5f9', drawBorder: false }, 
                        ticks: { stepSize: 1, color: '#64748b' } 
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { color: '#64748b', font: { weight: 'bold', size: 11 } } 
                    }
                }
            }
        });
    });
</script>
@endsection