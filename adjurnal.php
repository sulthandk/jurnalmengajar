<?php
session_start(); // Memulai sesi
include("header.php"); // Menyertakan header
include("db.php"); // Menyertakan koneksi database

// Memeriksa apakah pengguna sudah login dan memiliki peran 'user'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); // Arahkan ke halaman login jika tidak berhak
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <h5 class="sidebar-heading">Menu</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php') ? 'active' : ''; ?>" href="admin_dashboard.php">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'adjurnal.php') ? 'active' : ''; ?>" href="adjurnal.php">
                            <i class="fas fa-book"></i> Jurnal
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <?php
        // Mengambil jurnal untuk semua pengguna
        $query = "SELECT j.id, j.tanggal, k.kelas, m.mapel, j.materi, j.tidak_hadir 
          FROM jurnal j
          JOIN kelas k ON j.kelas = k.id
          JOIN mapel m ON j.mapel = m.id"; // Mengambil semua jurnal tanpa filter

        $result = mysqli_query($connection, $query); // Eksekusi kueri
        if (!$result) {
            die("Kueri gagal: " . mysqli_error($connection)); // Menampilkan pesan kesalahan jika kueri gagal
        }
        ?>

        <h5>Dashboard user: <span style="color: blue;"><?php echo $_SESSION['email']; ?></span></h5>
        <a href="logout.php">Logout</a>

        <div class="box1">
            <h3>Jurnal</h3>
        </div>

        <table class="table table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>Materi</th>
                    <th>Tidak Hadir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Menampilkan data jurnal yang diambil
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                        <td><?php echo htmlspecialchars($row['kelas']); ?></td>
                        <td><?php echo htmlspecialchars($row['mapel']); ?></td>
                        <td><?php echo htmlspecialchars($row['materi']); ?></td>
                        <td><?php echo htmlspecialchars($row['tidak_hadir']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Menampilkan pesan jika ada -->
        <?php
        $messages = ['message', 'insert_message', 'update_message', 'delete_message'];
        foreach ($messages as $msg) {
            if (isset($_GET[$msg])) {
                echo '<h6 class="' . $msg . '">' . htmlspecialchars($_GET[$msg]) . '</h6>';
            }
        }
        ?>

        <?php include("footer.php"); // Menyertakan footer 
        ?>