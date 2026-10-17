<?php
/**
 * صفحة الإعدادات العامة وإعدادات السيو والشعار
 */

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب (CSRF).';
        header('Location: settings.php');
        exit;
    }

    $keys = [
        'site_name', 'site_short_name', 'site_tagline', 'site_icon',
        'phone_primary', 'phone_secondary', 'whatsapp_number', 'email',
        'address', 'working_hours', 'discount_percentage',
        'meta_title', 'meta_description', 'meta_keywords', 'meta_author',
        'meta_robots', 'canonical_url', 'header_scripts', 'footer_scripts',
        'theme_primary_color', 'theme_secondary_color', 'theme_accent_color',
        'nav_services', 'nav_fleet', 'nav_whyus', 'nav_works', 'nav_packages',
        'nav_areas', 'nav_process', 'nav_guarantees', 'nav_faq', 'nav_contact', 'nav_call_btn'
    ];

    foreach ($keys as $k) {
        if (isset($_POST[$k])) {
            update_setting($k, $_POST[$k]);
        }
    }

    // معالجة رفع الشعار
    if (!empty($_FILES['logo_file']['name'])) {
        $upload = upload_image('logo_file');
        if ($upload['success']) {
            update_setting('site_logo', $upload['path']);
        } else {
            $_SESSION['flash_error'] = 'حدث خطأ في رفع الشعار: ' . $upload['error'];
        }
    } elseif (isset($_POST['site_logo_url']) && !empty($_POST['site_logo_url'])) {
        update_setting('site_logo', trim($_POST['site_logo_url']));
    }

    $_SESSION['flash_success'] = 'تم حفظ جميع الإعدادات بنجاح!';
    header('Location: settings.php');
    exit;
}

require_once __DIR__ . '/includes/admin_header.php';

$site_name           = get_setting('site_name');
$site_short_name     = get_setting('site_short_name');
$site_tagline        = get_setting('site_tagline');
$site_logo           = get_setting('site_logo');
$site_icon           = get_setting('site_icon', 'fas fa-truck-moving');
$phone_primary       = get_setting('phone_primary');
$phone_secondary     = get_setting('phone_secondary');
$whatsapp_number     = get_setting('whatsapp_number');
$email               = get_setting('email');
$address             = get_setting('address');
$working_hours       = get_setting('working_hours');
$discount_percentage = get_setting('discount_percentage', '20%');
$meta_title          = get_setting('meta_title');
$meta_description    = get_setting('meta_description');
$meta_keywords       = get_setting('meta_keywords');
$meta_author         = get_setting('meta_author');
$meta_robots         = get_setting('meta_robots', 'index, follow');
$canonical_url       = get_setting('canonical_url');
$header_scripts      = get_setting('header_scripts');
$footer_scripts      = get_setting('footer_scripts');

$theme_primary_color   = get_setting('theme_primary_color', '#8B5A2B');
$theme_secondary_color = get_setting('theme_secondary_color', '#341D13');
$theme_accent_color    = get_setting('theme_accent_color', '#D4A373');

$nav_services   = get_setting('nav_services', 'خدماتنا');
$nav_fleet      = get_setting('nav_fleet', 'الأسطول والمعدات');
$nav_whyus      = get_setting('nav_whyus', 'لماذا نحن');
$nav_works      = get_setting('nav_works', 'معرض الأعمال');
$nav_packages   = get_setting('nav_packages', 'باقات الأسعار');
$nav_areas      = get_setting('nav_areas', 'مناطق الخدمة');
$nav_process    = get_setting('nav_process', 'خطوات العمل');
$nav_guarantees = get_setting('nav_guarantees', 'الضمانات');
$nav_faq        = get_setting('nav_faq', 'الأسئلة الشائعة');
$nav_contact    = get_setting('nav_contact', 'تواصل معنا');
$nav_call_btn   = get_setting('nav_call_btn', 'اتصل الآن');
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">الإعدادات العامة والسيو</h3>
    <p class="text-muted small mb-0">تحكم كامل في هوية الموقع والشعار وأرقام الاتصال وبيانات محركات البحث</p>
  </div>
</div>

