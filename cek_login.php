<?php
session_start(); // Pastikan session dimulai di awal
include "koneksi.php";

// Set HTTP headers untuk meningkatkan keamanan
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");

// Konfigurasi batas login
$max_attempts = 3;
$lockout_duration = 180;

// Inisialisasi session login attempts jika belum ada
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = 0;
}

// Cek apakah user dalam masa blokir sementara
if ($_SESSION['login_attempts'] >= $max_attempts) {
    $sisa_waktu = $lockout_duration - (time() - $_SESSION['lockout_time']);
    if ($sisa_waktu > 0) {
        echo "<script>alert('Coba lagi dalam $sisa_waktu detik.'); document.location='index.php';</script>";
        exit;
    } else {
        $_SESSION['login_attempts'] = 0;
    }
}

// Validasi input
if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['level'])) {
    echo "<script>alert('Akses tidak sah!'); document.location='index.php'</script>";
    exit;
}

// Sanitasi input
$username = trim(mysqli_real_escape_string($koneksi, $_POST['username']));
$password = $_POST['password'];
$input_level = $_POST['level'];

// Query untuk cek username
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user_valid = $result->fetch_assoc();

if ($user_valid) {
    // Verifikasi password dengan password_hash()
    if (password_verify($password, $user_valid['password'])) {
        // Cek apakah akun aktif
        if ($user_valid['hak_akses'] !== 'Aktif') {
            echo "<script>alert('Akun Anda tidak aktif. Silakan hubungi Koordinator.'); document.location='index.php'</script>";
            exit;
        }

        // Validasi level yang sesuai
        if ($input_level !== $user_valid['level']) {
            echo "<script>alert('⚠️ Akses Salah! Level tidak sesuai.'); document.location='index.php';</script>";
            exit;
        }

        // Reset login attempts jika berhasil login
        $_SESSION['login_attempts'] = 0;
        session_regenerate_id(true);

        // Simpan data user ke session
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user_valid['username'];
        $_SESSION['nama_lengkap'] = $user_valid['nama_lengkap'];
        $_SESSION['level'] = $user_valid['level'];
        $_SESSION['klrhn'] = $user_valid['klrhn'];
        $_SESSION['kcmtn'] = $user_valid['kcmtn'];
        $_SESSION['id'] = $user_valid['id'];

        // Redirect sesuai level user
        switch ($user_valid['level']) {
            case "fasilitator":
                header('Location: fs_dashboard.php');
                break;
            case "operator":
                header('Location: op_dashboard.php');
                break;
            case "koordinator":
                header('Location: kd_dashboard.php');
                break;
            case "sekretaris":
                header('Location: sekretaris.php');
                break;
            case "manajer":
                header('Location: manajer.php');
                break;
            default:
                echo "<script>alert('Login Gagal. Akun tidak dikenal.'); document.location='index.php'</script>";
                break;
        }
        exit;
    } else {
        // Jika password salah, tambahkan percobaan login
        $_SESSION['login_attempts'] += 1;
        if ($_SESSION['login_attempts'] >= $max_attempts) {
            $_SESSION['lockout_time'] = time();
            echo "<script>alert('Coba lagi dalam $lockout_duration detik.'); document.location='index.php';</script>";
        } else {
            echo "<script>alert('⚠️ Login Gagal. Username atau password salah. Percobaan ke-".$_SESSION['login_attempts']." dari $max_attempts'); document.location='index.php';</script>";
        }
        exit;
    }
} else {
    $_SESSION['login_attempts'] += 1;
    if ($_SESSION['login_attempts'] >= $max_attempts) {
        $_SESSION['lockout_time'] = time();
        echo "<script>alert('Coba lagi dalam $lockout_duration detik.'); document.location='index.php';</script>";
    } else {
        echo "<script>alert('⚠️ Login Gagal. Username atau password salah. Percobaan ke-".$_SESSION['login_attempts']." dari $max_attempts'); document.location='index.php';</script>";
    }
    exit;
}

// Tutup statement dan koneksi
$stmt->close();
$koneksi->close();
?>
