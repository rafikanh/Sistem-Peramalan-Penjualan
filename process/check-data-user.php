<?php
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data email, nama depan, dan nama belakang dari permintaan POST
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $nama_depan = isset($_POST['nm_depan']) ? $_POST['nm_depan'] : '';
    $nama_belakang = isset($_POST['nm_belakang']) ? $_POST['nm_belakang'] : '';

    // Query SQL untuk memeriksa apakah email sudah terdaftar sebelumnya
    $sql_check_duplicate_user = "SELECT * FROM users WHERE email='$email' AND nm_depan='$nama_depan' AND nm_belakang='$nama_belakang'";
    $result_check_duplicate_user = $conn->query($sql_check_duplicate_user);

    // Jika email sudah terdaftar, kembalikan respons dengan true
    if ($result_check_duplicate_user && $result_check_duplicate_user->num_rows > 0) {
        echo json_encode(true);
    } else {
        // Jika email belum terdaftar, kembalikan respons dengan false
        echo json_encode(false);
    }

    // Tutup koneksi database
    $conn->close();
}
?>
