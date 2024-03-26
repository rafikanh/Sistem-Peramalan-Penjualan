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
            <div class="d-flex">
                <a href="../view/forecasting.php"><i class="bi bi-arrow-left-short custom-icon-style"></i></a>&ensp;
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-3 me-5">History Peramalan</h1>
                </div>
            </div>

            <div class="scrollable-table mb-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Merek</th>
                            <th scope="col">Tipe</th>
                            <th scope="col">Tanggal Peramalan</th>
                            <th scope="col">Hasil Peramalan</th>
                            <th scope="col">Mape</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        </tr>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const xhr = new XMLHttpRequest();

            // Atur callback untuk menangani respons dari server
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Respons dari server adalah data penjualan dalam format JSON
                        const dataHistory = JSON.parse(xhr.responseText);

                        updateTable(dataHistory);


                    } else {
                        console.error('Error post data:', xhr.statusText);
                    }
                }
            };

            // Atur jenis dan URL permintaan
            xhr.open('GET', '../process/get-forecast-history.php', true);

            // Atur header permintaan
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            // Kirim permintaan
            xhr.send();
        });

        function updateTable(dataHistory) {
            const tbody = document.querySelector('.table tbody');

            // Kosongkan isi tbody sebelum memperbarui
            tbody.innerHTML = '';

            // Loop melalui data penjualan dan tambahkan baris baru ke dalam tbody
            dataHistory.forEach(function(rowData, index) {
                const row = document.createElement('tr');

                // Loop melalui setiap kolom data dan tambahkan ke dalam baris
                Object.entries(rowData).forEach(function([key, value]) {
                    if (key !== 'id_brg') { // Skip kolom ID Barang
                        const cell = document.createElement('td');
                        cell.textContent = value + (key == 'mape' ? ' %' : '');
                        row.appendChild(cell);
                    }
                });


                tbody.appendChild(row);
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>