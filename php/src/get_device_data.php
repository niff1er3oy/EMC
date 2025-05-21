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
                HOUR(time) as hour,
                AVG(Van) as Van, AVG(Ia) as Ia, AVG(Pa) as Pa, AVG(Qa) as Qa,
                AVG(Sa) as Sa, AVG(Pfa) as Pfa, AVG(f) as f, AVG(pkWh_all) as pkWh_all
            FROM Schneider
            WHERE Dv_ID = $device_id AND date = CURDATE()
            GROUP BY HOUR(time)
            ORDER BY hour";
} elseif ($mode === 'month') {
    $sql = "SELECT 
                date,
                AVG(Van) as Van, AVG(Ia) as Ia, AVG(Pa) as Pa, AVG(Qa) as Qa,
                AVG(Sa) as Sa, AVG(Pfa) as Pfa, AVG(f) as f, AVG(pkWh_all) as pkWh_all
            FROM Schneider
            WHERE Dv_ID = $device_id AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())
            GROUP BY date
            ORDER BY date";
} elseif ($mode === 'year') {
    $sql = "SELECT 
                MONTH(date) as month,
                AVG(Van) as Van, AVG(Ia) as Ia, AVG(Pa) as Pa, AVG(Qa) as Qa,
                AVG(Sa) as Sa, AVG(Pfa) as Pfa, AVG(f) as f, AVG(pkWh_all) as pkWh_all
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
