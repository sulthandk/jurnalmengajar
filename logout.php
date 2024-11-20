<?php
session_start(); // Memulai session untuk mengakses variabel session

// Hapus semua data session untuk logout
session_unset(); // Menghapus semua variabel session
session_destroy(); // Menghancurkan data session

// Redirect ke halaman login setelah logout
header("Location: index.php");
exit;
