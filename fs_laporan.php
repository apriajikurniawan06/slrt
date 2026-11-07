<?php
// Konfigurasi zona waktu, koneksi, dan sesi laporan.
date_default_timezone_set('Asia/Jakarta');

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "slrt";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validasi sesi pengguna.
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Ambil identitas pengguna aktif.
$user_id = $_SESSION['id'];

// Tentukan bulan laporan dari parameter atau bulan berjalan.
$requested_month = $_GET['month'] ?? $_POST['month'] ?? $_POST['bulan'] ?? $_SESSION['selected_month'] ?? null;
$selected_month = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', (string) $requested_month)
    ? $requested_month
    : date('Y-m');
list($year, $month) = array_map('intval', explode('-', $selected_month));


// Simpan filter agar dapat digunakan oleh halaman cetak.
$_SESSION['selected_month'] = $selected_month;


// Siapkan wadah rekap mingguan dengan nilai awal nol.
$data = [
    'minggu_1' => 0,
    'minggu_2' => 0,
    'minggu_3' => 0,
    'minggu_4' => 0,
    'minggu_5' => 0,
    'cek_bansos_minggu_1' => 0,
    'cek_bansos_minggu_2' => 0,
    'cek_bansos_minggu_3' => 0,
    'cek_bansos_minggu_4' => 0,
    'cek_bansos_minggu_5' => 0,
    'usulan_baru_minggu_1' => 0,
    'usulan_baru_minggu_2' => 0,
    'usulan_baru_minggu_3' => 0,
    'usulan_baru_minggu_4' => 0,
    'usulan_baru_minggu_5' => 0,
    'bbl_minggu_1' => 0,
    'bbl_minggu_2' => 0,
    'bbl_minggu_3' => 0,
    'bbl_minggu_4' => 0,
    'bbl_minggu_5' => 0,
    'kelayakan_minggu_1' => 0,
    'kelayakan_minggu_2' => 0,
    'kelayakan_minggu_3' => 0,
    'kelayakan_minggu_4' => 0,
    'kelayakan_minggu_5' => 0,
    'tugas_tambahan_minggu_1' => 0,
    'tugas_tambahan_minggu_2' => 0,
    'tugas_tambahan_minggu_3' => 0,
    'tugas_tambahan_minggu_4' => 0,
    'tugas_tambahan_minggu_5' => 0,
];

// Rekap registrasi untuk tiga kategori pelayanan utama.
$query = "
    SELECT 
        SUM(CASE WHEN UPPER(TRIM(pelayanan)) = 'CEK BANSOS' AND DAY(tanggal) BETWEEN 1 AND 7 THEN 1 ELSE 0 END) AS cek_bansos_minggu_1,
        SUM(CASE WHEN UPPER(TRIM(pelayanan)) = 'CEK BANSOS' AND DAY(tanggal) BETWEEN 8 AND 14 THEN 1 ELSE 0 END) AS cek_bansos_minggu_2,
        SUM(CASE WHEN UPPER(TRIM(pelayanan)) = 'CEK BANSOS' AND DAY(tanggal) BETWEEN 15 AND 21 THEN 1 ELSE 0 END) AS cek_bansos_minggu_3,
        SUM(CASE WHEN UPPER(TRIM(pelayanan)) = 'CEK BANSOS' AND DAY(tanggal) BETWEEN 22 AND 28 THEN 1 ELSE 0 END) AS cek_bansos_minggu_4,
        SUM(CASE WHEN UPPER(TRIM(pelayanan)) = 'CEK BANSOS' AND DAY(tanggal) BETWEEN 29 AND 31 THEN 1 ELSE 0 END) AS cek_bansos_minggu_5,
        SUM(CASE WHEN UPPER(TRIM(pelayanan)) IN ('PKH', 'BPNT', 'BPJS', 'DESIL', 'DTSEN') AND DAY(tanggal) BETWEEN 1 AND 7 THEN 1 ELSE 0 END) AS usulan_baru_minggu_1,
        SUM(CASE WHEN UPPER(TRIM(pelayanan)) IN ('PKH', 'BPNT', 'BPJS', 'DESIL', 'DTSEN') AND DAY(tanggal) BETWEEN 8 AND 14 THEN 1 ELSE 0 END) AS usulan_baru_minggu_2,
        SUM(CASE WHEN UPPER(TRIM(pelayanan)) IN ('PKH', 'BPNT', 'BPJS', 'DESIL', 'DTSEN') AND DAY(tanggal) BETWEEN 15 AND 21 THEN 1 ELSE 0 END) AS usulan_baru_minggu_3,
        SUM(CASE WHEN UPPER(TRIM(pelayanan)) IN ('PKH', 'BPNT', 'BPJS', 'DESIL', 'DTSEN') AND DAY(tanggal) BETWEEN 22 AND 28 THEN 1 ELSE 0 END) AS usulan_baru_minggu_4,
        SUM(CASE WHEN UPPER(TRIM(pelayanan)) IN ('PKH', 'BPNT', 'BPJS', 'DESIL', 'DTSEN') AND DAY(tanggal) BETWEEN 29 AND 31 THEN 1 ELSE 0 END) AS usulan_baru_minggu_5
    FROM 
        registrasi
    WHERE 
        MONTH(tanggal) = $month 
        AND YEAR(tanggal) = $year
        AND user_id = '$user_id'
