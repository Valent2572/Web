<?php
header('Content-Type: application/json');

$conn = mysqli_connect('localhost', 'root', '', 'db_senja');
if (!$conn) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$res = mysqli_query($conn, "SELECT table_id, is_available FROM cafe_tables");
$status = [];
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $status[$row['table_id']] = (int)$row['is_available'];
    }
}

echo json_encode($status);
mysqli_close($conn);
?>
