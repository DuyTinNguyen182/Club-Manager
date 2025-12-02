<?php
$path_to_admin = '../';
include('../includes/header.php');

if (isset($_GET['user'])) {
    $username = $_GET['user'];
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
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    $sql_pass = "";
    if (!empty($_POST['password'])) {
        $pass_new = md5($_POST['password']);
        $sql_pass = ", password = '$pass_new'";
    }

    $sql_update = "UPDATE tbluser SET 
                   fullname = '$fullname', 
                   email = '$email', 
                   role = '$role',
                   status = '$status' 
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
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-edit'></i> Chỉnh sửa thành viên: <?= $username ?></h5>
                </div>
                <div class="card-body">

                    <?php if (isset($error_msg)) {
                        echo "<div class='alert alert-danger'>$error_msg</div>";
                    } ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" class="form-control bg-light" value="<?= $row['username'] ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên</label>
                            <input type="text" name="fullname" class="form-control" value="<?= $row['fullname'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $row['email'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-danger">Đổi mật khẩu (Để trống nếu không đổi)</label>
                            <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới...">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vai trò</label>
                                <select name="role" class="form-select">
                                    <option value="0" <?= ($row['role'] == 0) ? 'selected' : '' ?>>Thành viên</option>
                                    <option value="1" <?= ($row['role'] == 1) ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1" <?= ($row['status'] == 1) ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="0" <?= ($row['status'] == 0) ? 'selected' : '' ?>>Bị khóa</option>
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