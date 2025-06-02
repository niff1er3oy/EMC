<?php
include('conn.php');
header('Content-Type: application/json');
ini_set('memory_limit', '-1');

if ($_GET['action'] === 'download') {
    $mode = isset($_GET['mode']) ? $_GET['mode'] : 'latest';

    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=Temperature_{$mode}.csv");

    // เลือก SQL ตาม mode
    if ($mode === 'latest') {
        $sql = "SELECT temp, hum, Pond1_Temp, Pond2_Temp, Pond3_Temp, date, time 
                FROM Water_Temperature 
                ORDER BY id DESC ";
    } elseif ($mode === 'week') {
        $sql = "SELECT 
                    w.date,
                    FLOOR(HOUR(w.time) / 12) * 12 AS hour_range,
                    AVG(NULLIF(w.temp, 0)) AS temp,
                    AVG(NULLIF(w.hum, 0)) AS hum,
                    AVG(NULLIF(w.Pond1_Temp, 0)) AS Pond1_Temp,
                    AVG(NULLIF(w.Pond2_Temp, 0)) AS Pond2_Temp,
                    AVG(NULLIF(w.Pond3_Temp, 0)) AS Pond3_Temp
                FROM Water_Temperature w
                INNER JOIN (
                    SELECT DISTINCT date
                    FROM Water_Temperature
                    ORDER BY date DESC
                ) recent_dates ON w.date = recent_dates.date
                GROUP BY w.date, hour_range
                ORDER BY w.date, hour_range";

    } elseif ($mode === 'month') {
        $sql = "SELECT 
                    date,
                    AVG(NULLIF(temp, 0)) AS temp,
                    AVG(NULLIF(hum, 0)) AS hum,
                    AVG(NULLIF(Pond1_Temp, 0)) AS Pond1_Temp,
                    AVG(NULLIF(Pond2_Temp, 0)) AS Pond2_Temp,
                    AVG(NULLIF(Pond3_Temp, 0)) AS Pond3_Temp
                FROM Water_Temperature
                GROUP BY date
                ORDER BY date";

    } elseif ($mode === 'year') {
        $sql = "SELECT 
                    DATE_FORMAT(date, '%Y-%m') AS month,
                    AVG(NULLIF(temp, 0)) AS temp,
                    AVG(NULLIF(hum, 0)) AS hum,
                    AVG(NULLIF(Pond1_Temp, 0)) AS Pond1_Temp,
                    AVG(NULLIF(Pond2_Temp, 0)) AS Pond2_Temp,
                    AVG(NULLIF(Pond3_Temp, 0)) AS Pond3_Temp
                FROM Water_Temperature
                GROUP BY DATE_FORMAT(date, '%Y-%m')
                ORDER BY month";
    } else {
        // กรณี mode ไม่ตรงกับข้างต้น ให้คืนค่าเปล่า
        echo "Invalid mode";
        exit;
    }

    $result = $conn->query($sql);
    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    $output = fopen('php://output', 'w');

    if (!empty($data)) {
        $headers = array_keys($data[0]);
        fputcsv($output, $headers);

        foreach ($data as $row) {
            $orderedRow = [];
            foreach ($headers as $h) {
                $orderedRow[] = isset($row[$h]) ? $row[$h] : '';
            }
            fputcsv($output, $orderedRow);
        }
    } else {
        fputcsv($output, ['No data found']);
    }

    fclose($output);
    exit;
} elseif ($_GET['action'] === 'get_device_data'){
    $mode = $_GET['mode'] ?? 'latest';

    $data = [];

    if ($mode === 'latest') {
        $sql = "SELECT temp, hum, Pond1_Temp, Pond2_Temp, Pond3_Temp, date, time 
                FROM Water_Temperature 
                ORDER BY id DESC 
                LIMIT 14";

    } elseif ($mode === 'week') {
        $sql = "SELECT 
                    w.date,
                    FLOOR(HOUR(w.time) / 12) * 12 AS hour_range,
                    AVG(NULLIF(w.temp, 0)) AS temp,
                    AVG(NULLIF(w.hum, 0)) AS hum,
                    AVG(NULLIF(w.Pond1_Temp, 0)) AS Pond1_Temp,
                    AVG(NULLIF(w.Pond2_Temp, 0)) AS Pond2_Temp,
                    AVG(NULLIF(w.Pond3_Temp, 0)) AS Pond3_Temp
                FROM Water_Temperature w
                INNER JOIN (
                    SELECT DISTINCT date
                    FROM Water_Temperature
                    ORDER BY date DESC
                    LIMIT 7
                ) recent_dates ON w.date = recent_dates.date
                GROUP BY w.date, hour_range
                ORDER BY w.date, hour_range";

    } elseif ($mode === 'month') {
        $sql = "SELECT 
                    date,
                    AVG(NULLIF(temp, 0)) AS temp,
                    AVG(NULLIF(hum, 0)) AS hum,
                    AVG(NULLIF(Pond1_Temp, 0)) AS Pond1_Temp,
                    AVG(NULLIF(Pond2_Temp, 0)) AS Pond2_Temp,
                    AVG(NULLIF(Pond3_Temp, 0)) AS Pond3_Temp
                FROM Water_Temperature
                WHERE date >= CURDATE() - INTERVAL 29 DAY
                GROUP BY date
                ORDER BY date";

    } elseif ($mode === 'year') {
        $sql = "SELECT 
                    DATE_FORMAT(date, '%Y-%m') AS month,
                    AVG(NULLIF(temp, 0)) AS temp,
                    AVG(NULLIF(hum, 0)) AS hum,
                    AVG(NULLIF(Pond1_Temp, 0)) AS Pond1_Temp,
                    AVG(NULLIF(Pond2_Temp, 0)) AS Pond2_Temp,
                    AVG(NULLIF(Pond3_Temp, 0)) AS Pond3_Temp
                FROM Water_Temperature
                WHERE date >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
                GROUP BY DATE_FORMAT(date, '%Y-%m')
                ORDER BY month";
    }

    // ดึงข้อมูลจากฐานข้อมูล
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    // เพิ่ม label แสดงเวลา
    foreach ($data as &$row) {
        if ($mode === 'week') {
            $date = date('d/m/Y', strtotime($row["date"]));
            $start = str_pad($row['hour_range'], 2, '0', STR_PAD_LEFT) . ':00';
            $end = str_pad($row['hour_range'] + 11, 2, '0', STR_PAD_LEFT) . ':59';
            $row["label"] = "$date $start-$end";
        } elseif ($mode === 'month') {
            $row["label"] = date('d/m/Y', strtotime($row["date"]));
        } elseif ($mode === 'year') {
            $row["label"] = date('m/Y', strtotime($row['month']));
        } else {
            $row["label"] = date('d/m/Y H:i', strtotime($row["date"] . ' ' . $row["time"]));
        }
    }

    // เตรียมข้อมูลส่งออก
    $output = array_map(function ($row) {
        return [
            "temp"      => floatval($row["temp"]),
            "hum"    => floatval($row["hum"]),
            "pond1"     => floatval($row["Pond1_Temp"]),
            "pond2"     => floatval($row["Pond2_Temp"]),
            "pond3"     => floatval($row["Pond3_Temp"]),
            "label"     => $row["label"]
        ];
    }, $data);

    echo json_encode($output);
    $conn->close();
} elseif ($_GET['action'] === 'get_latest_data') {
    $sql = "SELECT `temp`, `hum`, `Pond1_Temp`, `Pond2_Temp`, `Pond3_Temp`, `date`, `time` 
            FROM `Water_Temperature` 
            ORDER BY `id` DESC 
            LIMIT 1";

    $result = $conn->query($sql);

    $data = [];
    if ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);

    $conn->close();
}
?>