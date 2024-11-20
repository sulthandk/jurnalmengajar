<?php
include("header.php");
include("db.php");

// Periksa apakah 'id' ada di URL
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Hapus semua jurnal terkait dengan pengguna
    $deleteJurnalQuery = "DELETE FROM jurnal WHERE user_id = '$id'";
    $deleteJurnalResult = mysqli_query($connection, $deleteJurnalQuery);

    // Periksa apakah penghapusan jurnal berhasil
    if (!$deleteJurnalResult) {
        die("Gagal menghapus jurnal: " . mysqli_error($connection));
    }

    // Hapus pengguna berdasarkan id
    $query = "DELETE FROM users WHERE id = $id";
    $result = mysqli_query($connection, $query);

    // Periksa apakah query berhasil dijalankan
    if (!$result) {
        die("Query gagal: " . mysqli_error($connection));
    } else {
        // Jika berhasil, arahkan kembali ke dashboard admin dengan pesan sukses
        header('taLocation: admin_dashboard.php?delete_message=Berhasil menghapus da.');
        exit;
    }
} else {
    die("ID pengguna tidak ditemukan.");
}
