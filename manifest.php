<?php
/**
 * Dynamic PWA Web App Manifest
 * يضمن تحديث اسم التطبيق وألوانه وبياناته تلقائياً وفقاً لإعدادات لوحة التحكم
 */
header('Content-Type: application/manifest+json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$site_name           = get_setting('site_name', 'شركة النظافة العامة');
$site_short_name     = get_setting('site_short_name', 'خدمات النظافة');
$site_tagline        = get_setting('site_tagline', 'خدمات تنظيف وتعقيم المنازل والفلل والمجالس بالدمام والشرقية');
$theme_primary_color = get_setting('theme_primary_color', '#0284C7');

$manifest = [
    'name'             => $site_name,
    'short_name'       => $site_short_name,
    'description'      => $site_tagline,
    'id'               => '/?pwa=' . substr(md5($site_name), 0, 8),
    'start_url'        => '/',
    'scope'            => '/',
    'display'          => 'standalone',
    'orientation'      => 'portrait',
    'background_color' => '#F0F9FF',
    'theme_color'      => $theme_primary_color,
    'dir'              => 'rtl',
    'lang'             => 'ar',
    'icons'            => [
        [
            'src'     => '/assets/images/1.jpg',
            'sizes'   => '192x192',
            'type'    => 'image/jpeg',
            'purpose' => 'any maskable'
        ],
        [
            'src'     => '/assets/images/1.jpg',
            'sizes'   => '512x512',
            'type'    => 'image/jpeg',
            'purpose' => 'any maskable'
        ]
    ]
];

echo json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
