<?php

include "koneksi.php";
// Koneksi ke database (sesuaikan dengan konfigurasi database Anda)
// $koneksi = mysqli_connect("localhost", "root", "", "slrt");
// if (!$koneksi) {
//     die("Koneksi database gagal: " . mysqli_connect_error());
// }

// Check if the user is logged in on the dashboard page
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Anda harus login terlebih dahulu.'); window.location.href = 'index.php';</script>";
    exit;
}

$user_id = $_SESSION['id']; // Mengambil ID user dari session

// Pastikan variabel $simpan didefinisikan sebelum digunakan
$simpan = isset($_POST['simpandata']) ? true : false;

if ($simpan) {
    $anggota_keluarga = isset($_POST['jumlah_anggota']) ? (int)$_POST['jumlah_anggota'] : 0;

    for ($i = 1; $i <= $anggota_keluarga; $i++) {
        $nomor_kk_anggota = htmlspecialchars($_POST["nomor_kk_$i"], ENT_QUOTES);
        $nama_lengkap = htmlspecialchars($_POST["nama_lengkap_$i"], ENT_QUOTES);
        $nik = htmlspecialchars($_POST["nik_$i"], ENT_QUOTES);
        $tanggal_lahir = htmlspecialchars($_POST["tanggal_lahir_$i"], ENT_QUOTES);
        $tempat_lahir = htmlspecialchars($_POST["tempat_lahir_$i"], ENT_QUOTES);
        $jenis_kelamin = htmlspecialchars($_POST["jenis_kelamin_$i"], ENT_QUOTES);
        $agama = htmlspecialchars($_POST["agama_$i"], ENT_QUOTES);
        $pendidikan = htmlspecialchars($_POST["pendidikan_$i"], ENT_QUOTES);
        $jenis_pekerjaan = htmlspecialchars($_POST["jenis_pekerjaan_$i"], ENT_QUOTES);

        // Check for duplicate KK
        // $checkKK = mysqli_query($koneksi, "SELECT * FROM anggota_keluarga WHERE nomor_kk_anggota = '$nomor_kk_anggota'");
        // if (mysqli_num_rows($checkKK) > 0) {
        //     echo "<script>alert('KK $nomor_kk_anggota sudah terdaftar. Silakan masukkan KK yang berbeda.');</script>";
        //     continue; // Skip this iteration if KK already exists
        // }

        // Simpan data anggota keluarga
        $query = "INSERT INTO anggota_keluarga (nomor_kk, nama_lengkap, nik, tanggal_lahir, tempat_lahir, jenis_kelamin, agama, pendidikan, jenis_pekerjaan)
                  VALUES ('$nomor_kk_anggota', '$nama_lengkap', '$nik', '$tanggal_lahir', '$tempat_lahir', '$jenis_kelamin', '$agama', '$pendidikan', '$jenis_pekerjaan')";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Berhasil simpan Data.'); window.location='op_dashboard.php?page=daftar_bansos'</script>";
        } else {
            echo "<script>alert('Simpan data Gagal: " . mysqli_error($koneksi) . "'); window.location='op_dashboard.php?page=daftar_bansos'</script>";
        }
    }
}
?>
<meta name="theme-color" content="#f8fafa">
    <link rel="manifest" href="manifest.json">


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="assets/dist/css/adminlte.min.css">



<div class="mx-2 bg-white">
    <h4 class="text-center">Tambah Data Anggota Keluarga</h4>
    <div class="col-lg-12 mt-3 d-inline-block" style="border: 1px solid black; border-radius:7px;">
        <form action="" method="POST" id="registrasiForm">
            <!-- JUMLAH ANGGOTA KELUARGA -->
            <!-- <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>"> -->
            <div class="mx-2 mt-2">
                <div class="form-group mb-3">
                    <label for="nomor_kk">NOMOR KARTU KELUARGA</label>
                    <input type="number" required name="nomor_kk" id="nomor_kk" class="form-control" style="text-transform: uppercase;">
                    <span id="message_kk" style="color: red; display: none;">Nomor KK tidak boleh lebih dari 16 angka.</span>
                </div>
                <div class="input-field form-group mb-3">
                    <label for="jumlahAnggota">JUMLAH ANGGOTA KELUARGA</label>
                    <input type="number" required name="jumlah_anggota" id="jumlahAnggota" class="form-control">
                </div>
                <!-- TOMBOL -->
                <div class="form-group mb-2 mt-3" style="position: relative; margin-left: 35px;">
                    <button type="button" id="btnSelanjutnya" class="btn btn-primary" style="width: 250px;">Selanjutnya</button>
                </div>
            </div>

            <!-- Container for Dynamic Family Member Forms -->
            <div id="dynamicFormsContainer" class="mx-1"></div>

            <br>
            <div class="form-group d-flex justify-content-center">
                <button type="submit" name="simpandata" class="btn btn-primary" style="width: 250px; display:none;">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- //TOMBOL HIDDEN -->
