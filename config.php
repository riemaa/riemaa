<?php
/**
 * ملف الإعدادات العامة والاتصال بقاعدة البيانات
 * يدعم العمل بملف قاعدة بيانات داخلية SQLite داخل المشروع لسهولة الرفع على GitHub
 * ويدعم أيضاً التحويل إلى MySQL عند الرغبة عبر المتغيرات البيئية
 */

if (!ob_get_level()) {
    ob_start();
}

// مفتاح التشفير العام للتطبيق (لحماية CSRF والمصادقة السيرفرلس)
define('APP_KEY', getenv('APP_KEY') ?: 'afsh_secret_key_riemaa_2026_x791a8c3');

if (session_status() === PHP_SESSION_NONE) {
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    session_set_cookie_params([
        'lifetime' => 86400 * 7,
        'path'     => '/',
        'secure'   => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// ضبط المنطقة الزمنية
date_default_timezone_set('Asia/Riyadh');

// نوع قاعدة البيانات: sqlite (افتراضياً بدون أي خوادم خارجية) أو mysql
define('DB_TYPE', getenv('DB_TYPE') ?: (getenv('MYSQLHOST') ? 'mysql' : 'sqlite'));

// المسارات الأساسية
define('BASE_PATH', __DIR__);
define('DATA_DIR', BASE_PATH . DIRECTORY_SEPARATOR . 'data');
define('SQLITE_FILE', DATA_DIR . DIRECTORY_SEPARATOR . 'database.sqlite');
define('UPLOAD_DIR', BASE_PATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads');

// إعدادات MySQL (في حال تفعيلها بدلاً من SQLite)
define('DB_HOST', getenv('DB_HOST') ?: (getenv('MYSQLHOST') ?: 'localhost'));
define('DB_PORT', getenv('DB_PORT') ?: (getenv('MYSQLPORT') ?: '3306'));
define('DB_NAME', getenv('DB_NAME') ?: (getenv('MYSQLDATABASE') ?: 'afsh_db'));
define('DB_USER', getenv('DB_USER') ?: (getenv('MYSQLUSER') ?: 'root'));
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : (getenv('MYSQLPASSWORD') !== false ? getenv('MYSQLPASSWORD') : ''));
define('DB_CHARSET', 'utf8mb4');

// تحديد الرابط الأساسي للموقع تلقائياً
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$scriptDir = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])) : '';
$baseDir = preg_replace('#/(admin|ajax)(/.*)?$#', '', $scriptDir);
$baseDir = rtrim($baseDir, '/');

define('SITE_URL', getenv('SITE_URL') ?: ($protocol . $host . ($baseDir ? $baseDir : '')));
define('UPLOAD_URL', SITE_URL . '/assets/uploads');
