<?php
/**
 * Application Configuration
 * 
 * Database credentials and application settings.
 * Copy this file to config.php and update the values.
 */

// Database configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'erreality');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// Application configuration
define('APP_NAME', 'Erreality Admin');
define('APP_URL', getenv('APP_URL') ?: 'https://erreality.ru');
define('APP_ENV', getenv('APP_ENV') ?: 'production');
define('APP_DEBUG', getenv('APP_DEBUG') ?: false);

// Session configuration
define('SESSION_LIFETIME', 7200); // 2 hours
define('SESSION_NAME', 'erreality_admin');

// Paths
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH . '/public');
define('SRC_PATH', BASE_PATH . '/src');
define('VIEWS_PATH', SRC_PATH . '/Views');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');
define('SCREENSHOTS_PATH', UPLOADS_PATH . '/screenshots');
define('THUMBNAILS_PATH', UPLOADS_PATH . '/thumbnails');
define('RESUME_PATH', UPLOADS_PATH . '/resume');

// Frontend data path (relative to project root)
define('FRONTEND_DATA_PATH', dirname(BASE_PATH) . '/frontend/src/lib/data');

// Upload limits
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10 MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('THUMBNAIL_WIDTH', 400);
define('THUMBNAIL_HEIGHT', 300);

// Error reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Timezone
date_default_timezone_set('Europe/Moscow');

// Autoload
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    $baseDir = SRC_PATH . '/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});