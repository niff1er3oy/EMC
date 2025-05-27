<?php
include('conn.php');
$device_id = isset($_GET['device']) ? intval($_GET['device']) : 1;
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'latest';

if ($mode === 'latest') {
    $sql = "SELECT pkWh_all, Van, Ia, Pa, Qa, Sa, Pfa, f, date, time 
            FROM Schneider 
            WHERE Dv_ID = $device_id 
            ORDER BY id DESC 
            LIMIT 7";
} elseif ($mode === 'day') {
    $sql = "SELECT 
                HOUR(time) AS hour,
                AVG(NULLIF(Van, 0)) AS Van,
                AVG(NULLIF(Ia, 0)) AS Ia,
                AVG(NULLIF(Pa, 0)) AS Pa,
                AVG(NULLIF(Qa, 0)) AS Qa,
                AVG(NULLIF(Sa, 0)) AS Sa,
                AVG(NULLIF(Pfa, 0)) AS Pfa,
                AVG(NULLIF(f, 0)) AS f,
                AVG(NULLIF(pkWh_all, 0)) AS pkWh_all
            FROM Schneider
            WHERE Dv_ID = $device_id AND date = CURDATE()
            GROUP BY HOUR(time)
            ORDER BY hour";
} elseif ($mode === 'month') {
    $sql = "SELECT 
                date,
                AVG(NULLIF(Van, 0)) AS Van,
                AVG(NULLIF(Ia, 0)) AS Ia,
                AVG(NULLIF(Pa, 0)) AS Pa,
                AVG(NULLIF(Qa, 0)) AS Qa,
                AVG(NULLIF(Sa, 0)) AS Sa,
                AVG(NULLIF(Pfa, 0)) AS Pfa,
                AVG(NULLIF(f, 0)) AS f,
                AVG(NULLIF(pkWh_all, 0)) AS pkWh_all
            FROM Schneider
            WHERE Dv_ID = $device_id AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())
            GROUP BY date
            ORDER BY date";
} elseif ($mode === 'year') {
    $sql = "SELECT 
                MONTH(date) as month,
                AVG(NULLIF(Van, 0)) AS Van,
                AVG(NULLIF(Ia, 0)) AS Ia,
                AVG(NULLIF(Pa, 0)) AS Pa,
                AVG(NULLIF(Qa, 0)) AS Qa,
                AVG(NULLIF(Sa, 0)) AS Sa,
                AVG(NULLIF(Pfa, 0)) AS Pfa,
                AVG(NULLIF(f, 0)) AS f,
                AVG(NULLIF(pkWh_all, 0)) AS pkWh_all
            FROM Schneider
            WHERE Dv_ID = $device_id AND YEAR(date) = YEAR(CURDATE())
            GROUP BY MONTH(date)
            ORDER BY month";
}

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $label = '';
    if ($mode === 'day') $label = $row['hour'] . ":00";
    elseif ($mode === 'month') $label = $row['date'];
    elseif ($mode === 'year') $label = str_pad($row['month'], 2, '0', STR_PAD_LEFT) . '/' . date('Y');
    else $label = $row["date"] . " " . $row["time"];

    $data[] = [
        "voltage" => floatval($row["Van"]),
        "current" => floatval($row["Ia"]),
        "a-power" => floatval($row["Pa"]),
        "r-power" => floatval($row["Qa"]),
        "app-power" => floatval($row["Sa"]),
        "power-f"  => floatval($row["Pfa"]),
        "f"        => floatval($row["f"]),
        "t-a-e"    => floatval($row["pkWh_all"]),
        "label"    => $label
    ];
}

echo json_encode($data);
$conn->close();

?>
