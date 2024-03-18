<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Style -->
    <link rel="stylesheet" href="../css/style.css">
    <title>Sistem Peramalan Penjualan</title>
</head>

<body>
    <?php include '../view/component/sidebar.php'; ?>
    <div class="content-large">
        <div class="container-fluid">
            <h1 class="mb-4">Update Data Penjualan</h1>

            <?php
            // Termasuk file koneksi
            include '../koneksi.php';

            // Periksa apakah parameter id_penjualan disertakan dalam URL
            if (isset($_GET['id_penjualan'])) {

                // Tangkap nilai id_penjualan dari parameter URL
                $id_penjualan = $_GET['id_penjualan'];

                // Query untuk mengambil data penjualan berdasarkan id_penjualan
                $sql_get_data_penjualan = "SELECT * FROM dt_penjualan WHERE id_penjualan = $id_penjualan";

                // Eksekusi query
                $result_get_data_penjualan = $conn->query($sql_get_data_penjualan);

                // Periksa apakah query berhasil dieksekusi dan apakah ada data yang ditemukan
                if ($result_get_data_penjualan && $result_get_data_penjualan->num_rows > 0) {
                    // Ambil data penjualan dari hasil query
                    $data_penjualan = $result_get_data_penjualan->fetch_assoc();
                } else {
                    // Jika tidak ada data yang ditemukan, alihkan pengguna kembali ke halaman data-penjualan.php
                    header("Location: ../view/data-penjualan.php");
                    exit();
                }
            }
            ?>


            <!-- Form untuk memperbarui data penjualan -->
            <form method="POST" action="../process/process-update-data-penjualan.php">
                <!-- Hidden input untuk menyimpan id_penjualan yang sedang diupdate -->
                <input type="hidden" name="id_penjualan" value="<?php echo isset($data_penjualan['id_penjualan']) ? $data_penjualan['id_penjualan'] : ''; ?>">
                <div class="d-flex">
                    <div class="mb-3">
                        <label for="bulan" class="input-data-label">Bulan</label>
                        <select class="form-select" name="bulan" aria-label="Default select example">
                            <!-- Tampilkan data bulan dari database -->
                            <?php
                            // Query SQL untuk mengambil data bulan dari tabel dt_penjualan tanpa duplikasi
                            $sql_bulan = "SELECT DISTINCT bulan FROM dt_penjualan";
                            $result_bulan = $conn->query($sql_bulan);

                            // Ambil nilai bulan dari data penjualan
                            $selected_bulan = isset($data_penjualan['bulan']) ? $data_penjualan['bulan'] : '';

                            while ($row = $result_bulan->fetch_assoc()) {
                                $bulan_value = $row['bulan'];
                                // Konversi nilai bulan menjadi nama bulan
                                $nama_bulan = date('F', mktime(0, 0, 0, $bulan_value, 1));
                                // Periksa apakah nilai bulan sama dengan yang dipilih dari data penjualan
                                $selected = ($bulan_value == $selected_bulan) ? 'selected' : '';
                                echo "<option value='$bulan_value' $selected>$nama_bulan</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3 ms-5">
                        <label for="tahun" class="input-data-label">Tahun</label>
                        <select class="form-select" name="tahun" aria-label="Default select example">
                            <!-- Tampilkan data tahun dari database -->
                            <?php
                            // Query SQL untuk mengambil data tahun dari tabel dt_penjualan tanpa duplikasi
                            $sql_tahun = "SELECT DISTINCT tahun FROM dt_penjualan";
                            $result_tahun = $conn->query($sql_tahun);

                            // Inisialisasi variabel bulan yang tersimpan sebelumnya
                            $tahun_sebelumnya = isset($data_penjualan['tahun']) ? $data_penjualan['tahun'] : '';

                            // Loop untuk menampilkan data tahun
                            while ($row = $result_tahun->fetch_assoc()) {
                                $selected = isset($data_penjualan['tahun']) && $data_penjualan['tahun'] == $row['tahun'] ? 'selected' : '';
                                echo "<option value='" . $row['tahun'] . "' $selected>" . $row['tahun'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="mb-3">
                        <label for="merek" class="input-data-label">Merek</label>
                        <select id="merekSelect" name="merek" class="form-select" aria-label="Default select example" onchange="updateTipeOptions()">
                            <?php
                            // Query SQL untuk mengambil data merek dan tipe yang terkait dengan data penjualan yang sedang diedit
                            $sql_merek = "SELECT DISTINCT db.merek FROM dt_penjualan dp INNER JOIN dt_barang db ON dp.id_brg = db.id_brg WHERE dp.id_penjualan = $id_penjualan";
                            $result_merek = $conn->query($sql_merek);

                            // Inisialisasi array untuk menyimpan data merek
                            $mereks = array();

                            // Ambil semua baris hasil query dan simpan dalam array $mereks
                            while ($row = $result_merek->fetch_assoc()) {
                                $mereks[] = $row;
                            }

                            // Loop untuk menampilkan data merek
                            foreach ($mereks as $row) {
                                $selected = ($merek_terpilih == $row['merek']) ? 'selected' : '';
                                echo "<option value='" . $row['merek'] . "' $selected>" . $row['merek'] . "</option>";
                            }

                            // Tambahkan opsi merek lainnya dari database
                            $sql_other_options = "SELECT DISTINCT merek FROM dt_barang WHERE merek NOT IN (SELECT DISTINCT db.merek FROM dt_penjualan dp INNER JOIN dt_barang db ON dp.id_brg = db.id_brg WHERE dp.id_penjualan = $id_penjualan)";
                            $result_other_options = $conn->query($sql_other_options);

                            // Loop untuk menampilkan opsi merek lainnya
                            while ($row = $result_other_options->fetch_assoc()) {
                                echo "<option value='" . $row['merek'] . "'>" . $row['merek'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3 ms-5">
                        <label for="tipe" class="input-data-label">Tipe</label>
                        <select id="tipeSelect" name="tipe" class="form-select" aria-label="Default select example">
                            <?php
                            // Ambil merek yang dipilih sebelumnya
                            $merek_terpilih = isset($data_penjualan['merek']) ? $data_penjualan['merek'] : '';

                            // Query SQL untuk mengambil data tipe yang terkait dengan merek yang dipilih
                            $sql_merek_tipe = "SELECT DISTINCT tipe FROM dt_barang WHERE merek = '$merek_terpilih'";
                            $result_merek_tipe = $conn->query($sql_merek_tipe);

                            // Periksa apakah query berhasil dieksekusi
                            if ($result_merek_tipe) {
                                // Loop untuk menampilkan data tipe
                                while ($row = $result_merek_tipe->fetch_assoc()) {
                                    $selected = isset($data_penjualan['tipe']) && $data_penjualan['tipe'] == $row['tipe'] ? 'selected' : '';
                                    echo "<option value='" . $row['tipe'] . "' $selected>" . $row['tipe'] . "</option>";
                                }
                            } else {
                                // Jika query gagal dieksekusi, tampilkan pesan kesalahan
                                echo "Error: " . $sql_merek_tipe . "<br>" . $conn->error;
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="data-aktual">
                        <label for="dt_aktual" class="input-data-label">Data Aktual</label>
                        <input type="text" class="input-data" name="dt_aktual" id="dt_aktual" placeholder="Masukkan data aktual" required value="<?php echo isset($data_penjualan['dt_aktual']) ? $data_penjualan['dt_aktual'] : ''; ?>">
                    </div>

                    <div class="mb-3 ms-5">
                        <label for="admin" class="input-data-label">Admin</label>
                        <input type="text" class="input-data" name="admin" id="admin" placeholder="Masukkan nama admin" required value="<?php echo isset($data_penjualan['admin']) ? $data_penjualan['admin'] : ''; ?>">
                    </div>
                </div>

                <div class="d-flex">
                    <button type="button" class="btn btn-custom me-2" id="saveBtn">Simpan Data</button>
                    <a href="../view/data-penjualan.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>

        <!-- Script Untuk Pengambilan Tipe Berdasarkan Merek -->
        <script>
            // Ambil elemen select merek dan tipe
            const merekSelect = document.getElementById('merekSelect');
            const tipeSelect = document.getElementById('tipeSelect');

            // Fungsi untuk memperbarui opsi tipe berdasarkan merek yang dipilih
            function updateTipeOptions() {
                const selectedMerek = merekSelect.value;

                // Kosongkan opsi tipe sebelum memperbarui
                tipeSelect.innerHTML = '<option selected>Pilih tipe</option>';

                // Kirim permintaan AJAX untuk mendapatkan data tipe yang berhubungan dengan merek yang dipilih
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            const tipes = JSON.parse(xhr.responseText);

                            // Hapus opsi tipe yang sudah ada
                            while (tipeSelect.firstChild) {
                                tipeSelect.removeChild(tipeSelect.firstChild);
                            }

                            // Tambahkan opsi tipe baru
                            tipes.forEach(tipe => {
                                const option = document.createElement('option');
                                option.value = tipe;
                                option.textContent = tipe;
                                tipeSelect.appendChild(option);
                            });
                        } else {
                            console.error('Error fetching data:', xhr.statusText);
                        }
                    }
                };
                xhr.open('POST', '../process/get_tipe.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('merek=' + encodeURIComponent(selectedMerek));
            }

            // Panggil fungsi untuk memperbarui opsi tipe saat halaman dimuat
            updateTipeOptions();

            // Tambahkan event listener untuk memperbarui opsi tipe saat merek dipilih
            merekSelect.addEventListener('change', updateTipeOptions);
        </script>

        <!-- SweetAlert2 script -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.getElementById('saveBtn').addEventListener('click', function() {
                // Mendapatkan nilai input bulan, tahun, merek, tipe, dan data aktual
                const bulanInput = document.querySelector('select[name="bulan"]').value;
                const tahunInput = document.querySelector('select[name="tahun"]').value;
                const merekInput = document.querySelector('select[name="merek"]').value;
                const tipeInput = document.querySelector('select[name="tipe"]').value;
                const dtAktualInput = document.getElementById('dt_aktual').value;
                const adminInput = document.getElementById('admin').value;

                // Memeriksa apakah semua input sudah diisi
                const isFormFilled = bulanInput.trim() !== '' && tahunInput.trim() !== '' && merekInput.trim() !== '' && tipeInput.trim() !== '' && dtAktualInput.trim() !== '' && adminInput.trim() !== '';

                if (isFormFilled) {
                    Swal.fire({
                        title: "Apakah Anda ingin menyimpan perubahan data?",
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: "Ya, Simpan",
                        denyButtonText: `Jangan Simpan`,
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: "Tersimpan!",
                                text: "Perubahan yang Anda buat sudah disimpan.",
                                icon: "success",
                                showConfirmButton: true,
                                confirmButtonText: "OK"
                            }).then(() => {
                                // Submit formulir setelah menampilkan pesan tersimpan
                                document.querySelector('form').submit();
                            });
                        } else if (result.isDenied) {
                            Swal.fire("Perubahan data tidak disimpan", "", "info").then(() => {
                                window.history.back();
                            });
                        }
                    });
                } else {
                    Swal.fire("Formulir belum lengkap", "Silakan isi semua data terlebih dahulu.", "warning");
                }
            });
        </script>

        <!-- Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>