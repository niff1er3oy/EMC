<?php
include('conn.php');
$sql = "SELECT s.*
FROM Schneider s
JOIN (
    SELECT Dv_ID, MAX(id) AS max_id
    FROM Schneider
    WHERE Dv_ID IN (1, 2, 3)
    GROUP BY Dv_ID
) AS latest
ON s.Dv_ID = latest.Dv_ID AND s.id = latest.max_id;
"; // 2 device ล่าสุด
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$conn->close();
?>
