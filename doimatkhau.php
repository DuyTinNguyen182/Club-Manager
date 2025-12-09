<?php
require("phandau.php");

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Vui lòng đăng nhập!'); window.location='login.php';</script>";
    exit();
}

$username = $_SESSION['username'];
$msg = ""; // Biến lưu thông báo

// 2. Xử lý khi bấm nút "Đổi mật khẩu"
if (isset($_POST['btn_change_pass'])) {
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    // Kiểm tra dữ liệu rỗng
    if (empty($old_pass) || empty($new_pass) || empty($confirm_pass)) {
        $msg = "<div class='alert-error'>Vui lòng nhập đầy đủ thông tin!</div>";
    }
    // Kiểm tra mật khẩu mới có khớp với nhập lại không
    elseif ($new_pass != $confirm_pass) {
        $msg = "<div class='alert-error'>Mật khẩu mới và nhập lại không khớp!</div>";
    }
    // Kiểm tra độ dài mật khẩu (Tùy chọn: ví dụ tối thiểu 6 ký tự)
    elseif (strlen($new_pass) < 6) {
        $msg = "<div class='alert-error'>Mật khẩu mới phải có ít nhất 6 ký tự!</div>";
    } else {
        // 3. Kiểm tra Mật khẩu cũ có đúng không
        // Lưu ý: Do DB của bạn đang dùng MD5, nên ta phải mã hóa MD5 cái user nhập vào để so sánh
        $old_pass_hash = md5($old_pass);

        $sql_check = "SELECT password FROM tbluser WHERE username = '$username' AND password = '$old_pass_hash'";
        $result = $conn->query($sql_check);

        if ($result->num_rows > 0) {
            // Mật khẩu cũ đúng -> Tiến hành cập nhật mật khẩu mới
            $new_pass_hash = md5($new_pass);

            $sql_update = "UPDATE tbluser SET password = '$new_pass_hash' WHERE username = '$username'";

            if ($conn->query($sql_update)) {
                $msg = "<div class='alert-success'>Đổi mật khẩu thành công!</div>";
            } else {
                $msg = "<div class='alert-error'>Lỗi hệ thống, vui lòng thử lại sau.</div>";
            }
        } else {
            // Mật khẩu cũ sai
            $msg = "<div class='alert-error'>Mật khẩu cũ không chính xác!</div>";
        }
    }
}
?>

<style>
    /* CSS Giao diện (Đồng bộ với trang Profile) */
    .password-container {
        max-width: 600px;
        /* Nhỏ gọn hơn trang profile */
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    .page-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f1f5f9;
    }

    .page-header h2 {
        margin: 0;
        color: #1e293b;
    }

    .page-header p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 0.9rem;
    }

    .form-group {
        margin-bottom: 20px;
        position: relative;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #334155;
    }

    .input-wrapper {
        position: relative;
    }

    .form-control {
        width: 100%;
        padding: 12px 45px 12px 15px;
        /* Chừa chỗ cho icon con mắt */
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 1rem;
        outline: none;
        transition: border 0.2s;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }

    /* Nút con mắt hiện/ẩn pass */
    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #94a3b8;
        font-size: 1.1rem;
    }

    .toggle-password:hover {
        color: #0d6efd;
    }

    .btn-submit {
        width: 100%;
        background: #0d6efd;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 10px;
    }

    .btn-submit:hover {
        background: #0b5ed7;
    }

    /* Thông báo */
    .alert-success {
        padding: 15px;
        background: #dcfce7;
        color: #166534;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
        border: 1px solid #bbf7d0;
    }

    .alert-error {
        padding: 15px;
        background: #fee2e2;
        color: #991b1b;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
        border: 1px solid #fecaca;
    }
</style>

<div class="content-section" style="background: #f8fafc; min-height: 100vh; padding-top: 20px;">
    <div class="container">

        <div class="password-container">
            <div class="page-header">
                <h2>Đổi mật khẩu</h2>
                <p>Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho người khác.</p>
            </div>

            <?= $msg ?>

            <form action="" method="POST">

                <div class="form-group">
                    <label class="form-label">Mật khẩu hiện tại</label>
                    <div class="input-wrapper">
                        <input type="password" name="old_pass" class="form-control" id="oldPass" required
                            placeholder="Nhập mật khẩu cũ...">
                        <i class="fa-regular fa-eye toggle-password" onclick="togglePass('oldPass', this)"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mật khẩu mới</label>
                    <div class="input-wrapper">
                        <input type="password" name="new_pass" class="form-control" id="newPass" required
                            placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)">
                        <i class="fa-regular fa-eye toggle-password" onclick="togglePass('newPass', this)"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Xác nhận mật khẩu mới</label>
                    <div class="input-wrapper">
                        <input type="password" name="confirm_pass" class="form-control" id="confirmPass" required
                            placeholder="Nhập lại mật khẩu mới">
                        <i class="fa-regular fa-eye toggle-password" onclick="togglePass('confirmPass', this)"></i>
                    </div>
                </div>

                <button type="submit" name="btn_change_pass" class="btn-submit">
                    <i class="fa-solid fa-check"></i> Xác nhận đổi mật khẩu
                </button>

                <div style="text-align:center; margin-top:15px;">
                    <a href="thongtincanhan.php" style="color:#64748b; text-decoration:none; font-size:0.9rem;">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại hồ sơ
                    </a>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
    // Hàm Javascript để hiện/ẩn mật khẩu khi bấm vào con mắt
    function togglePass(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

<?php require("phancuoi.php"); ?>