@extends('layouts.parent')

@section('title', 'Add New Child - Fonka')

@section('styles')
    <style>
        :root {
            --primary: #8b5cf6;
            --primary-glow: rgba(139, 92, 246, 0.15);
            --card-border: #e2e8f0;
            --input-bg: #f8fafc;
            --text-muted: #64748b;
        }

        .content-wrapper { animation: fadeIn 0.5s ease-out; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-hero {
            background: linear-gradient(135deg, #ffffff 0%, #fcfaff 100%);
            border-radius: 2rem;
            border: 1px solid var(--card-border);
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        }

        .avatar-main {
            width: 100px;
            height: 100px;
            border-radius: 2rem;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            background: #eee;
        }

        @media (min-width: 768px) {
            .avatar-main { width: 120px; height: 120px; border-radius: 2.5rem; border-width: 6px; }
        }

        .glass-card {
            background: white;
            border-radius: 1.5rem;
            border: 1px solid var(--card-border);
            padding: 1.5rem;
            transition: 0.3s;
        }

        @media (min-width: 768px) { .glass-card { padding: 2.5rem; } }

        .form-group-custom { margin-bottom: 2rem; }

        .form-label-modern {
            font-weight: 800;
            font-size: 0.85rem;
            color: #334155;
            margin-left: 0.25rem;
            margin-bottom: 0.75rem;
            display: block;
        }

        .form-control-modern {
            border-radius: 1rem;
            padding: 0.85rem 1.2rem;
            border: 2px solid #e2e8f0;
            background: var(--input-bg);
            font-weight: 600;
            transition: 0.3s;
            width: 100%;
        }

        .form-control-modern:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-glow);
            outline: none;
        }

        /* ----- REKA BENTUK KOTAK PILIHAN (OPTION CARDS) ----- */
        .grid-options {
            display: grid;
            gap: 12px;
        }
        
        .grid-age { grid-template-columns: repeat(3, 1fr); }
        .grid-2 { grid-template-columns: 1fr; }
        
        @media (min-width: 768px) {
            .grid-age { grid-template-columns: repeat(6, 1fr); }
            .grid-2 { grid-template-columns: 1fr 1fr; }
        }

        .option-card {
            cursor: pointer;
            border: 2px solid var(--card-border);
            border-radius: 1.25rem;
            padding: 1rem;
            background: white;
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            height: 100%;
        }

        .option-card-row {
            flex-direction: row;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
        }

        .option-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.05);
            border-color: #596a7e;
        }

        .option-card.selected {
            border-color: #26A69A;
            background-color: #E0F2F1;
            box-shadow: 0 8px 20px rgba(38, 166, 154, 0.15);
            transform: translateY(-3px);
        }


        /* Teks dalam Option Cards */
        .text-huge { font-size: 2.5rem; font-weight: 800; line-height: 1; margin-bottom: 0.25rem; }
        .text-muted-sm { color: #64748b; font-size: 0.8rem; font-weight: 600; }
        .text-answer { font-size: 1.1rem; font-weight: 600; color: #334155; border: 2px black;}
        .option-card.selected .text-answer { color: #0F766E; }

        .btn-modern {
            border-radius: 1rem;
            padding: 0.8rem 2.5rem;
            font-weight: 700;
            transition: 0.3s;
        }

        .btn-primary-modern {
            background: #84CC16;
            color: white;
            border: none;
            box-shadow: 0 8px 20px rgba(132, 204, 22, 0.2);
        }

        .btn-primary-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(132, 204, 22, 0.3);
            color: white;
            background-color: #65A30D;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <h2 class="fw-900 mb-1" style="color: #1e293b;">Add New Child</h2>
                <p class="text-muted mb-0">Create a new profile to start a unique learning adventure.</p>
            </div>
            <div>
                <a href="{{ route('parents.child-profile') }}" class="btn btn-light btn-modern border hover:shadow-sm">
                    <i class="fa fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>

        <div class="profile-hero shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                    <img id="previewAvatar" src="{{ asset('images/avatars/Bluppy.png') }}" class="avatar-main" alt="Default Avatar">
                </div>
                <div class="col-md text-center text-md-start">
                    <h2 class="fw-900 mb-0" id="previewName" style="color: #0f172a;">New Learner</h2>
                    <p class="text-muted fw-bold mb-0" id="previewAge" style="font-size: 1.1rem;">Age: —</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="glass-card shadow-sm">
                    <form action="{{ route('parents.store') }}" method="POST">
                        @csrf
                        
                        <h5 class="fw-900 mb-4" style="color: var(--primary);"><i class="fa-solid fa-user-astronaut me-2"></i>Basic Information</h5>
                        
                        <div class="form-group-custom">
                            <label class="form-label-modern">1. Child's Name</label>
                            <input type="text" name="name" id="inputName" class="form-control-modern" placeholder="Enter full name..." oninput="updatePreview()" required>
                        </div>

                        <div class="form-group-custom">
                            <label class="form-label-modern">2. Child's Age</label>
                            <div class="grid-options grid-age border-0 rounded-4 p-3" style="background-color: #f8fafc; border: 1px solid #e2e8f0 !important;">
                                <label class="option-card" onclick="selectOption(this, 'age', true)">
                                    <input type="radio" name="age" value="4" class="d-none" required>
                                    <div class="text-huge" style="color: #FF7043;">4</div>
                                    <span class="text-muted-sm">Tahun</span>
                                </label>
                                <label class="option-card" onclick="selectOption(this, 'age', true)">
                                    <input type="radio" name="age" value="5" class="d-none" required>
                                    <div class="text-huge" style="color: #FFCA28;">5</div>
                                    <span class="text-muted-sm">Tahun</span>
                                </label>
                                <label class="option-card" onclick="selectOption(this, 'age', true)">
                                    <input type="radio" name="age" value="6" class="d-none" required>
                                    <div class="text-huge" style="color: #66BB6A;">6</div>
                                    <span class="text-muted-sm">Tahun</span>
                                </label>
                                <label class="option-card" onclick="selectOption(this, 'age', true)">
                                    <input type="radio" name="age" value="7" class="d-none" required>
                                    <div class="text-huge" style="color: #26C6DA;">7</div>
                                    <span class="text-muted-sm">Tahun</span>
                                </label>
                                <label class="option-card" onclick="selectOption(this, 'age', true)">
                                    <input type="radio" name="age" value="8" class="d-none" required>
                                    <div class="text-huge" style="color: #ff7b93;">8</div>
                                    <span class="text-muted-sm">Tahun</span>
                                </label>
                                <label class="option-card" onclick="selectOption(this, 'age', true)">
                                    <input type="radio" name="age" value="9" class="d-none" required>
                                    <div class="text-huge" style="color: #AB47BC;">9</div>
                                    <span class="text-muted-sm">Tahun</span>
                                </label>
                            </div>
                        </div>

                        <hr class="my-5 border-light">

                        <h5 class="fw-900 mb-2" style="color: #0ea5e9;"><i class="fa-solid fa-clipboard-list me-2"></i> Assessment</h5>
                        <p class="text-muted small mb-4 fw-bold ms-1">Help us determine the best learning module level for your child.</p>

                        <div class="form-group-custom">
                            <label class="form-label-modern">1. Adakah anak anda keliru dengan huruf seperti 'b' dan 'd'?</label>
                            <div class="grid-options grid-2">
                                <label class="option-card option-card-row" onclick="selectOption(this, 'q1')">
                                    <span class="text-answer">Ya, kadang-kadang</span>
                                    <input type="radio" name="confuses_letters" value="yes" class="d-none" required>
                                </label>
                                <label class="option-card option-card-row" onclick="selectOption(this, 'q1')">
                                    <span class="text-answer">Tidak, tak pernah</span>
                                    <input type="radio" name="confuses_letters" value="no" class="d-none" required>
                                </label>
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label class="form-label-modern">2. Adakah anak anda tahu membunyikan huruf asas? (Cth: 'S' = /sss/)</label>
                            <div class="grid-options grid-2">
                                <label class="option-card option-card-row" onclick="selectOption(this, 'q2')">
                                    <span class="text-answer">Ya, tahu</span>
                                    <input type="radio" name="knows_sounds" value="yes" class="d-none" required>
                                </label>
                                <label class="option-card option-card-row" onclick="selectOption(this, 'q2')">
                                    <span class="text-answer">Belum tahu</span>
                                    <input type="radio" name="knows_sounds" value="no" class="d-none" required>
                                </label>
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label class="form-label-modern">3. Bolehkah anak anda menggabungkan bunyi menjadi perkataan? (Cth: b-a = ba)</label>
                            <div class="grid-options grid-2">
                                <label class="option-card option-card-row" onclick="selectOption(this, 'q3')">
                                    <span class="text-answer">Ya, boleh</span>
                                    <input type="radio" name="can_blend" value="yes" class="d-none" required>
                                </label>
                                <label class="option-card option-card-row" onclick="selectOption(this, 'q3')">
                                    <span class="text-answer">Belum boleh</span>
                                    <input type="radio" name="can_blend" value="no" class="d-none" required>
                                </label>
                            </div>
                        </div>

                        <div class="form-group-custom mb-5">
                            <label class="form-label-modern">4. Bolehkah anak anda membaca perkataan pendek dengan sendiri?</label>
                            <div class="grid-options grid-2">
                                <label class="option-card option-card-row" onclick="selectOption(this, 'q4')">
                                    <span class="text-answer">Boleh baca</span>
                                    <input type="radio" name="reads_words" value="yes" class="d-none" required>
                                </label>
                                <label class="option-card option-card-row" onclick="selectOption(this, 'q4')">
                                    <span class="text-answer">Belum lagi</span>
                                    <input type="radio" name="reads_words" value="no" class="d-none" required>
                                </label>
                            </div>
                        </div>

                        <div class="alert border-0 rounded-4 p-3 mb-4" style="background-color: #f8fafc; border: 1px solid #e2e8f0 !important;">
                            <div class="d-flex gap-3 align-items-center text-muted">
                                <i class="fa-solid fa-circle-info text-primary fs-4"></i>
                                <small><strong>Note:</strong> Every new learner starts with the default avatar. They can unlock and customize their own avatars later using points!</small>
                            </div>
                        </div>

                        <div class="text-center text-md-end mt-5">
                            <button type="submit" class="btn btn-primary-modern btn-modern w-100 w-md-auto py-3">
                                <i class="fa-solid fa-cloud-arrow-up me-2"></i>Save & Start Learning
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Logik menukar warna kad (option card)
        function selectOption(element, groupName, isAge = false) {
            // Cari semua kad dalam barisan/kumpulan yang sama
            const group = element.parentElement.querySelectorAll('.option-card');
            
            // Buang class 'selected' dari semua kad dalam kumpulan itu
            group.forEach(card => card.classList.remove('selected'));
            
            // Tambah class 'selected' pada kad yang diklik
            element.classList.add('selected');

            // Tandakan radio button yang tersembunyi
            const radio = element.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                
                // Jika umur dipilih, kemas kini teks di Banner Atas
                if (isAge) {
                    document.getElementById('previewAge').innerText = `Age: ${radio.value} Years Old`;
                }
            }
        }

        // Kemas kini nama semasa ditaip
        function updatePreview() {
            const name = document.getElementById('inputName').value;
            document.getElementById('previewName').innerText = name || "New Learner";
        }

        // Notifikasi SweetAlert jika berjaya
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berjaya!',
                    text: @json(session('success')),
                    confirmButtonColor: '#8b5cf6',
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endsection