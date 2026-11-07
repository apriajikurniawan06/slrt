<?php
include_once "koneksi.php";
?>



<!-- Ionicons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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



 

<div class="mx-1" style="position: relative; top: 0px;">
    <div class="">
        <div class="text-dark">
            <h3 class="text-center">DAFTAR NAMA KARYAWAN</h3>
        </div>

      
            <div class="card-body">
                <div class="mx-1">
                    <div class="d-none d-md-flex justify-content-start">
                        <button type="button" class="btn btn-primary mb-2 me-2" data-bs-toggle="modal" data-bs-target="#cekbansos">
                        <i class="bi bi-person-plus-fill fs-5 me-2"></i> Tambahkan
                        </button>
                    </div>
                </div>

            

                <div class="table-responsive">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered border table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NAMA LENGKAP</th>
                                    <th>FOTO</th>
                                    <th>JABATAN</th>
                                    <th>KELURAHAN</th>
                                    <th>KECAMATAN</th>
                                    <th>JENIS KELAMIN</th>
                                    <th class="text-center">AKSI</th>
                                    <th>NOMOR WA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                  $no = 1;
                                  $query = "SELECT id, nama_lengkap, gambar, level, klrhn, kcmtn, j_k, no_wa, hak_akses
                                        FROM users
                                        ORDER BY id DESC";
                                  $tampil = mysqli_query($koneksi, $query);

                                  if ($tampil === false) {
                                    echo '<tr><td colspan="9" class="text-center text-danger">Data tidak dapat dimuat: '
                                      . htmlspecialchars(mysqli_error($koneksi), ENT_QUOTES, 'UTF-8')
                                      . '</td></tr>';
                                  } else {
                                    while ($data = mysqli_fetch_assoc($tampil)) {
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['nama_lengkap']; ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($data['gambar'])): ?>
                                            <img src="<?= $data['gambar']; ?>" class="img-circle" alt="Foto" style="width: 60px; height: 60px;">
                                        <?php else: ?>
                                            <i class="fas fa-user" style="font-size: 20px;"></i>
                                            <i class="fas fa-question" style="font-size: 20px;"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $data['level']; ?></td>
                                    <td><?= $data['klrhn']; ?></td>
                                    <td><?= $data['kcmtn']; ?></td>
                                    <td><?= $data['j_k']; ?></td>
                                    
                                    <td>
                                        <div class="d-flex">
                                            <div class="me-3">
                                                <a href="" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modaledit<?= $data['id'] ?>"><i class="bi bi-pencil-square"></i></a>
                                            </div>
                                            <div class="me-3">
                                                <a href="" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalhapus<?= $data['id'] ?>"><i class="bi bi-trash"></i></a>
                                            </div>
                                            
                                            <div class="me-3">
                                                <select name="hak_akses" 
                                                        class="approval-status text-center" 
                                                        data-id="<?= isset($data['id']) ? $data['id'] : ''; ?>" 
                                                        onchange="updateApprovalStatus(this)" 
                                                        style="border: none; background-color: transparent; font-weight: bold;">
                                                    <option value="Aktif" <?= (isset($data['hak_akses']) && $data['hak_akses'] === 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                                                    <option value="Nonaktif" <?= (isset($data['hak_akses']) && $data['hak_akses'] === 'Nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                                                </select>
                                            </div>

                                        </div>
                                    </td>
                                    <td><?= $data['no_wa']; ?></td>
                                </tr>

                                <script>
                                function updateApprovalStatus(selectElement) {
                                            const userId = selectElement.getAttribute('data-id'); // Mengambil ID user
                                            const newStatus = selectElement.value; // Nilai baru (Aktif/Nonaktif)

                                            // Perbarui warna teks dan background berdasarkan status
                                            if (newStatus === "Aktif") {
                                                selectElement.style.backgroundColor = "green"; // Warna hijau untuk Aktif
                                                selectElement.style.color = "white"; // Warna teks putih untuk kontras
                                            } else {
                                                selectElement.style.backgroundColor = "red"; // Warna merah untuk Nonaktif
                                                selectElement.style.color = "white"; // Warna teks putih untuk kontras
                                            }

                                            // Data yang akan dikirim ke server
                                            const data = {
                                                id: userId,
                                                hak_akses: newStatus
                                            };

                                            // Mengirim permintaan ke server menggunakan Fetch API
                                            fetch('member_status.php', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                },
                                                body: JSON.stringify(data),
                                            })
                                            .then(response => response.json())
                                            .then(result => {
                                                if (result.success) {
                                                    alert('Status berhasil diperbarui!');
                                                } else {
                                                    alert('Gagal memperbarui status: ' + result.message);
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Terjadi kesalahan:', error);
                                                alert('Terjadi kesalahan saat memperbarui status.');
                                            });
                                        }

                                        // Inisialisasi warna teks dan background berdasarkan nilai awal dropdown
                                        document.addEventListener("DOMContentLoaded", function () {
                                            const approvalStatusElements = document.querySelectorAll(".approval-status");
                                            approvalStatusElements.forEach(element => {
                                                const currentValue = element.value;
                                                if (currentValue === "Aktif") {
                                                    element.style.backgroundColor = "green";
                                                    element.style.color = "white"; // Warna teks putih agar lebih kontras
                                                } else {
                                                    element.style.backgroundColor = "red";
                                                    element.style.color = "white";
                                                }
                                            });
                                        });

                                    </script>



                                <!-- Modal Edit -->
                                <div class="modal fade" id="modaledit<?= $data['id'] ?>" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true" style="z-index: 1000000;">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel"><b>EDIT DATA</b></h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="crud_member.php" method="POST" enctype="multipart/form-data">
                                                <!-- Hidden input untuk ID -->
                                                <input type="hidden" name="id" value="<?= $data['id']; ?>">
                                                <div class="modal-body">
                                                    <!-- Nama Lengkap -->
                                                    <div class="mb-3">
                                                        <input type="text" class="form-control" name="nama_lengkap" value="<?= $data['nama_lengkap']; ?>" placeholder="Nama Lengkap" required>
                                                    </div>

                                                    <!-- Level -->
                                                    <div class="mb-3">
                                                        <select class="form-select" name="level" required>
                                                            <option value="">PILIH JABATAN</option>
                                                            <option value="fasilitator" <?= (isset($data['level']) && $data['level'] === 'fasilitator') ? 'selected' : ''; ?>>FASILITATOR</option>
                                                            <option value="tksk" <?= (isset($data['level']) && $data['level'] === 'tksk') ? 'selected' : ''; ?>>TKSK</option>
                                                            <option value="operator" <?= (isset($data['level']) && $data['level'] === 'operator') ? 'selected' : ''; ?>>OPERATOR</option>
                                                            <option value="koordinator" <?= (isset($data['level']) && $data['level'] === 'koordinator') ? 'selected' : ''; ?>>KOORDINATOR</option>
                                                            <option value="sekretaris" <?= (isset($data['level']) && $data['level'] === 'sekretaris') ? 'selected' : ''; ?>>SEKRETARIS</option>
                                                            <option value="manajer" <?= (isset($data['level']) && $data['level'] === 'manajer') ? 'selected' : ''; ?>>MANAJER</option>
                                                        </select>
                                                    </div>

                                                    <!-- Edit Password -->
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">Password</label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control" name="password" placeholder="Password">
                                                            <button class="btn btn-outline-secondary toggle-password" type="button" aria-label="Tampilkan password">
                                                              <i class="fas fa-eye password-eye-icon"></i>
                                                            </button>
                                                        </div>
                                                    </div> 

                                                    
                                                    <!-- Jenis Kelamin -->
                                                    <div class="form-group border-1">
                                                        <label class="form-label d-block text-start">Jenis Kelamin</label>
                                                        <div class="d-flex gap-3 mt-2">
                                                            <div class="form-check">
                                                                <input type="radio" class="form-check-input" id="laki-laki" name="j_k" value="Laki-laki" required>Laki-laki
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="radio" class="form-check-input" id="perempuan" name="j_k" value="Perempuan" required>Perempuan                                           
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Kelurahan -->
                                                    <div class="mb-3">
                                                        <input type="text" class="form-control" name="klrhn" value="<?= $data['klrhn']; ?>" placeholder="Kelurahan" required>
                                                    </div>

                                                    <!-- Kecamatan -->
                                                    <div class="mb-3">
                                                        <input type="text" class="form-control" name="kcmtn" value="<?= $data['kcmtn']; ?>" placeholder="Kecamatan" required>
                                                    </div>

                                                    <!-- No. WhatsApp -->
                                                    <div class="mb-3">
                                                        <input type="tel" class="form-control" name="no_wa" value="<?= $data['no_wa']; ?>" placeholder="Nomor WhatsApp" required>
                                                    </div>

                                                    <!-- Input untuk Gambar -->
                                                    <div class="mb-3">
                                                        <label for="gambar" class="form-label">Upload Gambar</label>
                                                        <input type="file" class="form-control" name="gambar" id="gambar" accept="image/*">
                                                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" name="editmember" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                


                                <!-- Modal Hapus -->
                                <div class="modal fade" id="modalhapus<?= $data['id'] ?>" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalHapusLabel">Konfirmasi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="crud_member.php" method="POST">
                                                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                                <div class="modal-body">
                                                    <p class="text-center">Yakin Menghapus Data ini?<br><strong class="text-danger"><?= $data['nama_lengkap'] ?></strong></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                                                    <button type="submit" name="hapusmember" class="btn btn-danger">Ya</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    }
                                  }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div> <br>




                <style>
                    .modal {
                        z-index: 2000;
                    }
                    div .modal-lg .modal-content form .modal-body .form-group input {
                        border: 1px solid black;
                    }
                    div .modal-lg .modal-content form .modal-body .form-group select {
                        border: 1px solid black;
                        
                    }
                </style>

