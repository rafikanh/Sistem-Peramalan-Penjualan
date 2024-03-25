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
                <div class="grid-item">
                    <div class="d-flex">
                        <div class="me-1">Peramalan bulan berikutnya</div>
                        <div id="nextForecast"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div id='countPercentError' hidden></div>
                    <div class="d-flex">
                        <div class="me-1">MAPE</div>
                        <div id='mapeValue'></div>
                    </div>
                </div>
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

                const alpha = localStorage.getItem('alpha');
                const beta = localStorage.getItem('beta')

                const selectedMerek = merekSelect.value;
                const selectedTipe = tipeSelect.value;

                if (alpha == null || beta == null) {
                    alert('Nilai alpha dan beta harus diisi terlebih dahulu.');
                } else if (selectedMerek == 'Pilih merek' || selectedTipe == 'Pilih tipe') {
                    alert('Merek dan tipe harus dipilih terlebih dahulu.');
                } else {
                    // Buat objek XMLHttpRequest
                    const xhr = new XMLHttpRequest();

                    // Atur callback untuk menangani respons dari server
                    xhr.onreadystatechange = async function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                // Respons dari server adalah data penjualan dalam format JSON
                                const dataPenjualan = JSON.parse(xhr.responseText);

                                // Lakukan perhitungan DES di sini   
                                calculateForecast(dataPenjualan);

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
                }


            });

            function calculateForecast(dataPenjualan, selectedTipe) {

                const xhr = new XMLHttpRequest();

                // Atur callback untuk menangani respons dari server
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Respons dari server adalah data penjualan dalam format JSON
                            const dataForecast = JSON.parse(xhr.responseText);

                            const lastMonth = dataPenjualan[dataPenjualan.length - 1].Bulan;
                            const lastYear = dataPenjualan[dataPenjualan.length - 1].Tahun;

                            // Tentukan bulan berikutnya
                            const nextMonth = lastMonth === 12 ? 1 : lastMonth + 1;

                            // Tentukan tahun berikutnya
                            const nextYear = nextMonth === 1 ? lastYear + 1 : lastYear;

                            // Buat string bulan dan tahun berikutnya
                            const nextMonthYearString = nextYear + '-' + (nextMonth < 10 ? '0' + nextMonth : nextMonth);

                            // Tampilkan bulan dan tahun berikutnya
                            const nextMonthYearElement = document.querySelector('.me-1');
                            nextMonthYearElement.textContent = 'Peramalan bulan ' + nextMonthYearString;

                            //update table penjualan
                            updateNextForecast(dataForecast[dataForecast.length - 1]['Forecast']);
                            calculateMape(dataForecast);
                            updateTable(dataPenjualan, dataForecast);
                        } else {
                            console.error('Error fetching data:', xhr.statusText);
                        }
                    }
                };

                // Atur jenis dan URL permintaan
                xhr.open('POST', '../process/calculate_forecasting.php', true);

                // Atur header permintaan
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                // Kirim permintaan dengan merek dan tipe yang dipilih sebagai data POST
                xhr.send('alpha=' + encodeURIComponent(localStorage.getItem('alpha')) + '&beta=' + encodeURIComponent(localStorage.getItem('beta')) + '&data_penjualan=' + encodeURIComponent(JSON.stringify(dataPenjualan)));
            }

            function updateNextForecast(resultForecast) {
                const nextForecast = document.getElementById('nextForecast');

                nextForecast.textContent = resultForecast.toFixed(2);

            }

            function calculateMape(dataForecast) {
                const mapeValue = document.getElementById('mapeValue');
                const countPercentError = document.getElementById('countPercentError');

                let totalPercentError = 0;

                dataForecast.forEach(element => {
                    totalPercentError += element['% Error'];
                });

                countPercentError.textContent = totalPercentError.toFixed(2) + '%';
                mapeValue.textContent = (totalPercentError / (dataForecast.length - 1)).toFixed(2) + ' %';
            }

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
                        levelCell.textContent = forecasts[index]['Level'].toFixed(2);
                        trendCell.textContent = forecasts[index]['Trend'].toFixed(2);
                        forecastCell.textContent = forecasts[index]['Forecast'].toFixed(2);
                        errorCell.textContent = forecasts[index]['Error'].toFixed(2);
                        abserrorCell.textContent = forecasts[index]['Abs Error'].toFixed(2);
                        percentageerrorCell.textContent = forecasts[index]['% Error'].toFixed(2) + '%';
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
            // Bersihkan nilai alpha dan beta dari local storage saat halaman dimuat
            localStorage.removeItem('alpha');
            localStorage.removeItem('beta');

            // Ambil elemen input alpha dan beta
            const alphaInput = document.getElementById('alpha');
            const betaInput = document.getElementById('beta');

            // Setel nilai input alpha dan beta ke kosong saat halaman dimuat
            alphaInput.value = '';
            betaInput.value = '';

            // Ambil elemen tombol "Pen" untuk alpha dan beta
            const buttonAlpha = document.getElementById('alphaButton');
            const buttonBeta = document.getElementById('betaButton');

            // Tambahkan event listener untuk tombol "Pen" alpha
            buttonAlpha.addEventListener('click', function() {
                // Ambil nilai alpha dari input
                const alphaInputValue = parseFloat(alphaInput.value);

                // Periksa apakah nilai alpha berada dalam rentang yang diizinkan (0.1 - 0.9)
                if (alphaInputValue >= 0.1 && alphaInputValue <= 0.9) {
                    // Simpan nilai alpha ke dalam localStorage dengan kunci "alpha"
                    localStorage.setItem('alpha', alphaInputValue);

                    // Beri notifikasi bahwa nilai alpha telah disimpan
                    alert('Nilai alpha telah disimpan: ' + alphaInputValue);
                } else {
                    // Tampilkan alert jika nilai alpha tidak berada dalam rentang yang diizinkan
                    alert('Nilai alpha harus berada dalam rentang 0.1 sampai 0.9');
                }
            });

            // Tambahkan event listener untuk tombol "Pen" beta
            buttonBeta.addEventListener('click', function() {
                // Ambil nilai beta dari input
                const betaInputValue = parseFloat(betaInput.value);

                // Periksa apakah nilai beta berada dalam rentang yang diizinkan (0.1 - 0.9)
                if (betaInputValue >= 0.1 && betaInputValue <= 0.9) {
                    // Simpan nilai beta ke dalam localStorage dengan kunci "beta"
                    localStorage.setItem('beta', betaInputValue);

                    // Beri notifikasi bahwa nilai beta telah disimpan
                    alert('Nilai beta telah disimpan: ' + betaInputValue);
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