@extends('layouts.parent')

@section('title', 'Child Profile - Fonka')

@section('styles')
    <style>
        /* Modern Palette & Variables */
        :root {
            --primary: #8b5cf6;
            --primary-glow: rgba(139, 92, 246, 0.15);
            --card-border: #f1f5f9;
            --input-bg: #f8fafc;
            --text-muted: #64748b;
        }

        .content-wrapper {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Profile Hero Section */
        .profile-hero {
            background: white;
            border-radius: 2rem;
            border: 1px solid var(--card-border);
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        }

        .avatar-main {
            width: 140px;
            height: 140px;
            border-radius: 2.5rem;
            object-fit: cover;
            border: 6px solid #fff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .glass-card {
            background: white;
            border-radius: 1.5rem;
            border: 1px solid rgb(191, 191, 191);
            padding: 2rem;
            height: 100%;
            transition: 0.3s;
        }

        .glass-card:hover {
            border-color: var(--primary);
        }

        .form-control-modern {
            border-radius: 1rem;
            padding: 0.85rem 1.2rem;
            border: 2px solid rgb(191, 191, 191);
            background: var(--input-bg);
            font-weight: 600;
            transition: 0.3s;
        }

        .form-control-modern:focus {
            background: white;
            border-color: var(--primary);
            outline: none;
        }

        .pref-item {
            background: var(--input-bg);
            border-radius: 1.25rem;
            padding: 1.2rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-modern {
            border-radius: 1rem;
            padding: 0.8rem 2rem;
            font-weight: 700;
            transition: 0.3s;
        }

        .btn-primary-modern {
            background: var(--primary);
            color: white;
            border: none;
        }

        .btn-primary-modern:hover {
            transform: translateY(-3px);
            color: white;
            background-color: #4F7A0D;
        }

        .stat-pill {
            background: #fff;
            border: 1px solid var(--card-border);
            padding: 0.5rem 1.2rem;
            border-radius: 50rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h2 class="mb-1 fw-black text-slate">Child Profile</h2>
                <p class="text-muted mb-0">Manage settings for <b>{{ $child->name }}</b></p>
            </div>

            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-light btn-modern border dropdown-toggle d-flex align-items-center gap-2"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
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

                <a href="{{ route('parents.create') }}" class="btn btn-primary-modern btn-modern">
                    <i class="fa-solid fa-plus me-2"></i>Add New
                </a>
            </div>
        </div>

        <div class="glass-card profile-hero shadow-sm position-relative overflow-hidden">
            <div class="row align-items-center position-relative z-1">

                <div class="col-12 col-md-auto text-center text-md-start mb-4 mb-md-0">
                    @php
                        $avatar = \Illuminate\Support\Facades\DB::table('avatars')
                            ->where('id', $child->avatar_id)
                            ->first();
                        $avatarPath = $avatar
                            ? asset($avatar->image)
                            : 'https://ui-avatars.com/api/?name=' .
                                urlencode($child->name) .
                                '&background=8b5cf6&color=fff&size=140';
                    @endphp
                    <img src="{{ $avatarPath }}" class="avatar-main shadow-lg" alt="Avatar">
                </div>

                <div class="col-12 col-md text-center text-md-start">

                    <div
                        class="d-flex align-items-center justify-content-center justify-content-md-start gap-3 mb-3 flex-wrap">
                        <h1 class="fw-900 mb-0 text-dark">{{ $child->name }}</h1>

                        <div class="stat-pill text-primary" style="background-color: #ede9fe; border-color: #ddd6fe;">
                            <i class="fa-solid fa-bolt"></i> Level: {{ $child->current_level ?? 1 }}
                        </div>

                        <div class="stat-pill text-success" style="background-color: #d1fae5; border-color: #a7f3d0;">
                            <i class="fa-solid fa-check-double"></i> Completed levels: {{ $completedLevels ?? 0 }}
                        </div>

                        <div class="stat-pill text-warning" style="background-color: #fef3c7; border-color: #fde68a;">
                            <i class="fa-solid fa-bullseye"></i> {{ $child->total_point ?? 0 }} pts
                        </div>
                    </div>

                    <div class="d-flex justify-content-center justify-content-md-start gap-4">
                        <div>
                            <small class="text-muted d-block text-uppercase fw-800"
                                style="font-size: 0.65rem; letter-spacing: 0.5px;">Active Time</small>
                            <span class="fw-bold text-dark">
                                <i class="fa-regular fa-clock text-primary me-1 opacity-75"></i> 12.5 Hours
                            </span>
                        </div>
                        <div class="border-start ps-4">
                            <small class="text-muted d-block text-uppercase fw-800"
                                style="font-size: 0.65rem; letter-spacing: 0.5px;">Age</small>
                            <span class="fw-bold text-dark">
                                <i class="fa-solid fa-cake-candles text-info me-1 opacity-75"></i> {{ $child->age }}
                                Years Old
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="glass-card shadow-sm">
                    <h5 class="fw-bold mb-4">Child Information</h5>

                    <form action="{{ route('parents.child.update', $child->id) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="fw-bold text-muted ms-2">Full Display Name</label>
                                <input type="text" name="name" class="form-control form-control-modern"
                                    value="{{ old('name', $child->name) }}" required>
                                @error('name')
                                    <small class="text-danger ms-2">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="fw-bold text-muted ms-2">Current Age</label>
                                <input type="number" name="age" class="form-control form-control-modern"
                                    value="{{ old('age', $child->age) }}" required min="1">
                                @error('age')
                                    <small class="text-danger ms-2">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary-modern btn-modern w-100 w-md-auto">Save
                                Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="glass-card shadow-sm bg-light"
                    style="border-color: #f87171; background-color: #f8f9fa !important;">
                    <div class=" pt-2">
                        <h6 class="text-danger fw-bold mb-3">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>DANGER ZONE
                        </h6>

                        <form action="{{ route('parents.child.reset', $child->id) }}" method="POST"
                            id="reset-progress-form">
                            @csrf
                            <button type="button" class="btn btn-outline-warning btn-modern fw-bold w-100 mb-3"
                                onclick="confirmReset()">
                                <i class="fa-solid fa-rotate-left me-2"></i> Reset All Progress
                            </button>
                        </form>

                        <form action="{{ route('parents.child.destroy', $child->id) }}" method="POST"
                            id="delete-child-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-outline-danger btn-modern fw-bold w-100"
                                onclick="confirmDelete()">
                                <i class="fa-solid fa-trash me-2"></i> Delete Profile
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Are you absolutely sure?',
                text: "This action cannot be undone. All of {{ $child->name }}'s progress, points, and settings will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545', // Bootstrap Danger Red
                cancelButtonColor: '#6c757d', // Bootstrap Secondary Gray
                confirmButtonText: '<i class="fa-solid fa-trash"></i> Yes, delete profile!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    document.getElementById('delete-child-form').submit();
                }
            })
        }

        function confirmReset() {
            Swal.fire({
                title: 'Reset Progress?',
                text: "This will reset all completed levels back to 0 for {{ $child->name }}, but their points and score will be kept.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b', // Warning Amber
                cancelButtonColor: '#6c757d', // Secondary Gray
                confirmButtonText: '<i class="fa-solid fa-rotate-left"></i> Yes, reset it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reset-progress-form').submit();
                }
            });
        }
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#8b5cf6',
                timer: 2500,
                showConfirmButton: false
            });
        @endif
    </script>
@endsection
