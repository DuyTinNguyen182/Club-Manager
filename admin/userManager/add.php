<?php
// Định nghĩa đường dẫn (giống file edit của bạn)
$path_to_admin = '../';
// Lưu ý: Kiểm tra lại đường dẫn include này xem file header nằm ở 'admin/includes' hay 'includes' gốc
// Nếu header nằm trong admin/includes thì dùng: include('includes/header.php');
include('../includes/header.php'); 
require_once('../config.php');

// --- XỬ LÝ KHI BẤM NÚT LƯU (THÊM MỚI) ---
if (isset($_POST['btnAdd'])) {
    // 1. Lấy dữ liệu từ form
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $role     = $_POST['role'];
    $status   = $_POST['status'];

    // 2. Kiểm tra trùng lặp (Username hoặc Email đã tồn tại chưa?)
    // Bước này quan trọng để tránh lỗi cơ sở dữ liệu
    $checkSQL = "SELECT username FROM tbluser WHERE username = '$username' OR email = '$email'";
    $checkResult = $conn->query($checkSQL);

    if ($checkResult->num_rows > 0) {
        $error_msg = "Tên đăng nhập hoặc Email này đã tồn tại trên hệ thống!";
    } else {
        // 3. Mã hóa mật khẩu MD5 (Giống file edit)
        $pass_hash = md5($password);

        // 4. Avatar mặc định là '0'
        $avatar_default = '0'; 

        // 5. Câu lệnh Insert
        $sql_insert = "INSERT INTO tbluser (username, password, fullname, email, role, status, avatar) 
                       VALUES ('$username', '$pass_hash', '$fullname', '$email', '$role', '$status', '$avatar_default')";

        if ($conn->query($sql_insert) === TRUE) {
            // Thông báo và chuyển hướng giống file edit
            echo "<script>
                    alert('Thêm thành viên mới thành công!');
                    window.location.href='members.php';
                  </script>";
        } else {
            $error_msg = "Lỗi hệ thống: " . $conn->error;
        }
    }
}
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-user-plus'></i> Thêm thành viên mới</h5>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($error_msg)) {
                        echo "<div class='alert alert-danger'>$error_msg</div>";
                    } ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" placeholder="Viết liền không dấu (VD: admin123)" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="fullname" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vai trò</label>
                                <select name="role" class="form-select">
                                    <option value="0">Thành viên</option>
                                    <option value="1">Admin</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Bị khóa</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="members.php" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" name="btnAdd" class="btn btn-primary">Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Kiểm tra đường dẫn footer tương tự header
include('../includes/footer.php'); 
?>