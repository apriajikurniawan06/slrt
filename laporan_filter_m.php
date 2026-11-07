<?php 
// Sertakan file koneksi
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set header untuk JSON
    header('Content-Type: application/json');

    // Ambil input JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Periksa apakah ID dan filter_status ada
    if (!isset($input['id']) || !isset($input['filter_status'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Data tidak lengkap.']);
        exit;
    }

    $id = $input['id'];
    $filter_status = $input['filter_status'];

    // Validasi status
    $valid_statuses = ['Off', 'On Filter'];
    if (!in_array($filter_status, $valid_statuses)) {
        http_response_code(400);
        echo json_encode(['message' => 'Status tidak valid.']);
        exit;
    }

    // Lakukan update ke database pada tabel laporan
    $query = "UPDATE laporan SET filter_status = ? WHERE id = ?";
    $stmt = $koneksi->prepare($query);

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['message' => 'Gagal mempersiapkan query.', 'error' => $koneksi->error]);
        exit;
    }

    $stmt->bind_param("si", $filter_status, $id);

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
?>