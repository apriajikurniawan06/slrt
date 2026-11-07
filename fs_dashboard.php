

<style></style>
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
  <?php include "navbar/fs_navbar.php"; ?>
  <!-- /.navbar -->

  <br>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary elevation-0">
    <!-- Brand Logo -->
    <div class="mx-2 mt-1">
        <a href="" class="mx-3 d-flex align-items-center" style="text-decoration:none;">
            <img src="logo/slrt.png" class="me-3" width="50px" height="50px">
            <div class="">
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
  <div class="content-wrapper bg-white" style="position: relative; top: 0px;">
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
                case 'fs_registrasi':
                    include "fs_registrasi.php";
                    break;
                case 'fs_laporan':
                    include "fs_laporan.php";
                    break;
                case 'fs_laporan_persetujuan':
                    include "fs_laporan_persetujuan.php";
                    break;
                case 'fs_daftar_bansos':
                    include "fs_daftar_bansos.php";
                    break;
                case 'fs_layanan':
                    include "fs_layanan.php";
                    break;
                case 'fs_cek_bansos':
                    include "fs_cek_bansos.php";
                    break;
                case 'fs_usulan_baru':
                    include "fs_usulan_baru.php";
                    break;
                case 'fs_bbl':
                    include "fs_bbl.php";
                    break;
                case 'fs_kelayakan':
                    include "fs_kelayakan.php";
                    break;
                case 'fs_tugas_tambahan':
                    include "fs_tugas_tambahan.php";
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
                case 'fs-profile':
                    include "fs-profile.php";
                    break;
                case 'fs_cetak':
                    include "fs_cetak.php";
                    break;
                case 'signatur':
                    include "signatur.php";
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
</div>

<br>

  <!-- /.content-wrapper -->
  <div class="footer mb-5" style="position: relative; top: 130px;">
    <?php include "footer/fasilitator/footer.php"; ?>
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


<!-- Page specific script -->





<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
      "searching": true,
    //   "buttons": ["print", "pdf"]
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