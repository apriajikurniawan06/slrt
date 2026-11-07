<?php 

$server   = "localhost";
$user     = "root";
$password = "";
$database = "slrt";

// Membuat koneksi
$koneksi = mysqli_connect($server, $user, $password, $database);

// Periksa koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}



?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Registrasi</title>
    <meta name="theme-color" content="#f8fafa">
    <link rel="manifest" href="manifest.json">

    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="assets5/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets5/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="assets5/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="assets5/dist/css/adminlte.min.css">
    
    <script>
        // Function to automatically print the page
        window.onload = function() {
            window.print();
        };
    </script>
</head>
<body onload="window.print()">

<div class="mt-3">
    <div class="d-flex justify-content-between align-items-center my-1">
        <div class="title">
            <h4 class="mb-0 ms-4">DAFTAR REGISTRASI BANSOS</h4>
        </div>
        <div class="date text-muted">
            <span class="text-dark me-4"><?php echo date('d-m-Y'); ?></span>
        </div>
    </div>

    <div class=" card-body table-responsive">
        <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA PENGURUS BANSOS</th>
                    <th>NIK</th>
                    <th>KECAMATAN</th>
                    <th>KELURAHAN</th>
                    <th>BANSOS DITERIMA</th>
                    <th>PENDAFTARAN BANSOS</th>
                    <th>ALAMAT</th>
                    <th>NOMOR TLP/WA</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                $no = 1;
                // Gantilah 'tanggal_pembuatan' dengan kolom yang benar dari tabel 'registrasi'
                $query = "SELECT * FROM registrasi WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ORDER BY id DESC";
                $stmt = $koneksi->prepare($query);
                
                if ($stmt) {
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($data = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$no}</td>
                                <td>" . htmlspecialchars($data['nama_pengurus_bansos']) . "</td>
                                <td>" . htmlspecialchars($data['nik_kepala_keluarga']) . "</td>
                                <td>" . htmlspecialchars($data['kecamatan']) . "</td>
                                <td>" . htmlspecialchars($data['kelurahan']) . "</td>
                                <td class='text-center'>" . htmlspecialchars($data['bansos_diperoleh']) . "</td>
                                <td class='text-center'>" . htmlspecialchars($data['pelayanan']) . "</td>
                                <td>" . htmlspecialchars($data['alamat']) . "</td>
                                <td>" . htmlspecialchars($data['nomor_tlp']) . "</td>
                              </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='5'>Gagal mengambil data.</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
</div>

<style>

@media print {
    /* Menyembunyikan alamat URL yang muncul di header atau footer */
    @page {
        margin: 0; /* Menghapus margin default */
    }
}
    .back {
        position: fixed;
        bottom: 10px; /* Jarak dari bawah */
        right: 10px;  /* Jarak dari kanan */
        background-color: #e0e0e0; /* Warna abu-abu */
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .back a {
        color: #ffffff; /* Warna ikon putih */
        font-size: 24px; /* Ukuran ikon */
        text-decoration: none; /* Hilangkan garis bawah */
    }
    .back a {
        color: red;
    }

    .back:hover {
        background-color: #cfcfcf; /* Warna abu-abu lebih gelap saat hover */
        transition: background-color 0.3s ease;
    }
</style>

<!-- Modal pop up icon -->
<div class="back shadow">
    <a href="dashboard/rekapitulasi.php?page=rekapitulasi" class="icon">
        <ion-icon name="return-up-back-outline"></ion-icon>
    </a>
</div>

<script>
    window.onafterprint = function() {
            window.location.href = 'dashboard/cetak_daftar_bansos.php?page=op-dashboard'; // Ganti dengan URL halaman dashboard Anda
        };
</script>

<!-- Ionicons Script -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

<!-- modal pop up icon -->


</body>
</html>
