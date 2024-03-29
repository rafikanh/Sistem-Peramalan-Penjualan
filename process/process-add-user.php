<?php
// Sertakan file koneksi
include '../koneksi.php';

// Cek apakah formulir dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap data dari formulir
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $nama_depan = isset($_POST['nm_depan']) ? $_POST['nm_depan'] : '';
    $nama_belakang = isset($_POST['nm_belakang']) ? $_POST['nm_belakang'] : '';

    // Query SQL untuk memeriksa apakah email sudah terdaftar sebelumnya
    $sql_check_duplicate_user = "SELECT * FROM users WHERE email='$email' AND nm_depan='$nama_depan' AND nm_belakang='$nama_belakang'";
    $result_check_duplicate_user = $conn->query($sql_check_duplicate_user);

    // Jika email sudah terdaftar, kembalikan respons dengan pesan peringatan
    if ($result_check_duplicate_user->num_rows > 0) {
        header("Location: check-data-user.php");
        exit();
    } else {
        // Jika email belum terdaftar, lanjutkan untuk menyimpan data
        $password = md5($_POST['password']); 

        // Query SQL untuk menyimpan data ke dalam tabel users
        $query = "INSERT INTO users (nm_depan, nm_belakang, email, password) VALUES ('$nama_depan', '$nama_belakang', '$email', '$password')";

        if ($conn->query($query) === TRUE) {
            header("Location: ../view/user-manajemen.php");
            exit();
        } else {
            // Jika terjadi kesalahan, berikan respons dengan pesan error
            echo "Error: " . $query . "<br>" . $conn->error;
            exit(); 
        }
    }

    // Tutup koneksi
    $conn->close();
}
?>
