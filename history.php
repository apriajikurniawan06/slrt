<?php
include 'koneksi.php'; // Pastikan file koneksi benar
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelayanan</title>

<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fall back"> 
<!-- Font Awesome -->
<link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">


</head>
<body class="bg-white">

<div class="container mt-4">
    <h2 class="text-center">DAFTAR PELAYANAN</h2> 
    <br>

    <div class="row mb-3 mx-5">
        <div class="col-md-3">
            <input type="date" id="tgl-awal" class="form-control mb-2">
        </div>
        <div class="col-md-3">
            <input type="date" id="tgl-akhir" class="form-control mb-2">
        </div>
        <div class="col-md-3">
            <input type="text" id="search" class="form-control mb-2" placeholder="Cari nama Fasilitator">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button id="filter" class="btn btn-primary mb-3"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M440-160q-17 0-28.5-11.5T400-200v-240L168-736q-15-20-4.5-42t36.5-22h560q26 0 36.5 22t-4.5 42L560-440v240q0 17-11.5 28.5T520-160h-80Zm40-308 198-252H282l198 252Zm0 0Z"/></svg></button>
        </div>
    </div>

    <br>
    
    <table id="dataTable" class="table table-bordered table-striped dt-responsive nowrap" width="100%">
        <thead class="table-white">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Tanggal</th>
                <th>NIK</th>
                <th>Nama LENGKAP</th>
                <th>Alamat</th>
                <th>Jenis Layanan</th>
                <th>NAMA FASILITATOR</th>
            </tr>
        </thead>
        <tbody id="table-body">
            <?php include 'fetch_data.php'; ?>
        </tbody>
    </table>
</div>

<!-- jQuery & Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables & Plugins -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function () {
        var table = $("#dataTable").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            dom: 'Bfrtip',
            buttons: [
               'print', 'pdf'
            ],
            language: {
                search: '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2854C5"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>',
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "→",
                    previous: "←"
                }
            }
        });

        $("#filter").click(function () {
            var tglAwal = $("#tgl-awal").val();
            var tglAkhir = $("#tgl-akhir").val();
            var search = $("#search").val().trim();

            if ((tglAwal && !tglAkhir) || (!tglAwal && tglAkhir)) {
                alert("Isi tanggal awal dan tanggal akhir.");
                return;
            }

            if (tglAwal && tglAkhir && tglAwal > tglAkhir) {
                alert("Tanggal awal tidak boleh lebih besar dari tanggal akhir.");
                return;
            }

            $.ajax({
                url: "fetch_data.php",
                type: "POST",
                data: {
                    tgl_awal: tglAwal,
                    tgl_akhir: tglAkhir,
                    search: search
                },
                success: function (response) {
                    table.clear().destroy();
                    $("#table-body").html(response);
                    table = $("#dataTable").DataTable({
                        responsive: true,
                        lengthChange: true,
                        autoWidth: false,
                        dom: 'Bfrtip',
                        buttons: [
                            'print', 'pdf'
                        ],
                        language: {
                            // search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ data",
                            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                            infoEmpty: "Tidak ada data tersedia",
                            paginate: {
                                first: "Pertama",
                                last: "Terakhir",
                                next: "→",
                                previous: "←"
                            }
                        }
                    });
                },
                error: function (xhr) {
                    alert(xhr.responseText || "Data gagal difilter.");
                }
            });
        });

        $("#search").on("keydown", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $("#filter").trigger("click");
            }
        });
    });
</script>





</body>
</html>
