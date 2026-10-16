<?php
/**
 * ذيل صفحة الهبوط ويتضمن الفوتر والأزرار العائمة ومودال الصور وسكربتات التفاعل
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
$footer_scripts    = get_setting('footer_scripts', '');
?>

<!-- ============================================================
     FOOTER (Dark Luxury Roasted Walnut)
============================================================ -->
<footer>
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="footer-brand">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="brand-icon" style="width:55px;height:55px;font-size:1.6rem;">
              <?php if (!empty($site_logo)): ?>
                <img src="<?= htmlspecialchars(asset_url($site_logo)) ?>" alt="<?= htmlspecialchars($site_name) ?>" />
              <?php else: ?>
                <i class="<?= htmlspecialchars($site_icon) ?>"></i>
              <?php endif; ?>
            </div>
            <h4 class="mb-0 fw-bold"><?= htmlspecialchars($site_name) ?><br>
              <small style="font-size:0.75rem;color:rgba(255,255,255,0.7);"><?= htmlspecialchars($site_tagline) ?></small>
            </h4>
          </div>
          <p style="color:rgba(255,255,255,0.7);line-height:1.8;font-size:0.92rem;">
            الخيار الأول والموثوق لنقل وتغليف وتخزين العفش بالرياض وضواحيها. خبرة تفوق 10 سنوات في تقديم خدمات الفك والتركيب والتغليف وأوناش الرفع والتخزين الآمن بضمان معتمد 100%.
          </p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <h6 style="color:var(--accent-bright);font-weight:900;margin-bottom:1.2rem;font-size:1.1rem;">خدماتنا المتخصصة</h6>
        <ul style="list-style:none;padding:0;">
          <li style="margin-bottom:0.6rem;"><a href="#services" style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> نقل عفش داخل وخارج الرياض</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#services" style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> فك وتركيب غرف ومطابخ</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#services" style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> تغليف بابلز وفوم وكرتون</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#fleet"    style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> ونش رفع هيدروليكي للأبراج</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#services" style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> مستودعات تخزين آمنة</a></li>
        </ul>
      </div>

      <div class="col-lg-2 col-md-6">
        <h6 style="color:var(--accent-bright);font-weight:900;margin-bottom:1.2rem;font-size:1.1rem;">روابط سريعة</h6>
        <ul style="list-style:none;padding:0;">
          <li style="margin-bottom:0.6rem;"><a href="#hero"       style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> الرئيسية</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#packages"   style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> باقات الأسعار</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#works"      style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> معرض الأعمال</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#guarantees" style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> ميثاق الضمانات</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#faq"        style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> الأسئلة الشائعة</a></li>
        </ul>
      </div>

      <div class="col-lg-3">
        <h6 style="color:var(--accent-bright);font-weight:900;margin-bottom:1.2rem;font-size:1.1rem;">اتصل بنا على مدار الساعة</h6>
        <div class="footer-phones">
          <a href="tel:<?= htmlspecialchars(format_phone_tel($phone_primary)) ?>" class="d-flex mb-2">
            <i class="fas fa-phone-alt me-2 text-warning"></i> <?= htmlspecialchars($phone_primary) ?>
          </a>
          <a href="tel:<?= htmlspecialchars(format_phone_tel($phone_secondary)) ?>" class="d-flex mb-2">
            <i class="fas fa-phone-alt me-2 text-warning"></i> <?= htmlspecialchars($phone_secondary) ?>
          </a>
          <a href="<?= htmlspecialchars(format_whatsapp_link($whatsapp_number)) ?>" class="d-flex" target="_blank" style="background:rgba(37,211,102,0.2);border-color:#25D366;">
            <i class="fab fa-whatsapp me-2" style="color:#25D366;"></i> واتساب <?= htmlspecialchars($phone_primary) ?>
          </a>
        </div>
        <p style="color:rgba(255,255,255,0.6);font-size:0.85rem;margin-top:1.2rem;">
          <i class="fas fa-clock me-1 text-warning"></i> <?= htmlspecialchars(get_setting('working_hours', 'متاحون 24/7 طوال أيام الأسبوع')) ?>
        </p>
      </div>
    </div>

    <hr style="border-color:rgba(255,255,255,0.15);margin:2rem 0 1.5rem;" />

    <div class="text-center" style="font-size:0.88rem;color:rgba(255,255,255,0.6);">
      <p class="mb-0">
        جميع الحقوق محفوظة © <?= date('Y') ?> | <?= htmlspecialchars($site_name) ?>
        <br>
        <span style="font-size:0.82rem;color:rgba(255,255,255,0.45);">
          خدمات نقل وتغليف وتخزين الأثاث بالرياض وكافة مناطق المملكة العربية السعودية
        </span>
      </p>
    </div>
  </div>
</footer>

<!-- ============================================================
     LIGHTBOX MODAL
============================================================ -->
<div class="modal fade" id="lightboxModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-3" style="position:relative;">
        <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);position:absolute;top:15px;left:15px;z-index:10;"></button>
        <button class="lightbox-nav prev" id="lightboxPrev" title="السابق"><i class="fas fa-chevron-right"></i></button>
        <button class="lightbox-nav next" id="lightboxNext" title="التالي"><i class="fas fa-chevron-left"></i></button>
        <img id="lightboxImg" src="" alt="صورة من أعمالنا" />
        <p id="lightboxCaption" style="color:var(--accent-bright);text-align:center;margin-top:1rem;font-size:1.05rem;font-weight:700;"></p>
      </div>
    </div>
  </div>
</div>

<!-- ============================================================
     FLOATING BUTTONS & PWA INSTALL
============================================================ -->
<div class="floating-buttons">
  <button type="button" id="pwaInstallBtn" class="float-btn float-btn-pwa" title="تثبيت التطبيق على جهازك" style="display:none;">
    <i class="fas fa-download"></i>
  </button>
  <a href="<?= htmlspecialchars(format_whatsapp_link($whatsapp_number)) ?>" class="float-btn float-btn-whatsapp" target="_blank" title="محادثة واتساب">
    <i class="fab fa-whatsapp"></i>
  </a>
  <a href="tel:<?= htmlspecialchars(format_phone_tel($phone_primary)) ?>" class="float-btn float-btn-call" title="اتصال مباشر">
    <i class="fas fa-phone-alt"></i>
  </a>
</div>

<!-- PWA Install Prompt Banner -->
<div id="pwaBanner" class="pwa-install-banner" style="display:none;">
  <div class="pwa-banner-content">
    <div class="pwa-banner-icon">
      <i class="fas fa-mobile-screen-button"></i>
    </div>
    <div class="pwa-banner-text">
      <strong>تطبيق <?= htmlspecialchars($site_short_name) ?></strong>
      <p>ثبّت التطبيق على هاتفك لتصفح أسرع وحجز فوري في أي وقت!</p>
    </div>
  </div>
  <div class="pwa-banner-actions">
    <button type="button" id="pwaBannerInstall" class="btn btn-pwa-install">تثبيت الآن</button>
    <button type="button" id="pwaBannerDismiss" class="btn btn-pwa-close" title="إغلاق"><i class="fas fa-times"></i></button>
  </div>
</div>

<!-- Scroll To Top -->
<button id="scrollTop" title="العودة للأعلى"><i class="fas fa-chevron-up"></i></button>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Main JS -->
<script src="assets/js/main.js"></script>

<?php if (!empty($footer_scripts)): ?>
  <?= $footer_scripts ?>
<?php endif; ?>

</body>
</html>
