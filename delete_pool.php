<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user = $_SESSION['user'];

$data = json_decode(file_get_contents("php://input"), true);
$pool_id = $data['pool_id'];

$host = 'localhost';
$dbname = 'WECONDB';
$username = 'root';
$password = '989878';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit;
}

// Only delete if the pool belongs to the logged-in user
$stmt = $conn->prepare("DELETE FROM chat_pools WHERE id = ? AND created_by = ?");
$stmt->bind_param("is", $pool_id, $user);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'You can only delete your own pools']);
}

$conn->close();
?>
