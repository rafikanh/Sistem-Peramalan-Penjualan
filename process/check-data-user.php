<?php
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data email dari permintaan POST
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    // Query SQL untuk memeriksa apakah email sudah terdaftar sebelumnya
    $sql_check_duplicate_user = "SELECT * FROM users WHERE email='$email'";
    $result_check_duplicate_user = $conn->query($sql_check_duplicate_user);

    // Buat variabel untuk menampung hasil pengecekan
    $isUserExists = false;

    // Jika query berhasil dieksekusi dan jumlah baris hasil query lebih dari 0, maka email sudah terdaftar
    if ($result_check_duplicate_user && $result_check_duplicate_user->num_rows > 0) {
        $isUserExists = true;
    }

    // Mengembalikan respons dalam format JSON
    echo json_encode($isUserExists);

    // Tutup koneksi database
    $conn->close();
}
?>
