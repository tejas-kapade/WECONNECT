<?php
/**
 * logout.php — Session Termination Endpoint
 * WeConnect | Live Anonymous Chat Application
 *
 * Destroys the active session and returns a JSON confirmation.
 */

declare(strict_types=1);

session_start();
header('Content-Type: application/json');

// Unset all session variables
$_SESSION = [];

// Destroy the session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

session_destroy();

echo json_encode(['success' => true, 'message' => 'Logged out successfully.']);
