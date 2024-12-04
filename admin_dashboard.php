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
include("header.php");
include("db.php");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$view = isset($_GET['view']) ? $_GET['view'] : 'users';

// Filter initialization
$tanggal_filter = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';
$dibuat_oleh_filter = isset($_GET['dibuat_oleh']) ? $_GET['dibuat_oleh'] : '';
$kelas_filter = isset($_GET['kelas_id']) ? $_GET['kelas_id'] : '';
$mapel_filter = isset($_GET['mapel_id']) ? $_GET['mapel_id'] : '';

// Building query dynamically with filters
$query = "SELECT j.id, u.nama AS created_by, j.tanggal, k.kelas_name, m.mapel_name, j.materi, j.tidak_hadir 
          FROM jurnal j
          JOIN kelas k ON j.kelas_id = k.id
          JOIN mapel m ON j.mapel_id = m.id
          JOIN users u ON j.user_id = u.id
          WHERE 1";

if ($tanggal_filter) {
    $query .= " AND DATE_FORMAT(j.tanggal, '%Y-%m') = '$tanggal_filter'";
}
if ($dibuat_oleh_filter) {
    $query .= " AND u.nama LIKE '%$dibuat_oleh_filter%'";
}
if ($kelas_filter) {
    $query .= " AND k.kelas_name = '$kelas_filter'";
}
if ($mapel_filter) {
    $query .= " AND m.mapel_name = '$mapel_filter'";
}
?>

