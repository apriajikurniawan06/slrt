
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .navigation {
            z-index: 100;
            margin-bottom: -22px;
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
            padding: 10px;
            color: white; /* Warna ikon */
        }
        .fixed-bottom ul li a span {
            display: block;
        }
        .fixed-bottom ul li a span ion-icon {
            font-size: 24px;
            color: white;
            position: relative;
            bottom: 5px;
        }
        .fixed-bottom ul li a .text-center {
            font-size: 13px;
            position: relative;
            bottom: 13px;
        }
       
    </style>


    <!-- NAVBAR BOTTOM -->
    <div class="navigation d-lg-none fixed-bottom bg-primary" style="bottom: 0px;">
        <ul class="d-flex justify-content-around">
            <li class="list">
                <a href="sekretaris.php?page=menu">
                    <span class="icon">
                        <ion-icon name="grid-outline"></ion-icon>
                    </span>
                    <span class="text-center">Dashboard</span>
                </a>
            </li>
            <li class="list">
                <a href="sekretaris.php?page=daftar_bansos_s">
                    <span class="icon">
                        <ion-icon name="newspaper-outline"></ion-icon>
                    </span>
                    <span class="text-center">Bansos</span>
                </a>
            </li>
            <!-- <li class="list">
                <a href="" onclick="alert('Maaf, Belum ada akses dihalaman ini!!!'); return false;">
                    <span class="icon">
                        <ion-icon name="apps-outline"></ion-icon>
                    </span>
                    <span class="text-center">Add</span>
                </a>
            </li> -->
            <li class="list">
                <a href="sekretaris.php?page=member">
                    <span class="icon">
                        <ion-icon name="people-outline"></ion-icon>
                    </span>
                    <span class="text-center">Member</span>
                </a>
            </li>
            <li class="list">
                <a href="sekretaris.php?page=laporan_approv_s">
                    <span class="icon">
                        <ion-icon name="shield-checkmark-outline"></ion-icon>
                    </span>
                    <span class="text-center">Approve</span>
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

    <script src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js'></script>
    
