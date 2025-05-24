<?php
session_start();
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'WECONDB';
$user = 'root';
$pass = '989878';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $poolName = $conn->real_escape_string($data['pool_name']);
    $poolPassword = $conn->real_escape_string($data['pool_password']);
    $createdBy = $_SESSION['user'];

    

    $stmt = $conn->prepare("INSERT INTO chat_pools (pool_name, pool_password, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $poolName, $poolPassword, $createdBy);

    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create pool']);
    }
    $stmt->close();
} else {
    $sql = "SELECT p.*, COUNT(m.id) AS message_count FROM chat_pools p LEFT JOIN messages m ON p.id = m.pool_id GROUP BY p.id";

    $result = $conn->query("SELECT id, pool_name, created_by FROM chat_pools ORDER BY created_at DESC");
    $result = $conn->query($sql);
    $pools = [];
    while ($row = $result->fetch_assoc()) {
        $pools[] = $row;
    }
    echo json_encode($pools);
}

// Add this inside your pool-fetching logic in create_pool.php


/*
$result = $conn->query($sql);
$pools = [];

while ($row = $result->fetch_assoc()) {
    $pools[] = $row;
}

echo json_encode($pools);
*/
$conn->close();
?>
