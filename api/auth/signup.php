<?php
/**
 * User Signup
 * POST /api/auth/signup.php
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
$fullName = isset($input['full_name']) ? sanitize($input['full_name']) : '';
$phone = isset($input['phone']) ? sanitize($input['phone']) : null;

// Validation
if (empty($email) || !isValidEmail($email)) {
    jsonResponse(false, null, 'Valid email is required');
}

if (empty($password) || strlen($password) < 6) {
    jsonResponse(false, null, 'Password must be at least 6 characters');
}

if (empty($fullName)) {
    jsonResponse(false, null, 'Full name is required');
}

try {
    $db = getDB();

    // Check if email already exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        jsonResponse(false, null, 'Email already registered');
    }

    // Hash password
    $passwordHash = hashPassword($password);

    // Insert user
    $stmt = $db->prepare("
        INSERT INTO users (email, password_hash, full_name, phone, role)
        VALUES (?, ?, ?, ?, 'user')
    ");

    $stmt->execute([$email, $passwordHash, $fullName, $phone]);

    $userId = $db->lastInsertId();

    // Set session
    $_SESSION['user_id'] = $userId;
    $_SESSION['email'] = $email;
    $_SESSION['full_name'] = $fullName;
    $_SESSION['role'] = 'user';

    // Log analytics
    logEvent('user_signup', ['method' => 'email'], $userId);

    jsonResponse(true, [
        'user_id' => $userId,
        'email' => $email,
        'full_name' => $fullName,
    ]);

} catch (Exception $e) {
    error_log("Signup Error: " . $e->getMessage());
    jsonResponse(false, null, 'Signup failed. Please try again.');
}
