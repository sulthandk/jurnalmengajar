<?php
session_start();
include("db.php");

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input
    $mapel_name = mysqli_real_escape_string($connection, $_POST['nama']);

    // Insert the new class into the kelas table
    $query = "INSERT INTO mapel (mapel_name) VALUES ('$mapel_name')";

    if (mysqli_query($connection, $query)) {
        // Redirect to the kelas page with a success message
        header("Location: admin_dashboard.php?view=mapel&status=success");
        exit;
    } else {
        // Redirect to the kelas page with an error message
        header("Location: admin_dashboard.php?view=mapel&status=error");
        exit;
    }
}