";

// Jalankan query rekap registrasi.
$result = mysqli_query($conn, $query);
$data_query = mysqli_fetch_assoc($result);

// Gabungkan hasil registrasi ke dalam data laporan.
$data['cek_bansos_minggu_1'] += $data_query['cek_bansos_minggu_1'] ?? 0;
$data['cek_bansos_minggu_2'] += $data_query['cek_bansos_minggu_2'] ?? 0;
$data['cek_bansos_minggu_3'] += $data_query['cek_bansos_minggu_3'] ?? 0;
$data['cek_bansos_minggu_4'] += $data_query['cek_bansos_minggu_4'] ?? 0;
$data['cek_bansos_minggu_5'] += $data_query['cek_bansos_minggu_5'] ?? 0;
$data['usulan_baru_minggu_1'] += $data_query['usulan_baru_minggu_1'] ?? 0;
$data['usulan_baru_minggu_2'] += $data_query['usulan_baru_minggu_2'] ?? 0;
$data['usulan_baru_minggu_3'] += $data_query['usulan_baru_minggu_3'] ?? 0;
$data['usulan_baru_minggu_4'] += $data_query['usulan_baru_minggu_4'] ?? 0;
$data['usulan_baru_minggu_5'] += $data_query['usulan_baru_minggu_5'] ?? 0;

// Rekap layanan BBL.
$query_bbl = "
    SELECT 
        SUM(CASE WHEN DAY(tanggal) BETWEEN 1 AND 7 THEN 1 ELSE 0 END) AS bbl_minggu_1,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 8 AND 14 THEN 1 ELSE 0 END) AS bbl_minggu_2,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 15 AND 21 THEN 1 ELSE 0 END) AS bbl_minggu_3,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 22 AND 28 THEN 1 ELSE 0 END) AS bbl_minggu_4,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 29 AND 31 THEN 1 ELSE 0 END) AS bbl_minggu_5
    FROM 
        bbl
    WHERE 
        MONTH(tanggal) = $month 
        AND YEAR(tanggal) = $year
        AND user_id = '$user_id'
";

// Eksekusi query BBL
$result_bbl = mysqli_query($conn, $query_bbl);
$data_bbl = mysqli_fetch_assoc($result_bbl);

// Gabungkan data dari query ke dalam array $data
$data['bbl_minggu_1'] += $data_bbl['bbl_minggu_1'] ?? 0;
$data['bbl_minggu_2'] += $data_bbl['bbl_minggu_2'] ?? 0;
$data['bbl_minggu_3'] += $data_bbl['bbl_minggu_3'] ?? 0;
$data['bbl_minggu_4'] += $data_bbl['bbl_minggu_4'] ?? 0;
$data['bbl_minggu_5'] += $data_bbl['bbl_minggu_5'] ?? 0;

