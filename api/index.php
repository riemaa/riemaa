<?php
/**
 * Entrypoint / Router for Vercel Serverless Functions
 */

if (!ob_get_level()) {
    ob_start();
}

// Change working directory to project root
chdir(dirname(__DIR__));

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = rawurldecode($uri);

// Route helper function for static files
function serve_static_if_exists($filePath) {
    if (is_file($filePath)) {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimes = [
            'css'   => 'text/css; charset=UTF-8',
            'js'    => 'application/javascript; charset=UTF-8',
            'png'   => 'image/png',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'gif'   => 'image/gif',
            'svg'   => 'image/svg+xml',
            'ico'   => 'image/x-icon',
            'webp'  => 'image/webp',
            'woff2' => 'font/woff2',
            'woff'  => 'font/woff',
            'ttf'   => 'font/ttf',
            'json'  => 'application/json',
        ];
        if (isset($mimes[$ext])) {
            header('Content-Type: ' . $mimes[$ext]);
            header('Cache-Control: public, max-age=31536000');
            readfile($filePath);
            exit;
        }
    }
}

// Check direct static assets in assets/ or admin/assets/
if (strpos($uri, '/assets/') === 0) {
    serve_static_if_exists(__DIR__ . '/..' . $uri);
}
if (strpos($uri, '/admin/assets/') === 0) {
    serve_static_if_exists(__DIR__ . '/..' . $uri);
}
if ($uri === '/manifest.json' || $uri === '/manifest.webmanifest') {
    require __DIR__ . '/../manifest.php';
    exit;
}
if ($uri === '/sw.js') {
    serve_static_if_exists(__DIR__ . '/../sw.js');
}

// Convenient redirects for /login or /login.php
if ($uri === '/login' || $uri === '/login.php') {
    header('Location: /admin/login.php');
    exit;
}

// Route sitemap
if ($uri === '/sitemap.xml' || $uri === '/sitemap.php') {
    require __DIR__ . '/../sitemap.php';
    exit;
}

// Route admin
if (strpos($uri, '/admin') === 0) {
    $sub = substr($uri, strlen('/admin'));
    $sub = trim($sub, '/');
    if ($sub === '' || $sub === 'index.php') {
        require __DIR__ . '/../admin/index.php';
        exit;
    }
    $target = __DIR__ . '/../admin/' . $sub;
    serve_static_if_exists($target);

    if (is_file($target)) {
        require $target;
        exit;
    }
    if (is_file($target . '.php')) {
        require $target . '.php';
        exit;
    }
    require __DIR__ . '/../admin/index.php';
    exit;
}

// Route ajax
if (strpos($uri, '/ajax') === 0) {
    $target = __DIR__ . '/..' . $uri;
    if (is_file($target)) {
        require $target;
        exit;
    }
}

// Default landing page
require __DIR__ . '/../index.php';
