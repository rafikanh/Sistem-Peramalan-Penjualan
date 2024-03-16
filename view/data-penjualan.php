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
            <h1>Data Penjualan</h1>
            <div class="d-flex mb-4">
                <a href="../view/add-data-penjualan.php" class="btn btn-primary me-5 flex-shrink-0">Tambah Data</a>
                <form class="d-flex" action="" method="post">
                    <input class="form-control me-2" type="search" placeholder="Cari" aria-label="search" name="search_query">
                    <button class="btn btn-outline-dark flex-shrink-0" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <?php
            // Sertakan file koneksi
            include '../koneksi.php';

            // Inisialisasi variabel pencarian
            $search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';

            // Query SQL untuk mengambil data penjualan dengan informasi merek, tipe, bulan, dan tahun
            $sql = "SELECT dp.id_penjualan, DATE_FORMAT(STR_TO_DATE(CONCAT_WS('-', dp.tahun, dp.bulan, '01'), '%Y-%m-%d'), '%M %Y') AS bulan_tahun, db.merek, db.tipe, dp.dt_aktual, dp.admin 
            FROM dt_penjualan dp
            INNER JOIN dt_barang db ON dp.id_brg = db.id_brg";

            // Tambahkan filter berdasarkan kriteria pencarian jika ada
            if (!empty($search_query)) {
                $sql .= " WHERE db.merek LIKE '%$search_query%' OR db.tipe LIKE '%$search_query%' OR DATE_FORMAT(STR_TO_DATE(CONCAT_WS('-', dp.tahun, dp.bulan, '01'), '%Y-%m-%d'), '%M %Y') LIKE '%$search_query%' OR dp.admin LIKE '%$search_query%'";
            }

            // Tambahkan klausa ORDER BY untuk mengurutkan berdasarkan bulan dan tahun
            $sql .= " ORDER BY dp.tahun ASC, dp.bulan ASC";

            $result = $conn->query($sql);

            // Periksa keberhasilan eksekusi query
            if ($result === false) {
                die("Error executing the query: " . $conn->error);
            }
            ?>


            <div class="scrollable-table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Bulan - Tahun</th>
                            <th scope="col">Merek</th>
                            <th scope="col">Tipe</th>
                            <th scope="col">Nilai Aktual</th>
                            <th scope="col">Admin</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td class="align-middle"><?php echo $row['bulan_tahun']; ?></td>
                                <td class="align-middle"><?php echo $row['merek']; ?></td>
                                <td class="align-middle"><?php echo $row['tipe']; ?></td>
                                <td class="align-middle"><?php echo $row['dt_aktual']; ?></td>
                                <td class="align-middle"><?php echo $row['admin']; ?></td>
                                <td class="d-flex">
                                    <a href="../view/update-data-penjualan.php?id_penjualan=<?php echo $row['id_penjualan']; ?>" type="button" class="btn btn-warning me-2">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger delete-btn" onclick="confirmDelete(<?php echo $row['id_penjualan']; ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>


                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 script hapus data -->
    <?php
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            function confirmDelete(userID) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Anda tidak akan dapat mengembalikan ini!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../process/delete-data-penjualan.php?id_penjualan=' + userID;
                    }
                });
            }
        </script>";
    ?>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>