<?php
/**
 * ذيل صفحة الهبوط ويتضمن الفوتر والأزرار العائمة ومودال الصور وسكربتات التفاعل
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$site_name         = get_setting('site_name', 'شركة ريمة للنظافة العامة بالدمام');
$site_short_name   = get_setting('site_short_name', 'ريمة للنظافة');
$site_tagline      = get_setting('site_tagline', 'لخدمات التنظيف والتعقيم الشامل للمنازل والفلل والمفروشات');
$site_logo         = get_setting('site_logo', '');
$site_icon         = get_setting('site_icon', 'fas fa-spray-can-sparkles');
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
            الخيار الأول والموثوق لخدمات النظافة الشاملة والتعقيم بالدمام والمنطقة الشرقية (الدمام، القطيف، الخبر، الجبيل، الظهران، بقيق). خبرة تفوق 12 عاماً في تقديم أرقى خدمات تنظيف المنازل، غسيل السجاد والمجالس والكنب بالبخار، جلي الأرضيات، تنظيف الخزانات، وغسيل المكيفات بضمان معتمد 100%.
          </p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <h6 style="color:var(--accent-bright);font-weight:900;margin-bottom:1.2rem;font-size:1.1rem;">خدماتنا المتخصصة</h6>
        <ul style="list-style:none;padding:0;">
          <li style="margin-bottom:0.6rem;"><a href="#services" style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> تنظيف منازل وفلل وشقق</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#services" style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> تنظيف سجاد وموكيت بالبخار</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#services" style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> تنظيف وتعقيم مجالس عربية</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#services" style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> تنظيف كنب ومفروشات</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#services" style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> جلي وتلميع أرضيات ورخام</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#services" style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> تنظيف وتعقيم خزانات المياه</a></li>
          <li style="margin-bottom:0.6rem;"><a href="#services" style="color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.92rem;"><i class="fas fa-chevron-left me-1 text-warning" style="font-size:0.7rem;"></i> غسيل وصيانة مكيفات سبليت</a></li>
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

    <div class="text-center" style="font-size:0.88rem;color:rgba(255,255,255,0.65);">
      <p class="mb-1">
        جميع الحقوق محفوظة © <?= date('Y') ?> | <?= htmlspecialchars($site_name) ?>
      </p>
      <p class="mb-0" style="font-size:0.85rem;color:rgba(255,255,255,0.75);">
        تصميم وبرمجة <a href="https://tab3na.com" target="_blank" rel="noopener" style="color:var(--accent-bright, #D4A373);text-decoration:none;font-weight:700;">مؤسسة تابعنا</a>
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
  <button type="button" id="pwaInstallBtn" class="float-btn float-btn-pwa" title="تثبيت التطبيق على جهازك">
    <i class="fas fa-download"></i>
    <span class="float-btn-badge">تطبيق</span>
  </button>
  <a href="<?= htmlspecialchars(format_whatsapp_link($whatsapp_number)) ?>" class="float-btn float-btn-whatsapp" target="_blank" title="محادثة واتساب">
    <i class="fab fa-whatsapp"></i>
  </a>
  <a href="tel:<?= htmlspecialchars(format_phone_tel($phone_primary)) ?>" class="float-btn float-btn-call" title="اتصال مباشر">
    <i class="fas fa-phone-alt"></i>
  </a>
</div>

<!-- PWA Installation Guide Modal -->
<div class="modal fade" id="pwaGuideModal" tabindex="-1" aria-labelledby="pwaGuideModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:24px;border:none;box-shadow:0 25px 60px rgba(0,0,0,0.3);overflow:hidden;">
      <div class="modal-header text-white" style="background:linear-gradient(135deg, var(--wood-walnut), var(--primary));padding:1.4rem;">
        <h5 class="modal-title fw-bold" id="pwaGuideModalLabel">
          <i class="fas fa-mobile-screen-button me-2 text-warning"></i> تثبيت تطبيق <?= htmlspecialchars($site_short_name) ?>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 text-center">
        <div style="width:70px;height:70px;background:linear-gradient(135deg, var(--primary), var(--accent-bright));border-radius:20px;display:inline-flex;align-items:center;justify-content:center;color:white;font-size:2rem;margin-bottom:1rem;box-shadow:0 8px 25px rgba(0,0,0,0.15);">
          <i class="<?= htmlspecialchars($site_icon) ?>"></i>
        </div>
        <h4 class="fw-bold mb-2" style="color:var(--text-dark);">تثبيت تطبيق <?= htmlspecialchars($site_short_name) ?></h4>
        <p class="text-muted small mb-4">احصل على وصول فوري ومباشر لخدمات النظافة والتعقيم والخصومات الخاصة من شاشة هاتفك الرئيسية دون تحميل من المتاجر</p>

        <div class="text-start bg-light p-3 rounded-4 mb-3" style="border:1px solid rgba(0,0,0,0.06);">
          <h6 class="fw-bold mb-2 text-dark"><i class="fab fa-apple text-dark me-1"></i> لمستخدمي آيفون وآيباد (Safari):</h6>
          <ol class="small text-muted mb-0 ps-3" style="line-height:1.8;">
            <li>اضغط على أيقونة <strong>المشاركة (Share <i class="fas fa-arrow-up-from-bracket"></i>)</strong> في شريط المتصفح السفلي.</li>
            <li>مرّر للأسفل واختر <strong>"إضافة إلى الشاشة الرئيسية" (Add to Home Screen <i class="fas fa-plus-square text-primary"></i>)</strong>.</li>
            <li>اضغط على <strong>"إضافة" (Add)</strong> في الزاوية العلوية لتثبيت التطبيق.</li>
          </ol>
        </div>

        <div class="text-start bg-light p-3 rounded-4 mb-3" style="border:1px solid rgba(0,0,0,0.06);">
          <h6 class="fw-bold mb-2 text-dark"><i class="fab fa-android text-success me-1"></i> لمستخدمي أندرويد والكمبيوتر (Chrome / Edge):</h6>
          <ol class="small text-muted mb-0 ps-3" style="line-height:1.8;">
            <li>اضغط على زر <strong>"تثبيت التطبيق الآن"</strong> أدناه لتثبيته فوراً.</li>
            <li>أو اضغط على زر الخيارات <i class="fas fa-ellipsis-v"></i> بأعلى المتصفح ثم اختر <strong>"تثبيت التطبيق"</strong>.</li>
          </ol>
        </div>

        <button type="button" id="pwaModalInstallBtn" class="btn btn-hero-primary w-100 py-3 fw-bold" style="border-radius:50px;">
          <i class="fas fa-download me-2"></i> تثبيت التطبيق الآن على جهازي
        </button>
      </div>
    </div>
  </div>
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