<script>
    // Ambil referensi tombol Simpan Data dan form
    const simpanDataBtn = document.querySelector('button[name="simpandata"]');
    const registrasiForm = document.getElementById('registrasiForm');

    // Sembunyikan tombol Simpan Data secara default
    simpanDataBtn.style.display = 'none';

    // Event listener untuk tombol "Selanjutnya"
    document.getElementById('btnSelanjutnya').addEventListener('click', function () {
        const jumlahAnggota = parseInt(document.getElementById('jumlahAnggota').value);
        if (isNaN(jumlahAnggota) || jumlahAnggota <= 0) {
            alert('Jumlah anggota keluarga tidak valid.');
            return;
        }

        const dynamicFormsContainer = document.getElementById('dynamicFormsContainer');
        dynamicFormsContainer.innerHTML = '';

        const nomorKK = document.getElementById('nomor_kk').value; // Ambil nomor KK dari form registrasi

        for (let i = 1; i <= jumlahAnggota; i++) {
            const formGroup = document.createElement('div');
            formGroup.classList.add('form-group');
            formGroup.innerHTML = `
                <div class="mx-1">
                    <div class="col-lg-12 mt-5 d-inline-block" style="border: 1px solid black; border-radius:7px;">
                        <h5 class="text-primary mt-3">Data Anggota Keluarga ${i}</h5> <br>
                        <label>Nomor KK</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="copyNomorKK_${i}" required>
                            <label class="form-check-label" for="copyNomorKK_${i}">Salin</label>
                        </div>
                        <input type="number" class="form-control" name="nomor_kk_${i}" required> <br>

                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_lengkap_${i}" required style="text-transform:uppercase;"> <br>
                        <label>NIK</label>
                        <input type="number" class="form-control" name="nik_${i}" required> <br <label>Tanggal Lahir</label>
                        <input type="date" class="form-control" name="tanggal_lahir_${i}" required> <br>
                        <label>Tempat Lahir</label>
                        <input type="text" class="form-control" name="tempat_lahir_${i}" required> <br>
                        <label>Jenis Kelamin</label>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="jenis_kelamin_${i}" value="L" required>
                            <label class="form-check-label">Laki-laki</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="jenis_kelamin_${i}" value="P" required>
                            <label class="form-check-label">Perempuan</label>
                        </div> <br>
                        <label>Agama</label> <br>
                        <select name="agama_${i}" class="col-lg-12" style="border:none;" required>
                            <option value="ISLAM">ISLAM</option>
                            <option value="KRISTEN">KRISTEN</option>
                            <option value="BUDHA">BUDHA</option>
                            <option value="HINDU">HINDU</option>
                            <option value="KONGHUAN">KONGHUAN</option>
                        </select> <br> <br>
                        <label>Pendidikan</label> <br>
                        <select name="pendidikan_${i}" class="col-lg-12" style="border:none;" required>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select> <br> <br>
                        <label>Jenis Pekerjaan</label> <br>
                        <input type="text" class="form-control" name="jenis_pekerjaan_${i}" required style="text-transform:uppercase;">
                        <br> <br>
                    </div>
                </div>
            `;

            dynamicFormsContainer.appendChild(formGroup);
        }

        // Tampilkan tombol Simpan Data setelah tombol Selanjutnya diklik
        simpanDataBtn.style.display = 'block';

        // Event listener untuk checkbox di dalam loop
        dynamicFormsContainer.querySelectorAll('input[type="checkbox"]').forEach((checkbox, index) => {
            checkbox.addEventListener('change', function () {
                const nomorKKInput = checkbox.closest('.form-group').querySelector(`input[name="nomor_kk_${index + 1}"]`);
                if (this.checked) {
                    nomorKKInput.value = nomorKK; // Salin nomor KK jika checkbox dicentang
                } else {
                    nomorKKInput.value = ''; // Kosongkan jika checkbox tidak dicentang
                }
            });
        });
    });

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
</script>

