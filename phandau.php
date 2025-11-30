<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require("config.php");

if (isset($_SESSION['emailUser'])) {
    $email = $_SESSION['emailUser'];

    $sqlbs = "SELECT * FROM tbluser WHERE email = '$email'";
    $resultbs = $conn->query($sqlbs);

    if ($resultbs && $resultbs->num_rows > 0) {
        $row = $resultbs->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['role']     = $row['role'];
        $_SESSION['fullname'] = $row['fullname'];
        $_SESSION['avatar']   = $row['avatar'];

    } else {

        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <title>CLB Tin Học TVU - Trang chủ</title>
  <link rel="icon" href="images/images.png">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    /* --- CSS CHUNG --- */
    :root { --primary-color: #2563eb; --primary-hover: #1d4ed8; --accent-color: #0ea5e9; --bg-body: #f8fafc; --bg-card: #ffffff; --text-main: #1e293b; --text-secondary: #64748b; --shadow-sm: 0 1px 3px rgba(0,0,0,0.1); --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1); --radius: 12px; --nav-height: 70px; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background-color: var(--bg-body); color: var(--text-main); line-height: 1.6; padding-top: var(--nav-height); }
    a { text-decoration: none; color: inherit; transition: 0.3s; }
    ul { list-style: none; }
    img { max-width: 100%; display: block; }
    .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

    /* --- NAVBAR --- */
    .navbar { position: fixed; top: 0; left: 0; right: 0; height: var(--nav-height); background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,0,0,0.05); z-index: 1000; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; box-shadow: var(--shadow-sm); }
    .brand { display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 1.2rem; color: var(--primary-color); }
    .brand img { height: 36px; }
    .nav-links { display: flex; gap: 30px; align-items: center; }
    .nav-links a { font-weight: 500; color: var(--text-secondary); display: flex; align-items: center; gap: 8px; }
    .nav-links a:hover, .nav-links a.active { color: var(--primary-color); }
    .btn { padding: 8px 20px; border-radius: 8px; font-weight: 600; display: inline-block; }
    .btn-primary { background: var(--primary-color); color: white !important; box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.39); }
    .btn-primary:hover { transform: translateY(-1px); background: var(--primary-hover); }

    /* --- USER MENU --- */
    .user-menu { position: relative; cursor: pointer; }
    .user-info { display: flex; align-items: center; gap: 8px; padding: 5px 12px; background: #eff6ff; border-radius: 30px; color: var(--primary-color); font-weight: 600; border: 1px solid #dbeafe; }
    .dropdown-content { position: absolute; top: 120%; right: 0; background: white; min-width: 220px; border-radius: var(--radius); box-shadow: var(--shadow-md); padding: 10px 0; opacity: 0; visibility: hidden; transform: translateY(10px); transition: 0.2s ease; border: 1px solid #e2e8f0; z-index: 1001; }
    .user-menu:hover .dropdown-content { opacity: 1; visibility: visible; transform: translateY(0); }
    .dropdown-content a { display: block; padding: 10px 20px; color: var(--text-main); font-size: 0.95rem; }
    .dropdown-content a:hover { background: #f1f5f9; color: var(--primary-color); }
    .divider { height: 1px; background: #e2e8f0; margin: 5px 0; }

    /* --- SLIDER --- */
    .slider-container { margin-top: 20px; position: relative; width: 100%; height: 400px; overflow: hidden; border-radius: var(--radius); box-shadow: var(--shadow-md); }
    .slides { display: flex; width: 100%; height: 100%; transition: transform 0.5s ease-in-out; }
    .slide { min-width: 100%; position: relative; }
    .slide img { width: 100%; height: 100%; object-fit: cover; }
    .caption { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); color: white; padding: 40px 20px 20px; }
    .caption h3 { font-size: 1.5rem; margin-bottom: 5px; }
    .slider-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.8); border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 1.2rem; color: var(--text-main); display: flex; align-items: center; justify-content: center; transition: 0.2s; z-index: 10; }
    .slider-btn:hover { background: white; box-shadow: 0 0 10px white; }
    .prev { left: 20px; }
    .next { right: 20px; }

    /* --- LAYOUT --- */
    .main-content-wrapper { display: grid; grid-template-columns: 280px 1fr; gap: 30px; margin-top: 40px; margin-bottom: 60px; }
    
    /* --- SIDEBAR --- */
    .sidebar-card { background: var(--bg-card); border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow-sm); margin-bottom: 20px; border: 1px solid #e2e8f0; }
    .sidebar-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid var(--bg-body); color: var(--text-main); display: flex; align-items: center; gap: 10px; }
    .sidebar-menu a { display: block; padding: 10px 12px; border-radius: 8px; color: var(--text-secondary); font-weight: 500; margin-bottom: 5px; transition: all 0.2s; }
    .sidebar-menu a:hover { background: #eff6ff; color: var(--primary-color); transform: translateX(5px); }
    .admin-panel { border-left: 4px solid var(--primary-color); }

    /* --- CONTENT SECTIONS (HOẠT ĐỘNG & BÀI VIẾT) --- */
    .content-section { background: var(--bg-card); border-radius: var(--radius); padding: 25px; box-shadow: var(--shadow-sm); margin-bottom: 30px; border: 1px solid #e2e8f0; }
    .section-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid var(--bg-body); }
    .section-header h3 { font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }
    .text-danger { color: #ef4444; }
    .text-info { color: #0ea5e9; }
    
    /* Style cho Danh sách hoạt động */
    .item-list { display: flex; flex-direction: column; gap: 20px; }
    .item-card { display: flex; gap: 15px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9; transition: transform 0.2s; }
    .item-card:last-child { border-bottom: none; padding-bottom: 0; }
    .item-card:hover { transform: translateX(5px); }
    .date-badge { min-width: 70px; height: 70px; background: #fef2f2; color: #ef4444; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: center; font-weight: 700; border: 1px solid #fee2e2; }
    .date-badge i { font-size: 1.8rem; margin-bottom: 4px; }
    .item-content { flex: 1; }
    .item-title { font-size: 1.05rem; font-weight: 600; margin-bottom: 8px; line-height: 1.4; display: block; color: var(--text-main); }
    .item-title:hover { color: var(--primary-color); }
    .item-meta { font-size: 0.9rem; color: var(--text-secondary); display: flex; flex-wrap: wrap; gap: 15px; }
    .item-meta span { display: flex; align-items: center; gap: 6px; }

    /* Style cho Danh sách bài viết (Feed) */
    .feed-list { display: flex; flex-direction: column; gap: 20px; }
    .feed-item { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: transform 0.2s; }
    .feed-item:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .feed-header { display: flex; align-items: center; gap: 12px; margin-bottom: 15px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; }
    .feed-avatar { width: 45px; height: 45px; background: #e0f2fe; color: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; overflow: hidden; }
    .feed-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .feed-info { display: flex; flex-direction: column; }
    .feed-author { font-weight: 700; color: var(--text-main); font-size: 1rem; }
    .feed-time { font-size: 0.8rem; color: var(--text-secondary); display: flex; align-items: center; gap: 5px; }
    .feed-content { color: var(--text-main); font-size: 1rem; line-height: 1.6; }
    .feed-content p { margin-bottom: 10px; text-align: justify; }
    .feed-readmore { display: inline-block; margin-top: 10px; color: var(--primary-color); font-weight: 600; font-size: 0.9rem; text-decoration: none; }
    .feed-readmore:hover { text-decoration: underline; }

    /* Responsive */
    @media (max-width: 768px) { 
        .main-content-wrapper { grid-template-columns: 1fr; } 
        .nav-links { display: none; } 
        .navbar { padding: 0 20px; } 
        .slider-container { height: 250px; } 
    }
  </style>
</head>
<body>

  <nav class="navbar">
    <a class="brand" href="index.php">
      <img src="images/images.png" alt="CLB Logo">
      <span>CLB TIN HỌC TVU</span>
    </a>
    <div class="nav-links">
      <a href="index.php" class="active"><i class="fa-solid fa-house"></i> Trang chủ</a>
      <a href="contact.php"><i class="fa-solid fa-envelope"></i> Liên hệ</a>
      
      <?php if (isset($_SESSION["emailUser"])) { 
          // Logic chọn Avatar
          $avatarPath = 'images/default.png'; // Ảnh mặc định nếu không có
          if (isset($_SESSION['avatar']) && !empty($_SESSION['avatar'])) {
              $avatarPath = $_SESSION['avatar'];
          }
      ?>
          <div class="user-menu">
              <div class="user-info">
                  <img src="<?= $avatarPath ?>" alt="Avatar" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 1px solid #ccc;">
                  <span>Hi, <?php echo $_SESSION['fullname']; ?></span>
                  <i class="fa-solid fa-chevron-down" style="font-size: 0.8em"></i>
              </div>
              
              <div class="dropdown-content">
                  <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1) { ?>
                      <a href="admin/index.php" style="color: #2563eb; font-weight: bold;">
                          <i class="fa-solid fa-gauge-high"></i> Trang quản trị
                      </a>
                      <div class="divider"></div>
                  <?php } ?>

                  <a href="changepassword.php"><i class="fa-solid fa-key"></i> Đổi mật khẩu</a>
                  <div class="divider"></div>
                  <a href="logout.php" style="color: #ef4444;"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
              </div>
          </div>
      <?php } else { ?>
          <a href="signup.php">Đăng ký</a>
          <a href="login.php" class="btn btn-primary">Đăng nhập</a>
      <?php } ?>
    </div>
  </nav>

  <div class="container">
    <?php
    $sql = "SELECT * FROM tblslideshow WHERE Status = 0";
    $rs  = $conn->query($sql);
    if ($rs && $rs->num_rows > 0) { ?>
      <div class="slider-container">
        <div class="slides" id="slideWrapper">
          <?php while ($r = $rs->fetch_assoc()) { ?>
            <div class="slide">
              <img src="<?= $r['ImageUrl'] ?>" alt="Slide Image">
              <?php if(!empty($r['Title'])) { ?>
                <div class="caption"><h3><?= $r['Title'] ?></h3><p><?= $r['Description'] ?></p></div>
              <?php } ?>
            </div>
          <?php } ?>
        </div>
        <button class="slider-btn prev" onclick="moveSlide(-1)"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="slider-btn next" onclick="moveSlide(1)"><i class="fa-solid fa-chevron-right"></i></button>
      </div>
    <?php } ?>

    <div class="main-content-wrapper">
      
      <aside class="sidebar">
        <?php if (isset($_SESSION['emailUser']) && $_SESSION['role'] == 1) { ?>
          <div class="sidebar-card admin-panel">
            <div class="sidebar-title"><i class="fa-solid fa-gear"></i> Quản trị</div>
            <div class="sidebar-menu">
              <a href="admin/index.php">Vào trang quản trị</a>
            </div>
          </div>
        <?php } ?>

        <div class="sidebar-card">
          <?php
          $sqlcd = "SELECT * FROM tblchude WHERE Trangthai = 0";
          $resultcd = $conn->query($sqlcd);
          $sl = ($resultcd) ? $resultcd->num_rows : 0;
          ?>
          <div class="sidebar-title"><i class="fa-solid fa-list-ul"></i> Chủ đề (<?= $sl ?>)</div>
          <div class="sidebar-menu">
            <?php 
            if ($sl > 0) {
              while ($r = $resultcd->fetch_assoc()) { ?>
                <a href="baiviet.php?machude=<?= $r['Machude'] ?>"><?= $r['Tenchude'] ?></a>
              <?php }
            } else { echo "<p style='padding:10px; color:#999; font-size:0.9rem'>Chưa có chủ đề</p>"; }
            ?>
          </div>
        </div>
        
        <div class="sidebar-card">
             <div class="sidebar-menu">
                <a href="#" style="text-align: center; color: var(--primary-color); font-weight: 700;">Xem tất cả hoạt động <i class="fa-solid fa-arrow-right"></i></a>
             </div>
        </div>
      </aside>
      
      <main class="content">
          
          <div class="content-section">
            <div class="section-header">
                <i class="fa-solid fa-bullhorn text-danger"></i>
                <h3>Hoạt động sắp diễn ra</h3>
            </div>
            <div class="item-list">
                <?php
                $sql_hoatdong = "SELECT * FROM tblhoatdong WHERE trang_thai = 0 AND ngay_bat_dau >= NOW() ORDER BY ngay_bat_dau ASC LIMIT 3";
                $result_hoatdong = $conn->query($sql_hoatdong);

                if ($result_hoatdong && $result_hoatdong->num_rows > 0) {
                    while ($hd = $result_hoatdong->fetch_assoc()) {
                ?>
                    <div class="item-card">
                        <div class="date-badge">
                            <i class="fa-regular fa-calendar-days"></i>
                        </div>
                        <div class="item-content">
                            <a href="#" class="item-title"><?php echo $hd['ten_hoat_dong']; ?></a>
                            <div class="item-meta">
                                <span><i class="fa-regular fa-clock"></i> <?php echo date('H:i d/m/Y', strtotime($hd['ngay_bat_dau'])); ?></span>
                                <span><i class="fa-solid fa-location-dot"></i> <?php echo $hd['dia_diem']; ?></span>
                            </div>
                        </div>
                    </div>
                <?php 
                    }
                } else {
                    echo "<div class='text-center text-secondary py-3'>Hiện chưa có hoạt động nào sắp diễn ra.</div>";
                }
                ?>
            </div>
          </div>

          <div class="content-section">
            <div class="section-header">
                <i class="fa-solid fa-newspaper text-info"></i>
                <h3>Bài viết mới nhất</h3>
            </div>
            <div class="feed-list">
                <?php
                $sql_baiviet = "SELECT * FROM tblbaiviet WHERE Trangthai = 0 ORDER BY Ngaytao DESC LIMIT 5";
                $result_baiviet = $conn->query($sql_baiviet);

                if ($result_baiviet && $result_baiviet->num_rows > 0) {
                    while ($bv = $result_baiviet->fetch_assoc()) {
                ?>
                    <div class="feed-item">
                        <div class="feed-header">
                            <div class="feed-avatar">
                                <i class="fa-solid fa-circle-user"></i>
                            </div>
                            <div class="feed-info">
                                <span class="feed-author"><?php echo $bv['Username']; ?></span>
                                <span class="feed-time">
                                    <?php echo date('d/m/Y', strtotime($bv['Ngaytao'])); ?> 
                                    <i class="fa-solid fa-earth-americas" style="font-size: 10px; margin-left: 4px;"></i>
                                </span>
                            </div>
                        </div>

                        <div class="feed-content">
                            <p>
                                <?php 
                                    $noidung = strip_tags($bv['Noidung']);
                                    if(strlen($noidung) > 300) echo substr($noidung, 0, 300) . "...";
                                    else echo $noidung;
                                ?>
                            </p>
                            <a href="chitietbaiviet.php?id=<?php echo $bv['Mabaiviet']; ?>" class="feed-readmore">
                                Xem chi tiết bài viết
                            </a>
                        </div>
                    </div>
                <?php
                    }
                } else {
                    echo "<div class='text-center text-secondary py-3'>Hiện chưa có bài viết nào.</div>";
                }
                ?>
            </div>
          </div>

      </main>
    </div>
  </div>

  <script>
      let slideIndex = 0;
      const slides = document.querySelectorAll('.slide');
      const wrapper = document.getElementById('slideWrapper');
      
      // Auto slide
      if(slides.length > 0) {
          setInterval(() => { moveSlide(1) }, 5000);
      }

      function moveSlide(n) {
          if(slides.length === 0) return;
          slideIndex += n;
          if (slideIndex >= slides.length) { slideIndex = 0; }
          if (slideIndex < 0) { slideIndex = slides.length - 1; }
          
          wrapper.style.transform = `translateX(-${slideIndex * 100}%)`;
      }
  </script>

</body>
</html>
<?php
ob_end_flush(); // Kết thúc bộ đệm
?>