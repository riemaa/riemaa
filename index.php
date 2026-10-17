<?php
/**
 * صفحة الهبوط الرئيسية الديناميكية بالكامل لشركة المثلث كلين للنظافة العامة
 */

require_once __DIR__ . '/includes/header.php';

$rgb_primary = hex2rgb_css($theme_primary_color);

// جلب بيانات الأقسام من قاعدة البيانات
$hero = get_section('hero', [
    'badge'          => 'الشركة الرائدة الأولى في خدمات التنظيف والتعقيم الشامل بالمنطقة الشرقية',
    'title'          => 'نظافة احترافية بـ<span class="highlight">أعلى معايير الجودة</span><br>وبأحدث تقنيات <span class="highlight">التعقيم والتطهير</span>',
    'desc'           => 'شركة المثلث كلين للنظافة العامة – خبرة عريقة تتجاوز 10 سنوات في تقديم أرقى حلول التنظيف والتعقيم للمنازل والفلل والقصور والشركات في الدمام، القطيف، الخبر، الجبيل، الظهران، وبقيق. نوفر أحدث أجهزة البخار وضخ المياه وتقنيات غسيل السجاد والكنب وتنظيف الخزانات والأرضيات والمكيفات بأيدي كوادر فنية مدربة وبأفضل مواد التنظيف المعتمدة عالمياً.',
    'btn_call'       => 'اتصل واحجز فوراً',
    'btn_wa'         => 'استفسار واتساب سريع',
    'stat_clients'   => '15000',
    'stat_years'     => '12',
    'stat_guarantee' => '100',
    'stat_hours'     => '24/7',
    'card1_title'    => '100% نظافة وتعقيم',
    'card1_sub'      => 'ضمان شامل ومواد آمنة',
    'card2_title'    => 'تقنيات ألمانية متطورة',
    'card2_sub'      => 'أحدث أجهزة البخار والتعقيم',
    'image'          => 'assets/images/1.jpg'
]);

$about = get_section('about', [
    'tag'          => 'نبذة عن الشركة',
    'title'        => 'من نحن - قصة الريادة في عالم النظافة والتعقيم',
    'subtitle'     => 'نضع بين يديك خبرة متوارثة واحترافية مطلقة تجعل منزلك ومكان عملك بيئة صحية ونقية تفيض بالانتعاش واللمعان',
    'story1_title' => 'رؤيتنا: النقاء التام والالتزام بأعلى المعايير الصحية العالمية',
    'story1_p1'    => 'تأسست شركة المثلث كلين للنظافة العامة بالدمام لتكون المعيار الأرقى في قطاع خدمات النظافة والتعقيم ومكافحة الملوثات للمنشآت السكنية والتجارية بالمنطقة الشرقية والمملكة العربية السعودية...',
    'story1_p2'    => 'نحن نوفر كادراً وظيفياً متخصصاً خضع لأعلى دورات التدريب الفني على استخدام أحدث تقنيات التنظيف العميق بالبخار ومواد التطهير الآمنة والصديقة للبيئة والمسجلة رسمياً...',
    'story1_img'   => 'assets/images/2.jpg',
    'story2_title' => 'فريق فني متخصص وأحدث معدات النظافة والتعقيم',
    'story2_p1'    => 'لا نعتمد في المثلث كلين على العمالة العشوائية، بل نمتلك طواقم عمل مدربة تدريباً صارماً على التعامل مع أدق التفاصيل من مفروشات وسجاد وخزانات مياه وأجهزة تكييف...',
    'story2_p2'    => 'نستخدم أحدث ماكينات جلي وتلميع الرخام، وأجهزة غسيل السجاد بالبخار الحار والتجفيف السريع، مع فحص دوري للجودة بعد كل عملية لضمان رضاكم التام بنسبة 100%...',
    'story2_img'   => 'assets/images/3.jpg'
]);

// جلب الخدمات
try {
    $services = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC")->fetchAll();
} catch (Exception $e) { $services = []; }

// جلب ركائز التميز
try {
    $why_us = $pdo->query("SELECT * FROM why_us ORDER BY sort_order ASC")->fetchAll();
} catch (Exception $e) { $why_us = []; }

// جلب الأسطول والمعدات
try {
    $fleet = $pdo->query("SELECT * FROM fleet ORDER BY sort_order ASC")->fetchAll();
} catch (Exception $e) { $fleet = []; }

// جلب معرض الأعمال
try {
    $gallery = $pdo->query("SELECT * FROM gallery ORDER BY sort_order ASC")->fetchAll();
} catch (Exception $e) { $gallery = []; }

// جلب باقات الأسعار
try {
    $packages = $pdo->query("SELECT * FROM packages ORDER BY sort_order ASC")->fetchAll();
} catch (Exception $e) { $packages = []; }

// جلب خطوات العمل
try {
    $work_steps = $pdo->query("SELECT * FROM work_steps ORDER BY step_number ASC")->fetchAll();
} catch (Exception $e) { $work_steps = []; }

// جلب الضمانات
try {
    $guarantees = $pdo->query("SELECT * FROM guarantees ORDER BY sort_order ASC")->fetchAll();
} catch (Exception $e) { $guarantees = []; }

// جلب آراء العملاء
try {
    $testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY sort_order ASC")->fetchAll();
} catch (Exception $e) { $testimonials = []; }

// جلب الأسئلة الشائعة
try {
    $faqs = $pdo->query("SELECT * FROM faqs ORDER BY sort_order ASC")->fetchAll();
} catch (Exception $e) { $faqs = []; }

