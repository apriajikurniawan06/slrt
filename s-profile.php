<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<style>
    /* Gaya dasar untuk ikon kamera */
.camera-icon {
    width: 30%; /* Ukuran relatif terhadap container */
    height: auto; /* Tinggi menyesuaikan */
    aspect-ratio: 1/1; /* Memastikan bentuk lingkaran */
    max-width: 40px; /* Batas maksimum ukuran */
    max-height: 40px; /* Batas maksimum ukuran */
    position: absolute; /* Posisi absolut untuk penempatan */
    top: calc(50% - 100px); /* Posisi vertikal di tengah, agak ke atas 100px */
    left: 50%; /* Posisi horizontal di tengah */
    transform: translate(-50%, -50%); /* Pusatkan ikon */
}

/* Media Query untuk perangkat kecil (smartphone) */
@media (max-width: 480px) {
    .camera-icon {
        width: 50%; /* Ukuran lebih besar di layar kecil */
        max-width: 35px; /* Batas maksimum ukuran di smartphone */
        max-height: 35px;
        top: calc(50% - 170px); /* Posisi agak ke atas 120px */
        left: 62%; /* Geser ke kanan */
    }
}

@media (min-width: 481px) and (max-width: 598px) {
    .camera-icon {
        width: 40%;
        max-width: 35px;
        max-height: 35px;
        top: calc(50% - 160px);
        left: 113%;
    }
}


/* Media Query untuk tablet (lebar layar 481px - 768px) */
/* Tablet kecil (600px - 768px) */
@media (min-width: 600px) and (max-width: 768px) {
    .camera-icon {
        width: 40%;
        max-width: 35px;
        max-height: 35px;
        top: calc(50% - 163px);
        left: 115%;
    }
}

/* 
@media (min-width: 771px) and (max-width: 1016px) {
    .camera-icon {
        width: 40%;
        max-width: 35px;
        max-height: 35px;
        top: -163px;
        left: 115%;
        position: relative;
    }
} */



/* Tablet besar (901px - 1024px) */
@media (min-width: 901px) and (max-width: 1024px) {
    .camera-icon {
        width: 30%;
        max-width: 45px;
        max-height: 45px;
        top: calc(50% - 160px);
        left: 50%;
    }
}

/* Media Query untuk laptop (lebar layar 769px - 1024px) */
@media (min-width: 769px) and (max-width: 1024px) {
    .camera-icon {
        width: 35%; /* Ukuran lebih besar di layar kecil */
        max-width: 40px; /* Batas maksimum ukuran di laptop */
        max-height: 40px;
        top: calc(50% - 100px); /* Posisi agak ke atas 100px */
        left: calc(65% - 75%); /* Posisi horizontal di tengah */
    }
}

/* Media Query untuk PC (lebar layar >= 1025px) */
@media (min-width: 1025px) {
    .camera-icon {
        width: 30%; /* Ukuran lebih besar di layar kecil */
        max-width: 35px; /* Batas maksimum ukuran di PC */
        max-height: 35px;
        top: -170px; /* Posisi agak ke atas 100px */
        left: 109%; /* Posisi horizontal di tengah */
    }
}


#togglePassword {
    cursor: pointer;
    transition: all 0.2s ease;
}

#togglePassword:hover {
    opacity: 0.8;
    transform: scale(1.05);
}
</style>





<?php 
// Cek apakah user sudah login
if (!isset($_SESSION['id'])) {
    header("Location: fs_dashboard.php?page=fs-profile");
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['id'];

// Cek apakah variabel sesi yang diperlukan ada
if (empty($_SESSION['username']) || empty($_SESSION['level']) || empty($_SESSION['nama_lengkap'])) {
    echo "<script>alert('Maaf, User tidak terkoneksi.');</script>";
    header("Location: fs_dashboard.php?page=menu");
    exit();
}

// Pastikan $user_id sudah didefinisikan sebelumnya
if (isset($user_id)) {
    // Query untuk mengambil data gambar dan informasi lainnya dari tabel users
    $query = "SELECT klrhn, kcmtn, no_wa, gambar FROM users WHERE id = ?";
    $stmt = $koneksi->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Periksa apakah query berhasil
        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();
            // Simpan data ke dalam session
            $_SESSION['gambar'] = $data['gambar'];
            $_SESSION['klrhn'] = $data['klrhn'];
            $_SESSION['kcmtn'] = $data['kcmtn'];
            $_SESSION['no_wa'] = $data['no_wa'];
        } else {
            echo "<script>alert('Gagal mengambil data gambar.');</script>";
        }

        $stmt->close(); // Tutup statement setelah selesai
    } else {
        echo "<script>alert('Gagal mempersiapkan statement.');</script>";
    }
} else {
    echo "<script>alert('User  ID tidak ditemukan.');</script>";
}

