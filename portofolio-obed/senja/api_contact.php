<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$result = mysqli_query($conn, "SELECT contact_type, role, person_name, link_url, display_value FROM contacts");
$contacts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $contacts[$row['contact_type']] = [
        'role'          => $row['role'],
        'person_name'   => $row['person_name'],
        'link_url'      => $row['link_url'],
        'display_value' => $row['display_value']
    ];
}

echo json_encode($contacts, JSON_UNESCAPED_UNICODE);
mysqli_close($conn);
?>
