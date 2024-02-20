<!DOCTYPE html>
<html>

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
    <div class="content-large">
        <div class="container-fluid">
            <h1>User Manajemen</h1>
            <div class="d-flex mb-4">
                <a href="add-user.php" class="btn btn-primary me-2 flex-shrink-0">Tambah User</a>
                <input class="form-control me-2" type="search" placeholder="Cari" aria-label="search">
                <button class="btn btn-outline-dark flex-shrink-0" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>

            <div class="scrollable-table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Email</th>
                            <th scope="col">Password</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="align-middle" scope="row">1</th>
                            <td class="align-middle">admin@gmail.com</td>
                            <td class="align-middle" id="password-cell">admin123</td>
                            <td class="d-flex">
                                <a href="update-user.php" type="button" class="btn btn-warning me-2">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button" class="btn btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Mengganti teks dengan bintang-bintang pada elemen dengan id "password-cell"
        document.getElementById("password-cell").innerText = "********";
    </script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>