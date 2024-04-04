<?php require_once '../process/check_login.php'; ?>

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
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/img/logo.png">
    <title>Sistem Peramalan Penjualan</title>
</head>

<body>
    <?php include '../view/component/sidebar.php'; ?>
    <div class="content-medium">
        <div class="container-fluid">
            <h1>Hai, Admin!</h1>
            <p>Selamat datang di website Sistem Peramalan Penjualan</p>

            <?php
            include '../koneksi.php';

            $sql_merek = "SELECT DISTINCT merek FROM dt_barang";
            $result = $conn->query($sql_merek);

            $jumlah_merek = 0;

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $jumlah_merek++;
                }
            }

            $sql_tipe = "SELECT DISTINCT tipe FROM dt_barang";
            $result = $conn->query($sql_tipe);

            $jumlah_tipe = 0;

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $jumlah_tipe++;
                }
            }

            $sql_penjualanTahun = "SELECT tahun, SUM(dt_aktual) AS total_dt_aktual FROM dt_penjualan GROUP BY tahun ORDER BY total_dt_aktual DESC LIMIT 1";
            $result = $conn->query($sql_penjualanTahun);

            $penjualan_tertinggi = 0;
            $tahun_penjualan_tertinggi = '';

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $penjualan_tertinggi = $row["total_dt_aktual"];
                $tahun_penjualan_tertinggi = $row["tahun"];
            }

            $sql_penjualan_merek_tipe = "SELECT db.merek, db.tipe, SUM(dp.dt_aktual) AS total_penjualan FROM dt_barang db JOIN dt_penjualan dp ON db.id_brg = dp.id_brg GROUP BY db.merek, db.tipe ORDER BY total_penjualan DESC LIMIT 1";

            $result = $conn->query($sql_penjualan_merek_tipe);

            $penjualan_tertinggi_merek_tipe = 0;
            $merek_penjualan_tertinggi = '';
            $tipe_penjualan_tertinggi = '';

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $penjualan_tertinggi_merek_tipe = $row["total_penjualan"];
                $merek_penjualan_tertinggi = $row["merek"];
                $tipe_penjualan_tertinggi = $row["tipe"];
            }

            $conn->close();
            ?>

            <div class="gutter-container">
                <div class="gutter row g-2">
                    <div class="gutter col-3">
                        <div class="gutter">
                            <div class="gutter-item">
                                <a href="../view/data-barang.php">
                                    <i class="bi bi-arrow-up-right-square icon-gutter-1"></i>
                                </a>
                                <b><?php echo $jumlah_merek; ?></b>
                                <p>Jumlah Merek</p>
                            </div>
                        </div>
                    </div>
                    <div class="gutter col-3">
                        <div class="gutter">
                            <div class="gutter-item">
                                <a href="../view/data-barang.php">
                                    <i class="bi bi-arrow-up-right-square icon-gutter-2"></i>
                                </a>
                                <b><?php echo $jumlah_tipe; ?></b>
                                <p>Jumlah Tipe</p>
                            </div>
                        </div>
                    </div>
                    <div class="gutter col-3">
                        <div class="gutter">
                            <div class="gutter-item">
                                <a href="../view/data-penjualan.php">
                                    <i class="bi bi-arrow-up-right-square icon-gutter-3"></i>
                                </a>
                                <b><?php echo $penjualan_tertinggi; ?></b>
                                <p>Penjualan Tertinggi</p>
                                <p class="sub">di Tahun <?php echo $tahun_penjualan_tertinggi; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="gutter col-3">
                        <div class="gutter">
                            <div class="gutter-item">
                                <a href="../view/data-penjualan.php">
                                    <i class="bi bi-arrow-up-right-square icon-gutter-3"></i>
                                </a>
                                <b><?php echo $penjualan_tertinggi_merek_tipe; ?></b>
                                <p>Penjualan Tertinggi</p>
                                <p class="sub"><?php echo $merek_penjualan_tertinggi . ' ' . $tipe_penjualan_tertinggi; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>