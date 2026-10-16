<?php
/**
 * Entrypoint / Router for Vercel Serverless Functions
 */

// Change working directory to project root
chdir(dirname(__DIR__));

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = rawurldecode($uri);

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
