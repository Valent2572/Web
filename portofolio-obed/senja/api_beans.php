<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$result = mysqli_query($conn, "SELECT id, name, roast_level, origin, notes, image_url FROM coffee_beans ORDER BY name ASC");
$beans = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $beans[] = [
            'id'          => $row['id'],
            'name'        => $row['name'],
            'roast_level' => $row['roast_level'],
            'origin'      => $row['origin'],
            'notes'       => $row['notes'],
            'image_url'   => $row['image_url']
        ];
    }
}

echo json_encode($beans, JSON_UNESCAPED_UNICODE);
mysqli_close($conn);
?>
