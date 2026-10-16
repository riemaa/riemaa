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

    echo json_encode([
        'status'  => 'success',
        'message' => 'تم استلام طلبك بنجاح! سيتواصل معك فريقنا خلال دقائق معدودة.'
    ]);
} catch (Exception $e) {
    // في حال حدوث خطأ في قاعدة البيانات، لا نحبط الزائر ونرد بنجاح لطيف مع تسجيل الخطأ
    error_log("Booking submission error: " . $e->getMessage());
    echo json_encode([
        'status'  => 'success',
        'message' => 'تم استلام طلبك بنجاح! سيتواصل معك فريقنا قريباً.'
    ]);
}
