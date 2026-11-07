
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .navigation {
            z-index: 100;
            bottom: -5px;
        }
        .fixed-bottom ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .fixed-bottom ul li {
            flex: 1;
            text-align: center;
        }
        .fixed-bottom ul li a {
            display: block;
            padding: 5px;
            color: white; /* Warna ikon */
        }
        .fixed-bottom ul li a span {
            display: block;
            font-size: 30px; /* Ukuran ikon */

        }
       
    </style>

    <!-- NAVBAR BOTTOM -->
    <div class="navigation d-lg-none fixed-bottom bg-primary" style="bottom: 0px;">
        <ul class="d-flex justify-content-around">
            <li class="list">
                <a href="manajer.php?page=menu">
                    <span class="icon">
                        <ion-icon name="grid-outline"></ion-icon>
                    </span>
                    <span class="text-center" style="font-size: 15px;">Dashboard</span>
                </a>
            </li>
            <li class="list">
                <a href="manajer.php?page=daftar_bansos_m">
                    <span class="icon">
                        <ion-icon name="newspaper-outline"></ion-icon>
                    </span>
                    <span class="text-center" style="font-size: 15px;">Bansos</span>
                </a>
            </li>
            <li class="list">
                <a href="" onclick="alert('Maaf, Belum ada akses dihalaman ini!!!'); return false;">
                    <span class="icon">
                        <ion-icon name="apps-outline"></ion-icon>
                    </span>
                    <span class="text-center" style="font-size: 15px;">Add</span>
                </a>
            </li>
            <li class="list">
                <a href="manajer.php?page=member">
                    <span class="icon">
                        <ion-icon name="people-outline"></ion-icon>
                    </span>
                    <span class="text-center" style="font-size: 15px;">Member</span>
                </a>
            </li>
            <li class="list">
                <a href="manajer.php?page=laporan_approv_m">
                    <span class="icon">
                        <ion-icon name="checkmark-done-outline"></ion-icon>
                    </span>
                    <span class="text-center" style="font-size: 15px;">Approve</span>
                </a>
            </li>
        </ul>
    </div>

    <footer class="main-footer mb-5 text-center">
        <strong>&copy; <?= date('Y') ?> <a href="">SLRT Kito Kota Tanjungbalai</a>.</strong>
        <p class="">All rights reserved.</p>
        <div class="float-right d-none d-sm-inline-block">
        <!-- <b>Version</b> 3.2.0 -->
        </div>
    </footer>


     

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
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="assets/dist/js/demo.js"></script>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
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