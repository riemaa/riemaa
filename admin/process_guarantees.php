<?php
/**
 * إدارة خطوات العمل وميثاق الضمانات الذهبية
 */

require_once __DIR__ . '/includes/admin_header.php';

// تحديث الخطوات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_steps') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب.';
        header('Location: process_guarantees.php');
        exit;
    }

    if (isset($_POST['steps']) && is_array($_POST['steps'])) {
        foreach ($_POST['steps'] as $id => $data) {
            $stmt = $pdo->prepare("UPDATE work_steps SET title = :t, description = :d, color_code = :c WHERE id = :id");
            $stmt->execute([
                ':t' => clean_input($data['title']),
                ':d' => clean_input($data['description']),
                ':c' => clean_input($data['color_code']),
                ':id' => (int)$id
            ]);
        }
        $_SESSION['flash_success'] = 'تم تحديث خطوات العمل بنجاح!';
    }
    header('Location: process_guarantees.php');
    exit;
}

// تحديث الضمانات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_guarantees') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب.';
        header('Location: process_guarantees.php');
        exit;
    }

    if (isset($_POST['guarantees']) && is_array($_POST['guarantees'])) {
        foreach ($_POST['guarantees'] as $id => $data) {
            $stmt = $pdo->prepare("UPDATE guarantees SET icon = :i, title = :t, description = :d WHERE id = :id");
            $stmt->execute([
                ':i' => clean_input($data['icon']),
                ':t' => clean_input($data['title']),
                ':d' => clean_input($data['description']),
                ':id' => (int)$id
            ]);
        }
        $_SESSION['flash_success'] = 'تم تحديث ميثاق الضمانات بنجاح!';
    }
    header('Location: process_guarantees.php');
    exit;
}

$steps = $pdo->query("SELECT * FROM work_steps ORDER BY step_number ASC")->fetchAll();
$guarantees = $pdo->query("SELECT * FROM guarantees ORDER BY sort_order ASC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">إدارة خطوات العمل والضمانات الذهبية</h3>
    <p class="text-muted small mb-0">التحكم في مراحل وخطوات التنظيف الشامل وميثاق الضمانات الذهبية</p>
  </div>
</div>

<!-- 1. خطوات العمل -->
<div class="content-card mb-4">
  <div class="content-card-header">
    <h5><i class="fas fa-tasks text-primary"></i> مراحل وخطوات التنظيف والتعقيم</h5>
  </div>
  <div class="content-card-body">
    <form method="POST" action="process_guarantees.php">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="update_steps">

      <div class="row g-4">
        <?php foreach ($steps as $s): ?>
        <div class="col-md-6 col-lg-4">
          <div class="p-3 border rounded-3 bg-light h-100">
            <div class="d-flex align-items-center gap-2 mb-2">
              <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold">الخطوة <?= $s['step_number'] ?></span>
              <input type="color" name="steps[<?= $s['id'] ?>][color_code]" class="form-control form-control-color p-0 border-0" value="<?= htmlspecialchars($s['color_code']) ?>" title="لون الخطوة">
            </div>
            <div class="mb-2">
              <label class="form-label small">عنوان الخطوة</label>
              <input type="text" name="steps[<?= $s['id'] ?>][title]" class="form-control form-control-sm" value="<?= htmlspecialchars($s['title']) ?>" required />
            </div>
            <div>
              <label class="form-label small">شرح الخطوة</label>
              <textarea name="steps[<?= $s['id'] ?>][description]" class="form-control form-control-sm" rows="3" required><?= htmlspecialchars($s['description']) ?></textarea>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="mt-4 text-start">
        <button type="submit" class="btn btn-admin-primary px-4">
          <i class="fas fa-save me-1"></i> حفظ تعديلات خطوات العمل
        </button>
      </div>
    </form>
  </div>
</div>

<!-- 2. ميثاق الضمانات الذهبية -->
<div class="content-card">
  <div class="content-card-header">
    <h5><i class="fas fa-shield-alt text-success"></i> ميثاق الضمانات الذهبية الأربعة</h5>
  </div>
  <div class="content-card-body">
    <form method="POST" action="process_guarantees.php">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="update_guarantees">

      <div class="row g-4">
        <?php foreach ($guarantees as $g): ?>
        <div class="col-md-6">
          <div class="p-3 border rounded-3 bg-light h-100">
            <div class="row g-2 mb-2">
              <div class="col-8">
                <label class="form-label small">عنوان الضمان</label>
                <input type="text" name="guarantees[<?= $g['id'] ?>][title]" class="form-control form-control-sm" value="<?= htmlspecialchars($g['title']) ?>" required />
              </div>
              <div class="col-4">
                <label class="form-label small">الأيقونة</label>
                <input type="text" name="guarantees[<?= $g['id'] ?>][icon]" class="form-control form-control-sm" value="<?= htmlspecialchars($g['icon']) ?>" required />
              </div>
            </div>
            <div>
              <label class="form-label small">نص الضمان</label>
              <textarea name="guarantees[<?= $g['id'] ?>][description]" class="form-control form-control-sm" rows="3" required><?= htmlspecialchars($g['description']) ?></textarea>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="mt-4 text-start">
        <button type="submit" class="btn btn-admin-primary px-4">
          <i class="fas fa-save me-1"></i> حفظ تعديلات ميثاق الضمانات
        </button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
