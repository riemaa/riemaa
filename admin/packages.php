<?php
/**
 * إدارة باقات وعروض الأسعار
 */

require_once __DIR__ . '/includes/admin_header.php';

// حذف
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM packages WHERE id = :id");
    $stmt->execute([':id' => $del_id]);
    $_SESSION['flash_success'] = 'تم حذف الباقة بنجاح.';
    header('Location: packages.php');
    exit;
}

// تعديل
$edit_pkg = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM packages WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['edit_id']]);
    $edit_pkg = $stmt->fetch();
}

// حفظ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب.';
        header('Location: packages.php');
        exit;
    }

    $title       = clean_input($_POST['title'] ?? '');
    $subtitle    = clean_input($_POST['subtitle'] ?? '');
    $price_badge = clean_input($_POST['price_badge'] ?? '');
    $price_text  = clean_input($_POST['price_text'] ?? '');
    $features    = trim($_POST['features'] ?? '');
    $btn_text    = clean_input($_POST['btn_text'] ?? 'اطلب الباقة الآن');
    $btn_link    = clean_input($_POST['btn_link'] ?? 'tel:0554091835');
    $is_popular  = isset($_POST['is_popular']) ? 1 : 0;
    $sort_order  = (int)($_POST['sort_order'] ?? 0);
    $package_id  = (int)($_POST['package_id'] ?? 0);

    if (empty($title)) {
        $_SESSION['flash_error'] = 'يرجى إدخال اسم الباقة.';
    } else {
        if ($package_id > 0) {
            $stmt = $pdo->prepare("UPDATE packages SET title = :t, subtitle = :sub, price_badge = :pb, price_text = :pt, features = :f, btn_text = :bt, btn_link = :bl, is_popular = :pop, sort_order = :s WHERE id = :id");
            $stmt->execute([
                ':t' => $title, ':sub' => $subtitle, ':pb' => $price_badge, ':pt' => $price_text,
                ':f' => $features, ':bt' => $btn_text, ':bl' => $btn_link, ':pop' => $is_popular,
                ':s' => $sort_order, ':id' => $package_id
            ]);
            $_SESSION['flash_success'] = 'تم تحديث الباقة بنجاح!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO packages (title, subtitle, price_badge, price_text, features, btn_text, btn_link, is_popular, sort_order) VALUES (:t, :sub, :pb, :pt, :f, :bt, :bl, :pop, :s)");
            $stmt->execute([
                ':t' => $title, ':sub' => $subtitle, ':pb' => $price_badge, ':pt' => $price_text,
                ':f' => $features, ':bt' => $btn_text, ':bl' => $btn_link, ':pop' => $is_popular,
                ':s' => $sort_order
            ]);
            $_SESSION['flash_success'] = 'تمت إضافة الباقة بنجاح!';
        }
        header('Location: packages.php');
        exit;
    }
}

