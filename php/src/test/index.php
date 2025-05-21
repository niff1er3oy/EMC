<?php
header('Content-Type: application/json');
include('conn.php');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Connection failed: ' . $conn->connect_error
    ]);
    exit;
}

$sql = "SELECT s.*
FROM Schneider s
JOIN (
    SELECT Dv_ID, MAX(id) AS max_id
    FROM Schneider
    WHERE Dv_ID IN (1, 2, 3)
    GROUP BY Dv_ID
) AS latest
ON s.Dv_ID = latest.Dv_ID AND s.id = latest.max_id;";

$result = $conn->query($sql);

// ตรวจสอบผลลัพธ์
if (!$result) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Query failed: ' . $conn->error
    ]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