// Rekap layanan kelayakan.
$query_kelayakan = "
    SELECT 
        SUM(CASE WHEN DAY(tanggal) BETWEEN 1 AND 7 THEN 1 ELSE 0 END) AS kelayakan_minggu_1,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 8 AND 14 THEN 1 ELSE 0 END) AS kelayakan_minggu_2,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 15 AND 21 THEN 1 ELSE 0 END) AS kelayakan_minggu_3,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 22 AND 28 THEN 1 ELSE 0 END) AS kelayakan_minggu_4,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 29 AND 31 THEN 1 ELSE 0 END) AS kelayakan_minggu_5
    FROM 
        kelayakan
    WHERE 
        MONTH(tanggal) = $month 
        AND YEAR(tanggal) = $year
        AND user_id = '$user_id'
";

// Eksekusi query kelayakan
$result_kelayakan = mysqli_query($conn, $query_kelayakan);
$data_kelayakan = mysqli_fetch_assoc($result_kelayakan);

// Gabungkan data dari query kelayakan
$data['kelayakan_minggu_1'] += $data_kelayakan['kelayakan_minggu_1'] ?? 0;
$data['kelayakan_minggu_2'] += $data_kelayakan['kelayakan_minggu_2'] ?? 0;
$data['kelayakan_minggu_3'] += $data_kelayakan['kelayakan_minggu_3'] ?? 0;
$data['kelayakan_minggu_4'] += $data_kelayakan['kelayakan_minggu_4'] ?? 0;
$data['kelayakan_minggu_5'] += $data_kelayakan['kelayakan_minggu_5'] ?? 0;

// Rekap tugas tambahan.
$query_tugas_tambahan = "
    SELECT 
        SUM(CASE WHEN DAY(tanggal) BETWEEN 1 AND 7 THEN 1 ELSE 0 END) AS tugas_tambahan_minggu_1,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 8 AND 14 THEN 1 ELSE 0 END) AS tugas_tambahan_minggu_2,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 15 AND 21 THEN 1 ELSE 0 END) AS tugas_tambahan_minggu_3,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 22 AND 28 THEN 1 ELSE 0 END) AS tugas_tambahan_minggu_4,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 29 AND 31 THEN 1 ELSE 0 END) AS tugas_tambahan_minggu_5
    FROM 
        tugas_tambahan
    WHERE 
        MONTH(tanggal) = $month 
        AND YEAR(tanggal) = $year
        AND user_id = '$user_id'
";

// Eksekusi query tugas tambahan
$result_tugas_tambahan = mysqli_query($conn, $query_tugas_tambahan);
$data_tugas_tambahan = mysqli_fetch_assoc($result_tugas_tambahan);

// Gabungkan data dari query tugas tambahan
$data['tugas_tambahan_minggu_1'] += $data_tugas_tambahan['tugas_tambahan_minggu_1'] ?? 0;
$data['tugas_tambahan_minggu_2'] += $data_tugas_tambahan['tugas_tambahan_minggu_2'] ?? 0;
$data['tugas_tambahan_minggu_3'] += $data_tugas_tambahan['tugas_tambahan_minggu_3'] ?? 0;
$data['tugas_tambahan_minggu_4'] += $data_tugas_tambahan['tugas_tambahan_minggu_4'] ?? 0;
$data['tugas_tambahan_minggu_5'] += $data_tugas_tambahan['tugas_tambahan_minggu_5'] ?? 0;

for ($i = 1; $i <= 5; $i++) {
    $data["minggu_$i"] =
        $data["cek_bansos_minggu_$i"] +
        $data["usulan_baru_minggu_$i"];
}


?>

<style>
  .mx-2 .d-flex .print {
    position: fixed;
    bottom: 15px; /* Menempatkan elemen di bagian bawah */
    left: 50%; /* Mengatur posisi horizontal ke tengah */
    transform: translateX(-50%); /* Menggeser elemen ke kiri setengah lebar elemen */
    align-items: center;
    justify-content: center;
    display: flex;
    z-index: 1;
    width: 200px;
    background-color: red;
    border-radius: 20px;
    padding: 5px;
}

.mx-2 .d-flex .print a {
    color: white;
    position: relative;
    align-items: center;
    justify-content: center;
    display: flex;
    font-size: 20px;
}

.report-table {
    min-width: 760px;
    margin-bottom: 0;
    vertical-align: middle;
}

