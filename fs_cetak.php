<?php
// Konfigurasi sesi, zona waktu, dan koneksi database.
session_start();
date_default_timezone_set('Asia/Jakarta');
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

// Menahan output sampai seluruh proses halaman selesai.
ob_start();

// Validasi sesi pengguna.
if (!isset($_SESSION['id'])) {
    header("Location: fs_dashboard.php?page=menu");
    exit();
}

// Ambil identitas pengguna aktif.
$user_id = $_SESSION['id'];

if (empty($_SESSION['username']) || empty($_SESSION['level']) || empty($_SESSION['nama_lengkap'])) {
    echo "<script>alert('Maaf, Silahkan Login terlebih dahulu.');</script>";
    header("Location: index.php");
    exit();
}

// Tentukan bulan laporan dari parameter, filter tersimpan, atau bulan berjalan.
$requested_month = $_GET['month'] ?? $_POST['month'] ?? $_POST['bulan'] ?? $_SESSION['selected_month'] ?? null;
$selected_month = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', (string) $requested_month)
    ? $requested_month
    : date('Y-m');
$_SESSION['selected_month'] = $selected_month;
list($year, $month) = array_map('intval', explode('-', $selected_month));
$nama_bulan = [
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
$tanggal_cetak = date('d') . ' ' . $nama_bulan[(int) date('n')] . ' ' . date('Y');

// Siapkan wadah seluruh rekap mingguan dengan nilai awal nol.
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

// Jalankan query rekap dan gabungkan hasilnya ke data laporan.
function runQuery($koneksi, $query, $data) {
    $result = mysqli_query($koneksi, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $data_query = mysqli_fetch_assoc($result);
        foreach ($data_query as $key => $value) {
            if (isset($data[$key])) {
                $data[$key] += (int)$value;
            }
        }
    }
    return $data;
}

// Rekap registrasi: CEK BANSOS serta USULAN BARU.
$query_cek_bansos = "SELECT 
    SUM(CASE WHEN DAY(tanggal) BETWEEN 1 AND 7 AND UPPER(TRIM(pelayanan)) = 'CEK BANSOS' THEN 1 ELSE 0 END) AS cek_bansos_minggu_1,
    SUM(CASE WHEN DAY(tanggal) BETWEEN 8 AND 14 AND UPPER(TRIM(pelayanan)) = 'CEK BANSOS' THEN 1 ELSE 0 END) AS cek_bansos_minggu_2,
    SUM(CASE WHEN DAY(tanggal) BETWEEN 15 AND 21 AND UPPER(TRIM(pelayanan)) = 'CEK BANSOS' THEN 1 ELSE 0 END) AS cek_bansos_minggu_3,
    SUM(CASE WHEN DAY(tanggal) BETWEEN 22 AND 28 AND UPPER(TRIM(pelayanan)) = 'CEK BANSOS' THEN 1 ELSE 0 END) AS cek_bansos_minggu_4,
    SUM(CASE WHEN DAY(tanggal) BETWEEN 29 AND 31 AND UPPER(TRIM(pelayanan)) = 'CEK BANSOS' THEN 1 ELSE 0 END) AS cek_bansos_minggu_5,
    SUM(CASE WHEN DAY(tanggal) BETWEEN 1 AND 7 AND UPPER(TRIM(pelayanan)) IN ('PKH', 'BPNT', 'BPJS', 'DESIL', 'DTSEN') THEN 1 ELSE 0 END) AS usulan_baru_minggu_1,
    SUM(CASE WHEN DAY(tanggal) BETWEEN 8 AND 14 AND UPPER(TRIM(pelayanan)) IN ('PKH', 'BPNT', 'BPJS', 'DESIL', 'DTSEN') THEN 1 ELSE 0 END) AS usulan_baru_minggu_2,
    SUM(CASE WHEN DAY(tanggal) BETWEEN 15 AND 21 AND UPPER(TRIM(pelayanan)) IN ('PKH', 'BPNT', 'BPJS', 'DESIL', 'DTSEN') THEN 1 ELSE 0 END) AS usulan_baru_minggu_3,
    SUM(CASE WHEN DAY(tanggal) BETWEEN 22 AND 28 AND UPPER(TRIM(pelayanan)) IN ('PKH', 'BPNT', 'BPJS', 'DESIL', 'DTSEN') THEN 1 ELSE 0 END) AS usulan_baru_minggu_4,
    SUM(CASE WHEN DAY(tanggal) BETWEEN 29 AND 31 AND UPPER(TRIM(pelayanan)) IN ('PKH', 'BPNT', 'BPJS', 'DESIL', 'DTSEN') THEN 1 ELSE 0 END) AS usulan_baru_minggu_5
FROM 
    registrasi
WHERE 
    MONTH(tanggal) = $month 
    AND YEAR(tanggal) = $year
    AND user_id = '$user_id'";

$data = runQuery($koneksi, $query_cek_bansos, $data);

// Rekap layanan BBL.
$query_bbl = "SELECT 
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
    AND user_id = '$user_id'";

$data = runQuery($koneksi, $query_bbl, $data);

// Rekap layanan kelayakan.
$query_kelayakan = "SELECT 
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
    AND user_id = '$user_id'";

$data = runQuery($koneksi, $query_kelayakan, $data);

// Rekap tugas tambahan dan kolom tugas lain.
$query_tugas_tambahan = "SELECT 
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
    AND user_id = '$user_id'";

$data = runQuery($koneksi, $query_tugas_tambahan, $data);

// Jumlah layanan merupakan total CEK BANSOS dan USULAN BARU.
for ($i = 1; $i <= 5; $i++) {
    $data["minggu_$i"] =
        $data["cek_bansos_minggu_$i"] +
        $data["usulan_baru_minggu_$i"];
}



// Kelola tanda tangan fasilitator.
if (!isset($_SESSION['id'])) {
    header("Location: fs_dashboard.php?page=menu");
    exit();
}

$user_id = $_SESSION['id'];

// Proses unggah tanda tangan.
$upload_message = '';
$upload_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['ttd_image'])) {
    $upload_dir = 'uploads/ttd/';
    
    // Buat folder jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_name = $_FILES['ttd_image']['name'];
    $file_tmp = $_FILES['ttd_image']['tmp_name'];
    $file_size = $_FILES['ttd_image']['size'];
    $file_error = $_FILES['ttd_image']['error'];
    
    // Validasi file
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if ($file_error === UPLOAD_ERR_OK) {
        if (in_array($file_extension, $allowed_extensions)) {
            if ($file_size <= 2 * 1024 * 1024) { // Max 2MB
                // Generate nama file unik
                $new_file_name = 'ttd_' . $user_id . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_file_name;
                
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    // Update database
                    $query = "UPDATE users SET gbr_ttd = ? WHERE id = ?";
                    $stmt = mysqli_prepare($koneksi, $query);
                    mysqli_stmt_bind_param($stmt, "si", $new_file_name, $user_id);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $upload_success = true;
                        $upload_message = "TTD berhasil diupload!";
                        
                        // Update session jika perlu
                        $_SESSION['gbr_ttd'] = $new_file_name;
                    } else {
                        $upload_message = "Gagal menyimpan ke database: " . mysqli_error($koneksi);
                        // Hapus file yang sudah diupload
                        unlink($upload_path);
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $upload_message = "Gagal mengupload file.";
                }
            } else {
                $upload_message = "Ukuran file terlalu besar. Maksimal 2MB.";
            }
        } else {
            $upload_message = "Format file tidak didukung. Gunakan JPG, JPEG, PNG, GIF, atau BMP.";
        }
    } else {
        $upload_message = "Error upload: " . $file_error;
    }
}

