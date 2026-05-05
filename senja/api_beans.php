<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_senja";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM coffee_beans ORDER BY name ASC");
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

mysqli_close($conn);
echo json_encode($beans);
?>