// جلب مقالات السيو
try {
    $seo_articles = $pdo->query("SELECT * FROM seo_articles ORDER BY sort_order ASC")->fetchAll();
} catch (Exception $e) { $seo_articles = []; }

// جلب مناطق الخدمة مجمعة حسب القسم الجغرافي الحر
try {
    $areas_raw = $pdo->query("SELECT * FROM service_areas ORDER BY category ASC, sort_order ASC, id ASC")->fetchAll();
    $areas_grouped = [];
    foreach ($areas_raw as $ar) {
        $cat = !empty(trim($ar['category'])) ? trim($ar['category']) : 'مناطق الخدمة الرئيسية';
        $areas_grouped[$cat][] = $ar['area_name'];
    }
} catch (Exception $e) {
    $areas_grouped = [];
}
?>

<!-- ============================================================
     HERO SECTION (Colored - Dark Luxury Walnut Wood)
============================================================ -->
<section id="hero" style="overflow: hidden !important; position: relative;">
  <div class="hero-particles" id="particles"></div>
  <div class="container position-relative" style="z-index:1;">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="hero-badge">
          <i class="fas fa-award text-warning"></i>
          <span><?= htmlspecialchars($hero['badge'] ?? '') ?></span>
        </div>
        <h1 class="hero-title">
          <?= $hero['title'] ?? '' ?>
        </h1>
        <p class="hero-desc">
          <?= nl2br(htmlspecialchars($hero['desc'] ?? '')) ?>
        </p>
        <div class="hero-btns">
          <a href="tel:<?= htmlspecialchars(format_phone_tel($phone_primary)) ?>" class="btn-hero-primary">
            <i class="fas fa-phone-alt"></i> <?= htmlspecialchars(get_setting('btn_hero_call', $hero['btn_call'] ?? 'اتصل واحجز فوراً')) ?>
          </a>
          <a href="<?= htmlspecialchars(format_whatsapp_link($whatsapp_number)) ?>" class="btn-hero-secondary" target="_blank">
            <i class="fab fa-whatsapp"></i> <?= htmlspecialchars(get_setting('btn_hero_wa', $hero['btn_wa'] ?? 'استفسار واتساب سريع')) ?>
          </a>
        </div>
        <div class="hero-stats">
          <div class="stat-item">
            <span class="stat-number" data-count="<?= htmlspecialchars($hero['stat_clients'] ?? '5000') ?>">0</span>
            <span class="stat-label">عميل وثقوا بنا+</span>
          </div>
          <div class="stat-item">
            <span class="stat-number" data-count="<?= htmlspecialchars($hero['stat_years'] ?? '12') ?>">0</span>
            <span class="stat-label">سنة خبرة متواصلة</span>
          </div>
          <div class="stat-item">
            <span class="stat-number" data-count="<?= htmlspecialchars($hero['stat_guarantee'] ?? '100') ?>">0</span>
            <span class="stat-label">% ضمان الرضا والتعقيم</span>
          </div>
          <div class="stat-item">
            <span class="stat-number"><?= htmlspecialchars($hero['stat_hours'] ?? '24/7') ?></span>
            <span class="stat-label">خدمة على مدار الساعة</span>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="hero-image-wrapper">
          <img src="<?= htmlspecialchars(asset_url($hero['image'] ?? 'assets/images/1.jpg')) ?>" alt="<?= htmlspecialchars($site_name) ?>" class="hero-img-main" />
          <div class="hero-float-card card1">
            <div class="float-card-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="float-card-text">
              <span><?= htmlspecialchars($hero['card1_sub'] ?? 'ضمان معتمد 100%') ?></span>
              <strong><?= htmlspecialchars($hero['card1_title'] ?? 'نظافة وتعقيم فائق') ?></strong>
            </div>
          </div>
          <div class="hero-float-card card2">
            <div class="float-card-icon"><i class="fas fa-spray-can-sparkles"></i></div>
            <div class="float-card-text">
              <span><?= htmlspecialchars($hero['card2_sub'] ?? 'أحدث أجهزة البخار') ?></span>
              <strong><?= htmlspecialchars($hero['card2_title'] ?? 'إزالة أصعب البقع') ?></strong>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Wave Divider to Light -->
<div class="wave-fill-dark" style="background:var(--white); margin-top:-2px;">
  <svg viewBox="0 0 1440 50" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C360,50 1080,0 1440,30 L1440,0 L0,0 Z" fill="<?= htmlspecialchars($theme_secondary_color) ?>"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 1: ABOUT US & STORY (Light Clean Background)
