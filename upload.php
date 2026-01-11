<?php
require("phandau.php");
?>

<?php
// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['emailUser'])) {
  echo "<script> alert('Vui lòng đăng nhập!'); window.location.assign('index.php'); </script>";
  exit();
}

// 2. Xử lý Thêm mới (Upload)
if (isset($_POST["sbSubmit"])) {
  $ketquaupload = '';
  $tm = "images/";
  $rd = random_int(1, 1000);

  if (!empty($_FILES["fileToUpload"]["name"])) {
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $tachten = $tm . $rd . $fileName;
    $fileType = strtolower(pathinfo($tachten, PATHINFO_EXTENSION));

    $title = htmlentities($_REQUEST['txtTitle']);
    $descript = htmlentities($_REQUEST['txtDescript']);
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

    if (in_array($fileType, $allowTypes)) {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $tachten)) {
        $sql = "INSERT INTO tblslideshow (tieu_de, mo_ta, hinh_anh, trang_thai) VALUES ('$title', '$descript','$tachten', 0)";
        $insert = $conn->query($sql);

        if ($insert) {
          $ketquaupload = "<div class='alert alert-success'>Thêm thành công</div>";
        } else {
          $ketquaupload = "<div class='alert alert-danger'>Thất bại: " . $conn->error . "</div>";
        }
      } else {
        $ketquaupload = "<div class='alert alert-danger'>File không upload được vào thư mục đích.</div>";
      }
    } else {
      $ketquaupload = "<div class='alert alert-danger'>Chỉ cho phép upload JPG, JPEG, PNG, GIF.</div>";
    }
  } else {
    $ketquaupload = "<div class='alert alert-warning'>Chưa chọn ảnh.</div>";
  }
}
?>

<?php
// 3. Xử lý Thay đổi trạng thái
if (isset($_REQUEST['change']) && isset($_REQUEST['id']) && $_REQUEST['change'] == md5('yes')) {
  $id = $_REQUEST['id'];
  $status = $_REQUEST['status'];
  $c = ($status == 1) ? 0 : 1;
  $sql = "UPDATE tblslideshow SET trang_thai=$c WHERE ma_slide=$id";
  $conn->query($sql);
  echo "<script>window.location.href='upload.php';</script>";
}
?>

<?php
// 4. Xử lý Lấy dữ liệu để Sửa
if (isset($_REQUEST['edit']) && isset($_REQUEST['id']) && $_REQUEST['edit'] == md5('edit')) {
  $id = $_REQUEST['id'];
  $sql = "SELECT * FROM tblslideshow WHERE ma_slide='$id'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $r = $result->fetch_assoc();
    $ten = $r['tieu_de'];
    $dsc = $r['mo_ta'];
  }
}
?>

<?php
// 5. Xử lý Cập nhật dữ liệu
if (isset($_REQUEST['sbSua'])) {
  $id = $_REQUEST['id'];
  $title = htmlentities($_REQUEST['txtTitle']);
  $descript = htmlentities($_REQUEST['txtDescript']);
  $sql = "UPDATE tblslideshow SET tieu_de='$title', mo_ta='$descript' WHERE ma_slide=$id";
  $conn->query($sql);
  echo "<script>window.location.href='upload.php';</script>";
}
?>

<?php
// 6. Xử lý Xóa
if (isset($_REQUEST['del']) && isset($_REQUEST['id']) && $_REQUEST['del'] == md5('del')) {
  $id = $_REQUEST['id'];
  $sql = "DELETE FROM tblslideshow WHERE ma_slide='$id'";
  $conn->query($sql);
  echo "<script>window.location.href='upload.php';</script>";
}
?>

<style>
  .admin-container {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
  }

  .section-title {
    color: #1e293b;
    font-weight: 700;
    margin-bottom: 25px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-label {
    font-weight: 600;
    color: #334155;
    display: block;
    margin-bottom: 8px;
  }

  .form-control {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    outline: none;
    transition: 0.2s;
  }

  .form-control:focus {
    border-color: #2563eb;
  }

  .btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    text-decoration: none;
    display: inline-block;
  }

  .btn-primary {
    background: #2563eb;
    color: white;
  }

  .btn-primary:hover {
    background: #1d4ed8;
  }

  .btn-warning {
    background: #f59e0b;
    color: white;
  }

  .btn-warning:hover {
    background: #d97706;
  }

  .btn-default {
    background: #94a3b8;
    color: white;
  }

  .alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
  }

  .alert-success {
    background: #dcfce7;
    color: #166534;
  }

  .alert-danger {
    background: #fee2e2;
    color: #991b1b;
  }

  .alert-warning {
    background: #fef3c7;
    color: #92400e;
  }

  /* Table Styles */
  .table-wrapper {
    overflow-x: auto;
    /* Cuộn ngang trên mobile */
    margin-top: 30px;
  }

  .custom-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
    /* Đảm bảo bảng không bị co quá nhỏ */
  }

  .custom-table th,
  .custom-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
  }

  .custom-table th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
  }

  .status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
  }

  .status-active {
    background: #dcfce7;
    color: #166534;
  }

  .status-inactive {
    background: #fee2e2;
    color: #991b1b;
  }

  .action-link {
    margin-right: 10px;
    font-weight: 500;
    text-decoration: none;
  }

  .text-blue {
    color: #2563eb;
  }

  .text-red {
    color: #dc2626;
  }
