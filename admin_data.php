<?php
// admin_data.php

header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'WECONDB';
$user = 'root';
$pass = '989878';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed']);
    exit;
}

$result = $conn->query("SELECT id, username, password FROM users");
$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
$conn->close();
?>