<style>
    .sidebar {
        position: fixed;
        top: 0;
        /* Fixed to the top of the screen */
        left: 0;
        width: 12%;
        background-color: #343a40;
        /* Dark grey */
        color: white;
        z-index: 1000;
        padding-top: 1rem;
        height: 100vh;
        /* Make the sidebar take the full screen height */
        overflow-y: auto;
        /* Allows scrolling if content overflows */
    }

    .sidebar .nav-link {
        color: #adb5bd;
        /* Light grey for links */
    }

    .sidebar .nav-link.active {
        background-color: #495057;
        /* Slightly lighter for active link */
        color: white;
    }

    /* Main content styling to align it beside the sidebar */
    .content {
        margin-left: 15%;
        padding: 20px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="?view=users" class="nav-link <?php echo $view === 'users' ? 'active' : ''; ?>">Users</a>
                    </li>
                    <li class="nav-item">
                        <a href="?view=jurnal" class="nav-link <?php echo $view === 'jurnal' ? 'active' : ''; ?>">Jurnal</a>
                    </li>
                    <li class="nav-item">
                        <a href="?view=kelas" class="nav-link <?php echo $view === 'kelas' ? 'active' : ''; ?>">Kelas</a>
                    </li>
                    <li class="nav-item">
                        <a href="?view=mapel" class="nav-link <?php echo $view === 'mapel' ? 'active' : ''; ?>">Mapel</a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link" style="color: #ff4d4d;">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main" class="col-md-10 ms-auto content">

            <?php
            if ($view === 'users') {
            ?>

                <div class="d-flex align-items-center mb-3">
                    <h4>Pengguna</h4>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah Pengguna</button>
                        <button class="btn btn-primary ms-1" data-bs-toggle="modal" data-bs-target="#importCsvModal">Import CSV</button>
                        <button onclick="printSpecificTable('usersTable', 'Pengguna', true)" class="btn btn-primary ms-1">Print</button>
                    </div>
                </div>

                <div id="usersTable">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Dibuat</th>
                                <th class="ubah">Ubah</th>
                                <th class="hapus">Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM users";
                            $result = mysqli_query($connection, $query);
                            if (!$result) {
                                die("Query failed: " . mysqli_error($connection));
                            } else {
                                while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['password']; ?></td>
                                        <td><?php echo $row['nama']; ?></td>
                                        <td><?php echo $row['role']; ?></td>
                                        <td><?php echo $row['reg_date']; ?></td>
                                        <td class="ubah"><a href="update_users.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Ubah</a></td>
                                        <td class="hapus"><a href="delete_users.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Hapus</a></td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Import CSV Modal -->
                <div class="modal fade" id="importCsvModal" tabindex="-1" role="dialog" aria-labelledby="importCsvModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="importCsvModalLabel">Import CSV</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="usersimport_csv.php" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="csv_file">Choose CSV File</label>
                                        <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Import</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal to Add User -->
                <form action="insert_users.php" method="post">
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Pengguna</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" class="form-control" required>
                                        <label for="password">Password</label>
                                        <input type="text" name="password" class="form-control" required>
                                        <label for="nama">Nama</label>
                                        <input type="text" name="nama" class="form-control" required>
                                        <label for="role">Role</label>
                                        <select name="role" class="form-control" required>
                                            <option value="user" selected>User</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="add_user">Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php

            } elseif ($view === 'jurnal') {
            ?>
                <div class="d-flex align-items-center mb">
                    <h4>Jurnal</h4>

                </div>


                <!-- Filter Dropdown Button -->
                <div class="dropdown mb-1">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="filterButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Filter Data
                    </button>
                    <button onclick="printSpecificTable('jurnalTable', 'Jurnal', true)" class="btn btn-primary ms-2">
                        Print
                    </button>
                    <div class="dropdown-menu p-3" aria-labelledby="filterButton" style="min-width: 300px;">
                        <form method="get">
                            <input type="hidden" name="view" value="jurnal">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Bulan</label>
                                <input type="month" name="tanggal" class="form-control" value="<?php echo $tanggal_filter; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="dibuat_oleh" class="form-label">Dibuat Oleh</label>
                                <select name="dibuat_oleh" class="form-control">
                                    <option value="">Pilih User</option>
                                    <?php
                                    $user_query = "SELECT DISTINCT nama FROM users";
                                    $user_result = mysqli_query($connection, $user_query);
                                    while ($user = mysqli_fetch_assoc($user_result)) {
                                        echo "<option value='{$user['nama']}' " . ($user['nama'] == $dibuat_oleh_filter ? "selected" : "") . ">{$user['nama']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Kelas</label>
                                <select name="kelas_id" class="form-control">
                                    <option value="">Pilih Kelas</option>
                                    <?php
                                    $kelas_query = "SELECT DISTINCT kelas_name FROM kelas";
                                    $kelas_result = mysqli_query($connection, $kelas_query);
                                    while ($kelas = mysqli_fetch_assoc($kelas_result)) {
                                        echo "<option value='{$kelas['kelas_name']}' " . ($kelas['kelas_name'] == $kelas_filter ? "selected" : "") . ">{$kelas['kelas_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="mapel" class="form-label">Mapel</label>
                                <select name="mapel_id" class="form-control">
                                    <option value="">Pilih Mapel</option>
                                    <?php
                                    $mapel_query = "SELECT DISTINCT mapel_name FROM mapel";
                                    $mapel_result = mysqli_query($connection, $mapel_query);
                                    while ($mapel = mysqli_fetch_assoc($mapel_result)) {
                                        echo "<option value='{$mapel['mapel_name']}' " . ($mapel['mapel_name'] == $mapel_filter ? "selected" : "") . ">{$mapel['mapel_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </form>
                    </div>
                </div>


                <div id="jurnalTable">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Dibuat Oleh</th>
                                <th>Tanggal</th>
                                <th>Kelas</th>
                                <th>Mapel</th>
                                <th>Materi</th>
                                <th>Tidak Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Modify the query to filter by month
                            $result = mysqli_query($connection, $query);
                            if (!$result) {
                                die("Query failed: " . mysqli_error($connection));
                            }
                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_by']); ?></td>
                                    <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($row['tanggal']))); ?></td>
                                    <td><?php echo htmlspecialchars($row['kelas_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['mapel_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['materi']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tidak_hadir']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

    </div>
<?php
            } elseif ($view === 'kelas') {
?>
    <div class="d-flex align-items-center mb-3">
        <h4>Kelas</h4>
        <div class="ms-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKelasModal">Tambah Kelas</button>
        </div>
    </div>

    <div id="kelasTable">
        <table class="table table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kelas</th>
                    <th>Hapus</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $kelas_query = "SELECT * FROM kelas";
                $kelas_result = mysqli_query($connection, $kelas_query);
                while ($kelas = mysqli_fetch_assoc($kelas_result)) { ?>
                    <tr>
                        <td><?php echo $kelas['id']; ?></td>
                        <td><?php echo htmlspecialchars($kelas['kelas_name']); ?></td>
                        <td><a href="delete_kelas.php?id=<?php echo $kelas['id']; ?>" class="btn btn-danger">Hapus</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add Kelas Modal -->
    <div class="modal fade" id="addKelasModal" tabindex="-1" role="dialog" aria-labelledby="addKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="insert_kelas.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addKelasModalLabel">Tambah Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama Kelas</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php

            } elseif ($view === 'mapel') {
?>
    <div class="d-flex align-items-center mb-3">
        <h4>Mapel</h4>
        <div class="ms-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMapelModal">Tambah Mapel</button>
        </div>
    </div>

    <div id="mapelTable">
        <table class="table table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mapel</th>
                    <th>Hapus</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $mapel_query = "SELECT * FROM mapel";
                $mapel_result = mysqli_query($connection, $mapel_query);
                while ($mapel = mysqli_fetch_assoc($mapel_result)) { ?>
                    <tr>
                        <td><?php echo $mapel['id']; ?></td>
                        <td><?php echo htmlspecialchars($mapel['mapel_name']); ?></td>
                        <td><a href="delete_mapel.php?id=<?php echo $mapel['id']; ?>" class="btn btn-danger">Hapus</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add Mapel Modal -->
    <div class="modal fade" id="addMapelModal" tabindex="-1" role="dialog" aria-labelledby="addMapelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="insert_mapel.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMapelModalLabel">Tambah Mapel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama Mapel</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
            }
?>
</main>
</div>

<script>
    function printSpecificTable(tableId, title, hideColumns) {
        let printContent = `<h4>${title}</h4>` + document.getElementById(tableId).outerHTML;

        let printWindow = window.open('', '', 'width=800, height=600');
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">');
        printWindow.document.write('<style>.ubah, .hapus { display: none !important; }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContent);

        if (hideColumns) {
            printWindow.document.querySelectorAll('.ubah, .hapus').forEach(el => el.remove());
        }

        printWindow.document.write('</body></html>');
        printWindow.document.close();

        printWindow.print();
        printWindow.onafterprint = function() {
            printWindow.close();
        };
    }
</script>

<?php include("footer.php"); ?>
