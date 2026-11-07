
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
  <?php include "navbar/kd_navbar.php"; ?>
  <!-- /.navbar -->

  <br>
   <!-- Main Sidebar Container -->
   <aside class="main-sidebar sidebar-light-primary elevation-0">
    <!-- Brand Logo -->
    <div class="mx-3 mt-1">
        <a href="" class="d-flex align-items-center">
            <img src="logo/slrt.png" class="me-1" width="50px" height="50px">
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
  <div class="content-wrapper bg-white">
    <!-- Main content -->
    <?php 
// Cek apakah parameter 'page' ada di URL
if (isset($_GET['page'])) {
    // Ambil nilai dari parameter 'page'
    $page = $_GET['page'];

    // Tentukan file yang akan dimasukkan berdasarkan nilai 'page'
    switch ($page) {
        case 'menu-kd':
            include "menu-kd.php";
            break;
        case 'daftar_bansos_kd':
            include "daftar_bansos_kd.php";
            break;
        case 'member':
            include "member.php";
            break;
        case 'laporan_approv_kd':
            include "laporan_approv_kd.php"; 
            break;
        case 'kd-profile':
            include "kd-profile.php"; 
            break;
        case 'history':
            include "history.php";
            break;
        case 'data_dtsen':
            include "data_dtsen.php";
            break;
        case 'data_bpnt':
            include "data_bpnt.php";
            break;
        case 'data_pkh':
            include "data_pkh.php";
            break;
        case 'data_bpjs':
            include "data_bpjs.php";
            break;
    }
} else {
    
    include "menu-kd.php"; 
}
?>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->
    <?php include "footer/koordinator/footer.php"; ?>
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">


<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
<!-- Tambahkan FontAwesome untuk ikon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

                   

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
 

<link rel="stylesheet" href="assets/dist/css/adminlte.min.css">




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