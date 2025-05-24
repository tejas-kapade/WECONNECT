<?php
// DB credentials
$host = 'localhost';
$user = 'root';
$password = '989878';  // Replace with your actual password
$dbname = 'WECONDB';

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "DB Connection failed."]));
}

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $username = trim($data['user']);
    $password = trim($data['password']);

    // Optional: check for empty values
    if (!$username || !$password) {
        echo json_encode(["success" => false, "message" => "Username and password are required."]);
        exit;
    }

    // Optional: Check if username exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Username already exists."]);
        exit;
    }
    $check->close();

    // INSERT new user
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password); // In production, hash password here!

    if ($stmt->execute()) {
        $_SESSION['user'] = $username;
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
} else {
    // GET all users
    $result = $conn->query("SELECT * FROM users");
    $users = [];

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode($users);
}

$conn->close();
?>
