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
    <div class="content-small">
        <div class="container-fluid">
            <h1 class="mb-4">Update Data Barang</h1>

            <?php
            // Sertakan file koneksi
            include '../koneksi.php';

            // Inisialisasi variabel
            $merek = '';
            $tipe = '';

            // Periksa apakah parameter id diberikan
            if (isset($_GET['id'])) {
                // Ambil nilai id dari parameter URL
                $id = $_GET['id'];

                // Query SQL untuk mendapatkan data barang berdasarkan id
                $query = "SELECT * FROM dt_barang WHERE id_brg = $id";
                $result = $conn->query($query);

                // Periksa apakah data ditemukan
                if ($result->num_rows > 0) {
                    // Ambil data dari hasil query
                    $row = $result->fetch_assoc();
                    $merek = $row['merek'];
                    $tipe = $row['tipe'];
                } else {
                    // Jika data tidak ditemukan, bisa ditangani sesuai kebutuhan
                    echo "Data tidak ditemukan.";
                    exit(); // Keluar dari skrip jika data tidak ditemukan
                }
            } else {
                // Jika parameter id tidak diberikan, bisa ditangani sesuai kebutuhan
                echo "Parameter id tidak diberikan.";
                exit(); // Keluar dari skrip jika parameter id tidak diberikan
            }

            // Tutup koneksi
            $conn->close();
            ?>

            <form action="../process/process-update-data-barang.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="d-flex">
                    <div class="mb-3">
                        <label for="merek" class="input-data-label">Merek</label>
                        <input type="text" class="input-data" name="merek" id="merek" placeholder="Masukkan merek laptop" value="<?php echo $merek; ?>" required>
                    </div>
                    <div class="mb-3 ms-5">
                        <label for="tipe" class="input-data-label">Tipe</label>
                        <input type="text" class="input-data" name="tipe" id="tipe" placeholder="Masukkan tipe laptop" value="<?php echo $tipe; ?>" required>
                    </div>
                </div>

                <div class="d-flex">
                    <button type="button" class="btn btn-custom me-2" id="saveBtn">Update Data</button>
                    <a href="../view/data-barang.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('saveBtn').addEventListener('click', function() {
            // Mendapatkan semua input data
            const merekInput = document.getElementById('merek');
            const tipeInput = document.getElementById('tipe');

            // Memeriksa apakah kedua input sudah diisi atau belum
            const isFormFilled = merekInput.value.trim() !== '' && tipeInput.value.trim() !== '';

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
                            window.location.href = 'data-barang.php';
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