<?php
/**
 * إدارة ومتابعة طلبات الحجز المستلمة من الزوار
 */

require_once __DIR__ . '/includes/admin_header.php';

// تحديث حالة الطلب
if (isset($_GET['set_status']) && isset($_GET['id'])) {
    $status = clean_input($_GET['set_status']);
    $b_id   = (int)$_GET['id'];
    if (in_array($status, ['new', 'contacted', 'completed', 'cancelled'])) {
        $stmt = $pdo->prepare("UPDATE bookings SET status = :s WHERE id = :id");
        $stmt->execute([':s' => $status, ':id' => $b_id]);
        $_SESSION['flash_success'] = 'تم تحديث حالة الطلب بنجاح.';
    }
    header('Location: bookings.php');
    exit;
}

// حذف طلب
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = :id");
    $stmt->execute([':id' => $del_id]);
    $_SESSION['flash_success'] = 'تم حذف طلب الحجز.';
    header('Location: bookings.php');
    exit;
}

// تصفية وبحث
$filter_status = isset($_GET['status']) ? clean_input($_GET['status']) : '';
$search_query  = isset($_GET['q']) ? clean_input($_GET['q']) : '';

$sql = "SELECT * FROM bookings WHERE 1=1";
$params = [];

if (!empty($filter_status)) {
    $sql .= " AND status = :status";
    $params[':status'] = $filter_status;
}

