<?php
/**
 * Smart Path Helper - Auto-detects correct asset paths
 * This ensures CSS/JS loads regardless of SITE_URL configuration
 */

function getAssetUrl($path) {
    // Try SITE_URL first (if configured correctly)
    if (defined('SITE_URL') && !empty(SITE_URL)) {
        return SITE_URL . $path;
    }

    // Fallback: Auto-detect base path
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];

    // Get the directory path
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);

    // Remove /index.php or other script names
    $basePath = str_replace('/index.php', '', $scriptDir);
    $basePath = rtrim($basePath, '/');

    return $protocol . $host . $basePath . $path;
}

function asset($path) {
    return getAssetUrl($path);
}
