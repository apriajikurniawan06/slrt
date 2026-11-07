<?php 
include "koneksi.php";
$total_dtsen = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM registrasi WHERE pelayanan = 'DTSEN' AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)"))['total'] ?? 0;
if (empty($_SESSION['username']) || empty($_SESSION['level']) || empty($_SESSION['nama_lengkap']) ) {
    echo "<script>alert('Maaf, Silahkan Login terlebih dahulu.');</script>";
    header("Location: index.php");
    exit();
}
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="assets/dist/css/adminlte.min.css">

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-primary">DATA DTSEN</h3>
                        <span class="badge bg-primary">Jumlah data: <?= $total_dtsen ?? 0; ?></span>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>NO</th> <!-- Menambahkan kolom untuk nomor urut -->
                                    <th>TANGGAL</th>
                                    <th>NOMOR KK</th>
                                    <th>NIK</th>
                                    <th>NAMA LENGKAP</th>
                                    <th>KECAMATAN</th>
                                    <th>KELURAHAN</th>
                                    <th>PELAYANAN</th>
                                    <th>NAMA FASILITATOR</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Contoh koneksi ke database dan pengambilan data
                                $conn = new mysqli("localhost", "root", "", "slrt");

                                // Cek koneksi
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                $no = 1; // Inisialisasi nomor urut
                                // Query untuk mengambil data dari tabel registrasi
                                $sql = "SELECT tanggal, nama_pengurus_bansos, nomor_kk, nik_kepala_keluarga, kecamatan, kelurahan, pelayanan, nama_fasilitator, approval_status 
                                FROM registrasi 
                                WHERE pelayanan = 'DTSEN' 
                                AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
                                $result = $conn->query($sql);

                                // Cek apakah ada hasil
                                if ($result->num_rows > 0) {
                                    // Output data dari setiap baris
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td>" . $no++ . "</td> <!-- Menampilkan nomor urut -->
                                                <td>" . htmlspecialchars($row["tanggal"]) . "</td>
                                                <td>" . htmlspecialchars($row["nomor_kk"]) . "</td>
                                                <td>" . htmlspecialchars($row["nik_kepala_keluarga"]) . "</td>
                                                <td>" . htmlspecialchars($row["nama_pengurus_bansos"]) . "</td>
                                                <td>" . htmlspecialchars($row["kecamatan"]) . "</td>
                                                <td>" . htmlspecialchars($row["kelurahan"]) . "</td>
                                                <td>" . htmlspecialchars($row["pelayanan"]) . "</td>
                                                <td>" . htmlspecialchars($row["nama_fasilitator"]) . "</td>
                                                <td>" . htmlspecialchars($row["approval_status"]) . "</td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9' class='text-center'>Tidak ada data ditemukan</td></tr>";
                                }

                                // Tutup koneksi
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
