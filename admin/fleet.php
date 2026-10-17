<?php
/**
 * إدارة أجهزة ومعدات النظافة والتعقيم (إضافة، تعديل، حذف)
 */

require_once __DIR__ . '/includes/admin_header.php';

// حذف
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM fleet WHERE id = :id");
    $stmt->execute([':id' => $del_id]);
    $_SESSION['flash_success'] = 'تم حذف العنصر بنجاح.';
    header('Location: fleet.php');
    exit;
}

// تعديل
$edit_fleet = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM fleet WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['edit_id']]);
    $edit_fleet = $stmt->fetch();
}

// حفظ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب.';
        header('Location: fleet.php');
        exit;
    }

    $title       = clean_input($_POST['title'] ?? '');
    $badge       = clean_input($_POST['badge'] ?? '');
    $description = clean_input($_POST['description'] ?? '');
    $sort_order  = (int)($_POST['sort_order'] ?? 0);
    $fleet_id    = (int)($_POST['fleet_id'] ?? 0);
    $image_path  = $edit_fleet['image'] ?? 'assets/images/7.jpg';

    if (!empty($_FILES['image_file']['name'])) {
        $up = upload_image('image_file');
        if ($up['success']) $image_path = $up['path'];
    } elseif (!empty($_POST['image_url'])) {
        $image_path = trim($_POST['image_url']);
    }

    if (empty($title)) {
        $_SESSION['flash_error'] = 'يرجى إدخال عنوان المعدة أو الجهاز.';
    } else {
        if ($fleet_id > 0) {
            $stmt = $pdo->prepare("UPDATE fleet SET title = :t, badge = :b, image = :img, description = :d, sort_order = :s WHERE id = :id");
            $stmt->execute([':t' => $title, ':b' => $badge, ':img' => $image_path, ':d' => $description, ':s' => $sort_order, ':id' => $fleet_id]);
            $_SESSION['flash_success'] = 'تم تعديل بيانات الجهاز/المعدة بنجاح!';
        } else {
            $stmt = $pdo->prepare("INSERT INTO fleet (title, badge, image, description, sort_order) VALUES (:t, :b, :img, :d, :s)");
            $stmt->execute([':t' => $title, ':b' => $badge, ':img' => $image_path, ':d' => $description, ':s' => $sort_order]);
            $_SESSION['flash_success'] = 'تمت إضافة الجهاز/المعدة بنجاح!';
        }
        header('Location: fleet.php');
        exit;
    }
}

$fleet = $pdo->query("SELECT * FROM fleet ORDER BY sort_order ASC, id ASC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">إدارة أجهزة ومعدات النظافة والتعقيم</h3>
    <p class="text-muted small mb-0">التحكم في أجهزة البخار الحار، ماكينات جلي الرخام، ومعدات التعقيم الحديثة</p>
  </div>
  <?php if ($edit_fleet): ?>
    <a href="fleet.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
      <i class="fas fa-plus me-1"></i> إضافة جهاز أو معدة جديدة
    </a>
  <?php endif; ?>
</div>

<div class="row g-4">
  <!-- Form Column -->
  <div class="col-lg-5">
    <div class="content-card">
      <div class="content-card-header">
        <h5>
          <i class="fas <?= $edit_fleet ? 'fa-edit text-warning' : 'fa-plus-circle text-primary' ?>"></i>
          <?= $edit_fleet ? 'تعديل الجهاز / المعدة' : 'إضافة جهاز / معدة جديدة' ?>
        </h5>
      </div>
      <div class="content-card-body">
        <form method="POST" action="fleet.php" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <input type="hidden" name="fleet_id" value="<?= $edit_fleet['id'] ?? 0 ?>">

          <div class="mb-3">
            <label class="form-label">العنوان الرئيسي</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($edit_fleet['title'] ?? '') ?>" required placeholder="مثال: ماكينات غسيل السجاد والكنب بالبخار الحار" />
          </div>

          <div class="mb-3">
            <label class="form-label">الشارة التعريفية (Badge)</label>
            <input type="text" name="badge" class="form-control" value="<?= htmlspecialchars($edit_fleet['badge'] ?? '') ?>" placeholder="مثال: ضغط بخار فائق 180°C" />
          </div>

          <div class="mb-3">
            <label class="form-label">رفع صورة</label>
            <input type="file" name="image_file" class="form-control img-preview-input" data-preview-target="fleetPreview" accept="image/*" />
          </div>

          <div class="mb-3">
            <label class="form-label">أو رابط الصورة المباشر</label>
            <input type="text" name="image_url" class="form-control" value="<?= htmlspecialchars($edit_fleet['image'] ?? '') ?>" placeholder="assets/images/7.jpg" />
          </div>

          <?php if (!empty($edit_fleet['image'])): ?>
          <div class="mb-3">
            <img id="fleetPreview" src="<?= htmlspecialchars(asset_url($edit_fleet['image'])) ?>" alt="Preview" style="max-height:100px;border-radius:10px;border:1px solid #ccc;object-fit:cover;" />
          </div>
          <?php else: ?>
          <div class="mb-3">
            <img id="fleetPreview" src="" alt="Preview" style="max-height:100px;border-radius:10px;border:1px solid #ccc;display:none;object-fit:cover;" />
          </div>
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label">الشرح والوصف</label>
            <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($edit_fleet['description'] ?? '') ?></textarea>
          </div>

          <div class="mb-4">
            <label class="form-label">الترتيب</label>
            <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($edit_fleet['sort_order'] ?? 0) ?>" />
          </div>

          <button type="submit" class="btn btn-admin-primary w-100">
            <i class="fas fa-save me-1"></i> <?= $edit_fleet ? 'حفظ التعديل' : 'إضافة إلى الأسطول' ?>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Table Column -->
  <div class="col-lg-7">
    <div class="content-card">
      <div class="content-card-header">
        <h5><i class="fas fa-tools text-primary"></i> الأجهزة والمعدات المسجلة (<?= count($fleet) ?>)</h5>
      </div>
      <div class="content-card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th width="60">الصورة</th>
                <th>العنوان والشارة</th>
                <th>الترتيب</th>
                <th width="120">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($fleet as $f): ?>
              <tr>
                <td>
                  <img src="<?= htmlspecialchars(asset_url($f['image'])) ?>" alt="" style="width:50px;height:45px;border-radius:8px;object-fit:cover;" />
                </td>
                <td>
                  <strong class="d-block text-dark"><?= htmlspecialchars($f['title']) ?></strong>
                  <span class="badge bg-light text-primary border"><?= htmlspecialchars($f['badge']) ?></span>
                </td>
                <td><span class="badge bg-light text-dark border"><?= $f['sort_order'] ?></span></td>
                <td>
                  <a href="fleet.php?edit_id=<?= $f['id'] ?>" class="btn btn-sm btn-light border" title="تعديل">
                    <i class="fas fa-edit text-warning"></i>
                  </a>
                  <a href="fleet.php?delete_id=<?= $f['id'] ?>" class="btn btn-sm btn-light border confirm-delete" title="حذف">
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
