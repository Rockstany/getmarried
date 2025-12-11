<?php
/**
 * GetMarried.site Configuration
 * Update these values for your Hostinger environment
 */

// Environment (development/production)
define('ENVIRONMENT', 'production');

// Database Configuration
define('DB_HOST', '127.0.0.1:3306');
define('DB_NAME', 'u717011923_getmarried');
define('DB_USER', 'u717011923_getmarried');  // ⚠️ UPDATE THIS with your Hostinger DB user
define('DB_PASS', 'zxc12345+A');      // ⚠️ UPDATE THIS with your Hostinger DB password
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
define('SITE_URL', 'https://getmarried.site');
define('SITE_NAME', 'GetMarried.site');
define('SITE_EMAIL', 'hello@getmarried.site');

// File Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');
define('UPLOAD_URL', SITE_URL . '/assets/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);

// Session Configuration
define('SESSION_LIFETIME', 60 * 60 * 24 * 7); // 7 days
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
session_set_cookie_params(SESSION_LIFETIME);

// Security
define('HASH_ALGO', PASSWORD_BCRYPT);
define('HASH_COST', 10);

// Pagination
define('ITEMS_PER_PAGE', 24);

// WhatsApp Configuration
define('WHATSAPP_BASE_URL', 'https://wa.me/');

// Error Reporting
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('Asia/Kolkata');
