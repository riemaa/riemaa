/**
 * سكربتات لوحة التحكم
 */

document.addEventListener('DOMContentLoaded', function () {
  // تفعيل القائمة الجانبية في الهواتف
  const toggleBtn = document.querySelector('.topbar-toggle');
  const sidebar = document.querySelector('.admin-sidebar');

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', function () {
      sidebar.classList.toggle('show');
    });

    // إغلاق القائمة عند النقر خارجها
    document.addEventListener('click', function (e) {
      if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target) && sidebar.classList.contains('show')) {
        sidebar.classList.remove('show');
      }
    });
  }

  // تأكيد الحذف
  document.querySelectorAll('.confirm-delete').forEach(btn => {
    btn.addEventListener('click', function (e) {
      if (!confirm('هل أنت متأكد من رغبتك في حذف هذا العنصر نهائياً؟')) {
        e.preventDefault();
      }
    });
  });

  // معاينة الصور فور اختيارها
  document.querySelectorAll('.img-preview-input').forEach(input => {
    input.addEventListener('change', function () {
      const targetId = this.dataset.previewTarget;
      const targetImg = document.getElementById(targetId);
      if (targetImg && this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          targetImg.src = e.target.result;
          targetImg.style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  });
});
