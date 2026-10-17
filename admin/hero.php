<?php
/**
 * إدارة قسم البطل (Hero Section) والعدادات والبطاقات العائمة
 */

require_once __DIR__ . '/includes/admin_header.php';

$hero = get_section('hero', [
    'badge'          => 'الشركة الرائدة الأولى في خدمات التنظيف والتعقيم الشامل بالمنطقة الشرقية',
    'title'          => 'نظافة احترافية بـ<span class="highlight">أعلى معايير الجودة</span><br>وبأحدث تقنيات <span class="highlight">التعقيم والتطهير</span>',
    'desc'           => 'شركة ريمة للنظافة العامة – خبرة عريقة تتجاوز 10 سنوات في تقديم أرقى حلول التنظيف والتعقيم للمنازل والفلل والقصور والشركات في الدمام، القطيف، الخبر، الجبيل، الظهران، وبقيق. نوفر أحدث أجهزة البخار وضخ المياه وتقنيات غسيل السجاد والكنب وتنظيف الخزانات والأرضيات والمكيفات بأيدي كوادر فنية مدربة وبأفضل مواد التنظيف المعتمدة عالمياً.',
    'btn_call'       => 'اتصل واحجز فوراً',
    'btn_wa'         => 'استفسار واتساب سريع',
    'stat_clients'   => '5000',
    'stat_years'     => '12',
    'stat_guarantee' => '100',
    'stat_hours'     => '24/7',
    'card1_title'    => '100% نظافة وتعقيم',
    'card1_sub'      => 'ضمان شامل ومواد آمنة',
    'card2_title'    => 'تقنيات ألمانية متطورة',
    'card2_sub'      => 'أحدث أجهزة البخار والتعقيم',
    'image'          => 'assets/images/1.jpg'
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب (CSRF).';
        header('Location: hero.php');
        exit;
    }

    $hero_data = [
        'badge'          => $_POST['badge'] ?? '',
        'title'          => $_POST['title'] ?? '',
        'desc'           => $_POST['desc'] ?? '',
        'btn_call'       => $_POST['btn_call'] ?? '',
        'btn_wa'         => $_POST['btn_wa'] ?? '',
        'stat_clients'   => $_POST['stat_clients'] ?? '5000',
        'stat_years'     => $_POST['stat_years'] ?? '12',
        'stat_guarantee' => $_POST['stat_guarantee'] ?? '100',
        'stat_hours'     => $_POST['stat_hours'] ?? '24/7',
        'card1_title'    => $_POST['card1_title'] ?? '',
        'card1_sub'      => $_POST['card1_sub'] ?? '',
        'card2_title'    => $_POST['card2_title'] ?? '',
        'card2_sub'      => $_POST['card2_sub'] ?? '',
        'image'          => $hero['image']
    ];

    // رفع صورة البطل إذا تم تحديدها
    if (!empty($_FILES['hero_image_file']['name'])) {
        $upload = upload_image('hero_image_file');
        if ($upload['success']) {
            $hero_data['image'] = $upload['path'];
        }
    } elseif (!empty($_POST['hero_image_url'])) {
        $hero_data['image'] = trim($_POST['hero_image_url']);
    }

    update_section('hero', $hero_data);

    $_SESSION['flash_success'] = 'تم تحديث قسم البطل بنجاح!';
    header('Location: hero.php');
    exit;
}
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">إدارة قسم البطل (Hero Section)</h3>
    <p class="text-muted small mb-0">تعديل العناوين الرئيسية، نصوص الأزرار، العدادات الرقمية، والبطاقات العائمة</p>
  </div>
</div>

