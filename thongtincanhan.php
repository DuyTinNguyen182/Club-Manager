<?php
require("phandau.php");

if (!isset($_SESSION['username'])) {
  echo "<script>alert('Vui lòng đăng nhập!'); window.location='login.php';</script>";
  exit();
}

$username = $_SESSION['username'];
$msg = "";

if (isset($_POST['btn_save'])) {
  $fullname = trim($_POST['fullname']);
  $student_code = trim($_POST['student_code']);
  $class_code = trim($_POST['class_code']);
  $gender = (int) $_POST['gender'];

  $hasError = false;

  if (empty($fullname) || empty($student_code) || empty($class_code)) {
    $msg = "<div class='alert-error'>Vui lòng nhập đầy đủ họ tên, MSSV và mã lớp!</div>";
    $hasError = true;
  } elseif (!preg_match('/^[\p{L}\s]+$/u', $fullname)) {
    $msg = "<div class='alert-error'>Họ và tên chỉ được chứa chữ cái, không được chứa số hoặc ký tự đặc biệt!</div>";
    $hasError = true;
  } elseif (!ctype_digit($student_code)) {
    $msg = "<div class='alert-error'>Mã số sinh viên chỉ được chứa số!</div>";
    $hasError = true;
  }

  if (!$hasError) {
    $sql_check_mssv = "SELECT username FROM tbluser WHERE ma_sinh_vien = ? AND username != ?";
    $stmt_check = $conn->prepare($sql_check_mssv);
    $stmt_check->bind_param("ss", $student_code, $username);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
      $msg = "<div class='alert-error'>Mã số sinh viên này đã được sử dụng bởi tài khoản khác!</div>";
      $hasError = true;
    }
    $stmt_check->close();
  }

  $new_filename = null;
  if (!$hasError && isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] == 0) {
    $allowed = array('jpg', 'jpeg', 'png', 'gif');
    $filename = $_FILES['avatar_file']['name'];
    $fileSize = $_FILES['avatar_file']['size'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    $checkImage = getimagesize($_FILES['avatar_file']['tmp_name']);

    if ($checkImage === false) {
      $msg = "<div class='alert-error'>File tải lên không phải là ảnh hợp lệ!</div>";
      $hasError = true;
    } elseif (!in_array($ext, $allowed)) {
      $msg = "<div class='alert-error'>Chỉ chấp nhận file ảnh (jpg, png, gif)!</div>";
      $hasError = true;
    } elseif ($fileSize > 5000000) {
      $msg = "<div class='alert-error'>Kích thước ảnh quá lớn (Tối đa 5MB)!</div>";
      $hasError = true;
    } else {
      $new_filename = "avatar_" . $username . "_" . time() . "." . $ext;
      $upload_dir = "uploads/";
      if (!file_exists($upload_dir))
        mkdir($upload_dir, 0777, true);

      if (!move_uploaded_file($_FILES['avatar_file']['tmp_name'], $upload_dir . $new_filename)) {
        $msg = "<div class='alert-error'>Lỗi khi lưu ảnh vào server!</div>";
        $hasError = true;
        $new_filename = null;
      }
    }
  }

  if (!$hasError) {
    if ($new_filename) {
      $sql_update = "UPDATE tbluser SET ho_va_ten = ?, ma_sinh_vien = ?, ma_lop = ?, gioi_tinh = ?, anh_dai_dien = ? WHERE username = ?";
      $stmt_update = $conn->prepare($sql_update);
      $stmt_update->bind_param("sssiss", $fullname, $student_code, $class_code, $gender, $new_filename, $username);
    } else {
      $sql_update = "UPDATE tbluser SET ho_va_ten = ?, ma_sinh_vien = ?, ma_lop = ?, gioi_tinh = ? WHERE username = ?";
      $stmt_update = $conn->prepare($sql_update);
      $stmt_update->bind_param("sssis", $fullname, $student_code, $class_code, $gender, $username);
    }

    if ($stmt_update->execute()) {
      $msg = "<div class='alert-success'>Cập nhật thông tin thành công!</div>";
      $_SESSION['fullname'] = $fullname;
      if ($new_filename) {
        $_SESSION['avatar'] = $new_filename;
      }
    } else {
      $msg = "<div class='alert-error'>Lỗi DB: " . $conn->error . "</div>";
    }
    $stmt_update->close();
  }
}

$sql_user = "SELECT * FROM tbluser WHERE username = ?";
$stmt_get = $conn->prepare($sql_user);
$stmt_get->bind_param("s", $username);
$stmt_get->execute();
$result = $stmt_get->get_result();

if ($result->num_rows > 0) {
  $u = $result->fetch_assoc();
} else {
  echo "Không tìm thấy thông tin tài khoản.";
  exit();
}
$stmt_get->close();
?>

