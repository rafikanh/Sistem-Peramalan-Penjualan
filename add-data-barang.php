<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Style -->
    <link rel="stylesheet" href="css/style.css">
    <title>Sistem Peramalan Penjualan</title>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <div class="container-fluid">
            <h1 class="mb-4">Tambah Data Barang</h1>
            <div class="d-flex">
                <div class="mb-3">
                    <label for="merek" class="input-data-label">Merek</label>
                    <input type="text" class="input-data" id="merek" placeholder="Masukkan merek laptop">
                </div>
                <div class="mb-3 ms-5">
                    <label for="tipe" class="input-data-label">Tipe</label>
                    <input type="text" class="input-data" id="tipe" placeholder="Masukkan tipe laptop">
                </div>
            </div>

            <div class="d-flex">
                <a href="data-barang.php" class="btn btn-success me-2">Simpan Data</a>
                <a href="data-barang.php" class="btn btn-secondary">Batal</a>
            </div>
        </div>

        <!-- Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>