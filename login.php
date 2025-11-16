<?php
    require("phandau.php");
?>
<?php
if(isset($_SESSION['emailUser'])){
    echo "<script> alert(' Login');";
    echo "window.location.assign('index.php');";
    echo "</script>";
}

 // xử lý đăng nhập tại đây
if(isset($_REQUEST['sbSubmit'])){
    $tendangnhap=$_REQUEST['txtUsername'];
    $matkhau=md5($_REQUEST['txtPassword']);
    $sql="select * from tbluser where username='$tendangnhap' and password='$matkhau'";
    $result=$conn->query($sql);
    if($result->num_rows>0){
        //lưu username // lưu email lại // trả về trang index
        //while($row = $result->fetch_assoc())
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $tendangnhap;
        $_SESSION['emailUser'] = $row['email'];
        $_SESSION['role'] = $row['role'];
        header("Location: index.php");

    }else{
        echo "<script> alert(' Username / Password fail');";
        echo "</script>";
    }

}

?>

<h3>Trang đăng nhập </h3>
<form action="" method=post name=f1 class="w3-container">
  <div class="form-group">
    <label for="username">Tên đăng nhập:</label>
    <input type="text" class="form-control" id="txtUsername" name="txtUsername" required>
  </div>

  <div class="form-group">
    <label for="pwd">Mật khẩu:</label>
    <input type="password" class="form-control" id="txtPassword" name="txtPassword" required>
  </div>
  
  <button type="submit" class="btn btn-default" name="sbSubmit">Đăng nhập</button>
</form>   
   
<?php
    require("phancuoi.php");
?>