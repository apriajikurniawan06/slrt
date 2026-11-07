<?php
// Array nama bulan
$months = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];

// Ambil tahun dan bulan dari permintaan AJAX
$year = $_POST['year'];
$month = $_POST['month'];

// Ambil nama bulan
$monthName = $months[(int)$month];

// Kembalikan data dalam format JSON
echo json_encode([
    'monthName' => $monthName
]);
?>