

<?php 
  include "koneksi.php";

  // Cek apakah user sudah login
if (!isset($_SESSION['id'])) {
  header("Location: op_dashboard.php?page=profile");
  exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['id'];



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
          $_SESSION['gambar'] = $data['gambar'];
          $_SESSION['klrhn'] = $data['klrhn'];
          $_SESSION['kcmtn'] = $data['kcmtn'];
      } else {
          echo "<script>alert('Gagal mengambil data gambar.');</script>";
      }

      $stmt->close(); // Tutup statement setelah selesai
  } else {
      echo "<script>alert('Gagal mempersiapkan statement.');</script>";
  }
} else {
  echo "<script>alert('User  ID tidak ditemukan.');</script>";
}
?>


<nav class="main-header navbar bg-white navbar-expand navbar-white navbar-light fixed-top" style="z-index: 15; left:5px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav" style="position: relative; left: 5px;">
        <li class="nav-item">
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
        <li class="nav-item d-none d-sm-inline-block">
            <a href="op_dashboard.php?page=history" class="nav-link text-black">Daftar Layanan</a>
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
                  <?php if (!empty($data['gambar'])): ?>
                      <img src="<?= $data['gambar']; ?>" class="img-circle mb-1" style="width: 40px; height: 40px;">
                  <?php else: ?>
                      <i class="text-dark fas fa-user" style="font-size: 20px;"></i>
                      <i class="text-dark fas fa-question" style="font-size: 20px;"></i>
                  <?php endif; ?>
                </div>
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="op_dashboard.php?page=op-profile" class="dropdown-item">
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
    </ul>
  </nav>
<br>
