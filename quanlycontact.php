<?php
 require("phandau.php");

?>
<?php
if(!isset($_SESSION['emailUser'])|| $_SESSION['role']!=1){
    echo "<script> alert('Bạn không có quyền truy cập');";
    echo "window.location.assign('index.php');";
    echo "</script>";
}

if(isset($_REQUEST['id'])&&$_REQUEST['change']==md5('change')){
    $id = $_REQUEST['id'];
    $tt = $_REQUEST['tt']==0?1:0;
    $sqlcapnhap ="update tblcontact set Trangthai='$tt' where id='$id'";
    if($conn->query($sqlcapnhap)){
        header("Location: quanlycontact.php");
    }else{
        echo "Lỗi";
    }

}

?>
  <p>Danh sách người dùng gửi thông tin đến hệ thống</p>  
  <input class="form-control" id="myInput" type="text" placeholder="Search..">
  <br>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Tên người gửi</th>
        <th>Nội dung</th>
        <th>Email</th>
        <th>Ngày gửi</th>
        <th>Trạng thái</th>
      </tr>
    </thead>
    <tbody id="myTable">
    <?php 
    $sqlcontact = "SELECT * FROM tblcontact";
    $result = $conn->query($sqlcontact);
    $change=md5('change');
    if($result->num_rows > 0){
        while($r = $result->fetch_assoc()){
            
    ?>
      <tr>
        <td><?php echo $r['Tennguoigui'];?></td>
        <td><?php echo $r['Noidung'];?></td>
        <td><?php echo $r['Email'];?></td>
        <td><?php echo $r['Ngaygui'];?></td>
        <td><a href="?id=<?php echo $r['id'];?>&change=<?php echo $change;?>&tt=<?php echo $r['Trangthai'];?>"><?php echo $r['Trangthai']." - ";
            $ht = $r['Trangthai']==0?'Chưa đọc':'Đã đọc';
            echo $ht;
        ?></a></td>
      </tr>
      <?php
      }
    }
    ?>
    </tbody>
  </table>
  
  
</div>

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