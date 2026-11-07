<?php
// Setting keamanan session
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '', // isi domain jika perlu, contoh: 'example.com'
    'secure' => isset($_SERVER['HTTPS']), // harus true kalau HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

// Set HTTP headers untuk meningkatkan keamanan
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");

// Koneksi ke database
include "koneksi.php";

// Konfigurasi batas login
$max_attempts = 5;
$lockout_duration = 300; // 5 menit
$ip_address = $_SERVER['REMOTE_ADDR'];

// Buat token CSRF jika belum ada
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Inisialisasi percobaan login per IP
if (!isset($_SESSION['login_attempts'][$ip_address])) {
    $_SESSION['login_attempts'][$ip_address] = [
        'attempts' => 0,
        'lockout_time' => 0
    ];
}

// Cek apakah dalam masa blokir
$attempt_data = $_SESSION['login_attempts'][$ip_address];
if ($attempt_data['attempts'] >= $max_attempts) {
    $sisa_waktu = $lockout_duration - (time() - $attempt_data['lockout_time']);
    if ($sisa_waktu > 0) {
        echo "<script>alert('Terlalu banyak percobaan. Coba lagi dalam $sisa_waktu detik.'); document.location='index.php';</script>";
        exit;
    } else {
        $_SESSION['login_attempts'][$ip_address] = ['attempts' => 0, 'lockout_time' => 0];
    }
}

// Validasi input
if (!isset($_POST['username'], $_POST['password'], $_POST['level'], $_POST['csrf_token'])) {
    echo "<script>alert('Akses tidak sah!'); document.location='index.php'</script>";
    exit;
}

// Validasi token CSRF
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    echo "<script>alert('Token CSRF tidak valid.'); document.location='index.php'</script>";
    exit;
}

// Sanitasi input
$username = trim(mysqli_real_escape_string($koneksi, $_POST['username']));
$password = $_POST['password']; // password tidak diubah-ubah
$input_level = trim(mysqli_real_escape_string($koneksi, $_POST['level']));

// Query cek username
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user_valid = $result->fetch_assoc();

if ($user_valid) {
    if (password_verify($password, $user_valid['password'])) {
        // Cek akun aktif
        if ($user_valid['hak_akses'] !== 'Aktif') {
            echo "<script>alert('Akun tidak aktif. Hubungi Koordinator.'); document.location='index.php'</script>";
            exit;
        }

        // Validasi level
        if ($input_level !== $user_valid['level']) {
            echo "<script>alert('⚠️ Level tidak sesuai.'); document.location='index.php';</script>";
            exit;
        }

        // Reset percobaan login
        $_SESSION['login_attempts'][$ip_address] = ['attempts' => 0, 'lockout_time' => 0];

        // Regenerasi session ID
        session_regenerate_id(true);

        // Simpan data user ke session
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user_valid['username'];
        $_SESSION['nama_lengkap'] = $user_valid['nama_lengkap'];
        $_SESSION['level'] = $user_valid['level'];
        $_SESSION['klrhn'] = $user_valid['klrhn'];
        $_SESSION['kcmtn'] = $user_valid['kcmtn'];
        $_SESSION['id'] = $user_valid['id'];

        // Hapus CSRF token setelah login berhasil
        unset($_SESSION['csrf_token']);

        // Redirect sesuai level
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
        // Password salah
        $_SESSION['login_attempts'][$ip_address]['attempts'] += 1;
        if ($_SESSION['login_attempts'][$ip_address]['attempts'] >= $max_attempts) {
            $_SESSION['login_attempts'][$ip_address]['lockout_time'] = time();
            echo "<script>alert('Terlalu banyak percobaan. Coba lagi dalam $lockout_duration detik.'); document.location='index.php';</script>";
        } else {
            echo "<script>alert('⚠️ Login Gagal. Username atau password salah. Percobaan ke-".$_SESSION['login_attempts'][$ip_address]['attempts']." dari $max_attempts'); document.location='index.php';</script>";
        }
        exit;
    }
} else {
    // Username tidak ditemukan
    $_SESSION['login_attempts'][$ip_address]['attempts'] += 1;
    if ($_SESSION['login_attempts'][$ip_address]['attempts'] >= $max_attempts) {
        $_SESSION['login_attempts'][$ip_address]['lockout_time'] = time();
        echo "<script>alert('Terlalu banyak percobaan. Coba lagi dalam $lockout_duration detik.'); document.location='index.php';</script>";
    } else {
        echo "<script>alert('⚠️ Login Gagal. Username atau password salah. Percobaan ke-".$_SESSION['login_attempts'][$ip_address]['attempts']." dari $max_attempts'); document.location='index.php';</script>";
    }
    exit;
}

// Tutup koneksi
$stmt->close();
$koneksi->close();
?>


<!-- tambahkan input di index login -->
<!-- <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"> -->