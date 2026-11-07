<?php
// $server   = "localhost";
// $user     = "root";
// $password = "";
// $database = "slrt";

// // Membuat koneksi
// $koneksi = mysqli_connect($server, $user, $password, $database);

// // Periksa koneksi
// if (!$koneksi) {
//     die("Koneksi database gagal: " . mysqli_connect_error());
// }

include "koneksi.php";

session_start(); // Ensure session is started

// Cek apakah user_id ada dalam sesi
if (!isset($_SESSION['id'])) {
    header("Location: fasilitator.php?page=fs_bbl");
    exit();
}

// Jika ada, ambil user_id
$user_id = $_SESSION['id'];

// Ketika tombol simpan diklik
if (isset($_POST['simpanbbl'])) {
    $tanggal = $_POST['tanggal'] ?? '';
    $nik_klien = $_POST['nik_klien'] ?? '';
    $nama_klien = $_POST['nama_klien'] ?? '';
    $alamat_klien = $_POST['alamat_klien'] ?? '';
    $jenis_layanan = $_POST['jenis_layanan'] ?? '';
    $keterangan_bbl = $_POST['keterangan_bbl'] ?? '';
    $redirect = 'fs_dashboard.php?page=fs_bbl';

    mysqli_begin_transaction($koneksi);
    $insert_klien = mysqli_prepare($koneksi, "INSERT INTO klien_bbl (nik_klien, nama_klien, alamat_klien) VALUES (?, ?, ?)");
    $simpan = false;

    if ($insert_klien) {
        mysqli_stmt_bind_param($insert_klien, 'sss', $nik_klien, $nama_klien, $alamat_klien);
        if (mysqli_stmt_execute($insert_klien)) {
            $klien_id = mysqli_insert_id($koneksi);
            $insert_bbl = mysqli_prepare($koneksi, "INSERT INTO bbl (tanggal, klien_id, jenis_layanan, keterangan_bbl, user_id) VALUES (?, ?, ?, ?, ?)");

            if ($insert_bbl) {
                mysqli_stmt_bind_param($insert_bbl, 'sissi', $tanggal, $klien_id, $jenis_layanan, $keterangan_bbl, $user_id);
                $simpan = mysqli_stmt_execute($insert_bbl);
                mysqli_stmt_close($insert_bbl);
            }
        }
        mysqli_stmt_close($insert_klien);
    }

    if ($simpan) {
        mysqli_commit($koneksi);
        echo "<script>alert('Simpan Data Berhasil'); document.location='$redirect';</script>";
    } else {
        mysqli_rollback($koneksi);
        echo "<script>alert('Simpan Data Gagal. Periksa kembali data yang diinput.'); document.location='$redirect';</script>";
    }
}




// Ketika tombol edit diklik
if (isset($_POST['ubahbbl'])) {
    if (isset($_POST['id'])) {
        // Mengambil data dari form
        $id = mysqli_real_escape_string($koneksi, $_POST['id']);
        $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
        $nik_klien = mysqli_real_escape_string($koneksi, $_POST['nik_klien']);
        $nama_klien = mysqli_real_escape_string($koneksi, $_POST['nama_klien']);
        $alamat_klien = mysqli_real_escape_string($koneksi, $_POST['alamat_klien']);
        $jenis_layanan = mysqli_real_escape_string($koneksi, $_POST['jenis_layanan']);
        $keterangan_bbl = mysqli_real_escape_string($koneksi, $_POST['keterangan_bbl']);
        
        // Pertama, kita perlu mendapatkan klien_id dari bbl
        $query_klien_id = "SELECT klien_id FROM bbl WHERE id = '$id'";
        $result_klien_id = mysqli_query($koneksi, $query_klien_id);
        
        if ($result_klien_id && mysqli_num_rows($result_klien_id) > 0) {
            $row = mysqli_fetch_assoc($result_klien_id);
            $klien_id = $row['klien_id'];

            // Update data di tabel klien_bbl
            $query_update_klien = "UPDATE klien_bbl SET 
                                    nik_klien = '$nik_klien',
                                    nama_klien = '$nama_klien',
                                    alamat_klien = '$alamat_klien'
                                    WHERE id = '$klien_id'";
            $ubah_klien = mysqli_query($koneksi, $query_update_klien);

            // Update data di tabel bbl
            $query_update_usulan = "UPDATE bbl SET 
                                    tanggal = '$tanggal',
                                    jenis_layanan = '$jenis_layanan',
                                    keterangan_bbl = '$keterangan_bbl'
                                    WHERE id = '$id'";
            $ubah_usulan = mysqli_query($koneksi, $query_update_usulan);

            // Cek apakah kedua query berhasil
            if ($ubah_klien && $ubah_usulan) {
                echo "<script> alert('Edit Data Berhasil');
                document.location='fs_dashboard.php?page=fs_bbl';
                </script>";
            } else {
                echo "Error: " . mysqli_error($koneksi);
                echo "<script> alert('Edit Data Gagal');
                document.location='fs_dashboard.php?page=fs_bbl';
                </script>";
            }
        } else {
            echo "<script> alert('ID Klien tidak ditemukan');
            document.location='fs_dashboard.php?page=fs_bbl';
            </script>";
        }
    } else {
        echo "<script> alert('ID tidak ditemukan');
        document.location='fs_dashboard.php?page=fs_bbl';
        </script>";
    }
}

// Ketika tombol hapus diklik
if (isset($_POST['hapusbbl'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);

    $hapus = mysqli_query($koneksi, "DELETE FROM bbl WHERE id = '$id'");

    if ($hapus) {
        echo "<script> alert('Data Berhasil Dihapus');
        document.location='fs_dashboard.php?page=fs_bbl';
        </script>";
    } else {
        echo "<script> alert('Data Tidak bisa Dihapus');
        document.location='fs_dashboard.php?page=fs_bbl';
        </script>";
    }
}
?>