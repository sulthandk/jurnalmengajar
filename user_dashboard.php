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
session_start(); // Memulai sesi
include("header.php"); // Menyertakan header
include("db.php"); // Menyertakan koneksi database

// Memeriksa apakah pengguna sudah login dan memiliki peran 'user'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php"); // Arahkan ke halaman login jika tidak berhak
    exit;
}

// Mengambil user_id dari sesi
$user_id = $_SESSION['user_id'] ?? die("User ID tidak tersedia.");

$kelas_filter = $_GET['kelas'] ?? '';
$mapel_filter = $_GET['mapel'] ?? '';
$month_filter = $_GET['month'] ?? '';

// Base query
$query = "SELECT j.id, j.tanggal, k.kelas_name, m.mapel_name, j.materi, j.tidak_hadir 
          FROM jurnal j
          JOIN kelas k ON j.kelas_id = k.id
          JOIN mapel m ON j.mapel_id = m.id
          WHERE j.user_id = '$user_id'";

// Add filters to the query
if ($kelas_filter) {
    $query .= " AND k.kelas_name = '" . mysqli_real_escape_string($connection, $kelas_filter) . "'";
}
if ($mapel_filter) {
    $query .= " AND m.mapel_name = '" . mysqli_real_escape_string($connection, $mapel_filter) . "'";
}
if ($month_filter) {
    $query .= " AND MONTH(j.tanggal) = '" . mysqli_real_escape_string($connection, $month_filter) . "'";
}

$result = mysqli_query($connection, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}


?>

<h5 style="display: inline;">Dashboard user: <span style="color: blue;"><?php echo $_SESSION['email']; ?></span></h5>
<a href="logout.php" style="color: #ff4d4d;">Logout</a>


<div class="box1">
    <h3>Jurnal Mengajar</h3>
    <button type="button" class="btn btn-primary" onclick="printTable()" name="printbutton">Print</button>
    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#importCsvModal">Import CSV</button>
    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">Buat</button>

</div>

<!-- Single Filter Button -->
<div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        Filter
    </button>
    <style>
        #filterDropdown {
            margin-left: 585px;
            /* Adds space between the filter button and the other buttons */
            display: inline-block;
            /* Ensures it aligns with other buttons */
            vertical-align: middle;
            /* Ensures vertical alignment matches other buttons */
        }

        .dropdown {
            display: inline-block;
            /* Keeps the dropdown inline with other buttons */
        }
    </style>

    <ul class="dropdown-menu p-3" aria-labelledby="filterDropdown">
        <li>
            <label for="filterKelas">Kelas:</label>
            <select id="filterKelas" class="form-select mb-2">
                <option value="">All</option>
                <!-- Populate kelas options dynamically -->
                <?php
                $kelasQuery = "SELECT DISTINCT kelas_name FROM kelas";
                $kelasResult = mysqli_query($connection, $kelasQuery);
                while ($kelasRow = mysqli_fetch_assoc($kelasResult)) {
                    echo "<option value=\"{$kelasRow['kelas_name']}\">{$kelasRow['kelas_name']}</option>";
                }
                ?>
            </select>
        </li>
        <li>
            <label for="filterMapel">Mapel:</label>
            <select id="filterMapel" class="form-select mb-2">
                <option value="">All</option>
                <!-- Populate mapel options dynamically -->
                <?php
                $mapelQuery = "SELECT DISTINCT mapel_name FROM mapel";
                $mapelResult = mysqli_query($connection, $mapelQuery);
                while ($mapelRow = mysqli_fetch_assoc($mapelResult)) {
                    echo "<option value=\"{$mapelRow['mapel_name']}\">{$mapelRow['mapel_name']}</option>";
                }
                ?>
            </select>
        </li>
        <li>
            <label for="filterMonth">Bulan:</label>
            <select id="filterMonth" class="form-select mb-2">
                <option value="">All</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </li>
        <li class="text-center mt-2">
            <button id="applyFilter" class="btn btn-success">Terapkan Filter</button>
        </li>
    </ul>
</div>

<script>
    document.getElementById("applyFilter").addEventListener("click", function() {
        // Get filter values
        const kelas = document.getElementById("filterKelas").value;
        const mapel = document.getElementById("filterMapel").value;
        const month = document.getElementById("filterMonth").value;

        // Reload page with filter parameters
        const params = new URLSearchParams(window.location.search);
        params.set("kelas", kelas);
        params.set("mapel", mapel);
        params.set("month", month);
        window.location.search = params.toString();
    });
</script>




