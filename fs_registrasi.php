

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
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="index.js"></script>



<div class="bg-white">
        <div class="mt-5">
            <form id="registrasiForm" action="" method="POST" style="position: relative; top:-30px;">
                <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
                <h4 class="text-center" style="position: relative;">REGISTRASI</h4> <br>
                <div class="row mx-3" style="border: 1px solid black; border-radius:7px;">
                    <!-- Kolom Kiri -->
                    <div class="col-lg-6 col-md-12 mt-3" style="border-radius:7px;">
                        <!-- NOMOR KARTU KELUARGA -->
                        <div class="form-group mb-3">
                            <label for="nomor_kk">NOMOR KARTU KELUARGA</label>
                            <input type="number" required name="nomor_kk" id="nomor_kk" class="form-control" style="text-transform: uppercase;">
                            <span id="message_kk" style="color: red; display: none;">Nomor KK tidak boleh lebih dari 16 angka.</span>
                        </div>

                        <!-- PROVINSI -->
                        <div class="form-group mb-3">
                            <label for="provinsi">PROVINSI</label>
                            <input type="text" required name="provinsi" class="form-control" value="SUMATERA UTARA" style="text-transform: uppercase;">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="kota">KOTA</label>
                            <input type="text" required name="kota" class="form-control" value="TANJUNGBALAI" style="text-transform: uppercase;">
                        </div>

                        <!-- KECAMATAN -->
                        <div class="form-group mb-3">
                            <label for="kecamatan">KECAMATAN</label>
                            <input type="text" required name="kecamatan" class="form-control" style="text-transform: uppercase;">
                        </div>

                        <!-- KELURAHAN -->
                        <div class="form-group mb-3">
                            <label for="kelurahan">KELURAHAN</label>
                            <input type="text" required name="kelurahan" class="form-control" style="text-transform: uppercase;">
                        </div>

                        <!-- KODE POS -->
                        <div class="form-group mb-3">
                            <label for="kode_pos">KODE POS</label>
                            <input type="number" required name="kode_pos" id="kode_pos" class="form-control" style="text-transform: uppercase;">
                            <span id="message_kodepos" style="color: red; display: none;"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="alamat">ALAMAT</label>
                            <input type="text" required name="alamat" class="form-control" style="text-transform: uppercase;">
                        </div>

                        <!-- NAMA PENGURUS BANSOS -->
                        <div class="mb-3" style="border:1px solid black; border-radius:7px;">
                            <div class="mx-2">
                                <div class="form-group mb-4">
                                    <label for="">NAMA PENGURUS BANSOS</label>
                                    <input type="text" required name="nama_pengurus_bansos" class="form-control" style="text-transform: uppercase;">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="">NIK</label>
                                    <input type="number" required name="nik_kepala_keluarga" id="nik_kepala_keluarga" class="form-control" style="text-transform: uppercase;">
                                    <span id="message_nik_kepala" style="color: red; display: none;"></span>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="">TANGGAL LAHIR</label>
                                    <input type="date" required name="tgl_lahir" class="form-control" style="text-transform: uppercase;">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="">TEMPAT LAHIR</label>
                                    <input type="text" required name="tpt_lahir" class="form-control" style="text-transform: uppercase;">
                                </div>

                                <div class="form-group rounded-3 p-3 mb-3">
                                    <label class="form-label text-primary">JENIS KELAMIN</label>
                                    <div class="d-flex justify-content-between">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenis_k" id="laki_laki" value="laki laki">
                                            <label class="form-check-label" for="laki_laki">LAKI LAKI</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenis_k" id="perempuan" value="perempuan">
                                            <label class="form-check-label" for="perempuan">PEREMPUAN</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="container">
                                    <div class="mb-3">
                                        <label for="agama_kepala_keluarga" class="form-label fw-bold">Agama</label>
                                        <select class="form-select" name="agama_kepala_keluarga" id="agama_kepala_keluarga">
                                            <option value="" selected>Pilih Agama</option>
                                            <option value="ISLAM">ISLAM</option>
                                            <option value="KRISTEN">KRISTEN</option>
                                            <option value="HINDU">HINDU</option>
                                            <option value="BUDDHA">BUDDHA</option>
                                            <option value="KONGHUAN">KONGHUAN</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="pendidikan_kepala" class="form-label fw-bold">Pendidikan</label>
                                        <select class="form-select" name="pendidikan_kepala" id="pendidikan_kepala">
                                            <option value="SD">SD</option>
                                            <option value="SMP">SMP</option>
                                            <option value="SMA">SMA</option>
                                            <option value="D3">D3</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group mb-3 mt-2">
                                    <label for="alamat">PENGHASILAN KEPALA KELUARGA</label>
                                    <input type="text" required name="penghasilan" class="form-control" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                        <!-- NAMA PENGURUS BANSOS -->

                        <!-- DOKUMEN -->
                        <div class="form-group border border-dark rounded-3 p-3 mb-3">
                            <h5 class="text-primary">DOKUMEN</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fc_ktp" id="fc_ktp">
                                <label class="form-check-label" for="fc_ktp">Fotocopy KTP</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fc_kk" id="fc_kk">
                                <label class="form-check-label" for="fc_kk">Fotocopy Kartu Keluarga</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sktm" id="sktm">
                                <label class="form-check-label" for="sktm">Surat Keterangan Tidak Mampu</label>
                            </div>
                        </div>
                    </div>
                    <!-- form kiri -->

               

                    <!-- form kanan -->
                    <div class="col-lg-6 col-md-12 mt-3" style="border-radius:7px;">
                        <div class="form-group mb-3">
                            <label for="alamat">NAMA PETUGAS</label>
                            <input type="text" required name="nama_fasilitator" value="<?php echo strtoupper($_SESSION['nama_lengkap']); ?>" class="form-control" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat">PEKERJAAN KEPALA KELUARGA</label>
                            <input type="text" required name="pekerjaan" class="form-control" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat">NAMA IBU KANDUNG</label>
                            <input type="text" required name="nama_ibu_kandung" class="form-control" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat">BANSOS TELAH DIPEROLEH</label>
                            <input type="text" required name="bansos_diperoleh" class="form-control" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat">PENGADUAN</label>
                            <input type="text" required name="pengaduan" class="form-control" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat">PELAYANAN</label>
                            <input type="text" required name="pelayanan" class="form-control" style="text-transform: uppercase;">
                        </div>

                        <!-- JUMLAH ANGGOTA KELUARGA -->
                        <div class="form-group mb-3">
                            <label for="jumlahAnggota">JUMLAH ANGGOTA KELUARGA</label>
                            <input type="number" required name="jumlah_anggota" id="jumlahAnggota" class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="alamat">NOMOR TLP/WA</label>
                            <input type="text" required name="nomor_tlp" class="form-control" style="text-transform: uppercase;">
                        </div>

                        <!-- STATUS DTSEN -->
                        <div class="form-group border border-dark rounded-3 p-3 mb-3">
                            <label class="form-label text-primary">STATUS DTSEN</label>
                            <div class="d-flex justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_dtsen" id="dtsen" value="dtsen">
                                    <label class="form-check-label" for="dtsen">DTSEN</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_dtsen" id="non_dtsen" value="non_dtsen">
                                    <label class="form-check-label" for="non_dtsen">NON DTSEN</label>
                                </div>
                            </div>
                        </div>

                        <!-- STATUS MCK -->
                        <div class="form-group border border-dark rounded-3 p-3 mb-3">
                            <label class="form-label text-primary">STATUS MCK</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="mck" id="milik_sendiri" value="milik_sendiri">
                                <label class="form-check-label" for="milik_sendiri">MILIK SENDIRI</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="mck" id="bersama" value="bersama">
                                <label class="form-check-label" for="bersama">FASILITAS BERSAMA</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="mck" id="umum" value="umum">
                                <label class="form-check-label" for="umum">UMUM</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="mck" id="tidakada" value="tidakada">
                                <label class="form-check-label" for="tidakada">TIDAK ADA</label>
                            </div>
                        </div>

                        <div class="form-group border border-dark rounded-3 p-3 mb-3">
                            <label class="form-label text-primary">STATUS RUMAH</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="status_rumah" id="milik_sendiri" value="milik_sendiri">
                                <label class="form-check-label" for="milik_sendiri">MILIK SENDIRI</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="status_rumah" id="menyewa" value="menyewa">
                                <label class="form-check-label" for="menyewa">MENYEWA</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="status_rumah" id="menumpang" value="menumpang">
                                <label class="form-check-label" for="menumpang">MENUMPANG</label>
                            </div>
                        </div>

                        <div class="form-group border border-dark rounded-3 p-3 mb-3">
                            <label class="form-label mb-2 text-primary">JENIS PENERANGAN</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="penerangan" id="pln_450" value="pln_450">
                                <label class="form-check-label" for="pln_450"><b>PLN 450 KWH</b></label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="penerangan" id="pln_900" value="pln_900">
                                <label class="form-check-label" for="pln_900"><b>PLN 900 KWH</b></label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="penerangan" id="token_450" value="token_450">
                                <label class="form-check-label" for="token_450"><b>TOKEN 450 KWH</b></label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="penerangan" id="token_900" value="token_900">
                                <label class="form-check-label" for="token_900"><b>TOKEN 900 KWH</b></label>
                            </div>
                        </div>  
                        <br>
                        <div class="form-group text-center">
                            <button type="submit" name="simpandata" class="btn btn-primary" style="width: 200px;">Simpan Data</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script>

        //jika nomor kk lebih dari 16 =============================================
        document.getElementById('nomor_kk').addEventListener('input', function() {
            const message_kk = document.getElementById('message_kk');
            if (this.value.length > 16) {
                message_kk.style.display = 'block'; // Tampilkan pesan error
                message_kk.textContent = 'Cek kembali Nomor Kartu Keluarga.';
            } else {
                message_kk.style.display = 'none'; // Sembunyikan pesan error
            }
        });
        // Menambahkan event listener untuk 'nik_kepala_keluarga'
        document.getElementById('nik_kepala_keluarga').addEventListener('input', function() {
            const message_nik_kepala = document.getElementById('message_nik_kepala');
            if (this.value.length > 17) {
                message_nik_kepala.style.display = 'block'; // Tampilkan pesan error
                message_nik_kepala.textContent = 'Cek kembali NIK.'; // Pesan error
            } else {
                message_nik_kepala.style.display = 'none'; // Sembunyikan pesan error
            }
        });
        // Menambahkan event listener untuk 'kode pos'
        document.getElementById('kode_pos').addEventListener('input', function() {
            const message_kodepos = document.getElementById('message_kodepos');
            const value = this.value;

            // Cek apakah nilai adalah angka dan panjangnya lebih dari 5
            if (value.length > 5) {
                message_kodepos.style.display = 'block'; // Tampilkan pesan error
                message_kodepos.textContent = 'Kode Pos tidak boleh lebih.'; // Pesan error
            } else {
                message_kodepos.style.display = 'none'; // Sembunyikan pesan error
            }
        });