$packages = $pdo->query("SELECT * FROM packages ORDER BY sort_order ASC, id ASC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">إدارة باقات وعروض الأسعار</h3>
    <p class="text-muted small mb-0">تحديد أسعار الباقات، ميزات كل باقة، وتمييز الباقة الأكثر طلباً</p>
  </div>
  <?php if ($edit_pkg): ?>
    <a href="packages.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
      <i class="fas fa-plus me-1"></i> إضافة باقة جديدة
    </a>
  <?php endif; ?>
</div>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="content-card">
      <div class="content-card-header">
        <h5>
          <i class="fas <?= $edit_pkg ? 'fa-edit text-warning' : 'fa-plus-circle text-primary' ?>"></i>
          <?= $edit_pkg ? 'تعديل الباقة' : 'إضافة باقة جديدة' ?>
        </h5>
      </div>
      <div class="content-card-body">
        <form method="POST" action="packages.php">
          <?= csrf_field() ?>
          <input type="hidden" name="package_id" value="<?= $edit_pkg['id'] ?? 0 ?>">

          <div class="mb-3">
            <label class="form-label">اسم الباقة</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($edit_pkg['title'] ?? '') ?>" required placeholder="مثال: باقة الفلل والقصور VIP" />
          </div>

          <div class="mb-3">
            <label class="form-label">الوصف الفرعي</label>
            <input type="text" name="subtitle" class="form-control" value="<?= htmlspecialchars($edit_pkg['subtitle'] ?? '') ?>" placeholder="مثالية للشقق..." />
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">شارة السعر (الشريط)</label>
              <input type="text" name="price_badge" class="form-control" value="<?= htmlspecialchars($edit_pkg['price_badge'] ?? 'باقة اقتصادية') ?>" />
            </div>
            <div class="col-6">
              <label class="form-label">نص السعر</label>
              <input type="text" name="price_text" class="form-control" value="<?= htmlspecialchars($edit_pkg['price_text'] ?? 'حسب الاتفاق') ?>" required />
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">مميزات الباقة (كل ميزة في سطر منفصل)</label>
            <textarea name="features" class="form-control" rows="5" required placeholder="ديانة نقل حديثة ومقفلة&#10;طاقم نجارين&#10;تغليف نايلون"><?= htmlspecialchars($edit_pkg['features'] ?? '') ?></textarea>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">نص الزر</label>
              <input type="text" name="btn_text" class="form-control" value="<?= htmlspecialchars($edit_pkg['btn_text'] ?? 'اطلب الباقة الآن') ?>" />
            </div>
            <div class="col-6">
              <label class="form-label">رابط الزر (tel: أو wa.me)</label>
              <input type="text" name="btn_link" class="form-control" value="<?= htmlspecialchars($edit_pkg['btn_link'] ?? 'tel:0554091835') ?>" />
            </div>
          </div>

          <div class="mb-3 form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_popular" id="popSwitch" <?= !empty($edit_pkg['is_popular']) ? 'checked' : '' ?>>
            <label class="form-check-label fw-bold text-dark" for="popSwitch">تمييز كـ "الباقة الأكثر طلباً" (VIP)</label>
          </div>

          <div class="mb-4">
            <label class="form-label">الترتيب</label>
            <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($edit_pkg['sort_order'] ?? 0) ?>" />
          </div>

          <button type="submit" class="btn btn-admin-primary w-100">
            <i class="fas fa-save me-1"></i> <?= $edit_pkg ? 'حفظ التعديل' : 'إضافة الباقة' ?>
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="content-card">
      <div class="content-card-header">
        <h5><i class="fas fa-tags text-primary"></i> الباقات المتوفرة (<?= count($packages) ?>)</h5>
      </div>
      <div class="content-card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>الباقة</th>
                <th>السعر</th>
                <th>الحالة</th>
                <th width="120">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($packages as $p): ?>
              <tr>
                <td>
                  <strong class="d-block text-dark"><?= htmlspecialchars($p['title']) ?></strong>
                  <small class="text-muted"><?= htmlspecialchars($p['subtitle']) ?></small>
                </td>
                <td>
                  <span class="badge bg-light text-dark border"><?= htmlspecialchars($p['price_text']) ?></span>
                </td>
                <td>
                  <?php if (!empty($p['is_popular'])): ?>
                    <span class="badge bg-warning text-dark"><i class="fas fa-crown me-1"></i> الأكثر طلباً</span>
                  <?php else: ?>
                    <span class="badge bg-light text-muted border">عادية</span>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="packages.php?edit_id=<?= $p['id'] ?>" class="btn btn-sm btn-light border" title="تعديل">
                    <i class="fas fa-edit text-warning"></i>
                  </a>
                  <a href="packages.php?delete_id=<?= $p['id'] ?>" class="btn btn-sm btn-light border confirm-delete" title="حذف">
                    <i class="fas fa-trash-alt text-danger"></i>
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