// UPDATE PROFIL
// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateSuccess = false; // Flag untuk menandakan apakah update berhasil
    $isUpdated = false; // Flag untuk menandakan apakah ada perubahan

    // Cek apakah ada file gambar yang diupload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileSize = $_FILES['foto']['size'];
        $fileType = $_FILES['foto']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Validasi ekstensi file
        $allowedfileExtensions = array('jpg', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Tentukan lokasi penyimpanan file
            $newFileName = 'upload/' . $user_id . '.' . $fileExtension; // Ganti dengan path yang sesuai
            move_uploaded_file($fileTmpPath, $newFileName);

            // Update gambar di database
            $updateQuery = "UPDATE users SET gambar = ? WHERE id = ?";
            $updateStmt = $koneksi->prepare($updateQuery);
            $updateStmt->bind_param("si", $newFileName, $user_id);
            $updateStmt->execute();
            $updateStmt->close();

            // Update session gambar
            $_SESSION['gambar'] = $newFileName;
            $updateSuccess = true; // Set flag ke true jika gambar berhasil diupdate
            $isUpdated = true; // Set flag ke true jika ada perubahan
        } else {
            echo "<script>alert('Ekstensi file tidak valid.');</script>";
        }
    }

    // Cek apakah ada password baru yang diinput
    // Cek apakah ada password baru yang diinput
if (!empty($_POST['password'])) {
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
     // Enkripsi password menggunakan MD5

    // Update password di database
    $updatePasswordQuery = "UPDATE users SET password = ? WHERE id = ?";
    $updatePasswordStmt = $koneksi->prepare($updatePasswordQuery);
    $updatePasswordStmt->bind_param("si", $newPassword, $user_id);
    $updatePasswordStmt->execute();
    $updatePasswordStmt->close();
    $updateSuccess = true; // Set flag ke true jika password berhasil diupdate
    $isUpdated = true; // Set flag ke true jika ada perubahan

    // Setelah mengupdate password, logout dan arahkan pengguna untuk login kembali
    session_destroy(); // Menghancurkan session saat ini
    echo "<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil! Password Anda telah diperbarui. Silakan login kembali.',
        confirmButtonText: 'OK'
    }).then(function() {
        window.location = 'index.php'; // Redirect ke halaman login
    });
    </script>";
    exit();
}

if (!empty($_POST['no_wa'])) {
    $newNoWa = mysqli_real_escape_string($koneksi, trim($_POST['no_wa']));
    $updateNoWaQuery = "UPDATE users SET no_wa = ? WHERE id = ?";
    $updateNoWaStmt = $koneksi->prepare($updateNoWaQuery);
    $updateNoWaStmt->bind_param("si", $newNoWa, $user_id);
    $updateNoWaStmt->execute();
    $updateNoWaStmt->close();
    $isUpdated = true; // Set flag ke true jika ada perubahan no_wa
}

// Jika ada perubahan pada no_wa saja, tampilkan alert sukses
if ($isUpdated) {
    echo "<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil! Nomor WhatsApp telah diperbarui.',
        confirmButtonText: 'OK'
    }).then(function() {
        window.location = 'fs-profile.php'; // Redirect ke halaman profil
    });
    </script>";
} else {
    echo "<script>
    Swal.fire({
        icon: 'info',
        title: 'Tidak ada perubahan data.',
        confirmButtonText: 'OK'
    });
    </script>";
}
    // Tampilkan notifikasi berdasarkan hasil update
    if ($isUpdated) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Profil Anda telah diperbarui.',
            confirmButtonText: 'OK'
        });
        </script>";
    } else {
        echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: 'Tidak ada perubahan data.',
            confirmButtonText: 'OK'
        });
        </script>";
    }
}
?>