.report-table thead th {
    background-color: #eef2f5;
    text-align: center;
    white-space: nowrap;
}

.report-table tbody td {
    padding: 10px 12px;
}
</style>

<link rel="stylesheet" href="https://unpkg.com/ionicons@5.5.2/dist/css/ionicons.min.css">

<div class="mx-2 bg-white" style="position: relative; top:0px;">
    <!-- FILTER DATA -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="card-body border-dark rounded">
        <?php
            // Cek apakah toggle sudah di-set
            if (!isset($_SESSION['toggle'])) {
                $_SESSION['toggle'] = false; // Default off
            }

            // Toggle status
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle'])) {
                $_SESSION['toggle'] = !$_SESSION['toggle']; // Toggle on/off
            }
        ?>





        <!-- FILTER MONTH -->
        <div class="container mt-3">
            <form method="POST" action="">
                <div class="row justify-content-center">
                    <div class="col-auto mb-1">
                        <div class="input-group">
                            <input type="search" class="form-control" id="search-input" placeholder="Cari..." aria-label="Search">
                            <span class="input-group-text" id="basic-addon2">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="input-group">                   
                            <input type="month" name="month" id="month-input" class="form-control" value="<?= htmlspecialchars($selected_month, ENT_QUOTES, 'UTF-8'); ?>" onchange="updateMonthName()">
                        </div>
                    </div>
                    <div class="col-auto">
                            <?php 
                                // FILTER STATUS===========================================
                                $filter_status_query = mysqli_query($conn, "SELECT l.filter_status FROM laporan l WHERE l.user_id = '$user_id'");

                                // Cek apakah query berhasil dan ada hasil
                                if ($filter_status_query && mysqli_num_rows($filter_status_query) > 0) {
                                    $filter_status = mysqli_fetch_assoc($filter_status_query);
                                    $status = htmlspecialchars($filter_status['filter_status'], ENT_QUOTES);

                                    // Tampil filter jika status "Off"
                                    if ($status === 'On Filter') {
                                        echo '<button type="submit" class="btn btn-primary" style="font-size: 20px; border:none;">
                                                <i class="fas fa-filter me-1 text-white"></i> Filter
                                            </button>';
                                    } elseif ($status === 'Off') {
                                        echo '<button onclick="showAlert(event)" class="btn btn-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z"/>
                                            </svg>
                                        </button>';
                                        echo '<script>
                                        function showAlert(event) {
                                            event.preventDefault(); // Mencegah form disubmit
                                            Swal.fire({
                                                title: "Peringatan",
                                                text: "Hubungi Sekretaris atau Koordinator",
                                                icon: "warning",
                                                confirmButtonText: "OK",
                                                timer: 20000 // Notifikasi akan tampil selama 5 detik
                                            });
                                        }
                                        </script>';
                                    }
                                } 
                            ?>
                            <button type="submit" class="btn btn-outline-secondary ms-1" formaction="fs_cetak.php" formmethod="get" formtarget="_blank" name="print" value="1" title="Cetak laporan bulan terpilih">
                                <i class="fas fa-print me-1"></i> Cetak
                            </button>
                        </div>
                    </div>
                </form>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- FILTER MONTH -->


    <div class="text-center text-dark mt-2">
        <h4>LAPORAN FASILITATOR</h4>
        <span id="selected-month-year" class="badge bg-info" style="font-size: 20px;">
            <?php
            $months = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];

            $monthName = $months[(int)$month];
            echo $monthName . ' ' . $year;
            ?>
        </span>
    </div>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="assets/dist/css/adminlte.min.css">

        
        <div class="mx-1">
            <div class="card-body">
                <div class="table-responsive">
                <table id="example1" class="table table-bordered table-hover report-table">
                    <thead>
                        <tr>
                            <th class="text-center">NO</th>
                            <th class="text-center">JADWAL</th>
                            <th class="text-center">JUMLAH LAYANAN</th>
                            <th class="text-center">CEK BANSOS</th>
                            <th class="text-center">USULAN BARU</th>
                            <th class="text-center">BBL</th>
                            <th class="text-center">KELAYAKAN</th>
                            <th class="text-center">TUGAS TAMBAHAN</th>
                            <th class="text-center">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>MINGGU 1 (1-7)</td>
                            <td class="text-center"><?php echo $data['minggu_1']; ?></td>
                            <td class="text-center"><?php echo $data['cek_bansos_minggu_1']; ?></td>
                            <td class="text-center"><?php echo $data['usulan_baru_minggu_1']; ?></td>
                            <td class="text-center"><?php echo $data['bbl_minggu_1']; ?></td>
                            <td class="text-center"><?php echo $data['kelayakan_minggu_1']; ?></td>
                            <td class="text-center"><?php echo $data['tugas_tambahan_minggu_1']; ?></td>
                            <td class="text-center">-</td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td>MINGGU 2 (8-14)</td>
                            <td class="text-center"><?php echo $data['minggu_2']; ?></td>
                            <td class="text-center"><?php echo $data['cek_bansos_minggu_2']; ?></td>
                            <td class="text-center"><?php echo $data['usulan_baru_minggu_2']; ?></td>
                            <td class="text-center"><?php echo $data['bbl_minggu_2']; ?></td>
                            <td class="text-center"><?php echo $data['kelayakan_minggu_2']; ?></td>
                            <td class="text-center"><?php echo $data['tugas_tambahan_minggu_2']; ?></td>
                            <td class="text-center">-</td>
                            <!-- <td class="text-center"></td>
                            <td class="text-center"></td> -->
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td>MINGGU 3 (15-21)</td>
                            <td class="text-center"><?php echo $data['minggu_3']; ?></td>
                            <td class="text-center"><?php echo $data['cek_bansos_minggu_3']; ?></td>
                            <td class="text-center"><?php echo $data['usulan_baru_minggu_3']; ?></td>
                            <td class="text-center"><?php echo $data['bbl_minggu_3']; ?></td>
                            <td class="text-center"><?php echo $data['kelayakan_minggu_3']; ?></td>
                            <td class="text-center"><?php echo $data['tugas_tambahan_minggu_3']; ?></td>
                            <td class="text-center">-</td>
                            <!-- <td class="text-center"></td>
                            <td class="text-center"></td> -->
                        </tr>
                        <tr>
                            <td class="text-center">4</td>
                            <td>MINGGU 4 (22-28)</td>
                            <td class="text-center"><?php echo $data['minggu_4']; ?></td>
                            <td class="text-center"><?php echo $data['cek_bansos_minggu_4']; ?></td>
                            <td class="text-center"><?php echo $data['usulan_baru_minggu_4']; ?></td>
                            <td class="text-center"><?php echo $data['bbl_minggu_4']; ?></td>
                            <td class="text-center"><?php echo $data['kelayakan_minggu_4']; ?></td>
                            <td class="text-center"><?php echo $data['tugas_tambahan_minggu_4']; ?></td>
                            <td class="text-center">-</td>
                            <!-- <td class="text-center"></td>
                            <td class="text-center"></td> -->
                        </tr>
                        <tr>
                            <td class="text-center">5</td>
                            <td>MINGGU 5 (29-31)</td>
                            <td class="text-center"><?php echo $data['minggu_5']; ?></td>
                            <td class="text-center"><?php echo $data['cek_bansos_minggu_5']; ?></td>
                            <td class="text-center"><?php echo $data['usulan_baru_minggu_5']; ?></td>
                            <td class="text-center"><?php echo $data['bbl_minggu_5']; ?></td>
                            <td class="text-center"><?php echo $data['kelayakan_minggu_5']; ?></td>
                            <td class="text-center"><?php echo $data['tugas_tambahan_minggu_5']; ?></td>
                            <td class="text-center">-</td>
                            <!-- <td class="text-center"></td>
                            <td class="text-center"></td> -->
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        </div>
    </div>













    <?php
    // Gunakan bulan yang sama dengan filter laporan.
    $month = (int) $month;
    $year = (int) $year;

    // TABEL PELAYANAN SLRT
    ?>

