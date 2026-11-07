<?php

session_start(); // Pastikan session dimulai

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

// Cek apakah user_id ada dalam sesi
if (!isset($_SESSION['id']) || empty($_SESSION['nama_lengkap']) || empty($_SESSION['klrhn']) || empty($_SESSION['kcmtn'])) {
    echo "<script>alert('Maaf, Anda harus login terlebih dahulu.');</script>";
    header("Location: fs_dashboard.php?page=menu");
    exit();
}


// Jika ada, ambil user_id
$user_id = $_SESSION['id'];



$selected_month = isset($_SESSION['selected_month']) ? $_SESSION['selected_month'] : date('Y-m'); // Default ke bulan saat ini
list($year, $month) = explode('-', $selected_month);

// Inisialisasi data dengan default 0
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

// Fungsi untuk menjalankan query dan mengisi data
function runQuery($koneksi, $query, $data) {
    $result = mysqli_query($koneksi, $query);
    if ($result) {
        $data_query = mysqli_fetch_assoc($result);
        foreach ($data_query as $key => $value) {
            $data[$key] += $value;
        }
    }
    return $data;
}

// Query untuk CEK BANSOS
$query_cek_bansos = "
    SELECT 
        SUM(CASE WHEN DAY(tanggal) BETWEEN 1 AND 7 THEN 1 ELSE 0 END) AS minggu_1,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 8 AND 14 THEN 1 ELSE 0 END) AS minggu_2,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 15 AND 21 THEN 1 ELSE 0 END) AS minggu_3,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 22 AND 28 THEN 1 ELSE 0 END) AS minggu_4,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 29 AND 31 THEN 1 ELSE 0 END) AS minggu_5,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 1 AND 7 AND pelayanan = 'CEK BANSOS' THEN 1 ELSE 0 END) AS cek_bansos_minggu_1,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 8 AND 14 AND pelayanan = 'CEK BANSOS' THEN 1 ELSE 0 END) AS cek_bansos_minggu_2,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 15 AND 21 AND pelayanan = 'CEK BANSOS' THEN 1 ELSE 0 END) AS cek_bansos_minggu_3,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 22 AND 28 AND pelayanan = 'CEK BANSOS' THEN 1 ELSE 0 END) AS cek_bansos_minggu_4,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 29 AND 31 AND pelayanan = 'CEK BANSOS' THEN 1 ELSE 0 END) AS cek_bansos_minggu_5
    FROM 
        registrasi
    WHERE 
        MONTH(tanggal) = $month 
        AND YEAR(tanggal) = $year
        AND user_id = '$user_id'
";

$data = runQuery($koneksi, $query_cek_bansos, $data);

// Query untuk USULAN BARU
$query_usulan_baru = "
    SELECT 
        SUM(CASE WHEN DAY(tanggal) BETWEEN 1 AND 7 THEN 1 ELSE 0 END) AS usulan_baru_minggu_1,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 8 AND 14 THEN 1 ELSE 0 END) AS usulan_baru_minggu_2,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 15 AND 21 THEN 1 ELSE 0 END) AS usulan_baru_minggu_3,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 22 AND 28 THEN 1 ELSE 0 END) AS usulan_baru_minggu_4,
        SUM(CASE WHEN DAY(tanggal) BETWEEN 29 AND 31 THEN 1 ELSE 0 END) AS usulan_baru_minggu_5
    FROM 
        usulan_baru
    WHERE 
        MONTH(tanggal) = $month 
        AND YEAR(tanggal) = $year
        AND user_id = '$user_id'
";

$data = runQuery($koneksi, $query_usulan_baru, $data);

// Query untuk BBL
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

$data = runQuery($koneksi, $query_bbl, $data);

// Query untuk KELAYAKAN
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

$data = runQuery($koneksi, $query_kelayakan, $data);

// Query untuk TUGAS TAMBAHAN
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

$data = runQuery($koneksi, $query_tugas_tambahan, $data);

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



<style>
    @media print {
    /* Menyembunyikan alamat URL yang muncul di header atau footer */
    @page {
        margin: 0; /* Menghapus margin default */
    }
    /* table {
        page-break-before: always;
    }

    table {
        page-break-after: always;
    } */

    /* Menyembunyikan elemen-elemen tertentu yang mungkin menampilkan URL */
    body::after {
        display: none;
    }

    /* Jika ada elemen lain yang menampilkan URL, Anda bisa menyembunyikannya juga */
    .print-hide {
        display: none;
    }
}
</style>


