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
    <title>Sistem Peramalan Penjualan</title>
</head>

<body>
    <?php include '../view/component/sidebar.php'; ?>
    <div class="content-large">
        <div class="container-fluid">
            <h1>Data Penjualan</h1>
            <div class="d-flex mb-4">
                <a href="../view/add-data-penjualan.php" class="btn btn-primary me-3 flex-shrink-0">Tambah Data</a>
                <button type="button" class="btn btn-success import-button" data-bs-toggle="modal" data-bs-target="#importModal">
                    Import Data
                </button>
                <form id="searchForm" class="d-flex" action="" method="post">
                    <input id="searchInput" class="form-control form-control-custom-B me-2" type="search" placeholder="Cari" aria-label="search" name="search_query" value="<?php echo isset($_POST['search_query']) ? $_POST['search_query'] : ''; ?>">
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
            $sql = "SELECT dp.id_penjualan, DATE_FORMAT(STR_TO_DATE(CONCAT_WS('-', dp.tahun, dp.bulan, '01'), '%Y-%m-%d'), '%M %Y') AS bulan_tahun, db.merek, db.tipe, dp.dt_aktual, CONCAT(u.nm_depan,' ', u.nm_belakang) as admin
            FROM dt_penjualan dp
            INNER JOIN dt_barang db ON dp.id_brg = db.id_brg
            INNER JOIN users u ON dp.id_user= u.id";

            // Tambahkan filter berdasarkan kriteria pencarian jika ada
            if (!empty($search_query)) {
                $sql .= " WHERE db.merek LIKE '%$search_query%' OR db.tipe LIKE '%$search_query%' OR DATE_FORMAT(STR_TO_DATE(CONCAT_WS('-', dp.tahun, dp.bulan, '01'), '%Y-%m-%d'), '%M %Y') LIKE '%$search_query%' OR CONCAT(u.nm_depan,' ', u.nm_belakang) LIKE '%$search_query%'";
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
                            <th scope="col">Tanggal</th>
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
        <!-- Modal Import Data -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <b class="modal-title" id="importModalLabel">Import Data</b>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="importForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="fileInput" class="form-label">Pilih File</label>
                                <input class="form-control" type="file" id="fileInput" name="fileInput">
                                <small id="fileHelp" class="form-text text-muted">Ekstensi (xlsx, csv)</small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-success" onclick="submitForm()">Import</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Search -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');

            // Fungsi untuk mengembalikan semua data saat input pencarian dikosongkan
            searchInput.addEventListener('search', function() {
                if (searchInput.value === '') {
                    document.getElementById('searchForm').submit();
                }
            });
        });
    </script>

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