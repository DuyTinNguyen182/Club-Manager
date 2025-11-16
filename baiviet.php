<?php
    require("phandau.php");
?>

<?php
// xử lý ẩn bình luận
// Lấy được mã bình luận sử dụng Request
// thay đổi Trangthai thành 1
if(isset($_REQUEST['mbl'])&&$_REQUEST['an']==md5('an')){
  $mbl = $_REQUEST['mbl'];
  $sqlan ="update tblbinhluan set Trangthai=1 where Mabinhluan='$mbl'";
  $conn->query($sqlan);
  $mcd =$_REQUEST['machude'];
  header("Location:baiviet.php?machude=$mcd");
}

?>


<?php
if(!isset($_SESSION['emailUser'])){
  echo "<script> alert(' Bạn chưa đăng nhập');";
  echo "window.location.assign('index.php');";
  echo "</script>";
}
if(isset($_REQUEST['machude'])){
    $sqllcd = "select * from tblchude where machude=".$_REQUEST["machude"];
    $resultlcd = $conn->query($sqllcd);
    if($resultlcd->num_rows>0){
        $rlcd = $resultlcd->fetch_assoc();
        $tenchude = $rlcd['Tenchude'];
        echo "<h4>Chủ đề thảo luận: $tenchude</h4>";
        echo "<hr height=15px/>";
    }else{
        header("Location:index.php");
    }
}

?>
<?php
// nếu ngta bấm gửi
if(isset($_REQUEST['sbSubmit'])){
    $machude = $_REQUEST['machude'];
    $ndbv = $_REQUEST['ndbv'];
    $username1 =$_SESSION['username'];
    $sqlthembv = "insert into tblbaiviet(Noidung,Machude,Ngaytao,Username) values('$ndbv','$machude',now(),'$username1')";
  //  echo $sqlthembv;
    $conn->query($sqlthembv);
   // header("Location:baiviet.php?machude=$machude");
}
?>

<?php
if(isset($_REQUEST['sbPhanhoi'])){
    $mabaiviet= $_REQUEST['txtmabaiviet'];
    $noidungph = $_REQUEST['ndphanhoi'];
    $username2 =$_SESSION['username'];
    $sqlthemph = "insert into tblbinhluan(Noidung,Mabaiviet,Ngaytao,Username) values('$noidungph','$mabaiviet',now(),'$username2')";
    $conn->query($sqlthemph);
}
?>

<?php
    if(isset($_SESSION['emailUser'])){
?>
 
<form action="" method=post name=f1 class="w3-container" enctype="multipart/form-data">
  
    <div class="form-group">
      <label for="idbv"></label>
      <textarea class="form-control" name="ndbv" id="ndbv" rows="3"></textarea>
      
    </div>
 
  <button type="submit" class="btn btn-default" name="sbSubmit">Gửi</button>
  
</form>
<?php
    }
?>
<br />




<?php
// show bài viết theo chủ đề
if(isset($_REQUEST['machude'])){
    $machude = $_REQUEST['machude'];
    $sqlbv ="select * from tblbaiviet,tbluser where machude =$machude and tblbaiviet.username = tbluser.username";
    $resultbv = $conn->query($sqlbv);
    $i=0;
    if($resultbv->num_rows>0){
        while($rbv=$resultbv->fetch_assoc()){
            $mabaiviet = $rbv['Mabaiviet'];
            $noidung = $rbv['Noidung'];
            $ngaytao = $rbv['Ngaytao'];
            $username= $rbv['username'];
            $avatar = $rbv['avatar'];
            $i++;

       
?>
<div class="media">
    <div class="media-left">
      <img src="uploads/<?php echo $avatar;?>" class="media-object" style="width:45px">
    </div>
    <div class="media-body">
      <h4 class="media-heading"><?php echo $username; ?> <small><i><?php echo $ngaytao; ?></i></small></h4>
      <p><?php echo "Mã bài viết: $mabaiviet <br />";?>
        <?php echo $noidung; ?></p>
      <div>
          <?php
          // xử lý số lượng phản hồi ở đây
          $sqlslph ="select count(Mabinhluan) as sl from tblbinhluan where Mabaiviet='$mabaiviet' and Trangthai=0";
          $resultslph = $conn->query($sqlslph);
          if($resultslph->num_rows>0)
            $rsslph = $resultslph->fetch_assoc();
          ?>
            <a href="#" data-toggle="modal" data-target="#myModal<?php echo $i;?>">Phản hồi (<?php echo $rsslph['sl']; ?>) </a>
            <!-- Modal -->
            <div id="myModal<?php echo $i;?>" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Phản hồi bài viết</h4>
                </div>
                <div class="modal-body">
                <form action="" method=post name=f231 class="w3-container">
                    <div class="form-group">
                    <label for="idphanhoi"></label>
                    <input type="hidden" class="form-control" name="txtmabaiviet" value="<?php echo $mabaiviet;?>">
                    </div>
                    <div class="form-group">
                        <label for="idbv"></label>
                        <textarea class="form-control" name="ndphanhoi" id="ndphanhoi" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-default" name="sbPhanhoi">Gửi</button>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>

                </div>
        </div>
        </div>  
      <!-- bình luận -->
      <?php
         
        $sqlbl ="select * from tblbinhluan, tbluser where tblbinhluan.username=tbluser.username and Mabaiviet='$mabaiviet' and Trangthai=0";
        $resultbl = $conn->query($sqlbl);
        if($resultbl->num_rows>0){
            while($rbl = $resultbl->fetch_assoc()){
                $mabinhluan = $rbl['Mabinhluan'];
                $noidung1 = $rbl['Noidung'];
                $ngaytao1 = $rbl['Ngaytao'];
                $username2= $rbl['username'];
                $avatar2 = $rbl['avatar']; 
        
      ?>
      <div class="media">
        <div class="media-left">
          <img src="uploads/<?php echo $avatar2;?>" class="media-object" style="width:45px">
        </div>
        <div class="media-body">
          <h4 class="media-heading"><?php echo $username2;?> <small><i><?php echo $ngaytao1;?></i></small></h4>
          <p><?php echo $noidung1;?></p> 
          <?php
          if(isset($_SESSION['emailUser']) && $_SESSION['role']==1){
            $an=md5('an');
          ?>
          <p><a href="?machude=<?php echo $machude;?>&mbl=<?php echo $mabinhluan;?>&an=<?php echo $an;?>">Ẩn bình luận này</a></p>
          <?php
          }
          ?>
        </div>
      </div>
      <?php
          }
        }
      ?>

    </div>
  </div>

<?php
 }
}else{
    echo "Chưa có bài viết nào cho chủ đề này! Mời bạn quay lại sau.";
} 
}else{
header("Location:index.php");
}
?>

 

  </div>
</div>




<?php
    require("phancuoi.php");
?>