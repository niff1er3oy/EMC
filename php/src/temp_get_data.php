<?php
include('conn.php');

$mode = $_GET['mode'] ?? 'latest';

switch ($mode) {
    case 'day':
        $sql = "
            SELECT 
                HOUR(time) as label,
                AVG(temp) as temp,
                AVG(hum) as hum,
                AVG(Pond1_Temp) as Pond1_Temp,
                AVG(Pond2_Temp) as Pond2_Temp,
                AVG(Pond3_Temp) as Pond3_Temp,
                MAX(date) as date
            FROM Water_Temperature
            WHERE date = CURDATE()
            GROUP BY HOUR(time)
            ORDER BY HOUR(time)
        ";
        break;

    case 'month':
        $sql = "
            SELECT 
                date as label,
                AVG(temp) as temp,
                AVG(hum) as hum,
                AVG(Pond1_Temp) as Pond1_Temp,
                AVG(Pond2_Temp) as Pond2_Temp,
                AVG(Pond3_Temp) as Pond3_Temp
            FROM Water_Temperature
            WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())
            GROUP BY date
            ORDER BY date
        ";
        break;

    case 'year':
        $sql = "
            SELECT 
                MONTH(date) as label,
                AVG(temp) as temp,
                AVG(hum) as hum,
                AVG(Pond1_Temp) as Pond1_Temp,
                AVG(Pond2_Temp) as Pond2_Temp,
                AVG(Pond3_Temp) as Pond3_Temp
            FROM Water_Temperature
            WHERE YEAR(date) = YEAR(CURDATE())
            GROUP BY MONTH(date)
            ORDER BY MONTH(date)
        ";
        break;

    case 'latest':
    default:
        $sql = "
            SELECT temp, hum, Pond1_Temp, Pond2_Temp, Pond3_Temp, date, time,
                   CONCAT(date, ' ', time) as label
            FROM Water_Temperature
            ORDER BY date DESC, time DESC
            LIMIT 10
        ";
        break;
}

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($data);
?>
