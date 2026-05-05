<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_senja";

$conn = mysqli_connect($host, $user, $pass, $db);

$result = mysqli_query($conn, "SELECT * FROM menus");
$menus = [];
while ($row = mysqli_fetch_assoc($result)) {
    $menus[$row['category']][] = [
        'name'        => $row['name'],
        'description' => $row['description'],
        'price'       => $row['price'],
        'image_url'   => $row['image_url'] ?? ''
    ];
}

echo json_encode($menus);
?>
