<?php
// Koneksi ke database
include 'koneksi.php'; // Ganti dengan file koneksi Anda

// Mendapatkan data dari permintaan
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['id']) && isset($data['hak_akses'])) {
    $id = $data['id'];
    $hak_akses = $data['hak_akses'];

    // Query untuk memperbarui status
    $query = "UPDATE users SET hak_akses = ? WHERE id = ?";
    $stmt = $koneksi->prepare($query);

    if ($stmt) {
        $stmt->bind_param("si", $hak_akses, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . $koneksi->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
?>