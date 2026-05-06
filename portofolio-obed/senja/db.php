<?php
/**
 * db.php — Centralized Database Connection
 * 
 * Include this file in any PHP script that needs database access.
 * Changing credentials here applies globally to the entire project.
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_senja');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    http_response_code(500);
    // Return JSON error if called from an API context
    if (!headers_sent()) {
        header('Content-Type: application/json');
    }
    die(json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]));
}

// Set charset to UTF-8 to prevent character encoding issues
mysqli_set_charset($conn, 'utf8mb4');
?>
