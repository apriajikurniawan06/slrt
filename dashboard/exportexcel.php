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



// Mengatur header untuk file Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=DATA PENGAJUAN BANSOS.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Mengambil tanggal dari form
$tanggal1 = $_POST['tanggala'];
$tanggal2 = $_POST['tanggalb'];

// Query untuk mengambil data berdasarkan rentang tanggal
$tampil = mysqli_query($koneksi, "SELECT * FROM registrasi WHERE tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY id DESC");
?>

<meta name="theme-color" content="#f8fafa">
    <link rel="manifest" href="manifest.json">


<table border="1" align="center">
    <thead>
        <tr>
            <th colspan="10">DATA PENGAJUAN BANSOS</th>
        </tr>
        <tr>
            <th>NO</th>
            <th>TANGGAL</th>
            <th>NOMOR KK</th>
            <th>PROVINSI</th>
            <th>KOTA</th>
            <th>KECAMATAN</th>
            <th>KELURAHAN</th>
            <th>ALAMAT</th>
            <th>KODE POS</th>
            <th>NAMA PENGURUS BANSOS</th>
            <th>NIK KEPALA KELUARGA</th>
            <th>TANGGAL LAHIR</th>
            <th>TEMPAT LAHIR</th>
            <th>JENIS KELAMIN</th>
            <th>AGAMA</th>
            <th>PENDIDIKAN</th>
            <th>PEKERJAAN</th>
            <th>FC KTP</th>
            <th>FC KK</th>
            <th>SKTM</th>
            <th>STATUS RUMAH</th>
            <th>MCK</th>
            <th>PENERANGAN</th>
            <th>STATUS DTKS</th>
            <th>NAMA IBU KANDUNG</th>
            <th>BANSOS DIPEROLEH</th>
            <th>PENGADUAN</th>
            <th>PELAYANAN</th>
            <th>PENGHASILAN</th>
            <th>NOMOR TLP</th>
            <th>NAMA FASILITATOR</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($data = mysqli_fetch_array($tampil)) {
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($data['tanggal']); ?></td>
                <td><?= htmlspecialchars($data['nomor_kk']); ?></td>
                <td><?= htmlspecialchars($data['provinsi']); ?></td>
                <td><?= htmlspecialchars($data['kota']); ?></td>
                <td><?= htmlspecialchars($data['kecamatan']); ?></td>
                <td><?= htmlspecialchars($data['kelurahan']); ?></td>
                <td><?= htmlspecialchars($data['alamat']); ?></td>
                <td><?= htmlspecialchars($data['kode_pos']); ?></td>
                <td><?= htmlspecialchars($data['nama_pengurus_bansos']); ?></td>
                <td><?= htmlspecialchars($data['nik_kepala_keluarga']); ?></td>
                <td><?= htmlspecialchars($data['tgl_lahir']); ?></td>
                <td><?= htmlspecialchars($data['tpt_lahir']); ?></td>
                <td><?= htmlspecialchars($data['jenis_k']); ?></td>
                <td><?= htmlspecialchars($data['agama_kepala_keluarga']); ?></td>
                <td><?= htmlspecialchars($data['pendidikan_kepala']); ?></td>
                <td><?= htmlspecialchars($data['pekerjaan']); ?></td>
                <td><?= htmlspecialchars($data['fc_ktp']); ?></td>
                <td><?= htmlspecialchars($data['fc_kk']); ?></td>
                <td><?= htmlspecialchars($data['sktm']); ?></td>
                <td><?= htmlspecialchars($data['status_rumah']); ?></td>
                <td><?= htmlspecialchars($data['mck']); ?></td>
                <td><?= htmlspecialchars($data['penerangan']); ?></td>
                <td><?= htmlspecialchars($data['status_dtks']); ?></td>
                <td><?= htmlspecialchars($data['nama_ibu_kandung']); ?></td>
                <td><?= htmlspecialchars($data['bansos_diperoleh']); ?></td>
                <td><?= htmlspecialchars($data['pengaduan']); ?></td>
                <td><?= htmlspecialchars($data['pelayanan']); ?></td>
                <td><?= htmlspecialchars($data['penghasilan']); ?></td>
                <td><?= htmlspecialchars($data['nomor_tlp']); ?></td>
                <td><?= htmlspecialchars($data['nama_fasilitator']); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
