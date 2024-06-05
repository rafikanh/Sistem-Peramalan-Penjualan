<?php require_once '../process/check_login.php'; ?>

<!DOCTYPE html>
<html>

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
    <div class="content-large">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-3 me-5">Peramalan</h1>
                <a href="../view/history.php" type="button" class="btn btn-info">History</a>
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
                            <th scope="col">Tanggal</th>
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
        document.addEventListener('DOMContentLoaded', function() {
            const buttonHitung = document.getElementById('hitungButton');
            const merekSelect = document.getElementById('merekSelect');
            const tipeSelect = document.getElementById('tipeSelect');

            buttonHitung.addEventListener('click', function() {
                const selectedMerek = merekSelect.value;
                const selectedTipe = tipeSelect.value;

                if (selectedMerek === 'Pilih merek' || selectedTipe === 'Pilih tipe') {
                    alert('Merek dan tipe harus dipilih terlebih dahulu.');
                } else {
                    const xhr = new XMLHttpRequest();

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                const dataPenjualan = JSON.parse(xhr.responseText);
                                calculateForecast(dataPenjualan, selectedTipe);
                            } else {
                                console.error('Error fetching data:', xhr.statusText);
                            }
                        }
                    };

                    xhr.open('POST', '../process/get_penjualan.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.send('merek=' + encodeURIComponent(selectedMerek) + '&id_brg=' + encodeURIComponent(selectedTipe));
                }
            });

            function calculateForecast(dataPenjualan, selectedTipe) {
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            console.log(response);
                            const dataForecast = response['Best Forecasts'];

                            if (dataForecast.length >= 2) {
                                const nextMonth = getNextMonthYear(dataPenjualan);
                                const nextMonthYearElement = document.querySelector('.me-1');
                                nextMonthYearElement.textContent = 'Peramalan bulan ' + nextMonth.string;

                                const lastForecast = dataForecast[dataForecast.length - 1];
                                let nextForecastData = lastForecast['Level'] + lastForecast['Trend'];

                                // Set nextForecastData to 0 if it is negative
                                if (nextForecastData < 0) {
                                    nextForecastData = 0;
                                }

                                updateNextForecast(nextForecastData);

                                const resultMape = response["Lowest MAPE"];
                                const mapeValue = document.getElementById('mapeValue');
                                mapeValue.textContent = ' : ' + resultMape.toFixed(2) + '%';

                                saveCurrentForecast(selectedTipe, nextForecastData, resultMape, nextMonth.month, nextMonth.year);
                                updateTable(dataPenjualan, dataForecast);
                            }
                            alert(`Alpha Terbaik: ${response["Best Alpha"].toFixed(2)}, Beta Terbaik: ${response["Best Beta"].toFixed(2)}, MAPE Terendah: ${response["Lowest MAPE"].toFixed(2)}%`);
                        } else {
                            console.error('Error fetching data:', xhr.statusText);
                        }
                    }
                };
                xhr.open('POST', '../process/calculate_forecasting.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.send('data_penjualan=' + encodeURIComponent(JSON.stringify(dataPenjualan)));
            }

            function saveCurrentForecast(id_brg, res_forecast, mape, bulan, tahun) {
                // Set res_forecast to 0 if it is negative
                if (res_forecast < 0) {
                    res_forecast = 0;
                }

                const xhr = new XMLHttpRequest();

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            const result = JSON.parse(xhr.responseText);
                            console.log(result);
                        } else {
                            console.error('Error post data:', xhr.statusText);
                        }
                    }
                };

                xhr.open('POST', '../process/process-save-history.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.send('id_brg=' + encodeURIComponent(id_brg) + '&res_forecast=' + encodeURIComponent(JSON.stringify(res_forecast)) + '&mape=' + encodeURIComponent(mape) + '&bulan=' + encodeURIComponent(bulan) + '&tahun=' + encodeURIComponent(tahun));
            }

            function updateNextForecast(resultForecast) {
                const nextForecast = document.getElementById('nextForecast');
                if (resultForecast !== undefined && !isNaN(resultForecast)) {
                    // Set resultForecast to 0 if it is negative
                    if (resultForecast < 0) {
                        resultForecast = 0;
                    }
                    nextForecast.textContent = ' : ' + resultForecast.toFixed(2);
                } else {
                    nextForecast.textContent = ' : N/A';
                }
            }

            // Fungsi untuk memperbarui tabel dengan data penjualan dan hasil perhitungan DES
            function updateTable(dataPenjualan, forecasts) {
                const tbody = document.querySelector('.table tbody');
                tbody.innerHTML = '';

                dataPenjualan.forEach(function(rowData, index) {
                    const row = document.createElement('tr');

                    Object.values(rowData).forEach(function(value) {
                        const cell = document.createElement('td');
                        cell.textContent = value;
                        row.appendChild(cell);
                    });

                    const forecastData = forecasts[index];

                    const levelCell = document.createElement('td');
                    const trendCell = document.createElement('td');
                    const forecastCell = document.createElement('td');
                    const errorCell = document.createElement('td');
                    const abserrorCell = document.createElement('td');
                    const percentageerrorCell = document.createElement('td');

                    if (forecastData) {
                        // Jika nilai forecast negatif, atur menjadi 0
                        let forecastValue = forecastData['Forecast'] !== undefined ? forecastData['Forecast'] : NaN;
                        forecastValue = !isNaN(forecastValue) && forecastValue < 0 ? 0 : forecastValue;

                        levelCell.textContent = forecastData['Level'] !== undefined ? forecastData['Level'].toFixed(2) : 'N/A';
                        trendCell.textContent = forecastData['Trend'] !== undefined ? forecastData['Trend'].toFixed(2) : 'N/A';
                        forecastCell.textContent = !isNaN(forecastValue) ? forecastValue.toFixed(2) : 'N/A';
                        errorCell.textContent = forecastData['Error'] !== undefined ? forecastData['Error'].toFixed(2) : 'N/A';
                        abserrorCell.textContent = forecastData['Abs Error'] !== undefined ? forecastData['Abs Error'].toFixed(2) : 'N/A';
                        percentageerrorCell.textContent = forecastData['% Error'] !== undefined ? forecastData['% Error'].toFixed(2) + '%' : 'N/A';
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

            function getNextMonthYear(dataPenjualan) {
                const lastMonth = dataPenjualan[dataPenjualan.length - 1].bulan_tahun.split(' ')[0];
                const lastYear = parseInt(dataPenjualan[dataPenjualan.length - 1].bulan_tahun.split(' ')[1]);

                const lastMonthNumber = getMonthNumber(lastMonth);
                const nextMonth = lastMonthNumber === 12 ? 1 : lastMonthNumber + 1;
                const nextYear = lastMonthNumber === 12 ? lastYear + 1 : lastYear;

                return {
                    month: nextMonth,
                    year: nextYear,
                    string: getMonthString(nextMonth) + ' ' + nextYear
                };
            }
        });
    </script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="../utils/dateformat_utils.js"></script>
</body>

</html>