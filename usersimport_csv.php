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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
    $file = $_FILES['csv_file']['tmp_name'];

    if (($handle = fopen($file, "r")) !== FALSE) {
        $header = fgetcsv($handle); // Skip the first row (header)

        while (($data = fgetcsv($handle)) !== FALSE) {
            // Prepare and sanitize the CSV data
            $email = mysqli_real_escape_string($connection, $data[0]);
            $password = mysqli_real_escape_string($connection, $data[1]);
            $nama = mysqli_real_escape_string($connection, $data[2]);
            $role = mysqli_real_escape_string($connection, $data[3]);

            // Insert the data into the 'users' table
            $query = "INSERT INTO users (email, password, nama, role) VALUES ('$email', '$password', '$nama', '$role')";
            if (!mysqli_query($connection, $query)) {
                die("Error inserting data: " . mysqli_error($connection));
            }
        }
        fclose($handle);

        // Redirect back to users view after the import is successful
        header("Location: admin_dashboard.php?view=users");
        exit;
    } else {
        echo "Error opening the CSV file.";
    }
} else {
    echo "No file uploaded or file error.";
}
