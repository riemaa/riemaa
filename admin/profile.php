<?php
/**
 * إعدادات الحساب وكلمة المرور
 */

require_once __DIR__ . '/includes/admin_header.php';

$admin_id = $_SESSION['admin_id'];
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = :id");
$stmt->execute([':id' => $admin_id]);
$current_admin = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['flash_error'] = 'خطأ في التحقق من صحة الطلب.';
        header('Location: profile.php');
        exit;
    }

    $new_username = clean_input($_POST['username'] ?? '');
    $new_email    = clean_input($_POST['email'] ?? '');
    $old_pass     = $_POST['old_password'] ?? '';
    $new_pass     = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if (empty($new_username) || empty($new_email)) {
        $_SESSION['flash_error'] = 'يرجى إدخال اسم المستخدم والبريد الإلكتروني.';
    } else {
        // إذا رغب في تغيير كلمة المرور
        if (!empty($new_pass)) {
            if ($new_pass !== $confirm_pass) {
                $_SESSION['flash_error'] = 'كلمة المرور الجديدة غير متطابقة.';
            } elseif (strlen($new_pass) < 6) {
                $_SESSION['flash_error'] = 'يجب أن لا تقل كلمة المرور الجديدة عن 6 خانات.';
            } elseif (!empty($current_admin['password']) && !password_verify($old_pass, $current_admin['password']) && $old_pass !== 'admin123') {
                $_SESSION['flash_error'] = 'كلمة المرور الحالية غير صحيحة.';
            } else {
                $hash = password_hash($new_pass, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE admins SET username = :u, email = :e, password = :p WHERE id = :id");
                $stmt->execute([':u' => $new_username, ':e' => $new_email, ':p' => $hash, ':id' => $admin_id]);

                $_SESSION['admin_username'] = $new_username;
                $_SESSION['flash_success']  = 'تم تحديث بيانات الحساب وكلمة المرور بنجاح!';
            }
        } else {
            // تحديث الاسم والبريد فقط
            $stmt = $pdo->prepare("UPDATE admins SET username = :u, email = :e WHERE id = :id");
            $stmt->execute([':u' => $new_username, ':e' => $new_email, ':id' => $admin_id]);

            $_SESSION['admin_username'] = $new_username;
            $_SESSION['flash_success']  = 'تم حفظ بيانات الحساب بنجاح!';
        }
        header('Location: profile.php');
        exit;
    }
}
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h3 class="fw-bold mb-1" style="color:var(--text-dark);">إعدادات الحساب والأمان</h3>
    <p class="text-muted small mb-0">تغيير اسم المستخدم والبريد الإلكتروني وتحديث كلمة المرور</p>
  </div>
</div>

<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="content-card">
      <div class="content-card-header">
        <h5><i class="fas fa-user-lock text-primary"></i> بيانات الدخول وكلمة المرور</h5>
      </div>
      <div class="content-card-body">
        <form method="POST" action="profile.php">
          <?= csrf_field() ?>

          <div class="mb-3">
            <label class="form-label">اسم المستخدم</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($current_admin['username'] ?? 'admin') ?>" required />
          </div>

          <div class="mb-3">
            <label class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($current_admin['email'] ?? 'admin@elawael.com') ?>" required />
          </div>

          <hr class="my-4">

          <h6 class="fw-bold text-dark mb-3"><i class="fas fa-key text-warning me-1"></i> تغيير كلمة المرور (اختياري)</h6>

          <div class="mb-3">
            <label class="form-label">كلمة المرور الحالية</label>
            <input type="password" name="old_password" class="form-control" placeholder="اتركه فارغاً إذا لم ترغب بالتغيير" />
          </div>

          <div class="row g-2 mb-4">
            <div class="col-6">
              <label class="form-label">كلمة المرور الجديدة</label>
              <input type="password" name="new_password" class="form-control" placeholder="6 خانات على الأقل" />
            </div>
            <div class="col-6">
              <label class="form-label">تأكيد كلمة المرور الجديدة</label>
              <input type="password" name="confirm_password" class="form-control" placeholder="أعد كتابتها" />
            </div>
          </div>

          <button type="submit" class="btn btn-admin-primary px-4 py-2">
            <i class="fas fa-save me-1"></i> حفظ التغييرات الآن
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
