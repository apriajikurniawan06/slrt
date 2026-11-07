<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'slrt');

// Check koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current day of the month
$currentDay = date('j');

// Get the current month and year
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

    if ($stmt->execute()) {
        echo "<script>alert('Persetujuan berhasil diperbarui!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui persetujuan: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
}

if (!isset($_SESSION['id'])) {
    header("Location: sekretaris.php?page=laporan_approv_s");
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
// Cek apakah tombol print sudah ditekan
if (!isset($_SESSION['isPrinted'])) {
    $_SESSION['isPrinted'] = false; // Inisialisasi status print
}

// Definisikan array untuk nama bulan dalam bahasa Indonesia
$bulan = array(
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
);

// Mendapatkan bulan saat ini dalam bentuk angka (1-12)
$currentMonth = date('n');

// Cek status print
$isPrinted = isset($_SESSION['isPrinted']) ? $_SESSION['isPrinted'] : false;
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


<!-- Main content -->
<div class="mx-2 bg-white">
    <div class="row">
        <div class="col-12"  style="position: relative; top: -50px;">
            <div class="card-body">
            <?php
                // Mengatur zona waktu ke Indonesia Barat
                date_default_timezone_set('Asia/Jakarta'); // Ganti dengan zona waktu yang sesuai jika diperlukan

                // Mendapatkan tanggal dan waktu saat ini
                $tanggal_sekarang = date('d-m-Y');
                $jam_sekarang = date('H:i:s');
            ?>
            <div class="d-flex justify-content-between align-items-center">
                <!-- Judul di sebelah kiri -->
                <div class="card-body">
                    <h5 class="text-primary bold mb-0">PERSETUJUAN LAPORAN</h5>
                </div>

                <!-- Tanggal dan jam di sebelah kanan -->
                <div class="card-body text-right">
                    <span class="text-danger" id="tanggal"><?= $tanggal_sekarang ?></span> |
                    <span class="text-danger" id="jam"><?= $jam_sekarang ?></span>
                </div>
            </div>
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAMA LENGKAP</th>
                                <th>JENIS KELAMIN</th>
                                <th>KELURAHAN</th>
                                <th>ON/OFF FILTER</th>
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

            echo "<td>";
            echo "<select name='filter_status' class='approve-filter' data-id='{$row['id']}' onchange='updateApprovalStatus(this)' style='border: none; background-color: transparent; color: inherit;'>";
            echo "<option value='Off' " . ($row['filter_status'] === 'Off' ? 'selected' : '') . ">Off</option>";
            echo "<option value='On Filter' " . ($row['filter_status'] === 'On Filter' ? 'selected' : '') . ">On Filter</option>";
            echo "</select>";
            echo "</td>";

            // Tampilkan bulan sesuai status
            if ($isPrinted) {
                // Jika sudah ditekan, ganti bulan
                $nextMonth = ($currentMonth % 12) + 1; // Ganti ke bulan berikutnya
                echo "<td>{$bulan[$nextMonth]}</td>";
            } else {
                // Jika belum ditekan, tetap tampilkan bulan saat ini
                echo "<td>{$bulan[$currentMonth]}</td>";
            }

            echo "<td class='current-year'>" . date('Y') . "</td>";

            // Kolom Fasilitator
            $fasilitator = $row['fasilitator'];
            $backgroundColor = ($fasilitator == 'Selesai') ? '#43d443' : (($fasilitator == 'Diproses') ? '#fa3232' : 'transparent');
            echo "<td><span style='background-color: {$backgroundColor}; color: #ffffff; border-radius: 20px; width:  80px; display: flex; align-items: center; justify-content: center; text-align: center;'>{$fasilitator}</span></td>";

            // Kolom Operator
            $approveOperator = $row['Approve_Operator'];
            $backgroundColor = ($approveOperator == 'Disetujui') ? '#43d443' : (($approveOperator == 'Belum Disetujui') ? '#fa3232' : 'transparent');
            echo "<td><span style='background-color: {$backgroundColor}; color: #ffffff; border-radius: 20px; width: 120px; display: flex; align-items: center; justify-content: center; text-align: center;'>{$approveOperator}</span></td>";

            // Kolom Koordinator
            $approveKoordinator = $row['Approve_Koordinator'];
            $backgroundColor = ($approveKoordinator == 'Disetujui') ? '#43d443' : (($approveKoordinator == 'Belum Disetujui') ? '#fa3232' : 'transparent');
            echo "<td><span style='background-color: {$backgroundColor}; color: #ffffff; border-radius: 20px; width: 120px; display: flex; align-items: center; justify-content: center; text-align: center;'>{$approveKoordinator}</span></td>";

            // Kolom Sekretaris
            echo "<td>";
            $backgroundColor = '';
            if ($row['Approve_Sekretaris'] === 'Disetujui') {
                $backgroundColor = 'background-color: #43d443; color: #ffffff;'; // Hijau untuk 'Disetujui'
            } elseif ($row['Approve_Sekretaris'] === 'Belum Disetujui') {
                $backgroundColor = 'background-color: #fa3232;'; // Merah untuk 'Belum Disetujui'
            } else {
                $backgroundColor = 'background-color: #fa3232; color: #ffffff;'; // Merah untuk status lainnya
            }
            echo "<span style='{$backgroundColor} border-radius: 20px; color:#ffffff; width: 120px; display: inline-block; text-align: center;'>{$row['Approve_Sekretaris']}</span><br>";
            echo "</td>";

            // Kolom ACTION
            echo "<td>";
            $currentDay = date('j');
            $currentMonth = date('n');

            if ($row['fasilitator'] === 'Diproses' && 
                (($currentMonth == 2 && $currentDay > 28) || // Februari
                ($currentMonth == 4 || $currentMonth == 6 || $currentMonth == 9 || $currentMonth == 11) && $currentDay > 30 || // Bulan dengan 30 hari
                ($currentMonth == 1 || $currentMonth == 3 || $currentMonth == 5 || $currentMonth == 7 || $currentMonth == 8 || $currentMonth == 10 || $currentMonth == 12) && $currentDay > 31)) { // Bulan dengan 31 hari
                echo "<div style='margin-top: 5px;'>";
                echo "<a href='fs_laporan_persetujuan.php?action=fasilitator&id={$row['id']}' class='btn btn-sm btn-success' onclick='return confirmApproval(this);'>Selesai</a>";
                echo "</div>";
            }

            // Tombol Disetujui di bawah status
            if ($row['fasilitator'] === 'Selesai'  
            && $row['Approve_Koordinator'] === 'Disetujui' 
            && $row['Approve_Sekretaris'] !== 'Disetujui') {
                echo "<div style='margin-top: 5px;'>";
                echo "<a href='laporan_approv_s.php?action=Sekretaris&id={$row['id']}' class='btn btn-sm btn-success' onclick='return confirmApproval(this);'>Disetujui</a>";
                echo "</div>";
            }
            // Tampilkan tombol print setelah Approve manajer 'Disetujui'
            if ($row['Approve_Sekretaris'] === 'Disetujui') {
                echo "<a onclick='window.location.href=\"laporan_approv_s.php?id={$row['id']}\"'><ion-icon name='print-sharp' class='btn btn-primary text-white' style='font-size:25px;'></ion-icon></a>";
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




<script>
function updateApprovalStatus(selectElement) {
    // Ambil ID dan status yang dipilih
    const id = selectElement.dataset.id;
    const status = selectElement.value;

    // Kirim permintaan AJAX ke server
    fetch('laporan_filter_m.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id, filter_status: status }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data && data.message) {
            alert(data.message);
        }
        changeTextColor(selectElement);
    })
    .catch(error => {
        console.error('Error:', error.message);
        alert('Terjadi kesalahan saat memperbarui status. Periksa koneksi atau format respons server.');
    });
}

function changeTextColor(selectElement) {
    const status = selectElement.value;
    let textColor = '#ffffff';
    let backgroundColor;

    switch (status) {
        case 'On Filter':
            backgroundColor = '#60c760';
            break;
        case 'Off':
            backgroundColor = '#fa3232';
            break;
        default:
            backgroundColor = 'transparent';
    }

    selectElement.style.color = textColor; // Ubah warna teks
    selectElement.style.backgroundColor = backgroundColor; // Ubah warna latar belakang
    selectElement.style.borderRadius = '20px'; // Ubah border radius
    selectElement.style.appearance = 'none'; // Menghilangkan ikon petunjuk
    selectElement.style.webkitAppearance = 'none'; // Untuk Safari
    selectElement.style.mozAppearance = 'none'; // Untuk Firefox

    // Menambahkan properti CSS untuk menengahkan teks dan mengatur lebar
    selectElement.style.width = '100px'; 
    selectElement.style.textAlign = 'center'; 
    selectElement.style.lineHeight = '25px';
}

// Terapkan warna saat halaman dimuat
document.querySelectorAll('.approve-filter').forEach(select => {
    changeTextColor(select);
});
</script>





<script>
    //WAKTU================================================================
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

