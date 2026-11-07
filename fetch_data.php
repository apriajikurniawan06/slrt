<?php
include 'koneksi.php';

$tgl_awal = $_POST['tgl_awal'] ?? $_GET['tgl_awal'] ?? '';
$tgl_akhir = $_POST['tgl_akhir'] ?? $_GET['tgl_akhir'] ?? '';
$search = trim($_POST['search'] ?? $_GET['search'] ?? '');

    // Query Dasar
    $query = "SELECT 
        tanggal, nik_klien, nama_klien, alamat_klien, jenis_layanan, nama_fasilitator
        FROM (
            SELECT 
                bbl.tanggal, klien_bbl.nik_klien, klien_bbl.nama_klien, klien_bbl.alamat_klien, 
                bbl.jenis_layanan, '' AS nama_fasilitator
            FROM bbl
            JOIN klien_bbl ON bbl.klien_id = klien_bbl.id

            UNION ALL

            SELECT 
                usulan_baru.tanggal, klien_usulan_baru.nik_klien, klien_usulan_baru.nama_klien, 
                klien_usulan_baru.alamat_klien, usulan_baru.jenis_layanan, '' AS nama_fasilitator
            FROM usulan_baru
            JOIN klien_usulan_baru ON usulan_baru.klien_id = klien_usulan_baru.id

            UNION ALL

            SELECT 
                kelayakan.tanggal, klien_kelayakan.nik_klien, klien_kelayakan.nama_klien, 
                klien_kelayakan.alamat_klien, kelayakan.jenis_layanan, '' AS nama_fasilitator
            FROM kelayakan
            JOIN klien_kelayakan ON kelayakan.klien_id = klien_kelayakan.id

            UNION ALL

            SELECT 
                r.tanggal, r.nik_kepala_keluarga, r.nama_pengurus_bansos, r.alamat, 
                r.pelayanan, COALESCE(r.nama_fasilitator, '') AS nama_fasilitator
            FROM registrasi r
        ) AS combined WHERE 1=1";

    $params = [];
    $types = "";

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_awal) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_akhir)) {
        $query .= " AND tanggal >= ? AND tanggal < DATE_ADD(?, INTERVAL 1 DAY)";
        $params[] = $tgl_awal;
        $params[] = $tgl_akhir;
        $types .= "ss";
    }

    if (!empty($search)) {
        $query .= " AND (tanggal LIKE ? OR nik_klien LIKE ? OR nama_klien LIKE ? OR alamat_klien LIKE ? OR jenis_layanan LIKE ? OR nama_fasilitator LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $types .= "ssssss";
    }

    $query .= " ORDER BY tanggal ASC";

    // Prepared Statements
    $stmt = mysqli_prepare($koneksi, $query);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if (!mysqli_stmt_execute($stmt)) {
        http_response_code(500);
        exit('Gagal mengambil data: ' . htmlspecialchars(mysqli_stmt_error($stmt), ENT_QUOTES, 'UTF-8'));
    }
    $result = mysqli_stmt_get_result($stmt);

    $no = 1;
    while ($data = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td class='text-center'>" . $no . "</td>
            <td class='text-center'>" . htmlspecialchars($data['tanggal'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($data['nik_klien'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($data['nama_klien'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($data['alamat_klien'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($data['jenis_layanan'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($data['nama_fasilitator'], ENT_QUOTES, 'UTF-8') . "</td>
            </tr>";
        $no++;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
?>
