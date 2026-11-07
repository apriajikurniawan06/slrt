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
    header("Location: fasilitator.php?page=fs_tugas_tambahan");
    exit();
}

// Jika ada, ambil user_id
$user_id = $_SESSION['id'];

// Ketika tombol simpan diklik
if (isset($_POST['simpantambahan'])) {
    // Simpan data
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    
    // Insert into klien_tugas_tambahan first
    $nik_klien = mysqli_real_escape_string($koneksi, $_POST['nik_klien']);
    $nama_klien = mysqli_real_escape_string($koneksi, $_POST['nama_klien']);
    $alamat_klien = mysqli_real_escape_string($koneksi, $_POST['alamat_klien']);
    
    // Insert into klien_tugas_tambahan
    $insert_klien = mysqli_query($koneksi, "INSERT INTO klien_tugas_tambahan (nik_klien, nama_klien, alamat_klien) VALUES ('$nik_klien', '$nama_klien', '$alamat_klien')");
    
    if ($insert_klien) {
        $klien_id = mysqli_insert_id($koneksi); // Get the last inserted klien_id

        // Retrieve jenis layanan from the POST data
        $jenis_layanan = mysqli_real_escape_string($koneksi, $_POST['jenis_layanan']);
        $keterangan_tt = mysqli_real_escape_string($koneksi, $_POST['keterangan_tt']);
        
        // Now insert into tugas_tambahan
        $simpan = mysqli_query($koneksi, "INSERT INTO tugas_tambahan (tanggal, klien_id, jenis_layanan, keterangan_tt, user_id) 
        VALUES ('$tanggal', '$klien_id', '$jenis_layanan', '$keterangan_tt', '$user_id')");

        if ($simpan) {
            echo "<script> alert('Simpan Data Berhasil');
            document.location='fs_dashboard.php?page=fs_tugas_tambahan';
            </script>";
        } else {
            echo "<script> alert('Simpan Data Gagal');
            document.location='fs_tugas_tambahan.php';
            </script>";
        }
    } else {
        echo "<script> alert('Gagal menyimpan klien');
        document.location='fs_tugas_tambahan.php';
        </script>";
    }
}




// Ketika tombol edit diklik
if (isset($_POST['ubahtugastambahan'])) {
    if (isset($_POST['id'])) {
        // Mengambil data dari form
        $id = mysqli_real_escape_string($koneksi, $_POST['id']);
        $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
        $nik_klien = mysqli_real_escape_string($koneksi, $_POST['nik_klien']);
        $nama_klien = mysqli_real_escape_string($koneksi, $_POST['nama_klien']);
        $alamat_klien = mysqli_real_escape_string($koneksi, $_POST['alamat_klien']);
        $jenis_layanan = mysqli_real_escape_string($koneksi, $_POST['jenis_layanan']);
        $keterangan_tt = mysqli_real_escape_string($koneksi, $_POST['keterangan_tt']);
        
        // Pertama, kita perlu mendapatkan klien_id dari tugas_tambahan
        $query_klien_id = "SELECT klien_id FROM tugas_tambahan WHERE id = '$id'";
        $result_klien_id = mysqli_query($koneksi, $query_klien_id);
        
        if ($result_klien_id && mysqli_num_rows($result_klien_id) > 0) {
            $row = mysqli_fetch_assoc($result_klien_id);
            $klien_id = $row['klien_id'];

            // Update data di tabel klien_tugas_tambahan
            $query_update_klien = "UPDATE klien_tugas_tambahan SET
                                    nik_klien = '$nik_klien',
                                    nama_klien = '$nama_klien',
                                    alamat_klien = '$alamat_klien'
                                    WHERE id = '$klien_id'";
            $ubah_klien = mysqli_query($koneksi, $query_update_klien);

            // Update data di tabel tugas_tambahan
            $query_update_usulan = "UPDATE tugas_tambahan SET 
                                    tanggal = '$tanggal',
                                    jenis_layanan = '$jenis_layanan',
                                    keterangan_tt = '$keterangan_tt'
                                    WHERE id = '$id'";
            $ubah_usulan = mysqli_query($koneksi, $query_update_usulan);

            // Cek apakah kedua query berhasil
            if ($ubah_klien && $ubah_usulan) {
                echo "<script> alert('Edit Data Berhasil');
                document.location='fs_dashboard.php?page=fs_tugas_tambahan';
                </script>";
            } else {
                echo "Error: " . mysqli_error($koneksi);
                echo "<script> alert('Edit Data Gagal');
                document.location='fs_dashboard.php?page=fs_tugas_tambahan';
                </script>";
            }
        } else {
            echo "<script> alert('ID Klien tidak ditemukan');
            document.location='fs_dashboard.php?page=fs_tugas_tambahan';
            </script>";
        }
    } else {
        echo "<script> alert('ID tidak ditemukan');
        document.location='fs_dashboard.php?page=fs_tugas_tambahan';
        </script>";
    }
}

// Ketika tombol hapus diklik
if (isset($_POST['hapustugastambahan'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);

    $hapus = mysqli_query($koneksi, "DELETE FROM tugas_tambahan WHERE id = '$id'");

    if ($hapus) {
        echo "<script> alert('Data Berhasil Dihapus');
        document.location='fs_dashboard.php?page=fs_tugas_tambahan';
        </script>";
    } else {
        echo "<script> alert('Data Tidak bisa Dihapus');
        document.location='fs_dashboard.php?page=fs_tugas_tambahan';
        </script>";
    }
}
?>