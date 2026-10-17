<?php
/**
 * إدارة قائمة الهيدر ونصوص الأزرار
 */

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب (CSRF).';
        header('Location: nav.php');
        exit;
    }

    $nav_keys = [
        'nav_about', 'nav_services', 'nav_fleet', 'nav_whyus', 'nav_works', 'nav_packages',
        'nav_areas', 'nav_process', 'nav_guarantees', 'nav_faq', 'nav_contact',
        'nav_call_btn', 'btn_hero_call', 'btn_hero_wa', 'btn_submit_booking'
    ];

    foreach ($nav_keys as $k) {
        if (isset($_POST[$k])) {
            update_setting($k, clean_input($_POST[$k]));
        }
    }

    $_SESSION['flash_success'] = 'تم حفظ مسميات قائمة الهيدر ونصوص الأزرار بنجاح!';
    header('Location: nav.php');
    exit;
}

require_once __DIR__ . '/includes/admin_header.php';

$nav_about          = get_setting('nav_about', 'من نحن');
$nav_services       = get_setting('nav_services', 'خدماتنا');
$nav_fleet          = get_setting('nav_fleet', 'معدات النظافة');
$nav_whyus          = get_setting('nav_whyus', 'لماذا نحن');
$nav_works          = get_setting('nav_works', 'معرض الأعمال');
$nav_packages       = get_setting('nav_packages', 'باقات الأسعار');
$nav_areas          = get_setting('nav_areas', 'مناطق الخدمة');
$nav_process        = get_setting('nav_process', 'خطوات العمل');
$nav_guarantees     = get_setting('nav_guarantees', 'الضمانات');
$nav_faq            = get_setting('nav_faq', 'الأسئلة الشائعة');
$nav_contact        = get_setting('nav_contact', 'تواصل معنا');
$nav_call_btn       = get_setting('nav_call_btn', 'اتصل الآن');
$btn_hero_call      = get_setting('btn_hero_call', 'اتصل واحجز فوراً');
$btn_hero_wa        = get_setting('btn_hero_wa', 'استفسار واتساب سريع');
$btn_submit_booking = get_setting('btn_submit_booking', 'إرسال طلب الحجز والحصول على الخصم');
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);"><i class="fas fa-bars-staggered text-primary me-2"></i> إدارة قائمة الهيدر ونصوص الأزرار</h3>
    <p class="text-muted small mb-0">تحكم كامل في مسميات الروابط العلوية بالموقع وقائمة الموبايل ونصوص أزرار الإجراءات الرئيسية</p>
  </div>
</div>

<form method="POST" action="nav.php">
  <?= csrf_field() ?>

  <!-- مسميات روابط الهيدر -->
  <div class="content-card">
    <div class="content-card-header d-flex align-items-center justify-content-between">
      <h5><i class="fas fa-compass text-primary me-2"></i> روابط شريط التنقل العلوي (Desktop Navbar & Mobile Menu)</h5>
      <span class="badge bg-light text-dark border">11 رابطاً رئيسياً</span>
    </div>
    <div class="content-card-body">
      <p class="text-muted small mb-4">
        تعديل هذه النصوص سينعكس فوراً على القائمة العلوية للهيدر وقائمة الهواتف الذكية الجانبية:
      </p>

      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-info-circle text-warning me-1"></i> رابط من نحن (قصة الشركة)</label>
          <input type="text" name="nav_about" class="form-control" value="<?= htmlspecialchars($nav_about) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-concierge-bell text-warning me-1"></i> رابط الخدمات</label>
          <input type="text" name="nav_services" class="form-control" value="<?= htmlspecialchars($nav_services) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-tools text-warning me-1"></i> رابط معدات وأجهزة النظافة</label>
          <input type="text" name="nav_fleet" class="form-control" value="<?= htmlspecialchars($nav_fleet) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-crown text-warning me-1"></i> رابط لماذا نحن</label>
          <input type="text" name="nav_whyus" class="form-control" value="<?= htmlspecialchars($nav_whyus) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-camera-retro text-warning me-1"></i> رابط معرض الأعمال</label>
          <input type="text" name="nav_works" class="form-control" value="<?= htmlspecialchars($nav_works) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-tags text-warning me-1"></i> رابط باقات الأسعار</label>
          <input type="text" name="nav_packages" class="form-control" value="<?= htmlspecialchars($nav_packages) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-map-marked-alt text-warning me-1"></i> رابط مناطق الخدمة</label>
          <input type="text" name="nav_areas" class="form-control" value="<?= htmlspecialchars($nav_areas) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-cogs text-warning me-1"></i> رابط خطوات العمل</label>
          <input type="text" name="nav_process" class="form-control" value="<?= htmlspecialchars($nav_process) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-shield-alt text-warning me-1"></i> رابط الضمانات</label>
          <input type="text" name="nav_guarantees" class="form-control" value="<?= htmlspecialchars($nav_guarantees) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-question-circle text-warning me-1"></i> رابط الأسئلة الشائعة</label>
          <input type="text" name="nav_faq" class="form-control" value="<?= htmlspecialchars($nav_faq) ?>" required />
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold"><i class="fas fa-headset text-warning me-1"></i> رابط تواصل معنا</label>
          <input type="text" name="nav_contact" class="form-control" value="<?= htmlspecialchars($nav_contact) ?>" required />
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold"><i class="fas fa-phone-volume text-success me-1"></i> نص زر الاتصال البارز في الهيدر</label>
          <input type="text" name="nav_call_btn" class="form-control" value="<?= htmlspecialchars($nav_call_btn) ?>" placeholder="اتصل الآن أو رقم الهاتف" required />
        </div>
      </div>
    </div>
  </div>

  <!-- نصوص أزرار الإجراءات الرئيسية بالموقع -->
  <div class="content-card">
    <div class="content-card-header">
      <h5><i class="fas fa-mouse-pointer text-primary me-2"></i> نصوص أزرار الإجراءات والتفاعل (Call to Action Buttons)</h5>
    </div>
    <div class="content-card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-phone-alt text-primary me-1"></i> زر الاتصال في واجهة الهيرو الرئيسية</label>
          <input type="text" name="btn_hero_call" class="form-control" value="<?= htmlspecialchars($btn_hero_call) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fab fa-whatsapp text-success me-1"></i> زر الواتساب في واجهة الهيرو الرئيسية</label>
          <input type="text" name="btn_hero_wa" class="form-control" value="<?= htmlspecialchars($btn_hero_wa) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-paper-plane text-warning me-1"></i> نص زر إرسال نموذج الحجز</label>
          <input type="text" name="btn_submit_booking" class="form-control" value="<?= htmlspecialchars($btn_submit_booking) ?>" required />
        </div>
      </div>
    </div>
  </div>

  <div class="text-start mb-5">
    <button type="submit" class="btn btn-admin-primary px-5 py-2">
      <i class="fas fa-save me-2"></i> حفظ كافة المسميات والنصوص الآن
    </button>
  </div>
</form>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
