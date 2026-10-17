<?php
/**
 * إدارة آراء وتقييمات العملاء
 */

require_once __DIR__ . '/includes/admin_header.php';

// حذف
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = :id");
    $stmt->execute([':id' => $del_id]);
    $_SESSION['flash_success'] = 'تم حذف التقييم بنجاح.';
    header('Location: testimonials.php');
    exit;
}

// تعديل
$edit_t = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['edit_id']]);
    $edit_t = $stmt->fetch();
}

// حفظ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب.';
        header('Location: testimonials.php');
        exit;
    }

    $name          = clean_input($_POST['name'] ?? '');
    $location      = clean_input($_POST['location'] ?? '');
    $rating        = min(5, max(1, (int)($_POST['rating'] ?? 5)));
    $comment       = clean_input($_POST['comment'] ?? '');
    $avatar_letter = clean_input($_POST['avatar_letter'] ?? mb_substr($name, 0, 1, 'UTF-8'));
    $sort_order    = (int)($_POST['sort_order'] ?? 0);
    $testimonial_id = (int)($_POST['testimonial_id'] ?? 0);

    if (empty($name) || empty($comment)) {
        $_SESSION['flash_error'] = 'يرجى إدخال اسم العميل ونص التقييم.';
    } else {
        if ($testimonial_id > 0) {
            $stmt = $pdo->prepare("UPDATE testimonials SET name = :n, location = :loc, rating = :r, comment = :c, avatar_letter = :al, sort_order = :s WHERE id = :id");
            $stmt->execute([':n' => $name, ':loc' => $location, ':r' => $rating, ':c' => $comment, ':al' => $avatar_letter, ':s' => $sort_order, ':id' => $testimonial_id]);
            $_SESSION['flash_success'] = 'تم تعديل التقييم بنجاح!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO testimonials (name, location, rating, comment, avatar_letter, sort_order) VALUES (:n, :loc, :r, :c, :al, :s)");
            $stmt->execute([':n' => $name, ':loc' => $location, ':r' => $rating, ':c' => $comment, ':al' => $avatar_letter, ':s' => $sort_order]);
            $_SESSION['flash_success'] = 'تمت إضافة تقييم العميل بنجاح!';
        }
        header('Location: testimonials.php');
        exit;
    }
}

$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY sort_order ASC, id ASC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">إدارة آراء وتقييمات العملاء</h3>
    <p class="text-muted small mb-0">إضافة وتعديل التقييمات المعروضة في قسم تجارب حقيقية</p>
  </div>
  <?php if ($edit_t): ?>
    <a href="testimonials.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
      <i class="fas fa-plus me-1"></i> إضافة تقييم جديد
    </a>
  <?php endif; ?>
</div>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="content-card">
      <div class="content-card-header">
        <h5>
          <i class="fas <?= $edit_t ? 'fa-edit text-warning' : 'fa-plus-circle text-primary' ?>"></i>
          <?= $edit_t ? 'تعديل التقييم' : 'إضافة تقييم جديد' ?>
        </h5>
      </div>
      <div class="content-card-body">
        <form method="POST" action="testimonials.php">
          <?= csrf_field() ?>
          <input type="hidden" name="testimonial_id" value="<?= $edit_t['id'] ?? 0 ?>">

          <div class="mb-3">
            <label class="form-label">اسم العميل</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($edit_t['name'] ?? '') ?>" required placeholder="مثال: أحمد الشمري" />
          </div>

          <div class="mb-3">
            <label class="form-label">المدينة أو الحي</label>
            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($edit_t['location'] ?? 'الدمام - حي الشاطئ') ?>" required />
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">التقييم بالنجوم</label>
              <select name="rating" class="form-select">
                <option value="5" <?= ($edit_t['rating'] ?? 5) == 5 ? 'selected' : '' ?>>★★★★★ (5 نجوم)</option>
                <option value="4" <?= ($edit_t['rating'] ?? 5) == 4 ? 'selected' : '' ?>>★★★★☆ (4 نجوم)</option>
                <option value="3" <?= ($edit_t['rating'] ?? 5) == 3 ? 'selected' : '' ?>>★★★☆☆ (3 نجوم)</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label">الحرف الرمزي</label>
              <input type="text" name="avatar_letter" class="form-control" value="<?= htmlspecialchars($edit_t['avatar_letter'] ?? 'أ') ?>" maxlength="2" />
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">نص الرأي / التجربة</label>
            <textarea name="comment" class="form-control" rows="4" required placeholder="اكتب نص تقييم العميل..."><?= htmlspecialchars($edit_t['comment'] ?? '') ?></textarea>
          </div>

          <div class="mb-4">
            <label class="form-label">الترتيب</label>
            <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($edit_t['sort_order'] ?? 0) ?>" />
          </div>

          <button type="submit" class="btn btn-admin-primary w-100">
            <i class="fas fa-save me-1"></i> <?= $edit_t ? 'حفظ التعديل' : 'إضافة التقييم' ?>
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="content-card">
      <div class="content-card-header">
        <h5><i class="fas fa-comments text-primary"></i> تقييمات العملاء (<?= count($testimonials) ?>)</h5>
      </div>
      <div class="content-card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>العميل</th>
                <th>التقييم</th>
                <th>الرأي</th>
                <th width="120">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($testimonials as $t): ?>
              <tr>
                <td>
                  <strong class="d-block text-dark"><?= htmlspecialchars($t['name']) ?></strong>
                  <small class="text-muted"><?= htmlspecialchars($t['location']) ?></small>
                </td>
                <td><span class="text-warning"><?= str_repeat('★', (int)$t['rating']) ?></span></td>
                <td>
                  <small class="text-muted text-truncate d-inline-block" style="max-width:220px;"><?= htmlspecialchars($t['comment']) ?></small>
                </td>
                <td>
                  <a href="testimonials.php?edit_id=<?= $t['id'] ?>" class="btn btn-sm btn-light border" title="تعديل">
                    <i class="fas fa-edit text-warning"></i>
                  </a>
                  <a href="testimonials.php?delete_id=<?= $t['id'] ?>" class="btn btn-sm btn-light border confirm-delete" title="حذف">
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
