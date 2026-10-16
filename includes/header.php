<?php
/**
 * رأس صفحة الهبوط ويتضمن وسوم السيو الشاملة وSchema.org وشريط التنقل
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$site_name         = get_setting('site_name', 'شركة الأوائل لنقل العفش بالرياض');
$site_short_name   = get_setting('site_short_name', 'الأوائل');
$site_tagline      = get_setting('site_tagline', 'لنقل وتغليف وتخزين العفش والأثاث');
$site_logo         = get_setting('site_logo', '');
$site_icon         = get_setting('site_icon', 'fas fa-truck-moving');
$phone_primary     = get_setting('phone_primary', '0554091835');
$phone_secondary   = get_setting('phone_secondary', '0595084006');
$whatsapp_number   = get_setting('whatsapp_number', '966554091835');
$meta_title        = get_setting('meta_title', 'شركة الأوائل لنقل العفش بالرياض | فك وتركيب وتغليف وأوناش رفع 0554091835');
$meta_description  = get_setting('meta_description', 'شركة الأوائل لنقل العفش بالرياض - أفضل شركة نقل اثاث داخل وخارج الرياض مع الفك والتركيب والتغليف وأوناش الرفع الهيدروليكية والتخزين الآمن. اتصل الآن 0554091835 أو 0595084006');
$meta_keywords     = get_setting('meta_keywords', 'نقل عفش الرياض, شركة نقل اثاث الرياض, دينا نقل عفش الرياض, ونش رفع اثاث الرياض, فك وتركيب غرف نوم, تغليف عفش الرياض, تخزين اثاث الرياض, افضل شركة نقل عفش بالرياض, اسعار نقل العفش بالرياض, الأوائل نقل عفش');
$meta_author       = get_setting('meta_author', 'شركة الأوائل لنقل وتغليف الأثاث');
$meta_robots       = get_setting('meta_robots', 'index, follow');
$canonical_url     = get_setting('canonical_url', SITE_URL);
$header_scripts    = get_setting('header_scripts', '');

$og_image          = asset_url('assets/images/1.jpg');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- ===== SEO Meta Tags ===== -->
  <title><?= htmlspecialchars($meta_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($meta_description) ?>" />
  <meta name="keywords" content="<?= htmlspecialchars($meta_keywords) ?>" />
  <meta name="author" content="<?= htmlspecialchars($meta_author) ?>" />
  <meta name="robots" content="<?= htmlspecialchars($meta_robots) ?>" />
  <meta name="language" content="Arabic" />
  <meta name="geo.region" content="SA-01" />
  <meta name="geo.placename" content="الرياض" />

  <!-- Open Graph -->
  <meta property="og:title" content="<?= htmlspecialchars($meta_title) ?>" />
  <meta property="og:description" content="<?= htmlspecialchars($meta_description) ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:locale" content="ar_SA" />
  <meta property="og:url" content="<?= htmlspecialchars($canonical_url) ?>" />
  <meta property="og:image" content="<?= htmlspecialchars($og_image) ?>" />

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= htmlspecialchars($meta_title) ?>" />
  <meta name="twitter:description" content="<?= htmlspecialchars($meta_description) ?>" />
  <meta name="twitter:image" content="<?= htmlspecialchars($og_image) ?>" />

  <!-- Canonical -->
  <link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>" />

  <!-- Schema.org JSON-LD (MovingCompany / LocalBusiness) -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "MovingCompany",
    "name": "<?= addslashes($site_name) ?>",
    "telephone": ["<?= addslashes($phone_primary) ?>", "<?= addslashes($phone_secondary) ?>"],
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "الرياض",
      "addressCountry": "SA"
    },
    "description": "<?= addslashes($meta_description) ?>",
    "areaServed": "الرياض",
    "priceRange": "$$"
  }
  </script>

  <!-- Bootstrap 5 RTL -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" />
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <!-- Google Fonts Arabic (Cairo & Tajawal) -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet" />

  <!-- Main Luxury Stylesheet -->
  <link rel="stylesheet" href="assets/css/style.css" />

  <?php if (!empty($header_scripts)): ?>
    <?= $header_scripts ?>
  <?php endif; ?>
</head>
<body>

<!-- ============================================================
     NAVBAR
============================================================ -->
<nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand" href="#hero">
      <div class="brand-icon">
        <?php if (!empty($site_logo)): ?>
          <img src="<?= htmlspecialchars(asset_url($site_logo)) ?>" alt="<?= htmlspecialchars($site_name) ?>" />
        <?php else: ?>
          <i class="<?= htmlspecialchars($site_icon) ?>"></i>
        <?php endif; ?>
      </div>
      <div class="brand-text">
        <span><?= htmlspecialchars($site_short_name) ?></span>
        <span><?= htmlspecialchars($site_tagline) ?></span>
      </div>
    </a>

    <button class="navbar-toggler border-0" type="button"
      data-bs-toggle="offcanvas" data-bs-target="#navOffcanvas" aria-controls="navOffcanvas">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto gap-1">
        <li class="nav-item"><a class="nav-link" href="#services">خدماتنا</a></li>
        <li class="nav-item"><a class="nav-link" href="#fleet">الأسطول والمعدات</a></li>
        <li class="nav-item"><a class="nav-link" href="#whyus">لماذا نحن</a></li>
        <li class="nav-item"><a class="nav-link" href="#works">معرض الأعمال</a></li>
        <li class="nav-item"><a class="nav-link" href="#packages">باقات الأسعار</a></li>
        <li class="nav-item"><a class="nav-link" href="#areas">مناطق الخدمة</a></li>
        <li class="nav-item"><a class="nav-link" href="#process">خطوات العمل</a></li>
        <li class="nav-item"><a class="nav-link" href="#guarantees">الضمانات</a></li>
        <li class="nav-item"><a class="nav-link" href="#faq">الأسئلة الشائعة</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">تواصل معنا</a></li>
      </ul>
      <a href="tel:<?= htmlspecialchars(format_phone_tel($phone_primary)) ?>" class="btn nav-call-btn ms-3">
        <i class="fas fa-phone-alt"></i> <?= htmlspecialchars($phone_primary) ?>
      </a>
    </div>
  </div>
</nav>

<!-- Mobile Offcanvas -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="navOffcanvas">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title fw-bold">
      <i class="<?= htmlspecialchars($site_icon) ?> me-2 text-warning"></i> <?= htmlspecialchars($site_name) ?>
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="navbar-nav gap-2">
      <li class="nav-item"><a class="nav-link fw-bold" href="#services"   data-bs-dismiss="offcanvas"><i class="fas fa-concierge-bell me-2 text-warning"></i>خدماتنا المتميزة</a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#fleet"      data-bs-dismiss="offcanvas"><i class="fas fa-truck me-2 text-warning"></i>أسطولنا والمعدات</a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#whyus"      data-bs-dismiss="offcanvas"><i class="fas fa-crown me-2 text-warning"></i>لماذا تختارنا</a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#works"      data-bs-dismiss="offcanvas"><i class="fas fa-camera-retro me-2 text-warning"></i>معرض الأعمال </a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#packages"   data-bs-dismiss="offcanvas"><i class="fas fa-tags me-2 text-warning"></i>باقات الأسعار والعروض</a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#areas"      data-bs-dismiss="offcanvas"><i class="fas fa-map-marked-alt me-2 text-warning"></i>مناطق الخدمة بالرياض</a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#process"    data-bs-dismiss="offcanvas"><i class="fas fa-tasks me-2 text-warning"></i>مراحل وخطوات النقل</a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#guarantees" data-bs-dismiss="offcanvas"><i class="fas fa-shield-alt me-2 text-warning"></i>ميثاق الضمانات 100%</a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#faq"        data-bs-dismiss="offcanvas"><i class="fas fa-question-circle me-2 text-warning"></i>الأسئلة الشائعة</a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#contact"    data-bs-dismiss="offcanvas"><i class="fas fa-headset me-2 text-warning"></i>تواصل معنا وحجز موعد</a></li>
    </ul>
    <div class="mt-4 d-flex flex-column gap-2">
      <a href="tel:<?= htmlspecialchars(format_phone_tel($phone_primary)) ?>" class="btn btn-hero-primary justify-content-center">
        <i class="fas fa-phone-alt me-2"></i><?= htmlspecialchars($phone_primary) ?>
      </a>
      <a href="<?= htmlspecialchars(format_whatsapp_link($whatsapp_number)) ?>" class="btn" style="background:#25D366;color:white;border-radius:50px;font-weight:800;" target="_blank">
        <i class="fab fa-whatsapp me-2"></i>محادثة واتساب مباشرة
      </a>
    </div>
  </div>
</div>
