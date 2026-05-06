<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$result = mysqli_query($conn, "SELECT sold_count FROM favorite_coffee");
$chart_data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $chart_data[] = (int)$row['sold_count'];
}

echo json_encode($chart_data);
mysqli_close($conn);
?>
