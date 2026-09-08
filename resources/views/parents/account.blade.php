@extends('layouts.parent')

@section('title', 'My Account - Fonka')

@section('styles')
<style>
    .card-custom { background-color: var(--card-bg); border: none; border-radius: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .form-control-custom { border-radius: 12px; padding: 0.8rem 1.2rem; border: 2px solid #e2e8f0; background-color: #f8fafc; font-weight: 600; transition: all 0.3s ease; }
    .form-control-custom:focus { border-color: var(--primary); background-color: #fff; box-shadow: 0 0 0 4px var(--primary-light); outline: none; }
    .form-label-custom { font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .btn-purple { background-color: var(--primary); color: white; border-radius: 12px; font-weight: 700; padding: 0.6rem 1.5rem; transition: all 0.3s ease; border: none; }
    .btn-purple:hover { background-color: #7c3aed; color: white; transform: translateY(-2px); }
    
    /* Profile Upload Styles */
    .profile-edit-container { position: relative; display: inline-block; margin-bottom: 1.5rem; }
    .profile-preview { width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary-light); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .profile-upload-btn { position: absolute; bottom: 0; right: 0; width: 40px; height: 40px; border-radius: 50%; background-color: var(--primary); color: white; display: flex; align-items: center; justify-content: center; border: 3px solid white; cursor: pointer; transition: all 0.2s; }
    .profile-upload-btn:hover { transform: scale(1.1); background-color: #7c3aed; }
</style>
@endsection

@section('content')
    <header class="top-navbar mb-4">
        <div class="d-flex align-items-center gap-3">
             <div>
                <h2 class="fw-bold mb-1">My Account</h2>
                <p class="text-muted mb-0">Manage your personal information.</p>
            </div>
        </div>
    </header>

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold mb-4"><i class="fa-solid fa-id-card text-primary me-2"></i> Profile Information</h5>
                
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="text-center mb-4">
                        <div class="profile-edit-container">
                            @if(Auth::user()->profile)
                                <img src="{{ asset('storage/' . Auth::user()->profile) }}" alt="Profile" class="profile-preview" id="profilePreviewImage">
                            @else
                                <img src="https://ui-profiles.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ede9fe&color=8b5cf6&size=130" alt="Profile" class="profile-preview" id="profilePreviewImage">
                            @endif
                            <label for="profileUpload" class="profile-upload-btn" title="Change Picture">
                                <i class="fa-solid fa-camera"></i>
                            </label>
                            <input type="file" id="profileUpload" name="profile" class="d-none" accept="image/*" onchange="previewProfile(event)">
                        </div>
                        @error('profile') <span class="text-danger small fw-bold d-block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Full Name</label>
                        <input type="text" class="form-control-custom w-100" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                        @error('name') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Email Address</label>
                        <input type="email" class="form-control-custom w-100" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-purple"><i class="fa-solid fa-floppy-disk me-2"></i> Save Profile</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold mb-4"><i class="fa-solid fa-shield-halved text-success me-2"></i> Update Password</h5>
                <p class="text-muted small mb-4">Ensure your account is using a long, random password to stay secure.</p>

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label-custom">Current Password</label>
                        <input type="password" class="form-control-custom w-100" name="current_password" required>
                        @error('current_password', 'updatePassword') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">New Password</label>
                        <input type="password" class="form-control-custom w-100" name="password" required>
                        @error('password', 'updatePassword') <span class="text-danger small fw-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Confirm New Password</label>
                        <input type="password" class="form-control-custom w-100" name="password_confirmation" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-dark rounded-3 fw-bold px-4"><i class="fa-solid fa-key me-2"></i> Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Profile Preview Function
    function previewProfile(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('profilePreviewImage');
            output.src = reader.result;
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    // Trigger SweetAlert on Profile Success
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#8b5cf6', // Matches your purple theme
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    // Trigger SweetAlert on Password Success (Uses Laravel's default 'status' session variable)
    @if(session('status') === 'password-updated')
        Swal.fire({
            icon: 'success',
            title: 'Password Updated!',
            text: "Your password has been successfully changed.",
            confirmButtonColor: '#10b981', // Matches your success icon theme
            timer: 3000,
            timerProgressBar: true
        });
    @endif
</script>
@endsection