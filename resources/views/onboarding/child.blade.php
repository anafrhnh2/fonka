<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langkah 1: Pendaftaran & Penilaian Anak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Fredoka', sans-serif;
            /* Font bulat yang mesra kanak-kanak */
            background-color: #E0F7FA;
            /* Latar belakang biru muda */
        }

        .step-card {
            display: none;
            /* Sembunyikan semua langkah pada mulanya */
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            background: white;
            padding: 30px;
            text-align: center;
        }

        .step-card.active {
            display: block;
            /* Tunjukkan langkah aktif sahaja */
            animation: fadeIn 0.5s;
        }

        /* Kad Pilihan Besar untuk Jantina/Jawapan */
        .option-card {
            cursor: pointer;
            border: 3px solid #eee;
            border-radius: 15px;
            padding: 15px;
            transition: all 0.2s;
            margin-bottom: 10px;
        }

        .option-card:hover {
            background-color: #f9f9f9;
            transform: scale(1.02);
        }

        .option-card.selected {
            border-color: #4DB6AC;
            /* Sorotan warna teal */
            background-color: #E0F2F1;
        }

        /* Sembunyikan butang radio sebenar */
        input[type="radio"] {
            display: none;
        }

        .btn-custom {
            background-color: #FF7043;
            /* Butang oren */
            color: white;
            border-radius: 50px;
            padding: 10px 40px;
            font-size: 1.2rem;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-custom:hover {
            background-color: #F4511E;
            color: white;
        }

        .progress-container {
            margin-bottom: 30px;
        }

        .progress-bar {
            background-color: #26A69A;
            transition: width 0.4s ease;
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

        /* Input Umur Besar */
        .giant-input {
            font-size: 3rem;
            text-align: center;
            border: 3px solid #B2DFDB;
            border-radius: 15px;
            width: 120px;
            margin: 0 auto;
            color: #009688;
        }
    </style>
</head>

<body>

    <div class="container mt-5" style="max-width: 600px;">

        <div class="text-center mb-4">
            <h2 style="color: #00796B;">Jom Kita Mula! 🚀</h2>
        </div>
        <div class="progress progress-container" style="height: 15px; border-radius: 10px;">
            <div class="progress-bar" role="progressbar" style="width: 20%;" id="progressBar"></div>
        </div>

        <form action="{{ route('onboarding.child.store') }}" method="POST" id="wizardForm">
            @csrf

            <div class="step-card active" id="step1">
                <h3 class="mb-4">Berapa umur anak anda? 🎂</h3>

                <div class="row row-cols-3 g-3 mb-4 justify-content-center">
                    <div class="col">
                        <label class="option-card d-block w-100 py-3" onclick="selectOption(this, 'age')">
                            <input type="radio" name="age" value="5" required>
                            <div class="display-4 fw-bold" style="color: #FF7043;">5</div>
                            <span class="text-muted small">Tahun</span>
                        </label>
                    </div>
                    <div class="col">
                        <label class="option-card d-block w-100 py-3" onclick="selectOption(this, 'age')">
                            <input type="radio" name="age" value="6" required>
                            <div class="display-4 fw-bold" style="color: #FFCA28;">6</div>
                            <span class="text-muted small">Tahun</span>
                        </label>
                    </div>
                    <div class="col">
                        <label class="option-card d-block w-100 py-3" onclick="selectOption(this, 'age')">
                            <input type="radio" name="age" value="7" required>
                            <div class="display-4 fw-bold" style="color: #66BB6A;">7</div>
                            <span class="text-muted small">Tahun</span>
                        </label>
                    </div>
                    <div class="col">
                        <label class="option-card d-block w-100 py-3" onclick="selectOption(this, 'age')">
                            <input type="radio" name="age" value="8" required>
                            <div class="display-4 fw-bold" style="color: #26C6DA;">8</div>
                            <span class="text-muted small">Tahun</span>
                        </label>
                    </div>
                    <div class="col">
                        <label class="option-card d-block w-100 py-3" onclick="selectOption(this, 'age')">
                            <input type="radio" name="age" value="9" required>
                            <div class="display-4 fw-bold" style="color: #42A5F5;">9</div>
                            <span class="text-muted small">Tahun</span>
                        </label>
                    </div>
                    <div class="col">
                        <label class="option-card d-block w-100 py-3" onclick="selectOption(this, 'age')">
                            <input type="radio" name="age" value="10" required>
                            <div class="display-4 fw-bold" style="color: #AB47BC;">10</div>
                            <span class="text-muted small">Tahun</span>
                        </label>
                    </div>
                </div>

                <button type="button" class="btn btn-custom" onclick="nextStep(2)">Seterusnya ➜</button>
            </div>
            <div class="step-card" id="step2">
                <h3 class="mb-4">Siapa nama anak anda? </h3>

                <div class="mb-4 text-start">
                    <label class="form-label fw-bold">Nama:</label>
                    <input type="text" name="name" class="form-control form-control-lg"
                        placeholder="Masukkan nama anak anda di sini..." required
                        style="border-radius: 15px; border: 2px solid #B2DFDB;">
                </div>

                <div class="mb-4 text-start">
                    <label class="form-label fw-bold">Anak saya seorang:</label>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="option-card d-block text-center" onclick="selectOption(this, 'gender')">
                                <input type="radio" name="gender" value="boy" required>
                                <div style="font-size: 3rem;">👦</div>
                                <strong>Lelaki</strong>
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="option-card d-block text-center" onclick="selectOption(this, 'gender')">
                                <input type="radio" name="gender" value="girl" required>
                                <div style="font-size: 3rem;">👧</div>
                                <strong>Perempuan</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary me-2 rounded-pill px-4"
                    onclick="prevStep(1)">Kembali</button>
                <button type="button" class="btn btn-custom" onclick="nextStep(3)">Seterusnya ➜</button>
            </div>

            <div class="step-card" id="step3">
                <h4 class="mb-4">Soalan 1 / 4 ❓</h4>
                <p class="lead">Adakah anak anda keliru dengan huruf seperti <b>b</b> dan <b>d</b>?</p>

                <div class="d-grid gap-2 mb-4">
                    <label class="option-card d-flex align-items-center justify-content-between"
                        onclick="selectOption(this, 'q1')">
                        <span class="fs-4">Ya, kadang-kadang 🤔</span>
                        <input type="radio" name="confuses_letters" value="1" required>
                    </label>
                    <label class="option-card d-flex align-items-center justify-content-between"
                        onclick="selectOption(this, 'q1')">
                        <span class="fs-4">Tidak, tak pernah 👍</span>
                        <input type="radio" name="confuses_letters" value="0" required>
                    </label>
                </div>

                <button type="button" class="btn btn-secondary me-2 rounded-pill px-4"
                    onclick="prevStep(2)">Kembali</button>
                <button type="button" class="btn btn-custom" onclick="nextStep(4)">Seterusnya ➜</button>
            </div>

            <div class="step-card" id="step4">
                <h4 class="mb-4">Soalan 2 / 4 🎵</h4>
                <p class="lead">Adakah anak anda tahu bunyi asas huruf? (Contoh: 'a' untuk ayam)</p>

                <div class="d-grid gap-2 mb-4">
                    <label class="option-card d-flex align-items-center justify-content-between"
                        onclick="selectOption(this, 'q2')">
                        <span class="fs-4">Ya, tahu 📢</span>
                        <input type="radio" name="knows_basic_sounds" value="1" required>
                    </label>
                    <label class="option-card d-flex align-items-center justify-content-between"
                        onclick="selectOption(this, 'q2')">
                        <span class="fs-4">Tidak pasti 😶</span>
                        <input type="radio" name="knows_basic_sounds" value="0" required>
                    </label>
                </div>

                <button type="button" class="btn btn-secondary me-2 rounded-pill px-4"
                    onclick="prevStep(3)">Kembali</button>
                <button type="button" class="btn btn-custom" onclick="nextStep(5)">Seterusnya ➜</button>
            </div>

            <div class="step-card" id="step5">
                <h4 class="mb-4">Soalan 3 / 4 🎤</h4>
                <p class="lead">Bolehkah anak anda cam perkataan yang sama bunyi? (Contoh: Buku - Kuku)</p>

                <div class="d-grid gap-2 mb-4">
                    <label class="option-card d-flex align-items-center justify-content-between"
                        onclick="selectOption(this, 'q3')">
                        <span class="fs-4">Ya, boleh ✅</span>
                        <input type="radio" name="can_rhyme" value="1" required>
                    </label>
                    <label class="option-card d-flex align-items-center justify-content-between"
                        onclick="selectOption(this, 'q3')">
                        <span class="fs-4">Tidak boleh ❌</span>
                        <input type="radio" name="can_rhyme" value="0" required>
                    </label>
                </div>

                <button type="button" class="btn btn-secondary me-2 rounded-pill px-4"
                    onclick="prevStep(4)">Kembali</button>
                <button type="button" class="btn btn-custom" onclick="nextStep(6)">Seterusnya ➜</button>
            </div>

            <div class="step-card" id="step6">
                <h4 class="mb-4">Soalan Terakhir! 📖</h4>
                <p class="lead">Bolehkah anak anda baca perkataan mudah seperti 'ba-ju' atau 'bo-la'?</p>

                <div class="d-grid gap-2 mb-4">
                    <label class="option-card d-flex align-items-center justify-content-between"
                        onclick="selectOption(this, 'q4')">
                        <span class="fs-4">Boleh baca 👕</span>
                        <input type="radio" name="can_read_simple_words" value="1" required>
                    </label>
                    <label class="option-card d-flex align-items-center justify-content-between"
                        onclick="selectOption(this, 'q4')">
                        <span class="fs-4">Belum lagi 🚫</span>
                        <input type="radio" name="can_read_simple_words" value="0" required>
                    </label>
                </div>

                <button type="button" class="btn btn-secondary me-2 rounded-pill px-4"
                    onclick="prevStep(5)">Kembali</button>
                <button type="submit" class="btn btn-success rounded-pill px-5 py-2 fs-5">Siap & Daftar! 🎉</button>
            </div>

        </form>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 6;

        function showStep(step) {
            // Sembunyikan semua langkah
            document.querySelectorAll('.step-card').forEach(card => card.classList.remove('active'));
            // Tunjukkan langkah semasa
            document.getElementById('step' + step).classList.add('active');

            // Kemaskini Bar Kemajuan
            const progress = (step / totalSteps) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
        }

        function nextStep(targetStep) {
            // Semakan ringkas sebelum bergerak ke langkah seterusnya
            const currentCard = document.getElementById('step' + currentStep);
            const inputs = currentCard.querySelectorAll('input[required]');
            let valid = true;

            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    valid = false;
                }
            });

            if (valid) {
                currentStep = targetStep;
                showStep(currentStep);
            }
        }

        function prevStep(targetStep) {
            currentStep = targetStep;
            showStep(currentStep);
        }

        // Pembantu UI: Sorot kad pilihan yang ditekan
        function selectOption(element, groupName) {
            // Buang kelas 'selected' dari semua kad dalam kumpulan ini
            const group = element.closest('.step-card').querySelectorAll('.option-card');
            group.forEach(card => card.classList.remove('selected'));

            // Tambah kelas 'selected' pada yang diklik
            element.classList.add('selected');

            // Tandakan butang radio tersembunyi
            const radio = element.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        }
    </script>

</body>

</html>
