


     <?php

// Cek apakah user_id ada dalam sesi
if (!isset($_SESSION['id'])) {
    header("Location: fs_dashboard.php?page=menu");
    exit();
}

$user_id = $_SESSION['id'];

// HANDLE UPLOAD TTD
$upload_message = '';
$upload_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['ttd_image'])) {
    $upload_dir = 'uploads/ttd/';
    
    // Buat folder jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_name = $_FILES['ttd_image']['name'];
    $file_tmp = $_FILES['ttd_image']['tmp_name'];
    $file_size = $_FILES['ttd_image']['size'];
    $file_error = $_FILES['ttd_image']['error'];
    
    // Validasi file
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if ($file_error === UPLOAD_ERR_OK) {
        if (in_array($file_extension, $allowed_extensions)) {
            if ($file_size <= 2 * 1024 * 1024) { // Max 2MB
                // Generate nama file unik
                $new_file_name = 'ttd_' . $user_id . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_file_name;
                
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    // Update database
                    $query = "UPDATE users SET gbr_ttd = ? WHERE id = ?";
                    $stmt = mysqli_prepare($koneksi, $query);
                    mysqli_stmt_bind_param($stmt, "si", $new_file_name, $user_id);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $upload_success = true;
                        $upload_message = "Tanda Tangan berhasil diupload!";
                        
                        // Update session jika perlu
                        $_SESSION['gbr_ttd'] = $new_file_name;
                    } else {
                        $upload_message = "Gagal menyimpan ke database: " . mysqli_error($koneksi);
                        // Hapus file yang sudah diupload
                        unlink($upload_path);
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $upload_message = "Gagal mengupload file.";
                }
            } else {
                $upload_message = "Ukuran file terlalu besar. Maksimal 2MB.";
            }
        } else {
            $upload_message = "Format file tidak didukung. Gunakan JPG, JPEG, PNG, GIF, atau BMP.";
        }
    } else {
        $upload_message = "Error upload: " . $file_error;
    }
}

