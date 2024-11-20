<?php
// Definisikan detail koneksi database hanya jika belum didefinisikan
if (!defined("HOSTNAME")) {
    define("HOSTNAME", "localhost");
}
if (!defined("USERNAME")) {
    define("USERNAME", "root");
}
if (!defined("PASSWORD")) {
    define("PASSWORD", "");
}
if (!defined("DATABASE")) {
    define("DATABASE", "website1db");
}

// Membuat koneksi ke database
$connection = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);

// Periksa apakah koneksi berhasil
if (!$connection) {
    die('Tidak tersambung ke database: ' . mysqli_connect_error());
}
