<?php
/**
 * إدارة ركائز التميز (لماذا تختارنا)
 */

require_once __DIR__ . '/includes/admin_header.php';

// حذف
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM why_us WHERE id = :id");
    $stmt->execute([':id' => $del_id]);
    $_SESSION['flash_success'] = 'تم حذف الميزة بنجاح.';
    header('Location: whyus.php');
    exit;
}

// تعديل
$edit_why = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM why_us WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['edit_id']]);
    $edit_why = $stmt->fetch();
}

// حفظ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب.';
        header('Location: whyus.php');
        exit;
    }

    $title       = clean_input($_POST['title'] ?? '');
    $icon        = clean_input($_POST['icon'] ?? 'fas fa-shield-alt');
    $description = clean_input($_POST['description'] ?? '');
    $sort_order  = (int)($_POST['sort_order'] ?? 0);
    $why_id      = (int)($_POST['why_id'] ?? 0);

    if (empty($title)) {
        $_SESSION['flash_error'] = 'يرجى إدخال عنوان الميزة.';
    } else {
        if ($why_id > 0) {
            $stmt = $pdo->prepare("UPDATE why_us SET title = :t, icon = :i, description = :d, sort_order = :s WHERE id = :id");
            $stmt->execute([':t' => $title, ':i' => $icon, ':d' => $description, ':s' => $sort_order, ':id' => $why_id]);
            $_SESSION['flash_success'] = 'تم تحديث الميزة بنجاح!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO why_us (title, icon, description, sort_order) VALUES (:t, :i, :d, :s)");
            $stmt->execute([':t' => $title, ':i' => $icon, ':d' => $description, ':s' => $sort_order]);
            $_SESSION['flash_success'] = 'تمت إضافة الميزة بنجاح!';
        }
        header('Location: whyus.php');
        exit;
    }
}

$why_us = $pdo->query("SELECT * FROM why_us ORDER BY sort_order ASC, id ASC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">إدارة بطاقات (لماذا تختارنا)</h3>
    <p class="text-muted small mb-0">تعديل ركائز التميز وضمانات الأمان والسرعة والأمانة</p>
  </div>
  <?php if ($edit_why): ?>
    <a href="whyus.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
      <i class="fas fa-plus me-1"></i> إضافة ميزة جديدة
    </a>
  <?php endif; ?>
</div>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="content-card">
      <div class="content-card-header">
        <h5>
          <i class="fas <?= $edit_why ? 'fa-edit text-warning' : 'fa-plus-circle text-primary' ?>"></i>
          <?= $edit_why ? 'تعديل الميزة' : 'إضافة ميزة جديدة' ?>
        </h5>
      </div>
      <div class="content-card-body">
        <form method="POST" action="whyus.php">
          <?= csrf_field() ?>
          <input type="hidden" name="why_id" value="<?= $edit_why['id'] ?? 0 ?>">

          <div class="mb-3">
            <label class="form-label">عنوان الميزة</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($edit_why['title'] ?? '') ?>" required placeholder="مثال: ضمان سلامة الأثاث 100%" />
          </div>

          <div class="mb-3">
            <label class="form-label">أيقونة FontAwesome</label>
            <div class="input-group">
              <span class="input-group-text"><i class="<?= htmlspecialchars($edit_why['icon'] ?? 'fas fa-shield-alt') ?>"></i></span>
              <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($edit_why['icon'] ?? 'fas fa-shield-halved') ?>" required placeholder="fas fa-clock-rotate-left" />
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">الوصف التفصيلي</label>
            <textarea name="description" class="form-control" rows="3" required placeholder="شرح مبسط للميزة..."><?= htmlspecialchars($edit_why['description'] ?? '') ?></textarea>
          </div>

          <div class="mb-4">
            <label class="form-label">الترتيب</label>
            <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($edit_why['sort_order'] ?? 0) ?>" />
          </div>

          <button type="submit" class="btn btn-admin-primary w-100">
            <i class="fas fa-save me-1"></i> <?= $edit_why ? 'حفظ التعديل' : 'إضافة الميزة' ?>
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="content-card">
      <div class="content-card-header">
        <h5><i class="fas fa-crown text-warning"></i> ركائز التميز المسجلة (<?= count($why_us) ?>)</h5>
      </div>
      <div class="content-card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th width="45">الأيقونة</th>
                <th>العنوان والوصف</th>
                <th>الترتيب</th>
                <th width="120">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($why_us as $w): ?>
              <tr>
                <td>
                  <div class="stat-icon warning" style="width:36px;height:36px;font-size:1rem;">
                    <i class="<?= htmlspecialchars($w['icon']) ?>"></i>
                  </div>
                </td>
                <td>
                  <strong class="d-block text-dark"><?= htmlspecialchars($w['title']) ?></strong>
                  <small class="text-muted"><?= htmlspecialchars($w['description']) ?></small>
                </td>
                <td><span class="badge bg-light text-dark border"><?= $w['sort_order'] ?></span></td>
                <td>
                  <a href="whyus.php?edit_id=<?= $w['id'] ?>" class="btn btn-sm btn-light border" title="تعديل">
                    <i class="fas fa-edit text-warning"></i>
                  </a>
                  <a href="whyus.php?delete_id=<?= $w['id'] ?>" class="btn btn-sm btn-light border confirm-delete" title="حذف">
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