============================================================ -->
<section id="about" class="bg-clean-light" style="padding: 90px 0; position:relative;">
  <span id="about-info" style="position:absolute;top:-80px;visibility:hidden;"></span>
  <div class="container">
    <div class="text-center animate-on-scroll">
      <span class="section-subtitle-tag"><i class="fas fa-landmark"></i> <?= htmlspecialchars($about['tag'] ?? 'نبذة عن الشركة') ?></span>
      <h2 class="section-title"><?= htmlspecialchars($about['title'] ?? 'من نحن - قصة الريادة في عالم النظافة والتعقيم') ?></h2>
      <p class="section-subtitle"><?= htmlspecialchars($about['subtitle'] ?? '') ?></p>
    </div>

    <!-- Row 1 -->
    <div class="row align-items-center mb-5 animate-on-scroll">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <h3 class="fw-bold mb-3" style="color:var(--wood-walnut);font-size:1.8rem;"><?= htmlspecialchars($about['story1_title'] ?? '') ?></h3>
        <p style="font-size:1.05rem; line-height:1.9; color:#4a5568;">
          <?= nl2br(htmlspecialchars($about['story1_p1'] ?? '')) ?>
        </p>
        <?php if (!empty($about['story1_p2'])): ?>
        <p style="font-size:1.05rem; line-height:1.9; color:#4a5568;">
          <?= nl2br(htmlspecialchars($about['story1_p2'])) ?>
        </p>
        <?php endif; ?>
      </div>
      <div class="col-lg-6 text-center">
        <img src="<?= htmlspecialchars(asset_url($about['story1_img'] ?? 'assets/images/2.jpg')) ?>" alt="<?= htmlspecialchars($about['story1_title'] ?? '') ?>" style="width:100%; height:370px; object-fit:cover; border-radius:24px; box-shadow:var(--shadow-md); border:3px solid rgba(<?= $rgb_accent ?>, 0.35);">
      </div>
    </div>

    <!-- Row 2 -->
    <div class="row align-items-center flex-lg-row-reverse mb-5 animate-on-scroll">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <h3 class="fw-bold mb-3" style="color:var(--wood-walnut);font-size:1.8rem;"><?= htmlspecialchars($about['story2_title'] ?? '') ?></h3>
        <p style="font-size:1.05rem; line-height:1.9; color:#4a5568;">
          <?= nl2br(htmlspecialchars($about['story2_p1'] ?? '')) ?>
        </p>
        <?php if (!empty($about['story2_p2'])): ?>
        <p style="font-size:1.05rem; line-height:1.9; color:#4a5568;">
          <?= nl2br(htmlspecialchars($about['story2_p2'])) ?>
        </p>
        <?php endif; ?>
      </div>
      <div class="col-lg-6 text-center">
        <img src="<?= htmlspecialchars(asset_url($about['story2_img'] ?? 'assets/images/3.jpg')) ?>" alt="<?= htmlspecialchars($about['story2_title'] ?? '') ?>" style="width:100%; height:370px; object-fit:cover; border-radius:24px; box-shadow:var(--shadow-md); border:3px solid rgba(<?= $rgb_accent ?>, 0.35);">
      </div>
    </div>
  </div>
</section>

<!-- Wave Divider to Beige -->
<div class="wave-bg-beige" style="line-height:0;">
  <svg viewBox="0 0 1440 45" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C480,45 960,0 1440,35 L1440,0 L0,0 Z" fill="#FFFFFF"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 2: SERVICES (Colored - Warm Beige Background)
============================================================ -->
<section id="services" class="bg-warm-beige" style="padding: 90px 0;">
  <div class="container text-center">
    <div class="animate-on-scroll">
      <span class="section-subtitle-tag"><i class="fas fa-sparkles"></i> خدماتنا المتخصصة</span>
      <h2 class="section-title mb-2">حلول متكاملة لخدمات النظافة والتعقيم بكل احترافية</h2>
      <p class="section-subtitle">من الفحص والمعاينة الأولية حتى تسليم المكان معقماً ولامعاً يفوح بالنظافة والانتعاش</p>
    </div>

    <div class="row g-4 mt-2">
      <?php foreach ($services as $idx => $srv): 
        $delay = ($idx % 4) + 1;
        $features_list = array_filter(array_map('trim', explode("\n", $srv['features'])));
      ?>
      <div class="col-xl-4 col-md-6 animate-on-scroll animate-delay-<?= $delay ?>">
        <div class="service-card luxury-card">
          <div class="luxury-icon"><i class="<?= htmlspecialchars($srv['icon']) ?>"></i></div>
          <h4><?= htmlspecialchars($srv['title']) ?></h4>
          <p><?= htmlspecialchars($srv['description']) ?></p>
          <?php if (!empty($features_list)): ?>
          <ul class="service-features-list">
            <?php foreach ($features_list as $feat): ?>
              <li><i class="fas fa-check-circle"></i> <?= htmlspecialchars($feat) ?></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Wave Divider to Dark -->
<div class="wave-bg-dark wave-fill-beige" style="line-height:0;">
  <svg viewBox="0 0 1440 45" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C480,45 960,0 1440,35 L1440,0 L0,0 Z" fill="rgba(<?= $rgb_primary ?>, 0.075)"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 3: WHY US (Colored - Dark Luxury Walnut Wood)
