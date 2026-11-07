

<?php 
  include "koneksi.php";


  if (empty($_SESSION['username']) || empty($_SESSION['level']) || empty($_SESSION['nama_lengkap'])) {
    echo "<script>alert('Maaf, Silahkan Login terlebih dahulu.');</script>";
    header("Location: index.php");
    exit();
}

  // Cek apakah user sudah login
if (!isset($_SESSION['id'])) {
  header("Location: fs_dashboard.php?page=profile");
  exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['id'];
$profileImage = '';






// Pastikan $user_id sudah didefinisikan sebelumnya
if (isset($user_id)) {
  // Query untuk mengambil data gambar dan informasi lainnya dari tabel users
  $query = "SELECT klrhn, kcmtn, gambar FROM users WHERE id = ?";
  $stmt = $koneksi->prepare($query);
  
  if ($stmt) {
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();

      // Periksa apakah query berhasil
      if ($result && $result->num_rows > 0) {
          $data = $result->fetch_assoc();
          // Simpan data ke dalam session
          $profileImage = trim((string) $data['gambar']);
          $_SESSION['gambar'] = $profileImage;
          $_SESSION['klrhn'] = $data['klrhn'];
          $_SESSION['kcmtn'] = $data['kcmtn'];
      } else {
      $_SESSION['error_message'] = 'Gagal mengambil data gambar.';

      }

      $stmt->close(); // Tutup statement setelah selesai
  } else {
      $_SESSION['error_message'] = 'Gagal mempersiapkan statement.';

  }
} else {
      $_SESSION['error_message'] = 'User ID tidak ditemukan.';

}
?>


<nav class="main-header navbar bg-white navbar-expand navbar-white navbar-light fixed-top" style="z-index: 15; left:5px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav" style="position: relative; left: 10px;">
        <li class="nav-item d-none d-lg-block">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        
        <li class="nav-item d-lg-none">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <div class="user-panel" style="position: relative; top:-8px; left: -30px;">
                    <div class="image">
                        <img src="logo/slrt.png" class="img-circle" style="width: 43px; height:43px;">
                    </div>
                </div>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <div class="user-panel d-flex" style="position: relative; top:-5px; right:-15px;">
                <div class="info" style="position: relative; top:-8px;">
                    <b class="text-dark"><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></b> <br>
                    <b class="text-primary"><?= htmlspecialchars($_SESSION['level']) ?></b>
                </div>
                <div class="image">
                  <?php if ($profileImage !== ''): ?>
                      <img src="<?= htmlspecialchars($profileImage, ENT_QUOTES, 'UTF-8'); ?>" alt="Foto profil" class="img-circle mb-1" style="width: 40px; height: 40px; object-fit: cover;">
                  <?php else: ?>
                      <i class="text-dark fas fa-user" style="font-size: 20px;"></i>
                      <i class="text-dark fas fa-question" style="font-size: 20px;"></i>
                  <?php endif; ?>
                </div>
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="fs_dashboard.php?page=fs-profile" class="dropdown-item">
            <!-- Message Start -->
              <div class="media">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Profile <i class="fas fa-user float-right text-primary"></i>
                    <!-- <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span> -->
                  </h3>
                </div>
              </div>
              <!-- Message End -->
          </a>
          <a href="fs_dashboard.php?page=signatur" class="dropdown-item">
            <!-- Message Start -->
              <div class="media">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Tanda Tangan <i class="fas fa-signature float-right text-success"></i>
                    <!-- <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span> -->
                  </h3>
                </div>
              </div>
              <!-- Message End -->
            </a>
          <div class="dropdown-divider"></div>
              <a href="logout.php" class="nav-link" onclick="return confirm('Apakah Anda ingin keluar ?');">
                <div class="media">
                  <div class="media-body">
                    <h5 class="dropdown-item-title">
                      Logout
                      <span class="float-right text-sm text-muted"><i class="nav-icon fas fa-sign-out-alt text-danger"></i></span>
                    </h5>
                  </div>
                </div>
              </a>
          <div class="dropdown-divider"></div>
          <!-- <a href="#" class="dropdown-item dropdown-footer">See All Messages</a> -->
        </div>
      </li> 
  </nav>
<br>
