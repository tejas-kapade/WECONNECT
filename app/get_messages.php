<?php
/**
 * get_messages.php — Retrieve Chat Messages for a Pool
 * WeConnect | Live Anonymous Chat Application
 *
 * Requires an active session with 'user' and 'joined_pool_id' set.
 * Supports optional query param: ?since=<message_id> for incremental polling.
 * Returns JSON array of message objects.
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

// ─── Database ─────────────────────────────────────────────────────────────────
require_once __DIR__ . '/db.php';

$poolId    = (int) $_SESSION['joined_pool_id'];
$sinceId   = isset($_GET['since']) ? (int) $_GET['since'] : 0;

// Fetch only new messages if 'since' param provided (reduces payload size)
if ($sinceId > 0) {
    $stmt = $conn->prepare(
        "SELECT id, username, message,
                DATE_FORMAT(sent_at, '%d %b %Y, %h:%i %p') AS sent_at
         FROM   messages
         WHERE  pool_id = ? AND id > ?
         ORDER  BY sent_at ASC"
    );
    $stmt->bind_param('ii', $poolId, $sinceId);
} else {
    $stmt = $conn->prepare(
        "SELECT id, username, message,
                DATE_FORMAT(sent_at, '%d %b %Y, %h:%i %p') AS sent_at
         FROM   messages
         WHERE  pool_id = ?
         ORDER  BY sent_at ASC"
    );
    $stmt->bind_param('i', $poolId);
}

$stmt->execute();
$result   = $stmt->get_result();
$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($messages);
