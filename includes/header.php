<?php
/**
 * رأس صفحة الهبوط ويتضمن وسوم السيو الشاملة وSchema.org وشريط التنقل
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$site_name         = get_setting('site_name', 'شركة الأوائل لنقل العفش بالدمام');
$site_short_name   = get_setting('site_short_name', 'الأوائل');
$site_tagline      = get_setting('site_tagline', 'لنقل وتغليف وتخزين العفش والأثاث');
$site_logo         = get_setting('site_logo', '');
$site_icon         = get_setting('site_icon', 'fas fa-truck-moving');
$phone_primary     = get_setting('phone_primary', '0554091835');
$phone_secondary   = get_setting('phone_secondary', '0595084006');
$whatsapp_number   = get_setting('whatsapp_number', '966554091835');
$meta_title        = get_setting('meta_title', 'شركة الأوائل لنقل العفش بالدمام | فك وتركيب وتغليف وأوناش رفع 0554091835');
$meta_description  = get_setting('meta_description', 'شركة الأوائل لنقل العفش بالدمام - أفضل شركة نقل اثاث بالدمام والقطيف والخبر والظهران والجبيل مع الفك والتركيب والتغليف وأوناش الرفع الهيدروليكية والتخزين الآمن. اتصل الآن 0554091835 أو 0595084006');
$meta_keywords     = get_setting('meta_keywords', 'نقل عفش الدمام, شركة نقل اثاث بالدمام, نقل عفش الخبر, نقل عفش القطيف, نقل عفش الظهران, نقل عفش الجبيل, دينا نقل عفش الدمام, ونش رفع اثاث الدمام, فك وتركيب غرف نوم, تغليف عفش بالدمام, شركة نقل عفش بالمنطقة الشرقية, الأوائل نقل عفش');
$meta_author       = get_setting('meta_author', 'شركة الأوائل لنقل وتغليف الأثاث');
$meta_robots       = get_setting('meta_robots', 'index, follow');
$canonical_url     = get_setting('canonical_url', SITE_URL);
$header_scripts    = get_setting('header_scripts', '');

// ألوان الهوية والمشروع
$theme_primary_color   = get_setting('theme_primary_color', '#8B5A2B');
$theme_secondary_color = get_setting('theme_secondary_color', '#341D13');
$theme_accent_color    = get_setting('theme_accent_color', '#D4A373');

// مسميات نصوص القائمة العلوية
$nav_about      = get_setting('nav_about', 'من نحن');
$nav_services   = get_setting('nav_services', 'خدماتنا');
$nav_fleet      = get_setting('nav_fleet', 'الأسطول والمعدات');
$nav_whyus      = get_setting('nav_whyus', 'لماذا نحن');
$nav_works      = get_setting('nav_works', 'معرض الأعمال');
$nav_packages   = get_setting('nav_packages', 'باقات الأسعار');
$nav_areas      = get_setting('nav_areas', 'مناطق الخدمة');
$nav_process    = get_setting('nav_process', 'خطوات العمل');
$nav_guarantees = get_setting('nav_guarantees', 'الضمانات');
$nav_faq        = get_setting('nav_faq', 'الأسئلة الشائعة');
$nav_contact    = get_setting('nav_contact', 'تواصل معنا');
$nav_call_btn   = get_setting('nav_call_btn', $phone_primary);

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
  <meta name="geo.region" content="SA-04" />
  <meta name="geo.placename" content="الدمام" />

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
      "addressLocality": "الدمام",
      "addressCountry": "SA"
    },
    "description": "<?= addslashes($meta_description) ?>",
    "areaServed": "الدمام والمنطقة الشرقية",
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

  <!-- PWA Manifest & App Icons -->
  <link rel="manifest" href="/manifest.json" />
  <meta name="theme-color" content="<?= htmlspecialchars($theme_primary_color) ?>" />
  <meta name="mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <meta name="apple-mobile-web-app-title" content="<?= htmlspecialchars($site_short_name) ?>" />
  <link rel="apple-touch-icon" href="/assets/images/1.jpg" />

  <!-- Dynamic Theme Colors -->
  <?php
    $rgb_primary   = hex2rgb_css($theme_primary_color);
    $rgb_secondary = hex2rgb_css($theme_secondary_color);
    $rgb_accent    = hex2rgb_css($theme_accent_color);
  ?>
  <style id="custom-theme-variables">
    :root {
      --primary: <?= htmlspecialchars($theme_primary_color) ?> !important;
      --primary-rgb: <?= $rgb_primary ?> !important;
      --primary-dark: <?= htmlspecialchars($theme_secondary_color) ?> !important;
      --primary-light: <?= htmlspecialchars($theme_accent_color) ?> !important;
      --secondary: <?= htmlspecialchars($theme_secondary_color) ?> !important;
      --secondary-rgb: <?= $rgb_secondary ?> !important;
      --wood-dark: <?= htmlspecialchars($theme_secondary_color) ?> !important;
      --wood-walnut: <?= htmlspecialchars($theme_secondary_color) ?> !important;
      --wood-mocha: <?= htmlspecialchars($theme_secondary_color) ?> !important;
      --wood-chestnut: <?= htmlspecialchars($theme_primary_color) ?> !important;
      --wood-roasted: <?= htmlspecialchars($theme_secondary_color) ?> !important;
      --wood-caramel: <?= htmlspecialchars($theme_primary_color) ?> !important;
      --accent: <?= htmlspecialchars($theme_accent_color) ?> !important;
      --accent-gold: <?= htmlspecialchars($theme_accent_color) ?> !important;
      --accent-bright: <?= htmlspecialchars($theme_accent_color) ?> !important;
      --accent-rgb: <?= $rgb_accent ?> !important;
      --dark: #1e293b !important;
      --text-muted: #64748b !important;
      --shadow-sm: 0 6px 20px rgba(<?= $rgb_secondary ?>, 0.08) !important;
      --shadow-md: 0 12px 35px rgba(<?= $rgb_secondary ?>, 0.14) !important;
      --shadow-lg: 0 20px 60px rgba(<?= $rgb_secondary ?>, 0.20) !important;
      --shadow-hover: 0 28px 70px rgba(<?= $rgb_secondary ?>, 0.28) !important;
      --gold-glow: rgba(<?= $rgb_accent ?>, 0.45) !important;
      --beige-warm: rgba(<?= $rgb_primary ?>, 0.07) !important;
      --beige-light: rgba(<?= $rgb_primary ?>, 0.03) !important;
      --beige-sand: rgba(<?= $rgb_primary ?>, 0.12) !important;
    }

    /* 1. Hero Background */
    #hero {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_secondary_color) ?> 0%, rgba(<?= $rgb_secondary ?>, 0.95) 40%, rgba(<?= $rgb_primary ?>, 0.85) 75%, <?= htmlspecialchars($theme_secondary_color) ?> 100%) !important;
      overflow: hidden !important;
    }

    /* 2. Dark Sections Background (#whyus, #packages, #testimonials, #seo1) */
    .bg-dark-wood {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_secondary_color) ?> 0%, rgba(<?= $rgb_secondary ?>, 0.92) 50%, rgba(0,0,0,0.85) 100%) !important;
    }

    /* 3. Terracotta Gradient Section Background (#areas) */
    .bg-terracotta {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_primary_color) ?> 0%, <?= htmlspecialchars($theme_secondary_color) ?> 100%) !important;
    }

    /* 4. Warm / Light Sections Background (#services, #works, #guarantees, #contact) */
    .bg-warm-beige {
      background: linear-gradient(180deg, rgba(<?= $rgb_primary ?>, 0.035) 0%, rgba(<?= $rgb_primary ?>, 0.075) 100%) !important;
      border-top: 1px solid rgba(<?= $rgb_primary ?>, 0.15) !important;
      border-bottom: 1px solid rgba(<?= $rgb_primary ?>, 0.15) !important;
    }

    /* 5. Footer Background */
    footer {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_secondary_color) ?> 0%, #080A0E 100%) !important;
      border-top: 3px solid <?= htmlspecialchars($theme_primary_color) ?> !important;
    }

    /* 6. Wave Dividers */
    .wave-fill-dark svg path {
      fill: <?= htmlspecialchars($theme_secondary_color) ?> !important;
    }
    .wave-fill-primary svg path {
      fill: <?= htmlspecialchars($theme_primary_color) ?> !important;
    }
    .wave-fill-beige svg path {
      fill: rgba(<?= $rgb_primary ?>, 0.075) !important;
    }
    .wave-bg-dark {
      background: <?= htmlspecialchars($theme_secondary_color) ?> !important;
    }
    .wave-bg-terracotta {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_primary_color) ?> 0%, <?= htmlspecialchars($theme_secondary_color) ?> 100%) !important;
    }
    .wave-bg-beige {
      background: rgba(<?= $rgb_primary ?>, 0.075) !important;
    }

    /* 7. Buttons & Interactive Elements */
    .btn-hero-primary, .btn-calc, .nav-call-btn, .btn-login, .float-btn-pwa, .btn-submit, .btn-pwa-install {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_primary_color) ?>, <?= htmlspecialchars($theme_accent_color) ?>) !important;
    }
    .btn-hero-primary:hover, .btn-calc:hover, .btn-submit:hover, .btn-pwa-install:hover {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_accent_color) ?>, <?= htmlspecialchars($theme_primary_color) ?>) !important;
      box-shadow: 0 10px 25px rgba(<?= $rgb_primary ?>, 0.4) !important;
    }
    .btn-hero-secondary {
      border-color: <?= htmlspecialchars($theme_accent_color) ?> !important;
      color: <?= htmlspecialchars($theme_accent_color) ?> !important;
    }
    .btn-hero-secondary:hover {
      background: <?= htmlspecialchars($theme_accent_color) ?> !important;
      color: <?= htmlspecialchars($theme_secondary_color) ?> !important;
    }

    /* 8. Highlights, Icons & Badges */
    .highlight, .section-subtitle-tag i, .brand-text span:first-child, .stars {
      color: <?= htmlspecialchars($theme_accent_color) ?> !important;
    }
    .section-subtitle-tag {
      background: rgba(<?= $rgb_accent ?>, 0.15) !important;
      border-color: rgba(<?= $rgb_accent ?>, 0.4) !important;
      color: <?= htmlspecialchars($theme_primary_color) ?> !important;
    }
    .section-subtitle-tag.tag-light {
      color: <?= htmlspecialchars($theme_accent_color) ?> !important;
      background: rgba(255,255,255,0.1) !important;
      border-color: rgba(255,255,255,0.25) !important;
    }
    .hero-badge {
      background: rgba(<?= $rgb_accent ?>, 0.18) !important;
      border-color: rgba(<?= $rgb_accent ?>, 0.5) !important;
      color: <?= htmlspecialchars($theme_accent_color) ?> !important;
    }
    .brand-icon {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_secondary_color) ?>, <?= htmlspecialchars($theme_primary_color) ?>) !important;
      border-color: <?= htmlspecialchars($theme_accent_color) ?> !important;
      box-shadow: 0 6px 20px rgba(<?= $rgb_primary ?>, 0.35) !important;
    }
    #mainNav {
      border-image: linear-gradient(90deg, <?= htmlspecialchars($theme_secondary_color) ?>, <?= htmlspecialchars($theme_primary_color) ?>, <?= htmlspecialchars($theme_accent_color) ?>) 1 !important;
    }
    .nav-link:hover, .nav-link.active {
      color: <?= htmlspecialchars($theme_primary_color) ?> !important;
    }
    .navbar-nav .nav-link {
      padding: 0.45rem 0.6rem !important;
      font-size: 0.88rem !important;
    }
    .offcanvas-header {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_secondary_color) ?>, <?= htmlspecialchars($theme_primary_color) ?>) !important;
    }

    /* 9. Cards, Borders & Structural Elements */
    .feature-card, .service-card, .guarantee-card, .fleet-card, .gallery-item, 
    .process-step, .faq-accordion .accordion-item, .seo-card, .form-card, 
    .whyus-card, .review-card, .testimonial-card, .fleet-img-wrap {
      border-color: rgba(<?= $rgb_accent ?>, 0.3) !important;
    }
    .feature-icon-badge, .service-icon {
      background: linear-gradient(135deg, rgba(<?= $rgb_accent ?>, 0.25), rgba(<?= $rgb_primary ?>, 0.3)) !important;
      border-color: rgba(<?= $rgb_accent ?>, 0.5) !important;
      color: <?= htmlspecialchars($theme_accent_color) ?> !important;
    }
    .fleet-badge {
      background: rgba(<?= $rgb_primary ?>, 0.12) !important;
      color: <?= htmlspecialchars($theme_primary_color) ?> !important;
    }
    .gallery-item {
      border-color: rgba(<?= $rgb_accent ?>, 0.3) !important;
    }
    .package-card.popular {
      background: linear-gradient(180deg, rgba(<?= $rgb_accent ?>, 0.2) 0%, rgba(<?= $rgb_primary ?>, 0.22) 100%) !important;
      border-color: <?= htmlspecialchars($theme_accent_color) ?> !important;
    }
    .popular-ribbon {
      background: linear-gradient(90deg, <?= htmlspecialchars($theme_accent_color) ?>, <?= htmlspecialchars($theme_primary_color) ?>) !important;
      color: #FFF !important;
    }
    .package-price-box .price {
      color: <?= htmlspecialchars($theme_accent_color) ?> !important;
    }
    .contact-info-card {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_secondary_color) ?>, rgba(<?= $rgb_secondary ?>, 0.95)) !important;
      border-color: rgba(<?= $rgb_accent ?>, 0.35) !important;
    }
    .floating-buttons .float-btn-call {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_primary_color) ?>, <?= htmlspecialchars($theme_secondary_color) ?>) !important;
    }
    .area-badge {
      background: rgba(255,255,255,0.12) !important;
      border-color: rgba(255,255,255,0.25) !important;
    }
    .area-badge:hover {
      background: <?= htmlspecialchars($theme_accent_color) ?> !important;
      color: <?= htmlspecialchars($theme_secondary_color) ?> !important;
      border-color: <?= htmlspecialchars($theme_accent_color) ?> !important;
    }
    .guarantee-card:hover, .service-card:hover, .feature-card:hover, .fleet-card:hover, .process-step:hover {
      border-color: <?= htmlspecialchars($theme_primary_color) ?> !important;
    }
    .faq-accordion .accordion-button:not(.collapsed) {
      background: rgba(<?= $rgb_primary ?>, 0.08) !important;
      color: <?= htmlspecialchars($theme_primary_color) ?> !important;
    }
    .faq-accordion .accordion-body {
      background: rgba(<?= $rgb_primary ?>, 0.02) !important;
    }
    .section-title::after {
      background: linear-gradient(90deg, <?= htmlspecialchars($theme_accent_color) ?>, <?= htmlspecialchars($theme_primary_color) ?>) !important;
    }
    .section-title::before {
      background: <?= htmlspecialchars($theme_primary_color) ?> !important;
    }
    .step-number {
      background: <?= htmlspecialchars($theme_primary_color) ?> !important;
      box-shadow: 0 8px 24px rgba(<?= $rgb_primary ?>, 0.35) !important;
    }
    .process-step::before {
      background: <?= htmlspecialchars($theme_primary_color) ?> !important;
    }
    .form-control:focus, .form-select:focus {
      border-color: <?= htmlspecialchars($theme_primary_color) ?> !important;
      box-shadow: 0 0 0 4px rgba(<?= $rgb_primary ?>, 0.18) !important;
    }
    #scrollTop {
      background: linear-gradient(135deg, <?= htmlspecialchars($theme_secondary_color) ?>, <?= htmlspecialchars($theme_primary_color) ?>) !important;
      border-color: <?= htmlspecialchars($theme_accent_color) ?> !important;
      box-shadow: 0 8px 25px rgba(<?= $rgb_primary ?>, 0.45) !important;
    }
    .lightbox-nav {
      background: rgba(<?= $rgb_primary ?>, 0.85) !important;
      border-color: <?= htmlspecialchars($theme_accent_color) ?> !important;
    }
  </style>

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
        <li class="nav-item"><a class="nav-link" href="#about"><?= htmlspecialchars($nav_about) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#services"><?= htmlspecialchars($nav_services) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#fleet"><?= htmlspecialchars($nav_fleet) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#whyus"><?= htmlspecialchars($nav_whyus) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#works"><?= htmlspecialchars($nav_works) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#packages"><?= htmlspecialchars($nav_packages) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#areas"><?= htmlspecialchars($nav_areas) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#process"><?= htmlspecialchars($nav_process) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#guarantees"><?= htmlspecialchars($nav_guarantees) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#faq"><?= htmlspecialchars($nav_faq) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="#contact"><?= htmlspecialchars($nav_contact) ?></a></li>
      </ul>
      <a href="tel:<?= htmlspecialchars(format_phone_tel($phone_primary)) ?>" class="btn nav-call-btn ms-3">
        <i class="fas fa-phone-alt"></i> <?= htmlspecialchars($nav_call_btn) ?>
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
      <li class="nav-item"><a class="nav-link fw-bold" href="#about"      data-bs-dismiss="offcanvas"><i class="fas fa-info-circle me-2 text-warning"></i><?= htmlspecialchars($nav_about) ?></a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#services"   data-bs-dismiss="offcanvas"><i class="fas fa-concierge-bell me-2 text-warning"></i><?= htmlspecialchars($nav_services) ?></a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#fleet"      data-bs-dismiss="offcanvas"><i class="fas fa-truck me-2 text-warning"></i><?= htmlspecialchars($nav_fleet) ?></a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#whyus"      data-bs-dismiss="offcanvas"><i class="fas fa-crown me-2 text-warning"></i><?= htmlspecialchars($nav_whyus) ?></a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#works"      data-bs-dismiss="offcanvas"><i class="fas fa-camera-retro me-2 text-warning"></i><?= htmlspecialchars($nav_works) ?></a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#packages"   data-bs-dismiss="offcanvas"><i class="fas fa-tags me-2 text-warning"></i><?= htmlspecialchars($nav_packages) ?></a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#areas"      data-bs-dismiss="offcanvas"><i class="fas fa-map-marked-alt me-2 text-warning"></i><?= htmlspecialchars($nav_areas) ?></a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#process"    data-bs-dismiss="offcanvas"><i class="fas fa-tasks me-2 text-warning"></i><?= htmlspecialchars($nav_process) ?></a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#guarantees" data-bs-dismiss="offcanvas"><i class="fas fa-shield-alt me-2 text-warning"></i><?= htmlspecialchars($nav_guarantees) ?></a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#faq"        data-bs-dismiss="offcanvas"><i class="fas fa-question-circle me-2 text-warning"></i><?= htmlspecialchars($nav_faq) ?></a></li>
      <li class="nav-item"><a class="nav-link fw-bold" href="#contact"    data-bs-dismiss="offcanvas"><i class="fas fa-headset me-2 text-warning"></i><?= htmlspecialchars($nav_contact) ?></a></li>
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