============================================================ -->
<section id="whyus" class="bg-dark-wood" style="padding: 90px 0;">
  <div class="container text-center position-relative" style="z-index:1;">
    <div class="animate-on-scroll">
      <span class="section-subtitle-tag tag-light"><i class="fas fa-crown"></i> ركائز تميزنا</span>
      <h2 class="section-title title-light mb-2">لماذا يثق بنا آلاف العملاء في الدمام والمنطقة الشرقية؟</h2>
      <p class="section-subtitle sub-light">معايير صارمة وضمانات حقيقية تجعلنا الخيار الأول المفضل لكل أسرة ومؤسسة</p>
    </div>

    <div class="row g-4 mt-2">
      <?php foreach ($why_us as $idx => $w): 
        $delay = ($idx % 4) + 1;
      ?>
      <div class="col-lg-3 col-md-6 animate-on-scroll animate-delay-<?= $delay ?>">
        <div class="feature-card">
          <div class="feature-icon-badge"><i class="<?= htmlspecialchars($w['icon']) ?>"></i></div>
          <h5><?= htmlspecialchars($w['title']) ?></h5>
          <p><?= htmlspecialchars($w['description']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Wave Divider to Light -->
<div class="wave-fill-dark" style="background:var(--white); line-height:0;">
  <svg viewBox="0 0 1440 45" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C480,45 960,0 1440,35 L1440,0 L0,0 Z" fill="<?= htmlspecialchars($theme_secondary_color) ?>"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 4: FLEET & EQUIPMENT (Light Clean Background)
============================================================ -->
<section id="fleet" class="bg-clean-light" style="padding: 90px 0;">
  <div class="container">
    <div class="text-center animate-on-scroll">
      <span class="section-subtitle-tag"><i class="fas fa-spray-can-sparkles"></i> معداتنا وأجهزتنا</span>
      <h2 class="section-title mb-2">أحدث أجهزة ومعدات التنظيف والتعقيم المتطورة</h2>
      <p class="section-subtitle">لا نترك شيئاً للصدفة؛ نعتمد على أحدث التقنيات الألمانية والإيطالية لضمان أعلى درجات النقاء والتعقيم</p>
    </div>

    <div class="row g-4 mt-2">
      <?php foreach ($fleet as $idx => $fl): 
        $delay = ($idx % 3) + 1;
      ?>
      <div class="col-lg-4 col-md-6 animate-on-scroll animate-delay-<?= $delay ?>">
        <div class="fleet-card">
          <div class="fleet-img-wrap">
            <img src="<?= htmlspecialchars(asset_url($fl['image'])) ?>" alt="<?= htmlspecialchars($fl['title']) ?>" loading="lazy" />
          </div>
          <span class="fleet-badge"><i class="fas fa-shield-virus me-1"></i> <?= htmlspecialchars($fl['badge']) ?></span>
          <h4 class="fw-bold mb-2" style="color:var(--wood-walnut);"><?= htmlspecialchars($fl['title']) ?></h4>
          <p style="color:var(--text-muted);font-size:0.92rem;line-height:1.75;">
            <?= htmlspecialchars($fl['description']) ?>
          </p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Wave Divider to Beige -->
<div class="wave-bg-beige" style="line-height:0;">
  <svg viewBox="0 0 1440 45" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C480,45 960,0 1440,35 L1440,0 L0,0 Z" fill="#FFFFFF"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 5: GALLERY (Colored - Warm Biscuit Beige Background)
============================================================ -->
<section id="works" class="bg-warm-beige" style="padding: 90px 0;">
  <div class="container">
    <div class="text-center animate-on-scroll">
      <span class="section-subtitle-tag"><i class="fas fa-camera-retro"></i> أعمالنا الحية</span>
      <h2 class="section-title mb-2">معرض أعمالنا الميدانية بالدمام والشرقية</h2>
      <p class="section-subtitle">شاهد صوراً حية لعمليات التنظيف الشامل، غسيل السجاد والكنب والمجالس، وتطهير الخزانات والمكيفات باحترافية</p>
    </div>

    <!-- Gallery Grid Loaded Dynamically -->
    <div class="gallery-grid" id="galleryGrid">
      <?php foreach ($gallery as $g): ?>
      <div class="gallery-item animate-on-scroll visible">
        <img src="<?= htmlspecialchars(asset_url($g['image'])) ?>" alt="<?= htmlspecialchars($g['caption']) ?>" loading="lazy" />
        <div class="gallery-overlay">
          <i class="fas fa-search-plus"></i>
          <span><?= htmlspecialchars($g['caption']) ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Wave Divider to Dark -->
<div class="wave-bg-dark wave-fill-beige" style="line-height:0;">
  <svg viewBox="0 0 1440 45" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C480,45 960,0 1440,35 L1440,0 L0,0 Z" fill="rgba(<?= $rgb_primary ?>, 0.075)"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 6: PACKAGES & PRICING (Colored - Dark Walnut Wood)
============================================================ -->
<section id="packages" class="bg-dark-wood" style="padding: 90px 0;">
  <div class="container position-relative" style="z-index:1;">
    <div class="text-center animate-on-scroll">
      <span class="section-subtitle-tag tag-light"><i class="fas fa-tags"></i> باقات وعروض النظافة</span>
      <h2 class="section-title title-light mb-2">باقات تنافسية وعروض حصرية تلائم احتياجاتك</h2>
      <p class="section-subtitle sub-light">اختر الباقة المثالية لمنزلك أو منشأتك واستمتع بخصم إضافي <?= htmlspecialchars(get_setting('discount_percentage', '20%')) ?> عند الحجز اليوم</p>
    </div>

    <div class="row g-4 mt-2 align-items-stretch">
      <?php foreach ($packages as $idx => $pkg): 
        $delay = ($idx % 3) + 1;
        $is_pop = !empty($pkg['is_popular']);
        $p_features = array_filter(array_map('trim', explode("\n", $pkg['features'])));
      ?>
      <div class="col-lg-4 col-md-6 animate-on-scroll animate-delay-<?= $delay ?>">
        <div class="package-card <?= $is_pop ? 'popular' : '' ?>">
          <?php if ($is_pop): ?>
            <div class="popular-ribbon"><i class="fas fa-crown me-1"></i> الباقة الأكثر طلباً</div>
          <?php endif; ?>
          <h4 class="fw-bold mb-2" style="<?= $is_pop ? 'color:var(--accent-bright);' : '' ?>"><?= htmlspecialchars($pkg['title']) ?></h4>
          <p class="text-white-50" style="font-size:0.88rem;"><?= htmlspecialchars($pkg['subtitle']) ?></p>
          <div class="package-price-box">
            <span class="text-white-50 d-block" style="font-size:0.85rem;"><?= htmlspecialchars($pkg['price_badge']) ?></span>
            <span class="price"><?= htmlspecialchars($pkg['price_text']) ?></span>
          </div>
          <?php if (!empty($p_features)): ?>
          <ul class="package-features">
            <?php foreach ($p_features as $pf): ?>
              <li><i class="fas fa-check-circle"></i> <?= htmlspecialchars($pf) ?></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
          <a href="<?= htmlspecialchars($pkg['btn_link']) ?>" class="btn <?= $is_pop ? 'btn-hero-primary' : 'btn-hero-secondary' ?> w-100 justify-content-center">
            <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($pkg['btn_text']) ?>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Wave Divider to Terracotta -->
<div class="wave-bg-terracotta wave-fill-dark" style="line-height:0;">
  <svg viewBox="0 0 1440 45" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C480,45 960,0 1440,35 L1440,0 L0,0 Z" fill="<?= htmlspecialchars($theme_secondary_color) ?>"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 7: AREAS COVERAGE (Colored - Terracotta Saddle Gradient)
============================================================ -->
<section id="areas" class="bg-terracotta" style="padding: 90px 0;">
  <div class="container text-center">
    <div class="animate-on-scroll">
      <span class="section-subtitle-tag tag-light"><i class="fas fa-map-marked-alt"></i> شبكة التغطية</span>
      <h2 class="section-title title-light mb-2">تغطية شاملة لجميع أحياء الدمام والقطيف والخبر والجبيل والظهران وبقيق</h2>
      <p class="section-subtitle sub-light">نمتلك نقاط انتشار سريعة تصل إلى موقعك خلال دقائق معدودة في أي وقت</p>
    </div>

    <div class="animate-on-scroll animate-delay-1 mt-4">
      <?php if (!empty($areas_grouped)): ?>
        <?php foreach ($areas_grouped as $cat_title => $cat_areas): ?>
          <div class="mb-4">
            <h5 class="text-white fw-bold mb-3" style="font-size:1.3rem;">
              <i class="fas fa-layer-group text-warning me-2"></i> <?= htmlspecialchars($cat_title) ?>
            </h5>
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
              <?php foreach ($cat_areas as $area): ?>
                <span class="area-badge"><i class="fas fa-map-pin"></i> <?= htmlspecialchars($area) ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-white-50">نغطي كافة أحياء الدمام والقطيف والخبر والظهران والجبيل ومدن المملكة، تواصل معنا للاستفسار والحجز الفوري.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Wave Divider to Light -->
<div class="wave-fill-primary" style="background:var(--white); line-height:0;">
  <svg viewBox="0 0 1440 45" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C480,45 960,0 1440,35 L1440,0 L0,0 Z" fill="<?= htmlspecialchars($theme_primary_color) ?>"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 8: PROCESS (Light Clean Background)
============================================================ -->
<section id="process" class="bg-clean-light" style="padding: 90px 0;">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5 animate-on-scroll">
        <span class="section-subtitle-tag"><i class="fas fa-cogs"></i> منهجية العمل الذكية</span>
        <h2 class="section-title text-start mb-3" style="display:block;">كيف ننظف ونعقم المكان<br>في <?= count($work_steps) ?> خطوات محكمة</h2>
        <p style="color:var(--text-muted);line-height:1.85;font-size:1.02rem;">
          نظام تشغيلي متكامل يضمن أعلى مستويات النظافة والتعقيم الشامل من لحظة اتصالك الهاتفي وحتى تسليم منزلك نقياً وصحياً بالكامل.
        </p>
        <div class="p-3 my-3" style="background:var(--beige-warm);border-radius:16px;border-right:4px solid var(--primary);">
          <strong class="d-block mb-1 text-dark"><i class="fas fa-check-double text-warning me-1"></i> دقة مواعيد بنسبة 100%</strong>
          <small class="text-muted">نصل إليك في الموعد المتفق عليه تماماً دون أي تأخير.</small>
        </div>
        <a href="tel:<?= htmlspecialchars(format_phone_tel($phone_primary)) ?>" class="btn-hero-primary mt-2 d-inline-flex">
          <i class="fas fa-phone-alt"></i> ابدأ حجز موعدك الآن
        </a>
      </div>

      <div class="col-lg-7 animate-on-scroll animate-delay-2">
        <?php 
        $legacy_step_browns = ['#8B5A2B', '#A0522D', '#CD853F', '#5C3A28', '#341D13', '#6B421E', '#46271A'];
        $step_theme_palette = [
          $theme_primary_color,
          $theme_accent_color,
          $theme_secondary_color,
          $theme_primary_color,
          $theme_accent_color,
          $theme_secondary_color
        ];
        $st_idx = 0;
        foreach ($work_steps as $ws): 
          $scolor = trim($ws['color_code'] ?? '');
          if (empty($scolor) || ($theme_primary_color !== '#8B5A2B' && in_array(strtoupper($scolor), $legacy_step_browns))) {
            $scolor = $step_theme_palette[$st_idx % count($step_theme_palette)];
          }
          $st_idx++;
        ?>
        <div class="process-step" style="--step-color:<?= htmlspecialchars($scolor) ?>;">
          <div class="step-number"><?= htmlspecialchars($ws['step_number']) ?></div>
          <div class="step-content">
            <h5><?= htmlspecialchars($ws['title']) ?></h5>
            <p><?= htmlspecialchars($ws['description']) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- Wave Divider to Beige -->
<div class="wave-bg-beige" style="line-height:0;">
  <svg viewBox="0 0 1440 45" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C480,45 960,0 1440,35 L1440,0 L0,0 Z" fill="#FFFFFF"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 9: GUARANTEES CHARTER (Colored - Warm Beige)
============================================================ -->
<section id="guarantees" class="bg-warm-beige" style="padding: 90px 0;">
  <div class="container text-center">
    <div class="animate-on-scroll">
      <span class="section-subtitle-tag"><i class="fas fa-shield-alt"></i> ميثاق <?= htmlspecialchars($site_short_name) ?></span>
      <h2 class="section-title mb-2"><?= count($guarantees) ?> ضمانات ذهبية تمنحك راحة البال التامة</h2>
      <p class="section-subtitle">نحن لا نقدم مجرد تنظيف عادي، بل نمنحك بيئة صحية نقية وراحة بال تامة بوثيقة ضمان رسمية</p>
    </div>

    <div class="row g-4 mt-2">
      <?php foreach ($guarantees as $idx => $g): 
        $delay = ($idx % 4) + 1;
      ?>
      <div class="col-lg-3 col-md-6 animate-on-scroll animate-delay-<?= $delay ?>">
        <div class="guarantee-card">
          <div class="luxury-icon"><i class="<?= htmlspecialchars($g['icon']) ?>"></i></div>
          <h4><?= htmlspecialchars($g['title']) ?></h4>
          <p><?= htmlspecialchars($g['description']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Wave Divider to Dark -->
<div class="wave-bg-dark wave-fill-beige" style="line-height:0;">
  <svg viewBox="0 0 1440 45" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C480,45 960,0 1440,35 L1440,0 L0,0 Z" fill="rgba(<?= $rgb_primary ?>, 0.075)"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 10: TESTIMONIALS (Colored - Dark Roasted Espresso)
============================================================ -->
<section id="testimonials" class="bg-dark-wood" style="padding: 90px 0;">
  <div class="container text-center position-relative" style="z-index:1;">
    <div class="animate-on-scroll">
      <span class="section-subtitle-tag tag-light"><i class="fas fa-quote-right"></i> تجارب حقيقية</span>
      <h2 class="section-title title-light mb-2">ماذا يقول عملاؤنا الكرام عنا؟</h2>
      <p class="section-subtitle sub-light">آراء وتجارب حية من عملاء وثقوا بنا في تنظيف وتعقيم منازلهم وفللهم ومكاتبهم</p>
    </div>

    <div class="row g-4 mt-2">
      <?php foreach ($testimonials as $idx => $t): 
        $delay = ($idx % 3) + 1;
      ?>
      <div class="col-lg-4 col-md-6 animate-on-scroll animate-delay-<?= $delay ?>">
        <div class="testimonial-card">
          <div class="stars"><?= str_repeat('★', (int)$t['rating']) ?></div>
          <p class="testimonial-text">"<?= htmlspecialchars($t['comment']) ?>"</p>
          <div class="testimonial-author">
            <div class="author-avatar" style="background:linear-gradient(135deg, <?= htmlspecialchars($theme_primary_color) ?>, <?= htmlspecialchars($theme_accent_color) ?>);">
              <?= htmlspecialchars($t['avatar_letter']) ?>
            </div>
            <div class="author-info">
              <span><?= htmlspecialchars($t['name']) ?></span>
              <span><?= htmlspecialchars($t['location']) ?></span>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Wave Divider to Light -->
<div class="wave-fill-dark" style="background:var(--white); line-height:0;">
  <svg viewBox="0 0 1440 45" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C480,45 960,0 1440,35 L1440,0 L0,0 Z" fill="<?= htmlspecialchars($theme_secondary_color) ?>"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 11: FAQ (Light Clean Background)
============================================================ -->
<section id="faq" class="bg-clean-light" style="padding: 90px 0;">
  <div class="container">
    <div class="text-center animate-on-scroll">
      <span class="section-subtitle-tag"><i class="fas fa-question-circle"></i> إجابات شاملة</span>
      <h2 class="section-title mb-2">الأسئلة الأكثر شيوعاً حول خدمات التنظيف والتعقيم</h2>
      <p class="section-subtitle">جمعنا لك أهم التساؤلات التي يطرحها عملاؤنا مع إجابات واضحة وشفافة تماماً</p>
    </div>

    <div class="row justify-content-center mt-3">
      <div class="col-lg-9 animate-on-scroll">
        <div class="accordion faq-accordion" id="faqAccordion">
          <?php foreach ($faqs as $idx => $f): 
            $collapseId = "collapse_" . $f['id'];
            $is_first = ($idx === 0);
          ?>
          <div class="accordion-item">
            <h2 class="accordion-header" id="heading_<?= $f['id'] ?>">
              <button class="accordion-button <?= $is_first ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>" aria-expanded="<?= $is_first ? 'true' : 'false' ?>" aria-controls="<?= $collapseId ?>">
                <i class="<?= htmlspecialchars($f['icon']) ?> text-warning me-2"></i> <?= htmlspecialchars($f['question']) ?>
              </button>
            </h2>
            <div id="<?= $collapseId ?>" class="accordion-collapse collapse <?= $is_first ? 'show' : '' ?>" aria-labelledby="heading_<?= $f['id'] ?>" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                <?= nl2br(htmlspecialchars($f['answer'])) ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Wave Divider to Dark -->
<div class="wave-bg-dark" style="line-height:0;">
  <svg viewBox="0 0 1440 45" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C480,45 960,0 1440,35 L1440,0 L0,0 Z" fill="#FFFFFF"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 12: SEO GUIDE (Colored - Dark Mocha Wood)
============================================================ -->
<section id="seo1" class="bg-dark-wood" style="padding: 90px 0;">
  <div class="container text-center position-relative" style="z-index:1;">
    <div class="animate-on-scroll">
      <span class="section-subtitle-tag tag-light"><i class="fas fa-info-circle"></i> دليل النظافة الذكي</span>
      <h2 class="section-title title-light mb-2">دليل النظافة والتعقيم الشامل بالدمام والمنطقة الشرقية</h2>
      <p class="section-subtitle sub-light">نصائح وإرشادات علمية لضمان بيئة صحية نقية وخالية من الجراثيم والأتربة</p>
    </div>

    <div class="row g-4 mt-2">
      <?php foreach ($seo_articles as $idx => $sa): 
        $delay = ($idx % 4) + 1;
      ?>
      <div class="col-lg-4 col-md-6 animate-on-scroll animate-delay-<?= $delay ?>">
        <div class="seo-card">
          <h5><i class="<?= htmlspecialchars($sa['icon']) ?> text-warning me-2"></i><?= htmlspecialchars($sa['title']) ?></h5>
          <p><?= htmlspecialchars($sa['description']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Wave Divider to Beige -->
<div class="wave-bg-beige wave-fill-dark" style="line-height:0;">
  <svg viewBox="0 0 1440 45" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C480,45 960,0 1440,35 L1440,0 L0,0 Z" fill="<?= htmlspecialchars($theme_secondary_color) ?>"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 13: CONTACT & BOOKING (Colored - Warm Beige)
============================================================ -->
<section id="contact" class="bg-warm-beige" style="padding: 90px 0;">
  <div class="container">
    <div class="text-center animate-on-scroll">
      <span class="section-subtitle-tag"><i class="fas fa-headset"></i> تواصل معنا الآن</span>
      <h2 class="section-title mb-2">نحن في خدمتك على مدار 24 ساعة</h2>
      <p class="section-subtitle">اتصل بنا أو أرسل تفاصيل طلبك لنصل إليك فوراً ونقدم لك أفضل عرض سعر مجاني</p>
    </div>

    <div class="row g-4 mt-2">
      <!-- Contact Info Card -->
      <div class="col-lg-5 animate-on-scroll animate-delay-1">
        <div class="contact-info-card">
          <h3 class="fw-bold mb-4" style="color:var(--accent-bright);"><i class="fas fa-phone-volume me-2"></i>بيانات الاتصال المباشرة</h3>

          <div class="contact-detail">
            <div class="contact-detail-icon" style="background:rgba(<?= $rgb_accent ?>, 0.2);color:var(--accent-bright);">
              <i class="fas fa-phone-alt"></i>
            </div>
            <div>
              <p>الرقم الأساسي (اتصال وواتساب)</p>
              <a href="tel:<?= htmlspecialchars(format_phone_tel($phone_primary)) ?>"><?= htmlspecialchars($phone_primary) ?></a>
            </div>
          </div>

          <div class="contact-detail">
            <div class="contact-detail-icon" style="background:rgba(37,211,102,0.2);color:#25D366;">
              <i class="fab fa-whatsapp"></i>
            </div>
            <div>
              <p>الرقم الثاني (متاح 24 ساعة)</p>
              <a href="tel:<?= htmlspecialchars(format_phone_tel($phone_secondary)) ?>"><?= htmlspecialchars($phone_secondary) ?></a>
            </div>
          </div>

          <div class="contact-detail">
            <div class="contact-detail-icon" style="background:rgba(<?= $rgb_accent ?>, 0.2);color:var(--accent-bright);">
              <i class="fas fa-map-location-dot"></i>
            </div>
            <div>
              <p>المقر والتغطية</p>
              <a href="#"><?= htmlspecialchars(get_setting('address', 'حي البادية، الدمام، المملكة العربية السعودية - نغطي الدمام والقطيف والخبر والجبيل والظهران وبقيق')) ?></a>
            </div>
          </div>

          <div class="contact-detail">
            <div class="contact-detail-icon" style="background:rgba(255,255,255,0.15);color:white;">
              <i class="fas fa-clock"></i>
            </div>
            <div>
              <p>أوقات العمل والمعاينات</p>
              <a href="#"><?= htmlspecialchars(get_setting('working_hours', 'متاحون 24/7 طوال أيام الأسبوع')) ?></a>
            </div>
          </div>

          <div class="mt-4 pt-2">
            <a href="<?= htmlspecialchars(format_whatsapp_link($whatsapp_number)) ?>" class="btn btn-hero-primary w-100 justify-content-center" target="_blank">
              <i class="fab fa-whatsapp me-2"></i> محادثة واتساب فورية
            </a>
            <a href="tel:<?= htmlspecialchars(format_phone_tel($phone_primary)) ?>" class="btn mt-2 w-100 justify-content-center" style="background:rgba(255,255,255,0.1);border:1.5px solid rgba(255,255,255,0.3);color:white;border-radius:50px;font-weight:700;">
              <i class="fas fa-phone-alt me-2"></i> اتصال مباشر الآن
            </a>
          </div>
        </div>
      </div>

      <!-- Form Card -->
      <div class="col-lg-7 animate-on-scroll animate-delay-2">
        <div class="form-card">
          <h3 class="fw-bold mb-4" style="color:var(--wood-walnut);">
            <i class="fas fa-paper-plane me-2 text-warning"></i>احجز موعدك واحصل على خصم <?= htmlspecialchars(get_setting('discount_percentage', '20%')) ?>
          </h3>
          <form id="contactForm">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label"><i class="fas fa-user me-1 text-warning"></i> الاسم الكريم</label>
                <input type="text" name="client_name" class="form-control" placeholder="أدخل اسمك" required />
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="fas fa-phone me-1 text-warning"></i> رقم الجوال</label>
                <input type="tel" name="phone" class="form-control" placeholder="05xxxxxxxx" required />
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="fas fa-sparkles me-1 text-warning"></i> نوع الخدمة</label>
                <select name="service_type" class="form-select" required>
                  <option value="" disabled selected>اختر الخدمة المطلوبة</option>
                  <?php if (!empty($services)): ?>
                    <?php foreach ($services as $srv): ?>
                      <option><?= htmlspecialchars($srv['title']) ?></option>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <option>تنظيف منازل</option>
                    <option>تنظيف سجاد</option>
                    <option>تنظيف مجالس</option>
                    <option>تنظيف كنب</option>
                    <option>تنظيف ارضيات</option>
                    <option>تنظيف خزانات المياه</option>
                    <option>تنظيف مكيفات</option>
                    <option>تنظيف مدارس</option>
                    <option>تنظيف شركات</option>
                  <?php endif; ?>
                  <option>باقة التنظيف الشاملة VIP</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="fas fa-calendar-alt me-1 text-warning"></i> موعد وتاريخ الخدمة المفضل</label>
                <input type="date" name="moving_date" class="form-control" />
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="fas fa-city me-1 text-warning"></i> المدينة والحي</label>
                <input type="text" name="from_area" class="form-control" placeholder="مثال: الدمام - حي الشاطئ" required />
              </div>
              <div class="col-md-6">
                <label class="form-label"><i class="fas fa-home me-1 text-warning"></i> نوع العقار / العنوان</label>
                <input type="text" name="to_area" class="form-control" placeholder="مثال: فيلا دورين / شقة / مكتب" />
              </div>
              <div class="col-12">
                <label class="form-label"><i class="fas fa-comment-dots me-1 text-warning"></i> تفاصيل إضافية عن المكان أو الخدمة</label>
                <textarea name="details" class="form-control" rows="3" placeholder="أدخل أي ملاحظات (المساحة التقريبية، عدد الغرف، نوع البقع أو طلبات خاصة...)"></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn-submit">
                  <i class="fas fa-paper-plane me-2"></i> <?= htmlspecialchars(get_setting('btn_submit_booking', 'إرسال طلب الحجز والحصول على الخصم')) ?>
                </button>
              </div>
            </div>
          </form>

          <div id="formSuccess" style="display:none;" class="mt-3 p-4 text-center">
            <i class="fas fa-check-circle fa-3x mb-2 d-block text-success"></i>
            <h4 class="fw-bold text-success mb-1">تم إرسال طلبك بنجاح!</h4>
            <p class="text-muted mb-0">سيتواصل معك مندوبنا خلال دقائق معدودة لتأكيد الموعد وتقديم أفضل خدمة تنظيف.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php 
$show_map = get_setting('show_map', '1');
$map_address = get_setting('map_address', 'حي البادية، الدمام، المملكة العربية السعودية');
$map_embed_code = get_setting('map_embed_code', '');
if ($show_map === '1' && (!empty($map_address) || !empty($map_embed_code))):
?>
<!-- Wave Divider to Map -->
<div class="wave-bg-clean wave-fill-beige" style="line-height:0;">
  <svg viewBox="0 0 1440 45" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;">
    <path d="M0,0 C480,45 960,0 1440,35 L1440,0 L0,0 Z" fill="rgba(<?= $rgb_primary ?>, 0.075)"/>
  </svg>
</div>

<!-- ============================================================
     SECTION 14: LOCATION MAP (Directly Above Footer)
============================================================ -->
<section id="location-map" class="bg-clean-light" style="padding: 70px 0 0 0; position:relative;">
  <div class="container text-center mb-4">
    <div class="animate-on-scroll">
      <span class="section-subtitle-tag"><i class="fas fa-map-pin"></i> موقعنا الجغرافي</span>
      <h2 class="section-title mb-2">تفضل بزيارتنا أو حدد موقعك لنصلك فوراً</h2>
      <p class="section-subtitle"><i class="fas fa-map-marker-alt text-danger me-1"></i> <?= htmlspecialchars($map_address) ?></p>
    </div>
  </div>

  <div class="map-wrapper" style="position:relative; width:100%; height:420px; overflow:hidden; box-shadow:0 -4px 20px rgba(0,0,0,0.06);">
    <?php if (!empty($map_embed_code)): ?>
      <?= $map_embed_code ?>
    <?php else: ?>
      <iframe 
        title="Google Map Location"
        src="https://maps.google.com/maps?q=<?= urlencode($map_address) ?>&t=&z=14&ie=UTF8&iwloc=&output=embed" 
        width="100%" 
        height="100%" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    <?php endif; ?>
    <div class="map-overlay-badge" style="position:absolute; bottom:20px; right:20px; background:rgba(255,255,255,0.95); backdrop-filter:blur(8px); padding:12px 20px; border-radius:14px; box-shadow:0 4px 15px rgba(0,0,0,0.15); display:inline-flex; align-items:center; gap:10px; z-index:2;">
      <i class="fas fa-location-dot" style="font-size:1.4rem; color:var(--primary);"></i>
      <div style="text-align:right;">
        <strong style="display:block; color:var(--dark); font-size:0.95rem;"><?= htmlspecialchars($site_name) ?></strong>
        <small style="color:var(--text-muted); font-size:0.82rem;"><?= htmlspecialchars($map_address) ?></small>
      </div>
      <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($map_address) ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-2" style="border-radius:20px; font-size:0.8rem; font-weight:700;">
        <i class="fas fa-directions me-1"></i> الاتجاهات
      </a>
    </div>
  </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
