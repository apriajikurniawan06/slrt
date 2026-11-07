<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'slrt');

// Check koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mendapatkan tanggal saat ini
$currentDay = date('j');
$currentMonth = date('n');
$currentYear = date('Y');

// Jika tanggal saat ini adalah tanggal 1, maka update status default
if ($currentDay == 1) {
    // Query untuk update status default
    $sql = "UPDATE laporan SET 
            fasilitator = 'Diproses', 
            Approve_Operator = 'Belum Disetujui', 
            Approve_Koordinator = 'Belum Disetujui', 
            Approve_Sekretaris = 'Belum Disetujui' 
            WHERE Bulan = ? AND Tahun = ?";
    
    // Bulan sebelumnya
    $previousMonth = $currentMonth - 1;
    if ($previousMonth == 0) {
        $previousMonth = 12;
        $currentYear -= 1;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $previousMonth, $currentYear);

    if ($stmt->execute()) {
        echo "<script>console.log('Status default berhasil diupdate untuk bulan sebelumnya.');</script>";
    } else {
        echo "<script>console.log('Gagal mengupdate status default: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Update Approve approval berdasarkan level
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'fasilitator') {
        $sql = "UPDATE laporan SET fasilitator = 'Selesai' WHERE id = ? AND fasilitator = 'Diproses'";
    } elseif ($action === 'Operator') {
        $sql = "UPDATE laporan SET Approve_Operator = 'Disetujui' WHERE id = ? AND fasilitator = 'Selesai'";
    } elseif ($action === 'Koordinator') {
        $sql = "UPDATE laporan SET Approve_Koordinator = 'Disetujui' WHERE id = ? AND Approve_Operator = 'Disetujui'";
    } elseif ($action === 'Sekretaris') {
        $sql = "UPDATE laporan SET Approve_Sekretaris = 'Disetujui' WHERE id = ? AND Approve_Koordinator = 'Disetujui'";
    } else {
        die("Aksi tidak valid");
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    //NOTIFIKASI APPROVE
    if ($stmt->execute()) {
        echo "<script>alert('Persetujuan berhasil diperbarui!'); window.location.href='fs_laporan_persetujuan.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui persetujuan: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
}

// Jika tombol print diklik, kembalikan seprti biasa
if (isset($_GET['print']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Query reset status
    $sql = "UPDATE laporan SET 
            fasilitator = 'Diproses', 
            Approve_Operator = 'Belum Disetujui', 
            Approve_Koordinator = 'Belum Disetujui', 
            Approve_Sekretaris = 'Belum Disetujui' 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['isPrinted'] = true;
        echo "<script>console.log('Status berhasil direset sebelum cetak.');</script>";
        echo "<script>window.location.href='fs_cetak.php?id={$id}';</script>";
        echo "<script>setTimeout(function(){ window.location.reload(); }, 1000);</script>";
    } else {
        echo "<script>alert('Gagal mereset status sebelum cetak.');</script>";
    }

    $stmt->close();
    header("Location: fs_cetak.php?id={$id}"); 
    exit();
}

if (!isset($_SESSION['id'])) {
    header("Location: fs_dashboard.php?page=fs_laporan_persetujuan");
    exit();
}

// Jika ada, ambil user_id
$user_id = $_SESSION['id'];

if (empty($_SESSION['nama_lengkap'])) {
    echo "<script>alert('Maaf, User tidak terkoneksi.');</script>";
    header("Location: menu.php");
    exit();
}

// Ambil data untuk ditampilkan
$sql = "SELECT * FROM laporan WHERE user_id = ? AND (Tahun = ? OR Tahun = ?) ORDER BY Tahun, Bulan";
$stmt = $conn->prepare($sql);
$prevYear = $currentYear - 1;
$stmt->bind_param("iii", $user_id, $currentYear, $prevYear);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah tombol print sudah ditekan
if (!isset($_SESSION['isPrinted'])) {
    $_SESSION['isPrinted'] = false; // Inisialisasi status print
}

// Mengatur zona waktu ke Indonesia Barat
date_default_timezone_set('Asia/Jakarta'); // Ganti dengan zona waktu yang sesuai jika diperlukan

// Mendapatkan tanggal dan waktu saat ini
$tanggal_sekarang = date('d-m-Y');
$jam_sekarang = date('H:i:s');

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





<!-- Main content -->
<div class="mx-2 bg-white">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center" style="position: relative; top: -20px;">
                <!-- Judul di sebelah kiri -->
                <div class="card-body">
                    <h5 class="text-primary bold mb-0">PERSETUJUAN LAPORAN</h5>
                </div>

                <!-- Tanggal dan jam di sebelah kanan -->
                <div class="card-body text-right">
                    [<span class="text-danger" id="tanggal"><?= $tanggal_sekarang ?></span>]
                    [<span class="text-danger" id="jam"><?= $jam_sekarang ?></span>]
                </div>
            </div>

            <div class="table-responsive mx-2">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA LENGKAP</th>
                            <th>JENIS KELAMIN</th>
                            <th>KELURAHAN</th>
                            <th>BULAN</th>
                            <th>TAHUN</th>
                            <th>FASILITATOR</th>
                            <th>OPERATOR</th>
                            <th>KOORDINATOR</th>
                            <th>SEKRETARIS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $currentMonth = date('n'); // Bulan saat ini
                        $bulan = array(
                            'January' => 'Januari',
                            'February' => 'Februari',
                            'March' => 'Maret',
                            'April' => 'April',
                            'May' => 'Mei',
                            'June' => 'Juni',
                            'July' => 'Juli',
                            'August' => 'Agustus',
                            'September' => 'September',
                            'October' => 'Oktober',
                            'November' => 'November',
                            'December' => 'Desember'
                        );

                        // Cek apakah tombol print sudah ditekan
                        $isPrinted = false; // Ganti ini dengan logika untuk mengecek apakah tombol print sudah ditekan

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>{$no}</td>";
                                echo "<td>{$row['nama_lengkap']}</td>";
                                echo "<td>{$row['j_k']}</td>";
                                echo "<td>{$row['klrhn']}</td>";

                                // Tampilkan bulan sesuai status
                                if ($isPrinted) {
                                    // Jika sudah ditekan, ganti bulan
                                    $nextMonth = ($currentMonth % 12) + 1; // Ganti ke bulan berikutnya
                                    echo "<td>{$bulan[date('F', mktime(0, 0, 0, $nextMonth, 1))]}</td>";
                                } else {
                                    // Jika belum ditekan, tetap tampilkan bulan saat ini
                                    echo "<td>{$bulan[date('F')]}</td>";
                                }

                                echo "<td>" . date('Y') . "</td>";

                                // Kolom Fasilitator
                                echo "<td>";     
                                $backgroundColor = 'transparent'; 
                                if ($row['fasilitator'] === 'Selesai') {
                                    $backgroundColor = 'green'; // Color for 'Selesai'
                                } elseif ($row['fasilitator'] === 'Diproses') {
                                    $backgroundColor = 'red'; // Color for 'Diproses'
                                } else {
                                    $backgroundColor = 'yellow'; // Color for any other status
                                }
                                echo "<div style='background-color: {$backgroundColor}; padding: 0; width:120px; border-radius: 20px; color: white; text-align: center;'>";
                                echo "<span>{$row['fasilitator']}</span><br>";
                                echo "</div>";

                                // Kolom Operator
                                $approveOperator = $row['Approve_Operator'];
                                $backgroundColor = ($approveOperator == 'Disetujui') ? 'green' : (($approveOperator == 'Belum Disetujui') ? 'red' : 'transparent');
                                echo "<td><span style='background-color: {$backgroundColor}; color: white; border-radius: 20px; width: 120px; display: flex; align-items: center; justify-content: center; text-align: center;'>{$approveOperator}</span></td>";

                                // Kolom Koordinator
                                $approveKoordinator = $row['Approve_Koordinator'];
                                $backgroundColor = ($approveKoordinator == 'Disetujui') ? 'green' : (($approveKoordinator == 'Belum Disetujui') ? 'red' : 'transparent');
                                echo "<td><span style='background-color: {$backgroundColor}; color: white; border-radius: 20px; width: 120px; display: flex; align-items: center; justify-content: center; text-align: center;'>{$approveKoordinator}</span></td>";

                                // Kolom Sekretaris
                                $approveSekretaris = $row['Approve_Sekretaris'];
                                $backgroundColor = ($approveSekretaris == 'Disetujui') ? 'green' : (($approveSekretaris == 'Belum Disetujui') ? 'red' : 'transparent');
                                echo "<td><span style='background-color: {$backgroundColor}; color: white; border-radius: 20px; width: 120px; display: flex; align-items: center; justify-content: center; text-align: center;'>{$approveSekretaris}</span></td>";

                                // Kolom ACTION
                                echo "<td>";
                                $currentDay = date('j');
                                $currentMonth = date('n');

                                if ($row['fasilitator'] === 'Selesai' && 
                                    (($currentMonth == 2 && $currentDay > 28) || // Februari
                                    ($currentMonth == 4 || $currentMonth == 6 || $currentMonth == 9 || $currentMonth == 11) && $currentDay > 30 || // Bulan dengan 30 hari
                                    ($currentMonth == 1 || $currentMonth == 3 || $currentMonth == 5 || $currentMonth == 7 || $currentMonth == 8 || $currentMonth == 10 || $currentMonth == 12) && $currentDay > 31)) { // Bulan dengan 31 hari
                                    echo "<div style='margin-top: 5px;'>";
                                    echo "<a href='fs_laporan_persetujuan.php?action=fasilitator&id={$row['id']}' class='btn btn-sm btn-success' onclick='return confirmApproval(this);'>Diproses</a>";
                                    echo "</div>";
                                }
                                
                                // if ($row['fasilitator'] === 'Diproses' && date('j') <= 31 && date('n') == $currentMonth) {
                                //     echo "<div style='margin-top: 5px;'>";
                                //     echo "<a href='fs_laporan_persetujuan.php?action=fasilitator&id={$row['id']}' class='btn btn-sm btn-success' onclick='return confirmApproval(this);'>Selesai</a>";
                                //     echo "</div>";
                                // }

                                if (isset($row['fasilitator']) && $row['fasilitator'] === 'Diproses' && isset($currentMonth) && date('n') == $currentMonth) {
                                    echo "<div style='margin-top: 5px;'>";
                                    echo "<a href='fs_laporan_persetujuan.php?action=fasilitator&id={$row['id']}' class='btn btn-sm btn-success' onclick='return confirmApproval(this);'>Selesai</a>";
                                    echo "</div>";
                                }
                                
                                if ($row['Approve_Sekretaris'] === 'Disetujui' && $row['fasilitator'] === 'Selesai') {
                                    echo "<a onclick='printAndChangeMonth({$row['id']})'> <ion-icon name='print-sharp' class='btn btn-primary text-white' style='font-size:25px;'></ion-icon></a>";
                                }
                                echo "</td>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='11' class='text-center'>Data tidak ada.</td></tr>";
                        }
                        ?>
                    </tbody>
                    <script>
                            function printAndChangeMonth(id) {
                                let url = `fs_cetak.php?print=true&id=${encodeURIComponent(id)}`;
                                window.open(url, '_blank'); // Buka halaman cetak di tab baru
                            }
                    </script>

                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan SweetAlert2 (Jika belum ada) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmApproval(link) {
    Swal.fire({
        title: "Konfirmasi",
        text: "Apakah Anda yakin laporan telah selesai?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Selesai!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Sukses!",
                text: "Selamat, laporan selesai!",
                icon: "success"
            }).then(() => {
                window.location.href = link.href; // Arahkan ke link jika dikonfirmasi
            });
        }
    });

    return false; // Mencegah navigasi langsung sebelum konfirmasi
}
</script>




<script>
    // Fungsi untuk memperbarui tanggal dan jam secara real-time
    function updateWaktu() {
        const sekarang = new Date();
        const options = { timeZone: 'Asia/Jakarta' };

        // Format tanggal: DD-MM-YYYY
        const tanggal = sekarang.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            timeZone: 'Asia/Jakarta'
        });

        // Format jam: HH:MM:SS
        const jam = sekarang.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            timeZone: 'Asia/Jakarta'
        });

        // Update elemen HTML
        document.getElementById('tanggal').textContent = tanggal;
        document.getElementById('jam').textContent = jam;
    }

    // Perbarui waktu setiap detik
    setInterval(updateWaktu, 1000);

    // Jalankan fungsi pertama kali saat halaman dimuat
    updateWaktu();
</script>
