<?php

    require("phandau.php");
?>
<?php

if(!isset($_SESSION['emailUser'])){
  echo "<script> alert(' Bạn chưa đăng nhập');";
  echo "window.location.assign('index.php');";
  echo "</script>";
}

if($_SESSION['role']!=1){
    echo "<script> alert('Bạn không phải Admin');";
  echo "window.location.assign('index.php');";
  echo "</script>";
}
?>
<?php
// thêm chủ đề
if(isset($_REQUEST['sbSubmit'])){
    $tenchude = $_REQUEST['txtTitle'];
    $sqlthemchude= "insert into tblchude(Tenchude) values('$tenchude')";
    if(!$conn->query($sqlthemchude)){
        echo "<script> alert(' Wrong!');";
        echo "</script>";
    }else{
        header("Location:quanlychude.php");
    }    

}
?>
<?php
// đổi trạng thái
if(isset($_REQUEST['Machude']) && isset($_REQUEST['change']) && $_REQUEST['change']==md5('yes')){
    $machude =$_REQUEST['Machude'];
    $trangthai=$_REQUEST['status']==0?1:0;
    $sqlsuatrangthai ="update tblchude set Trangthai=$trangthai where Machude='$machude'";
    echo $sqlsuatrangthai;
    $conn->query($sqlsuatrangthai);
    header("Location:quanlychude.php");
}
?>

<form action="" method=post name=f1 class="w3-container" enctype="multipart/form-data">
  <div class="form-group">
    <label for="title">Tên chủ đề:</label>
    <input type="text" class="form-control" id="txtTitle" name="txtTitle" required placeholder="Nhập tên chủ đề">
  </div>
 
  <button type="submit" class="btn btn-default" name="sbSubmit">Thêm</button>
  <button type="submit" class="btn btn-default" name="sbSua">Sửa</button>
</form>

<br />

<input class="form-control" id="myInput" type="text" placeholder="Tìm kiếm...">
  <br>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Mã chủ đề</th>
        <th>Nội dung</th>
        <th>Trạng thái</th>
      </tr>
    </thead>
    <tbody id="myTable">
    <?php
        $sqlcd01 = "select * from tblchude";
        $resultcd01 = $conn->query($sqlcd01);
        $yes=md5('yes');
        if($resultcd01->num_rows>0){
            while($rcd = $resultcd01->fetch_assoc()){
    ?>
      <tr>
        <td><?php echo $rcd['Machude'];?></td>
        <td><?php echo $rcd['Tenchude'];?></td>
        <td><?php echo $rcd['Trangthai'];?>  - <a href="?Machude=<?php echo $rcd['Machude'];?>&change=<?php echo $yes;?>&status=<?php echo $rcd['Trangthai'];?>">Change</a></td>
      </tr>
     <?php
     }
    }else{
        echo "<tr><td colspan=3>Không có dữ liệu</td></tr>";
    }
     ?>
    </tbody>
  </table>
 

  <script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
<?php
    require("phancuoi.php");
?>