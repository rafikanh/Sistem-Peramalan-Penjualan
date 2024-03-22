<!DOCTYPE html>
<html>

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
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-3 me-5">Peramalan</h1>
                <a href="../view/history.php" type="button" class="btn btn-info">History</a>
            </div>

            <div class="d-flex">
                <div class="mb-1">
                    <p class="text-forecasting">Nilai alpha saat ini adalah ...</p>
                </div>
                <div class="mb-1 ms-5">
                    <input type="text" class="input-data-forecasting" id="alpha" placeholder="Masukkan nilai alpha">
                </div>
                <div class="mb-1 ms-2">
                    <button class="btn btn-custom custom-button" id="alphaButton">
                        <i class="bi bi-pen"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex">
                <div class="mb-3 beta">
                    <p class="text-forecasting">Nilai beta saat ini adalah ...</p>
                </div>
                <div class="mb-3 ms-5">
                    <input type="text" class="input-data-forecasting" id="beta" placeholder="Masukkan nilai beta">
                </div>
                <div class="mb-3 ms-2">
                    <button class="btn btn-custom custom-button" id="betaButton">
                        <i class="bi bi-pen"></i>
                    </button>
                </div>
            </div>

            <?php
            include '../koneksi.php';

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
            ?>

<?php
            // Inisialisasi variabel alpha dan beta tanpa nilai default
            $alpha = null;
            $beta = null;

            // Perbarui nilai alpha dan beta jika ada data yang dikirimkan
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['alpha']) && isset($_POST['beta'])) {
                    $alpha = $_POST['alpha'];
                    $beta = $_POST['beta'];
                }
            }

            // Fungsi untuk mengambil data penjualan dari database berdasarkan merek dan id barang yang dipilih
            function getDataPenjualan($selectedMerek, $selectedTipe)
            {
                // Sertakan file koneksi ke database
                include '../koneksi.php';

                // Escape string untuk mencegah SQL injection
                $merek = isset($selectedMerek) ? $conn->real_escape_string($selectedMerek) : '';
                $id_brg = isset($selectedTipe) ? $conn->real_escape_string($selectedTipe) : '';

                // Query SQL untuk mengambil data penjualan berdasarkan merek dan id_brg
                $sql = "SELECT DATE_FORMAT(STR_TO_DATE(CONCAT_WS('-', dp.tahun, dp.bulan, '01'), '%Y-%m-%d'), '%M %Y') AS bulan_tahun, dp.dt_aktual
                FROM dt_penjualan AS dp
                INNER JOIN dt_barang AS db ON dp.id_brg = db.id_brg
                WHERE db.merek = '$merek' AND db.id_brg = '$id_brg'
                ORDER BY dp.tahun, dp.bulan";

                // Lakukan query ke database
                $result = $conn->query($sql);

                // Periksa apakah query berhasil dieksekusi
                if ($result === false) {
                    die("Error executing the query: " . $conn->error);
                }

                // Inisialisasi array untuk menyimpan data penjualan
                $dataPenjualan = [];

                // Loop untuk menyimpan setiap baris data penjualan dalam array
                while ($row = $result->fetch_assoc()) {
                    $dataPenjualan[] = $row;
                }

                // Kembalikan data penjualan dalam format JSON
                return $dataPenjualan;
            }

            // Ambil data penjualan berdasarkan tipe yang dipilih
            $selectedMerek = $_POST['merek'] ?? null;
            $selectedTipe = $_POST['id_brg'] ?? null;
            $dataPenjualan = getDataPenjualan($selectedMerek, $selectedTipe);

            // Inisialisasi variabel alpha dan beta tanpa nilai default
            $alpha = isset($_POST['alpha']) ? $_POST['alpha'] : (isset($_COOKIE['alpha']) ? $_COOKIE['alpha'] : null);
            $beta = isset($_POST['beta']) ? $_POST['beta'] : (isset($_COOKIE['beta']) ? $_COOKIE['beta'] : null);

            // Inisialisasi array untuk menyimpan hasil perhitungan DES
            $forecasts = [];

            foreach ($dataPenjualan as $index => $item) {
                // Ambil nilai data aktual
                $actual = $item["dt_aktual"];

                // Hitung level dan trend
                $level = ($index == 0) ? $actual : $alpha * $actual + (1 - $alpha) * ($forecasts[$index - 1]["Level"] + $forecasts[$index - 1]["Trend"]);
                $trend = ($index == 0) ? 0 : $beta * ($level - $forecasts[$index - 1]["Level"]) + (1 - $beta) * $forecasts[$index - 1]["Trend"];

                // Hitung forecast DES
                $forecast = $level + $trend;

                // Hitung error
                $error = $actual - $forecast;

                // Hitung absolute error
                $absError = abs($error);

                // Hitung percentage error (menghindari pembagian oleh nol)
                $percentError = ($actual != 0) ? ($error / $actual) * 100 : 0;

                // Simpan hasil perhitungan
                $forecasts[] = [
                    "Level" => $level,
                    "Trend" => $trend,
                    "Forecast" => $forecast,
                    "Error" => $error,
                    "Abs Error" => $absError,
                    "% Error" => $percentError,
                ];
            }

            // Kembalikan hasil perhitungan dalam format JSON
            echo json_encode($forecasts);
            ?>

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

                <div class="button-count">
                    <button id="hitungButton" class="btn btn-custom custom-button">Hitung</button>
                </div>
            </div>

            <div class="scrollable-table-container mb-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Bulan Tahun</th>
                            <th scope="col">Nilai Aktual</th>
                            <th scope="col">Level</th>
                            <th scope="col">Trend</th>
                            <th scope="col">Nilai Peramalan</th>
                            <th scope="col">Error</th>
                            <th scope="col">Abs Error</th>
                            <th scope="col">% Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        </tr>
                </table>
            </div>

            <div class="grid">
                <div class="grid-item">Peramalan bulan berikutnya...</div>
                <div class="grid-item">MAPE...%</div>
            </div>
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

    <!-- Script Untuk Pengambilan Data Bulan Tahun dan Data Aktual Berdasarkan Tipe -->
    <script>
        // Script untuk menyimpan nilai alpha dan beta
        document.addEventListener('DOMContentLoaded', function() {
            const buttonHitung = document.getElementById('hitungButton');

            // Tambahkan event listener untuk tombol "Hitung"
            buttonHitung.addEventListener('click', function() {
                const selectedMerek = merekSelect.value;
                const selectedTipe = tipeSelect.value;

                // Buat objek XMLHttpRequest
                const xhr = new XMLHttpRequest();

                // Atur callback untuk menangani respons dari server
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Respons dari server adalah data penjualan dalam format JSON
                            const dataPenjualan = JSON.parse(xhr.responseText);

                            // Lakukan perhitungan DES di sini dan simpan hasilnya dalam variabel
                            const forecasts = <?php echo json_encode($forecasts); ?>;

                            // Perbarui tabel dengan data yang diterima
                            updateTable(dataPenjualan, forecasts);
                        } else {
                            console.error('Error fetching data:', xhr.statusText);
                        }
                    }
                };

                // Atur jenis dan URL permintaan
                xhr.open('POST', '../process/get_penjualan.php', true);

                // Atur header permintaan
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                // Kirim permintaan dengan merek dan tipe yang dipilih sebagai data POST
                xhr.send('merek=' + encodeURIComponent(selectedMerek) + '&id_brg=' + encodeURIComponent(selectedTipe));
            });

            // Fungsi untuk memperbarui tabel dengan data penjualan dan hasil perhitungan DES
            function updateTable(dataPenjualan, forecasts) {
                // Dapatkan elemen tbody dari tabel
                const tbody = document.querySelector('.table tbody');

                // Kosongkan isi tbody sebelum memperbarui
                tbody.innerHTML = '';

                // Loop melalui data penjualan dan tambahkan baris baru ke dalam tbody
                dataPenjualan.forEach(function(rowData, index) {
                    const row = document.createElement('tr');

                    // Loop melalui setiap kolom data dan tambahkan ke dalam baris
                    Object.values(rowData).forEach(function(value) {
                        const cell = document.createElement('td');
                        cell.textContent = value;
                        row.appendChild(cell);
                    });

                    // Tambahkan nilai Level, Trend, dan Forecast dari array forecasts ke dalam baris
                    const levelCell = document.createElement('td');
                    const trendCell = document.createElement('td');
                    const forecastCell = document.createElement('td');
                    const errorCell = document.createElement('td');
                    const abserrorCell = document.createElement('td');
                    const percentageerrorCell = document.createElement('td');

                    // Pastikan indeks `index` tidak melebihi panjang array `forecasts`
                    if (index < forecasts.length) {
                        levelCell.textContent = forecasts[index]['Level'];
                        trendCell.textContent = forecasts[index]['Trend'];
                        forecastCell.textContent = forecasts[index]['Forecast'];
                        errorCell.textContent = forecasts[index]['Error'];
                        abserrorCell.textContent = forecasts[index]['Abs Error'];
                        percentageerrorCell.textContent = forecasts[index]['% Error'];
                    } else {
                        levelCell.textContent = 'N/A';
                        trendCell.textContent = 'N/A';
                        forecastCell.textContent = 'N/A';
                        errorCell.textContent = 'N/A';
                        abserrorCell.textContent = 'N/A';
                        percentageerrorCell.textContent = 'N/A';
                    }

                    row.appendChild(levelCell);
                    row.appendChild(trendCell);
                    row.appendChild(forecastCell);
                    row.appendChild(errorCell);
                    row.appendChild(abserrorCell);
                    row.appendChild(percentageerrorCell);

                    tbody.appendChild(row);
                });
            }
        });
    </script>

    <!-- Script untuk menyimpan nilai alpha dan beta -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil elemen tombol "Pen" untuk alpha dan beta
            const buttonAlpha = document.getElementById('alphaButton');
            const buttonBeta = document.getElementById('betaButton');

            // Tambahkan event listener untuk tombol "Pen" alpha
            buttonAlpha.addEventListener('click', function() {
                // Ambil nilai alpha dari input
                const alphaInput = parseFloat(document.getElementById('alpha').value);

                // Periksa apakah nilai alpha berada dalam rentang yang diizinkan (0.1 - 0.9)
                if (alphaInput >= 0.1 && alphaInput <= 0.9) {
                    // Simpan nilai alpha ke dalam localStorage dengan kunci "alpha"
                    localStorage.setItem('alpha', alphaInput);

                    // Beri notifikasi bahwa nilai alpha telah disimpan
                    alert('Nilai alpha telah disimpan: ' + alphaInput);
                } else {
                    // Tampilkan alert jika nilai alpha tidak berada dalam rentang yang diizinkan
                    alert('Nilai alpha harus berada dalam rentang 0.1 sampai 0.9');
                }
            });

            // Tambahkan event listener untuk tombol "Pen" beta
            buttonBeta.addEventListener('click', function() {
                // Ambil nilai beta dari input
                const betaInput = parseFloat(document.getElementById('beta').value);

                // Periksa apakah nilai beta berada dalam rentang yang diizinkan (0.1 - 0.9)
                if (betaInput >= 0.1 && betaInput <= 0.9) {
                    // Simpan nilai beta ke dalam localStorage dengan kunci "beta"
                    localStorage.setItem('beta', betaInput);

                    // Beri notifikasi bahwa nilai beta telah disimpan
                    alert('Nilai beta telah disimpan: ' + betaInput);
                } else {
                    // Tampilkan alert jika nilai beta tidak berada dalam rentang yang diizinkan
                    alert('Nilai beta harus berada dalam rentang 0.1 sampai 0.9');
                }
            });
        });
    </script>


    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>