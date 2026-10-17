<?php
/**
 * الدوال المساعدة والحماية وإدارة البيانات
 */

require_once __DIR__ . '/db.php';

// مصفوفة الذاكرة المؤقتة للإعدادات لتفادي الاستعلامات المتكررة
$GLOBALS['_cached_settings'] = null;

/**
 * جلب كافة الإعدادات دفعة واحدة وحفظها في الذاكرة
 */
function get_all_settings() {
    global $pdo;
    if ($GLOBALS['_cached_settings'] === null) {
        $GLOBALS['_cached_settings'] = [];
        try {
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
            while ($row = $stmt->fetch()) {
                $GLOBALS['_cached_settings'][$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            // في حال عدم وجود الجدول
        }
    }
    return $GLOBALS['_cached_settings'];
}

/**
 * جلب قيمة إعداد معين
 */
function get_setting($key, $default = '') {
    $settings = get_all_settings();
    return isset($settings[$key]) && $settings[$key] !== '' ? $settings[$key] : $default;
}

/**
 * تحديث أو إضافة إعداد
 */
function update_setting($key, $value) {
    global $pdo;
    if (DB_TYPE === 'sqlite') {
        $stmt = $pdo->prepare("INSERT OR REPLACE INTO settings (setting_key, setting_value) VALUES (:k, :v)");
        $res = $stmt->execute([
            ':k' => $key,
            ':v' => $value
        ]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v2");
        $res = $stmt->execute([
            ':k' => $key,
            ':v' => $value,
            ':v2' => $value
        ]);
    }
    if ($GLOBALS['_cached_settings'] !== null) {
        $GLOBALS['_cached_settings'][$key] = $value;
    }
    return $res;
}

/**
 * جلب بيانات قسم معين (JSON)
 */
function get_section($section_key, $default = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT content_data FROM sections_content WHERE section_key = :k LIMIT 1");
        $stmt->execute([':k' => $section_key]);
        $row = $stmt->fetch();
        if ($row && !empty($row['content_data'])) {
            $decoded = json_decode($row['content_data'], true);
            return is_array($decoded) ? array_merge($default, $decoded) : $default;
        }
    } catch (Exception $e) {}
    return $default;
}

/**
 * تحديث بيانات قسم معين (JSON)
 */
function update_section($section_key, $data) {
    global $pdo;
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    if (DB_TYPE === 'sqlite') {
        $stmt = $pdo->prepare("INSERT OR REPLACE INTO sections_content (section_key, content_data) VALUES (:k, :d)");
        return $stmt->execute([
            ':k' => $section_key,
            ':d' => $json
        ]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO sections_content (section_key, content_data) VALUES (:k, :d) ON DUPLICATE KEY UPDATE content_data = :d2");
        return $stmt->execute([
            ':k' => $section_key,
            ':d' => $json,
            ':d2' => $json
        ]);
    }
}

/**
 * تنظيف المدخلات النصية
 */
function clean_input($data) {
    if (is_array($data)) {
        return array_map('clean_input', $data);
    }
    $data = trim((string)$data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * توليد رمز حماية CSRF
 */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * حقل CSRF مخفي للاستخدام في النماذج
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

/**
 * التحقق من رمز CSRF
 */
function verify_csrf_token($token) {
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

/**
 * معالجة ورفع الصور بأمان
 */
function upload_image($file_input_name, $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif']) {
    if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'لم يتم تحديد ملف صالح للرفع.'];
    }

    $file = $_FILES[$file_input_name];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_extensions)) {
        return ['success' => false, 'error' => 'نوع الملف غير مسموح به. الامتدادات المدعومة: ' . implode(', ', $allowed_extensions)];
    }

    // التحقق من الحجم (أقصى حد 8 ميجابايت)
    if ($file['size'] > 8 * 1024 * 1024) {
        return ['success' => false, 'error' => 'حجم الملف كبير جداً، الحد الأقصى هو 8 ميجابايت.'];
    }

    // التأكد من وجود مجلد الرفع
    $upload_dir = UPLOAD_DIR;
    if (!is_dir($upload_dir)) {
        @mkdir($upload_dir, 0755, true);
    }

    // توليد اسم فريد للملف
    $new_filename = 'img_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target_path = $upload_dir . DIRECTORY_SEPARATOR . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return [
            'success' => true,
            'path' => 'assets/uploads/' . $new_filename,
            'url' => UPLOAD_URL . '/' . $new_filename
        ];
    } else {
        return ['success' => false, 'error' => 'فشل حفظ الملف على السيرفر. يرجى التحقق من صلاحيات المجلد.'];
    }
}

/**
 * تحويل رقم الهاتف لرابط اتصال tel:
 */
function format_phone_tel($phone) {
    return preg_replace('/[^0-9+]/', '', (string)$phone);
}

/**
 * تحويل رقم الواتساب لرابط دولي صحيح (مثال: 055... إلى 96655...)
 */
function format_whatsapp_link($phone, $message = '') {
    $clean = preg_replace('/[^0-9]/', '', (string)$phone);
    if (strpos($clean, '05') === 0) {
        $clean = '966' . substr($clean, 1);
    } elseif (strpos($clean, '5') === 0 && strlen($clean) == 9) {
        $clean = '966' . $clean;
    }
    $url = "https://wa.me/" . $clean;
    if (!empty($message)) {
        $url .= "?text=" . urlencode($message);
    }
    return $url;
}

/**
 * معالجة رابط الصورة (محلي أو خارجي)
 */
function asset_url($path) {
    if (empty($path)) return '';
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    return SITE_URL . '/' . ltrim($path, '/');
}

/**
 * تحويل كود اللون السداسي Hex إلى RGB لاستخدامه في ألوان التعتيم والشفافية rgba()
 */
function hex2rgb_css($hex) {
    $hex = ltrim((string)$hex, '#');
    if (strlen($hex) === 3) {
        $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
        $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
        $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
    } elseif (strlen($hex) === 6) {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    } else {
        return '139, 90, 43';
    }
    return "$r, $g, $b";
}