<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Kelas</th>
            <th>Mapel</th>
            <th>Materi</th>
            <th>Tidak Hadir</th>
            <th>Ubah</th>
            <th>Hapus</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Menampilkan data jurnal yang diambil
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td>
                    <?php
                    $formatted_date = date("d/m/Y", strtotime($row['tanggal']));
                    echo htmlspecialchars($formatted_date);
                    ?>
                </td>
                <td><?php echo htmlspecialchars($row['kelas_name']); ?></td>
                <td><?php echo htmlspecialchars($row['mapel_name']); ?></td>
                <td><?php echo htmlspecialchars($row['materi']); ?></td>
                <td><?php echo htmlspecialchars($row['tidak_hadir']); ?></td>
                <td><a href="update_jurnal.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Ubah</a></td>
                <td><a href="delete_jurnal.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Hapus</a></td>
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

<!-- Modal untuk menambah jurnal -->
<form action="insert_jurnal.php" method="post">
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jurnal</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" id="tanggal">
                        <label for="kelas">Kelas</label>
                        <select name="kelas_name" class="form-control" required>
                            <option value="" disabled selected>Pilih Kelas</option>
                            <!-- Pilihan kelas -->
                            <?php for ($i = 1; $i <= 12; $i++) {
                                foreach (['X', 'XI', 'XII'] as $prefix) {
                                    echo "<option value=\"$prefix-$i\">$prefix-$i</option>";
                                }
                            } ?>
                        </select>
                        <label for="mapel">Mapel</label>
                        <select name="mapel_name" class="form-control" required>
                            <option value="" disabled selected>Pilih Mapel</option>
                            <!-- Pilihan mata pelajaran -->
                            <?php
                            $mapels = ['AGAMA', 'ANT', 'BINA', 'BING', 'INFO', 'KIM/PKWU', 'ML', 'MW', 'PJOK', 'PKN', 'SBK', 'SEJ', 'EKO-L', 'FIS-L', 'GEO-L', 'BING-L', 'KIM-L', 'SOS-L'];
                            foreach ($mapels as $mapel) {
                                echo "<option value=\"$mapel\">$mapel</option>";
                            }
                            ?>
                        </select>
                        <label for="materi">Materi</label>
                        <textarea name="materi" id="materi" class="form-control" rows="5" placeholder="Pokok pembelajaran..." required></textarea>
                        <label for="tidak_hadir">Tidak Hadir</label>
                        <textarea name="tidak_hadir" id="tidak_hadir" class="form-control" rows="3" placeholder="Murid yang tidak hadir" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <input type="submit" name="add_jurnal" class="btn btn-success" value="Simpan">
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Print Button -->

<script>
    function printTable() {
        // Clone the original table
        var originalTable = document.querySelector("table");
        var tableClone = originalTable.cloneNode(true);

        // Remove the last two columns from each row in the cloned table
        tableClone.querySelectorAll("tr").forEach(row => {
            row.removeChild(row.lastElementChild); // Delete column
            row.removeChild(row.lastElementChild); // Edit column
        });

        // Open a new window for printing
        var printWindow = window.open('', '', 'height=600,width=800');

        // Write the cloned table content to the new window with custom styles
        printWindow.document.write('<html><head><title>Print Jurnal Mengajar</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; table-layout: fixed; }');
        printWindow.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }');
        printWindow.document.write('th { background-color: #f2f2f2; }');
        printWindow.document.write('th.id-col, td.id-col { max-width: 50px; width: 50px; text-align: center; }'); // Set ID column width
        printWindow.document.write('th.date-col, td.date-col { max-width: 100px; width: 100px; }'); // Set Tanggal column width
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h3>Jurnal Mengajar</h3>');

        // Add specific class names to the cloned table headers for ID and Tanggal
        tableClone.querySelectorAll('th')[0].classList.add('id-col'); // Assuming ID is the first column
        tableClone.querySelectorAll('th')[1].classList.add('date-col'); // Assuming Tanggal is the second column
        tableClone.querySelectorAll('tr').forEach(row => {
            row.cells[0].classList.add('id-col'); // ID cells
            row.cells[1].classList.add('date-col'); // Tanggal cells
        });

        // Write the table to the new window
        printWindow.document.write(tableClone.outerHTML);
        printWindow.document.write('</body></html>');

        // Close the document and open the print dialog
        printWindow.document.close();
        printWindow.print();
    }
</script>


<!-- Modal for importing CSV -->
<div class="modal fade" id="importCsvModal" tabindex="-1" role="dialog" aria-labelledby="importCsvModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="jurnalimport_csv.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Import CSV File</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload and Import</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- JavaScript to set today’s date as the default in the date input -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var dateInput = document.getElementById('tanggal');
        if (dateInput && !dateInput.value) {
            var today = new Date().toISOString().split('T')[0];
            dateInput.value = today;
        }
    });
</script>

<?php include("footer.php"); ?>
