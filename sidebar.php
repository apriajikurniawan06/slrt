<?php 
if (empty($_SESSION['username']) || empty($_SESSION['level']) || empty($_SESSION['nama_lengkap']) ) {
    echo "<script>alert('Maaf, Silahkan Login terlebih dahulu.');</script>";
    header("Location: index.php");
    exit();
}
include "koneksi.php";
?>


    <style>
        .sidebar {
            height: 100vh; /* Set tinggi sidebar sesuai dengan tinggi viewport */

            overflow-x: auto; /* Aktifkan scrollbar vertikal */
        }
    </style>


<div class="sidebar">
      <!-- Sidebar Menu -->
      <?php 

    // Cek level pengguna dan include file yang sesuai
    if ($_SESSION['level'] == 'fasilitator') {
        include "sidebar/fasilitator.php";
    } else if ($_SESSION['level'] == 'operator') {
        include "sidebar/operator.php";
    } else if ($_SESSION['level'] == 'koordinator') {
        include "sidebar/koordinator.php";
    } else if ($_SESSION['level'] == 'sekretaris') {
        include "sidebar/sekretaris.php";
    } else if ($_SESSION['level'] == 'manajer') {
        include "sidebar/manajer.php";
    } else {
        // Jika level tidak dikenali, bisa diarahkan ke halaman default atau error
        include "sidebar/default.php"; // atau bisa redirect ke halaman login
    }
?>
      <!-- /.sidebar-menu -->
    </div>