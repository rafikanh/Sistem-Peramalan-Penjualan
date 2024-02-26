<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Style -->
    <link rel="stylesheet" href="../css/style.css">
    <title>Sistem Peramalan Penjualan</title>
</head>

<body>
    <?php include '../view/component/sidebar.php'; ?>
    <div class="content-large">
        <div class="container-fluid">
            <h1>Hai, Admin!</h1>
            <p>Selamat datang di website Sistem Peramalan Penjualan</p>

            <!-- Container untuk line chart -->
            <div class="chart-container">
                <canvas id="myLineChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Script untuk line chart -->
    <script>
        // Data contoh
        var data = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Penjualan',
                data: [50, 75, 60, 90, 80],
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2
            }]
        };

        // Konfigurasi line chart
        var options = {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // Inisialisasi line chart
        var ctx = document.getElementById('myLineChart').getContext('2d');
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: options
        });
    </script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>