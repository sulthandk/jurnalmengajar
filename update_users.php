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
include("header.php"); // Menyertakan header
include("db.php"); // Menyertakan koneksi database

// Cek apakah ada ID di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Kuery untuk mengambil data pengguna
    $query = "SELECT * FROM users WHERE id = '$id'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Kueri gagal: " . mysqli_error($connection)); // Tampilkan pesan kesalahan
    } else {
        $row = mysqli_fetch_assoc($result); // Ambil data pengguna
    }
}
?>

<?php
// Proses update jika form dikirim
if (isset($_POST["update_user"])) {
    $idnew = $_GET["id"]; // Ambil ID pengguna dari URL
    $fupdate_email = $_POST["email"];
    $fupdate_password = $_POST["password"];
    $fupdate_nama = $_POST["nama"];

    // Kuery untuk memperbarui data pengguna
    $query = "UPDATE users SET email = '$fupdate_email', 
              password = '$fupdate_password', nama = '$fupdate_nama' WHERE id = $idnew";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Kueri gagal: " . mysqli_error($connection)); // Tampilkan pesan kesalahan
    } else {
        header("location:admin_dashboard.php?update_message=Berhasil mengubah data."); // Arahkan setelah berhasil
    }
}
?>

<!-- Form untuk update pengguna -->
<form action="update_users.php?id=<?php echo $id; ?>" method="post">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="text" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>">

        <label for="password">Password</label>
        <input type="text" name="password" class="form-control" value="<?php echo htmlspecialchars($row['password']); ?>">

        <label for="nama">Nama</label>
        <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($row['nama']); ?>">
    </div>
    <input type="submit" class="btn btn-success" name="update_user" value="UPDATE"> <!-- Tombol untuk submit -->
</form>

<?php include("footer.php"); // Menyertakan footer 
?>
