


<style>
  .scroll {
    max-height: 120px; /* Limit height of the container */
    display: flex; /* Use flexbox for horizontal layout */
    overflow-x: auto; /* Allow horizontal scrolling */
    overflow-y: hidden; /* Hide vertical overflow */
    position: fixed; /* Fix position to viewport */
    bottom: 45px; /* Position from the top */
    left: 0; /* Align to the left */
    right: 0; /* Align to the right */
    z-index: 10; /* Ensure it's on top of other elements */
}

.scroll::-webkit-scrollbar {
    height: 5px; /* Height of the scrollbar */
}

/* Customize scrollbar */
.scroll::-webkit-scrollbar-thumb {
    background: #fff; /* Color of the scrollbar handle */
    border-radius: 10px; /* Rounded edges */
}

.scroll ul {
    display: flex; /* Make the ul element a flex container */
    padding: 0; /* Remove default padding */
    margin: 0; /* Remove default margin */
    list-style: none; /* Remove default list styles */
}

.item {
    min-width: 150px; /* Minimum width for each item */
    height: 30px; /* Height of each item */
    line-height: 140px; /* Center text vertically */
    text-align: center; /* Center text horizontally */
    background-color: #ddd; /* Background color */
    margin-right: 4px; /* Space between items */
    border-radius: 30px;
    border: 1px solid #a7a7a7;
}

.item a {
    text-decoration: none; /* Remove underline from links */
    color: black; /* Link text color */
    bottom: 56px;
    position: relative;
}




</style>



  


<div class="scroll d-lg-none"> 
    <ul>
        <li class="item">
            <a href="fs_dashboard.php?page=fs_layanan">Layanan SLRT</a>
        </li>
        <li class="item">
            <a href="fs_dashboard.php?page=fs_cek_bansos">Cek Bansos</a>
        </li>
        <!-- <li class="item">
            <a href="fs_dashboard.php?page=fs_usulan_baru">Usulan Baru</a>
        </li> -->
        <li class="item">
            <a href="fs_dashboard.php?page=fs_bbl">BBL</a>
        </li>
        <li class="item">
            <a href="fs_dashboard.php?page=fs_kelayakan">Kelayakan</a>
        </li>
        <li class="item">
            <a href="fs_dashboard.php?page=fs_tugas_tambahan">Tugas Tambahan</a>
        </li>
    </ul>
</div>

    <!-- FOOTER -->


<style>




  .navigation {
    z-index: 100;
    margin-bottom: -18px;
  }
 
  .fixed-bottom ul li a span {
    position: relative;
    bottom: -10px;
  }
  .fixed-bottom ul li a span ion-icon {
    font-size: 24px;
    color: white;
    position: relative;
    bottom: 5px;
  }
  .fixed-bottom ul li a span ion-icon:hover{
    color: orange;
  }
  .fixed-bottom ul li a .text {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 13px;
    top: -3px;
  }

</style>
    <!-- FOOTER -->



  <!-- NAVBAR BOTTOM -->
  <div class="navigation d-lg-none fixed-bottom bg-primary" style="bottom: 0px;">
    <ul class="list-unstyled d-flex justify-content-around">
        <li class="list">
            <a href="fs_dashboard.php?page=menu">
                <span class="icon">
                    <ion-icon name="grid-outline"></ion-icon>
                </span>
                <span class="text">Dashboard</span> <!-- Teks tambahan -->
            </a>
        </li>
        <li class="list">
            <a href="fs_dashboard.php?page=fs_laporan">
                <span class="icon">
                    <ion-icon name="newspaper-outline"></ion-icon>
                </span>
                <span class="text">Laporan</span> <!-- Teks tambahan -->
            </a>
        </li>
        
        <li class="list">
            <a href="fs_dashboard.php?page=fs_laporan_persetujuan">
                    <span class="icon">
                      <ion-icon name="checkmark-done-outline"></ion-icon>
                    </span>
                    <span class="text">Approve</span>
            </a>
        </li>
        <li class="list">
            <a href="fs_dashboard.php?page=fs_daftar_bansos">
                <span class="icon">
                    <ion-icon name="reader-outline"></ion-icon>
                </span>
                <span class="text">Bansos</span> <!-- Teks tambahan -->
            </a>
        </li>
        
        <li class="list">
            <a href="fs_dashboard.php?page=fs_registrasi">
                <span class="icon">
                    <ion-icon name="pencil-outline"></ion-icon>
                </span>
                <span class="text">Registrasi</span> <!-- Teks tambahan -->
            </a>
        </li>
    </ul>
</div>

<style>
    .list {
        text-align: center; /* Center text under icons */
    }

    .text {
        display: block; /* Make text a block element */
        font-size: 12px; /* Font size for the text */
        color: #fff; /* Text color */
        margin-top: 0px; /* Space between icon and text */
    }
</style>
<br>

<!-- <footer class="main-footer mb-3 text-center mt-5">
    <strong>&copy; <?= date('Y') ?> <a href="">SLRT Kito Kota Tanjungbalai</a>.</strong>
    <p class="">All rights reserved.</p>
    <div class="float-right d-none d-sm-inline-block">
    </div>
</footer> -->


<script src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js'></script>

  



