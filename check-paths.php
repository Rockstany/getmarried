<!DOCTYPE html>
<html>
<head>
    <title>GetMarried - Path Diagnostic</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        .warning { color: #f59e0b; }
        h2 { border-bottom: 2px solid #d946ef; padding-bottom: 10px; }
        code { background: #f3f4f6; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>🔍 GetMarried.site - Path Diagnostic Tool</h1>

    <?php
    require_once 'includes/config.php';

    echo '<div class="box">';
    echo '<h2>1. Configuration Check</h2>';
    echo '<p><strong>SITE_URL:</strong> <code>' . SITE_URL . '</code></p>';
    echo '<p><strong>Current Script:</strong> <code>' . $_SERVER['PHP_SELF'] . '</code></p>';
    echo '<p><strong>Document Root:</strong> <code>' . $_SERVER['DOCUMENT_ROOT'] . '</code></p>';
    echo '<p><strong>Script Directory:</strong> <code>' . __DIR__ . '</code></p>';
    echo '</div>';

    echo '<div class="box">';
    echo '<h2>2. CSS File Check</h2>';

    $cssFile = __DIR__ . '/assets/css/style.css';
    if (file_exists($cssFile)) {
        $fileSize = round(filesize($cssFile) / 1024, 2);
        echo '<p class="success">✅ CSS file exists!</p>';
        echo '<p><strong>Location:</strong> <code>' . $cssFile . '</code></p>';
        echo '<p><strong>Size:</strong> ' . $fileSize . ' KB</p>';
    } else {
        echo '<p class="error">❌ CSS file NOT found!</p>';
        echo '<p><strong>Looking for:</strong> <code>' . $cssFile . '</code></p>';
    }
    echo '</div>';

    echo '<div class="box">';
    echo '<h2>3. JavaScript File Check</h2>';

    $jsFile = __DIR__ . '/assets/js/main.js';
    if (file_exists($jsFile)) {
        $fileSize = round(filesize($jsFile) / 1024, 2);
        echo '<p class="success">✅ JavaScript file exists!</p>';
        echo '<p><strong>Location:</strong> <code>' . $jsFile . '</code></p>';
        echo '<p><strong>Size:</strong> ' . $fileSize . ' KB</p>';
    } else {
        echo '<p class="error">❌ JavaScript file NOT found!</p>';
        echo '<p><strong>Looking for:</strong> <code>' . $jsFile . '</code></p>';
    }
    echo '</div>';

    echo '<div class="box">';
    echo '<h2>4. Recommended Asset URLs</h2>';

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $scriptDir = dirname($_SERVER['PHP_SELF']);

    echo '<p><strong>Protocol:</strong> <code>' . $protocol . '</code></p>';
    echo '<p><strong>Host:</strong> <code>' . $host . '</code></p>';
    echo '<p><strong>Base Path:</strong> <code>' . $scriptDir . '</code></p>';

    $recommendedUrl = $protocol . $host . $scriptDir;
    echo '<p class="warning"><strong>⚠️ Your SITE_URL should probably be:</strong></p>';
    echo '<p><code style="font-size: 14px; display: block; padding: 10px; background: #fef3c7;">' . $recommendedUrl . '</code></p>';
    echo '</div>';

    echo '<div class="box">';
    echo '<h2>5. Test CSS Loading</h2>';
    echo '<p>Try these CSS URLs in your browser:</p>';

    $cssUrls = [
        SITE_URL . '/assets/css/style.css',
        $recommendedUrl . '/assets/css/style.css',
        $protocol . $host . '/getMarried/assets/css/style.css',
        $protocol . $host . '/assets/css/style.css',
    ];

    foreach ($cssUrls as $url) {
        echo '<p><a href="' . $url . '" target="_blank">' . $url . '</a></p>';
    }
    echo '</div>';

    echo '<div class="box">';
    echo '<h2>6. Quick Fix Instructions</h2>';
    echo '<ol>';
    echo '<li>Open <code>includes/config.php</code></li>';
    echo '<li>Find line: <code>define(\'SITE_URL\', \'http://localhost/getMarried\');</code></li>';
    echo '<li>Change it to: <code>define(\'SITE_URL\', \'' . $recommendedUrl . '\');</code></li>';
    echo '<li>Save and refresh your site</li>';
    echo '</ol>';
    echo '</div>';

    echo '<div class="box">';
    echo '<h2>7. Test Links</h2>';
    echo '<p><a href="test-css.html" target="_blank" class="success">→ Open CSS Test Page (HTML only)</a></p>';
    echo '<p><a href="index.php" target="_blank" class="success">→ Open Homepage (PHP with config)</a></p>';
    echo '</div>';
    ?>

    <div class="box">
        <h2>8. Browser Console Check</h2>
        <p>Press <code>F12</code> on your homepage and check Console tab for errors like:</p>
        <ul>
            <li>❌ <code>404 Not Found: style.css</code></li>
            <li>❌ <code>Failed to load resource</code></li>
        </ul>
    </div>

</body>
</html>
