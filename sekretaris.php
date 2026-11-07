
<!DOCTYPE html>
<html lang="en">
<meta name="theme-color" content="#f8fafa">
    <link rel="manifest" href="manifest.json">


<?php include "header/header.php"; ?>
<?php include "koneksi.php"; ?>

<body class="hold-transition sidebar-mini layout-fixed mt-5">
<div class="wrapper">

  <!-- Preloader -->
  <?php include "header/loader.php"; ?>

  <!-- Navbar -->
  <?php include "navbar/sk_navbar.php"; ?>
  <!-- /.navbar -->

  <br>
   <!-- Main Sidebar Container -->
   <aside class="main-sidebar sidebar-light-primary">
    <!-- Brand Logo -->
    <div class="mx-2">
        <a href="" class="d-flex align-items-center mx-3 mt-2">
            <img src="logo/slrt.png" class="me-3" width="50px" height="50px">
            <div class="mx-1">
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
  <div class="content-wrapper bg-white card-body">
    <!-- Main content -->
        <?php 
    // Cek apakah parameter 'page' ada di URL
    if (isset($_GET['page'])) {
        // Ambil nilai dari parameter 'page'
        $page = $_GET['page'];

        // Tentukan file yang akan dimasukkan berdasarkan nilai 'page'
        switch ($page) {
            case 'menu-s':
                include "menu-s.php";
                break;
            case 'daftar_bansos_s':
                include "daftar_bansos_s.php";
                break;
            case 'member':
                include "member.php";
                break;
            case 'laporan_approv_s':
                include "laporan_approv_s.php";
                break;
            case 'history':
                include "history.php";
                break;
            case 's-profile':
                include "s-profile.php";
                break;
            default:
                include "menu-s.php"; 
                break;
        }
    } else {
        
        include "menu-s.php"; 
    }
    ?>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->
    <?php include "footer/sekretaris/footer.php"; ?>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
</body>
</html>

<!-- Ionicons CDN -->
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>


<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>


<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="assets/plugins/jszip/jszip.min.js"></script>
<script src="assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>


<script src="assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->





<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
      "searching": true,
      "buttons": ["print", "pdf"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>