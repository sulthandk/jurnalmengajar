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
include("db.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (isset($_POST["add_user"])) {
    $finsert_email = $_POST["email"];
    $finsert_password = $_POST["password"];
    $finsert_nama = $_POST["nama"];
    $finsert_role = $_POST["role"];

    // Validasi email dan role tidak boleh kosong
    if (empty($finsert_email) || empty($finsert_role)) {
        header("location:admin_dashboard.php?message=Email dan Role tidak boleh kosong!");
        exit;
    } else {
        // Memeriksa jika email sudah terdaftar
        $check_query = "SELECT * FROM users WHERE email = '$finsert_email'";
        $check_result = mysqli_query($connection, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // Jika email sudah terdaftar, print pesan berikut
            header("location:admin_dashboard.php?message=Email sudah terdaftar");
            exit;
        } else {
            // Query untuk menambah user baru
            $query = "INSERT INTO users (email, password, nama, role) 
                      VALUES ('$finsert_email', '$finsert_password', '$finsert_nama', '$finsert_role')";
            $result = mysqli_query($connection, $query);

            // Periksa apakah query berhasil dijalankan
            if (!$result) {
                die("Query gagal: " . mysqli_error($connection));
            } else {
                header("location:admin_dashboard.php?insert_message=Berhasil menambah user!");
                exit;
            }
        }
    }
}
