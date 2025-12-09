<?php
require("phandau.php");

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Vui lòng đăng nhập!'); window.location='login.php';</script>";
    exit();
}

$username = $_SESSION['username'];
$msg = ""; // Biến lưu thông báo lỗi/thành công

// --- XỬ LÝ KHI BẤM NÚT LƯU ---
if (isset($_POST['btn_save'])) {
    $fullname = $_POST['fullname'];
    $gender = $_POST['gender']; // 0: Nam, 1: Nữ

    // Xử lý Upload Avatar
    $avatar_sql = "";

    // Kiểm tra xem có file ảnh được upload không
    if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        $filename = $_FILES['avatar_file']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            // Đổi tên file để tránh trùng: avatar_username_timestamp.jpg
            $new_filename = "avatar_" . $username . "_" . time() . "." . $ext;
            $upload_dir = "uploads/";

            // Kiểm tra và tạo thư mục nếu chưa có
            if (!file_exists($upload_dir))
                mkdir($upload_dir, 0777, true);

            if (move_uploaded_file($_FILES['avatar_file']['tmp_name'], $upload_dir . $new_filename)) {
                $avatar_sql = ", avatar = '$new_filename'";

                // Cập nhật ngay vào Session để Avatar trên menu đổi theo
                $_SESSION['avatar'] = $new_filename;
            } else {
                $msg = "<div class='alert-error'>Lỗi khi tải ảnh lên server!</div>";
            }
        } else {
            $msg = "<div class='alert-error'>Chỉ chấp nhận file ảnh (jpg, png, gif)!</div>";
        }
    }

    // Cập nhật Database (Chỉ update Fullname, Gender và Avatar nếu có)
    // Username và Email không được đưa vào câu lệnh UPDATE để đảm bảo an toàn
    $fullname = str_replace("'", "\'", $fullname); // Chống lỗi SQL đơn giản
    $sql_update = "UPDATE tbluser SET fullname = '$fullname', gender = '$gender' $avatar_sql WHERE username = '$username'";

    if ($conn->query($sql_update)) {
        $msg = "<div class='alert-success'>Cập nhật thông tin thành công!</div>";
        // Cập nhật lại Session fullname
        $_SESSION['fullname'] = $fullname;
    } else {
        $msg = "<div class='alert-error'>Lỗi: " . $conn->error . "</div>";
    }
}

// --- LẤY THÔNG TIN USER TỪ DB ĐỂ HIỂN THỊ ---
$sql_user = "SELECT * FROM tbluser WHERE username = '$username'";
$result = $conn->query($sql_user);
if ($result->num_rows > 0) {
    $u = $result->fetch_assoc();
} else {
    echo "Không tìm thấy thông tin tài khoản.";
    exit();
}
?>

<style>
    /* Tận dụng lại biến màu từ index.php */
    .profile-container {
        max-width: 900px;
        margin: 40px auto;
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 30px;
    }

    /* CỘT TRÁI: AVATAR */
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

    /* CỘT PHẢI: FORM */
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

    /* Input bị khóa */
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

    /* Thông báo */
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

    @media (max-width: 768px) {
        .profile-container {
            grid-template-columns: 1fr;
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
                <?php
                $avatarPath = !empty($u['avatar']) ? "uploads/" . $u['avatar'] : "images/default.png";
                ?>
                <div class="avatar-wrapper">
                    <img src="<?= $avatarPath ?>" id="preview-img" class="profile-avatar" alt="Avatar">
                </div>

                <h3 style="margin: 10px 0 5px;"><?= $u['username'] ?></h3>
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
                    <input type="text" class="form-control" value="<?= $u['username'] ?>" readonly>
                    <!-- <small style="color:#94a3b8; font-size: 0.85rem;"><i class="fa-solid fa-lock"></i> Không thể thay
                        đổi</small> -->
                </div>

                <div class="form-group">
                    <label class="form-label">Email đăng ký</label>
                    <input type="email" class="form-control" value="<?= $u['email'] ?>" readonly>
                    <!-- <small style="color:#94a3b8; font-size: 0.85rem;"><i class="fa-solid fa-lock"></i> Liên hệ Admin nếu
                        muốn đổi Email</small> -->
                </div>

                <div class="form-group">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="fullname" class="form-control" value="<?= $u['fullname'] ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Giới tính</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="gender" value="0" <?= ($u['gender'] == 0) ? 'checked' : '' ?>> Nam
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="gender" value="1" <?= ($u['gender'] == 1) ? 'checked' : '' ?>> Nữ
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
    // Hàm xem trước ảnh khi chọn file
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