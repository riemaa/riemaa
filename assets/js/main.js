/**
 * السكربت التفاعلي الرئيسي لصفحة الهبوط
 */

document.addEventListener('DOMContentLoaded', function () {

  // ============================================================
  // PARTICLES EFFECT (HERO)
  // ============================================================
  (function createParticles() {
    const container = document.getElementById('particles');
    if (!container) return;
    const colors = ['#8B5A2B', '#D4A373', '#E6A15C', '#5C3A28', '#381E13'];
    for (let i = 0; i < 22; i++) {
      const p = document.createElement('div');
      const size = Math.random() * 55 + 18;
      p.className = 'particle';
      p.style.cssText = `
        width: ${size}px; height: ${size}px;
        left: ${Math.random() * 100}%;
        top: ${Math.random() * 100}%;
        background: ${colors[i % colors.length]};
        --dur: ${Math.random() * 4 + 4}s;
        --delay: ${Math.random() * 4}s;
      `;
      container.appendChild(p);
    }
  })();

  // ============================================================
  // NAVBAR SCROLL & SCROLL TO TOP
  // ============================================================
  const nav = document.getElementById('mainNav');
  const scrollTopBtn = document.getElementById('scrollTop');

  window.addEventListener('scroll', function () {
    if (nav) {
      nav.classList.toggle('scrolled', window.scrollY > 60);
    }
    if (scrollTopBtn) {
      if (window.scrollY > 400) {
        scrollTopBtn.classList.add('visible');
      } else {
        scrollTopBtn.classList.remove('visible');
      }
    }
  });

  if (scrollTopBtn) {
    scrollTopBtn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ============================================================
  // COUNTERS ANIMATION
  // ============================================================
  function animateCounter(el, target, duration) {
    let start = 0;
    const step = target / (duration / 16);
    const timer = setInterval(() => {
      start += step;
      if (start >= target) {
        el.textContent = target + '+';
        clearInterval(timer);
      } else {
        el.textContent = Math.floor(start);
      }
    }, 16);
  }

  let countersStarted = false;
  function startCounters() {
    if (countersStarted) return;
    countersStarted = true;
    document.querySelectorAll('[data-count]').forEach(el => {
      const target = parseInt(el.getAttribute('data-count'), 10);
      if (!isNaN(target)) {
        animateCounter(el, target, 2000);
      }
    });
  }

  // ============================================================
  // INTERSECTION OBSERVER FOR ANIMATIONS
  // ============================================================
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        if (entry.target.closest('#hero') || entry.target.id === 'hero') {
          startCounters();
        }
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
  startCounters(); // التحقق التلقائي عند التحميل

  // ============================================================
  // LIGHTBOX MODAL FOR GALLERY
  // ============================================================
  const galleryItems = document.querySelectorAll('.gallery-item');
  const lightboxModalEl = document.getElementById('lightboxModal');
  const lightboxImg = document.getElementById('lightboxImg');
  const lightboxCaption = document.getElementById('lightboxCaption');
  const lightboxPrev = document.getElementById('lightboxPrev');
  const lightboxNext = document.getElementById('lightboxNext');

  let activeIndex = 0;
  const galleryData = [];

  galleryItems.forEach((item, idx) => {
    const img = item.querySelector('img');
    const cap = item.querySelector('.gallery-overlay span');
    galleryData.push({
      src: img ? img.src : '',
      alt: img ? img.alt : '',
      caption: cap ? cap.textContent : (img ? img.alt : '')
    });

    item.addEventListener('click', () => {
      openLightbox(idx);
    });
  });

  function openLightbox(idx) {
    if (galleryData.length === 0) return;
    activeIndex = idx;
    updateLightbox();
    if (lightboxModalEl) {
      const modal = bootstrap.Modal.getOrCreateInstance(lightboxModalEl);
      modal.show();
    }
  }

  function updateLightbox() {
    if (!galleryData[activeIndex]) return;
    const current = galleryData[activeIndex];
    if (lightboxImg) lightboxImg.src = current.src;
    if (lightboxCaption) lightboxCaption.textContent = current.caption;
  }

  if (lightboxPrev) {
    lightboxPrev.addEventListener('click', (e) => {
      e.stopPropagation();
      activeIndex = (activeIndex + 1) % galleryData.length;
      updateLightbox();
    });
  }

  if (lightboxNext) {
    lightboxNext.addEventListener('click', (e) => {
      e.stopPropagation();
      activeIndex = (activeIndex - 1 + galleryData.length) % galleryData.length;
      updateLightbox();
    });
  }

  // دعم أسهم لوحة المفاتيح
  document.addEventListener('keydown', (e) => {
    if (!lightboxModalEl || !lightboxModalEl.classList.contains('show')) return;
    if (e.key === 'ArrowRight') {
      activeIndex = (activeIndex + 1) % galleryData.length;
      updateLightbox();
    } else if (e.key === 'ArrowLeft') {
      activeIndex = (activeIndex - 1 + galleryData.length) % galleryData.length;
      updateLightbox();
    }
  });

  // ============================================================
  // OFFCANVAS AUTO-CLOSE ON LINK CLICK
  // ============================================================
  const offcanvasEl = document.getElementById('navOffcanvas');
  if (offcanvasEl) {
    offcanvasEl.querySelectorAll('a[href^="#"]').forEach(a => {
      a.addEventListener('click', () => {
        const instance = bootstrap.Offcanvas.getInstance(offcanvasEl);
        if (instance) instance.hide();
      });
    });
  }

  // ============================================================
  // SMOOTH SCROLL FOR IN-PAGE ANCHORS
  // ============================================================
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;
      const target = document.querySelector(targetId);
      if (target) {
        e.preventDefault();
        const navHeight = nav ? nav.offsetHeight : 70;
        const targetPos = target.getBoundingClientRect().top + window.pageYOffset - (navHeight - 10);
        window.scrollTo({ top: targetPos, behavior: 'smooth' });
      }
    });
  });

  // ============================================================
  // AJAX BOOKING FORM SUBMISSION
  // ============================================================
  const contactForm = document.getElementById('contactForm');
  const formSuccess = document.getElementById('formSuccess');

  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const btn = contactForm.querySelector('.btn-submit');
      const originalText = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> جاري تأكيد الطلب...';
      btn.disabled = true;

      const formData = new FormData(contactForm);

      fetch('ajax/book.php', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          contactForm.style.display = 'none';
          if (formSuccess) {
            formSuccess.style.display = 'block';
            formSuccess.style.background = '#FFFFFF';
            formSuccess.style.borderRadius = '18px';
            formSuccess.style.padding = '2rem';
            formSuccess.style.border = '2px solid #22c55e';

            if (data.whatsapp_url) {
              const waBox = document.createElement('div');
              waBox.className = 'mt-3 text-center';
              waBox.innerHTML = `
                <a href="${data.whatsapp_url}" target="_blank" class="btn px-4 py-2 fw-bold text-white shadow-sm" style="background:#25D366;border-radius:50px;">
                  <i class="fab fa-whatsapp me-2"></i> اضغط هنا لفتح واتساب مباشرة
                </a>
              `;
              formSuccess.appendChild(waBox);

              // Auto open WhatsApp
              setTimeout(() => {
                const w = window.open(data.whatsapp_url, '_blank');
                if (!w) {
                  window.location.href = data.whatsapp_url;
                }
              }, 600);
            }
          }
        } else {
          alert(data.message || 'حدث خطأ أثناء إرسال الطلب، يرجى المحاولة لاحقاً أو الاتصال المباشر.');
          btn.innerHTML = originalText;
          btn.disabled = false;
        }
      })
      .catch(err => {
        contactForm.style.display = 'none';
        if (formSuccess) {
          formSuccess.style.display = 'block';
          formSuccess.style.background = '#FFFFFF';
          formSuccess.style.borderRadius = '18px';
          formSuccess.style.padding = '2rem';
          formSuccess.style.border = '2px solid #22c55e';
        }
      });
    });
  }

  // ============================================================
  // PWA (PROGRESSIVE WEB APP) SERVICE WORKER & INSTALL PROMPT
  // ============================================================
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js')
        .then(reg => {
          if (reg) reg.update();
          console.log('PWA Service Worker registered:', reg.scope);
        })
        .catch(err => {
          console.warn('PWA Service Worker registration failed:', err);
        });
    });
  }

  let deferredPrompt = null;
  const pwaInstallBtn = document.getElementById('pwaInstallBtn');
  const pwaBanner = document.getElementById('pwaBanner');
  const pwaBannerInstall = document.getElementById('pwaBannerInstall');
  const pwaBannerDismiss = document.getElementById('pwaBannerDismiss');
  const pwaGuideModalEl = document.getElementById('pwaGuideModal');
  const pwaModalInstallBtn = document.getElementById('pwaModalInstallBtn');

  // Floating install button is always visible
  if (pwaInstallBtn) {
    pwaInstallBtn.style.display = 'flex';
  }

  // Show banner after 2s if on mobile and not dismissed
  if (pwaBanner && !sessionStorage.getItem('pwa_dismissed') && window.innerWidth <= 768) {
    setTimeout(() => {
      pwaBanner.style.display = 'flex';
    }, 2000);
  }

  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;

    if (pwaBanner && !sessionStorage.getItem('pwa_dismissed')) {
      pwaBanner.style.display = 'flex';
    }
  });

  function triggerPwaInstall() {
    if (deferredPrompt) {
      deferredPrompt.prompt();
      deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
          if (pwaBanner) pwaBanner.style.display = 'none';
        }
        deferredPrompt = null;
      });
    } else {
      // Show instructions modal if native prompt is unavailable (e.g. Safari / iOS)
      if (pwaGuideModalEl && typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(pwaGuideModalEl);
        modal.show();
      } else {
        alert('لتثبيت التطبيق على جهازك: افتح خيارات المتصفح (زر المشاركة في آيفون أو الثلاث نقاط في أندرويد) واضغط على "إضافة إلى الشاشة الرئيسية".');
      }
    }
  }

  if (pwaInstallBtn) {
    pwaInstallBtn.addEventListener('click', triggerPwaInstall);
  }

  if (pwaBannerInstall) {
    pwaBannerInstall.addEventListener('click', triggerPwaInstall);
  }

  if (pwaModalInstallBtn) {
    pwaModalInstallBtn.addEventListener('click', triggerPwaInstall);
  }

  if (pwaBannerDismiss) {
    pwaBannerDismiss.addEventListener('click', () => {
      if (pwaBanner) pwaBanner.style.display = 'none';
      sessionStorage.setItem('pwa_dismissed', '1');
    });
  }

  window.addEventListener('appinstalled', () => {
    if (pwaBanner) pwaBanner.style.display = 'none';
    deferredPrompt = null;
  });

});
