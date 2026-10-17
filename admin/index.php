<?php
/**
 * لوحة التحكم الرئيسية - الإحصائيات ونظرة عامة والطلبات الحديثة
 */

require_once __DIR__ . '/includes/admin_header.php';

// جلب الإحصائيات
try {
    $total_bookings = (int)$pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    $new_bookings   = (int)$pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'new'")->fetchColumn();
    $total_services = (int)$pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
    $total_gallery  = (int)$pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();

    // أحدث 8 طلبات حجز
    $stmt = $pdo->query("SELECT * FROM bookings ORDER BY id DESC LIMIT 8");
    $recent_bookings = $stmt->fetchAll();
} catch (Exception $e) {
    $total_bookings = $new_bookings = $total_services = $total_gallery = 0;
    $recent_bookings = [];
}
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h2 class="fw-bold mb-1" style="color:var(--text-dark);">نظرة عامة على الموقع</h2>
    <p class="text-muted mb-0 small">مرحباً بك في لوحة الإدارة لموقع <?= htmlspecialchars($site_name) ?></p>
  </div>
  <a href="settings.php" class="btn btn-admin-primary">
    <i class="fas fa-cog me-1"></i> الإعدادات العامة
  </a>
</div>

<!-- Stats Row -->
<div class="row g-4 mb-4">
  <div class="col-xl-3 col-sm-6">
    <div class="stat-card">
      <div class="stat-icon warning"><i class="fas fa-bell"></i></div>
      <div class="stat-info">
        <h3><?= $new_bookings ?></h3>
        <p>طلبات حجز جديدة</p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6">
    <div class="stat-card">
      <div class="stat-icon primary"><i class="fas fa-calendar-check"></i></div>
      <div class="stat-info">
        <h3><?= $total_bookings ?></h3>
        <p>إجمالي طلبات الحجز</p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6">
    <div class="stat-card">
      <div class="stat-icon success"><i class="fas fa-concierge-bell"></i></div>
      <div class="stat-info">
        <h3><?= $total_services ?></h3>
        <p>الخدمات المتاحة</p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6">
    <div class="stat-card">
      <div class="stat-icon danger"><i class="fas fa-images"></i></div>
      <div class="stat-info">
        <h3><?= $total_gallery ?></h3>
        <p>صور المعرض الميداني</p>
      </div>
    </div>
  </div>
</div>

<!-- Quick Links & Actions -->
<div class="content-card mb-4">
  <div class="content-card-header">
    <h5><i class="fas fa-bolt text-warning"></i> وصول سريع وإجراءات فورية</h5>
  </div>
  <div class="content-card-body">
    <div class="row g-3">
      <div class="col-md-3 col-6">
        <a href="settings.php" class="btn btn-light border w-100 py-3 text-center d-block rounded-3 shadow-sm text-decoration-none">
          <i class="fas fa-phone-alt fa-2x text-primary d-block mb-2"></i>
          <span class="fw-bold d-block text-dark small">تعديل الأرقام والواتساب</span>
        </a>
      </div>
      <div class="col-md-3 col-6">
        <a href="hero.php" class="btn btn-light border w-100 py-3 text-center d-block rounded-3 shadow-sm text-decoration-none">
          <i class="fas fa-heading fa-2x text-warning d-block mb-2"></i>
          <span class="fw-bold d-block text-dark small">تعديل العنوان الرئيسي</span>
        </a>
      </div>
      <div class="col-md-3 col-6">
        <a href="packages.php" class="btn btn-light border w-100 py-3 text-center d-block rounded-3 shadow-sm text-decoration-none">
          <i class="fas fa-tags fa-2x text-success d-block mb-2"></i>
          <span class="fw-bold d-block text-dark small">تعديل باقات الأسعار</span>
        </a>
      </div>
      <div class="col-md-3 col-6">
        <a href="gallery.php" class="btn btn-light border w-100 py-3 text-center d-block rounded-3 shadow-sm text-decoration-none">
          <i class="fas fa-upload fa-2x text-info d-block mb-2"></i>
          <span class="fw-bold d-block text-dark small">إضافة صور للمعرض</span>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Recent Bookings Table -->
<div class="content-card">
  <div class="content-card-header">
    <h5><i class="fas fa-clipboard-list text-primary"></i> أحدث طلبات الحجز المستلمة من الزوار</h5>
    <a href="bookings.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">عرض جميع الطلبات</a>
  </div>
  <div class="content-card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>اسم العميل</th>
            <th>رقم الجوال</th>
            <th>الخدمة المطلوبة</th>
            <th>المدينة / العنوان</th>
            <th>تاريخ الطلب</th>
            <th>الحالة</th>
            <th>الإجراء</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recent_bookings)): ?>
            <tr>
              <td colspan="8" class="text-center py-4 text-muted">لا توجد طلبات حجز مسجلة حتى الآن.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($recent_bookings as $b): 
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
              <td class="fw-bold"><?= htmlspecialchars($b['client_name']) ?></td>
              <td>
                <a href="tel:<?= htmlspecialchars($b['phone']) ?>" class="badge bg-light text-dark border text-decoration-none px-2 py-1">
                  <i class="fas fa-phone-alt text-primary me-1"></i> <?= htmlspecialchars($b['phone']) ?>
                </a>
              </td>
              <td><span class="badge bg-light text-secondary border"><?= htmlspecialchars($b['service_type'] ?: 'تنظيف شامل') ?></span></td>
              <td class="small">
                <?= htmlspecialchars($b['from_area'] ?: '—') ?>
                <?php if (!empty($b['to_area'])): ?>
                  (<?= htmlspecialchars($b['to_area']) ?>)
                <?php endif; ?>
              </td>
              <td class="small text-muted"><?= date('Y/m/d H:i', strtotime($b['created_at'])) ?></td>
              <td><span class="badge <?= $st['class'] ?>"><?= $st['text'] ?></span></td>
              <td>
                <a href="bookings.php?edit_id=<?= $b['id'] ?>" class="btn btn-sm btn-light border rounded-pill px-2" title="تفاصيل">
                  <i class="fas fa-eye text-primary"></i>
                </a>
                <a href="<?= htmlspecialchars(format_whatsapp_link($b['phone'], 'مرحباً ' . $b['client_name'] . '، نتواصل معك بخصوص طلبك لخدمات النظافة والتعقيم من ' . $site_short_name)) ?>" class="btn btn-sm btn-success rounded-pill px-2" target="_blank" title="واتساب">
                  <i class="fab fa-whatsapp"></i>
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

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
