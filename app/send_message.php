<?php
/**
 * send_message.php — Send a Chat Message
 * WeConnect | Live Anonymous Chat Application
 *
 * Requires an active session with 'user' and 'joined_pool_id' set.
 * Accepts POST with JSON body: { "message": string }
 * Returns JSON: { "success": bool, "message": string }
 */

declare(strict_types=1);

session_start();
header('Content-Type: application/json');

// ─── Authentication Guard ─────────────────────────────────────────────────────
if (!isset($_SESSION['user'], $_SESSION['joined_pool_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please log in.']);
    exit;
}

// ─── Request Validation ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$data    = json_decode(file_get_contents('php://input'), true);
$message = trim($data['message'] ?? '');

if ($message === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Message cannot be empty.']);
    exit;
}

if (mb_strlen($message) > 1000) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Message exceeds maximum length of 1000 characters.']);
    exit;
}

// ─── Database ─────────────────────────────────────────────────────────────────
require_once __DIR__ . '/db.php';

$stmt = $conn->prepare(
    'INSERT INTO messages (pool_id, username, message, sent_at) VALUES (?, ?, ?, NOW())'
);
$stmt->bind_param('iss', $_SESSION['joined_pool_id'], $_SESSION['user'], $message);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again.']);
}

$stmt->close();
$conn->close();
