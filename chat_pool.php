<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['joined_pool_id'])) {
    header("Location: index.html");
    exit;
}

$host = 'localhost';
$dbname = 'WECONDB';
$user = 'root';
$pass = '989878';

$conn = new mysqli($host, $user, $pass, $dbname);
$pool_id = $_SESSION['joined_pool_id'];

// Get pool name
$stmt = $conn->prepare("SELECT pool_name FROM chat_pools WHERE id = ?");
$stmt->bind_param("i", $pool_id);
$stmt->execute();
$result = $stmt->get_result();
$pool = $result->fetch_assoc();
$pool_name = $pool ? $pool['pool_name'] : "Unknown Pool";

// Fetch messages
$messages = [];
$stmt = $conn->prepare("SELECT username, message, sent_at FROM messages WHERE pool_id = ? ORDER BY sent_at ASC");
$stmt->bind_param("i", $pool_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $messages[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Chat - <?php echo htmlspecialchars($pool_name); ?></title>
    <style>
      body {
    font-family: Arial, sans-serif;
    background: #f2f2f2;
    margin: 0;
    padding: 0;
}

header {
    background: #4a90e2;
    color: white;
    padding: 1rem;
    text-align: center;
    font-size: 1.5rem;
}

.container {
    padding: 20px;
}

.chat-box {
    background: white;
    padding: 15px;
    border-radius: 10px;
    height: 60vh;
    overflow-y: auto;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    transition: height 0.3s ease-in-out;
}

/* Chat message */
.message {
    margin: 10px 0;
    padding: 10px;
    border-radius: 8px;
    max-width: 60%;
    position: relative;
    word-wrap: break-word;
}

.username {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

.timestamp {
    font-size: 0.75rem;
    color: #666;
    position: absolute;
    bottom: 5px;
    right: 10px;
}

/* Input container */
.input-area-container {
    background: #d9e6f2;
    padding: 15px;
    border-radius: 10px;
}

.input-area {
    display: flex;
    flex-direction: row;
    gap: 10px;
    flex-wrap: wrap;
}

.input-area input {
    flex: 1;
    padding: 15px 1px;
    font-size: 1rem;
    border-radius: 8px;
    border: 1px solid #ccc;
    min-width: 150px;
}

.input-area button {
    padding: 15px 20px;
    background: #4a90e2;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    white-space: nowrap;
}

.input-area button:hover {
    background: #357abd;
}

/* Back button */
.back-button {
    margin-top: 15px;
    display: inline-block;
    background: #777;
    color: white;
    padding: 10px 15px;
    border-radius: 6px;
    text-decoration: none;
}

/* Username color variants */
.user-1 { background: #e0f7fa; }
.user-2 { background: #ffe0b2; }
.user-3 { background: #e1bee7; }
.user-4 { background: #dcedc8; }
.user-5 { background:rgb(237, 184, 173); }


/* Responsive Styles */
@media (max-width: 768px) {
    header {
        font-size: 1.2rem;
    }

    .container {
        padding: 10px;
    }

    .message {
        max-width: 100%;
    }

    .input-area {
        flex-direction: column;
    }

    .input-area input,
    .input-area button {
        width: 100%;
    }

    .input-area button {
        padding: 12px;
        font-size: 1rem;
    }

    .chat-box {
        height: 50vh;
    }
}

@media (max-width: 480px) {
    .input-area input {
        font-size: 0.95rem;
    }

    .input-area button {
        font-size: 0.95rem;
    }

    .timestamp {
        font-size: 0.65rem;
    }
}

/* Popup animation for new messages */
.message.new-message {
  animation: popup 0.5s ease-in-out;
}

@keyframes popup {
  0%   { transform: scale(1); background-color: #ffff99; }
  50%  { transform: scale(1.05); background-color: #fff176; }
  100% { transform: scale(1); background-color: transparent; }
}



    </style>
</head>
<body>

<header>
    Welcome to Pool: <strong><?php echo htmlspecialchars($pool_name); ?></strong>
</header>

<div class="container">
    <a href="welcome.php" class="back-button">← Back to Pools</a>

    <div class="chat-box" id="chatBox">
        <?php
        $user_color_classes = ['user-1', 'user-2', 'user-3', 'user-4', 'user-5'];
        foreach ($messages as $msg) {
            $class = $user_color_classes[crc32($msg['username']) % count($user_color_classes)];
            echo '<div class="message ' . $class . '">';
            echo '<span class="username">' . htmlspecialchars($msg['username']) . '</span>';
            echo htmlspecialchars($msg['message']);
            echo '<span class="timestamp">' . date("d M Y, h:i A", strtotime($msg['sent_at'])) . '</span>';
            echo '</div>';
        }
        ?>
    </div>

    <div class="input-area-container">
        <div class="input-area">
            <input type="text" id="messageInput" placeholder="Type your message..." />
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>

<script>


let lastMessageCount = 0;
/*
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('messageInput');
    const chatBox = document.getElementById('chatBox');

    input.addEventListener('focus', () => {
        if (window.innerWidth < 768) { // Mobile-only
            chatBox.style.height = '30vh'; // Shrink to fit keyboard
            chatBox.style.padding = '15px 1px';
            setTimeout(() => {
                chatBox.scrollTop = chatBox.scrollHeight; // Optional: scroll to bottom when typing
            }, 300);
        }
    });

    input.addEventListener('blur', () => {
        if (window.innerWidth < 768) {
            chatBox.style.height = '50vh'; // Restore original height
        }
    });
});
*/


function scrollToBottom() {
    const chatBox = document.getElementById('chatBox');
    chatBox.scrollTop = chatBox.scrollHeight;
}

async function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    if (!message) return;

    await fetch('send_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message })
    });

    input.value = '';
    await loadMessages();
    scrollToBottom();  // This scroll only after sending
}


async function loadMessages() {
    const response = await fetch('get_messages.php');
    const messages = await response.json();

    const chatBox = document.getElementById('chatBox');

    // Skip reloading if no new messages
    if (messages.length === lastMessageCount) return;

    chatBox.innerHTML = ''; // Clear old messages

    const userClasses = ['user-1', 'user-2', 'user-3', 'user-4', 'user-5'];

    messages.forEach((msg, index) => {
        const msgDiv = document.createElement('div');
        const userClass = userClasses[Math.abs(msg.username.hashCode()) % userClasses.length];
        msgDiv.className = `message ${userClass}`;

        // Only animate last (newest) message
        if (index === messages.length - 1 && messages.length > lastMessageCount) {
            msgDiv.classList.add('new-message');
        }

        msgDiv.innerHTML = `
            <span class="username">${msg.username}</span>
            ${msg.message}
            <span class="timestamp">${msg.sent_at}</span>
        `;
        chatBox.appendChild(msgDiv);
    });

    lastMessageCount = messages.length;
}


// Add simple hashCode function for username color mapping
String.prototype.hashCode = function() {
    var hash = 0;
    for (var i = 0; i < this.length; i++) {
        hash = this.charCodeAt(i) + ((hash << 5) - hash);
    }
    return hash;
};

setInterval(loadMessages, 1000); // Refresh messages every 1 s
scrollToBottom();
</script>

</body>
</html>
