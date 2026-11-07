
<!DOCTYPE html>
<html lang="en">
<meta name="theme-color" content="#f8fafa">
    <link rel="manifest" href="manifest.json">

  

<?php include "header/header.php"; ?>
<?php include "koneksi.php"; ?>

<body class="hold-transition sidebar-mini layout-fixed mt-5">
<div class="mx-1">

  <!-- Preloader -->
  <?php //include "header/loader.php"; ?>

  <!-- Navbar -->
  <?php include "navbar/navbar.php"; ?>
  <!-- /.navbar -->

  <br>
   <!-- Main Sidebar Container -->
   <aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <div class="mx-2">
        <a href="" class="brand-link d-flex align-items-center">
            <img src="logo/slrt.png" class="me-3" width="50px" height="50px">
            <div class="mx-3">
                <span class="fw-bold text-dark mx-4">SLRT KITO</span> <br>
                <span class="fw-bold text-dark">TANJUNGBALAI</span>
            </div>
        </a>
    </div>

    <!-- Sidebar -->
    <?php include "sidebar.php"; ?>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
        <?php 
    // Cek apakah parameter 'page' ada di URL
    if (isset($_GET['page'])) {
        // Ambil nilai dari parameter 'page'
        $page = $_GET['page'];

        // Tentukan file yang akan dimasukkan berdasarkan nilai 'page'
        switch ($page) {
            case 'menu':
                include "menu.php";
                break;
            case 'daftar_bansos_m':
                include "daftar_bansos_m.php";
                break;
            case 'member':
                include "member.php";
                break;
            case 'laporan_approv_m':
                include "laporan_approv_m.php";
                break;
            case 'profile':
                include "profile.php";
                break;
            default:
                include "menu.php"; 
                break;
        }
    } else {
        
        include "menu.php"; 
    }
    ?>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->
    <?php include "footer/manajer/footer.php"; ?>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
</body>
</html>
