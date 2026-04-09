<?php
// admin_data.php

header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

$result = $conn->query("SELECT id, username, password FROM users");
$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
$conn->close();
?>
