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
            <h1>Data Barang</h1>
            <div class="d-flex mb-4">
                <a href="add-data-barang.php" class="btn btn-primary me-2 flex-shrink-0">Tambah Data</a>
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

            // Ambil nilai dari form pencarian
            $searchQuery = $_POST['search_query'] ?? '';

            // Query SQL untuk mengambil data dari tabel dt_barang
            $sql = "SELECT id_brg, merek, tipe FROM dt_barang WHERE merek LIKE '%$searchQuery%' OR tipe LIKE '%$searchQuery%'";
            $result = $conn->query($sql);

            if ($result === false) {
                die("Error executing the query: " . $conn->error);
            }
            ?>

            <div class="scrollable-table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Merek</th>
                            <th scope="col" class="text-center">Tipe</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td class="align-middle text-center"><?php echo $row['merek']; ?></td>
                                <td class="align-middle text-center"><?php echo $row['tipe']; ?></td>
                                <td class="d-flex justify-content-center">
                                    <a href="../view/update-data-barang.php?id=<?php echo $row['id_brg']; ?>" type="button" class="btn btn-warning me-2">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="../process/delete-data-barang.php?id=<?php echo $row['id_brg']; ?>" type="button" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php
            // Tutup koneksi
            $conn->close();
            ?>

        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>