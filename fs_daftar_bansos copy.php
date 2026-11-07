<?php 
    include "koneksi.php";
?>
 




    <style>
        /* Optional: For adjusting modal content to be responsive */
        .modal-dialog {
            max-width: 90%;
        }
    </style>


<div class="mx-1">
<div class="card-body">
            <h4 class="text-center mb-4">DAFTAR PENGAJUAN BANSOS</h4>
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped table-hover dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NIK</th>
                            <th>NAMA PENGADU</th>
                            <th>NOMOR KK</th>
                            <th>TANGGAL</th>
                            <th>PELAYANAN</th>
                            <th>STATUS</th>
                            <th>INFO</th>
                            <th>KELURAHAN</th>
                            <th>KECAMATAN</th>
                            <th>ALAMAT</th>
                            <th>NOMOR TLP/WA</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $no = 1;
                    $query = "SELECT 
                                id, 
                                tanggal, 
                                nomor_kk, 
                                nik_kepala_keluarga,
                                kecamatan, 
                                kelurahan, 
                                nama_pengurus_bansos, 
                                alamat, 
                                bansos_diperoleh, 
                                pelayanan, 
                                nomor_tlp, 
                                approval_status 
                            FROM 
                                registrasi 
                            ORDER BY 
                                nomor_kk DESC;";

                    $stmt = $koneksi->prepare($query);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($data = $result->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($data['nik_kepala_keluarga']); ?></td>
                            <td><?= htmlspecialchars($data['nama_pengurus_bansos']); ?></td>
                            <td><?= htmlspecialchars($data['nomor_kk']); ?></td>
                            <td><?= htmlspecialchars($data['tanggal']); ?></td>
                            <td><?= htmlspecialchars($data['pelayanan']); ?></td>
                            <td>
                                <?php
                                $status = htmlspecialchars($data['approval_status']); // Pastikan $data['approval_status'] berisi nilai yang benar
                                $backgroundColor = '';

                                // Tentukan warna berdasarkan nilai status
                                if ($status === 'Diterima') {
                                    $backgroundColor = 'background-color: orange; color: black;';
                                } elseif ($status === 'Penerima Bansos') {
                                    $backgroundColor = 'background-color: #1e9400; color: white;';
                                } elseif ($status === 'Diproses') {
                                    $backgroundColor = 'background-color: yellow; color: black;';
                                } else {
                                    $backgroundColor = 'background-color: gray; color: white;';
                                }
                                ?>
                                <!-- Elemen span dengan gaya latar dan teks -->
                                <span style="<?= $backgroundColor; ?> padding: 5px; border-radius: 5px; display: inline-block;">
                                    <?= $status; ?>
                                </span>
                            </td>




                            <td>
                                <a class="" data-bs-toggle="modal" style="cursor:pointer;" data-bs-target="#detailModal<?= $data['id']; ?>">
                                <ion-icon name="information-circle" style="font-size: 40px;"></ion-icon>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($data['kelurahan']); ?></td>
                            <td><?= htmlspecialchars($data['kecamatan']); ?></td>
                            <td><?= htmlspecialchars($data['alamat']); ?></td>
                            <td><?= htmlspecialchars($data['nomor_tlp']); ?></td>
                            
                        </tr>

                        <!-- Modal untuk Detail -->
                        <div class="modal fade" id="detailModal<?= $data['id']; ?>" tabindex="-1" aria-labelledby="detailModalLabel<?= $data['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Anggota Keluarga</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
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
                                                        <div class="card-header text-primary">
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

