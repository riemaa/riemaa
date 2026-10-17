<?php
/**
 * إدارة مقالات وبطاقات دليل السيو (دليل النظافة والتعقيم)
 */

require_once __DIR__ . '/includes/admin_header.php';

// حذف
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM seo_articles WHERE id = :id");
    $stmt->execute([':id' => $del_id]);
    $_SESSION['flash_success'] = 'تم حذف المقال بنجاح.';
    header('Location: seo_guide.php');
    exit;
}

// تعديل
$edit_article = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM seo_articles WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['edit_id']]);
    $edit_article = $stmt->fetch();
}

// حفظ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب.';
        header('Location: seo_guide.php');
        exit;
    }

    $title       = clean_input($_POST['title'] ?? '');
    $icon        = clean_input($_POST['icon'] ?? 'fas fa-shield-alt');
    $description = clean_input($_POST['description'] ?? '');
    $sort_order  = (int)($_POST['sort_order'] ?? 0);
    $article_id  = (int)($_POST['article_id'] ?? 0);

    if (empty($title) || empty($description)) {
        $_SESSION['flash_error'] = 'يرجى إدخال العنوان والمحتوى.';
    } else {
        if ($article_id > 0) {
            $stmt = $pdo->prepare("UPDATE seo_articles SET title = :t, icon = :i, description = :d, sort_order = :s WHERE id = :id");
            $stmt->execute([':t' => $title, ':i' => $icon, ':d' => $description, ':s' => $sort_order, ':id' => $article_id]);
            $_SESSION['flash_success'] = 'تم تعديل المقال بنجاح!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO seo_articles (title, icon, description, sort_order) VALUES (:t, :i, :d, :s)");
            $stmt->execute([':t' => $title, ':i' => $icon, ':d' => $description, ':s' => $sort_order]);
            $_SESSION['flash_success'] = 'تمت إضافة المقال بنجاح!';
        }
        header('Location: seo_guide.php');
        exit;
    }
}

$articles = $pdo->query("SELECT * FROM seo_articles ORDER BY sort_order ASC, id ASC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">إدارة دليل النظافة والتعقيم والسيو</h3>
    <p class="text-muted small mb-0">مقالات وفقرات غنية بالكلمات المفتاحية لرفع ترتيب موقع النظافة في جوجل وتسهيل الأرشفة</p>
  </div>
  <?php if ($edit_article): ?>
    <a href="seo_guide.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
      <i class="fas fa-plus me-1"></i> إضافة مقال جديد
    </a>
  <?php endif; ?>
</div>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="content-card">
      <div class="content-card-header">
        <h5>
          <i class="fas <?= $edit_article ? 'fa-edit text-warning' : 'fa-plus-circle text-primary' ?>"></i>
          <?= $edit_article ? 'تعديل المقال' : 'إضافة مقال جديد' ?>
        </h5>
      </div>
      <div class="content-card-body">
        <form method="POST" action="seo_guide.php">
          <?= csrf_field() ?>
          <input type="hidden" name="article_id" value="<?= $edit_article['id'] ?? 0 ?>">

          <div class="mb-3">
            <label class="form-label">عنوان البطاقة / المقال</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($edit_article['title'] ?? '') ?>" required placeholder="مثال: الأمان والضمان القانوني" />
          </div>

          <div class="mb-3">
            <label class="form-label">أيقونة FontAwesome</label>
            <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($edit_article['icon'] ?? 'fas fa-shield-alt') ?>" required placeholder="fas fa-broom" />
          </div>

          <div class="mb-3">
            <label class="form-label">المحتوى والنص الإرشادي</label>
            <textarea name="description" class="form-control" rows="4" required placeholder="اكتب فقرة تحتوي على كلمات مفتاحية عن خدمات النظافة والتعقيم..."><?= htmlspecialchars($edit_article['description'] ?? '') ?></textarea>
          </div>

          <div class="mb-4">
            <label class="form-label">الترتيب</label>
            <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($edit_article['sort_order'] ?? 0) ?>" />
          </div>

          <button type="submit" class="btn btn-admin-primary w-100">
            <i class="fas fa-save me-1"></i> <?= $edit_article ? 'حفظ التعديل' : 'إضافة المقال' ?>
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="content-card">
      <div class="content-card-header">
        <h5><i class="fas fa-file-alt text-primary"></i> مقالات الدليل المضافة (<?= count($articles) ?>)</h5>
      </div>
      <div class="content-card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>العنوان</th>
                <th>الترتيب</th>
                <th width="120">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($articles as $art): ?>
              <tr>
                <td>
                  <strong class="d-block text-dark mb-1">
                    <i class="<?= htmlspecialchars($art['icon']) ?> text-warning me-1"></i>
                    <?= htmlspecialchars($art['title']) ?>
                  </strong>
                  <small class="text-muted text-truncate d-inline-block" style="max-width:320px;"><?= htmlspecialchars($art['description']) ?></small>
                </td>
                <td><span class="badge bg-light text-dark border"><?= $art['sort_order'] ?></span></td>
                <td>
                  <a href="seo_guide.php?edit_id=<?= $art['id'] ?>" class="btn btn-sm btn-light border" title="تعديل">
                    <i class="fas fa-edit text-warning"></i>
                  </a>
                  <a href="seo_guide.php?delete_id=<?= $art['id'] ?>" class="btn btn-sm btn-light border confirm-delete" title="حذف">
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
