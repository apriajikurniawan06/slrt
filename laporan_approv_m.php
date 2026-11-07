<?php

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'slrt');

// Check koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
        echo "<script>alert('Persetujuan berhasil diperbarui!'); window.location.href='laporan_approv_m.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui persetujuan: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
}


if (!isset($_SESSION['id'])) {
    header("Location: manajer.php?page=laporan_approv_m");
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
// Ambil data berdasarkan ID jika ada, jika tidak ambil semua
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
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">


<!-- Main content -->
<div class="mx-2 bg-white">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="mb-3">
                    <h4 class="card-title">PERSETUJUAN LAPORAN</h4>
                </div>
                <div class="table-responsive mx-2">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAMA LENGKAP</th>
                                <th>JENIS KELAMIN</th>
                                <th>KELURAHAN</th>
                                <th>ON/OFF FILTER</th>
                                <th>TAHUN</th>
                                <th>FASILITATOR</th>
                                <th>OPERATOR</th>
                                <th>KOORDINATOR</th>
                                <th>SEKRETARIS</th>
                                <th>MANAJER</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $data = []; // Inisialisasi array $data

                            while ($row = $result->fetch_assoc()) {
                                $data[] = $row; // Menyimpan setiap baris ke dalam array $data

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
                                $approveKoordinator = $row['Approve_Koordinator'];
                                $backgroundColor = ($approveKoordinator == 'Disetujui') ? 'green' : (($approveKoordinator == 'Belum Disetujui') ? 'red' : 'transparent');
                                echo "<td><span style='background-color: {$backgroundColor}; color: white; border-radius: 20px; width: 120px; display: flex; align-items: center; justify-content: center; text-align: center;'>{$approveKoordinator}</span></td>";

                                // Kolom Sekretaris
                                $approveSekretaris = $row['Approve_Sekretaris'];
                                $backgroundColor = ($approveSekretaris == 'Disetujui') ? 'green' : (($approveSekretaris == 'Belum Disetujui') ? 'red' : 'transparent');
                                echo "<td><span style='background-color: {$backgroundColor}; color: white; border-radius: 20px; width: 120px; display: flex; align-items: center; justify-content: center; text-align: center;'>{$approveSekretaris}</span></td>";


                                // Kolom ACTION
                                echo "<td>";
                                if ($row['fasilitator'] === 'Selesai' && $row['Approve_Koordinator'] !== 'Disetujui' && $row['Approve_Sekretaris'] === 'Disetujui') {
                                    echo "<div style='margin-top: 5px;'>";
                                    echo "<a href='laporan_approv_m.php?action=Manajer&id={$row['id']}' class='btn btn-sm btn-success' onclick='return confirmApproval(this);'> Disetujui</a>";
                                    echo "</div>";
                                }
                                if ($row['Approve_Sekretaris'] === 'Disetujui') {
                                    echo "<a onclick='window.location.href=\"laporan_approv_m.php?id={$row['id']}\"'><ion-icon name='print-sharp' class='btn btn-primary text-white' style='font-size:25px;'></ion-icon></a>";
                                }
                                echo "</td>";

                                echo "</tr>";
                                $no++;
                            }
                            if ($no === 1) {
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
        // Tampilkan alert untuk konfirmasi
        if (confirm('Konfirmasi Laporan telah selesai.')) {
            // Jika pengguna mengklik "OK", lanjutkan ke link
            setTimeout(function() {
                alert('Selamat, laporan selesai!');
            }, 10); // Delay 10ms untuk memastikan alert muncul setelah navigasi
            return true; // Lanjutkan ke link
        } else {
            return false; // Batalkan jika pengguna mengklik "Cancel"
        }
    }





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
            body: JSON.stringify({ id, filter_status: status }), // Perbaiki nama kunci dari filter_status menjadi approval_filter
        })
        .then(response => {
            // Pastikan respons memiliki status OK
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            // Coba parse respons sebagai JSON
            return response.json();
        })
        .then(data => {
            // Tampilkan pesan sukses dari server
            if (data && data.message) {
                alert(data.message);
            }
            // Ubah warna teks berdasarkan status yang dipilih
            changeTextColor(selectElement);
        })
        .catch(error => {
            console.error('Error:', error.message);
            alert('Terjadi kesalahan saat memperbarui status. Periksa koneksi atau format respons server.');
        });
    }


    function changeTextColor(selectElement) {
        const status = selectElement.value;
        let textColor = '#ffffff'; // Warna teks putih
        let backgroundColor;

        switch (status) {
            case 'On Filter':
                backgroundColor = 'green'; // hijau tua
                break;
            case 'Off':
                backgroundColor = 'red'; // merah
                break;
            default:
                backgroundColor = 'transparent'; // Default tidak ada warna
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