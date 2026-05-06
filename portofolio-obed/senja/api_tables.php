<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$res    = mysqli_query($conn, "SELECT table_id, is_available FROM cafe_tables");
$status = [];
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $status[$row['table_id']] = (int)$row['is_available'];
    }
}

echo json_encode($status);
mysqli_close($conn);
?>
