<?php
    require("phandau.php");
?>

<script language='javascript'>
function kiemtra(){
	var pass1 = document.f1.txtMatkhau.value;
	var pass2 = document.f1.txtreMatkhau.value;
	if(pass1 != pass2){
		alert("Mật khẩu không trung khớp");
		return false;		
	}
	
}
</script>

<?php
    if(isset($_SESSION['emailUser'])){
        echo "<script> alert(' Login');";
        echo "window.location.assign('index.php');";
        echo "</script>";
    }

    if(isset($_REQUEST['sbDangky'])){
        $tendangnhap=$_REQUEST['txtTendangnhap'];
		$matkhau=md5($_REQUEST['txtMatkhau']); //sd md5 để mã hóa dữ liệu
		$tendaydu=$_REQUEST['txtTendaydu'];
		$email=$_REQUEST['txtEmail'];
		$gioitinh=$_REQUEST['rdGt'];
		$ketquaupload = 0;
        $tm = "uploads/";
        $fileName = basename($_FILES["fileAnh"]["name"]); // lấy tên tập tin // size
		$targetFilePath = $tm . $fileName;  // nơi mà lưu trữ tập tin
		$fileType = strtolower(pathinfo($targetFilePath,PATHINFO_EXTENSION));
        // kiểm tra nếu user đó tồn tại rồi thì không cho đăng ký nữa
        $sqlcheck ="select * from tbluser where username='$tendangnhap' or email='$email'";
        $result = $conn->query($sqlcheck);
        if(	$result->num_rows > 0){
            echo "<script> alert(' Username/Email đã tồn tại');";
            echo "</script>";
        }else{

        if(!empty($_FILES["fileAnh"]["name"])){
			// liệt kê các loại tập tin cho phép upload 
			 $allowTypes = array('jpg','png','jpeg','gif');
			 // kiểm tra đúng loại cho phép không?
			if(in_array($fileType, $allowTypes)){ 
				if(move_uploaded_file($_FILES["fileAnh"]["tmp_name"], $targetFilePath)){ // di chuyển thành công hay ko??
					$sql="insert into tbluser(username, password, fullname, gender, email,avatar,role,status) values('".$tendangnhap."','".$matkhau."','".$tendaydu."',$gioitinh,'".$email."','".$fileName."',0,0)";
					if($conn->query($sql)){
						echo "<script> alert('Thành công');";
						echo "window.location.assign('login.php');";
						echo "</script>";
					}				
				}else{
					echo "<script> alert(' Upload tập tin avatar bị lỗi');";
                    echo "</script>";
				}
			
			}else{
				echo "<script> alert(' Tập tin upload không đúng định dạng');";
                    echo "</script>";
			    }
        }else{
                $sql="insert into tbluser(username, password, fullname, gender, email,role,status) values('".$tendangnhap."','".$matkhau."','".$tendaydu."',$gioitinh,'".$email."',0,0)";
					if($conn->query($sql)){
						echo "<script> alert('Thành công');";
						echo "window.location.assign('login.php');";
						echo "</script>";
					}
            }
        }
    }

?>

<h3>Trang đăng ký </h3>
<form action="" method="post" name="f1" onsubmit="return kiemtra();" enctype="multipart/form-data">
    <div class="form-group">
      <label for="usr">Tên đăng nhập:</label>
      <input type="text" class="form-control" id="usr" name="txtTendangnhap" placeholder="Nhập username..." required value="<?php echo @$_REQUEST['txtTendangnhap'];?>">
    </div>
    <div class="form-group">
      <label for="pwd">Mật khẩu:</label>
      <input type="password" class="form-control" id="pwd"  name="txtMatkhau" required >
    </div>
	<div class="form-group">
      <label for="pwd1">Nhắc lại:</label>
      <input type="password" class="form-control" id="pwd1" name="txtreMatkhau">
    </div>
	<div class="form-group">
      <label for="tendaydu">Tên đầy đủ:</label>
      <input type="text" class="form-control" id="tendaydu" name="txtTendaydu" value="<?php echo @$_REQUEST['txtTendaydu'];?>">
    </div>

	<div class="form-group">
	<label for="gioitinh">Giới tính:</label>
		<label class="radio-inline"><input type="radio" name="rdGt" value=0 checked>Nam</label>
		<label class="radio-inline"><input type="radio" name="rdGt" value=1 <?php if(@$_REQUEST['rdGt']==1){echo 'checked';} ?> >Nữ</label>
	</div>
    <div class="form-group">
		<label for="email">Email:</label>
		<input type="email" class="form-control" id="email" name="txtEmail" required value="<?php echo @$_REQUEST['txtEmail'];?>">
	</div>
	
	<div class="form-group">
		<label for="anhdaidien">Ảnh đại diện:</label>
		<input type="file" name="fileAnh">
	</div>
	 <button type="submit" class="btn btn-default" name="sbDangky">Đăng ký</button>
  </form>
   
<?php
    require("phancuoi.php");
?>