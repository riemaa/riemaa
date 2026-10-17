<?php
/**
 * إدارة معرض الأعمال والصور الميدانية
 */

require_once __DIR__ . '/includes/admin_header.php';

// حذف صورة
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = :id");
    $stmt->execute([':id' => $del_id]);
    $_SESSION['flash_success'] = 'تم حذف الصورة بنجاح.';
    header('Location: gallery.php');
    exit;
}

// تعديل
$edit_item = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['edit_id']]);
    $edit_item = $stmt->fetch();
}

// حفظ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب.';
        header('Location: gallery.php');
        exit;
    }

    $caption    = clean_input($_POST['caption'] ?? '');
    $category   = clean_input($_POST['category'] ?? 'تنظيف منازل');
    if (empty($category)) {
        $category = 'عام';
    }
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $item_id    = (int)($_POST['item_id'] ?? 0);
    $image_path = $edit_item['image'] ?? '';

    // رفع صورة جديدة
    if (!empty($_FILES['gallery_file']['name'])) {
        $up = upload_image('gallery_file');
        if ($up['success']) {
            $image_path = $up['path'];
        } else {
            $_SESSION['flash_error'] = 'فشل رفع الصورة: ' . $up['error'];
        }
    } elseif (!empty($_POST['image_url'])) {
        $image_path = trim($_POST['image_url']);
    }

    if (empty($image_path)) {
        $_SESSION['flash_error'] = 'يرجى رفع صورة أو إدخال رابط صالح.';
    } else {
        if ($item_id > 0) {
            $stmt = $pdo->prepare("UPDATE gallery SET image = :img, caption = :c, category = :cat, sort_order = :s WHERE id = :id");
            $stmt->execute([':img' => $image_path, ':c' => $caption, ':cat' => $category, ':s' => $sort_order, ':id' => $item_id]);
            $_SESSION['flash_success'] = 'تم تعديل بيانات الصورة بنجاح!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO gallery (image, caption, category, sort_order) VALUES (:img, :c, :cat, :s)");
            $stmt->execute([':img' => $image_path, ':c' => $caption, ':cat' => $category, ':s' => $sort_order]);
            $_SESSION['flash_success'] = 'تمت إضافة الصورة إلى المعرض بنجاح!';
        }
        header('Location: gallery.php');
        exit;
    }
}

$gallery_items = $pdo->query("SELECT * FROM gallery ORDER BY sort_order ASC, id ASC")->fetchAll();

