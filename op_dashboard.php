
<!DOCTYPE html>
<html lang="en">
<meta name="theme-color" content="#f8fafa">
    <link rel="manifest" href="manifest.json">


<?php include "header/header.php"; ?>
<?php include "koneksi.php"; ?>

<body class="hold-transition sidebar-mini layout-fixed mt-5">
    <div class="mx-1">

    <!-- Preloader -->
    <?php include "header/loader.php"; ?>

    <!-- Navbar -->
    <?php include "navbar/op_navbar.php"; ?>
    <!-- /.navbar -->

    <br>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-primary elevation-0">
        <!-- Brand Logo -->
        <div class="mx-3 mt-1">
            <a href="" class="d-flex align-items-center" style="text-decoration: none;">
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
    <div class="content-wrapper bg-white">
        <!-- Main content -->
        <?php 
            // Cek apakah parameter 'page' ada di URL
            if (isset($_GET['page'])) {
                // Ambil nilai dari parameter 'page'
                $page = $_GET['page'];

                // Tentukan file yang akan dimasukkan berdasarkan nilai 'page'
                switch ($page) {
                    case 'menu-op':
                        include "menu-op.php";
                        break;
                    case 'daftar_bansos':
                        include "dashboard/daftar_bansos.php";
                        break;
                    case 'input_data':
                        include "dashboard/input_data.php";
                        break;
                    case 'rekapitulasi':
                        include "dashboard/rekapitulasi.php";
                        break;
                    case 'laporan_approv_o':
                        include "laporan_approv_o.php";
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
                    case 'op-profile':
                        include "op-profile.php";
                        break;
                    case 'history':
                        include "history.php";
                        break;
                    default:
                        include "menu-op.php"; 
                        break;
                }
            } else {
                
                include "menu-op.php"; 
            }
            ?>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
        <?php include "footer/operator/footer.php"; ?>
    
        <br>
    </div>


<!-- ./wrapper -->
</body>
</html>







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
    //   "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
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