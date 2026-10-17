<?php
/**
 * سكربت لتغيير هوية واسم المشروع بضغطة زر واحدة لأي مشروع أو عميل جديد مستقبلاً
 * الاستخدام من التيرمنال:
 * php rebrand.php
 * أو بتمرير المعاملات مباشرة:
 * php rebrand.php "اسم الشركة الجديد" "الاسم المختصر" "05XXXXXXXX"
 */

if (php_sapi_name() !== 'cli') {
    die("هذا السكربت يعمل من خلال موجه الأوامر (CLI) فقط.\n");
}

echo "====================================================\n";
echo "   أداة ضبط وتغيير هوية المشروع وتطبيقات الـ PWA    \n";
echo "====================================================\n\n";

$args = array_slice($argv, 1);

if (isset($args[0]) && !empty($args[0])) {
    $site_name = trim($args[0]);
} else {
    echo "أدخل اسم الشركة الكامل (مثال: شركة الوفاء للنظافة العامة): ";
    $site_name = trim(fgets(STDIN));
}

if (isset($args[1]) && !empty($args[1])) {
    $site_short = trim($args[1]);
} else {
    echo "أدخل الاسم المختصر للتطبيق (مثال: الوفاء كلين): ";
    $site_short = trim(fgets(STDIN));
}

if (isset($args[2]) && !empty($args[2])) {
    $phone = trim($args[2]);
} else {
    echo "أدخل رقم الجوال الأساسي (مثال: 0573247739): ";
    $phone = trim(fgets(STDIN));
}

if (empty($site_name)) {
    die("خطأ: يجب تحديد اسم الشركة على الأقل.\n");
}

if (empty($site_short)) {
    $site_short = $site_name;
}

if (empty($phone)) {
    $phone = '0573247739';
}

$clean_phone = preg_replace('/[^0-9]/', '', $phone);
$wa_phone = $clean_phone;
if (strpos($wa_phone, '0') === 0) {
    $wa_phone = '966' . substr($wa_phone, 1);
} elseif (strpos($wa_phone, '966') !== 0) {
    $wa_phone = '966' . $wa_phone;
}

$db_file = __DIR__ . '/data/database.sqlite';
if (!file_exists($db_file)) {
    die("خطأ: لم يتم العثور على قاعدة البيانات في: $db_file\n");
}

$pdo = new PDO('sqlite:' . $db_file);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 1. تحديث الإعدادات
$settings_to_update = [
    'site_name'        => $site_name,
    'site_short_name'  => $site_short,
    'phone_primary'    => $phone,
    'phone_secondary'  => $phone,
    'whatsapp_number'  => $wa_phone,
    'meta_title'       => "{$site_name} بالدمام | تنظيف منازل وسجاد ومجالس وخزانات",
    'meta_description' => "{$site_name} بالدمام والمنطقة الشرقية - أفضل خدمات تنظيف منازل وفلل، غسيل سجاد ومجالس وكنب بالبخار، جلي أرضيات، تنظيف خزانات مياه مع الضمان. اتصل الآن {$phone}",
    'meta_author'      => $site_name
];

$stmt = $pdo->prepare("INSERT OR REPLACE INTO settings (setting_key, setting_value) VALUES (:k, :v)");
foreach ($settings_to_update as $k => $v) {
    $stmt->execute([':k' => $k, ':v' => $v]);
}

// 2. تحديث قسم الهيرو والنبذة إذا كان يحتوي على مسمى قديم
$hero_row = $pdo->query("SELECT content_data FROM sections_content WHERE section_key = 'hero'")->fetchColumn();
if ($hero_row) {
    $hero_data = json_decode($hero_row, true);
    if (is_array($hero_data)) {
        $hero_data['desc'] = preg_replace('/شركة [^–-]+ [–-]/u', "{$site_name} –", $hero_data['desc'] ?? '');
        $stmt_s = $pdo->prepare("INSERT OR REPLACE INTO sections_content (section_key, content_data) VALUES ('hero', :d)");
        $stmt_s->execute([':d' => json_encode($hero_data, JSON_UNESCAPED_UNICODE)]);
    }
}

// 3. تحديث manifest.json الثابت مع Unique App ID
$manifest_path = __DIR__ . '/manifest.json';
$pwa_id = '/?pwa=' . substr(md5($site_name), 0, 8);
$manifest_data = [
    'name'             => $site_name,
    'short_name'       => $site_short,
    'description'      => "خدمات تنظيف وتعقيم المنازل والفلل والمجالس بالدمام والشرقية",
    'id'               => $pwa_id,
    'start_url'        => '/',
    'scope'            => '/',
    'display'          => 'standalone',
    'orientation'      => 'portrait',
    'background_color' => '#F0F9FF',
    'theme_color'      => '#0284C7',
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
file_put_contents($manifest_path, json_encode($manifest_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 4. تحديث Service Worker Cache Name لمسح أي كاش قديم على أجهزة العملاء
$sw_path = __DIR__ . '/sw.js';
if (file_exists($sw_path)) {
    $sw_content = file_get_contents($sw_path);
    $new_cache_name = 'pwa-v' . time();
    $sw_content = preg_replace("/const CACHE_NAME = ['\"][^'\"]+['\"];/", "const CACHE_NAME = '{$new_cache_name}';", $sw_content);
    file_put_contents($sw_path, $sw_content);
}

// 5. تصدير ملف database.sql المتوافق
$tables = ['settings', 'admins', 'sections_content', 'services', 'fleet', 'why_us', 'gallery', 'packages', 'service_areas', 'work_steps', 'guarantees', 'testimonials', 'faqs', 'seo_articles', 'bookings'];
$sql_out = "-- ============================================================\n";
$sql_out .= "-- قاعدة بيانات مشروع {$site_name}\n";
$sql_out .= "-- تم التوليد تلقائياً: " . date('Y-m-d H:i:s') . "\n";
$sql_out .= "-- ============================================================\n\n";

foreach ($tables as $t) {
    $rows = $pdo->query("SELECT * FROM $t")->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($rows)) {
        $cols = array_keys($rows[0]);
        $cols_str = '`' . implode('`, `', $cols) . '`';
        $sql_out .= "INSERT INTO `$t` ($cols_str) VALUES\n";
        $val_lines = [];
        foreach ($rows as $r) {
            $vals = [];
            foreach ($r as $val) {
                if ($val === null) $vals[] = 'NULL';
                elseif (is_numeric($val) && !preg_match('/^0[0-9]/', $val)) $vals[] = $val;
                else $vals[] = "'" . addslashes($val) . "'";
            }
            $val_lines[] = " (" . implode(', ', $vals) . ")";
        }
        $sql_out .= implode(",\n", $val_lines) . ";\n\n";
    }
}
file_put_contents(__DIR__ . '/database.sql', $sql_out);

echo "\n تم تحديث هوية المشروع بالكامل بنجاح!\n";
echo "====================================================\n";
echo " - اسم الموقع: {$site_name}\n";
echo " - اسم التطبيق المختصر: {$site_short}\n";
echo " - رقم الهاتف والواتساب: {$phone}\n";
echo " - معرّف تطبيق الـ PWA الفريد: {$pwa_id}\n";
echo " - تم تحديث قاعدة بيانات SQLite الداخلية\n";
echo " - تم تحديث ملف manifest.json\n";
echo " - تم تحديث وتصدير database.sql\n";
echo " - تم تحديث كاش Service Worker لفرض التحديث على جميع الهواتف\n";
echo "====================================================\n";
echo "🚀 جاهز للرفع على GitHub والنشر المباشر!\n\n";
