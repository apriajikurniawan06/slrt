<?php 
include "koneksi.php";

// Membuat koneksi
$koneksi = mysqli_connect($server, $user, $password, $database);

// Periksa koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}  
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi BANSOS</title>
    <meta name="theme-color" content="#f8fafa">
    <link rel="manifest" href="manifest.json">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    

    
    <style>
        
    /* Style umum untuk semua perangkat */
    .export-print {
        position: fixed;
        top: 500px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        width: auto;
    }

    .export-print button i {
        font-size: 20px;
    }

    /* Responsif untuk layar kecil (mobile) */
    @media (max-width: 576px) {
        .export-print {
            width: 90%;
            bottom: 25px;
            top: auto;
        }
    }

    /* Responsif untuk layar tablet */
    @media (min-width: 577px) and (max-width: 768px) {
        .export-print {
            width: 70%;
            bottom: 25px;
            top: auto;
        }
    }

    /* Responsif untuk layar laptop */
    @media (min-width: 769px) and (max-width: 1025px) {
        .export-print {
            width: 50%;
            top: 560px;
        }
    }

    /* Responsif untuk layar lebih besar (desktop/PC) */
    @media (min-width: 1026px) {
        .export-print {
            width: auto;
            top: 600px;
        }

    @media (max-width: 1232px){
        .export-print {
            width: auto;
            top: 620px;
        }
    }
    }

    /* Style untuk cetak */
    @media print {
        @page {
            margin: 0.5cm;
            size: landscape;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.2;
            background: white !important;
        }
        
        .no-print {
            display: none !important;
        }
        
        .export-print {
            display: none !important;
        }
        
        .table {
            border-collapse: collapse;
            width: 100%;
            font-size: 9px;
        }
        
        .table th,
        .table td {
            padding: 4px 6px;
            border: 1px solid #000;
        }
        
        .table th {
            background-color: #f8f9fa !important;
            color: #000 !important;
            font-weight: bold;
            text-align: center;
        }
        
        .header-print {
            display: block !important;
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        
        .footer-print {
            display: block !important;
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #666;
        }
    }

    /* Style untuk tampilan layar */
    .table-responsive {
        max-height: 500px;
        overflow: auto;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }
    
    .table th {
        
        top: 0;
        background: #343a40;
        color: white;
        z-index: 1;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }

    .print-header {
        display: none;
    }
</style>
</head>
<body>

<div class="bg-white" style="position: relative; top:0px;">
    <div class="mx-4">
        <form action="" method="POST">
            <h5 class="text-center mb-4">REKAPITULASI PENDAFTAR BANSOS</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal1" class="form-label fw-bold">Dari Tanggal</label>
                        <input type="date" class="form-control" name="tanggal1" value="<?= isset($_POST['tanggal1']) ? $_POST['tanggal1'] : date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal2" class="form-label fw-bold">Sampai Tanggal</label>
                        <input type="date" class="form-control" name="tanggal2" value="<?= isset($_POST['tanggal2']) ? $_POST['tanggal2'] : date('Y-m-d') ?>" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <button type="submit" class="btn btn-primary" name="btampilkan">
                        <i class="fa fa-search me-2"></i> Tampilkan Data
                    </button>
                </div>
            </div>
        </form>

        <?php 
            if (isset($_POST['btampilkan'])) {
                $tgl1 = $_POST['tanggal1'];
                $tgl2 = $_POST['tanggal2'];

                $tampil = mysqli_query($koneksi, "SELECT * FROM registrasi WHERE tanggal BETWEEN '$tgl1' AND '$tgl2' ORDER BY id DESC");
                $total_data = mysqli_num_rows($tampil);
        ?>

        <!-- Header untuk cetak (akan muncul saat print) -->
        <div class="print-header">
            <h4 style="margin: 0; font-weight: bold; text-align: center;">DAFTAR REGISTRASI BANSOS</h4>
            <p style="margin: 5px 0 0 0; font-size: 12px; text-align: center;">
                Periode: <?= date('d-m-Y', strtotime($tgl1)) . ' s/d ' . date('d-m-Y', strtotime($tgl2)) ?>
            </p>
            <p style="margin: 5px 0 0 0; font-size: 12px; text-align: center;">
                Dicetak pada: <?= date('d-m-Y H:i:s') ?>
            </p>
        </div>

 

        <div class="mx-1 mb-5">
            <div class="table-container mt-3">
                <table class="table table-bordered table-hover table-striped" id="example1">
                    <thead>
                        <tr>
                            <th width="40">NO</th>
                            <th width="90">TANGGAL</th>
                            <th width="150">NAMA PENGURUS</th>
                            <th width="120">NOMOR KK</th>
                            <th width="100">KECAMATAN</th>
                            <th width="100">KELURAHAN</th>
                            <th width="150">ALAMAT</th>
                            <th width="120">NIK</th>
                            <th width="90">TGL LAHIR</th>
                            <th width="100">TEMPAT LAHIR</th>
                            <th width="80">JENIS KEL</th>
                            <th width="80">AGAMA</th>
                            <th width="100">PENDIDIKAN</th>
                            <th width="100">PEKERJAAN</th>
                            <th width="60">FC KTP</th>
                            <th width="60">FC KK</th>
                            <th width="60">SKTM</th>
                            <th width="100">STATUS RUMAH</th>
                            <th width="60">MCK</th>
                            <th width="80">PENERANGAN</th>
                            <th width="80">STATUS DTSEN</th>
                            <th width="120">NAMA IBU KANDUNG</th>
                            <th width="120">BANSOS DIPEROLEH</th>
                            <th width="100">PENGADUAN</th>
                            <th width="100">PELAYANAN</th>
                            <th width="100">PENGHASILAN</th>
                            <th width="100">NOMOR TLP</th>
                            <th width="120">NAMA FASILITATOR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            while ($data = mysqli_fetch_array($tampil)) {
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= date('d-m-Y', strtotime($data['tanggal'])) ?></td>
                            <td><?= htmlspecialchars($data['nama_pengurus_bansos']) ?></td>
                            <td><?= htmlspecialchars($data['nomor_kk']) ?></td>
                            <td><?= htmlspecialchars($data['kecamatan']) ?></td>
                            <td><?= htmlspecialchars($data['kelurahan']) ?></td>
                            <td><?= htmlspecialchars($data['alamat']) ?></td>
                            <td><?= htmlspecialchars($data['nik_kepala_keluarga']) ?></td>
                            <td><?= date('d-m-Y', strtotime($data['tgl_lahir'])) ?></td>
                            <td><?= htmlspecialchars($data['tpt_lahir']) ?></td>
                            <td><?= htmlspecialchars($data['jenis_k']) ?></td>
                            <td><?= htmlspecialchars($data['agama_kepala_keluarga']) ?></td>
                            <td><?= htmlspecialchars($data['pendidikan_kepala']) ?></td>
                            <td><?= htmlspecialchars($data['pekerjaan']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($data['fc_ktp']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($data['fc_kk']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($data['sktm']) ?></td>
                            <td><?= htmlspecialchars($data['status_rumah']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($data['mck']) ?></td>
                            <td><?= htmlspecialchars($data['penerangan']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($data['status_dtsen']) ?></td>
                            <td><?= htmlspecialchars($data['nama_ibu_kandung']) ?></td>
                            <td><?= htmlspecialchars($data['bansos_diperoleh']) ?></td>
                            <td><?= htmlspecialchars($data['pengaduan']) ?></td>
                            <td><?= htmlspecialchars($data['pelayanan']) ?></td>
                            <td>Rp <?= number_format($data['penghasilan'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($data['nomor_tlp']) ?></td>
                            <td><?= htmlspecialchars($data['nama_fasilitator']) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($total_data == 0): ?>
            <div class="alert alert-warning text-center mt-3">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Tidak ada data ditemukan untuk periode yang dipilih.
            </div>
            <?php endif; ?>
        </div>

<br>
<br>
      
        <!-- Tombol Export & Print -->
        <div class="text-center export-print no-print">
            <form action="dashboard/exportexcel.php" method="POST" style="display: inline;">
                <input type="hidden" name="tanggala" value="<?= htmlspecialchars($_POST['tanggal1']) ?>">
                <input type="hidden" name="tanggalb" value="<?= htmlspecialchars($_POST['tanggal2']) ?>">
                <button class="btn btn-success mb-4" name="bexport" title="Export ke Excel">
                    <i class="fas fa-file-excel"></i>
                </button>
            </form>
            
            <button type="button" class="btn btn-danger mb-4" onclick="cetakLaporan()" title="Cetak Laporan">
                <i class="fas fa-file-pdf"></i>
            </button>
            
            <!-- <button type="button" class="btn btn-info mb-4" onclick="simpanPDF()" title="Simpan sebagai PDF">
                <i class="fas fa-file-pdf me-2"></i>
            </button> -->
        </div>

        <?php } ?>
    </div>
</div>

<script>
function cetakLaporan() {
    // Buat window baru untuk cetak
    const printWindow = window.open('', '_blank');
    
    // Ambil data dari PHP
    const tanggal1 = '<?= isset($_POST['tanggal1']) ? $_POST['tanggal1'] : '' ?>';
    const tanggal2 = '<?= isset($_POST['tanggal2']) ? $_POST['tanggal2'] : '' ?>';
    
    // Buat konten HTML untuk cetak dengan struktur yang sama
    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Cetak Rekapitulasi BANSOS</title>
            <style>
                @page {
                    margin: 0.5cm;
                    size: landscape;
                }
                body {
                    margin: 0;
                    padding: 20px;
                    font-family: Arial, sans-serif;
                    font-size: 10px;
                    background: white;
                    line-height: 1.2;
                }
                .print-header {
                    text-align: center;
                    margin-bottom: 15px;
                    padding-bottom: 10px;
                    border-bottom: 2px solid #000;
                }
                .print-header h4 {
                    margin: 0 0 5px 0;
                    font-size: 16px;
                    font-weight: bold;
                }
                .print-header p {
                    margin: 2px 0;
                    font-size: 11px;
                }
                .table-container {
                    width: 100%;
                    overflow: hidden;
                }
                .table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 7px;
                    table-layout: fixed;
                }
                .table th {
                    background-color: #f0f0f0;
                    font-weight: bold;
                    text-align: center;
                    border: 1px solid #000;
                    padding: 3px 2px;
                    vertical-align: middle;
                }
                .table td {
                    border: 1px solid #000;
                    padding: 2px 2px;
                    text-align: left;
                    vertical-align: middle;
                    word-wrap: break-word;
                    overflow: hidden;
                }
                /* Lebar kolom sesuai permintaan */
                .table th:nth-child(1), .table td:nth-child(1) { width: 30px; text-align: center; }  /* NO */
                .table th:nth-child(2), .table td:nth-child(2) { width: 70px; }  /* TANGGAL */
                .table th:nth-child(3), .table td:nth-child(3) { width: 120px; } /* NAMA PENGURUS */
                .table th:nth-child(4), .table td:nth-child(4) { width: 100px; } /* NOMOR KK */
                .table th:nth-child(5), .table td:nth-child(5) { width: 80px; } /* KECAMATAN */
                .table th:nth-child(6), .table td:nth-child(6) { width: 80px; } /* KELURAHAN */
                .table th:nth-child(7), .table td:nth-child(7) { width: 120px; } /* ALAMAT */
                .table th:nth-child(8), .table td:nth-child(8) { width: 100px; } /* NIK */
                .table th:nth-child(9), .table td:nth-child(9) { width: 70px; }  /* TGL LAHIR */
                .table th:nth-child(10), .table td:nth-child(10) { width: 80px; } /* TEMPAT LAHIR */
                .table th:nth-child(11), .table td:nth-child(11) { width: 60px; text-align: center; } /* JENIS KEL */
                .table th:nth-child(12), .table td:nth-child(12) { width: 60px; } /* AGAMA */
                .table th:nth-child(13), .table td:nth-child(13) { width: 80px; } /* PENDIDIKAN */
                .table th:nth-child(14), .table td:nth-child(14) { width: 80px; } /* PEKERJAAN */
                .table th:nth-child(15), .table td:nth-child(15) { width: 50px; text-align: center; } /* FC KTP */
                .table th:nth-child(16), .table td:nth-child(16) { width: 50px; text-align: center; } /* FC KK */
                .table th:nth-child(17), .table td:nth-child(17) { width: 50px; text-align: center; } /* SKTM */
                .table th:nth-child(18), .table td:nth-child(18) { width: 80px; } /* STATUS RUMAH */
                .table th:nth-child(19), .table td:nth-child(19) { width: 50px; text-align: center; } /* MCK */
                .table th:nth-child(20), .table td:nth-child(20) { width: 60px; } /* PENERANGAN */
                .table th:nth-child(21), .table td:nth-child(21) { width: 60px; text-align: center; } /* STATUS DTKS */
                .table th:nth-child(22), .table td:nth-child(22) { width: 100px; } /* NAMA IBU KANDUNG */
                .table th:nth-child(23), .table td:nth-child(23) { width: 100px; } /* BANSOS DIPEROLEH */
                .table th:nth-child(24), .table td:nth-child(24) { width: 80px; } /* PENGADUAN */
                .table th:nth-child(25), .table td:nth-child(25) { width: 80px; } /* PELAYANAN */
                .table th:nth-child(26), .table td:nth-child(26) { width: 80px; } /* PENGHASILAN */
                .table th:nth-child(27), .table td:nth-child(27) { width: 80px; } /* NOMOR TLP */
                .table th:nth-child(28), .table td:nth-child(28) { width: 100px; } /* NAMA FASILITATOR */
                
                .table-striped tbody tr:nth-child(odd) {
                    background-color: #f9f9f9;
                }
                .text-center {
                    text-align: center;
                }
                .footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 9px;
                    color: #666;
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                }
                /* Untuk memastikan teks tidak overflow */
                .table td {
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h4>DAFTAR REGISTRASI BANSOS</h4>
                <p>Periode: ${tanggal1 ? new Date(tanggal1).toLocaleDateString('id-ID') : ''} s/d ${tanggal2 ? new Date(tanggal2).toLocaleDateString('id-ID') : ''}</p>
                <p>Dicetak pada: ${new Date().toLocaleDateString('id-ID')} ${new Date().toLocaleTimeString('id-ID')}</p>
            </div>
            
            <div class="table-container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>TANGGAL</th>
                            <th>NAMA PENGURUS</th>
                            <th>NOMOR KK</th>
                            <th>KECAMATAN</th>
                            <th>KELURAHAN</th>
                            <th>ALAMAT</th>
                            <th>NIK</th>
                            <th>TGL LAHIR</th>
                            <th>TEMPAT LAHIR</th>
                            <th>JENIS KEL</th>
                            <th>AGAMA</th>
                            <th>PENDIDIKAN</th>
                            <th>PEKERJAAN</th>
                            <th>FC KTP</th>
                            <th>FC KK</th>
                            <th>SKTM</th>
                            <th>STATUS RUMAH</th>
                            <th>MCK</th>
                            <th>PENERANGAN</th>
                            <th>STATUS DTKS</th>
                            <th>NAMA IBU KANDUNG</th>
                            <th>BANSOS DIPEROLEH</th>
                            <th>PENGADUAN</th>
                            <th>PELAYANAN</th>
                            <th>PENGHASILAN</th>
                            <th>NOMOR TLP</th>
                            <th>NAMA FASILITATOR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_POST['btampilkan'])) {
                            $tgl1 = $_POST['tanggal1'];
                            $tgl2 = $_POST['tanggal2'];
                            $tampil = mysqli_query($koneksi, "SELECT * FROM registrasi WHERE tanggal BETWEEN '$tgl1' AND '$tgl2' ORDER BY id DESC");
                            $no = 1;
                            
                            while ($data = mysqli_fetch_array($tampil)) {
                                echo "
                                <tr>
                                    <td class='text-center'>{$no}</td>
                                    <td>" . date('d-m-Y', strtotime($data['tanggal'])) . "</td>
                                    <td>" . htmlspecialchars($data['nama_pengurus_bansos']) . "</td>
                                    <td>" . htmlspecialchars($data['nomor_kk']) . "</td>
                                    <td>" . htmlspecialchars($data['kecamatan']) . "</td>
                                    <td>" . htmlspecialchars($data['kelurahan']) . "</td>
                                    <td>" . htmlspecialchars($data['alamat']) . "</td>
                                    <td>" . htmlspecialchars($data['nik_kepala_keluarga']) . "</td>
                                    <td>" . date('d-m-Y', strtotime($data['tgl_lahir'])) . "</td>
                                    <td>" . htmlspecialchars($data['tpt_lahir']) . "</td>
                                    <td class='text-center'>" . htmlspecialchars($data['jenis_k']) . "</td>
                                    <td>" . htmlspecialchars($data['agama_kepala_keluarga']) . "</td>
                                    <td>" . htmlspecialchars($data['pendidikan_kepala']) . "</td>
                                    <td>" . htmlspecialchars($data['pekerjaan']) . "</td>
                                    <td class='text-center'>" . htmlspecialchars($data['fc_ktp']) . "</td>
                                    <td class='text-center'>" . htmlspecialchars($data['fc_kk']) . "</td>
                                    <td class='text-center'>" . htmlspecialchars($data['sktm']) . "</td>
                                    <td>" . htmlspecialchars($data['status_rumah']) . "</td>
                                    <td class='text-center'>" . htmlspecialchars($data['mck']) . "</td>
                                    <td>" . htmlspecialchars($data['penerangan']) . "</td>
                                    <td class='text-center'>" . htmlspecialchars($data['status_dtsen']) . "</td>
                                    <td>" . htmlspecialchars($data['nama_ibu_kandung']) . "</td>
                                    <td>" . htmlspecialchars($data['bansos_diperoleh']) . "</td>
                                    <td>" . htmlspecialchars($data['pengaduan']) . "</td>
                                    <td>" . htmlspecialchars($data['pelayanan']) . "</td>
                                    <td>Rp " . number_format($data['penghasilan'], 0, ',', '.') . "</td>
                                    <td>" . htmlspecialchars($data['nomor_tlp']) . "</td>
                                    <td>" . htmlspecialchars($data['nama_fasilitator']) . "</td>
                                </tr>";
                                $no++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div class="footer">
                Dicetak dari Sistem Layanan Registrasi Terpadu | 
                Total Data: <?php echo isset($no) ? $no-1 : 0; ?> | 
                Halaman 1
            </div>
        </body>
        </html>
    `;
    
    // Tulis konten ke window baru
    printWindow.document.open();
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Tunggu konten load lalu print
    printWindow.onload = function() {
        setTimeout(() => {
            printWindow.print();
        }, 500);
    };
}

// function simpanPDF() {
//     // Sama dengan fungsi cetak, karena PDF melalui dialog print yang sama
//     cetakLaporan();
// }

// Auto focus pada input tanggal
document.addEventListener('DOMContentLoaded', function() {
    const tanggal1Input = document.querySelector('input[name="tanggal1"]');
    if (tanggal1Input) {
        tanggal1Input.focus();
    }
});
</script>

</body>
</html>