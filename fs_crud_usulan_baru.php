

<?php
// $server = "localhost";
// $user = "root";
// $password = "";
// $database = "slrt";

// // Membuat koneksi
// $koneksi = mysqli_connect($server, $user, $password, $database);

// // Periksa koneksi
// if (!$koneksi) {
// die("Koneksi database gagal: " . mysqli_connect_error());
// }

include "koneksi.php";

session_start(); // Ensure session is started

// Cek apakah user_id ada dalam sesi
if (!isset($_SESSION['id'])) {
header("Location: fasilitator.php?page=usulan_baru");
exit();
}

// Jika ada, ambil user_id
$user_id = $_SESSION['id'];

// Ketika tombol simpan diklik
if (isset($_POST['simpanusulanbaru'])) {
// Simpan data
$tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);

// Insert into klien_usulan_baru first
$nik_klien = mysqli_real_escape_string($koneksi, $_POST['nik_klien']);
$nama_klien = mysqli_real_escape_string($koneksi, $_POST['nama_klien']);
$alamat_klien = mysqli_real_escape_string($koneksi, $_POST['alamat_klien']);

// Insert into klien_usulan_baru
$insert_klien = mysqli_query($koneksi, "INSERT INTO klien_usulan_baru (nik_klien, nama_klien, alamat_klien) VALUES ('$nik_klien', '$nama_klien', '$alamat_klien')");

if ($insert_klien) {
$klien_id = mysqli_insert_id($koneksi); // Get the last inserted klien_id

// Retrieve jenis layanan from the POST data
$jenis_layanan = mysqli_real_escape_string($koneksi, $_POST['jenis_layanan']);
$keterangan_ub = mysqli_real_escape_string($koneksi, $_POST['keterangan_ub']);

// Now insert into usulan_baru
$simpan = mysqli_query($koneksi, "INSERT INTO usulan_baru (tanggal, klien_id, jenis_layanan, keterangan_ub, user_id)
VALUES ('$tanggal', '$klien_id', '$jenis_layanan', '$keterangan_ub', '$user_id')");

if ($simpan) {
echo "<script> alert('Simpan Data Berhasil');
            document.location='fs_dashboard.php?page=fs_usulan_baru';
            </script>";
} else {
echo "<script> alert('Simpan Data Gagal');
document.location='fs_usulan_baru.php';
</script>";
}
} else {
echo "<script> alert('Gagal menyimpan klien');
document.location='fs_usulan_baru.php';
</script>";
}
}




// Ketika tombol edit diklik
if (isset($_POST['ubahusulanbaru'])) {
if (isset($_POST['id'])) {
// Mengambil data dari form
$id = mysqli_real_escape_string($koneksi, $_POST['id']);
$nik_klien = mysqli_real_escape_string($koneksi, $_POST['nik_klien']);
$tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
$nama_klien = mysqli_real_escape_string($koneksi, $_POST['nama_klien']);
$alamat_klien = mysqli_real_escape_string($koneksi, $_POST['alamat_klien']);
$jenis_layanan = mysqli_real_escape_string($koneksi, $_POST['jenis_layanan']);
$keterangan_ub = mysqli_real_escape_string($koneksi, $_POST['keterangan_ub']);

// Pertama, kita perlu mendapatkan klien_id dari usulan_baru
$query_klien_id = "SELECT klien_id FROM usulan_baru WHERE id = '$id'";
$result_klien_id = mysqli_query($koneksi, $query_klien_id);

if ($result_klien_id && mysqli_num_rows($result_klien_id) > 0) {
$row = mysqli_fetch_assoc($result_klien_id);
$klien_id = $row['klien_id'];

// Update data di tabel klien_usulan_baru
$query_update_klien = "UPDATE klien_usulan_baru SET
nik_klien = '$nik_klien',
nama_klien = '$nama_klien',
alamat_klien = '$alamat_klien'
WHERE id = '$klien_id'";
$ubah_klien = mysqli_query($koneksi, $query_update_klien);

// Update data di tabel usulan_baru
$query_update_usulan = "UPDATE usulan_baru SET
tanggal = '$tanggal',
jenis_layanan = '$jenis_layanan',
keterangan_ub = '$keterangan_ub'
WHERE id = '$id'";
$ubah_usulan = mysqli_query($koneksi, $query_update_usulan);

// Cek apakah kedua query berhasil
if ($ubah_klien && $ubah_usulan) {
echo "<script> alert('Edit Data Berhasil');
document.location='fs_dashboard.php?page=fs_usulan_baru';
</script>";
} else {
echo "Error: " . mysqli_error($koneksi);
echo "<script> alert('Edit Data Gagal');
document.location='fs_dashboard.php?page=fs_usulan_baru';
</script>";
}
} else {
echo "<script> alert('ID Klien tidak ditemukan');
document.location='fs_dashboard.php?page=fs_usulan_baru';
</script>";
}
} else {
echo "<script> alert('ID tidak ditemukan');
document.location='fs_dashboard.php?page=fs_usulan_baru';
</script>";
}
}

// Ketika tombol hapus diklik
if (isset($_POST['hapususulanbaru'])) {
$id = mysqli_real_escape_string($koneksi, $_POST['id']);

$hapus = mysqli_query($koneksi, "DELETE FROM usulan_baru WHERE id = '$id'");

if ($hapus) {
echo "<script> alert('Data Berhasil Dihapus');
document.location='fs_dashboard.php?page=fs_usulan_baru';
</script>";
} else {
echo "<script> alert('Data Tidak bisa Dihapus');
document.location='fs_dashboard.php?page=fs_usulan_baru';
</script>";
}
}
?>