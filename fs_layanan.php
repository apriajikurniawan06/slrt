<?php 


include "koneksi.php";

// Cek apakah user_id ada dalam sesi
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Jika ada, ambil user_id
$user_id = $_SESSION['id'];

?>




<style>
    /* Import Google Font */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

/* General Styling */
body {
    font-family: 'Poppins', sans-serif;
}

/* Form Container */
form {
    position: relative;
    top: 150px;
    border-radius: 15px;
    padding: 15px;
    margin: 10px auto 200px;
    min-width: 355px;
    background: #f9f9f9;
}

/* Input Field Styling */
.input-field {
    position: relative;
    margin-bottom: 20px;
}

.input-field input {
    width: 100%;
    height: 40px;
    border: 1px solid #000;
    border-radius: 6px;
    font-size: 16px;
    padding: 10px;
    outline: none;
    background: transparent;
    color: #000;
    position: relative;
    z-index: 0;
}

/* Label Styling */
.input-field label {
    position: absolute;
    top: 50%;
    left: 15px;
    font-size: 16px;
    color: #000;
    transform: translateY(-50%);
    pointer-events: none;
    transition: all 0.3s ease;
    background: #fff;
    padding: 0 5px;
    z-index: 0;
}

/* Animations on Focus and Valid Input */
.input-field input:focus ~ label,
.input-field input:valid ~ label {
    top: 0;
    font-size: 12px;
    color: #000;
    transform: translateY(-50%);
}

/* Focused Input Border */
.input-field input:focus {
    border: 2px solid #000;
}

/* Responsive Styling */
@media (max-width: 768px) {
    form {
        margin: 20px auto;
    }
}

</style>




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



<div class="mx-2" style="position: relative; top: 0px;">
    <div class="card-body">
        <div class="text-dark">
            <h3 class="text-center mt-2">BNBA PELAYANAN SLRT</h3>
        </div>

        <div class="card-body">
            <!-- <div class="container d-none d-md-block">
                <div class="d-flex justify-content-start">
                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#cekbansos">
                        Tambahkan
                    </button>
                </div>
            </div> -->

            <div class="mx-2">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered border table-hover">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>TANGGAL</th>
                                <th>NIK</th>
                                <th>NAMA LENGKAP</th>
                                <th>ALAMAT</th>
                                <th>JENIS PELAYANAN</th>
                                <th>KETERANGAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                // Ambil data
                                $no = 1;
                                $tampil = mysqli_query($koneksi, "SELECT 
                                                                    id, 
                                                                    tanggal,
                                                                    nik_kepala_keluarga,
                                                                    nama_pengurus_bansos, 
                                                                    alamat, 
                                                                    pelayanan
                                                                FROM 
                                                                    registrasi 
                                                                WHERE 
                                                                    user_id = '$user_id' AND 
                                                                    MONTH(tanggal) = MONTH(CURRENT_DATE()) AND 
                                                                    YEAR(tanggal) = YEAR(CURRENT_DATE()) 
                                                                ORDER BY 
                                                                    id DESC");

                                // Cek apakah ada data
                                if (mysqli_num_rows($tampil) == 0) {
                                    echo '<tr><td colspan="8" class="text-center text-danger">Tidak ada data untuk bulan ini.</td></tr>';
                                } else {
                                    while ($data = mysqli_fetch_array($tampil)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?= $data['tanggal']; ?></td>
                                <td><?= $data['nik_kepala_keluarga']; ?></td>
                                <td><?= $data['nama_pengurus_bansos']; ?></td>
                                <td><?= $data['alamat']; ?></td>
                                <td><?= $data['pelayanan']; ?></td>
                                <td>Selesai</td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modaledit<?= $data['cek_bansos_id'] ?>" style="z-index:1000000;" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel"><b>EDIT LAPORAN PELAYANAN</b></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="crud_cekbansos.php" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $data['cek_bansos_id']; ?>">
                                                <div class="mb-3">
                                                    <label for="tanggal" class="form-label">Tanggal</label>
                                                    <input type="date" required name="tanggal" value="<?= $data['tanggal'] ?>" class="form-control" id="tanggal">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nama_klien" class="form-label">Nama Klien</label>
                                                    <input type="text" required name="nama_klien" value="<?= $data['nama_klien'] ?>" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="alamat_klien" class="form-label">Alamat Klien</label>
                                                    <input type="text" required name="alamat_klien" value="<?= $data['alamat_klien'] ?>" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jenis_layanan" class="form-label">Jenis Layanan</label>
                                                    <input type="text" required name="jenis_layanan" value="<?= $data['jenis_layanan'] ?>" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="keterangan_cekbansos" class="form-label">Keterangan</label>
                                                    <input type="text" required name="keterangan_cekbansos" value ="<?= $data['keterangan_cekbansos'] ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="ubahbansos" class="btn btn-primary">Ubah</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Hapus -->
                            <div class="modal fade" id="modalhapus<?= $data['cek_bansos_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="crud_cekbansos.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $data['cek_bansos_id'] ?>">
                                            <div class="modal-body">
                                                <h5 class="text-center">Yakin Menghapus Data ini<br>
                                                    <span class="text-danger"><?= $data['nama_klien'] ?></span>
                                                </h5>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                                                <button type="submit" name="hapusbansos" class="btn btn-primary">Ya</button>
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
            </div>

            <div class="modal fade" id="cekbansos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 200000;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel"><b>TAMBAH DATA CEK BANSOS</b></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="crud_cekbansos.php" method="POST" style="position: relative; top:-10px;">
                            <div class="modal-form">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <div class="input-field form-group">
                                    <input type="date" required name="tanggal" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" id="tanggal" autofocus>
                                    <label></label>
                                </div> <br>
                                <div class="input-field form-group">
                                    <input type="text" required name="nama_klien">
                                    <label>NAMA KLIEN</label>
                                </div><br>
                                <div class="input-field form-group">
                                    <input type="text" required name="alamat_klien">
                                    <label>ALAMAT</label>
                                </div><br>
                                <div class="input-field form-group">
                                    <input type="text" required name="jenis_layanan">
                                    <label>JENIS LAYANAN</label>
                                </div><br>
                                <div class="input-field form-group">
                                    <input type="text" required name="keterangan_cekbansos">
                                    <label>KETERANGAN</label>
                                </div><br>
                                <div class="input-field form-group">
                                    <input type="hidden" required name="keterangan_cekbansos" value="<?php echo strtoupper($_SESSION['nama_lengkap']); ?>">
                                </div><br>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="bansossimpan" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tambah {
    position: fixed;
    bottom: 3px;
    left: 51%;
    transform: translateX(-50%);
    background-color: #006eff;
    z-index: 100000;
    width: 60px;
    height: 60px;
    border-radius: 50px;
    justify-content: center;
    display: flex;
    align-items: center;
}
.tambah .icon a ion-icon {
    font-size: 3em;
}
</style>
<!-- <div class="tambah d-lg-none">
    <div class="icon">
        <a href="" data-bs-toggle="modal" data-bs-target="#cekbansos">
            <ion-icon name="add-outline" class="text-white"></ion-icon>
        </a>
    </div>
</div> -->


