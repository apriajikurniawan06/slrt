<?php 

include "koneksi.php";

// Cek apakah user_id ada dalam sesi
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Ambil user_id dari sesi
$user_id = $_SESSION['id'];
?>

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


<style>

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



<div class="mx-2" style="position: relative; top: -20px;">
    <div class="card-body">
        <div class="text-dark text-center mt-1">
            <h4 class="">DATA BAYI BARU LAHIR</h4>
        </div>

        <div class="card-body">
            <div class="container d-none d-md-block">
                <div class="d-flex justify-content-start">
                    <button type="button" class="btn btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#tambahbbl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-plus-circle-dotted text-white" viewBox="0 0 16 16">
                            <path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                        </svg>
                        Tambahkan
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="example1" class="table table-bordered border table-hover">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>TANGGAL</th>
                            <th>NIK IBU</th>
                            <th>NAMA LENKGAP</th>
                            <th>ALAMAT</th>
                            <th>JENIS PELAYANAN</th>
                            <th>KETERANGAN</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        //ambil data berdasarkan user_id
                        $no = 1;
                        $tampil = mysqli_query($koneksi, "SELECT 
                                                            b.id AS bbl_id,
                                                            b.tanggal,
                                                            b.user_id,
                                                            k.id AS klien_id,
                                                            k.nama_klien,
                                                            k.nik_klien,
                                                            k.alamat_klien,
                                                            b.jenis_layanan,
                                                            b.keterangan_bbl
                                                        FROM 
                                                            bbl b
                                                        JOIN 
                                                            klien_bbl k ON b.klien_id = k.id
                                                        WHERE 
                                                            b.user_id = '$user_id'
                                                        ORDER BY 
                                                            b.tanggal DESC");
                        while ($data = mysqli_fetch_array($tampil)) {
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?= htmlspecialchars($data['tanggal'], ENT_QUOTES); ?></td>
                            <td><?= htmlspecialchars($data['nik_klien'], ENT_QUOTES); ?></td>
                            <td>BAYI NYONYA <?= htmlspecialchars($data['nama_klien'], ENT_QUOTES); ?></td>
                            <td><?= htmlspecialchars($data['alamat_klien'], ENT_QUOTES); ?></td>
                            <td><?= htmlspecialchars($data['jenis_layanan'], ENT_QUOTES); ?></td>
                            <td><?= htmlspecialchars($data['keterangan_bbl'], ENT_QUOTES); ?></td>
                            <td class="d-flex align-items-center justify-content-center">
                                <a href="" class="me-2" data-bs-toggle="modal" data-bs-target="#modaledit<?= $no ?>"><ion-icon name="create-sharp" class="text-primary" style="font-size:25px;"></ion-icon></a>
                                <a href="" class="ms-2" data-bs-toggle="modal" data-bs-target="#modalhapus<?= $no ?>"><ion-icon name="trash-sharp" class="text-danger" style="font-size:25px;"></ion-icon></a>
                            </td>
                        </tr>

                        <!-- Modal ubah-->
                        <div class="modal fade" id="modaledit<?= $no ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 200000;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel"><b>EDIT LAPORAN PELAYANAN</b></h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="fs_crud_bbl.php" method="POST">
                                        <div class="container" style="position: relative; top:-80px;">
                                            <div class="card p-2">
                                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($data['user_id'], ENT_QUOTES); ?>">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($data['bbl_id'], ENT_QUOTES); ?>">
                                                <div class="rounded">
                                                    <div class="input-field form-group">
                                                        <input type="date" required uppercase name="tanggal" value="<?= htmlspecialchars($data['tanggal'], ENT_QUOTES); ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" id="tanggal" autofocus>
                                                        <label></label>
                                                    </div>

                                                    <div class="input-field form-group">
                                                        <input type="number" required style="text-transform: uppercase;" name="nik_klien" value="<?= $data['nik_klien'] ?>">
                                                        <label>NIK</label>
                                                    </div>
                                                    <div class="input-field form-group">
                                                        <input type="text" required style="text-transform: uppercase;" name="nama_klien" value="<?= htmlspecialchars($data['nama_klien'], ENT_QUOTES); ?>" oninput="this.value = this.value.toUpperCase();">
                                                        <label>NAMA KLIEN</label>
                                                    </div>
                                                </div>

                                                <div class="bg-light rounded">
                                                    <div class="input-field form-group">
                                                        <input type="text" required style="text-transform: uppercase;" name="alamat_klien" value="<?= htmlspecialchars($data['alamat_klien'], ENT_QUOTES); ?>">
                                                        <label>ALAMAT KLIEN</label>
                                                    </div>
                                      
                                                    <div class="input-field form-group">
                                                        <input type="text" required style="text-transform: uppercase;" name="jenis_layanan" value="<?= htmlspecialchars($data['jenis_layanan'], ENT_QUOTES); ?>">
                                                        <label>JENIS LAYANAN</label>
                                                    </div>
                                                    <div class="input-field form-group">
                                                        <input type="text" required style="text-transform: uppercase;" name="keterangan_bbl" value="<?= htmlspecialchars($data['keterangan_bbl'], ENT_QUOTES); ?>">
                                                        <label>KETERANGAN</label>
                                                    </div>
                                                </div>
                                              

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" name="ubahbbl" class="btn btn-primary">Ubah</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- DELETE DATA =========================================================-->
                        <div class="modal fade" id="modalhapus<?= $no ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title fs-5 text-danger" id="staticBackdropLabel">Konfirmasi</h3>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="fs_crud_bbl.php" method="POST">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($data['bbl_id'], ENT_QUOTES); ?>">
                                        <div class="modal-body">
                                            <h5 class="text-center">Yakin Menghapus Data!!!<br>
                                                <span class="text-danger"><?= htmlspecialchars($data['nama_klien'], ENT_QUOTES); ?></span>
                                            </h5>
                                        </div>
                                        
                                        <div class="modal-footer" style="z-index: 10;">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                                            <button type="submit" name="hapusbbl" class="btn btn-primary">Ya</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- DELETE DATA =========================================================-->

                    <?php 
                       } 
                    ?>
                    </tbody>
                </table>
            </div>

                <!-- Modal Tambah -->
                <div class="modal fade" id="tambahbbl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 200000;">
                    <div class="modal-dialog bg-white">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel"><b>TAMBAH DATA BBL </b></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="fs_crud_bbl.php" method="POST" class="bg-white" style="position: relative; top: -10px;">
                                <div class="modal-form bg-white">
                                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id, ENT_QUOTES); ?>">
                                    <div class="input-field form-group">
                                        <input type="date" required name="tanggal" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" id="tanggal" autofocus>
                                        <label></label>
                                    </div> 
                                    <div class="input-field form-group">
                                        <input type="number" required uppercase name="nik_klien" id="nik_klien">
                                        <span id="message_nik_klien" style="text-transform: uppercase; color: red; display: none;"></span>
                                        <label>NIK</label>
                                    </div> 
                                    <div class="input-field form-group">
                                        <input type="text" required style="text-transform: uppercase;" name="nama_klien">
                                        <label>NAMA KLIEN</label>
                                    </div>
                                    <div class="input-field form-group">
                                        <input type="text" required style="text-transform: uppercase;" name="alamat_klien">
                                        <label>ALAMAT</label>
                                    </div>
                                    <div class="input-field form-group">
                                        <input type="text" value="PENGUPDETAN PBI JK BAYI BARU LAHIR" required style="text-transform: uppercase;" name="jenis_layanan">
                                        <label>JENIS LAYANAN</label>
                                    </div>
                                    <div class="input-field form-group">
                                        <input type="text" required style="text-transform: uppercase;" name="keterangan_bbl">
                                        <label>KETERANGAN</label>
                                    </div>
                                    <div class="text-center d-flex justify-content-between">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="simpanbbl" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Button trigger modal -->
            </div>
        </div>
    </div>
</div>

<style>
.tambah {
    position: fixed;
    bottom: 20px;
    left: 52%;
    transform: translateX(-50%);
    background-color: #006eff;
    z-index: 1040;
    width: 50px;
    height: 50px;
    box-shadow: 0px 0px 5px 0px #ffffff;
    -webkit-box-shadow: 0px 0px 5px 0px #ffffff;
    -moz-box-shadow: 0px 0px 5px 0px #ffffff;
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
        <a href="" data-bs-toggle="modal" data-bs-target="#tambahbbl">
            <ion-icon name="add-outline" class="text-white"></ion-icon>
        </a>
    </div>
</div>

<br>

<!-- NIK -->
<script>
    // Menambahkan event listener untuk 'nik_klien'
    document.getElementById('nik_klien').addEventListener('input', function() {
        const message_nik_klien = document.getElementById('message_nik_klien');
        if (this.value.length > 16) {
            message_nik_klien.style.display = 'block'; // Tampilkan pesan error
            message_nik_klien.textContent = 'Mohon cek kembali Nomor NIK.'; // Pesan error
        } else {
            message_nik_klien.style.display = 'none'; // Sembunyikan pesan error
        }
    });
</script>
<!-- NIK -->


