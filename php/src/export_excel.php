<?php
include('conn.php');

// รับค่า device และ mode จาก GET
$device = isset($_GET['device']) ? intval($_GET['device']) : 1;
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'latest';

header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=Device{$device}_{$mode}.csv");

$data = [];
$previousPkWh = null;

if ($mode === 'latest') {
    $sql = "SELECT pkWh_all, Van, Ia, Pa, Qa, Sa, Pfa, f, date, time 
            FROM Schneider 
            WHERE Dv_ID = $device 
            ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $row['pkWh_diff'] = ($previousPkWh !== null) ? round($previousPkWh - $row['pkWh_all'], 3) : 0;
        $previousPkWh = $row['pkWh_all'];
        $data[] = $row;
    }

} elseif ($mode === 'week') {
    $sql = "SELECT 
                s.date,
                CASE 
                    WHEN HOUR(s.time) < 12 THEN '00:00 - 11:59'
                    ELSE '12:00 - 23:59'
                END AS hour_range,
                MAX(NULLIF(s.pkWh_all, 0)) AS pkWh_all,
                AVG(NULLIF(s.Van, 0)) AS Van,
                AVG(NULLIF(s.Ia, 0)) AS Ia,
                AVG(NULLIF(s.Pa, 0)) AS Pa,
                AVG(NULLIF(s.Qa, 0)) AS Qa,
                AVG(NULLIF(s.Sa, 0)) AS Sa,
                AVG(NULLIF(s.Pfa, 0)) AS Pfa,
                AVG(NULLIF(s.f, 0)) AS f
            FROM Schneider s
            INNER JOIN (
                SELECT DISTINCT date
                FROM Schneider
                WHERE Dv_ID = $device
                ORDER BY date DESC
            ) recent_dates ON s.date = recent_dates.date
            WHERE s.Dv_ID = $device
            GROUP BY s.date, hour_range
            ORDER BY s.date, hour_range";

    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $row['pkWh_diff'] = ($previousPkWh !== null) ? round($row['pkWh_all'] - $previousPkWh, 3) : 0;
        $previousPkWh = $row['pkWh_all'];
        $data[] = $row;
    }

} elseif ($mode === 'month') {
    $sql = "SELECT 
                date,
                MAX(NULLIF(pkWh_all, 0)) AS pkWh_all,
                AVG(NULLIF(Van, 0)) AS Van,
                AVG(NULLIF(Ia, 0)) AS Ia,
                AVG(NULLIF(Pa, 0)) AS Pa,
                AVG(NULLIF(Qa, 0)) AS Qa,
                AVG(NULLIF(Sa, 0)) AS Sa,
                AVG(NULLIF(Pfa, 0)) AS Pfa,
                AVG(NULLIF(f, 0)) AS f
            FROM Schneider
            WHERE Dv_ID = $device 
            GROUP BY date
            ORDER BY date";

    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $row['pkWh_diff'] = ($previousPkWh !== null) ? round($row['pkWh_all'] - $previousPkWh, 3) : 0;
        $previousPkWh = $row['pkWh_all'];
        $data[] = $row;
    }

} elseif ($mode === 'year') {
    $sql = "SELECT 
                DATE_FORMAT(date, '%Y-%m') AS month,
                MAX(NULLIF(pkWh_all, 0)) AS pkWh_all,
                AVG(NULLIF(Van, 0)) AS Van,
                AVG(NULLIF(Ia, 0)) AS Ia,
                AVG(NULLIF(Pa, 0)) AS Pa,
                AVG(NULLIF(Qa, 0)) AS Qa,
                AVG(NULLIF(Sa, 0)) AS Sa,
                AVG(NULLIF(Pfa, 0)) AS Pfa,
                AVG(NULLIF(f, 0)) AS f
            FROM Schneider
            WHERE Dv_ID = $device
            GROUP BY DATE_FORMAT(date, '%Y-%m')
            ORDER BY month";

    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $row['pkWh_diff'] = ($previousPkWh !== null) ? round($row['pkWh_all'] - $previousPkWh, 3) : 0;
        $previousPkWh = $row['pkWh_all'];
        $data[] = $row;
    }
}

// เขียน CSV
$output = fopen('php://output', 'w');

if (!empty($data)) {
    // จัดเรียงหัวตารางให้ pkWh_all อยู่ก่อน pkWh_diff เสมอ
    $headers = array_keys($data[0]);

    // ถ้ามีทั้ง pkWh_all และ pkWh_diff ปรับลำดับ
    if (in_array('pkWh_all', $headers) && in_array('pkWh_diff', $headers)) {
        $headers = array_diff($headers, ['pkWh_diff']);
        array_splice($headers, array_search('pkWh_all', $headers) + 1, 0, 'pkWh_diff');
    }

    fputcsv($output, $headers);
    foreach ($data as $row) {
        // จัดเรียงค่าให้ตรงกับหัวตาราง
        $orderedRow = [];
        foreach ($headers as $h) {
            $orderedRow[] = $row[$h] ?? '';
        }
        fputcsv($output, $orderedRow);
    }
} else {
    fputcsv($output, ['No data found']);
}

fclose($output);
exit;
?>