// جلب التصنيفات المسجلة حالياً لتقديمها كمقترحات مع إمكانية كتابة أي تصنيف جديد بحرية
$existing_categories = [];
try {
    $existing_categories = $pdo->query("SELECT DISTINCT category FROM gallery WHERE category IS NOT NULL AND category != '' ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {}

$default_suggestions = ['تنظيف منازل', 'تنظيف سجاد', 'تنظيف مجالس', 'تنظيف كنب', 'تنظيف ارضيات', 'تنظيف خزانات المياه', 'تنظيف مكيفات', 'تنظيف مدارس', 'تنظيف شركات'];
$suggested_categories = array_values(array_unique(array_filter(array_merge($existing_categories, $default_suggestions))));
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">إدارة معرض الأعمال (<?= count($gallery_items) ?> صورة)</h3>
    <p class="text-muted small mb-0">رفع وإدارة صور عمليات التنظيف الشامل وغسيل السجاد والمجالس والكنب والخزانات والمكيفات</p>
  </div>
  <?php if ($edit_item): ?>
    <a href="gallery.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
      <i class="fas fa-plus me-1"></i> إضافة صورة جديدة
    </a>
  <?php endif; ?>
</div>

<div class="row g-4">
  <!-- Form Column -->
  <div class="col-lg-4">
    <div class="content-card">
      <div class="content-card-header">
        <h5>
          <i class="fas <?= $edit_item ? 'fa-edit text-warning' : 'fa-upload text-primary' ?>"></i>
          <?= $edit_item ? 'تعديل الصورة' : 'إضافة صورة جديدة' ?>
        </h5>
      </div>
      <div class="content-card-body">
        <form method="POST" action="gallery.php" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <input type="hidden" name="item_id" value="<?= $edit_item['id'] ?? 0 ?>">

          <div class="mb-3">
            <label class="form-label">وصف الصورة (يظهر عند النقر واللايت بوكس)</label>
            <input type="text" name="caption" class="form-control" value="<?= htmlspecialchars($edit_item['caption'] ?? '') ?>" required placeholder="مثال: تنظيف منازل وفلل بالدمام" />
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">تصنيف الصورة <small class="text-muted fw-normal">(اكتب تصنيفاً بحرية أو اختر من القائمة)</small></label>
            <input type="text" name="category" list="categoriesList" class="form-control" value="<?= htmlspecialchars($edit_item['category'] ?? '') ?>" required placeholder="اكتب تصنيف الصورة (مثال: تنظيف منازل، تنظيف سجاد، تنظيف كنب...)" autocomplete="off" />
            <datalist id="categoriesList">
              <?php foreach ($suggested_categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>"></option>
              <?php endforeach; ?>
            </datalist>
            <div class="form-text text-muted small"><i class="fas fa-info-circle me-1"></i> يمكنك كتابة أي تصنيف جديد بحرية تامة وتعديله في أي وقت، أو الاختيار من القائمة.</div>
          </div>

          <div class="mb-3">
            <label class="form-label">رفع ملف الصورة</label>
            <input type="file" name="gallery_file" class="form-control img-preview-input" data-preview-target="galleryPreview" accept="image/*" />
          </div>

          <div class="mb-3">
            <label class="form-label">أو مسار / رابط الصورة</label>
            <input type="text" name="image_url" class="form-control" value="<?= htmlspecialchars($edit_item['image'] ?? '') ?>" placeholder="assets/images/1.jpg" />
          </div>

          <div class="mb-3">
            <img id="galleryPreview" src="<?= !empty($edit_item['image']) ? htmlspecialchars(asset_url($edit_item['image'])) : '' ?>" alt="Preview" style="max-height:120px;border-radius:10px;border:1px solid #ccc;<?= empty($edit_item['image']) ? 'display:none;' : '' ?>object-fit:cover;" />
          </div>

          <div class="mb-4">
            <label class="form-label">الترتيب</label>
            <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($edit_item['sort_order'] ?? 0) ?>" />
          </div>

          <button type="submit" class="btn btn-admin-primary w-100">
            <i class="fas fa-save me-1"></i> <?= $edit_item ? 'حفظ التعديل' : 'إضافة إلى المعرض' ?>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Gallery Grid / Table Column -->
  <div class="col-lg-8">
    <div class="content-card">
      <div class="content-card-header">
        <h5><i class="fas fa-images text-primary"></i> صور المعرض الميداني</h5>
      </div>
      <div class="content-card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th width="70">المعاينة</th>
                <th>الوصف والشرح</th>
                <th>التصنيف</th>
                <th>الترتيب</th>
                <th width="120">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($gallery_items as $item): ?>
              <tr>
                <td>
                  <img src="<?= htmlspecialchars(asset_url($item['image'])) ?>" alt="" style="width:55px;height:45px;border-radius:8px;object-fit:cover;box-shadow:0 2px 6px rgba(0,0,0,0.1);" />
                </td>
                <td>
                  <strong class="d-block text-dark small"><?= htmlspecialchars($item['caption']) ?></strong>
                  <code class="text-muted small"><?= htmlspecialchars($item['image']) ?></code>
                </td>
                <td><span class="badge bg-light text-primary border fw-bold"><?= htmlspecialchars($item['category'] ?: 'عام') ?></span></td>
                <td><span class="badge bg-light text-dark border"><?= $item['sort_order'] ?></span></td>
                <td>
                  <a href="gallery.php?edit_id=<?= $item['id'] ?>" class="btn btn-sm btn-light border" title="تعديل">
                    <i class="fas fa-edit text-warning"></i>
                  </a>
                  <a href="gallery.php?delete_id=<?= $item['id'] ?>" class="btn btn-sm btn-light border confirm-delete" title="حذف">
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
