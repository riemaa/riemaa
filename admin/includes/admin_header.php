<?php
/**
 * هيدر لوحة التحكم والقائمة الجانبية والشريط العلوي
 */

if (!ob_get_level()) {
    ob_start();
}

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

$current_page = basename($_SERVER['PHP_SELF']);

// جلب عدد الطلبات الجديدة
try {
    $new_bookings_count = (int)$pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'new'")->fetchColumn();
} catch (Exception $e) {
    $new_bookings_count = 0;
}

$site_name = get_setting('site_name', 'شركة المثلث كلين للنظافة العامة');
$site_short_name = get_setting('site_short_name', 'المثلث كلين');
$theme_primary_color   = get_setting('theme_primary_color', '#0284C7');
$theme_secondary_color = get_setting('theme_secondary_color', '#0F172A');
$theme_accent_color    = get_setting('theme_accent_color', '#38BDF8');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>لوحة التحكم | <?= htmlspecialchars($site_name) ?></title>
  
  <!-- Bootstrap 5 RTL -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- Google Fonts Cairo -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  
  <!-- Admin Styles -->
  <link rel="stylesheet" href="/admin/assets/css/admin.css">

  <style id="admin-theme-vars">
    :root {
      --sidebar-bg: <?= htmlspecialchars($theme_secondary_color) ?> !important;
      --sidebar-active: <?= htmlspecialchars($theme_primary_color) ?> !important;
      --primary: <?= htmlspecialchars($theme_primary_color) ?> !important;
      --primary-hover: <?= htmlspecialchars($theme_secondary_color) ?> !important;
      --accent: <?= htmlspecialchars($theme_accent_color) ?> !important;
    }
    .btn-admin-primary, .sidebar-badge {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_primary_color) ?>, <?= htmlspecialchars($theme_accent_color) ?>) !important;
    }
    .sidebar-brand-icon {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_primary_color) ?>, <?= htmlspecialchars($theme_accent_color) ?>) !important;
    }
    .sidebar-link.active, .sidebar-link:hover {
      border-right-color: <?= htmlspecialchars($theme_accent_color) ?> !important;
    }
  </style>
</head>
<body>

