<?php
/*
 * Hak Cipta © 2024 Muhammad Sulthan Dary Kurahman
 *
 * Tidak diperkenankan menyalin, mendistribusikan ulang, atau memodifikasi kode ini tanpa izin tertulis
 * dari pemilik hak cipta.
 *
 * Ketentuan Penggunaan:
 * 1. Hanya untuk keperluan pendidikan atau non-komersial, dengan mencantumkan kredit kepada pemilik.
 * 2. Dilarang mengklaim sebagian atau seluruh kode ini sebagai milik Anda.
 *
 * Hubungi: msulthandaryk@gmail.com jika Anda memerlukan izin atau menemukan pelanggaran.
 *
 * Terima kasih telah menghormati karya saya!
 */
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
