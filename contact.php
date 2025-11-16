<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';  // Đường dẫn đến file autoload của PHPMailer (nếu dùng Composer)
require("phandau.php");

?>
<?php
if(!isset($_SESSION['emailUser'])){
    echo "<script> alert(' Bạn chưa đăng nhập');";
    echo "window.location.assign('index.php');";
    echo "</script>";
}

if(isset($_REQUEST['sbgui'])){
    $ten = $_REQUEST['name'];
    $email = $_REQUEST['email'];
    $noidung =$_REQUEST['comments'];
    $sql = "insert into tblcontact(Tennguoigui,Noidung,Email,Ngaygui,Trangthai) values('$ten','$noidung','$email',now(),0)";
    if($conn->query($sql)){
        echo "<script> alert('Gửi thông tin thành công!');";
        echo "window.location.assign('contact.php');";
        echo "</script>";
    }else{
        echo "Lỗi khi thêm vào";
    }
}
?>

<div class="container-fluid bg-grey">
  <h2 class="text-center">Liên hệ</h2>
  <div class="row">
    <div class="col-sm-5">
      <p>Vui lòng cho chúng tôi thông tin bạn quan tâm</p>
      <p><span class="glyphicon glyphicon-map-marker"></span> Số 126 Nguyễn Thiện Thành - Khóm 4, Phường 5, Thành phố Trà Vinh, tỉnh Trà Vinh</p>
      <p><span class="glyphicon glyphicon-phone"></span> (+84).2943.855.246</p>
      <p><span class="glyphicon glyphicon-envelope"></span> tvu@tvu.edu.vn</p>
    </div>
    <div class="col-sm-7">
    <form action="" name="fcontact" method="post">
      <div class="row">
        <div class="col-sm-6 form-group">
          <input class="form-control" id="name" name="name" placeholder="Họ và tên" type="text" required>
        </div>
        <div class="col-sm-6 form-group">
          <input class="form-control" id="email" name="email" placeholder="Email" type="email" required>
        </div>
      </div>
      <textarea class="form-control" id="comments" name="comments" placeholder="Góp ý " rows="5"></textarea><br>
      <div class="row">
        <div class="col-sm-12 form-group">
        <button type="submit" class="btn btn-default" name="sbgui">Gửi</button>
        </div>
      </div>
</form>
    </div>
  </div>
</div>
<?php
    require("phancuoi.php");
?>