<?php
include("header.php"); // Menyertakan header
include("db.php"); // Menyertakan koneksi database

// Cek apakah ID jurnal ada di URL
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    // Kuery untuk mengambil data jurnal
    $query = "SELECT * FROM jurnal WHERE id = '$id'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Kueri gagal: " . mysqli_error($connection));
    } else {
        $row = mysqli_fetch_assoc($result); // Mengambil data jurnal
    }
} else {
    die("ID jurnal tidak ditemukan."); // Tangani jika ID tidak ada
}

// Proses update jika form dikirim
if (isset($_POST["update_jurnal"])) {
    $idnew = $_GET["id"]; // Ambil ID jurnal dari URL
    $fupdate_materi = $_POST["materi"]; // Ambil input materi
    $fupdate_tidakhadir = $_POST["tidak_hadir"]; // Ambil input tidak hadir

    // Kuery update data jurnal
    $query = "UPDATE jurnal SET materi = '$fupdate_materi', 
              tidak_hadir = '$fupdate_tidakhadir' WHERE id = $idnew";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Kueri gagal: " . mysqli_error($connection));
    } else {
        header("location:user_dashboard.php?update_message=Berhasil mengubah data.");
    }
}
?>

<!-- Form untuk update jurnal -->
<form action="update_jurnal.php?id=<?php echo $id; ?>" method="post">
    <div class="form-group">
        <label for="materi">Materi</label>
        <input type="text" name="materi" class="form-control" value="<?php echo $row['materi'] ?? ''; ?>"> <!-- Input materi -->

        <label for="tidak_hadir">Tidak Hadir</label>
        <input type="text" name="tidak_hadir" class="form-control" value="<?php echo $row['tidak_hadir'] ?? ''; ?>"> <!-- Input tidak hadir -->
    </div>
    <input type="submit" class="btn btn-success" name="update_jurnal" value="UPDATE"> <!-- Tombol submit -->
</form>

<?php include("footer.php"); // Menyertakan footer 
?>