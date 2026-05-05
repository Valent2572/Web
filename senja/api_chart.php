<?php
// 1. Tell the browser this is pure JSON data
header('Content-Type: application/json');

// 2. Connect to the database
$host = "localhost"; // Change to your live credentials when uploading
$user = "root";
$pass = "";
$db   = "db_senja";

$conn = mysqli_connect($host, $user, $pass, $db);

// 3. Fetch just the latest sold counts
$query = "SELECT sold_count FROM favorite_coffee";
$result = mysqli_query($conn, $query);

$chart_data = [];
while($row = mysqli_fetch_assoc($result)) {
    $chart_data[] = $row['sold_count'];
}

// 4. Print it out as a JSON array
echo json_encode($chart_data);
?>
