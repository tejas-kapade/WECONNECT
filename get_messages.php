<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['joined_pool_id'])) {
    http_response_code(401);
    exit;
}

$host = 'localhost';
$dbname = 'WECONDB';
$user = 'root';
$pass = '989878';

$conn = new mysqli($host, $user, $pass, $dbname);

$stmt = $conn->prepare("SELECT username, message, DATE_FORMAT(sent_at, '%d %b %Y, %h:%i %p') AS sent_at FROM messages WHERE pool_id = ? ORDER BY sent_at ASC");
$stmt->bind_param("i", $_SESSION['joined_pool_id']);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
