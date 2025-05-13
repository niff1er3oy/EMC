<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "test"; // เปลี่ยนชื่อฐานข้อมูล
$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$range = $_GET['range'] ?? '7d';         // 24h, 7d, 30d
$groupby = $_GET['groupby'] ?? 'hour';   // hour, day, month
$data = [];

$conditions = "Dv_ID = 1";
$group = "";
$label = "";
$order = "";

// ✅ กรองช่วงเวลา
switch ($range) {
  case '24h':
    $conditions .= " AND CONCAT(date, ' ', time) >= NOW() - INTERVAL 24 HOUR";
    break;
  case '7d':
    $conditions .= " AND date >= CURDATE() - INTERVAL 6 DAY";
    break;
  case '30d':
    $conditions .= " AND date >= CURDATE() - INTERVAL 30 DAY";
    break;
  default:
    echo json_encode([]); exit;
}

// ✅ จัดกลุ่มเฉลี่ย
switch ($groupby) {
  case 'hour':
    $group = "DATE(date), HOUR(time)";
    $label = "DATE_FORMAT(CONCAT(date, ' ', time), '%d/%m %H:00')";
    $order = "date, HOUR(time)";
    break;
  case 'day':
    $group = "DATE(date)";
    $label = "DATE_FORMAT(date, '%d/%m')";
    $order = "date";
    break;
  case 'month':
    $group = "YEAR(date), MONTH(date)";
    $label = "DATE_FORMAT(date, '%b %Y')";
    $order = "YEAR(date), MONTH(date)";
    break;
  default:
    echo json_encode([]); exit;
}

// ✅ ดึงข้อมูลเฉลี่ย
$sql = "SELECT $label AS label, AVG(f) AS value
        FROM schneider
        WHERE $conditions
        GROUP BY $group
        ORDER BY $order";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
  $data[] = [
    'label' => $row['label'],
    'value' => floatval($row['value'])
  ];
}

echo json_encode($data);
$conn->close();
?>
