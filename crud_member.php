<?php
// Koneksi ke database
include "koneksi.php";

// Ketika tombol simpan diklik
if (isset($_POST['simpanmember'])) {
    // Simpan data ke users
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);
    $password = password_hash(mysqli_real_escape_string($koneksi, $_POST['password']), PASSWORD_DEFAULT); // Menggunakan password_hash
    $j_k = mysqli_real_escape_string($koneksi, $_POST['j_k']);
    $klrhn = mysqli_real_escape_string($koneksi, $_POST['klrhn']);
    $kcmtn = mysqli_real_escape_string($koneksi, $_POST['kcmtn']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_wa = mysqli_real_escape_string($koneksi, $_POST['no_wa']);
    $hak_akses = 'Aktif'; // Default value for hak_akses

    // Proses upload gambar
    $target_dir = "upload/"; // Pastikan folder ini ada dan memiliki permission yang benar
    $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file gambar adalah gambar sebenarnya atau bukan
    $check = getimagesize($_FILES["gambar"]["tmp_name"]);
    if($check === false) {
        echo "File bukan gambar.";
        $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["gambar"]["size"] > 500000) { // 500KB
        echo "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Cek format file
    if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        $uploadOk = 0;
    }

    // Cek apakah $uploadOk di-set ke 0 oleh kesalahan
    if ($uploadOk == 0) {
        echo "Maaf, file tidak bisa diupload.";
    } else {
        // Jika semua cek lulus, coba upload file
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            // Insert into users table
            $simpan_user = mysqli_query($koneksi, "INSERT INTO users (username, nama_lengkap, level, password, j_k, klrhn, kcmtn, email, no_wa, gambar)
            VALUES ('$username', '$nama_lengkap','$level','$password','$j_k','$klrhn','$kcmtn','$email','$no_wa', '$target_file')");
            
            if (!$simpan_user) {
                echo "Error: " . mysqli_error($koneksi);
            } else {
                echo "<script> alert('Simpan Data Berhasil');
                document.location='sekretaris.php?page=member';
                </script>";
            }
        } else {
            echo "Maaf, terjadi kesalahan saat mengupload file.";
        }
    }
}








// Ketika tombol edit diklik
if (isset($_POST['editmember'])) {
    if (isset($_POST['id'])) {
        require 'koneksi.php'; // Pastikan koneksi diatur

        $id = intval($_POST['id']); // Hindari SQL Injection dengan konversi ke integer
        $nama_lengkap = htmlspecialchars($_POST['nama_lengkap'], ENT_QUOTES);
        $password = $_POST['password']; // Password baru
        $level = htmlspecialchars($_POST['level'], ENT_QUOTES);
        $j_k = htmlspecialchars($_POST['j_k'], ENT_QUOTES);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $no_wa = htmlspecialchars($_POST['no_wa'], ENT_QUOTES);
        $klrhn = htmlspecialchars($_POST['klrhn'], ENT_QUOTES);
        $kcmtn = htmlspecialchars($_POST['kcmtn'], ENT_QUOTES);
        $gambar = null;

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Format email tidak valid'); window.history.back();</script>";
            exit;
        }

        // Proses upload gambar jika ada
        if (!empty($_FILES['gambar']['name'])) {
            $target_dir = "upload/";
            $imageFileType = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
            $filename = uniqid() . '.' . $imageFileType; // Hindari duplikasi nama file
            $target_file = $target_dir . $filename;

            // Validasi gambar
            $check = getimagesize($_FILES["gambar"]["tmp_name"]);
            if (!$check) {
                echo "<script>alert('File bukan gambar.'); window.history.back();</script>";
                exit;
            }

            if ($_FILES["gambar"]["size"] > 500000) {
                echo "<script>alert('Ukuran gambar terlalu besar (maks 500KB).'); window.history.back();</script>";
                exit;
            }

            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                echo "<script>alert('Format gambar harus JPG, JPEG, PNG, atau GIF.'); window.history.back();</script>";
                exit;
            }

            if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                echo "<script>alert('Gagal mengupload gambar.'); window.history.back();</script>";
                exit;
            }

            $gambar = $target_file;
        }

        // Buat query prepared statement
        $sql = "UPDATE users SET 
                nama_lengkap = ?, 
                level = ?, 
                j_k = ?, 
                email = ?, 
                no_wa = ?, 
                klrhn = ?, 
                kcmtn = ?";

        $params = [$nama_lengkap, $level, $j_k, $email, $no_wa, $klrhn, $kcmtn];

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Gunakan password_hash()
            $sql .= ", password = ?";
            $params[] = $hashed_password;
        }

        if ($gambar) {
            $sql .= ", gambar = ?";
            $params[] = $gambar;
        }

        $sql .= " WHERE id = ?"; 
        $params[] = $id;

        // Jalankan prepared statement
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, str_repeat("s", count($params) - 1) . "i", ...$params);
        $ubah_user = mysqli_stmt_execute($stmt);

        if ($ubah_user) {
            echo "<script>
                    alert('Edit Data Berhasil');
                    document.location='sekretaris.php?page=member';
                  </script>";
        } else {
            echo "<script>
                    alert('Edit Data Gagal: " . mysqli_error($koneksi) . "');
                    document.location='sekretaris.php?page=member';
                  </script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('ID tidak ditemukan'); document.location='sekretaris.php?page=member';</script>";
    }
}









//delete
if (isset($_POST['hapusmember'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);

    // Mulai transaksi untuk memastikan kedua query berjalan bersamaan
    mysqli_begin_transaction($koneksi);

    try {
        // Hapus data di tabel laporan
        $hapusLaporan = mysqli_query($koneksi, "DELETE FROM laporan WHERE user_id = '$id'");

        // Hapus data di tabel users
        $hapusUser   = mysqli_query($koneksi, "DELETE FROM users WHERE id = '$id'");

        if ($hapusLaporan && $hapusUser ) {
            // Commit jika kedua query berhasil
            mysqli_commit($koneksi);
            echo "<script> alert('Data Berhasil Dihapus');
            document.location='sekretaris.php?page=member';
            </script>";
        } else {
            // Rollback jika salah satu query gagal
            mysqli_rollback($koneksi);
            echo "<script> alert('Data Tidak bisa Dihapus');
            document.location='sekretaris.php?page=member';
            </script>";
        }
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        echo "<script> alert('Terjadi kesalahan: " . $e->getMessage() . " document.location='sekretaris.php?page=member';
        </script>";
    }
}
?>