<?php
require 'db_config.php';

$range = $_GET['range'] ?? '24h';
$avg = $_GET['avg'] ?? 'none';

$start_time = date('Y-m-d H:i:s');

switch ($range) {
    case '24h':
        $start_time = date('Y-m-d H:i:s', strtotime('-24 hours'));
        break;
    case '7d':
        $start_time = date('Y-m-d H:i:s', strtotime('-7 days'));
        break;
    case '30d':
        $start_time = date('Y-m-d H:i:s', strtotime('-30 days'));
        break;
    case '3m':
        $start_time = date('Y-m-d H:i:s', strtotime('-3 months'));
        break;
}

$sql = "";
$params = [":start_time" => $start_time];

if ($avg == "none") {
    $sql = "SELECT Ia, CONCAT(date, ' ', time) AS datetime FROM schneider WHERE Dv_ID = 2 AND date >= :start_time ORDER BY date, time";
} elseif ($avg == "1h") {
    $sql = "SELECT DATE(date) AS d, HOUR(time) AS h, AVG(Ia) AS avg_Ia 
            FROM schneider 
            WHERE Dv_ID = 2 AND date >= :start_time 
            GROUP BY d, h ORDER BY d, h";
} elseif ($avg == "1d" && in_array($range, ['7d','30d','3m'])) {
    $sql = "SELECT DATE(date) AS d, AVG(Ia) AS avg_Ia 
            FROM schneider 
            WHERE Dv_ID = 2 AND date >= :start_time 
            GROUP BY d ORDER BY d";
} elseif ($avg == "1m" && $range == "3m") {
    $sql = "SELECT YEAR(date) AS y, MONTH(date) AS m, AVG(Ia) AS avg_Ia 
            FROM schneider 
            WHERE Dv_ID = 2 AND date >= :start_time 
            GROUP BY y, m ORDER BY y, m";
} else {
    echo json_encode(["error" => "Invalid selection"]);
    exit;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($avg == "none") {
        $data[] = ["datetime" => $row["datetime"], "Ia" => $row["Ia"]];
    } elseif ($avg == "1h") {
        $data[] = ["datetime" => $row["d"] . ' ' . $row["h"] . ":00", "avg_Ia" => $row["avg_Ia"]];
    } elseif ($avg == "1d") {
        $data[] = ["datetime" => $row["d"], "avg_Ia" => $row["avg_Ia"]];
    } elseif ($avg == "1m") {
        $data[] = ["datetime" => $row["y"] . '-' . str_pad($row["m"], 2, "0", STR_PAD_LEFT), "avg_Ia" => $row["avg_Ia"]];
    }
}

echo json_encode($data);
?>