<div class="admin-wrapper">
  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <div class="sidebar-header">
      <div class="sidebar-brand-icon"><i class="fas fa-sparkles"></i></div>
      <div class="sidebar-brand-title">
        <?= htmlspecialchars($site_short_name) ?>
        <small>لوحة الإدارة الشاملة</small>
      </div>
    </div>

    <ul class="sidebar-menu">
      <li>
        <a href="index.php" class="sidebar-link <?= $current_page == 'index.php' ? 'active' : '' ?>">
          <i class="fas fa-home"></i> الرئيسية
        </a>
      </li>
      <li>
        <a href="bookings.php" class="sidebar-link <?= $current_page == 'bookings.php' ? 'active' : '' ?>">
          <i class="fas fa-calendar-check"></i> طلبات الحجز
          <?php if ($new_bookings_count > 0): ?>
            <span class="sidebar-badge"><?= $new_bookings_count ?></span>
          <?php endif; ?>
        </a>
      </li>

      <li class="sidebar-menu-header">إعدادات الموقع والسيو</li>
      <li>
        <a href="settings.php" class="sidebar-link <?= $current_page == 'settings.php' ? 'active' : '' ?>">
          <i class="fas fa-palette"></i> الإعدادات العامة
        </a>
      </li>
      <li>
        <a href="nav.php" class="sidebar-link <?= $current_page == 'nav.php' ? 'active' : '' ?>">
          <i class="fas fa-bars-staggered"></i> قائمة الهيدر
        </a>
      </li>
      <li>
        <a href="hero.php" class="sidebar-link <?= $current_page == 'hero.php' ? 'active' : '' ?>">
          <i class="fas fa-bullhorn"></i> قسم البطل والعدادات
        </a>
      </li>
      <li>
        <a href="about.php" class="sidebar-link <?= $current_page == 'about.php' ? 'active' : '' ?>">
          <i class="fas fa-info-circle"></i> من نحن وقصة الريادة
        </a>
      </li>

      <li class="sidebar-menu-header">إدارة الأقسام والخدمات</li>
      <li>
        <a href="services.php" class="sidebar-link <?= $current_page == 'services.php' ? 'active' : '' ?>">
          <i class="fas fa-concierge-bell"></i> إدارة الخدمات
        </a>
      </li>
      <li>
        <a href="fleet.php" class="sidebar-link <?= $current_page == 'fleet.php' ? 'active' : '' ?>">
          <i class="fas fa-tools"></i> أجهزة ومعدات النظافة
        </a>
      </li>
      <li>
        <a href="whyus.php" class="sidebar-link <?= $current_page == 'whyus.php' ? 'active' : '' ?>">
          <i class="fas fa-award"></i> مميزات لماذا تختارنا
        </a>
      </li>
      <li>
        <a href="packages.php" class="sidebar-link <?= $current_page == 'packages.php' ? 'active' : '' ?>">
          <i class="fas fa-tags"></i> باقات وعروض الأسعار
        </a>
      </li>
      <li>
        <a href="gallery.php" class="sidebar-link <?= $current_page == 'gallery.php' ? 'active' : '' ?>">
          <i class="fas fa-images"></i> معرض الأعمال والصور
        </a>
      </li>
      <li>
        <a href="process_guarantees.php" class="sidebar-link <?= $current_page == 'process_guarantees.php' ? 'active' : '' ?>">
          <i class="fas fa-tasks"></i> خطوات العمل والضمانات
        </a>
      </li>
      <li>
        <a href="testimonials.php" class="sidebar-link <?= $current_page == 'testimonials.php' ? 'active' : '' ?>">
          <i class="fas fa-comments"></i> آراء وتقييمات العملاء
        </a>
      </li>
      <li>
        <a href="faqs.php" class="sidebar-link <?= $current_page == 'faqs.php' ? 'active' : '' ?>">
          <i class="fas fa-question-circle"></i> الأسئلة الشائعة
        </a>
      </li>
      <li>
        <a href="seo_guide.php" class="sidebar-link <?= $current_page == 'seo_guide.php' ? 'active' : '' ?>">
          <i class="fas fa-file-alt"></i> دليل النظافة والتعقيم
        </a>
      </li>
      <li>
        <a href="areas.php" class="sidebar-link <?= $current_page == 'areas.php' ? 'active' : '' ?>">
          <i class="fas fa-map-marked-alt"></i> مناطق وأحياء الخدمة
        </a>
      </li>

      <li class="sidebar-menu-header">الحساب والموقع</li>
      <li>
        <a href="profile.php" class="sidebar-link <?= $current_page == 'profile.php' ? 'active' : '' ?>">
          <i class="fas fa-user-shield"></i> الأمان وكلمة المرور
        </a>
      </li>
      <li>
        <a href="../index.php" target="_blank" class="sidebar-link text-warning">
          <i class="fas fa-external-link-alt text-warning"></i> معاينة الموقع الخارجي
        </a>
      </li>
      <li>
        <a href="logout.php" class="sidebar-link text-danger">
          <i class="fas fa-sign-out-alt text-danger"></i> تسجيل الخروج
        </a>
      </li>
    </ul>
  </aside>

  <!-- Main Content Area -->
  <div class="admin-main">
    <header class="admin-topbar">
      <button class="topbar-toggle" type="button"><i class="fas fa-bars"></i></button>
      <div class="fw-bold d-none d-md-block" style="color:var(--text-dark);">
        مرحباً بك، <span style="color:var(--primary);"><?= htmlspecialchars($_SESSION['admin_username'] ?? 'المدير') ?></span> 🌟
      </div>
      <div class="topbar-user">
        <a href="../index.php" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
          <i class="fas fa-eye me-1"></i> عرض الموقع
        </a>
        <a href="profile.php" class="btn btn-sm btn-light rounded-pill px-3 border">
          <i class="fas fa-user-cog me-1"></i> حسابي
        </a>
        <a href="logout.php" class="btn btn-sm btn-danger rounded-pill px-3">
          <i class="fas fa-sign-out-alt me-1"></i> خروج
        </a>
      </div>
    </header>

    <main class="admin-content">
      <?php 
        $flash_success = $_SESSION['flash_success'] ?? ($_COOKIE['flash_success'] ?? null);
        $flash_error   = $_SESSION['flash_error'] ?? ($_COOKIE['flash_error'] ?? null);
        if (isset($_COOKIE['flash_success'])) setcookie('flash_success', '', time() - 3600, '/');
        if (isset($_COOKIE['flash_error'])) setcookie('flash_error', '', time() - 3600, '/');
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
      ?>
      <?php if (!empty($flash_success)): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
          <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($flash_success) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?php if (!empty($flash_error)): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i> <?= htmlspecialchars($flash_error) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
