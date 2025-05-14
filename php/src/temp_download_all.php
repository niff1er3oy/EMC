<?php
include('conn.php');

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=water_temperature_data.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['date', 'time', 'temp', 'hum', 'Pond1_Temp', 'Pond2_Temp', 'Pond3_Temp']);

$sql = "SELECT * FROM Water_Temperature ORDER BY date DESC, time DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['date'], $row['time'], $row['temp'], $row['hum'], $row['Pond1_Temp'], $row['Pond2_Temp'], $row['Pond3_Temp']]);
}

fclose($output);
$conn->close();
exit();
?>