if (!empty($search_query)) {
    $sql .= " AND (client_name LIKE :q OR phone LIKE :q2 OR from_area LIKE :q3 OR to_area LIKE :q4)";
    $params[':q'] = "%$search_query%";
    $params[':q2'] = "%$search_query%";
    $params[':q3'] = "%$search_query%";
    $params[':q4'] = "%$search_query%";
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">طلبات الحجز</h3>
    <p class="text-muted small mb-0">متابعة طلبات حجز خدمات النظافة والتعقيم والتواصل مع العملاء</p>
  </div>
  <div class="d-flex gap-2">
    <a href="bookings.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3 <?= empty($filter_status) ? 'active' : '' ?>">الكل (<?= count($bookings) ?>)</a>
    <a href="bookings.php?status=new" class="btn btn-sm btn-danger rounded-pill px-3 <?= $filter_status == 'new' ? 'active' : '' ?>">الجديدة</a>
    <a href="bookings.php?status=contacted" class="btn btn-sm btn-warning rounded-pill px-3 <?= $filter_status == 'contacted' ? 'active' : '' ?>">تم التواصل</a>
    <a href="bookings.php?status=completed" class="btn btn-sm btn-success rounded-pill px-3 <?= $filter_status == 'completed' ? 'active' : '' ?>">مكتملة</a>
  </div>
</div>

<!-- Search Bar -->
<div class="content-card mb-4">
  <div class="content-card-body py-3">
    <form method="GET" action="bookings.php" class="row g-2 align-items-center">
      <?php if (!empty($filter_status)): ?>
        <input type="hidden" name="status" value="<?= htmlspecialchars($filter_status) ?>">
      <?php endif; ?>
      <div class="col-md-9">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
          <input type="text" name="q" class="form-control" placeholder="ابحث باسم العميل أو رقم الجوال أو الحي..." value="<?= htmlspecialchars($search_query) ?>">
        </div>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-admin-primary w-100">بحث فوري</button>
      </div>
    </form>
  </div>
</div>

<!-- Bookings Table -->
<div class="content-card">
  <div class="content-card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>اسم العميل</th>
            <th>رقم الجوال</th>
            <th>نوع الخدمة</th>
            <th>التاريخ المفضل</th>
            <th>المدينة / الحي والعقار</th>
            <th>التفاصيل والملاحظات</th>
            <th>تاريخ الطلب</th>
            <th>الحالة</th>
            <th width="140">الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($bookings)): ?>
            <tr>
              <td colspan="10" class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x d-block mb-3 text-secondary"></i>
                لا توجد طلبات حجز مطابقة حالياً.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($bookings as $b): 
              $status_map = [
                'new'       => ['text' => 'جديد', 'class' => 'bg-danger'],
                'contacted' => ['text' => 'تم التواصل', 'class' => 'bg-warning text-dark'],
                'completed' => ['text' => 'مكتمل', 'class' => 'bg-success'],
                'cancelled' => ['text' => 'ملغي', 'class' => 'bg-secondary']
              ];
              $st = $status_map[$b['status']] ?? ['text' => $b['status'], 'class' => 'bg-info'];
            ?>
            <tr>
              <td><span class="fw-bold text-muted">#<?= $b['id'] ?></span></td>
              <td><strong class="text-dark"><?= htmlspecialchars($b['client_name']) ?></strong></td>
              <td>
                <a href="tel:<?= htmlspecialchars($b['phone']) ?>" class="badge bg-light text-dark border text-decoration-none px-2 py-1">
                  <i class="fas fa-phone-alt text-primary me-1"></i> <?= htmlspecialchars($b['phone']) ?>
                </a>
              </td>
              <td><span class="badge bg-light text-primary border"><?= htmlspecialchars($b['service_type'] ?: 'تنظيف شامل') ?></span></td>
              <td><small class="text-muted"><?= htmlspecialchars($b['moving_date'] ?: 'غير محدد') ?></small></td>
              <td>
                <small class="d-block text-dark">
                  <i class="fas fa-city text-primary me-1"></i><?= htmlspecialchars($b['from_area'] ?: '—') ?>
                  <?php if (!empty($b['to_area'])): ?>
                    <br><i class="fas fa-home text-muted me-1"></i><?= htmlspecialchars($b['to_area']) ?>
                  <?php endif; ?>
                </small>
              </td>
              <td>
                <small class="text-muted text-truncate d-inline-block" style="max-width:200px;" title="<?= htmlspecialchars($b['details'] ?: '') ?>">
                  <?= htmlspecialchars($b['details'] ?: 'لا توجد ملاحظات') ?>
                </small>
              </td>
              <td><small class="text-muted"><?= date('Y/m/d H:i', strtotime($b['created_at'])) ?></small></td>
              <td>
                <div class="dropdown">
                  <button class="btn btn-sm <?= $st['class'] ?> dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <?= $st['text'] ?>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="bookings.php?id=<?= $b['id'] ?>&set_status=new">🔴 جديد</a></li>
                    <li><a class="dropdown-item" href="bookings.php?id=<?= $b['id'] ?>&set_status=contacted">🟡 تم التواصل</a></li>
                    <li><a class="dropdown-item" href="bookings.php?id=<?= $b['id'] ?>&set_status=completed">🟢 مكتمل</a></li>
                    <li><a class="dropdown-item" href="bookings.php?id=<?= $b['id'] ?>&set_status=cancelled">⚪ ملغي</a></li>
                  </ul>
                </div>
              </td>
              <td>
                <div class="d-flex gap-1">
                  <a href="<?= htmlspecialchars(format_whatsapp_link($b['phone'], 'مرحباً ' . $b['client_name'] . '، نتواصل معك بخصوص طلبك لخدمات النظافة والتعقيم من ' . $site_short_name)) ?>" class="btn btn-sm btn-success rounded-pill" target="_blank" title="محادثة واتساب">
                    <i class="fab fa-whatsapp"></i>
                  </a>
                  <a href="tel:<?= htmlspecialchars($b['phone']) ?>" class="btn btn-sm btn-primary rounded-pill" title="اتصال مباشر">
                    <i class="fas fa-phone-alt"></i>
                  </a>
                  <a href="bookings.php?delete_id=<?= $b['id'] ?>" class="btn btn-sm btn-light border confirm-delete rounded-pill text-danger" title="حذف">
                    <i class="fas fa-trash-alt"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
