<?php

session_start();
include("db.php"); // Koneksi ke database

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["csv_file"])) {
    $file = $_FILES["csv_file"]["tmp_name"];

    if (($handle = fopen($file, "r")) !== FALSE) {
        // Lewati baris pertama jika mengandung judul kolom
        fgetcsv($handle, 1000, ",");

        // Loop untuk setiap baris di dalam CSV
        while (($rowData = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $email = mysqli_real_escape_string($connection, $rowData[0]);
            $tanggal = mysqli_real_escape_string($connection, $rowData[1]);
            $kelas_name = mysqli_real_escape_string($connection, $rowData[2]);
            $mapel_name = mysqli_real_escape_string($connection, $rowData[3]);
            $materi = mysqli_real_escape_string($connection, $rowData[4]);
            $tidak_hadir = mysqli_real_escape_string($connection, $rowData[5]);

            // Step 1: Ambil user id berdasarkan email
            $query_user = "SELECT id FROM users WHERE email = '$email'";
            $result_user = mysqli_query($connection, $query_user);
            $row_user = mysqli_fetch_assoc($result_user);

            if (!$row_user) {
                die("User with email '$email' not found.");
            }
            $user_id = $row_user['id'];

            // Step 2: ambil kelas_id berdasarkan kelas_name
            $query_kelas = "SELECT id FROM kelas WHERE kelas_name = '$kelas_name'";
            $result_kelas = mysqli_query($connection, $query_kelas);
            $row_kelas = mysqli_fetch_assoc($result_kelas);

            if (!$row_kelas) {
                die("Class '$kelas_name' not found.");
            }
            $kelas_id = $row_kelas['id'];

            // Step 3: ambil mapel_id berdasarkan mapel_name
            $query_mapel = "SELECT id FROM mapel WHERE mapel_name = '$mapel_name'";
            $result_mapel = mysqli_query($connection, $query_mapel);
            $row_mapel = mysqli_fetch_assoc($result_mapel);

            if (!$row_mapel) {
                die("Subject '$mapel_name' not found.");
            }
            $mapel_id = $row_mapel['id'];

            // Step 4: masukkan ke tabel jurnal
            $query = "INSERT INTO jurnal (user_id, tanggal, kelas_id, mapel_id, materi, tidak_hadir)
                      VALUES ('$user_id', '$tanggal', '$kelas_id', '$mapel_id', '$materi', '$tidak_hadir')";

            $result = mysqli_query($connection, $query);

            if (!$result) {
                die("Error inserting row: " . mysqli_error($connection));
            }
        }

        fclose($handle);

        // Redirect kembali ke dashboard
        header("Location: user_dashboard.php?message=CSV berhasil diimpor.");
        exit;
    } else {
        die("Error opening file.");
    }
}
