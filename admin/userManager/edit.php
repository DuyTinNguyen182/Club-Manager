<?php
$path_to_admin = '../';
include('../includes/header.php');

if (isset($_GET['user'])) {
    $username = mysqli_real_escape_string($conn, $_GET['user']);
    $sql = "SELECT * FROM tbluser WHERE username = '$username'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "<script>alert('Thành viên không tồn tại!'); window.location.href='members.php';</script>";
        exit();
    }
} else {
    header("Location: members.php");
    exit();
}

if (isset($_POST['btnUpdate'])) {
    $fullname = trim($_POST['fullname']);
    $student_code = trim($_POST['student_code']);
    $class_code = trim($_POST['class_code']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $status = $_POST['status'];
    $password_raw = $_POST['password'];

    if (empty($fullname) || empty($email)) {
        $error_msg = "Vui lòng không để trống Họ tên và Email!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Địa chỉ Email không hợp lệ!";
    } elseif (!empty($student_code) && (strlen($student_code) !== 9 || !ctype_digit($student_code))) {
        $error_msg = "Mã số sinh viên phải bao gồm đúng 9 chữ số!";
    } elseif (!empty($password_raw) && strlen($password_raw) < 6) {
        $error_msg = "Mật khẩu mới phải có ít nhất 6 ký tự!";
    } else {
        $email_safe = mysqli_real_escape_string($conn, $email);
        $student_code_safe = mysqli_real_escape_string($conn, $student_code);

        $checkSQL = "SELECT username FROM tbluser WHERE (email = '$email_safe'";
        if (!empty($student_code_safe)) {
            $checkSQL .= " OR ma_sinh_vien = '$student_code_safe'";
        }
        $checkSQL .= ") AND username != '$username'";

        $checkResult = $conn->query($checkSQL);

        if ($checkResult->num_rows > 0) {
            $error_msg = "Email hoặc Mã số sinh viên đã được sử dụng bởi thành viên khác!";
        } else {
            $sql_pass = "";
            if (!empty($password_raw)) {
                $pass_new = md5($password_raw);
                $sql_pass = ", password = '$pass_new'";
            }

            $sql_update = "UPDATE tbluser SET 
                           ho_va_ten = '$fullname', 
                           ma_sinh_vien = '$student_code',
                           ma_lop = '$class_code',
                           email = '$email', 
                           quyen = '$role',
                           trang_thai = '$status' 
                           $sql_pass 
                           WHERE username = '$username'";

            if ($conn->query($sql_update) === TRUE) {
                echo "<script>
                        alert('Cập nhật thành công!');
                        window.location.href='members.php';
                      </script>";
            } else {
                $error_msg = "Lỗi: " . $conn->error;
            }
        }
    }
}
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-edit'></i> Chỉnh sửa thành viên:
                        <?= htmlspecialchars($username) ?></h5>
                </div>
                <div class="card-body">

                    <?php if (isset($error_msg)) {
                        echo "<div class='alert alert-danger'>$error_msg</div>";
                    } ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" class="form-control bg-light"
                                value="<?= htmlspecialchars($row['username']) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên</label>
                            <input type="text" name="fullname" class="form-control"
                                value="<?= htmlspecialchars($row['ho_va_ten']) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mã số sinh viên</label>
                                <input type="text" name="student_code" class="form-control"
                                    value="<?= htmlspecialchars($row['ma_sinh_vien']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mã lớp</label>
                                <input type="text" name="class_code" class="form-control"
                                    value="<?= htmlspecialchars($row['ma_lop']) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="<?= htmlspecialchars($row['email']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-danger">Đổi mật khẩu (Để trống nếu không đổi)</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Nhập mật khẩu mới...">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vai trò</label>
                                <select name="role" class="form-select">
                                    <option value="0" <?= ($row['quyen'] == 0) ? 'selected' : '' ?>>Thành viên</option>
                                    <option value="1" <?= ($row['quyen'] == 1) ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1" <?= ($row['trang_thai'] == 1) ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="0" <?= ($row['trang_thai'] == 0) ? 'selected' : '' ?>>Bị khóa</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="members.php" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" name="btnUpdate" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>