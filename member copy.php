<?php 
   
   $server   = "localhost";
   $user     = "root";
   $password = "";
   $database = "slrt";
   
   // Membuat koneksi
   $koneksi = mysqli_connect($server, $user, $password, $database);
   
   // Periksa koneksi
   if (!$koneksi) {
       die("Koneksi database gagal: " . mysqli_connect_error());
   }

    $level = ['fasilitator', 'operator', 'koorinator', 'sekretaris'];

?>



<!-- Ionicons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
                                    <th>EMAIL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $no = 1;
                                    $levels = ["fasilitator", "operator", "koordinator"]; // Array berisi level yang diinginkan
                                    $levelList = "'" . implode("', '", $levels) . "'"; // Menggabungkan array untuk digunakan dalam query SQL
                                    
                                    $tampil = mysqli_query($koneksi, "SELECT id, nama_lengkap, gambar, level, klrhn, kcmtn, j_k, no_wa, email FROM users WHERE level IN ($levelList) ORDER BY id DESC");
                                    
                                    while ($data = mysqli_fetch_array($tampil)) {
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?= $data['nama_lengkap']; ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($data['gambar'])): ?>
                                            <img src="<?= $data['gambar']; ?>" class="img-circle" alt="Foto" style="width: 70px; height: auto;">
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
                                    <td><?= $data['email']; ?></td>
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
                                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                                <ion-icon name="eye-sharp"></ion-icon>
                                                            </span>
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

                                                    <div class="mb-3">
                                                        <input type="email" class="form-control" name="email" value="<?= $data['email']; ?>" placeholder="Email" required>
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
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div> <br>




                <style>
                    div .modal-lg .modal-content form .modal-body .form-group input {
                        border: 1px solid black;
                    }
                    div .modal-lg .modal-content form .modal-body .form-group select {
                        border: 1px solid black;
                        
                    }
                </style>

                <!-- Button trigger modal -->
                <!-- Modal -->
                <div class="modal fade" id="cekbansos" data-bs-keyboard="false" style="z-index: 200000;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel"><b>TAMBAH DATA FASILITATOR</b></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="crud_member.php" method="POST" class="text-center" style="position: relative; top:-10px; margin-left: 15px;" enctype="multipart/form-data">
                                <div class="modal-body text-center" style="position: relative; margin-left: -15px;">
                                    <!-- Username -->
                                    <div class="form-group">                                       
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                    </div>

                                    <!-- Nama Lengkap -->
                                    <div class="form-group">
                                        <label for="nama_lengkap" class="form-label"></label>
                                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" required>
                                    </div>

                                    <!-- Level -->
                                    <div class="form-group">
                                        <label for="level" class="form-label"></label>
                                        <select class="form-select" id="level" name="level" required style="min-width: 300px;">
                                            <option value="">PILIH JABATAN</option>
                                            <option value="fasilitator">FASILITATOR</option>
                                            <option value="tksk">TKSK</option>
                                            <option value="operator">OPERATOR</option>
                                            <option value="koordinator">KOORDINATOR</option>
                                            <option value="sekretaris">SEKRETARIS</option>
                                            <option value="manajer">MANAJER</option>
                                        </select>
                                    </div>

                                    <!-- Password Field -->
                                    <div class="form-group row mx-1 mx-sm-0 justify-content-center">
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                <i id="eyeIcon" class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Pastikan skrip ini berada di bawah elemen modal -->
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            document.getElementById('togglePassword').addEventListener('click', function () {
                                                const passwordInput = document.getElementById('password');
                                                const eyeIcon = document.getElementById('eyeIcon');

                                                if (passwordInput.type === 'password') {
                                                    passwordInput.type = 'text'; // Tampilkan password
                                                    eyeIcon.classList.remove('fa-eye');
                                                    eyeIcon.classList.add('fa-eye-slash'); // Ubah ikon ke mata tertutup
                                                } else {
                                                    passwordInput.type = 'password'; // Sembunyikan password
                                                    eyeIcon.classList.remove('fa-eye-slash');
                                                    eyeIcon.classList.add('fa-eye'); // Ubah ikon ke mata terbuka
                                                }
                                            });
                                        });
                                    </script>


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
                                    <div class="form-group">
                                        <label for="klrhn" class="form-label"></label>
                                        <input type="text" class="form-control" id="klrhn" name="klrhn" placeholder="Kelurahan" required>
                                    </div>

                                    <!-- Kecamatan -->
                                    <div class="form-group">
                                        <label for="kcmtn" class="form-label"></label>
                                        <input type="text" class="form-control" id="kcmtn" name="kcmtn" placeholder="Kecamatan" required>
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group">
                                        <label for="email" class="form-label"></label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                    </div>

                                    <!-- No. WhatsApp -->
                                    <div class="form-group">
                                        <label for="no_wa" class="form-label"></label>
                                        <input type="tel" class="form-control" id="no_wa" name="no_wa" placeholder="Nomor Whatsapp" required>
                                    </div>

                                    <div class="form-group">
                                        <img src="<?= htmlspecialchars($_SESSION['gambar']) ?>" class="img-circle img-fluid mb-3" width="100" height="100">
                                            <div class="custom-file mb-3">
                                                <input type="file" class="custom-file-input" id="exampleInputFile" name="gambar" required>
                                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                            </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="simpanmember" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                 <!-- Button trigger modal -->
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
    z-index: 10000;
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


