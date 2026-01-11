<?php
$path_to_admin = '../';
include('../includes/header.php');
require_once('../config.php');

if (isset($_POST['btnAdd'])) {
    // ... (Giữ nguyên phần logic xử lý PHP của bạn ở đây để code gọn) ...
    // LOGIC PHP CỦA BẠN KHÔNG CẦN THAY ĐỔI, TÔI GIỮ NGUYÊN HTML BÊN DƯỚI

    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $student_code = trim($_POST['student_code']);
    $class_code = trim($_POST['class_code']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    if (empty($username) || empty($fullname) || empty($email) || empty($password)) {
        $error_msg = "Vui lòng điền đầy đủ các trường bắt buộc!";
    } elseif (strlen($username) < 4 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error_msg = "Username phải từ 4 ký tự trở lên và chỉ chứa chữ, số, dấu gạch dưới!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Địa chỉ Email không hợp lệ!";
    } elseif (strlen($password) < 6) {
        $error_msg = "Mật khẩu phải có ít nhất 6 ký tự!";
    } elseif (!empty($student_code) && (strlen($student_code) !== 9 || !ctype_digit($student_code))) {
        $error_msg = "Mã số sinh viên phải bao gồm đúng 9 chữ số!";
    } else {
        $username = mysqli_real_escape_string($conn, $username);
        $email = mysqli_real_escape_string($conn, $email);
        $student_code = mysqli_real_escape_string($conn, $student_code);

        $checkSQL = "SELECT username FROM tbluser WHERE username = '$username' OR email = '$email'";
        if (!empty($student_code)) {
            $checkSQL .= " OR ma_sinh_vien = '$student_code'";
        }

        $checkResult = $conn->query($checkSQL);

        if ($checkResult->num_rows > 0) {
            $error_msg = "Tên đăng nhập, Email hoặc Mã sinh viên này đã tồn tại!";
        } else {
            $pass_hash = md5($password);
            $avatar_default = '0'; // Hoặc tên file ảnh mặc định

            $sql_insert = "INSERT INTO tbluser (username, password, ho_va_ten, ma_sinh_vien, ma_lop, email, gioi_tinh, quyen, trang_thai, anh_dai_dien) 
                           VALUES ('$username', '$pass_hash', '$fullname', '$student_code', '$class_code', '$email', 0, '$role', '$status', '$avatar_default')";

            if ($conn->query($sql_insert) === TRUE) {
                echo "<script>alert('Thêm thành viên mới thành công!'); window.location.href='members.php';</script>";
            } else {
                $error_msg = "Lỗi hệ thống: " . $conn->error;
            }
        }
    }
}
?>

<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-user-plus'></i> Thêm thành viên mới</h5>
                </div>
                <div class="card-body">

                    <?php if (isset($error_msg)) {
                        echo "<div class='alert alert-danger alert-dismissible fade show'>
                                <i class='bx bx-error-circle'></i> $error_msg
                                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                              </div>";
                    } ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" placeholder="Viết liền không dấu"
                                value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="fullname" class="form-control"
                                value="<?= isset($fullname) ? htmlspecialchars($fullname) : '' ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-bold">Mã số sinh viên</label>
                                <input type="text" name="student_code" class="form-control" placeholder="Nhập MSSV"
                                    value="<?= isset($student_code) ? htmlspecialchars($student_code) : '' ?>">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-bold">Mã lớp</label>
                                <input type="text" name="class_code" class="form-control" placeholder="Nhập mã lớp"
                                    value="<?= isset($class_code) ? htmlspecialchars($class_code) : '' ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control"
                                value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-bold">Vai trò</label>
                                <select name="role" class="form-select">
                                    <option value="0" <?= (isset($role) && $role == 0) ? 'selected' : '' ?>>Thành viên
                                    </option>
                                    <option value="1" <?= (isset($role) && $role == 1) ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1" <?= (isset($status) && $status == 1) ? 'selected' : '' ?>>Hoạt động
                                    </option>
                                    <option value="0" <?= (isset($status) && $status == 0) ? 'selected' : '' ?>>Bị khóa
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="members.php" class="btn btn-secondary"><i class='bx bx-arrow-back'></i> Quay
                                lại</a>
                            <button type="submit" name="btnAdd" class="btn btn-primary"><i class='bx bx-save'></i> Lưu
                                lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>