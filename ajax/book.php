<?php
/**
 * معالج استقبال طلبات الحجز من نموذج التواصل
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'طريقة الطلب غير مسموح بها.']);
    exit;
}

$client_name  = isset($_POST['client_name']) ? clean_input($_POST['client_name']) : '';
$phone        = isset($_POST['phone']) ? clean_input($_POST['phone']) : '';
$service_type = isset($_POST['service_type']) ? clean_input($_POST['service_type']) : '';
$moving_date  = isset($_POST['moving_date']) && !empty($_POST['moving_date']) ? clean_input($_POST['moving_date']) : null;
$from_area    = isset($_POST['from_area']) ? clean_input($_POST['from_area']) : '';
$to_area      = isset($_POST['to_area']) ? clean_input($_POST['to_area']) : '';
$details      = isset($_POST['details']) ? clean_input($_POST['details']) : '';

if (empty($client_name) || empty($phone)) {
    echo json_encode(['status' => 'error', 'message' => 'يرجى إدخال الاسم ورقم الجوال.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO bookings (client_name, phone, service_type, moving_date, from_area, to_area, details, status)
        VALUES (:name, :phone, :service, :date, :from_a, :to_a, :details, 'new')
    ");
    $stmt->execute([
        ':name'    => $client_name,
        ':phone'   => $phone,
        ':service' => $service_type,
        ':date'    => $moving_date,
        ':from_a'  => $from_area,
        ':to_a'    => $to_area,
        ':details' => $details
    ]);

    // تجهيز رسالة الواتساب المنسقة بالتفاصيل
    $whatsapp_num = get_setting('whatsapp_number', '966554091835');
    $site_name    = get_setting('site_name', 'شركة ريمة للنظافة العامة');

    $wa_text = "👋 مرحباً *{$site_name}*،\n";
    $wa_text .= "أود تأكيد طلب حجز خدمة نظافة عبر الموقع:\n\n";
    $wa_text .= "👤 *الاسم:* " . $client_name . "\n";
    $wa_text .= "📱 *الجوال:* " . $phone . "\n";
    if (!empty($service_type)) {
        $wa_text .= "✨ *الخدمة المطلوبة:* " . $service_type . "\n";
    }
    if (!empty($from_area)) {
        $wa_text .= "📍 *المدينة / الحي:* " . $from_area . "\n";
    }
    if (!empty($to_area)) {
        $wa_text .= "🏢 *نوع العقار / العنوان:* " . $to_area . "\n";
    }
    if (!empty($moving_date)) {
        $wa_text .= "📅 *تاريخ وموعد الخدمة:* " . $moving_date . "\n";
    }
    if (!empty($details)) {
        $wa_text .= "📝 *ملاحظات وتفاصيل:* " . $details . "\n";
    }
    $wa_text .= "\nيرجى التواصل معي للتأكيد وتقديم أفضل عرض سعر. وشكراً!";

    $wa_url = format_whatsapp_link($whatsapp_num, $wa_text);

    echo json_encode([
        'status'       => 'success',
        'message'      => 'تم تسجيل طلبك بنجاح! جاري تحويلك الآن للواتساب لتأكيد الحجز فوراً...',
        'whatsapp_url' => $wa_url
    ]);
} catch (Exception $e) {
    error_log("Booking submission error: " . $e->getMessage());
    $whatsapp_num = get_setting('whatsapp_number', '966554091835');
    $wa_text = "مرحباً، أود الاستفسار وحجز خدمة نظافة - الاسم: {$client_name} - الجوال: {$phone}";
    $wa_url = format_whatsapp_link($whatsapp_num, $wa_text);

    echo json_encode([
        'status'       => 'success',
        'message'      => 'تم تسجيل طلبك! جاري تحويلك إلى واتساب...',
        'whatsapp_url' => $wa_url
    ]);
}
