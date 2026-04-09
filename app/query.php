<?php
/**
 * query.php — User Registration Endpoint
 * WeConnect | Live Anonymous Chat Application
 *
 * Accepts POST with JSON body: { "user": string, "password": string }
 * Returns JSON: { "success": bool, "message": string }
 *
 * NOTE: Passwords are currently stored in plain text.
 *       Replace with password_hash() / password_verify() before deploying.
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

if (strlen($username) < 3 || strlen($username) > 32) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Username must be between 3 and 32 characters.']);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
    exit;
}

// ─── Duplicate Username Check ─────────────────────────────────────────────────
$check = $conn->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
$check->bind_param('s', $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    http_response_code(409);
    echo json_encode(['success' => false, 'message' => 'Username is already taken. Please choose another.']);
    $check->close();
    exit;
}
$check->close();

// ─── Insert New User ──────────────────────────────────────────────────────────
// TODO: Replace plain-text storage with: $password = password_hash($password, PASSWORD_BCRYPT);
$stmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
$stmt->bind_param('ss', $username, $password);

if ($stmt->execute()) {
    $_SESSION['user']     = $username;
    $_SESSION['is_admin'] = false;
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
}

$stmt->close();
$conn->close();
