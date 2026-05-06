<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$result = mysqli_query($conn, "SELECT category, name, description, price, image_url FROM menus");
$menus = [];
while ($row = mysqli_fetch_assoc($result)) {
    $menus[$row['category']][] = [
        'name'        => $row['name'],
        'description' => $row['description'],
        'price'       => $row['price'],
        'image_url'   => $row['image_url'] ?? ''
    ];
}

echo json_encode($menus, JSON_UNESCAPED_UNICODE);
mysqli_close($conn);
?>