<div class="mx-2 d-flex align-items-center justify-content-center">
    <div class="card bg- shadow" style="width: 100%; border-radius: 15px;">
        <div class="card-header text-center text-dark">
            <h5 class="mt-1 text-bold"><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Tidak ada nama User') ?></h5>
        </div>
        <div class="card-body">
            <!-- GAMBAR -->
            <?php if (!empty($_SESSION['gambar'])) { ?>
                <div class="text-center">
                <img src="<?= htmlspecialchars($_SESSION['gambar']); ?>" class="img-circle mb-3 shadow-sm" style="width: 130px; height:130px; border: 1px solid black;">
                </div>
            <?php } else { ?>
                <div class="text-center">
                    <i class="text-dark fas fa-user" style="font-size: 100px;"></i>
                    <i class="text-dark fas fa-question" style="font-size: 100px;"></i>
                </div>
            <?php }?>
            <!-- GAMBAR --> 
            <br>
            <br>
            <div class="d-flex flex-column">
                <div>
                    <strong>KELURAHAN:</strong> <?= htmlspecialchars($_SESSION['klrhn'] ?? '---') ?>
                </div>
                <div>
                    <strong>KECAMATAN:</strong> <?= htmlspecialchars($_SESSION['kcmtn'] ?? '---') ?>
                </div>

                <div>
                    <strong>Nomor Whatsapp:</strong> <?= htmlspecialchars($_SESSION['no_wa'] ?? '---') ?>
                </div>
            </div>
            <!-- Form untuk update foto dan password -->
            <form method="POST" enctype="multipart/form-data" class="mt-3 bg-white" id="updateProfileForm">
                <div class="row">
                    <div class="col-sm-6 mb-4 text-center">
                        <!-- Ikon Kamera -->
                        <div class="icon-container" style="position: relative; width: 100%; max-width: 600px; margin: 0 auto;">
                            <!-- Ikon Kamera -->
                            <label for="foto" class="btn btn-outline-white bg-primary rounded-circle d-flex align-items-center justify-content-center camera-icon">
                                <i class="fas fa-camera fa-lg text-white"></i>
                            </label>
                        </div>
                        <input type="file" class="form-control d-none" id="foto" name="foto" accept="image/*">
                    </div>

                    


                    <div class="col-sm-3 mb-2 d-flex">
                        <span style="position: relative; top:10px; left:-5px;"><i class="fas fa-phone text-primary"></i></span>
                        <input type="number" class="form-control" id="no_wa" name="no_wa" placeholder="Whatsapp Baru">
                    </div>

                    <div class="col-sm-3 d-flex">
                        <div class="input-group">
                            <!-- Ikon Kunci -->
                            <span style="position: relative; top:10px; left:-5px;"><i class="fas fa-lock text-primary me-1"></i></span>
                            
                            <!-- Input Password -->
                            <input type="password" class="form-control y-2" id="password" name="password" placeholder="Password Baru">
                            
                            <!-- Ikon Toggle Mata -->
                            <span style="position: relative; top:10px; right:-10px;">
                                <i class="fas fa-eye text-danger" id="togglePassword" style="cursor: pointer;"></i>
                            </span>
                        </div>
                    </div>

                    <!-- JavaScript untuk Toggle Password -->
                          <script>
                    document.getElementById("togglePassword").addEventListener("click", function() {
                        const passwordInput = document.getElementById("password");
                        const icon = this;
                        
                        // Toggle password visibility
                        if (passwordInput.type === "text") {
                            passwordInput.type = "password";
                            icon.classList.remove("fa-eye");
                            icon.classList.add("fa-eye-slash");
                        } else {
                            passwordInput.type = "text";
                            icon.classList.remove("fa-eye-slash");
                            icon.classList.add("fa-eye");
                        }
                    });
                </script>


                    
                </div> <br>
                <div class="card-footer text-end bg-white text-center">
                    <button type="submit" class="btn btn-success w-50">Update Profil</button>
                </div>
            </form>
        </div>
    </div>
</div>








<script>
    $(document).ready(function() {
        $('#updateProfileForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah form dari submit default

            $.ajax({
                type: 'POST',
                url: '', // URL yang sama dengan halaman ini
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(response) {
                // Tampilkan SweetAlert jika update berhasil
                Swal.fire({
                    icon: 'success',
                    title: 'Profil berhasil diperbarui!',
                    text: 'Data profil Anda telah diperbarui.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tambahkan delay 5 detik sebelum reload
                        setTimeout(function() {
                            location.reload();
                        }, 5000); // 5000 ms = 5 detik
                    }
                });
            },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat memperbarui profil.',
                    });
                }
            });
        });
    });
</script>

<!-- jQuery CDN dari jsDelivr -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

<!-- jQuery CDN dari jQuery resmi -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
