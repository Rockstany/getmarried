<?php
/**
 * Common Utility Functions
 */

/**
 * Sanitize input data
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Hash password
 */
function hashPassword($password) {
    return password_hash($password, HASH_ALGO, ['cost' => HASH_COST]);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Generate random token
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * JSON response helper
 */
function jsonResponse($success, $data = null, $error = null, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'error' => $error,
        'request_id' => uniqid('req_', true)
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user ID
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Redirect helper
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Format price in Indian Rupees
 */
function formatPrice($amount) {
    return '₹' . number_format($amount, 0, '.', ',');
}

/**
 * Time ago helper
 */
function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;

    if ($difference < 60) {
        return 'Just now';
    } elseif ($difference < 3600) {
        $mins = floor($difference / 60);
        return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($difference < 86400) {
        $hours = floor($difference / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($difference < 604800) {
        $days = floor($difference / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('d M Y', $timestamp);
    }
}

/**
 * Upload image helper
 */
function uploadImage($file, $subdir = '') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'No file uploaded or upload error'];
    }

    // Validate file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'error' => 'File size exceeds maximum allowed'];
    }

    // Validate file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return ['success' => false, 'error' => 'Invalid file type. Allowed: ' . implode(', ', ALLOWED_EXTENSIONS)];
    }

    // Create upload directory if not exists
    $yearMonth = date('Y/m');
    $uploadPath = UPLOAD_DIR . $yearMonth . '/' . $subdir;
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    // Generate unique filename
    $fileName = uniqid() . '_' . time() . '.' . $extension;
    $filePath = $uploadPath . '/' . $fileName;
    $fileUrl = UPLOAD_URL . $yearMonth . '/' . $subdir . '/' . $fileName;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Optional: Create thumbnail or compress image here
        return [
            'success' => true,
            'path' => $filePath,
            'url' => $fileUrl,
            'filename' => $fileName
        ];
    } else {
        return ['success' => false, 'error' => 'Failed to move uploaded file'];
    }
}

/**
 * Generate WhatsApp link
 */
function getWhatsAppLink($phone, $message = '') {
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    if (substr($cleanPhone, 0, 2) !== '91' && strlen($cleanPhone) === 10) {
        $cleanPhone = '91' . $cleanPhone;
    }
    return WHATSAPP_BASE_URL . $cleanPhone . '?text=' . urlencode($message);
}

/**
 * Log analytics event
 */
function logEvent($eventName, $eventData = null, $userId = null) {
    try {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO analytics_events
            (event_name, user_id, event_data, utm_source, utm_medium, utm_campaign, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $eventName,
            $userId ?? getCurrentUserId(),
            $eventData ? json_encode($eventData) : null,
            $_GET['utm_source'] ?? null,
            $_GET['utm_medium'] ?? null,
            $_GET['utm_campaign'] ?? null,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);

        return true;
    } catch (Exception $e) {
        error_log("Analytics Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Send email helper (configure SMTP in production)
 */
function sendEmail($to, $subject, $body) {
    // For Hostinger, configure this with SMTP settings
    $headers = "From: " . SITE_EMAIL . "\r\n";
    $headers .= "Reply-To: " . SITE_EMAIL . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    return mail($to, $subject, $body, $headers);
}

/**
 * Escape output for HTML
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Debug helper (only in development)
 */
function dd($data) {
    if (ENVIRONMENT === 'development') {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
    }
}
