      
      <?php 

      // Cek apakah user sudah login
if (!isset($_SESSION['id'])) {
  header("Location: fs_dashboard.php?page=profile");
  exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['id'];



        if (empty($_SESSION['username']) || empty($_SESSION['level']) || empty($_SESSION['nama_lengkap']) ) {
          echo "<script>alert('Maaf, Silahkan Login terlebih dahulu.');</script>";
          header("Location: index.php");
          exit();
      }

      
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

<style>
        .sidebar {
            height: 100vh; /* Set tinggi sidebar sesuai dengan tinggi viewport */
            overflow-x: auto; /* Aktifkan scrollbar vertikal */
        }
    </style>
    

<div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-1 pb-3 mb-3 d-flex">
        <div class="image mt-3">
          <?php if (!empty($data['gambar'])): ?>
              <img src="<?= $data['gambar']; ?>" class="img-circle" style="width: 50px; height: 50px;">
          <?php else: ?>
              <i class="fas fa-user" style="font-size: 30px;"></i>
              <i class="fas fa-question" style="font-size: 30px;"></i>
          <?php endif; ?>
        </div>
        <div class="info">
            <b class="text-dark"><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></b> <br>
            <b class="text-primary"><?= htmlspecialchars($_SESSION['level']) ?></b>
        </div>
      </div>
      
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="manajer.php?page=menu" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="manajer.php?page=daftar_bansos_m" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Daftar Bansos
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="manajer.php?page=member" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
              <p>
                Akses Karyawan
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="manajer.php?page=laporan_approv_m" class="nav-link" style="position: relative; left:-5px;">
            <i class="nav-icon fas fa-check-double"></i>
              <p>
                Approve Laporan
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link" onclick="return confirm('Apakah Anda ingin keluar ?');">
              <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
        </ul>
      </nav>
</div>