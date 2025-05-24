<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['joined_pool_id'])) {
    http_response_code(401);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$message = trim($data['message']);
if (!$message) exit;

$host = 'localhost';
$dbname = 'WECONDB';
$user = 'root';
$pass = '989878';

$conn = new mysqli($host, $user, $pass, $dbname);

$stmt = $conn->prepare("INSERT INTO messages (pool_id, username, message, sent_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iss", $_SESSION['joined_pool_id'], $_SESSION['user'], $message);
$stmt->execute();
?>
