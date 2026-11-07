<?php
include "koneksi.php"; // File koneksi MySQLi
session_start();

// Batasi jumlah percobaan registrasi per IP (untuk mencegah brute-force)
$ip_address = $_SERVER['REMOTE_ADDR'];
if (!isset($_SESSION['register_attempts'])) {
    $_SESSION['register_attempts'] = [];
}

// Hapus percobaan yang lebih lama dari 5 menit
$_SESSION['register_attempts'] = array_filter($_SESSION['register_attempts'], function ($time) {
    return $time > time() - 300;
});

// Jika lebih dari 5 percobaan dalam 5 menit, blokir sementara
if (count($_SESSION['register_attempts']) >= 5) {
    die(json_encode(["error" => "Terlalu banyak percobaan, coba lagi nanti!"]));
}

// Membatasi percakapan dari satu IP dalam satu waktu
if (!isset($_SESSION['last_request_time'])) {
    $_SESSION['last_request_time'] = time();
} else {
    $time_diff = time() - $_SESSION['last_request_time'];
    if ($time_diff < 5) {
        die("Tunggu beberapa detik sebelum mencoba lagi.");
    }
    $_SESSION['last_request_time'] = time();
}

if (isset($_POST['simpan'])) {
    // Mengambil dan membersihkan input
    $username = htmlspecialchars(trim($_POST['username']));
    $nama_lengkap = htmlspecialchars(trim($_POST['nama_lengkap']));
    $password = trim($_POST['password']);
    $no_wa = htmlspecialchars(trim($_POST['no_wa']));
    $hak_akses = 'Aktif';

    // Validasi input tidak boleh kosong
    if (empty($username) || empty($nama_lengkap) || empty($password) || empty($no_wa)) {
        echo "<script>alert('Semua kolom harus diisi!'); window.location.href='index.php';</script>";
        exit;
    }

    // Validasi panjang username
    if (strlen($username) < 5) {
        echo "<script>alert('Username minimal 5 karakter!'); window.location.href='index.php';</script>";
        exit;
    }

    // Validasi panjang password
    if (strlen($password) < 8) {
        echo "<script>alert('Password minimal 8 karakter!'); window.location.href='index.php';</script>";
        exit;
    }

    // Cek apakah username sudah digunakan
    $stmt = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        sleep(2); // Tambahkan delay untuk menghindari brute force attack
        echo "<script>alert('Username sudah digunakan, silakan pilih yang lain!'); window.location.href='index.php';</script>";
        exit;
    }
    $stmt->close();

    // Enkripsi password dengan bcrypt
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Simpan data ke database menggunakan prepared statement
    $stmt = $koneksi->prepare("INSERT INTO users (username, nama_lengkap, password, no_wa, hak_akses) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $nama_lengkap, $hashed_password, $no_wa, $hak_akses);

    if ($stmt->execute()) {
        echo "<script>alert('Registrasi berhasil, silakan login!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data!'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $koneksi->close();
}
?>
