<?php
/**
 * db.php — Database Connection Module
 * WeConnect | Live Anonymous Chat Application
 *
 * Provides a single reusable MySQLi connection.
 * Include this file in every endpoint that requires DB access.
 */

// ─── Configuration ───────────────────────────────────────────────────────────
define('DB_HOST',     'db'); // Matches the service name in docker-compose.yml
define('DB_NAME',     'WECONDB');
define('DB_USER',     'root');
define('DB_PASS',     getenv('WECON_DB_PASS') ?: ''); // Use env var in production
define('DB_CHARSET',  'utf8mb4');

// ─── Connection ───────────────────────────────────────────────────────────────
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    http_response_code(503);
    echo json_encode([
        'success' => false,
        'message' => 'Service temporarily unavailable. Please try again later.',
    ]);
    exit;
}

$conn->set_charset(DB_CHARSET);
