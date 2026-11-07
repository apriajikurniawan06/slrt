<?php 
// Sertakan file koneksi
include "koneksi.php";

// APPROVE operator===============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set header untuk JSON
    header('Content-Type: application/json');
    // Ambil input JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Debugging: Tampilkan input diterima
    error_log(print_r($input, true)); // Ini akan mencetak input ke log error

    if (!isset($input['id']) || !isset($input['Approve_Sekretaris'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Data tidak lengkap.']);
        exit;
    }

    $id = $input['id'];
 $approv = $input['Approve_Sekretaris'];

    // Validasi status
    $valid_statuses = ['Belum Disetujui', 'Disetujui', 'Ditolak'];
    if (!in_array($approv, $valid_statuses)) {
        http_response_code(400);
        echo json_encode(['message' => 'Status tidak valid.']);
        exit;
    }

    // Lakukan update ke database
    $query = "UPDATE laporan SET Approve_Sekretaris = ? WHERE id = ?";
    $stmt = $koneksi->prepare($query);

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['message' => 'Gagal mempersiapkan query.', 'error' => $koneksi->error]);
        exit;
    }

    $stmt->bind_param("si", $approv, $id);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Status berhasil diperbarui.']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Gagal memperbarui status.', 'error' => $stmt->error]);
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $koneksi->close();
} else {
    // Method selain POST
    http_response_code(405);
    echo json_encode(['message' => 'Metode tidak diizinkan.']);
    exit;
}
// APPROVE Sekretaris===============================================================