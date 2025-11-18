</main> </div> </div> <script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const wrapper = document.getElementById('slideWrapper');
    const totalSlides = slides.length;

    function moveSlide(direction) {
      if(totalSlides === 0) return;
      currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
      const offset = -currentSlide * 100;
      if(wrapper) wrapper.style.transform = `translateX(${offset}%)`;
    }

    // Tự động chạy slide mỗi 5 giây
    if(totalSlides > 0) {
        setInterval(() => moveSlide(1), 5000);
    }
  </script>

  <style>
    .site-footer {
      background-color: #0f172a; /* Màu nền tối sang trọng */
      color: #94a3b8; /* Màu chữ xám sáng */
      padding: 60px 0 0;
      font-size: 0.95rem;
      margin-top: 60px;
    }
    
    .footer-content {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: grid;
      grid-template-columns: 1.2fr 0.8fr 1fr; /* Chia 3 cột */
      gap: 40px;
    }

    /* Cột 1: Brand */
    .footer-brand p { margin-top: 15px; line-height: 1.6; }
    .footer-logo {
      display: flex; align-items: center; gap: 10px;
      color: white; font-weight: 700; font-size: 1.3rem;
    }
    .footer-logo img { height: 40px; background: white; padding: 2px; border-radius: 5px; }

    /* Cột 2, 3: Title */
    .footer-title {
      color: white;
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 20px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      position: relative;
      display: inline-block;
    }
    .footer-title::after {
      content: '';
      position: absolute;
      left: 0; bottom: -5px;
      width: 40px; height: 3px;
      /* background: var(--primary-color); */
      border-radius: 2px;
    }

    /* Links */
    .footer-links ul { padding: 0; list-style: none; }
    .footer-links li { margin-bottom: 12px; }
    .footer-links a {
      color: #94a3b8;
      transition: 0.3s;
      display: flex; align-items: center; gap: 8px;
    }
    .footer-links a:hover {
      color: var(--accent-color);
      transform: translateX(5px);
    }

    /* Contact Info */
    .contact-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      margin-bottom: 15px;
    }
    .contact-item i {
      color: var(--primary-color);
      margin-top: 5px;
    }

    /* Social Icons */
    .social-links {
      display: flex; gap: 15px; margin-top: 20px;
    }
    .social-btn {
      width: 36px; height: 36px;
      border-radius: 50%;
      background: rgba(255,255,255,0.1);
      display: flex; align-items: center; justify-content: center;
      color: white;
      transition: 0.3s;
    }
    .social-btn:hover {
      background: var(--primary-color);
      transform: translateY(-3px);
    }

    /* Copyright */
    .footer-bottom {
      background: #020617;
      padding: 20px 0;
      margin-top: 50px;
      text-align: center;
      border-top: 1px solid rgba(255,255,255,0.05);
      font-size: 0.85rem;
    }
  </style>

  <footer class="site-footer">
    <div class="footer-content">
      
      <div class="footer-col footer-brand">
        <div class="footer-logo">
          <img src="images/images.png" alt="Logo">
          <span>CLB TIN HỌC TVU</span>
        </div>
        <p>
          Nơi kết nối đam mê công nghệ, chia sẻ kiến thức và cùng nhau phát triển. 
          Tham gia cùng chúng tôi để trải nghiệm môi trường học tập năng động tại Đại học Trà Vinh.
        </p>
        <div class="social-links">
          <a href="#" class="social-btn"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="social-btn"><i class="fa-brands fa-youtube"></i></a>
          <a href="#" class="social-btn"><i class="fa-brands fa-tiktok"></i></a>
        </div>
      </div>

      <div class="footer-col footer-links">
        <h3 class="footer-title">Liên kết nhanh</h3>
        <ul>
          <li><a href="index.php"><i class="fa-solid fa-chevron-right" style="font-size:0.7em"></i> Trang chủ</a></li>
          <li><a href="#"><i class="fa-solid fa-chevron-right" style="font-size:0.7em"></i> Giới thiệu</a></li>
          <li><a href="contact.php"><i class="fa-solid fa-chevron-right" style="font-size:0.7em"></i> Liên hệ góp ý</a></li>
          <li><a href="signup.php"><i class="fa-solid fa-chevron-right" style="font-size:0.7em"></i> Đăng ký thành viên</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h3 class="footer-title">Thông tin liên hệ</h3>
        <div class="contact-info">
          <div class="contact-item">
            <i class="fa-solid fa-location-dot"></i>
            <span>Số 126 Nguyễn Thiện Thành, Phường Hòa Thuận, Tỉnh Vĩnh Long</span>
          </div>
          <div class="contact-item">
            <i class="fa-solid fa-phone"></i>
            <span>0123456789</span>
          </div>
          <div class="contact-item">
            <i class="fa-solid fa-envelope"></i>
            <span>clbtinhoc@tvu.edu.vn</span>
          </div>
          <div class="contact-item">
            <i class="fa-solid fa-globe"></i>
            <span>www.tvu.edu.vn</span>
          </div>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <div class="container">
        Copyright &copy; 2025 Đại Học Trà Vinh. All rights reserved. <br>
        Design by CLB Tin Học
      </div>
    </div>
  </footer>

</body>
</html>
<?php
    ob_end_flush();
?>