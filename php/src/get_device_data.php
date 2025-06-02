<?php
include('conn.php');

$device_id = isset($_GET['device']) ? intval($_GET['device']) : 1;
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'latest';

$data = [];
$previousPkWh = null;

if ($mode === 'latest') {
    $sql = "SELECT pkWh_all, Van, Ia, Pa, Qa, Sa, Pfa, f, date, time 
            FROM Schneider 
            WHERE Dv_ID = $device_id 
            ORDER BY id DESC 
            LIMIT 14";

    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        if ($previousPkWh !== null) {
            $row['pkWh_diff'] = round($previousPkWh - $row['pkWh_all'], 3);
        } else {
            $row['pkWh_diff'] = 0;
        }
        $previousPkWh = $row['pkWh_all'];
        $data[] = $row;
    }

} elseif ($mode === 'week') {
    $sql = "SELECT 
                s.date,
                FLOOR(HOUR(s.time) / 12) * 12 AS hour_range,
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
                WHERE Dv_ID = $device_id
                ORDER BY date DESC
                LIMIT 7
            ) recent_dates ON s.date = recent_dates.date
            WHERE s.Dv_ID = $device_id
            GROUP BY s.date, hour_range
            ORDER BY s.date, hour_range";

    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        if ($previousPkWh !== null) {
            $row['pkWh_diff'] = round($row['pkWh_all'] - $previousPkWh, 3);
        } else {
            $row['pkWh_diff'] = 0;
        }
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
            WHERE Dv_ID = $device_id 
                AND date >= CURDATE() - INTERVAL 29 DAY
            GROUP BY date
            ORDER BY date";

    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        if ($previousPkWh !== null) {
            $row['pkWh_diff'] = round($row['pkWh_all'] - $previousPkWh, 3);
        } else {
            $row['pkWh_diff'] = 0;
        }
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
            WHERE Dv_ID = $device_id 
                AND date >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
            GROUP BY DATE_FORMAT(date, '%Y-%m')
            ORDER BY month";

    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        if ($previousPkWh !== null) {
            $row['pkWh_diff'] = round($row['pkWh_all'] - $previousPkWh, 3);
        } else {
            $row['pkWh_diff'] = 0;
        }
        $previousPkWh = $row['pkWh_all'];
        $data[] = $row;
    }
}

// เพิ่ม label สำหรับแสดงผลแต่ละโหมด
foreach ($data as &$row) {
    $label = '';

    if ($mode === 'week') {
        $date = date('d/m/Y', strtotime($row["date"]));
        $start = str_pad($row['hour_range'], 2, '0', STR_PAD_LEFT) . ':00';
        $end = str_pad($row['hour_range'] + 11, 2, '0', STR_PAD_LEFT) . ':59';
        $label = $date . " {$start}-{$end}";
    } elseif ($mode === 'month') {
        $label = date('d/m/Y', strtotime($row["date"]));
    } elseif ($mode === 'year') {
        $label = date('m/Y', strtotime($row['month']));
    } else {
        $date = date('d/m/Y', strtotime($row["date"]));
        $time = substr($row["time"], 0, 5);
        $label = $date . " " . $time;
    }

    $row["label"] = $label;
}

// เตรียมข้อมูลสำหรับแสดงผล
$output = array_map(function ($row) {
    return [
        "voltage" => floatval($row["Van"]),
        "current" => floatval($row["Ia"]),
        "a-power" => floatval($row["Pa"]),
        "r-power" => floatval($row["Qa"]),
        "app-power" => floatval($row["Sa"]),
        "power-f"  => floatval($row["Pfa"]),
        "f"        => floatval($row["f"]),
        // "t-a-e"    => floatval($row["pkWh_all"]),
        "t-a-e"     => floatval($row["pkWh_diff"]),
        "label"    => $row["label"]
    ];
}, $data);

echo json_encode($output);
$conn->close();
?>