<style>
  .profile-container {
    max-width: 900px;
    margin: 40px auto;
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 30px;
  }

  .profile-sidebar {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    text-align: center;
    border: 1px solid #e2e8f0;
  }

  .avatar-wrapper {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto 20px;
  }

  .profile-avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #e0f2fe;
  }

  .btn-upload-label {
    margin-top: 10px;
    display: inline-block;
    padding: 8px 16px;
    background: #f1f5f9;
    color: #334155;
    border-radius: 20px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    transition: 0.2s;
  }

  .btn-upload-label:hover {
    background: #e2e8f0;
    color: #0d6efd;
  }

  .profile-content {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #334155;
  }

  .form-control {
    width: 100%;
    padding: 10px 15px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    font-size: 1rem;
    outline: none;
    transition: border 0.2s;
    font-family: inherit;
  }

  .form-control:focus {
    border-color: #0d6efd;
  }

  .form-control[readonly] {
    background-color: #f1f5f9;
    color: #64748b;
    cursor: not-allowed;
  }

  .radio-group {
    display: flex;
    gap: 20px;
  }

  .radio-option {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
  }

  .btn-save {
    background: #0d6efd;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
  }

  .btn-save:hover {
    background: #0b5ed7;
    transform: translateY(-2px);
  }

  .alert-success {
    padding: 15px;
    background: #dcfce7;
    color: #166534;
    border-radius: 8px;
    margin-bottom: 20px;
  }

  .alert-error {
    padding: 15px;
    background: #fee2e2;
    color: #991b1b;
    border-radius: 8px;
    margin-bottom: 20px;
  }

  .form-row {
    display: flex;
    gap: 20px;
  }

  .form-row .form-group {
    flex: 1;
  }

  /* Responsive Mobile */
  @media (max-width: 768px) {
    .profile-container {
      grid-template-columns: 1fr;
      /* 1 cột trên mobile */
      margin: 20px 10px;
      gap: 20px;
    }

    .form-row {
      flex-direction: column;
      /* Input xếp chồng */
      gap: 0;
    }

    .profile-sidebar,
    .profile-content {
      padding: 20px;
    }

    .btn-save {
      width: 100%;
    }
  }
</style>

<div class="content-section" style="background: #f8fafc; min-height: 100vh; padding-top: 20px;">
  <div class="container">
    <div style="margin-bottom: 20px; display:flex; align-items:center; gap:10px;">
      <i class="fa-solid fa-user-gear" style="font-size: 1.5rem; color:#0d6efd;"></i>
      <h2 style="margin:0; color: #1e293b;">Hồ sơ cá nhân</h2>
    </div>

    <form action="" method="POST" enctype="multipart/form-data" class="profile-container">
      <div class="profile-sidebar">
        <?php $avatarPath = !empty($u['anh_dai_dien']) ? "uploads/" . $u['anh_dai_dien'] : "uploads/avatar-default.png"; ?>
        <div class="avatar-wrapper">
          <img src="<?= htmlspecialchars($avatarPath) ?>" id="preview-img" class="profile-avatar" alt="Avatar">
        </div>
        <h3 style="margin: 10px 0 5px;"><?= htmlspecialchars($u['username']) ?></h3>
        <p style="color:#64748b; font-size:0.9rem;">Thành viên CLB</p>
        <label for="file-upload" class="btn-upload-label">
          <i class="fa-solid fa-camera"></i> Đổi ảnh đại diện
        </label>
        <input type="file" name="avatar_file" id="file-upload" style="display: none;" accept="image/*"
          onchange="previewImage(this)">
      </div>

      <div class="profile-content">
        <?= $msg ?>
        <div class="form-group">
          <label class="form-label">Tên đăng nhập</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($u['username']) ?>" readonly>
        </div>
        <div class="form-group">
          <label class="form-label">Email đăng ký</label>
          <input type="email" class="form-control" value="<?= htmlspecialchars($u['email']) ?>" readonly>
        </div>
        <div class="form-group">
          <label class="form-label">Họ và tên</label>
          <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($u['ho_va_ten']) ?>"
            required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Mã số sinh viên</label>
            <input type="text" name="student_code" class="form-control"
              value="<?= htmlspecialchars($u['ma_sinh_vien']) ?>" maxlength="15" placeholder="Nhập MSSV">
          </div>
          <div class="form-group">
            <label class="form-label">Mã lớp</label>
            <input type="text" name="class_code" class="form-control" value="<?= htmlspecialchars($u['ma_lop']) ?>"
              maxlength="20" placeholder="Nhập mã lớp">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Giới tính</label>
          <div class="radio-group">
            <label class="radio-option">
              <input type="radio" name="gender" value="0" <?= ($u['gioi_tinh'] == 0) ? 'checked' : '' ?>> Nam
            </label>
            <label class="radio-option">
              <input type="radio" name="gender" value="1" <?= ($u['gioi_tinh'] == 1) ? 'checked' : '' ?>> Nữ
            </label>
          </div>
        </div>
        <hr style="border:0; border-top:1px solid #e2e8f0; margin: 25px 0;">
        <button type="submit" name="btn_save" class="btn-save">
          <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function previewImage(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('preview-img').src = e.target.result;
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
<?php require("phancuoi.php"); ?>