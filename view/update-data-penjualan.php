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
    <div class="content-medium">
        <div class="container-fluid">
            <h1 class="mb-4">Update Data Penjualan</h1>
            <div class="d-flex">
                <div class="mb-3">
                    <label for="bulan" class="input-data-label">Bulan</label>
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Pilih bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                    </select>
                </div>

                <div class="mb-3 ms-5">
                    <label for="tahun" class="input-data-label">Tahun</label>
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Pilih tahun</option>
                        <option value="1">2021</option>
                        <option value="2">2022</option>
                        <option value="3">2023</option>
                    </select>
                </div>
            </div>

            <div class="d-flex">
                <div class="mb-3">
                    <label for="merek" class="input-data-label">Merek</label>
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Pilih merek</option>
                        <option value="1">Acer</option>
                        <option value="2">Asus</option>
                        <option value="3">Dell</option>
                    </select>
                </div>

                <div class="mb-3 ms-5">
                    <label for="tipe" class="input-data-label">Tipe</label>
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Pilih tipe</option>
                        <option value="1">ASPIRE 3 A314</option>
                        <option value="2">ASPIRE 3 A315</option>
                        <option value="3">ASPIRE 5 A513</option>
                    </select>
                </div>
            </div>

            <div class="d-flex">
                <a href="../view/data-penjualan.php" class="btn btn-success me-2">Simpan Data</a>
                <a href="../view/data-penjualan.php" class="btn btn-secondary">Batal</a>
            </div>
        </div>

        <!-- Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>