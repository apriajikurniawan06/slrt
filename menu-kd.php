<?php

include "koneksi.php";
include_once "dashboard_stats.php";
$dashboard_stats = getDashboardStats($koneksi);
$total_hari_ini = $dashboard_stats['total_hari_ini'];
$total_seminggu = $dashboard_stats['total_seminggu'];
$total_bulan_ini = $dashboard_stats['total_bulan_ini'];
$total_keseluruhan = $dashboard_stats['total_keseluruhan'];
$service_totals = $dashboard_stats['service_totals'];
$chart_year = $dashboard_stats['chart_year'];
$available_years = $dashboard_stats['available_years'];
extract($dashboard_stats['chart_data'], EXTR_SKIP);

?>


<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    


<style>
    @keyframes installIconAnimation {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .install-container {
        position: fixed;
        top: 65px;
        width: 250px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.7);
        color: black;
        padding: 15px 10px;
        border-radius: 8px;
        cursor: pointer;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        font-size: 16px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
        backdrop-filter: blur(5px);
    }

    .install-container .logo1 {
        width: 35px;
        height: 35px;
    }

    .install-icon {
        width: 24px;
        height: auto;
        animation: installIconAnimation 1s infinite;
    }
</style>


<!-- TOTAL PENGUJUNG -->
<section class="bg-white mx-1" style="position: relative; top:0px;">
    <?php
    // Mengatur zona waktu ke Indonesia Barat
    date_default_timezone_set('Asia/Jakarta'); // Ganti dengan zona waktu yang sesuai jika diperlukan

    // Mendapatkan tanggal dan waktu saat ini
    $tanggal_sekarang = date('d-m-Y');
    $jam_sekarang = date('H:i:s');
    ?>

    <div class="d-flex justify-content-between align-items-center" style="position: relative; top: -20px;">
        <div class="card-body">
            <h5 class="text-primary bold mb-0">TOTAL PENGUNJUNG</h5>
        </div>
        <!-- <div class="card-body text-right">
            <span class="text-danger"><?= $tanggal_sekarang ?></span> |
            <span class="text-danger" id="jam"><?= $jam_sekarang ?></span>
        </div> -->
    </div>

    





    <div class="bg-white">
        <div class="row">
            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <h5 class=" description-header">HARI INI</h5>
                    <span class="description-text size-5"><?= $total_hari_ini ?></span>
                </div>
            </div>
            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <h5 class="description-header">SEMINGGU</h5>
                    <span class="description-text"><?= $total_seminggu ?></span>
                </div>
            </div>
            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <h5 class="description-header">BULAN INI</h5>
                    <span class="description-text"><?= $total_bulan_ini ?></span>
                </div>
            </div>
            <div class="col-sm-3 col-6">
                <div class="description-block">
                    <h5 class="description-header">SETAHUN</h5>
                    <span class="description-text"><?= $total_keseluruhan ?></span>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- TOTAL PENGUJUNG -->
 <hr class="bg-dark mx-5">

<br>
<br>

