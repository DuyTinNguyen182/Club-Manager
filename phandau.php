<?php
ob_start();
session_start();
require("config.php");

// Kiểm tra user login
if (isset($_SESSION['emailUser'])) {
  $email = $_SESSION['emailUser'];
  $sqlbs = "SELECT * FROM tbluser WHERE email = '$email'";
  $resultbs = $conn->query($sqlbs);

  if ($resultbs->num_rows > 0) {
    $row = $resultbs->fetch_assoc();
    $_SESSION['username'] = $row['username'];
    $_SESSION['role']     = $row['role'];
  } else {
    unset($_SESSION['token']);
    session_destroy();
    unset($_SESSION['emailUser']);
    unset($_SESSION['role']);
    header("Location: login.php");
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>CLB Tin Học TVU</title>
  <link rel="icon" href="images/images.png">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <style>
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }

    footer {
      padding: 25px;
    }

    .carousel-inner img {
      width: 100%;
      margin: auto;
      min-height: 200px;
      max-height: 300px;
    }

    @media (max-width: 600px) {
      .carousel-caption {
        display: none;
      }
    }
  </style>
</head>

<body>

  <!-- NAVIGATION -->
  <!-- ================================================================ -->
  <!--                      PHẦN NAVIGATION ĐÃ NÂNG CẤP                 -->
  <!-- ================================================================ -->
  <nav class="navbar navbar-default navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">
          <img src="images/images.png" alt="CLB Logo" style="height: 30px; display: inline-block; margin-top: -5px;">
          <span>CLB TIN HỌC TVU</span>
        </a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li class="active">
            <a href="index.php">
              <span class="glyphicon glyphicon-home"></span> Trang chủ
            </a>
          </li>
          <li>
            <a href="contact.php">
              <span class="glyphicon glyphicon-envelope"></span> Liên hệ
            </a>
          </li>
          <!-- Thêm các mục menu khác ở đây -->
        </ul>

        <ul class="nav navbar-nav navbar-right">
          <?php if (isset($_SESSION["emailUser"])) { ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <span class="glyphicon glyphicon-user"></span> Xin chào, <?php echo $_SESSION['username']; ?> <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="changepassword.php"><span class="glyphicon glyphicon-pencil"></span> Đổi mật khẩu</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
              </ul>
            </li>
          <?php } else { ?>
            <li><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Đăng ký</a></li>
            <li><a href="login.php" class="btn btn-primary" style="padding: 10px 15px; margin-top: 8px; color: white;">
                <span class="glyphicon glyphicon-log-in"></span> Đăng nhập
              </a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>

  <style>
    .navbar-default .navbar-brand,
    .navbar-default .navbar-nav>li>a {
      color: #337ab7;
      font-weight: bold;
    }

    .navbar-default .navbar-nav>li>a:hover,
    .navbar-default .navbar-nav>li>a:focus {
      color: #23527c;
    }

    .navbar-default .navbar-nav>.active>a,
    .navbar-default .navbar-nav>.active>a:hover,
    .navbar-default .navbar-nav>.active>a:focus {
      color: #ffffff;
      background-color: #337ab7;
    }

    .navbar-brand span {
      vertical-align: middle;
    }
  </style>

  <!-- SLIDESHOW -->
  <?php
  $sql = "SELECT * FROM tblslideshow WHERE Status = 0";
  $rs  = $conn->query($sql);

  if ($rs->num_rows > 0) {
    $sodong = $rs->num_rows;
  ?>
    <div id="myCarousel" class="carousel slide" data-ride="carousel">

      <!-- Indicators -->
      <ol class="carousel-indicators">
        <?php for ($i = 0; $i < $sodong; $i++) { ?>
          <li data-target="#myCarousel" data-slide-to="<?= $i ?>" class="<?= $i == 0 ? 'active' : '' ?>"></li>
        <?php } ?>
      </ol>

      <!-- Slides -->
      <div class="carousel-inner" role="listbox">
        <?php
        $j = 0;
        while ($r = $rs->fetch_assoc()) { ?>
          <div class="item <?= $j == 0 ? 'active' : '' ?>">
            <img src="<?= $r['ImageUrl'] ?>" alt="Image">
            <div class="carousel-caption">
              <h3><?= $r['Title'] ?></h3>
              <p><?= $r['Description'] ?></p>
            </div>
          </div>
        <?php $j = 1;
        } ?>
      </div>

      <!-- Controls -->
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>

      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>

    </div>
  <?php } ?>

  <!-- MAIN CONTENT -->
  <div class="container-fluid">
    <br>
    <div class="row">

      <!-- LEFT SIDEBAR -->
      <div class="col-sm-3">

        <?php if (isset($_SESSION['emailUser']) && $_SESSION['role'] == 1) { ?>
          <div class="panel panel-primary">
            <div class="panel-heading">Tùy chọn</div>
            <div class="panel-body"><a href="upload.php">Đăng tải tệp trình chiếu</a></div>
            <div class="panel-body"><a href="quanlychude.php">Chủ đề</a></div>
            <div class="panel-body"><a href="quanlycontact.php">Liên hệ</a></div>
          </div>
        <?php } ?>

        <div class="panel panel-success">
          <?php
          $sqlcd = "SELECT * FROM tblchude WHERE Trangthai = 0";
          $resultcd = $conn->query($sqlcd);

          if ($resultcd->num_rows > 0) {
            $sl = $resultcd->num_rows;
          ?>
            <div class="panel-heading">Danh mục chủ đề (<?= $sl ?>)</div>

            <?php while ($r = $resultcd->fetch_assoc()) { ?>
              <div class="panel-body">
                <a href="baiviet.php?machude=<?= $r['Machude'] ?>">
                  <?= $r['Tenchude'] ?>
                </a>
              </div>
          <?php }
          } else {
            echo "<div class='panel-body'>Chưa có chủ đề</div>";
          }
          ?>
        </div>
        <a href="#">Tất cả hoạt động</a>
      </div>

      <!-- RIGHT CONTENT -->
      <div class="col-sm-9">