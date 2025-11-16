<?php
    require("phandau.php");
?>
<?php
if(!isset($_SESSION['emailUser'])){
  echo "<script> alert('Login');";
  echo "window.location.assign('index.php');";
  echo "</script>";
}

if(isset($_POST["sbSubmit"])){
    $ketquaupload = '';
    $tm = "images/";
    $rd = random_int(1,1000);
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $targetFilePath = $tm . $fileName;
    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
    if(!empty($_FILES["fileToUpload"]["name"])){
        $title= htmlentities($_REQUEST['txtTitle']);
        $descript=htmlentities($_REQUEST['txtDescript']);
        $allowTypes = array('jpg','png','jpeg','gif');
        $tachten=$tm.$rd.$fileName;
        if(in_array($fileType, $allowTypes)){
            //  if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFilePath)){
            if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $tachten)){
                $sql = "INSERT into tblslideshow (Id,Title,Description,ImageUrl,Status) VALUES ('','$title', '$descript','$tachten',0)";
                $insert = $conn->query($sql);
              //  echo $sql;
            if($insert){
                $ketquaupload = "Thêm thành công";
            }else{
                $ketquaupload = "Thất bại";
            } 
            }else{
                $ketquaupload = "File upload không upload được";
            }
        }else{
            $ketquaupload = 'Chỉ cho phép upload JPG, JPEG, PNG, GIF.';
        }
    }else{
        $ketquaupload = 'Chưa chọn ảnh.';
    }
}
?>

<?php
if(isset($_REQUEST['change']) && isset($_REQUEST['id']) && $_REQUEST['change']==md5('yes')){
	$id=$_REQUEST['id'];
	$status=$_REQUEST['status'];
	$c=$status==1?0:1;
	$sql="update tblslideshow set Status=$c where Id=$id";
	$conn->query($sql);
	header("Location: upload.php");
	
}
?>

<?php
if(isset($_REQUEST['edit']) && isset($_REQUEST['id']) && $_REQUEST['edit']==md5('edit')){
  // nếu có click vào chữ sửa
  $id=$_REQUEST['id']; // thì lấy id
  // đem so sánh trong csdl, lấy đúng trường id theo dòng
  $sql="select * from tblslideshow where Id='$id'";
  $result = $conn->query($sql);
  if($result->num_rows>0){ // Nếu có dữ liệu
    $r=$result->fetch_assoc();
    $ten=$r['Title']; // lấy title
    $dsc = $r['Description']; // lấy descript
  }
}
?>
<?php
if(isset($_REQUEST['sbSua'])){
  $id=$_REQUEST['id'];
  $title= htmlentities($_REQUEST['txtTitle']);
  $descript=htmlentities($_REQUEST['txtDescript']);
  $sql="update tblslideshow set Title='$title', Description='$descript' where Id=$id";
	$conn->query($sql);
	header("Location: upload.php");
}
?>
<?php
if(isset($_REQUEST['del'])&&isset($_REQUEST['id'])&&$_REQUEST['del']=md5('del')){
  $id=$_REQUEST['id'];
  $sql="Delete from tblslideshow where Id='$id'";
  $conn->query($sql);
  header("Location: upload.php");
}
?>


<form action="" method=post name=f1 class="w3-container" enctype="multipart/form-data">
  <div class="form-group">
    <label for="title">Title:</label>
    <input type="text" class="form-control" id="txtTitle" name="txtTitle" value="<?php echo @$ten;?>">
  </div>
  <div class="form-group">
	<label for="Descript">Descript:</label>
    <input type="text" name="txtDescript" class="form-control" value="<?php echo @$dsc;?>"> 
  </div>
  
  <div class="form-group">
    <label for="anh">Image:</label>
    <input type="file" class="form-control" id="fileToUpload" name="fileToUpload" <?php if(!isset($_REQUEST['edit'])){echo 'required';}?>>
  </div>
  <button type="submit" class="btn btn-default" name="sbSubmit">Thêm</button>
  <button type="submit" class="btn btn-default" name="sbSua">Sửa</button>
</form>
<?php
    echo "<h4>".@$ketquaupload."</h4>";
?>

<?php
// hiển thị danh sách SLIDESHOWS
$sql="select * from tblslideshow order by Id";
$rs=$conn->query($sql);
if($rs->num_rows>0){
?>
<font color=red><h2>DANH SÁCH SLIDESHOWS</H2></font>
 <table class="table table-bordered">
    <thead>
      <tr>
        <th>Id</th>
        <th>Image</th>
		<th>Title</th>
		<th>Descript</th>
		<th>Status</th>
        <th>Edit</th>
		<th>Delete</th>
      </tr>
    </thead>
    <tbody>
	<?php
		$del=md5('del');
		$edit=md5('edit');
		$yes=md5('yes');
		while($r=$rs->fetch_assoc()){ // r == row // rs == result ==> dat sau cung duoc.
	?>
      <tr>
        <td><?php echo $r['Id'];?></td>
        <td><img src="<?php echo $r['ImageUrl'];?>" width=100 height=100></td>
        <td><?php echo $r['Title'];?></td>
		<td><?php echo $r['Description'];?></td>
		<td><?php echo $r['Status'];?> - <a href="?id=<?php echo $r['Id'];?>&change=<?php echo $yes;?>&status=<?php echo $r['Status'];?>">Change</a> </td>
		<td><a href="?edit=<?php echo $edit;?>&id=<?php echo $r['Id'];?>&name=<?php echo $r['Title'];?>&url=<?php echo $r['Description'];?>&anh=<?php echo $r['ImageUrl'];?>">Edit</a></td>
		<td><a href="?del=<?php echo $del;?>&id=<?php echo $r['Id'];?>" onclick="return confirm('Are you sure?');">Delete</a></td>
      </tr>
    <?php
		}
	?>
    </tbody>
  </table>
<?php
}
?>



<?php
    require("phancuoi.php");
?>