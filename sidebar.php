<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Style -->
    <link rel="stylesheet" href="css/sidebar.css">
    <title>Sistem Peramalan Penjualan</title>
</head>

<body>
    <div class="bg">
        <div class="d-flex flex-column flex-shrink-0 p-3" style="width: 280px;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <img src="assets/img/logo.png" class="logo"><br>&ensp; &ensp; &ensp;
                <span class="fs-4">Sistem Peramalan Penjualan</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li>
                    <a href="dashboard.php" class="nav-link link-dark">
                        <i class="bi bi-house"></i></i>&ensp;
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="data-barang.php" class="nav-link link-dark">
                        <i class="bi bi-box"></i></i>&ensp;
                        Data Barang
                    </a>
                </li>
                <li>
                    <a href="data-penjualan.php" class="nav-link link-dark">
                        <i class="bi bi-receipt"></i></i>&ensp;
                        Data Penjualan
                    </a>
                </li>
                <li>
                    <a href="forecasting.php" class="nav-link link-dark">
                        <i class="bi bi-clipboard-data"></i>&ensp;
                        Peramalan
                    </a>
                </li>
                <li>
                    <a href="user-manajemen.php" class="nav-link link-dark">
                        <i class="bi bi-people"></i>&ensp;
                        User Manajemen
                    </a>
                </li>
            </ul>
            <hr>
            <a href="index.php" class="nav-link link-logout">
                <i class="bi bi-box-arrow-left"></i></i>&ensp;
                Keluar
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Ambil path URL saat ini
            var path = window.location.pathname;

            // Loop melalui setiap elemen menu
            $('.nav-link').each(function() {
                // Ambil path dari atribut href
                var href = $(this).attr('href');

                // Cek apakah path URL saat ini cocok dengan path dari elemen menu
                if (path.includes(href)) {
                    // Jika cocok, tambahkan kelas 'active'
                    $(this).addClass('active');
                }
            });
        });
    </script>


    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>