<form method="POST" action="settings.php" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <!-- هوية الموقع والشعار -->
  <div class="content-card">
    <div class="content-card-header">
      <h5><i class="fas fa-id-card text-primary"></i> هوية الموقع والشعار</h5>
    </div>
    <div class="content-card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">اسم الموقع الكامل</label>
          <input type="text" name="site_name" class="form-control" value="<?= htmlspecialchars($site_name) ?>" required />
        </div>
        <div class="col-md-3">
          <label class="form-label">الاسم المختصر (النافبار)</label>
          <input type="text" name="site_short_name" class="form-control" value="<?= htmlspecialchars($site_short_name) ?>" required />
        </div>
        <div class="col-md-3">
          <label class="form-label">أيقونة الشعار الافتراضية (FontAwesome)</label>
          <input type="text" name="site_icon" class="form-control" value="<?= htmlspecialchars($site_icon) ?>" placeholder="fas fa-truck-moving" />
        </div>
        <div class="col-md-12">
          <label class="form-label">الوصف المختصر (سلوجان الموقع)</label>
          <input type="text" name="site_tagline" class="form-control" value="<?= htmlspecialchars($site_tagline) ?>" />
        </div>
        <div class="col-md-6">
          <label class="form-label">رفع شعار مخصص (صورة)</label>
          <input type="file" name="logo_file" class="form-control img-preview-input" data-preview-target="logoPreview" accept="image/*" />
          <small class="text-muted">يدعم PNG, JPG, WEBP, SVG</small>
        </div>
        <div class="col-md-6">
          <label class="form-label">أو رابط صورة الشعار مباشرة (للنشر السحابي على Vercel)</label>
          <input type="text" name="site_logo_url" class="form-control" value="<?= htmlspecialchars($site_logo) ?>" placeholder="https://..." />
        </div>
        <?php if (!empty($site_logo)): ?>
        <div class="col-12 mt-2">
          <span class="d-block small text-muted mb-1">الشعار الحالي:</span>
          <img id="logoPreview" src="<?= htmlspecialchars(asset_url($site_logo)) ?>" alt="Logo Preview" style="max-height:60px;border-radius:10px;border:1px solid #ccc;padding:4px;background:#fff;" />
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- ألوان وهوية المشروع بالكامل -->
  <div class="content-card">
    <div class="content-card-header">
      <h5><i class="fas fa-palette text-primary"></i> ألوان وتصميم المشروع بالكامل (Theme Colors)</h5>
    </div>
    <div class="content-card-body">
      <p class="text-muted small mb-4">
        يمكنك تغيير لون الموقع بالكامل بنقرة واحدة ليتم تطبيقه فوراً على الأزرار، التدرجات، الأيقونات، البطاقات، وهوية الموقع:
      </p>

      <div class="row g-4 align-items-center">
        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-paint-brush me-1 text-primary"></i> اللون الأساسي (Primary Color)</label>
          <div class="d-flex align-items-center gap-2">
            <input type="color" id="primaryColorInput" name="theme_primary_color" class="form-control form-control-color" value="<?= htmlspecialchars($theme_primary_color) ?>" title="اختر اللون الأساسي" style="width:60px;height:45px;cursor:pointer;" oninput="document.getElementById('primaryColorText').value=this.value;">
            <input type="text" id="primaryColorText" class="form-control font-monospace" value="<?= htmlspecialchars($theme_primary_color) ?>" oninput="document.getElementById('primaryColorInput').value=this.value;">
          </div>
          <small class="text-muted">يُستخدم في الأزرار الرئيسية، التمييز، والأيقونات البارزة</small>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-fill-drip me-1 text-secondary"></i> اللون الثانوي / الداكن (Secondary Color)</label>
          <div class="d-flex align-items-center gap-2">
            <input type="color" id="secondaryColorInput" name="theme_secondary_color" class="form-control form-control-color" value="<?= htmlspecialchars($theme_secondary_color) ?>" title="اختر اللون الثانوي" style="width:60px;height:45px;cursor:pointer;" oninput="document.getElementById('secondaryColorText').value=this.value;">
            <input type="text" id="secondaryColorText" class="form-control font-monospace" value="<?= htmlspecialchars($theme_secondary_color) ?>" oninput="document.getElementById('secondaryColorInput').value=this.value;">
          </div>
          <small class="text-muted">يُستخدم في خلفيات الهيدر، الفوتر، والبطاقات الداكنة</small>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold"><i class="fas fa-magic me-1 text-warning"></i> لون التمييز واللمعان (Accent Color)</label>
          <div class="d-flex align-items-center gap-2">
            <input type="color" id="accentColorInput" name="theme_accent_color" class="form-control form-control-color" value="<?= htmlspecialchars($theme_accent_color) ?>" title="اختر لون التمييز" style="width:60px;height:45px;cursor:pointer;" oninput="document.getElementById('accentColorText').value=this.value;">
            <input type="text" id="accentColorText" class="form-control font-monospace" value="<?= htmlspecialchars($theme_accent_color) ?>" oninput="document.getElementById('accentColorInput').value=this.value;">
          </div>
          <small class="text-muted">يُستخدم في الشارات، النجوم، والحدود اللامعة</small>
        </div>
      </div>

      <hr class="my-4" style="opacity:0.1;">

      <div class="d-flex flex-wrap align-items-center gap-2">
        <span class="small fw-bold text-dark me-2"><i class="fas fa-swatchbook me-1"></i> باقات ألوان جاهزة ومقترحة:</span>
        <button type="button" class="btn btn-sm btn-light border" onclick="applyPreset('#8B5A2B', '#341D13', '#D4A373')">
          <span style="display:inline-block;width:12px;height:12px;background:#8B5A2B;border-radius:50%;margin-left:4px;"></span> خشب كلاسيكي (الأصل)
        </button>
        <button type="button" class="btn btn-sm btn-light border" onclick="applyPreset('#1E40AF', '#0F172A', '#60A5FA')">
          <span style="display:inline-block;width:12px;height:12px;background:#1E40AF;border-radius:50%;margin-left:4px;"></span> أزرق كحلي ملكي
        </button>
        <button type="button" class="btn btn-sm btn-light border" onclick="applyPreset('#047857', '#064E3B', '#34D399')">
          <span style="display:inline-block;width:12px;height:12px;background:#047857;border-radius:50%;margin-left:4px;"></span> أخضر زمردي فخم
        </button>
        <button type="button" class="btn btn-sm btn-light border" onclick="applyPreset('#B45309', '#451A03', '#FBBF24')">
          <span style="display:inline-block;width:12px;height:12px;background:#B45309;border-radius:50%;margin-left:4px;"></span> برونزي ذهبي أنيق
        </button>
        <button type="button" class="btn btn-sm btn-light border" onclick="applyPreset('#374151', '#111827', '#9CA3AF')">
          <span style="display:inline-block;width:12px;height:12px;background:#374151;border-radius:50%;margin-left:4px;"></span> رمادي داكن فاحم
        </button>
      </div>
    </div>
  </div>

  <!-- تخصيص مسميات نصوص القائمة العلوية (الهيدر) -->
  <div class="content-card">
    <div class="content-card-header">
      <h5><i class="fas fa-bars-staggered text-primary"></i> تخصيص مسميات نصوص القائمة العلوية (الهيدر)</h5>
    </div>
    <div class="content-card-body">
      <p class="text-muted small mb-4">
        تحكم في النصوص المعروضة في شريط التنقل العلوي للموقع وقائمة الموبايل الجانبية:
      </p>

      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">رابط الخدمات</label>
          <input type="text" name="nav_services" class="form-control" value="<?= htmlspecialchars($nav_services) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">رابط الأسطول والمعدات</label>
          <input type="text" name="nav_fleet" class="form-control" value="<?= htmlspecialchars($nav_fleet) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">رابط لماذا نحن</label>
          <input type="text" name="nav_whyus" class="form-control" value="<?= htmlspecialchars($nav_whyus) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">رابط معرض الأعمال</label>
          <input type="text" name="nav_works" class="form-control" value="<?= htmlspecialchars($nav_works) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">رابط باقات الأسعار</label>
          <input type="text" name="nav_packages" class="form-control" value="<?= htmlspecialchars($nav_packages) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">رابط مناطق الخدمة</label>
          <input type="text" name="nav_areas" class="form-control" value="<?= htmlspecialchars($nav_areas) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">رابط خطوات العمل</label>
          <input type="text" name="nav_process" class="form-control" value="<?= htmlspecialchars($nav_process) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">رابط الضمانات</label>
          <input type="text" name="nav_guarantees" class="form-control" value="<?= htmlspecialchars($nav_guarantees) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">رابط الأسئلة الشائعة</label>
          <input type="text" name="nav_faq" class="form-control" value="<?= htmlspecialchars($nav_faq) ?>" required />
        </div>
        <div class="col-md-6">
          <label class="form-label">رابط تواصل معنا</label>
          <input type="text" name="nav_contact" class="form-control" value="<?= htmlspecialchars($nav_contact) ?>" required />
        </div>
        <div class="col-md-6">
          <label class="form-label">نص زر الاتصال في الهيدر</label>
          <input type="text" name="nav_call_btn" class="form-control" value="<?= htmlspecialchars($nav_call_btn) ?>" placeholder="اتصل الآن أو رقم الهاتف" />
        </div>
      </div>
    </div>
  </div>

  <!-- أرقام الاتصال والواتساب -->
  <div class="content-card">
    <div class="content-card-header">
      <h5><i class="fas fa-phone-volume text-success"></i> أرقام الهواتف والواتساب والعنوان</h5>
    </div>
    <div class="content-card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">رقم الهاتف الأساسي (اتصال مباشر)</label>
          <input type="text" name="phone_primary" class="form-control" value="<?= htmlspecialchars($phone_primary) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">رقم الهاتف الثانوي (متاح 24 ساعة)</label>
          <input type="text" name="phone_secondary" class="form-control" value="<?= htmlspecialchars($phone_secondary) ?>" />
        </div>
        <div class="col-md-4">
          <label class="form-label">رقم الواتساب (بالصيغة الدولية أو المحلية)</label>
          <input type="text" name="whatsapp_number" class="form-control" value="<?= htmlspecialchars($whatsapp_number) ?>" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">البريد الإلكتروني الرسمي</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" />
        </div>
        <div class="col-md-4">
          <label class="form-label">ساعات وأوقات العمل</label>
          <input type="text" name="working_hours" class="form-control" value="<?= htmlspecialchars($working_hours) ?>" />
        </div>
        <div class="col-md-4">
          <label class="form-label">نسبة خصم الحجز المعروضة</label>
          <input type="text" name="discount_percentage" class="form-control" value="<?= htmlspecialchars($discount_percentage) ?>" placeholder="20%" />
        </div>
        <div class="col-12">
          <label class="form-label">العنوان والتغطية الجغرافية</label>
          <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($address) ?>" />
        </div>
      </div>
    </div>
  </div>

  <!-- إعدادات الأرشفة ومحركات البحث (SEO) -->
  <div class="content-card">
    <div class="content-card-header">
      <h5><i class="fas fa-search text-warning"></i> إعدادات الأرشفة ومحركات البحث (SEO Meta Tags)</h5>
    </div>
    <div class="content-card-body">
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">عنوان الصفحة في محركات البحث (Meta Title)</label>
          <input type="text" name="meta_title" class="form-control" value="<?= htmlspecialchars($meta_title) ?>" required />
          <small class="text-muted">العنوان الذي يظهر في نتائج بحث جوجل وعلامة تبويب المتصفح</small>
        </div>
        <div class="col-12">
          <label class="form-label">وصف الموقع لمحركات البحث (Meta Description)</label>
          <textarea name="meta_description" class="form-control" rows="3" required><?= htmlspecialchars($meta_description) ?></textarea>
          <small class="text-muted">المقتطف النصي الذي يظهر أسفل العنوان في بحث جوجل</small>
        </div>
        <div class="col-12">
          <label class="form-label">الكلمات المفتاحية (Meta Keywords مفصولة بفواصل)</label>
          <textarea name="meta_keywords" class="form-control" rows="2"><?= htmlspecialchars($meta_keywords) ?></textarea>
        </div>
        <div class="col-md-4">
          <label class="form-label">الكاتب / المالك (Meta Author)</label>
          <input type="text" name="meta_author" class="form-control" value="<?= htmlspecialchars($meta_author) ?>" />
        </div>
        <div class="col-md-4">
          <label class="form-label">توجيه عناكب البحث (Meta Robots)</label>
          <select name="meta_robots" class="form-select">
            <option value="index, follow" <?= $meta_robots === 'index, follow' ? 'selected' : '' ?>>index, follow (السماح بالأرشفة)</option>
            <option value="noindex, follow" <?= $meta_robots === 'noindex, follow' ? 'selected' : '' ?>>noindex, follow</option>
            <option value="noindex, nofollow" <?= $meta_robots === 'noindex, nofollow' ? 'selected' : '' ?>>noindex, nofollow</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">الرابط الثابت (Canonical URL)</label>
          <input type="url" name="canonical_url" class="form-control" value="<?= htmlspecialchars($canonical_url) ?>" placeholder="https://..." />
        </div>
        <div class="col-md-6">
          <label class="form-label">أكواد التتبع في الهيدر (Header Scripts / Google Analytics / Pixel)</label>
          <textarea name="header_scripts" class="form-control" rows="4" style="direction:ltr;font-family:monospace;"><?= htmlspecialchars($header_scripts) ?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">أكواد التتبع في الفوتر (Footer Scripts / Live Chat Widgets)</label>
          <textarea name="footer_scripts" class="form-control" rows="4" style="direction:ltr;font-family:monospace;"><?= htmlspecialchars($footer_scripts) ?></textarea>
        </div>
      </div>
    </div>
  </div>

  <div class="text-start mb-5">
    <button type="submit" class="btn btn-admin-primary px-5 py-2">
      <i class="fas fa-save me-2"></i> حفظ كافة التعديلات الآن
    </button>
  </div>
</form>

<script>
function applyPreset(primary, secondary, accent) {
  const pIn = document.getElementById('primaryColorInput');
  const pTx = document.getElementById('primaryColorText');
  const sIn = document.getElementById('secondaryColorInput');
  const sTx = document.getElementById('secondaryColorText');
  const aIn = document.getElementById('accentColorInput');
  const aTx = document.getElementById('accentColorText');
  if (pIn) pIn.value = primary;
  if (pTx) pTx.value = primary;
  if (sIn) sIn.value = secondary;
  if (sTx) sTx.value = secondary;
  if (aIn) aIn.value = accent;
  if (aTx) aTx.value = accent;
}
</script>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
