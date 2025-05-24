<?php
// sql_terminal.php

$host = 'localhost';
$dbname = 'WECONDB';
$user = 'root';
$pass = '989878';

$conn = new mysqli($host, $user, $pass, $dbname);
$message = '';
$results = [];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['query'])) {
    $query = $_POST['query'];
    $result = $conn->query($query);

    if ($result === TRUE) {
        $message = "Query executed successfully.";
    } elseif ($result instanceof mysqli_result) {
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Terminal</title>
    <style>
        body {
            font-family: monospace;
            padding: 20px;
            background-color: #f0f0f0;
        }
        textarea {
            width: 100%;
            height: 100px;
            font-family: monospace;
            font-size: 14px;
            padding: 10px;
        }
        button {
            padding: 10px 20px;
            margin-top: 10px;
            background: #333;
            color: white;
            border: none;
            cursor: pointer;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        .message {
            margin-top: 15px;
            color: green;
        }
    </style>
</head>
<body>
    <h2>SQL Command Line Terminal (PHP)</h2>
    <form method="post">
        <textarea name="query" placeholder="Enter your SQL query here..."><?php echo isset($_POST['query']) ? htmlspecialchars($_POST['query']) : ''; ?></textarea>
        <br>
        <button type="submit">Execute</button>
    </form>

    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <table>
            <thead>
                <tr>
                    <?php foreach (array_keys($results[0]) as $col): ?>
                        <th><?php echo htmlspecialchars($col); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <?php foreach ($row as $value): ?>
                            <td><?php echo htmlspecialchars($value); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
