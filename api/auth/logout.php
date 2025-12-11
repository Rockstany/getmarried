<?php
/**
 * User Logout
 * GET /api/auth/logout.php
 */

require_once '../../includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy session
session_unset();
session_destroy();

// Redirect to homepage
header('Location: ' . SITE_URL . '/index.php');
exit;
