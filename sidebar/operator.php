      
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
      <div class="mx-3 mt-3 pb-3 mb-3 d-flex">
        <div class="image mt-2 me-2">
          <?php if (!empty($data['gambar'])): ?>
              <img src="<?= $data['gambar']; ?>" class="img-circle mb-1" style="width: 40px; height: 40px;">
          <?php else: ?>
              <i class="text-dark fas fa-user" style="font-size: 20px;"></i>
              <i class="text-dark fas fa-question" style="font-size: 20px;"></i>
          <?php endif; ?>
        </div>
        <div class="info ml-2">
            <b class="text-dark"><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></b> <br>
            <b class="text-primary"><?= htmlspecialchars($_SESSION['level']) ?></b>
        </div>
      </div>
      
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column d-lg-none d-md-block" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="sekretaris.php?page=history" class="nav-link">
            <i class="nav-icon fas fa-file-invoice"></i>

              <p>
                Daftar Pelayanan
              </p>
            </a>
          </li>
        </ul>

        <ul class="nav nav-pills nav-sidebar flex-column d-none d-md-block" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="op_dashboard.php?page=menu" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="op_dashboard.php?page=daftar_bansos" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Pendaftar Bansos
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="op_dashboard.php?page=input_data" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Input Data
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="op_dashboard.php?page=rekapitulasi" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
              <p>
                Rekap Data
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="op_dashboard.php?page=laporan_approv_o" class="nav-link" style="position: relative; left:-5px;">
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



