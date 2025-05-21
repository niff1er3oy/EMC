<?php
include('conn.php');
$device = isset($_GET['device']) ? intval($_GET['device']) : 1;

header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=Device{$device}_latest.csv");

$output = fopen('php://output', 'w');
fputcsv($output, ['pkWh_all', 'Van', 'Ia', 'Pa', 'Qa', 'Sa', 'Pfa', 'f', 'date', 'time']); // หัวตาราง

$sql = "SELECT pkWh_all, Van, Ia, Pa, Qa, Sa, Pfa, f, date, time FROM Schneider WHERE Dv_ID = $device ORDER BY id DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
exit;

?>