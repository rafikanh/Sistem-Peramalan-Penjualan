<?php
// Sertakan file konfigurasi database
require_once "koneksi.php";

// Mulai sesi
session_start();

// Cek apakah form login disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil nilai dari form login
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Menggunakan fungsi md5 untuk hash password
    $hashedPassword = md5($password);

    // Query untuk memeriksa apakah username dan password valid
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$hashedPassword'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        // Login berhasil
        $_SESSION['email'] = $data['email'];
        $_SESSION['id'] = $data['id'];
        $_SESSION['logged_in'] = true;

        // Alihkan pengguna langsung ke halaman dashboard
        header("Location: view/dashboard.php");
        exit(); // Pastikan untuk keluar setelah melakukan pengalihan
    } else {
        // Login gagal, tampilkan pesan kesalahan
        $errorMessage = "Email atau password salah. Mohon periksa kembali.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- Style -->
    <link rel="stylesheet" href="css/login.css">
    <title>Sistem Peramalan Penjualan</title>
</head>

<body>
    <section class="gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-4">
                            <img src="assets/img/logo.png" class="logo"><br>

                            <h3 class="mb-4"><b>LOGIN</b></h3>

                            <!-- Tampilkan pesan kesalahan jika login gagal -->
                            <?php if (isset($errorMessage)) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $errorMessage; ?>
                                </div>
                            <?php endif; ?>

                            <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" autocomplete="email" placeholder="Masukkan email" aria-describedby="emailHelp" required>
                                    <div class="invalid-feedback">Email atau password salah.</div>
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" autocomplete="password" placeholder="Masukkan password" required>
                                        <i class="bi bi-eye-fill" id="togglePassword"></i>
                                    </div>
                                    <div class="invalid-feedback">Email atau password salah.</div>
                                </div>

                                <input type="hidden" name="action" value="login">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Show or hide Password -->
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

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>