<!-- Modal Tambah Data Fasilitator -->
<div class="modal fade" id="cekbansos" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">

      <!-- Header -->
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold">
          <i class="fas fa-user-plus me-2"></i> Tambah Data Karyawan
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <form action="crud_member.php" method="POST" enctype="multipart/form-data" class="p-4">
        <div class="row g-3">
          
          <!-- Username -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Username</label>
            <input type="text" class="form-control form-control-lg" name="username" placeholder="Masukkan Username" required>
          </div>

          <!-- Nama Lengkap -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" class="form-control form-control-lg" name="nama_lengkap" placeholder="Masukkan Nama Lengkap" required>
          </div>

          <!-- Level -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Jabatan</label>
            <select class="form-select form-select-lg" name="level" required>
              <option value="">Pilih Jabatan</option>
              <option value="fasilitator">Fasilitator</option>
              <option value="tksk">TKSK</option>
              <option value="operator">Operator</option>
              <option value="koordinator">Koordinator</option>
              <option value="sekretaris">Sekretaris</option>
              <option value="manajer">Manajer</option>
            </select>
          </div>

          <!-- Password -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group input-group-lg">
              <input type="password" class="form-control form-control-lg" name="password" placeholder="Masukkan Password" required>
              <button class="btn btn-outline-secondary toggle-password" type="button" aria-label="Tampilkan password">
                <i class="fas fa-eye password-eye-icon"></i>
              </button>
            </div>
          </div>

          <!-- Jenis Kelamin -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Jenis Kelamin</label>
            <div class="d-flex gap-3 mt-1">
              <div class="form-check">
                <input type="radio" class="form-check-input" id="laki" name="j_k" value="Laki-laki" required>
                <label class="form-check-label" for="laki">Laki-laki</label>
              </div>
              <div class="form-check">
                <input type="radio" class="form-check-input" id="perempuan" name="j_k" value="Perempuan" required>
                <label class="form-check-label" for="perempuan">Perempuan</label>
              </div>
            </div>
          </div>

          <!-- Kecamatan -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Kecamatan</label>
            <input type="text" class="form-control form-control-lg" name="kcmtn" placeholder="Masukkan Kecamatan" required>
          </div>

          <!-- Kelurahan -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Kelurahan</label>
            <input type="text" class="form-control form-control-lg" name="klrhn" placeholder="Masukkan Kelurahan" required>
          </div>

          <!-- Nomor WhatsApp -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Nomor WhatsApp</label>
            <input type="tel" class="form-control form-control-lg" name="no_wa" placeholder="Masukkan Nomor WhatsApp" required>
          </div>

          <!-- Upload Foto + Preview -->
          <div class="col-12 text-center mt-4">
            <label class="form-label fw-semibold mb-3">Upload Foto Profil</label>

            <!-- Area Preview -->
            <div class="preview-container d-flex flex-column align-items-center">
              <div id="imagePreview" class="mb-3" style="display:none;">
                <img id="previewImage" src="" class="rounded-circle shadow-lg" style="width:120px; height:120px; object-fit:cover; animation: fadeIn .5s ease-in-out;">
              </div>

              <div id="imagePlaceholder" class="mb-3">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width:120px; height:120px;">
                  <i class="fas fa-user text-muted" style="font-size:2rem;"></i>
                </div>
              </div>

              <!-- Input File -->
              <div class="input-group w-50">
                <input type="file" class="form-control" id="exampleInputFile" name="gambar" accept="image/*" onchange="previewImage(this)">
              </div>
              <small class="text-muted mt-2">Format: JPG, PNG, JPEG (maks. 2MB)</small>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer border-0 mt-4">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i> Batal
          </button>
          <button type="submit" name="simpanmember" class="btn btn-primary px-4">
            <i class="fas fa-save me-1"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".toggle-password").forEach(function (toggleButton) {
    toggleButton.addEventListener("click", function () {
      const inputGroup = toggleButton.closest(".input-group");
      const passwordInput = inputGroup.querySelector("input[name='password']");
      const eyeIcon = toggleButton.querySelector(".password-eye-icon");
      const isHidden = passwordInput.type === "password";

      passwordInput.type = isHidden ? "text" : "password";
      eyeIcon.classList.toggle("fa-eye", !isHidden);
      eyeIcon.classList.toggle("fa-eye-slash", isHidden);
      toggleButton.setAttribute("aria-label", isHidden ? "Sembunyikan password" : "Tampilkan password");
    });
  });
});

  // === Preview Gambar Sebelum Upload ===
  const fileInput = document.getElementById("exampleInputFile");
  const preview = document.getElementById("imagePreview");
  const placeholder = document.getElementById("imagePlaceholder");
  const previewImage = document.getElementById("previewImage");

  if (fileInput) {
    fileInput.addEventListener("change", function (event) {
      const file = event.target.files[0];

      if (!file) {
        preview.style.display = "none";
        placeholder.style.display = "block";
        return;
      }

      // Validasi tipe file
      if (!file.type.startsWith("image/")) {
        alert("File yang dipilih bukan gambar!");
        fileInput.value = "";
        preview.style.display = "none";
        placeholder.style.display = "block";
        return;
      }

      // Menampilkan preview
      const reader = new FileReader();
      reader.onload = function (e) {
        previewImage.src = e.target.result;
        preview.style.display = "block";
        placeholder.style.display = "none";

        // Efek animasi fade-in
        previewImage.style.animation = "fadeIn 0.4s ease-in-out";
      };
      reader.readAsDataURL(file);
    });
  }