<div class="card-body mx-2 bg-white border bordered mb-3 mt-5">
    <h3 class="text-center">BNBA PELAYANAN DI SEKRETARIAT SLRT</h3> <br>
    <div class="table-responsive mb-2">
        <table id="example2" class="table table-bordered border table-hover report-table">
            <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">TANGGAL</th>
                    <th>NIK</th>
                    <th>NAMA LENGKAP</th>
                    <th>ALAMAT</th>
                    <th>JENIS PELAYANAN</th>
                    <th class="text-center">KETERANGAN</th>
                </tr>
            </thead>
            <tbody id="data-table-body">
                <?php 
                    // Ambil data
                    $no = 1;
                    $stmt = $conn->prepare("SELECT id, tanggal, nik_kepala_keluarga, nama_pengurus_bansos, alamat, pelayanan FROM registrasi WHERE user_id = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ? ORDER BY tanggal DESC");
                    $stmt->bind_param("iii", $user_id, $month, $year);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Cek apakah ada data
                    if ($result->num_rows == 0) {
                        echo '<tr id="no-data-row"><td colspan="7" class="text-center">Tidak ada data terinput untuk bulan ini.</td></tr>';
                    } else {
                        while ($data = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td class="text-center"><?= htmlspecialchars(date('d-m-Y', strtotime($data['tanggal'])), ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['nik_kepala_keluarga'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['nama_pengurus_bansos'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['alamat'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['pelayanan'], ENT_QUOTES); ?></td>
                    <td class="text-center">Selesai</td>
                </tr>
                <?php 
                        } 
                    } 
                    $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- TABEL BBL SLRT -->
<div class="card-body mx-2 bg-white border bordered mb-3 mt-5">
    <h3 class="text-center">BNBA PENDATAAN BBL</h3> <br>
    <div class="table-responsive">
        <table id="example1" class="table table-bordered border table-hover report-table">
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
                // Ambil data berdasarkan user_id
                $no = 1;
                $stmt = $conn->prepare("SELECT 
                        b.id AS bbl_id, 
                        b.tanggal, 
                        k.nik_klien, 
                        k.nama_klien, 
                        k.alamat_klien, 
                        b.jenis_layanan,
                        b.keterangan_bbl
                    FROM 
                        bbl b 
                    JOIN 
                        klien_bbl k ON b.klien_id = k.id 
                    WHERE 
                        b.user_id = ? 
                        AND MONTH(b.tanggal) = ? 
                        AND YEAR(b.tanggal) = ? 
                    ORDER BY 
                        b.tanggal DESC");
                $stmt->bind_param("iii", $user_id, $month, $year);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($data = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td class="text-center"><?= htmlspecialchars(date('d-m-Y', strtotime($data['tanggal'])), ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['nik_klien'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['nama_klien'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['alamat_klien'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['jenis_layanan'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['keterangan_bbl'], ENT_QUOTES); ?></td>
                </tr>
            <?php 
                } 
                $stmt->close();
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- TABEL USULAN BARU -->
<div class="card-body mx-2 bg-white border bordered mb-3 mt-5">
    <h3 class="text-center">BNBA PENDATAAN USULAN BARU</h3> <br>
    <div class="table-responsive mb-3">
        <table id="example1" class="table table-bordered border table-hover report-table">
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
                // Ambil data berdasarkan user_id
                $no = 1;
                $stmt = $conn->prepare("SELECT 
                        ub.id AS usulan_baru_id, 
                        ub.tanggal, 
                        kub.nik_klien, 
                        kub.nama_klien, 
                        kub.alamat_klien, 
                        ub.jenis_layanan,
                        ub.keterangan_ub
                    FROM 
                        usulan_baru ub 
                    JOIN 
                        klien_usulan_baru kub ON ub.klien_id = kub.id 
                    WHERE 
                        ub.user_id = ? 
                        AND MONTH(ub.tanggal) = ? 
                        AND YEAR(ub.tanggal) = ? 
                    ORDER BY 
                        ub.tanggal DESC");
                $stmt->bind_param("iii", $user_id, $month, $year);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($data = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td class="text-center"><?= htmlspecialchars(date('d-m-Y', strtotime($data['tanggal'])), ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['nik_klien'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['nama_klien'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['alamat_klien'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['jenis_layanan'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['keterangan_ub'], ENT_QUOTES); ?></td>
                </tr>
            <?php 
                } 
                $stmt->close();
            ?>
            </tbody>
        </table>
    </div>
</div>



<!-- TABEL TUGAS TAMBAHAN SLRT -->
<div class="card-body mx-2 bg-white border bordered mb-3 mt-5">
    <h3 class="text-center">TABEL KELAYAKAN</h3> <br>
    <div class="table-responsive mb-3">
        <table id="example1" class="table table-bordered border table-hover report-table">
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
                    // Ambil data
                    $no = 1;
                    $stmt = $conn->prepare("SELECT 
                            tt.id AS kelayakan_id, 
                            tt.tanggal, 
                            ktt.nik_klien, 
                            ktt.nama_klien, 
                            ktt.alamat_klien, 
                            tt.jenis_layanan,
                            tt.keterangan_kelayakan
                        FROM 
                            kelayakan tt 
                        JOIN 
                            klien_kelayakan ktt ON tt.klien_id = ktt.id 
                        WHERE 
                            tt.user_id = ? 
                            AND MONTH(tt.tanggal) = ? 
                            AND YEAR(tt.tanggal) = ? 
                        ORDER BY 
                            tt.tanggal DESC");
                    $stmt->bind_param("iii", $user_id, $month, $year);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($data = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td class="text-center"><?= htmlspecialchars(date('d-m-Y', strtotime($data['tanggal'])), ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['nik_klien'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['nama_klien'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['alamat_klien'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['jenis_layanan'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['keterangan_kelayakan'], ENT_QUOTES); ?></td>
                </tr>
                <?php 
                    } 
                    $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
</div>
<br>



<!-- TABEL TUGAS TAMBAHAN SLRT -->
<div class="card-body mx-2 bg-white border bordered mb-3 mt-5">
    <h3 class="text-center">TABEL TUGAS TAMBAHAN</h3> <br>
    <div class="table-responsive mb-3">
        <table id="example1" class="table table-bordered border table-hover report-table">
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
                    // Ambil data
                    $no = 1;
                    $stmt = $conn->prepare("SELECT 
                            tt.id AS tugas_tambahan_id, 
                            tt.tanggal, 
                            ktt.nik_klien, 
                            ktt.nama_klien, 
                            ktt.alamat_klien, 
                            tt.jenis_layanan,
                            tt.keterangan_tt
                        FROM 
                            tugas_tambahan tt 
                        JOIN 
                            klien_tugas_tambahan ktt ON tt.klien_id = ktt.id 
                        WHERE 
                            tt.user_id = ? 
                            AND MONTH(tt.tanggal) = ? 
                            AND YEAR(tt.tanggal) = ? 
                        ORDER BY 
                            tt.tanggal DESC");
                    $stmt->bind_param("iii", $user_id, $month, $year);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($data = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td class="text-center"><?= htmlspecialchars(date('d-m-Y', strtotime($data['tanggal'])), ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['nik_klien'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['nama_klien'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['alamat_klien'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['jenis_layanan'], ENT_QUOTES); ?></td>
                    <td><?= htmlspecialchars($data['keterangan_tt'], ENT_QUOTES); ?></td>
                </tr>
                <?php 
                    } 
                    $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
</div>
<br>

<script>
    $(document).ready(function() {
    // Pencarian
    $('#search-input').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Update bulan dan tahun yang dipilih
    $('#month-input').on('change', function() {
        updateMonthName();
    });

    // Tampilkan bulan yang sudah dipilih dari server.
    updateMonthName();
});

function setCurrentMonthYear() {
    const monthInput = document.getElementById('month-input');
    if (monthInput.value) {
        return;
    }
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Tambahkan '0' di depan jika bulan < 10
    monthInput.value = `${year}-${month}`;
}

function updateMonthName() {
    const monthInput = document.getElementById('month-input');
    const monthNameDisplay = document.getElementById('selected-month-year');

    // Ambil nilai bulan dan tahun dari input
    const selectedDate = new Date(monthInput.value + '-01'); // Tambahkan '-01' untuk membuat tanggal valid
    const monthNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    // Tampilkan nama bulan dan tahun
    monthNameDisplay.textContent = monthNames[selectedDate.getMonth()] + ' ' + selectedDate.getFullYear();
}
</script>


