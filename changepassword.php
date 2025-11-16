<?php
require("phandau.php");
?>

<?php
if(!isset($_SESSION['emailUser'])){
    echo "<script> alert('Login');";
    echo "window.location.assign('login.php');";
    echo "</script>";
  }
?>

<script language='javascript'>
function kiemtra(){
	var pass1 = document.f1.txtMatkhaum.value;
	var pass2 = document.f1.txtreMatkhaum.value;
	if(pass1 != pass2){
		alert("Mật khẩu không trung khớp");
		return false;		
	}
	
}
</script>
<?php
if(isset($_SESSION['username'])){
    $ten = $_SESSION['username'];
}else{
    $email = $_SESSION['emailUser'];
    $sql="select * from tbluser where email='$email'";
    $result = $conn->query($sql);
    if($result->num_rows>0){
        $row = $result->fetch_assoc();
        $ten = $row['username'];
    }
}
?>
<?php
// kiểm tra mật khẩu hiện tại
if(isset($_REQUEST['sbsua'])){
    $tendn = $_REQUEST['txtTendangnhap'];
    $mkht = md5($_REQUEST['txtMatkhauht']);
    $sql="select * from tbluser where Username ='$tendn' and Password='$mkht'";
    $result = $conn->query($sql);
    if($result->num_rows>0){
        $mkmoi =md5($_REQUEST['txtMatkhaum']);
        $nlmk = md5($_REQUEST['txtreMatkhaum']);
        $sqldoi ="update tbluser set Password='$mkmoi' where Username='$tendn'";
        $conn->query($sqldoi);
        echo "<script language='javascript'>alert('Đổi thành công mk!');</script>";

    }else{
        echo "<script language='javascript'>alert('Mk hiện tại không đúng!');</script>";
    }
}   
?>

<h3>Trang đăng ký </h3>
<form action="" method="post" name="f1" onsubmit="return kiemtra();" enctype="multipart/form-data">
    <div class="form-group">
      <label for="usr">Tên đăng nhập:</label>
      <input type="text" class="form-control" id="usr" name="txtTendangnhap"  required readonly value="<?php echo @$ten;?>">
    </div>
    <div class="form-group">
      <label for="pwd">Mật khẩu hiện tại:</label>
      <input type="password" class="form-control" id="pwdht"  name="txtMatkhauht" required >
    </div>
    <div class="form-group">
      <label for="pwd">Mật khẩu:</label>
      <input type="password" class="form-control" id="pwd"  name="txtMatkhaum" required >
    </div>
	<div class="form-group">
      <label for="pwd1">Nhắc lại:</label>
      <input type="password" class="form-control" id="pwd1" name="txtreMatkhaum">
    </div>
	

	
	 <button type="submit" class="btn btn-default" name="sbsua">Đổi mật khẩu</button>
     <button type="reset" class="btn btn-default" name="sbHuy">Hủy</button>
  </form>
  <div class="media">
  <div class="media-left">
      <?php
      $username=$_SESSION['username'];
      $sqlanh ="select avatar from tbluser where username='$username'";
      $rsanh = $conn->query($sqlanh);
      if($rsanh->num_rows>0){
        $ra = $rsanh->fetch_assoc();
      }
      
      ?>
          <img src="uploads/<?php echo $ra['avatar'];?>" class="media-object" style="width:45px">
  </div>
  <div class="media-body">
    <form action="" name='favatar' method="post">
    
      <div class="form-group">
        <label for="da">Chọn ảnh mới</label>
        <input type="file"          class="form-control" name="txtfile" id="txtfile" aria-describedby="helpId" placeholder="">
       
      </div>
    </form>
</div>
</div>

<?php
require("phancuoi.php");
?>