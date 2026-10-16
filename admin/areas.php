<?php
/**
 * إدارة مناطق وأحياء الخدمة
 */

require_once __DIR__ . '/includes/admin_header.php';

// حذف
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM service_areas WHERE id = :id");
    $stmt->execute([':id' => $del_id]);
    $_SESSION['flash_success'] = 'تم حذف المنطقة بنجاح.';
    header('Location: areas.php');
    exit;
}

// حفظ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب.';
        header('Location: areas.php');
        exit;
    }

    $area_name  = clean_input($_POST['area_name'] ?? '');
    $category   = clean_input($_POST['category'] ?? 'north_central');
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    if (empty($area_name)) {
        $_SESSION['flash_error'] = 'يرجى إدخال اسم الحي أو المنطقة.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO service_areas (category, area_name, sort_order) VALUES (:c, :a, :s)");
        $stmt->execute([':c' => $category, ':a' => $area_name, ':s' => $sort_order]);
        $_SESSION['flash_success'] = 'تمت إضافة المنطقة بنجاح!';
        header('Location: areas.php');
        exit;
    }
}

$all_areas = $pdo->query("SELECT * FROM service_areas ORDER BY category ASC, sort_order ASC, id ASC")->fetchAll();
$existing_categories = $pdo->query("SELECT DISTINCT category FROM service_areas WHERE category != '' ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">إدارة مناطق وأحياء التغطية</h3>
    <p class="text-muted small mb-0">يمكنك كتابة وتحديد أي أقسام أو مدن أو أحياء بحرية تامة دون أي قيود</p>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="content-card">
      <div class="content-card-header">
        <h5><i class="fas fa-map-marker-alt text-primary"></i> إضافة حي / منطقة جديدة</h5>
      </div>
      <div class="content-card-body">
        <form method="POST" action="areas.php">
          <?= csrf_field() ?>

          <div class="mb-3">
            <label class="form-label fw-bold">القسم الجغرافي أو التصنيف (اكتب بحرية)</label>
            <input type="text" name="category" list="categoriesList" class="form-control" required placeholder="مثال: أحياء شمال الرياض، مدن الغربية..." />
            <datalist id="categoriesList">
              <?php foreach ($existing_categories as $ec): ?>
                <option value="<?= htmlspecialchars($ec) ?>"></option>
              <?php endforeach; ?>
            </datalist>
            <small class="text-muted">يمكنك كتابة مسمى قسم جديد، أو اختيار قسم مكتوب مسبقاً</small>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">اسم الحي أو المدينة</label>
            <input type="text" name="area_name" class="form-control" required placeholder="مثال: حطين، النرجس، جدة، الدمام..." />
          </div>

          <div class="mb-4">
            <label class="form-label">الترتيب</label>
            <input type="number" name="sort_order" class="form-control" value="0" />
          </div>

          <button type="submit" class="btn btn-admin-primary w-100">
            <i class="fas fa-plus me-1"></i> إضافة المنطقة
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="content-card">
      <div class="content-card-header">
        <h5><i class="fas fa-map-marked-alt text-primary"></i> المناطق المضافة حالياً (<?= count($all_areas) ?>)</h5>
      </div>
      <div class="content-card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>الحي / المنطقة</th>
                <th>القسم الجغرافي</th>
                <th width="80">حذف</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($all_areas)): ?>
                <tr><td colspan="3" class="text-center py-4 text-muted">لا توجد مناطق مضافة حالياً.</td></tr>
              <?php else: ?>
                <?php foreach ($all_areas as $ar): ?>
                <tr>
                  <td><strong class="text-dark"><i class="fas fa-map-pin text-danger me-1"></i> <?= htmlspecialchars($ar['area_name']) ?></strong></td>
                  <td><span class="badge bg-light text-dark border px-2 py-1"><i class="fas fa-folder text-warning me-1"></i> <?= htmlspecialchars($ar['category']) ?></span></td>
                  <td>
                    <a href="areas.php?delete_id=<?= $ar['id'] ?>" class="btn btn-sm btn-light border confirm-delete" title="حذف">
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
