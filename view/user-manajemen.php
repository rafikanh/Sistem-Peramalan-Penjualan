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
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/img/logo.png">
    <title>Sistem Peramalan Penjualan</title>
</head>

<body>
    <?php include '../view/component/sidebar.php'; ?>
    <div class="content-large">
        <div class="container-fluid">
            <h1>User Manajemen</h1>
            <div class="d-flex mb-4">
                <a href="../view/add-user.php" class="btn btn-primary me-5 flex-shrink-0">Tambah User</a>
                <form id="searchForm" class="d-flex" action="" method="post">
                    <input id="searchInput" class="form-control form-control-custom-A me-2" type="search" placeholder="Cari" aria-label="search" name="search_query" value="<?php echo isset($_POST['search_query']) ? $_POST['search_query'] : ''; ?>">
                    <button class="btn btn-outline-dark flex-shrink-0" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <?php
            // Sertakan file koneksi
            include '../koneksi.php';

            // Ambil nilai dari form pencarian
            $searchQuery = isset($_POST['search_query']) ? $_POST['search_query'] : '';

            // Query SQL untuk mengambil data user
            $sql = "SELECT id, nm_depan, nm_belakang, email, password FROM users WHERE nm_depan LIKE '%$searchQuery%' OR nm_belakang LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%'";
            $result = $conn->query($sql);

            // Tampilkan data user dalam tabel
            echo "<div class='scrollable-table-container'>";
            echo "<table class='table'>";
            echo "<thead>
                <tr>
                    <th scope='col'>Nama Depan</th>
                    <th scope='col'>Nama Belakang</th>
                    <th scope='col'>Email</th>
                    <th scope='col'>Password</th>
                    <th scope='col'>Aksi</th>
                </tr>
            </thead>
            <tbody>";

            // Fungsi untuk mendekripsi password
            function decryptPassword($encryptedPassword)
            {
                return md5($encryptedPassword); // Ini hanya contoh, sebaiknya diganti dengan metode dekripsi yang aman
            }

            while ($row = $result->fetch_assoc()) {
                $userID = $row['id'];
                $nama_depan = $row['nm_depan'];
                $nama_belakang = $row['nm_belakang'];
                $email = $row['email'];
                $encryptedPassword = $row['password'];

                // Dekripsi password (contoh, sebaiknya ganti dengan metode dekripsi yang aman)
                $password = decryptPassword($encryptedPassword);

                // Tampilkan data dalam tabel
                echo "<tr>
                    <td class='align-middle'>$nama_depan</td>
                    <td class='align-middle'>$nama_belakang</td>
                    <td class='align-middle'>$email</td>
                    <td class='align-middle' id='password-cell-$userID'>$password</td>
                    <td class='d-flex'>
                        <a href='../view/update-user.php?id=$userID' type='button' class='btn btn-warning me-2'>
                            <i class='bi bi-pencil-square'></i>
                        </a>
                        <a href='#' onclick='confirmDelete($userID)' class='btn btn-danger'>
                            <i class='bi bi-trash'></i>
                        </a>
                    </td>
                </tr>";
            }

            echo "</tbody></table></div>";

            // Tutup koneksi
            $conn->close();
            ?>
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

    <!-- SweetAlert2 script -->
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
                        window.location.href = '../process/delete-user.php?id=' + userID;
                    }
                });
            }
        </script>";
    ?>

    <!-- Mengganti teks dengan bintang-bintang pada elemen dengan id "password-cell" -->
    <script>
        <?php
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            $userID = $row['id'];
            echo "document.getElementById('password-cell-$userID').innerText = '••••••••';\n";
        }
        ?>
    </script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>