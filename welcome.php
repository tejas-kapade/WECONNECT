    <?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: index.html');
        exit;
    }
    $username = $_SESSION['user'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Welcome</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
        font-family: Arial, sans-serif;
        padding: 20px;
        background: #f0f2f5;
        color: #333;
        }
        header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 20px;
        }
        .greeting { font-size: 1.5em; font-weight: bold; }
        .top-right {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        }
        .top-right button {
        padding: 10px;
        font-size: 1em;
        cursor: pointer;
        }
        .create-pool-form input, .create-pool-form button {
        padding: 10px;
        font-size: 1em;
        }
        .create-pool-form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
        }
        .pool-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        }
        .pool-box {
        padding: 15px;
        border-radius: 12px;
        color: #333;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 300px;
        flex: 1 1 calc(33.333% - 20px);
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease-in-out;
        }
        .pool-box:hover { transform: scale(1.02); }
        .pool-box h3 { margin-bottom: 5px; }
        .join-form {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 10px;
        }
        .join-form input, .join-form button {
        padding: 8px;
        font-size: 1em;
        }
        .delete-btn {
        margin-top: 10px;
        background-color: #ff4d4d;
        color: white;
        border: none;
        cursor: pointer;
        }
        @media (max-width: 768px) {
        .pool-box { flex: 1 1 calc(50% - 20px); }
        }
        @media (max-width: 480px) {
        .pool-box { flex: 1 1 100%; }
        .create-pool-form { flex-direction: column; }
        }
    </style>
    </head>
    <body>
    <header>
        <div class="greeting">Welcome, <?php echo htmlspecialchars($username); ?>!</div> 
        <div class="top-right">
        <button onclick="toggleCreateForm()" id="create_pool_btn" style="background:#2ecc71;color:white;border:none;padding:10px;border-radius:8px;">+ Create your Pool</button>

<button style="background:tomato;color:white;border:none;border-radius:10%;" onclick="logout()">Logout</button>

<form id="createPoolForm" class="create-pool-form" style="display:none;" onsubmit="createPool(event)">
  <input type="text" id="poolName" placeholder="Your Pool Name" required>
  <input type="password" id="poolPassword" placeholder="Password" required>
  <button type="submit">Create Pool</button>
</form>

        </div>
    </header>

    <br> <h3> Checkout below Available Chatting Pools</h3> <br>

    <section class="pool-container" id="poolContainer">
        <!-- Pool boxes will be loaded here -->
    </section>

    <script>
        const currentUser = "<?php echo $username; ?>";
        const pastelColors = [
        '#ffd1dc', '#c1f0f6', '#ffe7a0', '#c5f7d6', '#e5d1f2',
        '#ffdab9', '#d8bfd8', '#b0e0e6', '#f4c2c2', '#c3f584'
        ];

        function toggleCreateForm() {
  const form = document.getElementById('createPoolForm');
  form.style.display = form.style.display === 'none' ? 'flex' : 'none';
}


        async function loadPools() {
        const res = await fetch('create_pool.php');
        const pools = await res.json();
        const container = document.getElementById('poolContainer');
        container.innerHTML = '';

        pools.forEach((pool, index) => {
            const color = pastelColors[index % pastelColors.length];
            const box = document.createElement('div');
            box.className = 'pool-box';
            box.style.backgroundColor = color;
            box.innerHTML = `
             <h3>${pool.pool_name}</h3>
  <p>Created by: ${pool.created_by}</p><br>
  <p style="font-size: 0.9em; color: #555;">Total Messages: ${pool.message_count}</p>
  <div class="join-form">
    <input type="password" placeholder="Enter Pool Password" id="pass-${pool.id}">
    <button onclick="joinPool(${pool.id})">Join Pool</button>
  </div>
  ${pool.created_by === currentUser ? `<button class="delete-btn" onclick="deletePool(${pool.id})">Delete Pool</button>` : ''}
`;
            container.appendChild(box);
        });
        }

        async function createPool(event) {
        event.preventDefault();
        const poolName = document.getElementById('poolName').value;
        const poolPassword = document.getElementById('poolPassword').value;

        const res = await fetch('create_pool.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ pool_name: poolName, pool_password: poolPassword })
        });
        const result = await res.json();
        if (result.success) {
            document.getElementById('poolName').value = '';
            document.getElementById('poolPassword').value = '';
            loadPools();
        } else {
            alert('Failed to create pool');
        }
        }

        async function joinPool(poolId) {
        const password = document.getElementById(`pass-${poolId}`).value;
        const res = await fetch('join_pool.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ pool_id: poolId, password })
        });
        const result = await res.json();
        if (result.success) {
            window.location.href = 'chat_pool.php';
        } else {
            alert(result.message || 'Incorrect password');
        }
        }

        async function deletePool(poolId) {
        if (!confirm("Are you sure you want to delete this pool?")) return;

        const res = await fetch('delete_pool.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ pool_id: poolId })
        });
        const result = await res.json();
        if (result.success) {
            loadPools();
        } else {
            alert('Failed to delete pool');
        }
        }

        function logout() {
        fetch('logout.php').then(() => {
            window.location.href = 'index.html';
        });
        }

        loadPools();
    </script>
    </body>
    </html>
