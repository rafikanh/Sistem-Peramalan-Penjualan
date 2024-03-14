<?php
include '../koneksi.php';

// Ambil merek yang dipilih dari permintaan POST
$merek = $_POST['merek'];

// Query SQL untuk mengambil data tipe berdasarkan merek
$sql = "SELECT DISTINCT tipe FROM dt_barang WHERE merek = '$merek'";
$result = $conn->query($sql);

if ($result === false) {
    die("Error executing the query: " . $conn->error);
}

// Inisialisasi array untuk menyimpan data tipe
$tipes = [];

// Loop untuk mengambil data tipe
while ($row = $result->fetch_assoc()) {
    $tipes[] = $row['tipe'];
}

// Mengembalikan data tipe dalam format JSON
echo json_encode($tipes);

// Tutup koneksi database
$conn->close();
?>
