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
            <h1 class="mb-4">Tambah User</h1>
            <form action="../process/process-add-user.php" method="post">
                <div class="d-flex">
                    <div class="mb-3">
                        <label for="email" class="input-data-label">Email</label>
                        <input type="text" class="input-data" name="email" id="email" placeholder="Masukkan email">
                    </div>
                    <div class="mb-3 ms-5">
                        <label for="password" class="input-data-label">Password</label>
                        <input type="password" class="input-data" name="password" id="password" placeholder="Masukkan password">
                    </div>
                </div>

                <div class="d-flex">
                    <button type="button" class="btn btn-success me-2" id="saveBtn">Simpan Data</button>
                    <a href="../view/user-manajemen.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>

        <!-- SweetAlert2 script -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.getElementById('saveBtn').addEventListener('click', function() {
                Swal.fire({
                    title: "Apakah Anda ingin menyimpan perubahan?",
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: "Simpan",
                    denyButtonText: `Jangan Simpan`,
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If user clicks "Save", submit the form
                        Swal.fire({
                            title: "Tersimpan!",
                            text: "Perubahan yang Anda buat sudah disimpan.",
                            icon: "success",
                            timer: 10000,
                            showConfirmButton: false
                        });
                        document.querySelector('form').submit();
                    } else if (result.isDenied) {
                        Swal.fire("Perubahan tidak disimpan", "", "info");
                        window.history.back();
                    }
                });
            });
        </script>

        <!-- Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>