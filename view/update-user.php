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
    <div class="content-large">
        <div class="container-fluid">
            <h1 class="mb-4">Update User</h1>

            <?php
            // Sertakan file koneksi
            include '../koneksi.php';

            // Periksa apakah ID pengguna disertakan di URL
            if (isset($_GET['id'])) {
                $userID = $_GET['id'];

                // Query SQL untuk mengambil data pengguna berdasarkan ID
                $query = "SELECT * FROM users WHERE id = $userID";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    // Ambil data pengguna
                    $row = $result->fetch_assoc();
                    $nama_depan = $row['nm_depan'];
                    $nama_belakang = $row['nm_belakang'];
                    $email = $row['email'];
                    $password = $row['password']; // Ini dianggap masih dalam format MD5
                } else {
                    echo "Data pengguna tidak ditemukan.";
                    exit();
                }
            } else {
                echo "ID pengguna tidak disertakan dalam URL.";
                exit();
            }
            ?>

            <!-- Formulir update -->
            <form action="../process/process-update-user.php" method="post">
                <div class="d-flex">
                    <div class="mb-3">
                        <label for="nama_depan" class="input-data-label">Nama Depan</label>
                        <input type="text" class="input-data" name="nm_depan" id="nama_depan" value="<?php echo $nama_depan; ?>" placeholder="Masukkan nama depan" required>
                    </div>
                    <div class="mb-3 ms-5">
                        <label for="nama_belakang" class="input-data-label">Nama Belakang</label>
                        <input type="text" class="input-data" name="nm_belakang" id="nama_belakang" value="<?php echo $nama_belakang; ?>" placeholder="Masukkan nama belakang">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="mb-3">
                        <label for="email" class="input-data-label">Email</label>
                        <input type="text" class="input-data" id="email" name="email" value="<?php echo $email; ?>" placeholder="Masukkan email" required>
                    </div>
                    <div class="mb-3 ms-5">
                        <label for="password" class="input-data-label">Password</label>
                        <div class="input-group">
                            <input type="text" class="input-data" name="password" id="password" value="<?php $passwordLength = strlen($password);
                                                                                                        echo str_repeat('•', $passwordLength); ?>" placeholder="Password">
                        </div>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="mb-3">
                        <label for="ganti_password" class="input-data-label">Ganti Password</label>
                        <div class="input-group">
                            <input type="text" class="input-data" name="ganti_password" id="ganti_password" placeholder="Ganti password">
                            <i class="bi bi-eye-slash-fill" id="togglePassword"></i>
                        </div>
                    </div>
                </div>

                <div class="d-flex">
                    <input type="hidden" name="userID" value="<?php echo $userID; ?>"> <!-- Tambahkan input tersembunyi untuk userID -->
                    <button type="button" class="btn btn-custom me-2" id="saveBtn">Update Data</button>
                    <a href="../view/user-manajemen.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Password -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const passwordDiv = document.getElementById('password');

            // Membuat event untuk mengganti teks menjadi karakter bintang
            passwordDiv.addEventListener('keydown', function(event) {
                // Mengganti teks dengan karakter bintang
                passwordDiv.textContent = '•'.repeat(passwordDiv.textContent.length);
            });
        });
    </script>

    <!-- Show or Hide Password -->
    <script>
        window.onload = function() {
            const passwordInput = document.getElementById('ganti_password');
            passwordInput.type = 'password';
        };

        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('ganti_password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.remove('bi-eye-slash-fill');
                this.classList.add('bi-eye-fill');
            } else {
                passwordInput.type = 'password';
                this.classList.remove('bi-eye-fill');
                this.classList.add('bi-eye-slash-fill');
            }
        });
    </script>

    <!-- SweetAlert2 script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('saveBtn').addEventListener('click', function() {
            // Mendapatkan semua input data
            const namaDepanInput = document.getElementById('nama_depan');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            // Memeriksa apakah kedua input sudah diisi atau belum
            const isFormFilled = namaDepanInput.value.trim() !== '' && emailInput.value.trim() !== '' && passwordInput.value.trim() !== '';

            // Memeriksa apakah formulir valid atau tidak
            const isFormValid = isFormFilled; // Tambahkan kondisi validasi formulir sesuai kebutuhan

            if (isFormValid) {
                Swal.fire({
                    title: "Apakah Anda ingin menyimpan perubahan data?",
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: "Ya, Simpan",
                    denyButtonText: `Jangan Simpan`,
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika pengguna mengklik "Ya, Simpan", tampilkan pesan "Tersimpan!" dan kirim formulir setelahnya
                        Swal.fire({
                            title: "Tersimpan!",
                            text: "Perubahan yang Anda buat sudah disimpan.",
                            icon: "success",
                            showConfirmButton: true,
                            confirmButtonText: "OK"
                        }).then(() => {
                            document.querySelector('form').submit();
                        });
                    } else if (result.isDenied) {
                        // Jika pengguna memilih "Jangan Simpan", tampilkan pesan informasi dan kembali ke halaman sebelumnya
                        Swal.fire("Perubahan data tidak disimpan", "", "info").then(() => {
                            window.history.back();
                        });
                    }
                });
            } else {
                // Menampilkan pesan bahwa data harus diisi
                Swal.fire("Formulir belum lengkap", "Silakan isi semua data terlebih dahulu.", "warning");
            }
        });
    </script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>