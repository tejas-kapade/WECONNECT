<?php
// login.php
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

$data = json_decode(file_get_contents("php://input"), true);
$username = $conn->real_escape_string($data['user']);
$password = $data['password'];

// Get hashed password from database
$sql = "SELECT password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();



if ($row = $result->fetch_assoc()) {
    if ($username === 'admin' && $password === '9090') {
        $_SESSION['user'] = 'admin';
        echo json_encode(['success' => true, 'admin' => true]);
    } 

elseif ($password === $row['password']) {
    $_SESSION['user'] = $username;
    echo json_encode(['success' => true, 'admin' => false]);
}

  //  if ($password = $row['password']) { echo json_encode(['success' => true]); } 
    else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password', 'admin' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}

$conn->close();
?>
