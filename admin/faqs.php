<?php
/**
 * إدارة بنك الأسئلة الشائعة
 */

require_once __DIR__ . '/includes/admin_header.php';

// حذف
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM faqs WHERE id = :id");
    $stmt->execute([':id' => $del_id]);
    $_SESSION['flash_success'] = 'تم حذف السؤال بنجاح.';
    header('Location: faqs.php');
    exit;
}

// تعديل
$edit_faq = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM faqs WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['edit_id']]);
    $edit_faq = $stmt->fetch();
}

// حفظ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب.';
        header('Location: faqs.php');
        exit;
    }

    $question   = clean_input($_POST['question'] ?? '');
    $answer     = clean_input($_POST['answer'] ?? '');
    $icon       = clean_input($_POST['icon'] ?? 'fas fa-question-circle');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $faq_id     = (int)($_POST['faq_id'] ?? 0);

    if (empty($question) || empty($answer)) {
        $_SESSION['flash_error'] = 'يرجى إدخال نص السؤال والإجابة.';
    } else {
        if ($faq_id > 0) {
            $stmt = $pdo->prepare("UPDATE faqs SET question = :q, answer = :a, icon = :i, sort_order = :s WHERE id = :id");
            $stmt->execute([':q' => $question, ':a' => $answer, ':i' => $icon, ':s' => $sort_order, ':id' => $faq_id]);
            $_SESSION['flash_success'] = 'تم تحديث السؤال بنجاح!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO faqs (question, answer, icon, sort_order) VALUES (:q, :a, :i, :s)");
            $stmt->execute([':q' => $question, ':a' => $answer, ':i' => $icon, ':s' => $sort_order]);
            $_SESSION['flash_success'] = 'تمت إضافة السؤال بنجاح!';
        }
        header('Location: faqs.php');
        exit;
    }
}

$faqs = $pdo->query("SELECT * FROM faqs ORDER BY sort_order ASC, id ASC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">إدارة الأسئلة الشائعة</h3>
    <p class="text-muted small mb-0">إضافة وتعديل وحذف أسئلة وأجوبة العملاء</p>
  </div>
  <?php if ($edit_faq): ?>
    <a href="faqs.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
      <i class="fas fa-plus me-1"></i> إضافة سؤال جديد
    </a>
  <?php endif; ?>
</div>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="content-card">
      <div class="content-card-header">
        <h5>
          <i class="fas <?= $edit_faq ? 'fa-edit text-warning' : 'fa-plus-circle text-primary' ?>"></i>
          <?= $edit_faq ? 'تعديل السؤال' : 'إضافة سؤال جديد' ?>
        </h5>
      </div>
      <div class="content-card-body">
        <form method="POST" action="faqs.php">
          <?= csrf_field() ?>
          <input type="hidden" name="faq_id" value="<?= $edit_faq['id'] ?? 0 ?>">

          <div class="mb-3">
            <label class="form-label">نص السؤال</label>
            <input type="text" name="question" class="form-control" value="<?= htmlspecialchars($edit_faq['question'] ?? '') ?>" required placeholder="مثال: كيف يتم تحديد تكلفة تنظيف المنازل والفلل؟" />
          </div>

          <div class="mb-3">
            <label class="form-label">أيقونة السؤال</label>
            <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($edit_faq['icon'] ?? 'fas fa-question-circle') ?>" required placeholder="fas fa-calculator" />
          </div>

          <div class="mb-3">
            <label class="form-label">الإجابة الشاملة</label>
            <textarea name="answer" class="form-control" rows="5" required placeholder="اكتب الإجابة المفصلة..."><?= htmlspecialchars($edit_faq['answer'] ?? '') ?></textarea>
          </div>

          <div class="mb-4">
            <label class="form-label">الترتيب</label>
            <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($edit_faq['sort_order'] ?? 0) ?>" />
          </div>

          <button type="submit" class="btn btn-admin-primary w-100">
            <i class="fas fa-save me-1"></i> <?= $edit_faq ? 'حفظ التعديل' : 'إضافة السؤال' ?>
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="content-card">
      <div class="content-card-header">
        <h5><i class="fas fa-question-circle text-primary"></i> بنك الأسئلة الشائعة (<?= count($faqs) ?>)</h5>
      </div>
      <div class="content-card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>السؤال</th>
                <th>الترتيب</th>
                <th width="120">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($faqs as $f): ?>
              <tr>
                <td>
                  <strong class="d-block text-dark mb-1">
                    <i class="<?= htmlspecialchars($f['icon']) ?> text-warning me-1"></i>
                    <?= htmlspecialchars($f['question']) ?>
                  </strong>
                  <small class="text-muted text-truncate d-inline-block" style="max-width:320px;"><?= htmlspecialchars($f['answer']) ?></small>
                </td>
                <td><span class="badge bg-light text-dark border"><?= $f['sort_order'] ?></span></td>
                <td>
                  <a href="faqs.php?edit_id=<?= $f['id'] ?>" class="btn btn-sm btn-light border" title="تعديل">
                    <i class="fas fa-edit text-warning"></i>
                  </a>
                  <a href="faqs.php?delete_id=<?= $f['id'] ?>" class="btn btn-sm btn-light border confirm-delete" title="حذف">
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
