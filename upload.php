<?php
require("phandau.php");
?>

<?php
// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['emailUser'])) {
  echo "<script> alert('Vui lòng đăng nhập!');";
  echo "window.location.assign('index.php');";
  echo "</script>";
}

// 2. Xử lý Thêm mới (Upload)
if (isset($_POST["sbSubmit"])) {
  $ketquaupload = '';
  $tm = "images/"; // Lưu ý: Nếu file này ở trong folder admin, có thể cần sửa thành "../images/"
  $rd = random_int(1, 1000);

  if (!empty($_FILES["fileToUpload"]["name"])) {
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    // Tạo đường dẫn file mới để tránh trùng tên: images/123tenfile.jpg
    $tachten = $tm . $rd . $fileName;
    $fileType = strtolower(pathinfo($tachten, PATHINFO_EXTENSION));

    $title = htmlentities($_REQUEST['txtTitle']);
    $descript = htmlentities($_REQUEST['txtDescript']);
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

    if (in_array($fileType, $allowTypes)) {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $tachten)) {
        // CẬP NHẬT: Insert vào các cột tên mới
        // Bỏ qua cột ma_slide để nó tự tăng (Auto Increment)
        $sql = "INSERT INTO tblslideshow (tieu_de, mo_ta, hinh_anh, trang_thai) VALUES ('$title', '$descript','$tachten', 0)";
        $insert = $conn->query($sql);

        if ($insert) {
          $ketquaupload = "Thêm thành công";
        } else {
          $ketquaupload = "Thất bại: " . $conn->error;
        }
      } else {
        $ketquaupload = "File không upload được vào thư mục đích.";
      }
    } else {
      $ketquaupload = 'Chỉ cho phép upload JPG, JPEG, PNG, GIF.';
    }
  } else {
    $ketquaupload = 'Chưa chọn ảnh.';
  }
}
?>

<?php
// 3. Xử lý Thay đổi trạng thái (Ẩn/Hiện)
if (isset($_REQUEST['change']) && isset($_REQUEST['id']) && $_REQUEST['change'] == md5('yes')) {
  $id = $_REQUEST['id'];
  $status = $_REQUEST['status'];
  $c = ($status == 1) ? 0 : 1;

  // CẬP NHẬT: trang_thai, ma_slide
  $sql = "UPDATE tblslideshow SET trang_thai=$c WHERE ma_slide=$id";
  $conn->query($sql);
  header("Location: upload.php");
}
?>

<?php
// 4. Xử lý Lấy dữ liệu để Sửa (Khi bấm nút Edit)
if (isset($_REQUEST['edit']) && isset($_REQUEST['id']) && $_REQUEST['edit'] == md5('edit')) {
  $id = $_REQUEST['id'];
  // CẬP NHẬT: ma_slide
  $sql = "SELECT * FROM tblslideshow WHERE ma_slide='$id'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $r = $result->fetch_assoc();
    // CẬP NHẬT: tieu_de, mo_ta
    $ten = $r['tieu_de'];
    $dsc = $r['mo_ta'];
  }
}
?>

<?php
// 5. Xử lý Cập nhật dữ liệu (Khi bấm nút Sửa trong form)
if (isset($_REQUEST['sbSua'])) {
  $id = $_REQUEST['id'];
  $title = htmlentities($_REQUEST['txtTitle']);
  $descript = htmlentities($_REQUEST['txtDescript']);

  // CẬP NHẬT: tieu_de, mo_ta, ma_slide
  // Lưu ý: Code gốc không xử lý cập nhật ảnh mới, nên ở đây giữ nguyên logic chỉ sửa text
  $sql = "UPDATE tblslideshow SET tieu_de='$title', mo_ta='$descript' WHERE ma_slide=$id";
  $conn->query($sql);
  header("Location: upload.php");
}
?>

<?php
// 6. Xử lý Xóa
if (isset($_REQUEST['del']) && isset($_REQUEST['id']) && $_REQUEST['del'] == md5('del')) {
  $id = $_REQUEST['id'];
  // CẬP NHẬT: ma_slide
  $sql = "DELETE FROM tblslideshow WHERE ma_slide='$id'";
  $conn->query($sql);
  header("Location: upload.php");
}
?>

<form action="" method="post" name="f1" class="w3-container" enctype="multipart/form-data">
  <div class="form-group">
    <label for="title">Title:</label>
    <input type="text" class="form-control" id="txtTitle" name="txtTitle" value="<?php echo @$ten; ?>">
  </div>
  <div class="form-group">
    <label for="Descript">Descript:</label>
    <input type="text" name="txtDescript" class="form-control" value="<?php echo @$dsc; ?>">
  </div>

  <div class="form-group">
    <label for="anh">Image:</label>
    <input type="file" class="form-control" id="fileToUpload" name="fileToUpload" <?php if (!isset($_REQUEST['edit'])) {
      echo 'required';
    } ?>>
  </div>

  <?php if (isset($_REQUEST['edit'])): ?>
    <button type="submit" class="btn btn-warning" name="sbSua">Cập nhật</button>
    <a href="upload.php" class="btn btn-default">Hủy</a>
  <?php else: ?>
    <button type="submit" class="btn btn-primary" name="sbSubmit">Thêm mới</button>
  <?php endif; ?>
</form>

<?php
echo "<h4>" . @$ketquaupload . "</h4>";
?>

<?php
// 7. HIỂN THỊ DANH SÁCH
// CẬP NHẬT: Sắp xếp theo ma_slide
$sql = "SELECT * FROM tblslideshow ORDER BY ma_slide DESC";
$rs = $conn->query($sql);

if ($rs->num_rows > 0) {
  ?>
  <font color="red">
    <h2>DANH SÁCH SLIDESHOWS</h2>
  </font>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Title</th>
        <th>Description</th>
        <th>Status</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $del = md5('del');
      $edit = md5('edit');
      $yes = md5('yes');
      while ($r = $rs->fetch_assoc()) {
        ?>
        <tr>
          <td><?php echo $r['ma_slide']; ?></td>

          <td><img src="<?php echo $r['hinh_anh']; ?>" width="100" height="100" style="object-fit: cover;"></td>

          <td><?php echo $r['tieu_de']; ?></td>

          <td><?php echo $r['mo_ta']; ?></td>

          <td>
            <?php echo ($r['trang_thai'] == 1) ? '<span class="text-success">Hiện</span>' : '<span class="text-danger">Ẩn</span>'; ?>
            -
            <a
              href="?id=<?php echo $r['ma_slide']; ?>&change=<?php echo $yes; ?>&status=<?php echo $r['trang_thai']; ?>">Change</a>
          </td>

          <td><a href="?edit=<?php echo $edit; ?>&id=<?php echo $r['ma_slide']; ?>">Edit</a></td>

          <td><a href="?del=<?php echo $del; ?>&id=<?php echo $r['ma_slide']; ?>"
              onclick="return confirm('Are you sure?');">Delete</a></td>
        </tr>
        <?php
      }
      ?>
    </tbody>
  </table>
  <?php
} else {
  echo "<p>Chưa có slide nào.</p>";
}
?>

<?php
require("phancuoi.php");
?>