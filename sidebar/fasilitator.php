<?php 

if (empty($_SESSION['username']) || empty($_SESSION['level']) || empty($_SESSION['nama_lengkap'])) {
    echo "<script>alert('Maaf, Silahkan Login terlebih dahulu.');</script>";
    header("Location: index.php");
    exit();
}

include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['id'])) {
    header("Location: fs_dashboard.php?page=profile");
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


    <style>
        .sidebar {
            height: 100vh; /* Set tinggi sidebar sesuai dengan tinggi viewport */
            overflow-x: auto; /* Aktifkan scrollbar vertikal */
        }
    </style>



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- Google Font: Source Sans Pro -->




<div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="mx-2 mt-3 pb-3 mb-3 d-flex">
        <div class="image mt-1 me-2">
          <?php if (!empty($data['gambar'])): ?>
              <img src="<?= $data['gambar']; ?>" class="img-circle" style="width: 50px; height: 50px;">
          <?php else: ?>
              <i class="fas fa-user" style="font-size: 30px;"></i>
              <i class="fas fa-question" style="font-size: 30px;"></i>
          <?php endif; ?>
        </div>
        <div class="info mt-2 ms-2">
            <b class="text-dark"><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></b> <br>
            <b class="text-primary"><?= htmlspecialchars($_SESSION['level']) ?></b>
        </div>
      </div>



      <!-- sidebar mobile 
      <nav class="mt-2 d-block d-none d-sm-block">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item" style="list-style: none; display:flex;">
                <a href="fs_dashboard.php?page=fs_laporan_persetujuan" class="nav-link">
                    <i class="nav-icon fas fa-check-double"></i>
                    <p>
                        Laporan Diterima
                    </p>
                </a>
            </li>
         </ul>
      </nav>
     sidebar mobile -->

     

      <!-- Sidebar Menu -->
      <nav class="mt-2 d-sm-none d-lg-block">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="fs_dashboard.php?page=menu" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="fs_dashboard.php?page=fs_registrasi" class="nav-link">
            <i class="nav-icon fas fa-edit"></i>
              <p>
                Registrasi
              </p>
            </a>
          </li>

          
          <li class="nav-item">
            <a href="fs_dashboard.php?page=fs_daftar_bansos" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Daftar Bansos
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="fs_dashboard.php?page=fs_laporan" class="nav-link">
            <!-- <i class="nav-icon fas fa-book"></i> -->
            <i class="nav-icon fas fa-copy"></i>
              <p>
                Rekap Laporan
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="fs_dashboard.php?page=fs_laporan_persetujuan" class="nav-link">
            <!-- <i class="nav-icon fas fa-book"></i> -->
              <i class="nav-icon fas fa-check-double"></i>
              <p>
                Approve Laporan
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-clipboard"></i>
              <p>
                Input Laporan
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="fs_dashboard.php?page=fs_layanan" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Layanan SLRT</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="fs_dashboard.php?page=fs_cek_bansos" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Cek bansos</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="fs_dashboard.php?page=fs_usulan_baru" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Usulan Baru</p>
                </a>
              </li> -->
              <li class="nav-item">
                <a href="fs_dashboard.php?page=fs_bbl" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Bbl</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="fs_dashboard.php?page=fs_kelayakan" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kelayakan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="fs_dashboard.php?page=fs_tugas_tambahan" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tugas Tambahan</p>
                </a>
              </li>
              
            </ul>
          </li>


          <li class="nav-item">
            <a href="logout.php" class="nav-link" onclick="return confirm('Apakah laporannya sudah selesai?');">
              <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>








