<?php
include('conn.php');
$sql = "WITH Ranked AS (
    SELECT *, ROW_NUMBER() OVER (PARTITION BY Dv_ID ORDER BY id DESC) AS rn
    FROM schneider
    WHERE Dv_ID IN (1, 2)
)
SELECT * FROM Ranked
WHERE rn = 1"; // 2 device ล่าสุด
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$conn->close();
?>