</script>

 


<?php 
include "koneksi.php";

// Check if the user is logged in on the dashboard page
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Anda harus login terlebih dahulu.'); window.location.href = 'index.php';</script>";
    exit;
}

$user_id = $_SESSION['id']; // Mengambil ID user dari session

// Cek apakah user_id valid di tabel users
$checkUser_Query = "SELECT * FROM users WHERE id = ?";
$stmt = $koneksi->prepare($checkUser_Query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('User ID tidak valid.'); window.location.href = 'dashboard.php';</script>";
    exit; 
}

// Proses penyimpanan data registrasi.
if (isset($_POST['simpandata'])) {
    date_default_timezone_set('Asia/Jakarta');
    $tgl = date('Y-m-d H:i:s');
    $tahun_sekarang = date('Y');
    $nomor_kk = htmlspecialchars($_POST['nomor_kk'], ENT_QUOTES);
    
    // Cegah pendaftaran ulang nomor KK pada tahun yang sama.
    $cekKK = "SELECT tanggal FROM registrasi WHERE nomor_kk = ? AND YEAR(tanggal) = ?";
    $stmt = $koneksi->prepare($cekKK);
    $stmt->bind_param("si", $nomor_kk, $tahun_sekarang);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($tanggal_terdaftar);
        $stmt->fetch();
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Nomor KK Sudah Terdaftar!',
                text: 'Pada $tanggal_terdaftar.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'fs_dashboard.php?page=fs_registrasi';
                }
            });
        </script>";
        exit;
    }
    
    
    // Ambil dan bersihkan seluruh nilai dari formulir.
    $provinsi = htmlspecialchars($_POST['provinsi'], ENT_QUOTES);
    $kota = htmlspecialchars($_POST['kota'], ENT_QUOTES);
    $kecamatan = htmlspecialchars($_POST['kecamatan'], ENT_QUOTES);
    $kelurahan = htmlspecialchars($_POST['kelurahan'], ENT_QUOTES);
    $alamat = htmlspecialchars($_POST['alamat'], ENT_QUOTES);
    $kode_pos = htmlspecialchars($_POST['kode_pos'], ENT_QUOTES);
    $nama_pengurus_bansos = htmlspecialchars($_POST['nama_pengurus_bansos'], ENT_QUOTES);
    $nik_kepala_keluarga = htmlspecialchars($_POST['nik_kepala_keluarga'], ENT_QUOTES);
    $tgl_lahir = htmlspecialchars($_POST['tgl_lahir'], ENT_QUOTES);
    $tpt_lahir = htmlspecialchars($_POST['tpt_lahir'], ENT_QUOTES);
    $agama_kepala_keluarga = htmlspecialchars($_POST['agama_kepala_keluarga'], ENT_QUOTES);
    $pendidikan_kepala = htmlspecialchars($_POST['pendidikan_kepala'], ENT_QUOTES);
    $fc_ktp = isset($_POST['fc_ktp']) ? 1 : 0;
    $fc_kk = isset($_POST['fc_kk']) ? 1 : 0;
    $sktm = isset($_POST['sktm']) ? 1 : 0;
    $status_dtsen = htmlspecialchars($_POST['status_dtsen'], ENT_QUOTES);
    $mck = htmlspecialchars($_POST['mck'], ENT_QUOTES);
    $nama_fasilitator = htmlspecialchars($_POST['nama_fasilitator'], ENT_QUOTES);
    $pekerjaan = htmlspecialchars($_POST['pekerjaan'], ENT_QUOTES);
    $nama_ibu_kandung = htmlspecialchars($_POST['nama_ibu_kandung'], ENT_QUOTES);
    $status_rumah = htmlspecialchars($_POST['status_rumah'], ENT_QUOTES);
    $penerangan = htmlspecialchars($_POST['penerangan'], ENT_QUOTES);
    $bansos_diperoleh = htmlspecialchars($_POST['bansos_diperoleh'], ENT_QUOTES);
    $pengaduan = htmlspecialchars($_POST['pengaduan'], ENT_QUOTES);
    $pelayanan = htmlspecialchars($_POST['pelayanan'], ENT_QUOTES);
    $penghasilan = htmlspecialchars($_POST['penghasilan'], ENT_QUOTES);
    $nomor_tlp = htmlspecialchars($_POST['nomor_tlp'], ENT_QUOTES);
    $anggota_keluarga = intval($_POST['jumlah_anggota']);

    // Simpan data registrasi utama ke database.
    $simpan = mysqli_query($koneksi, "INSERT INTO registrasi (user_id, tanggal, nomor_kk, provinsi, kota, kecamatan, kelurahan, alamat, kode_pos, nama_pengurus_bansos, nik_kepala_keluarga, tgl_lahir, tpt_lahir, agama_kepala_keluarga, pendidikan_kepala, fc_ktp, fc_kk, sktm, status_dtsen, mck, nama_fasilitator, pekerjaan, nama_ibu_kandung, status_rumah, penerangan, bansos_diperoleh, pengaduan, pelayanan, penghasilan, nomor_tlp, anggota_keluarga) VALUES ('$user_id', '$tgl', '$nomor_kk', '$provinsi', '$kota', '$kecamatan', '$kelurahan', '$alamat', '$kode_pos', '$nama_pengurus_bansos', '$nik_kepala_keluarga', '$tgl_lahir', '$tpt_lahir', '$agama_kepala_keluarga', '$pendidikan_kepala', '$fc_ktp', '$fc_kk', '$sktm', '$status_dtsen', '$mck', '$nama_fasilitator', '$pekerjaan', '$nama_ibu_kandung', '$status_rumah', '$penerangan', '$bansos_diperoleh', '$pengaduan', '$pelayanan', '$penghasilan', '$nomor_tlp', '$anggota_keluarga')");

    if ($simpan) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data berhasil disimpan.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'fs_dashboard.php?page=fs_daftar_bansos';
                }
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Simpan data gagal: " . mysqli_error($koneksi) . "',
                confirmButtonText: 'Coba Lagi'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'fs_dashboard.php?page=fs_registrasi';
                }
            });
        </script>";
    }    
}
?>


<br>
<br>
<br>

