<?php
/**
 * User Login
 * POST /api/auth/login.php
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, null, 'Method not allowed', 405);
}

// Get request body
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
$email = isset($input['email']) ? sanitize($input['email']) : '';
$password = isset($input['password']) ? $input['password'] : '';

if (empty($email) || !isValidEmail($email)) {
    jsonResponse(false, null, 'Valid email is required');
}

if (empty($password)) {
    jsonResponse(false, null, 'Password is required');
}

try {
    $db = getDB();

    // Get user by email
    $stmt = $db->prepare("SELECT id, email, password_hash, full_name, role, is_verified FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        jsonResponse(false, null, 'Invalid email or password');
    }

    // Verify password
    if (!verifyPassword($password, $user['password_hash'])) {
        jsonResponse(false, null, 'Invalid email or password');
    }

    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['is_verified'] = $user['is_verified'];

    // Log analytics
    logEvent('user_login', ['method' => 'email'], $user['id']);

    jsonResponse(true, [
        'user_id' => $user['id'],
        'email' => $user['email'],
        'full_name' => $user['full_name'],
        'role' => $user['role'],
    ]);

} catch (Exception $e) {
    error_log("Login Error: " . $e->getMessage());
    jsonResponse(false, null, 'Login failed. Please try again.');
}
