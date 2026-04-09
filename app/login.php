<?php
/**
 * login.php — User Authentication Endpoint
 * WeConnect | Live Anonymous Chat Application
 *
 * Accepts POST with JSON body: { "user": string, "password": string }
 * Returns JSON: { "success": bool, "admin": bool, "message": string }
 */

declare(strict_types=1);

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

// ─── Request Validation ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$data     = json_decode(file_get_contents('php://input'), true);
$username = trim($data['user']     ?? '');
$password = trim($data['password'] ?? '');

if ($username === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
    exit;
}

// ─── Admin Shortcut (replace with DB-stored hashed password in production) ───
if ($username === 'admin' && $password === getenv('WECON_ADMIN_PASS')) {
    $_SESSION['user']    = 'admin';
    $_SESSION['is_admin'] = true;
    echo json_encode(['success' => true, 'admin' => true]);
    exit;
}

// ─── Regular User Authentication ─────────────────────────────────────────────
$stmt = $conn->prepare(
    'SELECT password FROM users WHERE username = ? LIMIT 1'
);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$row    = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}

// NOTE: Use password_verify() once passwords are stored as bcrypt hashes.
if ($password !== $row['password']) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
    exit;
}

$_SESSION['user']     = $username;
$_SESSION['is_admin'] = false;

echo json_encode(['success' => true, 'admin' => false]);

$conn->close();
