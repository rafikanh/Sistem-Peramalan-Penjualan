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
    <title>Sistem Peramalan Penjualan</title>
</head>

<body>
    <?php include '../view/component/sidebar.php'; ?>
    <div class="content-large">
        <div class="container-fluid">
            <h1 class="mb-4">Tambah Data Penjualan</h1>

            <?php
            // Sertakan file koneksi
            include '../koneksi.php';

            // Query SQL untuk mengambil data bulan dari tabel dt_penjualan tanpa duplikasi
            $sql_bulan = "SELECT DISTINCT bulan FROM dt_penjualan";
            $result_bulan = $conn->query($sql_bulan);

            // Periksa apakah query berhasil dieksekusi
            if ($result_bulan === false) {
                die("Error executing the query for month: " . $conn->error);
            }

            // Query SQL untuk mengambil data tahun dari tabel dt_penjualan tanpa duplikasi
            $sql_tahun = "SELECT DISTINCT tahun FROM dt_penjualan";
            $result_tahun = $conn->query($sql_tahun);

            // Periksa apakah query berhasil dieksekusi
            if ($result_tahun === false) {
                die("Error executing the query for year: " . $conn->error);
            }

            // Query SQL untuk mengambil data merek dari tabel dt_barang tanpa duplikasi
            $sql_merek = "SELECT DISTINCT merek FROM dt_barang";
            $result_merek = $conn->query($sql_merek);

            // Periksa apakah query berhasil dieksekusi
            if ($result_merek === false) {
                die("Error executing the query for brand: " . $conn->error);
            }

            // Inisialisasi array untuk menyimpan data merek
            $merekOptions = [];

            // Loop untuk menyimpan data merek dalam array
            while ($row = $result_merek->fetch_assoc()) {
                $merekOptions[] = $row['merek'];
            }

            // Mendefinisikan struktur data untuk menyimpan tipe berdasarkan merek
            $tipeData = [];

            // Loop untuk mengambil data tipe untuk setiap merek
            foreach ($merekOptions as $merek) {
                // Query SQL untuk mengambil data tipe untuk merek tertentu
                $sql_tipe_per_merek = "SELECT DISTINCT tipe FROM dt_barang WHERE merek = '$merek'";
                $result_tipe_per_merek = $conn->query($sql_tipe_per_merek);

                // Periksa apakah query berhasil dieksekusi
                if ($result_tipe_per_merek === false) {
                    die("Error executing the query for type: " . $conn->error);
                }

                // Inisialisasi array untuk menyimpan tipe untuk merek tertentu
                $tipes = [];

                // Loop untuk mengambil data tipe
                while ($tipe = $result_tipe_per_merek->fetch_assoc()) {
                    $tipes[] = $tipe['tipe'];
                }

                // Tambahkan merek dan tipe ke dalam struktur tipeData
                $tipeData[$merek] = $tipes;
            }

            // Ambil tahun terakhir dari tabel dt_penjualan
            $sql_latest_year = "SELECT MAX(tahun) AS latest_year FROM dt_penjualan";
            $result_latest_year = $conn->query($sql_latest_year);

            if ($result_latest_year === false) {
                die("Error executing the query to get the latest year: " . $conn->error);
            }

            $latest_year_row = $result_latest_year->fetch_assoc();
            $latest_year = $latest_year_row['latest_year'];

            // Ambil tahun terbaru dari tabel dt_penjualan
            $current_year = date('Y');

            // Tambahkan satu tahun untuk mendapatkan tahun yang diinginkan
            $desired_year = $current_year;
            if ($latest_year >= $desired_year) {
                $desired_year = $latest_year + 1;
            }
            ?>

            <form method="POST" action="../process/process-add-data-penjualan.php">
                <div class="d-flex">
                    <div class="mb-3">
                        <label for="bulan" class="input-data-label">Bulan</label>
                        <select class="form-select" aria-label="Default select example" name="bulan" id="bulan">
                            <option selected>Pilih bulan</option>
                            <?php while ($row = $result_bulan->fetch_assoc()) : ?>
                                <?php
                                // Konversi nilai bulan integer menjadi nama bulan
                                $nama_bulan = date('F', mktime(0, 0, 0, $row['bulan'], 1));
                                ?>
                                <option value="<?php echo $row['bulan']; ?>"><?php echo $nama_bulan; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3 ms-5">
                        <label for="tahun" class="input-data-label">Tahun</label>
                        <select class="form-select" aria-label="Default select example" name="tahun" id="tahun">
                            <option selected>Pilih tahun</option>
                            <?php while ($row = $result_tahun->fetch_assoc()) : ?>
                                <option value="<?php echo $row['tahun']; ?>"><?php echo $row['tahun']; ?></option>
                            <?php endwhile; ?>
                            <?php
                            if ($latest_year < $desired_year) {
                                echo "<option value='$desired_year'>$desired_year</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="mb-3">
                        <label for="merekSelect" class="input-data-label">Merek</label>
                        <select class="form-select" aria-label="Default select example" name="merek" id="merekSelect" onchange="updateTipeOptions()">
                            <option selected>Pilih merek</option>
                            <?php foreach ($merekOptions as $merek) : ?>
                                <option value="<?php echo $merek; ?>"><?php echo $merek; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3 ms-5">
                        <label for="tipeSelect" class="input-data-label">Tipe</label>
                        <select class="form-select" aria-label="Default select example" name="tipe" id="tipeSelect">
                            <option selected>Pilih tipe</option>
                        </select>
                    </div>
                </div>

                <div class="data-aktual">
                    <label for="dt_aktual" class="input-data-label">Data Aktual</label>
                    <input type="text" class="input-data" name="dt_aktual" id="dt_aktual" placeholder="Masukkan data aktual" required>
                </div>

                <div class="d-flex">
                    <button type="button" class="btn btn-custom me-2" id="saveBtn">Simpan Data</button>
                    <a href="../view/data-penjualan.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Untuk Pengambilan Tipe Berdasarkan Merek -->
    <script>
        // Ambil elemen select merek dan tipe
        const merekSelect = document.getElementById('merekSelect');
        const tipeSelect = document.getElementById('tipeSelect');

        // Definisikan data tipe untuk setiap merek dari PHP
        const tipeData = <?php echo json_encode($tipeData); ?>;

        // Fungsi untuk memperbarui opsi tipe berdasarkan merek yang dipilih
        function updateTipeOptions() {
            const selectedMerek = merekSelect.value;
            const tipes = tipeData[selectedMerek] || [];

            // Kosongkan opsi tipe sebelum memperbarui
            tipeSelect.innerHTML = '<option selected>Pilih tipe</option>';

            // Tambahkan opsi tipe sesuai dengan merek yang dipilih
            tipes.forEach(tipe => {
                const option = document.createElement('option');
                option.value = tipe;
                option.textContent = tipe;
                tipeSelect.appendChild(option);
            });
        }

        // Panggil fungsi untuk memperbarui opsi tipe saat halaman dimuat
        updateTipeOptions();

        // Tambahkan event listener untuk memperbarui opsi tipe saat merek dipilih
        merekSelect.addEventListener('change', function() {
            const selectedMerek = merekSelect.value;

            // Buat objek XMLHttpRequest
            const xhr = new XMLHttpRequest();

            // Atur callback untuk menangani respons dari server
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Respons dari server adalah data tipe dalam format JSON
                        const tipes = JSON.parse(xhr.responseText);

                        // Kosongkan opsi tipe sebelum memperbarui
                        tipeSelect.innerHTML = '<option selected>Pilih tipe</option>';

                        // Tambahkan opsi tipe sesuai dengan merek yang dipilih
                        tipes.forEach(tipe => {
                            const option = document.createElement('option');
                            option.value = tipe['id_brg'];
                            option.textContent = tipe['tipe'];
                            tipeSelect.appendChild(option);
                        });
                    } else {
                        console.error('Error fetching data:', xhr.statusText);
                    }
                }
            };

            // Atur jenis dan URL permintaan
            xhr.open('POST', '../process/get_tipe.php', true);

            // Atur header permintaan
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            // Kirim permintaan dengan merek yang dipilih sebagai data POST
            xhr.send('merek=' + encodeURIComponent(selectedMerek));
        });
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

            // Memeriksa apakah semua input sudah diisi
            const isFormFilled = bulanInput.trim() !== '' && tahunInput.trim() !== '' && merekInput.trim() !== '' && tipeInput.trim() !== '' && dtAktualInput.trim() !== '';

            if (isFormFilled) {
                // Buat objek XMLHttpRequest
                const xhr = new XMLHttpRequest();

                // Atur callback untuk menangani respons dari server
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Respons dari server adalah apakah data sudah ada atau belum
                            const isDataExists = JSON.parse(xhr.responseText);

                            // Jika data sudah ada, tampilkan alert warning
                            if (isDataExists) {
                                // Konversi nilai bulan dari angka menjadi nama bulan
                                const namaBulan = convertToMonthName(bulanInput);

                                Swal.fire("Data sudah tersedia", `Data untuk bulan ${namaBulan} tahun ${tahunInput} dengan merek dan tipe yang sama sudah tersedia.`, "warning");
                            } else {
                                // Jika data belum ada, tampilkan konfirmasi untuk menyimpan
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
                                            // Submit formulir setelah menampilkan pesan tersimpan
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

                // Atur jenis dan URL permintaan
                xhr.open('POST', '../process/check_duplicate_data.php', true);

                // Atur header permintaan
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                // Kirim permintaan dengan data yang dikirimkan dari formulir
                xhr.send('bulan=' + encodeURIComponent(bulanInput) + '&tahun=' + encodeURIComponent(tahunInput) + '&tipe=' + encodeURIComponent(tipeInput));
            } else {
                Swal.fire("Formulir belum lengkap", "Silakan isi semua data terlebih dahulu.", "warning");
            }
        });

        // Fungsi untuk mengonversi angka bulan menjadi nama bulan
        function convertToMonthName(monthNumber) {
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            return monthNames[monthNumber - 1];
        }
    </script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>