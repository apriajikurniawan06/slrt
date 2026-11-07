<?php 

include "koneksi.php";

// Cek apakah user_id ada dalam sesi
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Jika ada, ambil user_id
$user_id = $_SESSION['id'];

// Ambil data berdasarkan user yang login
$query = "SELECT tanggal, nik_kepala_keluarga, nama_pengurus_bansos, alamat, pelayanan
          FROM registrasi
          WHERE MONTH(tanggal) = MONTH(CURRENT_DATE) 
          AND YEAR(tanggal) = YEAR(CURRENT_DATE)
          AND pelayanan = 'CEK BANSOS'  -- Memastikan hanya mengambil data dengan pelayanan BPNT
          AND user_id = '$user_id'"; // Pastikan kolom user_id ada di tabel registrasi

$tampil = mysqli_query($koneksi, $query);

?>


<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fall back"> 
<!-- Font Awesome -->
<link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">




<div class="mx-2" style="position: relative; top: 20px;">
    <div class="card-body">
        <div class="text-dark">
            <h3 class="text-center mt-2">CEK BANSOS</h3>
        </div>
        <div class="mx-1">
            <div class="my-3">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">NO</th>
                                <th class="text-center">TANGGAL</th>
                                <th>NIK</th>
                                <th>NAMA LENGKAP</th>
                                <th>ALAMAT</th>
                                <th>JENIS PELAYANAN</th>
                                <th>KETERANGAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $no = 1;
                                while ($data = mysqli_fetch_array($tampil)) {
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td class="text-center"><?= $data['tanggal']; ?></td>
                                <td><?= $data['nik_kepala_keluarga']; ?></td>
                                <td><?= $data['nama_pengurus_bansos']; ?></td>
                                <td><?= $data['alamat']; ?></td>
                                <td><?= $data['pelayanan']; ?></td>
                                <td>Selesai</td>
                            </tr>
                            <?php 
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

