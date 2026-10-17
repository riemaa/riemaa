<?php
/**
 * إدارة الخدمات (إضافة، تعديل، حذف)
 */

require_once __DIR__ . '/includes/admin_header.php';

// حذف خدمة
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = :id");
    $stmt->execute([':id' => $del_id]);
    $_SESSION['flash_success'] = 'تم حذف الخدمة بنجاح.';
    header('Location: services.php');
    exit;
}

// حفظ (إضافة أو تعديل)
$edit_service = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['edit_id']]);
    $edit_service = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب.';
        header('Location: services.php');
        exit;
    }

    $title       = clean_input($_POST['title'] ?? '');
    $icon        = clean_input($_POST['icon'] ?? 'fas fa-truck-moving');
    $description = clean_input($_POST['description'] ?? '');
    $features    = trim($_POST['features'] ?? '');
    $sort_order  = (int)($_POST['sort_order'] ?? 0);
    $service_id  = (int)($_POST['service_id'] ?? 0);

    if (empty($title)) {
        $_SESSION['flash_error'] = 'يرجى إدخال عنوان الخدمة.';
    } else {
        if ($service_id > 0) {
            $stmt = $pdo->prepare("UPDATE services SET title = :t, icon = :i, description = :d, features = :f, sort_order = :s WHERE id = :id");
            $stmt->execute([':t' => $title, ':i' => $icon, ':d' => $description, ':f' => $features, ':s' => $sort_order, ':id' => $service_id]);
            $_SESSION['flash_success'] = 'تم تعديل الخدمة بنجاح!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO services (title, icon, description, features, sort_order) VALUES (:t, :i, :d, :f, :s)");
            $stmt->execute([':t' => $title, ':i' => $icon, ':d' => $description, ':f' => $features, ':s' => $sort_order]);
            $_SESSION['flash_success'] = 'تمت إضافة الخدمة بنجاح!';
        }
        header('Location: services.php');
        exit;
    }
}

$services = $pdo->query("SELECT * FROM services ORDER BY sort_order ASC, id ASC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">إدارة الخدمات</h3>
    <p class="text-muted small mb-0">إضافة وتعديل وحذف بطاقات الخدمات والميزات المعروضة في صفحة الهبوط</p>
  </div>
  <?php if ($edit_service): ?>
    <a href="services.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
      <i class="fas fa-plus me-1"></i> إضافة خدمة جديدة
    </a>
  <?php endif; ?>
</div>

<div class="row g-4">
  <!-- Form Column -->
  <div class="col-lg-5">
    <div class="content-card">
      <div class="content-card-header">
        <h5>
          <i class="fas <?= $edit_service ? 'fa-edit text-warning' : 'fa-plus-circle text-primary' ?>"></i>
          <?= $edit_service ? 'تعديل الخدمة' : 'إضافة خدمة جديدة' ?>
        </h5>
      </div>
      <div class="content-card-body">
        <form method="POST" action="services.php">
          <?= csrf_field() ?>
          <input type="hidden" name="service_id" value="<?= $edit_service['id'] ?? 0 ?>">

          <div class="mb-3">
            <label class="form-label">عنوان الخدمة</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($edit_service['title'] ?? '') ?>" required placeholder="مثال: تنظيف منازل وفلل بالدمام والشرقية" />
          </div>

          <div class="mb-3">
            <label class="form-label">أيقونة FontAwesome</label>
            <div class="input-group">
              <span class="input-group-text"><i class="<?= htmlspecialchars($edit_service['icon'] ?? 'fas fa-spray-can-sparkles') ?>"></i></span>
              <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($edit_service['icon'] ?? 'fas fa-spray-can-sparkles') ?>" required placeholder="fas fa-spray-can-sparkles" />
            </div>
            <small class="text-muted">مثال: <code>fas fa-spray-can-sparkles</code>, <code>fas fa-soap</code>, <code>fas fa-broom</code>, <code>fas fa-water</code></small>
          </div>

          <div class="mb-3">
            <label class="form-label">وصف الخدمة</label>
            <textarea name="description" class="form-control" rows="3" required placeholder="شرح مبسط للخدمة..."><?= htmlspecialchars($edit_service['description'] ?? '') ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">الميزات الفرعية (كل ميزة في سطر منفصل)</label>
            <textarea name="features" class="form-control" rows="4" placeholder="ديانات مجهزة بأحدث بطانات الفوم&#10;سائقون محترفون&#10;وصول في الموعد"><?= htmlspecialchars($edit_service['features'] ?? '') ?></textarea>
          </div>

          <div class="mb-4">
            <label class="form-label">ترتيب الظهور</label>
            <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($edit_service['sort_order'] ?? 0) ?>" />
          </div>

          <button type="submit" class="btn btn-admin-primary w-100">
            <i class="fas fa-save me-1"></i> <?= $edit_service ? 'حفظ التعديل' : 'إضافة الخدمة' ?>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Table Column -->
  <div class="col-lg-7">
    <div class="content-card">
      <div class="content-card-header">
        <h5><i class="fas fa-list text-primary"></i> قائمة الخدمات المضافة (<?= count($services) ?>)</h5>
      </div>
      <div class="content-card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th width="45">الأيقونة</th>
                <th>العنوان</th>
                <th>الترتيب</th>
                <th width="120">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($services)): ?>
                <tr><td colspan="4" class="text-center py-4 text-muted">لا توجد خدمات مضافة حتى الآن.</td></tr>
              <?php else: ?>
                <?php foreach ($services as $srv): ?>
                <tr>
                  <td>
                    <div class="stat-icon primary" style="width:38px;height:38px;font-size:1.1rem;">
                      <i class="<?= htmlspecialchars($srv['icon']) ?>"></i>
                    </div>
                  </td>
                  <td>
                    <strong class="d-block text-dark"><?= htmlspecialchars($srv['title']) ?></strong>
                    <small class="text-muted text-truncate d-inline-block" style="max-width:240px;"><?= htmlspecialchars($srv['description']) ?></small>
                  </td>
                  <td><span class="badge bg-light text-dark border"><?= $srv['sort_order'] ?></span></td>
                  <td>
                    <a href="services.php?edit_id=<?= $srv['id'] ?>" class="btn btn-sm btn-light border" title="تعديل">
                      <i class="fas fa-edit text-warning"></i>
                    </a>
                    <a href="services.php?delete_id=<?= $srv['id'] ?>" class="btn btn-sm btn-light border confirm-delete" title="حذف">
                      <i class="fas fa-trash-alt text-danger"></i>
                    </a>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