<form method="POST" action="hero.php" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <!-- النصوص والعناوين -->
  <div class="content-card">
    <div class="content-card-header">
      <h5><i class="fas fa-heading text-primary"></i> العناوين والنصوص الرئيسية</h5>
    </div>
    <div class="content-card-body">
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">شارة القسم العلوية (Badge)</label>
          <input type="text" name="badge" class="form-control" value="<?= htmlspecialchars($hero['badge'] ?? '') ?>" required />
        </div>
        <div class="col-12">
          <label class="form-label">العنوان الرئيسي (يدعم كود التمييز &lt;span class="highlight"&gt;...&lt;/span&gt;)</label>
          <textarea name="title" class="form-control" rows="2" required><?= htmlspecialchars($hero['title'] ?? '') ?></textarea>
        </div>
        <div class="col-12">
          <label class="form-label">الوصف والفقرة التعريفية</label>
          <textarea name="desc" class="form-control" rows="4" required><?= htmlspecialchars($hero['desc'] ?? '') ?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">نص زر الاتصال الأساسي</label>
          <input type="text" name="btn_call" class="form-control" value="<?= htmlspecialchars($hero['btn_call'] ?? '') ?>" />
        </div>
        <div class="col-md-6">
          <label class="form-label">نص زر محادثة الواتساب</label>
          <input type="text" name="btn_wa" class="form-control" value="<?= htmlspecialchars($hero['btn_wa'] ?? '') ?>" />
        </div>
      </div>
    </div>
  </div>

  <!-- العدادات الرقمية -->
  <div class="content-card">
    <div class="content-card-header">
      <h5><i class="fas fa-sort-numeric-up-alt text-warning"></i> أرقام الإحصائيات الحية (Counters)</h5>
    </div>
    <div class="content-card-body">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">عدد العملاء (مثال: 5000)</label>
          <input type="number" name="stat_clients" class="form-control" value="<?= htmlspecialchars($hero['stat_clients'] ?? '5000') ?>" required />
        </div>
        <div class="col-md-3">
          <label class="form-label">سنوات الخبرة (مثال: 12)</label>
          <input type="number" name="stat_years" class="form-control" value="<?= htmlspecialchars($hero['stat_years'] ?? '12') ?>" required />
        </div>
        <div class="col-md-3">
          <label class="form-label">نسبة الضمان (مثال: 100)</label>
          <input type="number" name="stat_guarantee" class="form-control" value="<?= htmlspecialchars($hero['stat_guarantee'] ?? '100') ?>" required />
        </div>
        <div class="col-md-3">
          <label class="form-label">ساعات العمل (مثال: 24/7)</label>
          <input type="text" name="stat_hours" class="form-control" value="<?= htmlspecialchars($hero['stat_hours'] ?? '24/7') ?>" required />
        </div>
      </div>
    </div>
  </div>

  <!-- صورة البطل والبطاقات العائمة -->
  <div class="content-card">
    <div class="content-card-header">
      <h5><i class="fas fa-image text-success"></i> الصورة الرئيسية والبطاقات العائمة</h5>
    </div>
    <div class="content-card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">رفع صورة جديدة لواجهة الهيرو</label>
          <input type="file" name="hero_image_file" class="form-control img-preview-input" data-preview-target="heroPreview" accept="image/*" />
        </div>
        <div class="col-md-6">
          <label class="form-label">أو رابط الصورة المباشر</label>
          <input type="text" name="hero_image_url" class="form-control" value="<?= htmlspecialchars($hero['image'] ?? '') ?>" />
        </div>
        <div class="col-12 mt-2">
          <span class="d-block small text-muted mb-1">الصورة الحالية:</span>
          <img id="heroPreview" src="<?= htmlspecialchars(asset_url($hero['image'] ?? 'assets/images/1.jpg')) ?>" alt="Hero Preview" style="max-height:140px;border-radius:12px;border:1px solid #ccc;object-fit:cover;" />
        </div>

        <div class="col-12"><hr class="my-2"></div>

        <div class="col-md-6">
          <label class="form-label">عنوان البطاقة العائمة الأولى</label>
          <input type="text" name="card1_title" class="form-control" value="<?= htmlspecialchars($hero['card1_title'] ?? '') ?>" />
          <input type="text" name="card1_sub" class="form-control mt-2" placeholder="النص الفرعي" value="<?= htmlspecialchars($hero['card1_sub'] ?? '') ?>" />
        </div>

        <div class="col-md-6">
          <label class="form-label">عنوان البطاقة العائمة الثانية</label>
          <input type="text" name="card2_title" class="form-control" value="<?= htmlspecialchars($hero['card2_title'] ?? '') ?>" />
          <input type="text" name="card2_sub" class="form-control mt-2" placeholder="النص الفرعي" value="<?= htmlspecialchars($hero['card2_sub'] ?? '') ?>" />
        </div>
      </div>
    </div>
  </div>

  <div class="text-start mb-5">
    <button type="submit" class="btn btn-admin-primary px-5 py-2">
      <i class="fas fa-save me-2"></i> حفظ تعديلات قسم البطل
    </button>
  </div>
</form>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
