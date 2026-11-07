<?php

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'slrt');

// Check koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$currentDay = date('j'); // 'j' mengembalikan hari dalam bulan tanpa angka nol di depan
$currentMonth = date('n'); // 'n' mengembalikan bulan tanpa angka nol di depan
$currentYear = date('Y'); // 'Y' mengembalikan tahun penuh


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

    if ($stmt->execute()) {
        echo "<script>alert('Persetujuan berhasil diperbarui!'); window.location.href='laporan_approv_kd.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui persetujuan: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
}

if (!isset($_SESSION['id'])) {
    header("Location: kd_dashboard.php?page=laporan_approv_kd");
    exit();
}

// Jika ada, ambil user_id
$user_id = $_SESSION['id'];

if (empty($_SESSION['nama_lengkap'])) {
    echo "<script>alert('Maaf, User tidak terkoneksi.');</script>";
    header("Location: menu.php");
    exit();
}

// Mendapatkan tahun saat ini
$currentYear = date('Y'); // Tahun saat ini (4 digit)

// Ambil data untuk ditampilkan berdasarkan user_id dan tahun
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM laporan WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
} else {
    $sql = "SELECT * FROM laporan ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();


if (!isset($_SESSION['isPrinted'])) {
    $_SESSION['isPrinted'] = false; // Inisialisasi status print
}
?>

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

<!-- Main content -->
<div class="mx-2 bg-white">
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="">
                    <h4 class="card-title text-center">APPROVE LAPORAN</h4>
                </div>
                <div class="table-responsive">
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
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>{$no}</td>";
                                    echo "<td>{$row['nama_lengkap']}</td>";
                                    echo "<td>{$row['j_k']}</td>";
                                    echo "<td>{$row['klrhn']}</td>";

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

                                    echo "<td>{$bulan[date('F')]}</td>";
                                    echo "<td class='current-year'>" . date('Y') . "</td>";

                                    // Kolom fasilitator
                                    $fasilitator = $row['fasilitator'];
                                    $backgroundColor = ($fasilitator == 'Selesai') ? 'green' : (($fasilitator == 'Diproses') ? 'red' : 'transparent');
                                    echo "<td><span style='background-color: {$backgroundColor}; color: white; border-radius: 20px; width: 80px; display: flex; align-items: center; justify-content: center; text-align: center;'>{$fasilitator}</span></td>";

                                    // Kolom Operator
                                    $approveOperator = $row['Approve_Operator'];
                                    $backgroundColor = ($approveOperator == 'Disetujui') ? 'green' : (($approveOperator == 'Belum Disetujui') ? 'red' : 'transparent');
                                    echo "<td><span style='background-color: {$backgroundColor}; color: white; border-radius: 20px; width: 120px; display: flex; align-items: center; justify-content: center; text-align: center;'>{$approveOperator}</span></td>";

                                    // Kolom Koordinator
                                    echo "<td>";
                                    $backgroundColor = ($row['Approve_Koordinator'] === 'Disetujui') ? 'background-color: green; color: white;' : 'background-color: red;';
                                    echo "<span style='{$backgroundColor} border-radius: 20px; color:white; width: 120px; display: inline-block; text-align: center;'>{$row['Approve_Koordinator']}</span><br>";

                                    

                                    // Kolom Sekretaris
                                    $approveSekretaris = $row['Approve_Sekretaris'];
                                    $backgroundColor = ($approveSekretaris == 'Disetujui') ? 'green' : (($approveSekretaris == 'Belum Disetujui') ? 'red' : 'transparent');
                                    echo "<td><span style='background-color: {$backgroundColor}; color: white; border-radius: 20px; width: 120px; display: flex; align-items: center; justify-content: center; text-align: center;'>{$approveSekretaris}</span></td>";


                                    // Kolom ACTION
                                    echo "<td>";
                                    // Tambahkan logika untuk menampilkan tombol "Disetujui" hanya jika operator disetujui
                                    if ($row['Approve_Operator'] === 'Disetujui' && $row['fasilitator'] === 'Selesai' && $row['Approve_Koordinator'] !== 'Disetujui') {
                                        echo "<div style='margin-top: 5px;'><a href='laporan_approv_kd.php?action=Koordinator&id={$row['id']}' class='btn btn-sm btn-success' onclick='return confirmApproval(this);'>Disetujui</a></div>";
                                    }
                                  
                                    if ($row['Approve_Sekretaris'] === 'Disetujui') {
                                        echo "<a onclick='window.location.href=\"laporan_approv_kd.php?id={$row['id']}\"'><ion-icon name='print-sharp' class='btn btn-primary text-white' style='font-size:25px;'></ion-icon></a>";
                                    }
                                    echo "</td>";

                                    echo "</tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='12' class='text-center'>Tidak ada data</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan SweetAlert2 (Jika belum ada) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function updateCurrentYear() {
    document.querySelectorAll('.current-year').forEach(function (yearCell) {
        yearCell.textContent = new Date().getFullYear();
    });
}

updateCurrentYear();
setInterval(updateCurrentYear, 1000);
</script>

<script>
function confirmApproval(link) {
    Swal.fire({
        title: "Konfirmasi",
        text: "Apakah Anda yakin Menyetujui ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Diterima!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Sukses!",
                text: "Terimakasih, atas persetujuan anda!",
                icon: "success"
            }).then(() => {
                window.location.href = link.href; // Arahkan ke link jika dikonfirmasi
            });
        }
    });

    return false; // Mencegah navigasi langsung sebelum konfirmasi
}
</script>

