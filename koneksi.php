<?php
$host ="localhost";
$username ="root";
$pass ="";

$dbname = "peramalan";
$koneksi = mysqli_connect($host, $username, $pass, $dbname);

if (!$koneksi) {
    die("Database tidak dapat terhubung: " . mysqli_connect_error());
}
?>