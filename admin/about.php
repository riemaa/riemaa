<?php
/**
 * إدارة قسم من نحن وقصة الريادة
 */

require_once __DIR__ . '/includes/admin_header.php';

$about = get_section('about', [
    'tag'          => 'نبذة عن الشركة',
    'title'        => 'من نحن - قصة الريادة في نقل الأثاث',
    'subtitle'     => 'نضع بين يديك خبرة متوارثة واحترافية مطلقة تجعل انتقالك لمنزلك الجديد تجربة مريحة وسلسة دون أي عناء',
    'story1_title' => 'رؤيتنا: الأمان التام والالتزام بأعلى المعايير العالمية',
    'story1_p1'    => 'تأسست شركة الأوائل لنقل العفش بالدمام لتكون النموذج الأرقى...',
    'story1_p2'    => 'نحن نوفر كادراً وظيفياً متخصصاً...',
    'story1_img'   => 'assets/images/2.jpg',
    'story2_title' => 'فريق نجارين وفنيين محترفين لكل نوع أثاث',
    'story2_p1'    => 'لا نعتمد في الأوائل على العمالة العشوائية...',
    'story2_p2'    => 'يتم ترقيم كافة القطع والمفاصل...',
    'story2_img'   => 'assets/images/3.jpg'
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب (CSRF).';
        header('Location: about.php');
        exit;
    }

    $about_data = [
        'tag'          => $_POST['tag'] ?? '',
        'title'        => $_POST['title'] ?? '',
        'subtitle'     => $_POST['subtitle'] ?? '',
        'story1_title' => $_POST['story1_title'] ?? '',
        'story1_p1'    => $_POST['story1_p1'] ?? '',
        'story1_p2'    => $_POST['story1_p2'] ?? '',
        'story1_img'   => $about['story1_img'] ?? 'assets/images/2.jpg',
        'story2_title' => $_POST['story2_title'] ?? '',
        'story2_p1'    => $_POST['story2_p1'] ?? '',
        'story2_p2'    => $_POST['story2_p2'] ?? '',
        'story2_img'   => $about['story2_img'] ?? 'assets/images/3.jpg'
    ];

    // صورة القصة الأولى
    if (!empty($_FILES['story1_file']['name'])) {
        $u1 = upload_image('story1_file');
        if ($u1['success']) $about_data['story1_img'] = $u1['path'];
    } elseif (!empty($_POST['story1_url'])) {
        $about_data['story1_img'] = trim($_POST['story1_url']);
    }

    // صورة القصة الثانية
    if (!empty($_FILES['story2_file']['name'])) {
        $u2 = upload_image('story2_file');
        if ($u2['success']) $about_data['story2_img'] = $u2['path'];
    } elseif (!empty($_POST['story2_url'])) {
        $about_data['story2_img'] = trim($_POST['story2_url']);
    }

    update_section('about', $about_data);

    $_SESSION['flash_success'] = 'تم تحديث قسم من نحن بنجاح!';
    header('Location: about.php');
    exit;
}
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">إدارة قسم من نحن وقصة الريادة</h3>
    <p class="text-muted small mb-0">تعديل نصوص الرؤية وخبرة الشركة وصور فريق العمل</p>
  </div>
</div>

<form method="POST" action="about.php" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="content-card">
    <div class="content-card-header">
      <h5><i class="fas fa-landmark text-primary"></i> العناوين الرئيسية للقسم</h5>
    </div>
    <div class="content-card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">الشارة العلوية (Badge Tag)</label>
          <input type="text" name="tag" class="form-control" value="<?= htmlspecialchars($about['tag'] ?? '') ?>" required />
        </div>
        <div class="col-md-8">
          <label class="form-label">العنوان الرئيسي للقسم</label>
          <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($about['title'] ?? '') ?>" required />
        </div>
        <div class="col-12">
          <label class="form-label">الوصف التمهيدي الفرعي</label>
          <textarea name="subtitle" class="form-control" rows="2"><?= htmlspecialchars($about['subtitle'] ?? '') ?></textarea>
        </div>
      </div>
    </div>
  </div>

  <!-- القصة الأولى (الرؤية) -->
  <div class="content-card">
    <div class="content-card-header">
      <h5><i class="fas fa-shield-alt text-success"></i> الفقرة الأولى (رؤيتنا ومعاييرنا)</h5>
    </div>
    <div class="content-card-body">
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">عنوان الفقرة الأولى</label>
          <input type="text" name="story1_title" class="form-control" value="<?= htmlspecialchars($about['story1_title'] ?? '') ?>" required />
        </div>
        <div class="col-md-6">
          <label class="form-label">النص الأول</label>
          <textarea name="story1_p1" class="form-control" rows="5" required><?= htmlspecialchars($about['story1_p1'] ?? '') ?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">النص الثاني الإضافي</label>
          <textarea name="story1_p2" class="form-control" rows="5"><?= htmlspecialchars($about['story1_p2'] ?? '') ?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">رفع صورة الفقرة الأولى</label>
          <input type="file" name="story1_file" class="form-control img-preview-input" data-preview-target="story1Preview" accept="image/*" />
        </div>
        <div class="col-md-6">
          <label class="form-label">أو رابط الصورة المباشر</label>
          <input type="text" name="story1_url" class="form-control" value="<?= htmlspecialchars($about['story1_img'] ?? '') ?>" />
        </div>
        <div class="col-12">
          <img id="story1Preview" src="<?= htmlspecialchars(asset_url($about['story1_img'] ?? 'assets/images/2.jpg')) ?>" alt="Story 1" style="max-height:120px;border-radius:12px;border:1px solid #ccc;object-fit:cover;" />
        </div>
      </div>
    </div>
  </div>

  <!-- القصة الثانية (فريق العمل) -->
  <div class="content-card">
    <div class="content-card-header">
      <h5><i class="fas fa-users-cog text-warning"></i> الفقرة الثانية (فريق النجارين والفنيين)</h5>
    </div>
    <div class="content-card-body">
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">عنوان الفقرة الثانية</label>
          <input type="text" name="story2_title" class="form-control" value="<?= htmlspecialchars($about['story2_title'] ?? '') ?>" required />
        </div>
        <div class="col-md-6">
          <label class="form-label">النص الأول</label>
          <textarea name="story2_p1" class="form-control" rows="5" required><?= htmlspecialchars($about['story2_p1'] ?? '') ?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">النص الثاني الإضافي</label>
          <textarea name="story2_p2" class="form-control" rows="5"><?= htmlspecialchars($about['story2_p2'] ?? '') ?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">رفع صورة الفقرة الثانية</label>
          <input type="file" name="story2_file" class="form-control img-preview-input" data-preview-target="story2Preview" accept="image/*" />
        </div>
        <div class="col-md-6">
          <label class="form-label">أو رابط الصورة المباشر</label>
          <input type="text" name="story2_url" class="form-control" value="<?= htmlspecialchars($about['story2_img'] ?? '') ?>" />
        </div>
        <div class="col-12">
          <img id="story2Preview" src="<?= htmlspecialchars(asset_url($about['story2_img'] ?? 'assets/images/3.jpg')) ?>" alt="Story 2" style="max-height:120px;border-radius:12px;border:1px solid #ccc;object-fit:cover;" />
        </div>
      </div>
    </div>
  </div>

  <div class="text-start mb-5">
    <button type="submit" class="btn btn-admin-primary px-5 py-2">
      <i class="fas fa-save me-2"></i> حفظ تعديلات قسم من نحن
    </button>
  </div>
</form>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
