<?php

function getDashboardStats($koneksi)
{
    date_default_timezone_set('Asia/Jakarta');

    $current_year = (int) date('Y');
    $available_years = [$current_year];
    $year_stmt = mysqli_prepare($koneksi, "SELECT DISTINCT YEAR(tanggal) AS tahun
        FROM registrasi
        WHERE tanggal < ?
        ORDER BY tahun DESC");
    $next_year = ($current_year + 1) . '-01-01';
    mysqli_stmt_bind_param($year_stmt, 's', $next_year);
    mysqli_stmt_execute($year_stmt);
    $year_result = mysqli_stmt_get_result($year_stmt);
    while ($row = mysqli_fetch_assoc($year_result)) {
        $available_years[] = (int) $row['tahun'];
    }
    mysqli_stmt_close($year_stmt);
    $available_years = array_values(array_unique($available_years));

    $requested_year = filter_var($_GET['chart_year'] ?? $current_year, FILTER_VALIDATE_INT);
    $chart_year = in_array($requested_year, $available_years, true) ? $requested_year : $current_year;
    $chart_month_count = $chart_year === $current_year ? (int) date('n') : 12;

    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $week_start = date('Y-m-d', strtotime('-6 days'));
    $month_start = date('Y-m-01');
    $next_month_start = date('Y-m-d', strtotime('first day of next month'));
    $year_start = date('Y-01-01');
    $next_year_start = date('Y-01-01', strtotime('+1 year'));

    $summary_stmt = mysqli_prepare($koneksi, "SELECT
        COUNT(CASE WHEN tanggal >= ? AND tanggal < ? THEN 1 END) AS total_hari_ini,
        COUNT(CASE WHEN tanggal >= ? AND tanggal < ? THEN 1 END) AS total_kemarin,
        COUNT(CASE WHEN tanggal >= ? AND tanggal < ? THEN 1 END) AS total_seminggu,
        COUNT(CASE WHEN tanggal >= ? AND tanggal < ? THEN 1 END) AS total_bulan_ini,
        COUNT(CASE WHEN tanggal >= ? AND tanggal < ? THEN 1 END) AS total_keseluruhan
        FROM registrasi");
    mysqli_stmt_bind_param(
        $summary_stmt,
        'ssssssssss',
        $today,
        $tomorrow,
        $yesterday,
        $today,
        $week_start,
        $tomorrow,
        $month_start,
        $next_month_start,
        $year_start,
        $next_year_start
    );
    mysqli_stmt_execute($summary_stmt);
    $summary = mysqli_fetch_assoc(mysqli_stmt_get_result($summary_stmt));
    mysqli_stmt_close($summary_stmt);

    $service_names = ['CEK BANSOS', 'DTSEN', 'DESIL', 'BPNT', 'PKH', 'BPJS'];
    $service_totals = array_fill_keys($service_names, 0);
    $all_month_names = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];
    $month_names = array_slice($all_month_names, 0, $chart_month_count, true);
    $monthly_totals = [];
    foreach ($service_names as $service_name) {
        $monthly_totals[$service_name] = array_fill(1, $chart_month_count, 0);
    }

    $chart_year_start = $chart_year . '-01-01';
    $chart_year_end = $chart_year === $current_year
        ? date('Y-m-d', strtotime('first day of next month'))
        : (($chart_year + 1) . '-01-01');
    $chart_stmt = mysqli_prepare($koneksi, "SELECT MONTH(tanggal) AS nomor_bulan,
        SUM(UPPER(TRIM(pelayanan)) = 'CEK BANSOS') AS cek_bansos,
        SUM(UPPER(TRIM(pelayanan)) = 'DTSEN') AS dtsen,
        SUM(UPPER(TRIM(pelayanan)) = 'DESIL') AS desil,
        SUM(UPPER(TRIM(pelayanan)) = 'BPNT') AS bpnt,
        SUM(UPPER(TRIM(pelayanan)) = 'PKH') AS pkh,
        SUM(UPPER(TRIM(pelayanan)) = 'BPJS') AS bpjs
        FROM registrasi
        WHERE tanggal >= ? AND tanggal < ?
        GROUP BY MONTH(tanggal)");
    mysqli_stmt_bind_param($chart_stmt, 'ss', $chart_year_start, $chart_year_end);
    mysqli_stmt_execute($chart_stmt);
    $chart_result = mysqli_stmt_get_result($chart_stmt);
    while ($row = mysqli_fetch_assoc($chart_result)) {
        $month_number = (int) $row['nomor_bulan'];
        foreach ($service_names as $service_name) {
            $service_key = strtolower(str_replace(' ', '_', $service_name));
            $total = (int) $row[$service_key];
            $monthly_totals[$service_name][$month_number] = $total;
            $service_totals[$service_name] += $total;
        }
    }
    mysqli_stmt_close($chart_stmt);

    $chart_data = [
        'data_bulan' => array_values($month_names),
        'data_cek_bansos' => array_values($monthly_totals['CEK BANSOS']),
        'data_dtsen' => array_values($monthly_totals['DTSEN']),
        'data_desil' => array_values($monthly_totals['DESIL']),
        'data_bpnt' => array_values($monthly_totals['BPNT']),
        'data_pkh' => array_values($monthly_totals['PKH']),
        'data_bpjs' => array_values($monthly_totals['BPJS']),
    ];

    return array_merge(
        array_map('intval', $summary),
        [
            'service_totals' => $service_totals,
            'chart_data' => $chart_data,
            'chart_year' => $chart_year,
            'available_years' => $available_years,
        ]
    );
}