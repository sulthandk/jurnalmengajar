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
include("header.php");
include("db.php");

// Periksa apakah 'id' ada di URL
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}

// Query untuk menghapus data jurnal berdasarkan id
$query = "DELETE FROM mapel WHERE id = $id";
$result = mysqli_query($connection, $query);

// Periksa apakah query berhasil dijalankan
if (!$result) {
    die("Query gagal: " . mysqli_error());
} else {
    // Jika berhasil, arahkan kembali ke dashboard dengan pesan sukses
    header('location:admin_dashboard.php?view=mapel&status=deleted');
}
