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
            <h1>Peramalan</h1>
            <div class="d-flex">
                <div class="mb-1">
                    <p class="text-forecasting">Nilai alpha saat ini adalah ...</p>
                </div>
                <div class="mb-1 ms-5">
                    <input type="text" class="input-data-forecasting" id="alpha" placeholder="Masukkan nilai alpha">
                </div>
                <div class="mb-1 ms-2">
                    <button class="btn btn-info custom-button">
                        <i class="bi bi-pen"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex">
                <div class="mb-3 beta">
                    <p class="text-forecasting">Nilai beta saat ini adalah ...</p>
                </div>
                <div class="mb-3 ms-5">
                    <input type="text" class="input-data-forecasting" id="beta" placeholder="Masukkan nilai beta">
                </div>
                <div class="mb-3 ms-2">
                    <button class="btn btn-info custom-button">
                        <i class="bi bi-pen"></i>
                    </button>
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

            <div class="scrollable-table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Bulan Tahun</th>
                            <th scope="col">Nilai Aktual</th>
                            <th scope="col">Level</th>
                            <th scope="col">Trend</th>
                            <th scope="col">Nilai Peramalan</th>
                            <th scope="col">Error</th>
                            <th scope="col">Abs Error</th>
                            <th scope="col">% Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="align-middle">Januari 2021</td>
                            <td class="align-middle">16</td>
                            <td class="align-middle"> </td>
                            <td class="align-middle"> </td>
                            <td class="align-middle"> </td>
                            <td class="align-middle"> </td>
                            <td class="align-middle"> </td>
                            <td class="align-middle"> </td>
                        </tr>
                        <tr>
                            <td class="align-middle">Februari 2021</td>
                            <td class="align-middle">17</td>
                            <td class="align-middle">17.00</td>
                            <td class="align-middle">1.00</td>
                            <td class="align-middle"> </td>
                            <td class="align-middle"> </td>
                            <td class="align-middle"> </td>
                            <td class="align-middle"> </td>
                        </tr>
                        <tr>
                            <td class="align-middle">Maret 2021</td>
                            <td class="align-middle">19</td>
                            <td class="align-middle">18.80</td>
                            <td class="align-middle">1.16</td>
                            <td class="align-middle">18.00</td>
                            <td class="align-middle">1.00</td>
                            <td class="align-middle">1.00</td>
                            <td class="align-middle">5.26%</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h4>Peramalan bulan berikutnya ....</h4>
            <h4>MAPE ....%</h4>
        </div>
    </div>

    <!-- Bootstrap -->
    <<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>