<section class="rotasi mx-1 text-dark" style="position:relative; top:20px; font-family: 'Times New Roman', Times, serif;">
    <div class="d-flex justify-content-center align-items-center">
        <div class="text-center mt-2">
            <h1 class="card-title bold">LAPORAN KEGIATAN FASILITATOR</h1>
        </div>
    </div> <br>
    <div class="table-responsive">
        <table class="mx-4">
        <?php
            $bulanIndonesia = [
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

            $month = date('n'); // Mengambil bulan saat ini sebagai angka (1-12)
            $year = date('Y'); // Mengambil tahun saat ini
            $namaBulan = strtoupper($bulanIndonesia[$month]); // Mengubah nama bulan menjadi uppercase

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
            <div class="mx-1">
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-responsive-lg">
                        <thead>
                            <tr>
                                <th rowspan="2">NO</th>
                                <th rowspan="2">JADWAL</th>
                                <th rowspan="2">JUMLAH LAYANAN</th>
                                <th colspan="5" class="text-center">PENDATAAN</th>
                                <th rowspan="2">KETERANGAN</th>
                                <th rowspan="2">TANTANGAN</th>
                            </tr>
                            <tr>
                                <th>CEK BANSOS</th>
                                <th>USULAN BARU</th>
                                <th>BBL</th>
                                <th>KELAYAKAN</th>
                                <th>TUGAS LAIN</th>
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
                                <td class="text-center">Selesai</td>
                                <td class="text-center">-</td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="d-flex justify-content-between mb-5 text-dark mx-5">
        <div style="font-family: 'Times New Roman', Times, serif;">
            <span>Dibuat Oleh:</span>
            <br>
            <span>FASILITATOR</span>
            <br><br>
            <span style="position: relative; top:70px;"><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></span>
        </div>
        <div style="font-family: 'Times New Roman', Times, serif; text-align: center;">
            <span>Diperiksa Oleh:</span>
            <br>
            <span>KOORDINATOR SLRT</span>
            <br><br>
            <span style="position: relative; top:70px;">MUHAMMAD TAUFIK, SE</span>
        </div>
        <div style="font-family: 'Times New Roman', Times, serif; text-align: center;">
            <span style="position: relative; left: -38px;">Diketahui Oleh:</span>
            <br>
            <span>KA. SEKRETARIAT SLRT</span>
            <br><br>
            <span style="position: relative; top:70px; left: -24px;">ELVI TRISNA, S.ST</span><br>
            <span style="position: relative; top:70px; left: 2px;">NIP. 19790124 200502 2 002</span>
        </div>
    </div>
    <div class="mx-5" style="display: flex; justify-content: space-between; align-items: center; position: relative; top: 50px; font-size: 15px;">
        <div style="display: flex; align-items: center;">
            <img src="logo/slrt.png" style="width: 35px; height: 35px; margin-bottom:15px; margin-right: 10px;">
            <p class="text-dark" style="font-family: Verdana, Geneva, Tahoma, sans-serif;">Sistem Layanan Rujukan Terpadu</p>
        </div>
        <div>
            <span style="font-family: Verdana, Geneva, Tahoma, sans-serif; position:relative; right:0px; bottom:5px;">
                &copy; All Right Reserved <?= date('d-m-Y') ?>
            </span>
        </div>
    </div>
</section>


    <div class="mx-2 bg-white" style="position: relative; top:750px;">
        <h5 class="text-center">BNBA PELAYANAN DI SEKRETARIAT SLRT</h5> <br>
        <div class="table-responsive">
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
                        <td><?= htmlspecialchars($data['tanggal'], ENT_QUOTES); ?></td>
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
            <?php 
                } else {
                    echo '<p class="text-center">Tidak ada data terinput untuk bulan ini.</p>';
                }
            ?>
        </div>
    </div>
    <br>
    <br>

   



    <div class="mt-5 mx-2 bg-white" style="position: relative; top:1800px;">
        <h5 class="text-center">BNBA PENDATAAN BBL</h5>
        <div class="table-responsive">
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
                        b.tanggal DESC");
                
                if (mysqli_num_rows($tampil) > 0) {
            ?>
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
                        <td><?= htmlspecialchars($data['tanggal'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['nama_klien'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['alamat_klien'], ENT_QUOTES ); ?></td>
                        <td><?= htmlspecialchars($data['jenis_layanan'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['keterangan_bbl'], ENT_QUOTES); ?></td>
                    </tr>
                    <?php 
                        } 
                    ?>
                </tbody>
            </table>
            <?php 
                } else {
                    echo '<p class="text-center">Tidak ada data terinput untuk bulan ini.</p>';
                }
            ?>
        </div>
    </div>
    <br>



    <div class="mt-5 mx-2 bg-white" style="position: relative; top:3000px;">
        <h5 class="text-center">BNBA KELAYAKAN</h5>
        <div class="table-responsive">
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
                        <td><?= htmlspecialchars($data['tanggal'], ENT_QUOTES); ?></td>
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
            <?php 
                } else {
                    echo '<p class="text-center">Tidak ada data terinput untuk bulan ini.</p>';
                }
            ?>
        </div>
    </div>




    <div class="mt-5 mx-2 bg-white" style="position: relative; top:4330px;">
        <h5 class="text-center">BNBA TUGAS TAMBAHAN</h5> <br>
        <div class="table-responsive">
            <?php 
                $no = 1;
                $tampil = mysqli_query($koneksi, "
                    SELECT 
                        tt.id AS tugas_tambahan_id,
                        tt.tanggal,
                        ktt.nama_klien,
                        ktt.alamat_klien,
                        tt.jenis_layanan,
                        tt.keterangan_tt
                    FROM 
                        tugas_tambahan tt
                    JOIN 
                        klien_tugas_tambahan ktt ON tt.klien_id = ktt.id
                    WHERE 
                        tt.user_id = '$user_id'  
                        AND MONTH(tt.tanggal) = $month  
                        AND YEAR(tt.tanggal) = $year  
                    ORDER BY 
                        tt.tanggal DESC
                ");
                
                if (mysqli_num_rows($tampil) > 0) {
            ?>
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
                        <td><?= htmlspecialchars($data['tanggal'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['nama_klien'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['alamat_klien'], ENT_QUOTES ); ?></td>
                        <td><?= htmlspecialchars($data['jenis_layanan'], ENT_QUOTES); ?></td>
                        <td><?= htmlspecialchars($data['keterangan_tt'], ENT_QUOTES); ?></td>
                    </tr>
                    <?php 
                        } 
                    ?>
                </tbody>
            </table>
            <?php 
                } else {
                    echo '<p class="text-center">Tidak ada data terinput untuk bulan ini.</p>';
                }
            ?>
        </div>
    </div>
    <br>
    <br>



    <div class="mx-2 bg-white" style="position: relative; top:5600px;">
            <h5 class="text-center">PENDATAAN USULAN BARU</h5> <br>
            <div class="table-responsive">
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
                            <td><?= htmlspecialchars($data['tanggal'], ENT_QUOTES); ?></td>
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
                <?php 
                    } else {
                        echo '<p class="text-center">Tidak ada data terinput untuk bulan ini.</p>';
                    }
                ?>
        </div>
    </div>
    <br>
    <br>





<script>
        window.onload = function() {
            // Mencetak halaman
            window.print();

            // Membuat PDF setelah mencetak
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Menambahkan konten ke PDF
            doc.text(document.getElementById('content').innerText, 10, 10);
            
            // Menyimpan PDF dengan nama file yang diinginkan
            const currentDate = new Date();
            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            const month = monthNames[currentDate.getMonth()];
            const fileName = `Laporan Fasilitator ${month} ${currentDate.getFullYear()}.pdf`; // Nama file dengan format yang diinginkan
            doc.save(fileName);
        };

        window.onafterprint = function() {
            window.location.href = 'fs_dashboard.php'; // Ganti dengan URL halaman dashboard Anda
        };
    </script>

<script src="assets/dist/js/pages/dashboard2.js"></script>
<script src="assets/assets/js/main.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>




<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
      "searching": true,
    //   "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
