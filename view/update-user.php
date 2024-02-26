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
    <div class="content-small">
        <div class="container-fluid">
            <h1 class="mb-4">Update User</h1>

            <?php
            // Sertakan file koneksi
            include '../koneksi.php';

            // Periksa apakah ID pengguna disertakan di URL
            if (isset($_GET['id'])) {
                $userID = $_GET['id'];

                // Query SQL untuk mengambil data pengguna berdasarkan ID
                $query = "SELECT * FROM users WHERE id = $userID";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    // Ambil data pengguna
                    $row = $result->fetch_assoc();
                    $email = $row['email'];
                    $password = $row['password']; // Ini dianggap masih dalam format MD5
                } else {
                    echo "Data pengguna tidak ditemukan.";
                    exit();
                }
            } else {
                echo "ID pengguna tidak disertakan dalam URL.";
                exit();
            }
            ?>

            <!-- Formulir update -->
            <form action="../process/process-update-user.php" method="post">
                <div class="d-flex">
                    <div class="mb-3">
                        <label for="email" class="input-data-label">Email</label>
                        <input type="text" class="input-data" id="email" name="email" value="<?php echo $email; ?>" placeholder="Masukkan email">
                    </div>
                    <div class="mb-3 ms-5">
                        <label for="password" class="input-data-label">Password</label>
                        <input type="password" class="input-data" id="password" name="password" placeholder="Masukkan password">
                    </div>
                </div>

                <div class="d-flex">
                    <input type="hidden" name="userID" value="<?php echo $userID; ?>"> <!-- Tambahkan input tersembunyi untuk userID -->
                    <button type="submit" class="btn btn-warning me-2">Update Data</button>
                    <a href="../view/user-manajemen.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>

        </div>

        <!-- Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>