<?php
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
