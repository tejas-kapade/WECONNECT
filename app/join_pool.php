<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.html");
    exit;
}

require_once __DIR__ . '/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$pool_id = $data['pool_id'];
$password = $data['password'];

$stmt = $conn->prepare("SELECT * FROM chat_pools WHERE id = ? AND pool_password = ?");
$stmt->bind_param("is", $pool_id, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Set session for chat pool
    $_SESSION['joined_pool_id'] = $pool_id;
    echo json_encode(['success' => true, 'redirect' => 'chat_pool.php']);
} else {
    echo json_encode(['success' => false, 'message' => 'Incorrect password']);
}
?>
