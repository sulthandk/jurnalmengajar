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
session_start();
include("db.php");

if (isset($_POST["add_jurnal"])) {
    // Periksa apakah user_id ada di session
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php"); // Redirect ke halaman login jika belum login
        exit;
    }

    $finsert_tanggal = $_POST["tanggal"];
    $finsert_kelas = $_POST["kelas_name"];
    $finsert_mapel = $_POST["mapel_name"];
    $finsert_materi = $_POST["materi"];
    $finsert_tidakhadir = $_POST["tidak_hadir"];
    $user_id = $_SESSION['user_id'];

    // Validasi input
    if (empty($finsert_kelas)) {
        header("location:user_dashboard.php?message=Kelas tidak boleh kosong!");
        exit;
    } elseif (empty($finsert_mapel)) {
        header("location:user_dashboard.php?message=Mapel tidak boleh kosong!");
        exit;
    }

    // Step 1: Ambil ID untuk kelas
    $query_kelas = "SELECT id FROM kelas WHERE kelas_name = '$finsert_kelas'";
    $result_kelas = mysqli_query($connection, $query_kelas);

    if (!$result_kelas) {
        die("Query gagal: " . mysqli_error($connection));
    }

    $row_kelas = mysqli_fetch_assoc($result_kelas);
    if (!$row_kelas) {
        header("location:user_dashboard.php?message=Kelas tidak ditemukan!");
        exit;
    }
    $kelas_id = $row_kelas['id'];

    // Step 2: Ambil ID untuk mapel
    $query_mapel = "SELECT id FROM mapel WHERE mapel_name = '$finsert_mapel'";
    $result_mapel = mysqli_query($connection, $query_mapel);

    if (!$result_mapel) {
        die("Query gagal: " . mysqli_error($connection));
    }

    $row_mapel = mysqli_fetch_assoc($result_mapel);
    if (!$row_mapel) {
        header("location:user_dashboard.php?message=Mapel tidak ditemukan!");
        exit;
    }
    $mapel_id = $row_mapel['id'];

    // Step 3: Masukkan record ke tabel jurnal
    $query_insert = "INSERT INTO jurnal (user_id, tanggal, kelas_id, mapel_id, materi, tidak_hadir) 
                     VALUES ('$user_id', '$finsert_tanggal', '$kelas_id', '$mapel_id', '$finsert_materi', '$finsert_tidakhadir')";

    $insert_result = mysqli_query($connection, $query_insert);

    if (!$insert_result) {
        die("Query gagal: " . mysqli_error($connection));
    } else {
        header("location:user_dashboard.php?insert_message=Berhasil menambah jurnal!");
        exit; // Praktik yang baik untuk exit setelah redirect
    }
}