<div class="mx-1">
    <div class="row">
        <div class="col-lg-2 col-6">
            <div class="small-box">
                <div class="inner">
                    <h3><?= $service_totals['CEK BANSOS']; ?></h3>
                    <p>CEK BANSOS</p>
                </div>
                <div class="icon">
                    <i class="fas fa-search text-info"></i>
                </div>
                <a href="kd_dashboard.php?page=daftar_bansos_kd" class="small-box-footer bg-info" style="z-index: 0;"><span class="text-white">Detail</span> <i class="fas fa-arrow-circle-right text-white"></i></a>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box">
                <div class="inner">
                    <h3><?= $service_totals['DTSEN']; ?></h3>
                    <p>DTSEN</p>
                </div>
                <div class="icon">
                    <i class="ion ion-document-text text-warning"></i>
                </div>
                <a href="kd_dashboard.php?page=data_dtsen" class="small-box-footer bg-warning" style="z-index: 0;"><span class="text-white">Detail</span> <i class="fas fa-arrow-circle-right text-white"></i></a>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box">
                <div class="inner">
                    <h3><?= $service_totals['DESIL']; ?></h3>
                    <p>DESIL</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-bar text-secondary"></i>
                </div>
                <a href="kd_dashboard.php?page=daftar_bansos_kd" class="small-box-footer bg-secondary" style="z-index: 0;"><span class="text-white">Detail</span> <i class="fas fa-arrow-circle-right text-white"></i></a>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box">
                <div class="inner">
                    <h3><?= $service_totals['BPNT']; ?></h3>
                    <p>BPNT</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars text-primary"></i>
                </div>
                <a href="kd_dashboard.php?page=data_bpnt" class="small-box-footer bg-primary" style="z-index: 0;"><span class="text-white">Detail</span> <i class="fas fa-arrow-circle-right text-white"></i></a>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box">
                <div class="inner">
                    <h3><?= $service_totals['PKH']; ?></h3>
                    <p>PKH</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-people text-danger"></i>
                </div>
                <a href="kd_dashboard.php?page=data_pkh" class="small-box-footer bg-danger" style="z-index: 0;"><span class="text-white">Detail</span> <i class="fas fa-arrow-circle-right text-white"></i></a>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-white">
                <div class="inner">
                    <h3><?= $service_totals['BPJS']; ?></h3>
                    <p>BPJS</p>
                </div>
                <div class="icon">
                    <i class="ion ion-medkit text-success"></i>
                </div>
                <a href="kd_dashboard.php?page=data_bpjs" class="small-box-footer bg-success" style="z-index: 0;"><span class="text-white">Detail</span> <i class="fas fa-arrow-circle-right text-white"></i></a>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="mb-2 mx-1">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center py-3">
            <h6 class="m-0 mb-2 mb-md-0 font-weight-bold text-primary">Statistik Pelayanan SLRT Tahun <?= $chart_year ?></h6>
            <div class="btn-group flex-wrap" role="group" aria-label="Pilih tahun grafik">
                <?php foreach ($available_years as $available_year): ?>
                    <?php
                    $year_query = $_GET;
                    $year_query['chart_year'] = $available_year;
                    $year_url = '?' . http_build_query($year_query);
                    ?>
                    <a href="<?= htmlspecialchars($year_url, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm <?= $available_year === $chart_year ? 'btn-primary' : 'btn-outline-primary' ?>" aria-pressed="<?= $available_year === $chart_year ? 'true' : 'false' ?>"><?= $available_year ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: <?= json_encode($data_bulan) ?>,
              datasets: [
                                {
                                        label: 'CEK BANSOS',
                                        data: <?= json_encode($data_cek_bansos) ?>,
                                        backgroundColor: '#59c8c5',
                                        borderColor: '#59c8c5',
                                        borderWidth: 1
                                },
                {
                    label: '    DTSEN',
                    data: <?= json_encode($data_dtsen) ?>,
                    backgroundColor: '#ffcc00',
                    borderColor: '#ffcc00',
                    borderWidth: 1
                },
                {
                    label: 'DESIL',
                    data: <?= json_encode($data_desil) ?>,
                    backgroundColor: '#9da5ad',
                    borderColor: '#9da5ad',
                    borderWidth: 1
                },
                {
                  label: 'BPNT',
                  data: <?= json_encode($data_bpnt) ?>,
                  backgroundColor: '#006eff',
                  borderColor: '#006eff',
                  borderWidth: 1
                },
                {
                  label: 'PKH',
                        data: <?= json_encode($data_pkh) ?>,
                        backgroundColor: '#ce0000',
                        borderColor: '#ce0000',
                        borderWidth: 1
                    },
                    {
                        label: 'BPJS',
                        data: <?= json_encode($data_bpjs) ?>,
                        backgroundColor: '#07ce00d3',
                        borderColor: '#07ce00d3',
                        borderWidth: 1
                    }
                ]
            },
            
        });
    </script>
    <!-- CHART -->
</div>

<br>
<br>
<br>



<script>

    let deferredPrompt;

    window.addEventListener("beforeinstallprompt", (e) => {
        e.preventDefault();
        deferredPrompt = e; // Simpan event untuk dipakai nanti

        // Tampilkan pop-up setelah 2 detik (bisa diatur)
        setTimeout(() => {
            const installPopup = document.createElement("div");
            installPopup.id = "installPWA";
            installPopup.innerHTML = `
                <div class="install-container">
                    <img src="icons/slrt.png" class="logo1">
                    Install Aplikasi SLRT
                    <img src="image/install.png" class="install-icon">
                </div>
            `;
            document.body.appendChild(installPopup);

            // Klik untuk memunculkan prompt install
            installPopup.addEventListener("click", () => {
                installPopup.style.display = "none"; // Sembunyikan pop-up setelah diklik
                deferredPrompt.prompt();

                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === "accepted") {
                        console.log("User accepted the install");
                    } else {
                        console.log("User dismissed the install");
                    }
                    deferredPrompt = null;
                });
            });

            // Hapus pop-up setelah 10 detik
            setTimeout(() => {
                installPopup.style.display = "none";
            }, 15000);

        }, 2000); // Delay 2 detik sebelum muncul
    });



    if ("serviceWorker" in navigator) {
        navigator.serviceWorker.register("/sw.js")
            .then((reg) => console.log("Service Worker Registered!", reg))
            .catch((err) => console.log("Service Worker Registration Failed!", err));
    }
</script>



