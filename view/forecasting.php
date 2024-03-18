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
                <button type="button" class="btn btn-info">History</button>
            </div>

            <div class="d-flex">
                <div class="mb-1">
                    <p class="text-forecasting">Nilai alpha saat ini adalah ...</p>
                </div>
                <div class="mb-1 ms-5">
                    <input type="text" class="input-data-forecasting" id="alpha" placeholder="Masukkan nilai alpha">
                </div>
                <div class="mb-1 ms-2">
                    <button class="btn btn-custom custom-button">
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
                    <button class="btn btn-custom custom-button">
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

            <div class="d-flex">
                <div class="mb-3">
                    <label for="merek" class="input-data-label">Merek</label>
                    <select class="form-select" aria-label="Default select example" name="merek" id="merekSelect" onchange="updateTipeOptions()">
                        <option selected>Pilih merek</option>
                        <?php foreach ($merekOptions as $merek) : ?>
                            <option value="<?php echo $merek; ?>"><?php echo $merek; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3 ms-5">
                    <label for="tipe" class="input-data-label">Tipe</label>
                    <select class="form-select" aria-label="Default select example" name="tipe" id="tipeSelect">
                        <option selected>Pilih tipe</option>
                    </select>
                </div>
            </div>

            <div class="scrollable-table-container">
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

            <h4>Peramalan bulan berikutnya ....</h4>
            <h4>MAPE ....%</h4>
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
                            option.value = tipe;
                            option.textContent = tipe;
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
        // Tambahkan event listener untuk memperbarui tabel saat tipe dipilih
        tipeSelect.addEventListener('change', function() {
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

                        // Perbarui tabel dengan data yang diterima
                        updateTable(dataPenjualan);
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
            xhr.send('merek=' + encodeURIComponent(selectedMerek) + '&tipe=' + encodeURIComponent(selectedTipe));
        });

        // Fungsi untuk memperbarui tabel dengan data penjualan
        function updateTable(dataPenjualan) {
            // Dapatkan elemen tbody dari tabel
            const tbody = document.querySelector('.table tbody');

            // Kosongkan isi tbody sebelum memperbarui
            tbody.innerHTML = '';

            // Loop melalui data penjualan dan tambahkan baris baru ke dalam tbody
            dataPenjualan.forEach(function(rowData) {
                const row = document.createElement('tr');

                // Loop melalui setiap kolom data dan tambahkan ke dalam baris
                Object.values(rowData).forEach(function(value) {
                    const cell = document.createElement('td');
                    cell.textContent = value;
                    row.appendChild(cell);
                });

                tbody.appendChild(row);
            });
        }
    </script>

    <!-- Bootstrap -->
    <<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>
</body>

</html>