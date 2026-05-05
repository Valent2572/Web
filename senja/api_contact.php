<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_senja";

$conn = mysqli_connect($host, $user, $pass, $db);

$result = mysqli_query($conn, "SELECT * FROM contacts");
$contacts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $contacts[$row['contact_type']] = [
        'role'          => $row['role'],
        'person_name'   => $row['person_name'],
        'link_url'      => $row['link_url'],
        'display_value' => $row['display_value']
    ];
}

echo json_encode($contacts);
?>