</style>

<div class="content-section" style="background: #f8fafc; min-height: 100vh;">
  <div class="admin-container">
    <h2 class="section-title"><i class="fa-solid fa-cloud-arrow-up"></i> Quản lý Slideshow</h2>

    <form action="" method="post" name="f1" enctype="multipart/form-data">
      <div class="form-group">
        <label class="form-label" for="txtTitle">Tiêu đề:</label>
        <input type="text" class="form-control" id="txtTitle" name="txtTitle" value="<?php echo @$ten; ?>"
          placeholder="Nhập tiêu đề slide...">
      </div>

      <div class="form-group">
        <label class="form-label" for="txtDescript">Mô tả:</label>
        <input type="text" name="txtDescript" id="txtDescript" class="form-control" value="<?php echo @$dsc; ?>"
          placeholder="Nhập mô tả ngắn...">
      </div>

      <div class="form-group">
        <label class="form-label" for="fileToUpload">Hình ảnh:</label>
        <input type="file" class="form-control" id="fileToUpload" name="fileToUpload" <?php if (!isset($_REQUEST['edit']))
          echo 'required'; ?>>
        <?php if (isset($_REQUEST['edit'])): ?>
          <small style="color: #64748b;">(Bỏ qua nếu không muốn thay đổi ảnh)</small>
        <?php endif; ?>
      </div>

      <?php if (isset($_REQUEST['edit'])): ?>
        <button type="submit" class="btn btn-warning" name="sbSua"><i class="fa-solid fa-pen"></i> Cập nhật</button>
        <a href="upload.php" class="btn btn-default">Hủy</a>
      <?php else: ?>
        <button type="submit" class="btn btn-primary" name="sbSubmit"><i class="fa-solid fa-plus"></i> Thêm mới</button>
      <?php endif; ?>
    </form>

    <?php echo @$ketquaupload; ?>

    <?php
    $sql = "SELECT * FROM tblslideshow ORDER BY ma_slide DESC";
    $rs = $conn->query($sql);

    if ($rs->num_rows > 0) {
      ?>
      <div class="table-wrapper">
        <h3 style="color:#1e293b; margin-bottom:15px; font-size:1.2rem;">Danh sách Slide hiện có</h3>
        <table class="custom-table">
          <thead>
            <tr>
              <th width="50">ID</th>
              <th width="120">Hình ảnh</th>
              <th>Tiêu đề / Mô tả</th>
              <th width="100">Trạng thái</th>
              <th width="150">Hành động</th>
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
                <td>#<?php echo $r['ma_slide']; ?></td>
                <td>
                  <img src="<?php echo $r['hinh_anh']; ?>"
                    style="width: 100px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                </td>
                <td>
                  <strong><?php echo $r['tieu_de']; ?></strong><br>
                  <small style="color:#64748b;"><?php echo $r['mo_ta']; ?></small>
                </td>
                <td>
                  <?php if ($r['trang_thai'] == 1): ?>
                    <a href="?id=<?php echo $r['ma_slide']; ?>&change=<?php echo $yes; ?>&status=1"
                      class="status-badge status-active" style="text-decoration:none;">Hiện</a>
                  <?php else: ?>
                    <a href="?id=<?php echo $r['ma_slide']; ?>&change=<?php echo $yes; ?>&status=0"
                      class="status-badge status-inactive" style="text-decoration:none;">Ẩn</a>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="?edit=<?php echo $edit; ?>&id=<?php echo $r['ma_slide']; ?>" class="action-link text-blue"><i
                      class="fa-solid fa-pen-to-square"></i> Sửa</a>
                  <a href="?del=<?php echo $del; ?>&id=<?php echo $r['ma_slide']; ?>" class="action-link text-red"
                    onclick="return confirm('Bạn có chắc chắn muốn xóa slide này?');"><i class="fa-solid fa-trash"></i>
                    Xóa</a>
                </td>
              </tr>
              <?php
            }
            ?>
          </tbody>
        </table>
      </div>
      <?php
    } else {
      echo "<p style='margin-top:20px; color:#64748b; text-align:center;'>Chưa có dữ liệu slideshow.</p>";
    }
    ?>
  </div>
</div>

<?php
require("phancuoi.php");
?>