// Proses hapus tanda tangan.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ttd'])) {
    // Ambil nama file TTD dari database
    $query = "SELECT gbr_ttd FROM users WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $current_ttd);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    if ($current_ttd) {
        // Hapus file dari server
        $file_path = 'uploads/ttd/' . $current_ttd;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Update database
        $query = "UPDATE users SET gbr_ttd = NULL WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $upload_message = "TTD berhasil dihapus!";
            $_SESSION['gbr_ttd'] = null;
        } else {
            $upload_message = "Gagal menghapus TTD dari database.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Ambil data TTD user
$query = "SELECT gbr_ttd FROM users WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $gbr_ttd);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

?>

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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


<style>
    @media print {
        @page {
            margin: 0;
        }
        body::after {
            display: none;
        }
        .print-hide {
            display: none !important;
        }
        .no-print {
            display: none !important;
        }
    }

    .dokumentasi {
        position: relative;
        top: 200px;
    }

    .filter-section {
        background: #f8f9fa;
        border-left: 4px solid #007bff;
        padding: 15px;
    }
</style>

<!-- BAGIAN UTAMA LAPORAN -->
<section class="rotasi mx-1 text-dark" style="position:relative; top:20px; font-family: 'Times New Roman', Times, serif;">
    <div class="d-flex justify-content-center align-items-center">
        <div class="text-center mt-2">
            <h1 class="card-title bold">LAPORAN KEGIATAN FASILITATOR</h1>
        </div>
    </div> 
    <br>
    <div class="table-responsive">
        <table class="mx-4">
        <?php
        $month_int = (int)$month;
        $namaBulan = strtoupper($nama_bulan[$month_int]);
        ?>
            <tr>
                <td class="uppercase">BULAN : <?= $namaBulan ?></td>
                <td>TAHUN : <?= $year ?></td>
            </tr>
            <tr>
                <td><b>WILAYAH KERJA FASILITATOR</b></td>
            </tr>
            <tr>
                <td>Kelurahan</td>
                <td>: <?= htmlspecialchars($_SESSION['klrhn']) ?></td>
            </tr>
            <tr>
                <td>Kecamatan</td>
                <td>: <?= htmlspecialchars($_SESSION['kcmtn']) ?></td>
            </tr>
            <tr>
                <td>Kota</td>
                <td>: Tanjungbalai</td>
            </tr>
            <tr>
                <td>Provinsi</td>
                <td>: Sumatera Utara</td>
            </tr>
        </table>
    </div>
    <br>
    <div class="bg-white">
        <div class="border-dark rounded">
            <div class="card-body">
                <h5 class="text-center">BNBA PELAYANAN SEKRETARIAT SLRT</h5> <br>
                <table id="example1" class="table table-bordered table-responsive-lg">
                    <thead>
                        <tr>
                            <th rowspan="2">NO</th>
                            <th rowspan="2">JADWAL</th>
                            <th rowspan="2">JUMLAH LAYANAN</th>
                            <th colspan="4" class="text-center">PENDATAAN</th>
                            <th rowspan="2">TUGAS TAMBAHAN</th>
                            <th rowspan="2">KETERANGAN</th>
                        </tr>
                        <tr>
                            <th>CEK BANSOS</th>
                            <th>USULAN BARU</th>
                            <th>BBL</th>
                            <th>KELAYAKAN</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <tr>
                            <td class="text-center"><?= $i ?></td>
                            <td>MINGGU <?= $i ?> (<?= ($i - 1) * 7 + 1 ?>-<?= min($i * 7, 31) ?>)</td>
                            <td class="text-center"><?= $data["minggu_$i"] ?></td>
                            <td class="text-center"><?= $data["cek_bansos_minggu_$i"] ?></td>
                            <td class="text-center"><?= $data["usulan_baru_minggu_$i"] ?></td>
                            <td class="text-center"><?= $data["bbl_minggu_$i"] ?></td>
                            <td class="text-center"><?= $data["kelayakan_minggu_$i"] ?></td>
                            <td class="text-center"><?= $data["tugas_tambahan_minggu_$i"] ?></td>
                            <td class="text-center">-</td>
                        </tr>
                    <?php endfor; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>
    <p class="text-end" style="position: relative; right: 16px; top: 40px;">
        Tanjungbalai, <?= $tanggal_cetak ?>
    </p>
    <br>
    <div class="d-flex justify-content-between mb-5 text-dark mx-5">
        <div style="font-family: 'Times New Roman', Times, serif;">
            <span>Dibuat Oleh:</span>
            <br>
            <span>FASILITATOR</span>
            <br>

            
<!-- TAMPILAN TTD -->

        
        <div class="ttd-wrapper" style="position: relative; min-height: 150px;">
            <?php if (!empty($gbr_ttd)): ?>
                <!-- Gambar TTD -->
                <div class="mb-3">
                    <img 
                        src="uploads/ttd/<?= htmlspecialchars($gbr_ttd) ?>" 
                        width="125" 
                        height="125"
                        class="img-fluid ttd-image" 
                        alt="Tanda Tangan <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>"
                        onerror="this.src='https://via.placeholder.com/400x200/6c757d/ffffff?text=TTD+Tidak+Tersedia'">
                </div>
            <?php else: ?>
                <!-- Space kosong untuk menjaga layout -->
                <div style="height: 120px; width: 120px;"></div>
            <?php endif; ?>
        
            <span class="nama-fixed"><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></span>
            </div>
            <style>
                .ttd-wrapper {
                position: relative;
                min-height: 150px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .nama-fixed {
                position: absolute;
                bottom: 18px;
                left: 60%;
                transform: translateX(-50%);
                width: 120%;
            }
            </style>
        </div>
        <div style="font-family: 'Times New Roman', Times, serif; text-align: center;">
            <span>Diperiksa Oleh:</span>
            <br>
            <span>KA. SEKRETARIAT SLRT</span>
            <br>
            <img src="image/elvi-ttd.png" width="220" height="220" style="position:relative; top:-40px; z-index: 10;">
            <br>
            <span style="position: relative; top:-110px;">ELVI TRISNA, S.ST</span> <br>
            <span style="position: relative; top:-110px; left: 2px;">NIP. 19790124 200502 2 002</span>
        </div>
        <div style="font-family: 'Times New Roman', Times, serif; text-align: center;">
            <span style="position: relative; left: -38px;">Diketahui Oleh:</span>
            <br>
            <span>WAKIL. MANAJER SLRT</span>
            <br>
            <img src="image/andika.png" width="190" height="190" style="position:relative; top:-40px; z-index: 10;">
            <br>
            <span style="position: relative; top:-80px; left: 0px;">ANDIKA CAHYADI, M. Pd</span><br>
            <span style="position: relative; top:-80px; left: 2px;">NIP. 19780311 200502 1 004</span>
        </div>
    </div>
    <div class="mx-5" style="display: flex; justify-content: space-between; align-items: center; position: relative; top: 50px; font-size: 15px;">
        <div style="display: flex; align-items: center;">
            <img src="logo/slrt.png" style="width: 35px; height: 35px; margin-bottom:15px; margin-right: 10px;">
            <p class="text-dark" style="font-family: Verdana, Geneva, Tahoma, sans-serif;">Sistem Layanan Rujukan Terpadu</p>
        </div>
        <div>
            <span style="font-family: Verdana, Geneva, Tahoma, sans-serif; position:relative; right:0px; bottom:5px;">
                &copy; All Right Reserved 
            </span>
        </div>
    </div>
</section>















<!-- TABEL LAPORAN===================================================================== -->
<section class="mx-2" style="position: relative; top: 750px;">
    <div class="mx-2 bg-white">
        <?php 
            $no = 1;
            $tampil = mysqli_query($koneksi, "
                SELECT 
                    id, 
                    tanggal, 
                    nama_pengurus_bansos, 
                    alamat, 
                    pelayanan
                FROM 
                    registrasi 
                WHERE 
                    user_id = '$user_id' AND 
                    MONTH(tanggal) = $month AND 
                    YEAR(tanggal) = $year 
                ORDER BY 
                    id DESC
            ");

            if (mysqli_num_rows($tampil) > 0) {
        ?>
        <h5 class="text-center">BNBA PELAYANAN DI SEKRETARIAT SLRT</h5> <br>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered border">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>TANGGAL</th>
                        <th>NAMA</th>
                        <th>ALAMAT</th>
                        <th>JENIS PELAYANAN</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        while ($data = mysqli_fetch_array($tampil)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?= htmlspecialchars(date('d-m-Y', strtotime($data['tanggal'])), ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['nama_pengurus_bansos'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['alamat'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['pelayanan'], ENT_QUOTES); ?></td>
                        <td class="text-center">Selesai</td>
                    </tr>
                    <?php 
                        } 
                    ?>
                </tbody>
            </table>
        </div>
        <?php 
            } else {
                echo '<p class="text-center">Tidak ada data terinput untuk bulan ini.</p>';
            }
        ?>
    </div>
    <br>



        <div class="mx-2 bg-white">
        <?php 
            $no = 1;
            $tampil = mysqli_query($koneksi, "
                SELECT 
                    ub.id AS usulan_baru_id,
                    ub.tanggal,
                    kub.nama_klien,
                    kub.alamat_klien,
                    ub.jenis_layanan,
                    ub.keterangan_ub
                FROM 
                    usulan_baru ub
                JOIN 
                    klien_usulan_baru kub ON ub.klien_id = kub.id
                WHERE 
                    ub.user_id = '$user_id'  
                    AND MONTH(ub.tanggal) = '$month'  
                    AND YEAR(ub.tanggal) = '$year'
                ORDER BY 
                    ub.tanggal DESC
            ");
            
            if (mysqli_num_rows($tampil) > 0) {
        ?>
        <h5 class="text-center">PENDATAAN USULAN BARU</h5>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered border">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>TANGGAL</th>
                        <th>NAMA</th>
                        <th>ALAMAT</th>
                        <th>JENIS PELAYANAN</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        while ($data = mysqli_fetch_array($tampil)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?= htmlspecialchars(date('d-m-Y', strtotime($data['tanggal'])), ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['nama_klien'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['alamat_klien'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['jenis_layanan'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['keterangan_ub'], ENT_QUOTES); ?></td>
                    </tr>
                    <?php 
                        } 
                    ?>
                </tbody>
            </table>
        </div>
        <?php 
            } else {
                echo '<p class="text-center">Tidak ada data terinput untuk bulan ini.</p>';
            }
        ?>
    </div>




    <div class="mt-5 mx-2 bg-white">
        <?php 
            $no = 1;
            $tampil = mysqli_query($koneksi, "
                SELECT 
                    b.id AS bbl_id,
                    b.tanggal,
                    k.nama_klien,
                    k.alamat_klien,
                    b.jenis_layanan,
                    b.keterangan_bbl
                FROM 
                    bbl b
                JOIN 
                    klien_bbl k ON b.klien_id = k.id
                WHERE 
                    b.user_id = '$user_id'  
                    AND MONTH(b.tanggal) = $month  
                    AND YEAR(b.tanggal) = $year
                ORDER BY 
                    b.tanggal DESC
            ");
            
            if (mysqli_num_rows($tampil) > 0) {
        ?>

        <h5 class="text-center">BNBA PENDATAAN BBL</h5>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered border">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>TANGGAL</th>
                        <th>NAMA</th>
                        <th>ALAMAT</th>
                        <th>JENIS PELAYANAN</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        while ($data = mysqli_fetch_array($tampil)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?= htmlspecialchars(date('d-m-Y', strtotime($data['tanggal'])), ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['nama_klien'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['alamat_klien'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['jenis_layanan'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['keterangan_bbl'], ENT_QUOTES); ?></td>
                    </tr>
                    <?php 
                        } 
                    ?>
                </tbody>
            </table>
        </div>
        <?php 
            } else {
                echo '<p class="text-center"></p>';
            }
        ?>
    </div>
    <br>


    <div class="mt-5 mx-2 bg-white">
        <?php 
            $no = 1;
            $tampil = mysqli_query($koneksi, "
                SELECT 
                    tt.id AS kelayakan_id,
                    tt.tanggal,
                    ktt.nama_klien,
                    ktt.alamat_klien,
                    tt.jenis_layanan,
                    tt.keterangan_kelayakan
                FROM 
                    kelayakan tt
                JOIN 
                    klien_kelayakan ktt ON tt.klien_id = ktt.id
                WHERE 
                    tt.user_id = '$user_id'  
                    AND MONTH(tt.tanggal) = $month  
                    AND YEAR(tt.tanggal) = $year  
                ORDER BY 
                    tt.tanggal DESC
            ");
            
            if (mysqli_num_rows($tampil) > 0) {
        ?>
        <h5 class="text-center">BNBA KELAYAKAN</h5>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered border">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>TANGGAL</th>
                        <th>NAMA</th>
                        <th>ALAMAT</th>
                        <th>JENIS PELAYANAN</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        while ($data = mysqli_fetch_array($tampil)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?= htmlspecialchars(date('d-m-Y', strtotime($data['tanggal'])), ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['nama_klien'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['alamat_klien'], ENT_QUOTES ); ?></td>
                        <td><?= htmlspecialchars($data['jenis_layanan'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['keterangan_kelayakan'], ENT_QUOTES); ?></td>
                    </tr>
                    <?php 
                        } 
                    ?>
                </tbody>
            </table>
        </div>
        <?php 
            } else {
                echo '<p class="text-center"></p>';
            }
        ?>
    </div>

    <br>
</section>







<script>
    if (new URLSearchParams(window.location.search).get('print') === '1') {
        if (document.readyState === 'complete') {
            window.print();
        } else {
            window.addEventListener('load', () => window.print(), { once: true });
        }
    }
</script>

<script src="assets/dist/js/pages/dashboard2.js"></script>
<script src="assets/assets/js/main.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>