// HANDLE DELETE TTD
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ttd'])) {
    // Ambil nama file TTD dari database
    $query = "SELECT gbr_ttd FROM users WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $current_ttd);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    if ($current_ttd) {
        // Hapus file dari server
        $file_path = 'uploads/ttd/' . $current_ttd;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Update database
        $query = "UPDATE users SET gbr_ttd = NULL WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $upload_message = "Tanda Tangan berhasil dihapus!";
            $_SESSION['gbr_ttd'] = null;
        } else {
            $upload_message = "Gagal menghapus Tanda Tangan dari database.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Ambil data TTD user
$query = "SELECT gbr_ttd FROM users WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $gbr_ttd);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
?>

<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.upload-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.ttd-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.ttd-card:hover {
    transform: translateY(-5px);
}

.ttd-image {
    max-height: 300px;
    object-fit: contain;
    border: 2px solid #e9ecef;
    border-radius: 8px;
}

.upload-btn {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
}

.delete-btn {
    background: linear-gradient(135deg, #dc3545, #e83e8c);
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
}

.alert-custom {
    border-radius: 10px;
    border: none;
}

.file-input-custom {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.file-input-custom:hover {
    border-color: #28a745;
    background: #e8f5e8;
}

.file-input-custom.dragover {
    border-color: #28a745;
    background: #d4edda;
}

.upload-preview {
    max-width: 200px;
    max-height: 150px;
    display: none;
    margin: 0 auto;
    border-radius: 8px;
}
</style>

<!-- UPLOAD SECTION -->
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="upload-section">
                <h4 class="text-center mb-4">UPLOAD TANDA TANGAN</h4>
                
                <!-- Notifikasi -->
                <?php if (!empty($upload_message)): ?>
                    <div class="alert alert-custom <?php echo $upload_success ? 'alert-success' : 'alert-danger'; ?>">
                        <i class="fas <?php echo $upload_success ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?> me-2"></i>
                        <?php echo $upload_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Form Upload -->
                <form method="POST" action="" enctype="multipart/form-data" id="uploadForm">
                    <div class="file-input-custom mb-3" id="fileDropArea">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Drag & Drop Foto Tanda Tangan disini</h5>
                        <p class="text-muted small">atau</p>
                        <input type="file" name="ttd_image" id="ttd_image" accept=".jpg,.jpeg,.png,.gif,.bmp" class="d-none" required>
                        <label for="ttd_image" class="btn btn-outline-primary">
                            <i class="fas fa-folder-open me-2"></i>Pilih File
                        </label>
                        <div class="mt-2">
                            <small class="text-muted">Format: JPG, JPEG, PNG, GIF, BMP | Maksimal: 2MB</small>
                        </div>
                    </div>
                    
                    <!-- Preview Gambar -->
                    <div class="text-center mb-3">
                        <img id="imagePreview" class="upload-preview img-thumbnail" alt="Preview TTD">
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn upload-btn">
                            <i class="fas fa-upload me-2"></i>UPLOAD Tanda Tangan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- TAMPILAN TTD -->
<section class="mx-2 mt-4">
    <div class="card-body mx-2 bg-white border bordered">
        <h4 class="text-center mb-4">TANDA TANGAN SAYA</h4>
        
        <?php if (!empty($gbr_ttd)): ?>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card ttd-card">
                        <div class="card-body p-4 text-center">
                            <!-- Gambar TTD -->
                            <div class="mb-3">
                                <img 
                                    src="uploads/ttd/<?= htmlspecialchars($gbr_ttd) ?>" 
                                    class="img-fluid ttd-image" 
                                    alt="Tanda Tangan <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>"
                                    onerror="this.src='https://via.placeholder.com/400x200/6c757d/ffffff?text=Tanda tangan+Tidak+Tersedia'"
                                >
                            </div>
                            
                            <!-- Informasi -->
                            <div class="image-info">
                                <div class="row text-center mb-3">
                                    <div class="col-12">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tombol Hapus -->
                            <form method="POST" action="" onsubmit="return confirm('Apakah Anda yakin ingin menghapus TTD ini?')">
                                <button type="submit" name="delete_ttd" class="btn delete-btn btn-sm">
                                    <i class="fas fa-trash me-1"></i>Hapus Tanda Tangan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>

            
            <!-- Placeholder jika tidak ada TTD -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body py-5">
                            <div class="no-image-placeholder">
                                <i class="fas fa-signature fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Tanda Tangan Kosong</h5>
                                <p class="text-muted small">Upload Tanda Tangan Anda pada fitur form di atas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- JavaScript untuk Preview dan Drag & Drop -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('ttd_image');
    const fileDropArea = document.getElementById('fileDropArea');
    const imagePreview = document.getElementById('imagePreview');
    const uploadForm = document.getElementById('uploadForm');

    // Preview gambar saat dipilih
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // Drag & Drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileDropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        fileDropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        fileDropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        fileDropArea.classList.add('dragover');
    }

    function unhighlight() {
        fileDropArea.classList.remove('dragover');
    }

    fileDropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        
        // Trigger change event untuk preview
        const event = new Event('change');
        fileInput.dispatchEvent(event);
    }

    // Validasi form sebelum submit
    uploadForm.addEventListener('submit', function(e) {
        const file = fileInput.files[0];
        if (!file) {
            e.preventDefault();
            alert('Pilih file TTD terlebih dahulu!');
            return false;
        }
        
        // Validasi ukuran file (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            e.preventDefault();
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            return false;
        }
        
        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp'];
        if (!allowedTypes.includes(file.type)) {
            e.preventDefault();
            alert('Format file tidak didukung. Gunakan JPG, JPEG, PNG, GIF, atau BMP.');
            return false;
        }
    });
});
</script>