</script>

<!-- Efek animasi -->
<style>
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
</style>


<!-- Efek Fade In -->
<style>
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
</style>

        </div>
      
    </div>
</div>




<style>
    .approval-status {
    border-radius: 8px; /* Sudut membulat pada dropdown */
    width: 100px;
    appearance: none; /* Untuk browser modern */
    -webkit-appearance: none; /* Untuk Safari & Chrome */
    -moz-appearance: none;
    padding: 5px;
}


 .tambah {
    position: fixed;
    bottom: 33px;
    left: 52%;
    transform: translateX(-50%);
    background-color: #006eff;
    box-shadow: 0px 0px 5px 0px #ffffff;
    -webkit-box-shadow: 0px 0px 5px 0px #ffffff;
    -moz-box-shadow: 0px 0px 5px 0px #ffffff;
    z-index: 1000;
    width: 50px;
    height: 50px;
    border-radius: 50px;
    justify-content: center;
    display: flex;
    align-items: center;
}
.tambah .icon a ion-icon {
    font-size: 2em;
}

</style>
<div class="tambah d-lg-none">
    <div class="icon">
        <a href="" data-bs-toggle="modal" data-bs-target="#cekbansos">
            <ion-icon name="add-outline" class="text-white"></ion-icon>
        </a>
    </div>
</div>


