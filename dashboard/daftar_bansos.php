<?php 
include "koneksi.php";
?>


<style>
    .modal-dialog {
        max-width: 90%;
    }

    /* bintang */
    .show-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    margin-left: 5px;
}

.show-btn ion-icon {
    font-size: 1.2em;
    color: #007bff;
}
</style>

<meta name="theme-color" content="#f8fafa">
    <link rel="manifest" href="manifest.json">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    

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


 


    <div class="">
        <div class="card-body">
            <h4 class="text-center mb-3">DAFTAR PENGAJUAN BANSOS</h4>
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped table-hover dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA LENGKAP</th>
                            <th>NOMOR KK</th>
                            <th>NIK</th>
                            <th>TANGGAL</th>
                            <th>KECAMATAN</th>
                            <th>KELURAHAN</th>
                            <th>STATUS</th>
                            <th>DETAIL</th>
                            <th>BANSOS DITERIMA</th>
                            <th>PENGAJUAN</th>
                            <th>ALAMAT</th>
                            <th>NOMOR TLP/WA</th>
                            <th>JENIS KELAMIN</th>
                            <th>JUMLAH KELUARGA</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $no = 1;
                    $query = "SELECT id, tanggal, nomor_kk, nik_kepala_keluarga, kecamatan, kelurahan, nama_pengurus_bansos, jenis_k, alamat, bansos_diperoleh, pelayanan, nomor_tlp, anggota_keluarga, approval_status FROM registrasi ORDER BY nomor_kk DESC";
                    $stmt = $koneksi->prepare($query);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($data = $result->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($data['nama_pengurus_bansos']); ?></td>
                            <td><?= htmlspecialchars($data['nomor_kk']); ?></td>                            
                            <td class="d-flex justify-content-between align-items-center">
                                <span id="nik_kepala_keluarga_<?= $no; ?>" data-full="<?= htmlspecialchars($data['nik_kepala_keluarga']); ?>">
                                    <?= substr(htmlspecialchars($data['nik_kepala_keluarga']), 0, -4) . '****'; ?>
                                </span>
                                <div class="button-group">
                                    <button class="btn copy-btn mt-1" data-target="nik_kepala_keluarga_<?= $no; ?>">
                                        <ion-icon name="copy-outline"></ion-icon>
                                    </button>
                                    <button class="btn show-btn mt-1" onclick="toggleVisibility('nik_kepala_keluarga_<?= $no; ?>')">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </button>
                                </div>
                            </td>
                            
                            <script>
                                function toggleVisibility(elementId) {
                                    const element = document.getElementById(elementId);
                                    const fullText = element.getAttribute('data-full'); // Ambil teks asli dari atribut data
                                const maskedText = fullText.substring(0, fullText.length - 4) + '****'; // Buat teks yang disamarkan

                                // Toggle antara teks asli dan teks yang disamarkan
                                if (element.textContent.includes('****')) {
                                    element.textContent = fullText; // Tampilkan teks asli
                                } else {
                                    element.textContent = maskedText; // Tampilkan teks yang disamarkan
                                }
                            }
                            </script>

                            <td><?= htmlspecialchars($data['tanggal']); ?></td>     
                            <td><?= htmlspecialchars($data['kecamatan']); ?></td>
                            <td><?= htmlspecialchars($data['kelurahan']); ?></td>
                            <td>
                                <select name="approval_status" 
                                    class="approval-status" 
                                    data-id="<?= $data['id']; ?>" 
                                    onchange="updateApprovalStatus(this)" 
                                    style="border: none; background-color: transparent; color: inherit; width:120px;">
                                    <!-- <option value="None" <?= $data['approval_status'] === 'None' ? 'selected' : ''; ?>>None</option> -->
                                    <option value="Diterima" <?= $data['approval_status'] === 'Diterima' ? 'selected' : ''; ?>> <span style="width:20px; height:20px; border-radius:50px;"></span> Diterima</option>
                                    <option value="Diproses" <?= $data['approval_status'] === 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
                                    <option value="Penerima Bansos" <?= $data['approval_status'] === 'Penerima Bansos' ? 'selected' : ''; ?>>Penerima Bansos</option>
                                </select>
                            </td>
                        
                        
                                                    
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                            <script>
                            function updateApprovalStatus(selectElement) {
                                const id = selectElement.dataset.id;
                                const status = selectElement.value;

                                Swal.fire({
                                    title: 'Memproses...',
                                    text: 'Harap tunggu...',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                fetch('approve.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({ id, approval_status: status }),
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(`HTTP error! status: ${response.status}`);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: data.message,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        changeTextStyle(selectElement); // Terapkan perubahan warna
                                    });
                                })
                                .catch(error => {
                                    console.error('Error:', error.message);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: 'Terjadi kesalahan saat memperbarui status. Periksa koneksi atau format respons server.',
                                        confirmButtonText: 'Coba Lagi'
                                    });
                                });
                            }

                            function changeTextStyle(selectElement) {
                                const status = selectElement.value;
                                let textColor, bgColor;

                                switch (status) {
                                    case 'Diterima':
                                        textColor = '#000';
                                        bgColor = '#ff9100';
                                        break;
                                    case 'Diproses':
                                        textColor = '#000';
                                        bgColor = '#ffe600';
                                        break;
                                    case 'Penerima Bansos':
                                        textColor = '#fff';
                                        bgColor = '#1e9400';
                                        break;
                                    default:
                                        textColor = '';
                                        bgColor = ''; 
                                }

                                selectElement.style.color = textColor;
                                selectElement.style.backgroundColor = bgColor;
                                selectElement.style.borderRadius = '5px';
                                selectElement.style.padding = '5px';
                            }

                            // Terapkan gaya ke semua select saat halaman dimuat
                            document.addEventListener('DOMContentLoaded', function() {
                                document.querySelectorAll('.approval-status').forEach(select => {
                                    changeTextStyle(select);
                                });
                            });







                            
                            // Fungsi untuk menyalin teks ke clipboard
                            function copyToClipboard(elementId) {
                                const targetElement = document.getElementById(elementId);
                                if (targetElement) {
                                    const textToCopy = targetElement.textContent || targetElement.innerText;
                                    navigator.clipboard.writeText(textToCopy).then(() => {
                                        // Hanya tampilkan alert jika belum ada alert yang ditampilkan
                                        if (!copyAlertShown) {
                                            alert(`Data "${textToCopy}" disalin.`);
                                            copyAlertShown = true; // Set flag agar alert tidak ditampilkan lagi
                                            setTimeout(() => {
                                                copyAlertShown = false; // Reset flag setelah beberapa waktu
                                            }, 2000); // Reset flag setelah 2 detik
                                        }
                                    }).catch(err => {
                                        alert("Gagal menyalin.");
                                        console.error(err);
                                    });
                                }
                            }

                            // Flag untuk mengontrol alert
                            let copyAlertShown = false;

                            // Event listener untuk tombol salin
                            document.addEventListener('click', function (event) {
                                if (event.target.closest('.copy-btn')) {
                                    const button = event.target.closest('.copy-btn');
                                    const targetId = button.getAttribute('data-target');
                                    copyToClipboard(targetId);
                                }
                            });
                            </script>

                            <td>
                                <a class="" data-bs-toggle="modal" style="cursor:pointer;" data-bs-target="#detailModal<?= $data['id']; ?>">
                                    <ion-icon name="information-circle" style="font-size: 40px;"></ion-icon>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($data['bansos_diperoleh']); ?></td>
                            <td><?= htmlspecialchars($data['pelayanan']); ?></td>
                            <td><?= htmlspecialchars($data['alamat']); ?></td>
                            <td><?= htmlspecialchars($data['nomor_tlp']); ?></td>
                            <td><?= htmlspecialchars($data['jenis_k']); ?></td>
                            <td><?= htmlspecialchars($data['anggota_keluarga']); ?></td>
                            
                        </tr>

                        <div class="modal fade" id="detailModal<?= $data['id']; ?>" tabindex="-1" aria-labelledby="detailModalLabel<?= $data['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg" style="max-width: 90%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Anggota Keluarga</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <?php 
                                                $no_detail = 1;
                                                $query_detail = "SELECT nama_lengkap, nik, tanggal_lahir, tempat_lahir, jenis_kelamin, agama, pendidikan, jenis_pekerjaan FROM anggota_keluarga WHERE nomor_kk = ?";
                                                $stmt_detail = $koneksi->prepare($query_detail);
                                                $stmt_detail->bind_param("s", $data['nomor_kk']);
                                                $stmt_detail->execute();
                                                $result_detail = $stmt_detail->get_result();

                                                while ($data_detail = $result_detail->fetch_assoc()) {
                                                ?>
                                                    <div class="col-md-4 col-sm-6 mb-4">
                                                        <div class="card">
                                                            <div class="card-header text-danger">
                                                                Anggota Keluarga <?= $no_detail++; ?>
                                                            </div>
                                                            <div class="card-body">
                                                                <p class="card-text"><strong>NAMA LENGKAP</strong> <?= htmlspecialchars($data_detail['nama_lengkap']); ?></p>
                                                                <p class="card-text"><strong>NIK:</strong> <?= htmlspecialchars($data_detail['nik']); ?></p>
                                                                <p class="card-text"><strong>Tanggal Lahir:</strong> <?= htmlspecialchars($data_detail['tanggal_lahir']); ?></p>
                                                                <p class="card-text"><strong>Tempat Lahir:</strong> <?= htmlspecialchars($data_detail['tempat_lahir']); ?></p>
                                                                <p class="card-text"><strong>Jenis Kelamin:</strong> <?= htmlspecialchars($data_detail['jenis_kelamin']); ?></p>
                                                                <p class="card-text"><strong>Agama:</strong> <?= htmlspecialchars($data_detail['agama']); ?></p>
                                                                <p class="card-text"><strong>Pendidikan:</strong> <?= htmlspecialchars($data_detail['pendidikan']); ?></p>
                                                                <p class="card-text"><strong>Pekerjaan:</strong> <?= htmlspecialchars($data_detail['jenis_pekerjaan']); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

