<?php require_once '../process/check_login.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Style -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/img/logo.png">
    <title>Sistem Peramalan Penjualan</title>
</head>

<body>
    <?php include '../view/component/sidebar.php'; ?>
    <div class="content-medium">
        <div class="container-fluid">
            <h1 class="mb-4">Tambah User</h1>
            <form action="../process/process-add-user.php" method="post">
                <div class="d-flex">
                    <div class="mb-3">
                        <label for="nama_depan" class="input-data-label">Nama Depan</label>
                        <input type="text" class="input-data" name="nm_depan" id="nama_depan" placeholder="Masukkan nama depan" required>
                    </div>
                    <div class="mb-3 ms-5">
                        <label for="nama_belakang" class="input-data-label">Nama Belakang</label>
                        <input type="text" class="input-data" name="nm_belakang" id="nama_belakang" placeholder="Masukkan nama belakang" required>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="mb-3">
                        <label for="email" class="input-data-label">Email</label>
                        <input type="text" class="input-data" name="email" id="email" placeholder="Masukkan email" required>
                    </div>
                    <div class="mb-3 ms-5">
                        <label for="password" class="input-data-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="input-data" name="password" id="password" placeholder="Masukkan password" required>
                            <i class="bi bi-eye-fill" id="togglePassword"></i>
                        </div>
                    </div>
                </div>

                <div class="d-flex">
                    <button type="button" class="btn btn-custom me-2" id="saveBtn">Simpan Data</button>
                    <a href="../view/user-manajemen.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Show or Hide Password -->
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.remove('bi-eye-fill');
                this.classList.add('bi-eye-slash-fill');
            } else {
                passwordInput.type = 'password';
                this.classList.remove('bi-eye-slash-fill');
                this.classList.add('bi-eye-fill');
            }
        });
    </script>

    <!-- SweetAlert2 script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('saveBtn').addEventListener('click', function() {
            // Mendapatkan semua input data
            const namaDepanInput = document.getElementById('nama_depan').value.trim();
            const namaBelakangInput = document.getElementById('nama_belakang').value.trim();
            const emailInput = document.getElementById('email').value.trim();
            const passwordInput = document.getElementById('password').value.trim();

            // Memeriksa apakah kedua input sudah diisi atau belum
            const isFormFilled = namaDepanInput !== '' && namaBelakangInput !== '' && emailInput !== '' && passwordInput !== '';

            if (isFormFilled) {
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            const isUserExists = JSON.parse(xhr.responseText);
                            if (isUserExists) {
                                Swal.fire("Data sudah tersedia", "Data user sudah tersedia.", "warning");
                            } else {
                                Swal.fire({
                                    title: "Apakah Anda ingin menyimpan data baru?",
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonText: "Ya, Simpan",
                                    denyButtonText: `Jangan Simpan`,
                                    cancelButtonText: "Batal"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        Swal.fire({
                                            title: "Tersimpan!",
                                            text: "Data baru yang Anda buat sudah disimpan.",
                                            icon: "success",
                                            showConfirmButton: true,
                                            confirmButtonText: "OK"
                                        }).then(() => {
                                            document.querySelector('form').submit();
                                        });
                                    } else if (result.isDenied) {
                                        Swal.fire("Data baru tidak disimpan", "", "info").then(() => {
                                            window.history.back();
                                        });
                                    }
                                });
                            }
                        } else {
                            console.error('Error fetching data:', xhr.statusText);
                        }
                    }
                };

                // Kirim data ke check-data-user.php
                xhr.open('POST', '../process/check-data-user.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.send('nm_depan=' + encodeURIComponent(namaDepanInput) + '&nm_belakang=' + encodeURIComponent(namaBelakangInput) + '&email=' + encodeURIComponent(emailInput));
            } else {
                Swal.fire("Formulir belum lengkap", "Silakan isi semua data terlebih dahulu.", "warning");
            }
        });
    </script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>