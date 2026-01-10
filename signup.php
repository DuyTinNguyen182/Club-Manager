<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['emailUser'])) {
    header('Location: index.php');
    exit();
}

$error_msg = '';
$success_msg = '';

if (isset($_POST['sbDangky'])) {
    $tendangnhap = trim($_POST['txtTendangnhap']);
    $matkhau_raw = $_POST['txtMatkhau'];
    $re_matkhau = $_POST['txtreMatkhau'];
    $tendaydu = trim($_POST['txtTendaydu']);
    $malop = trim($_POST['txtMalop']);
    $mssv = trim($_POST['txtMSSV']);
    $email = trim($_POST['txtEmail']);
    $gioitinh = isset($_POST['rdGt']) ? (int) $_POST['rdGt'] : 0;

    if (empty($tendangnhap) || empty($matkhau_raw) || empty($re_matkhau) || empty($tendaydu) || empty($malop) || empty($mssv) || empty($email)) {
        $error_msg = "Vui lòng điền đầy đủ tất cả các trường bắt buộc.";
    } elseif (strlen($tendangnhap) < 4 || !preg_match('/^[a-zA-Z0-9_]+$/', $tendangnhap)) {
        $error_msg = "Tên đăng nhập tối thiểu 4 ký tự, không chứa ký tự đặc biệt hoặc khoảng trắng.";
    } elseif (!preg_match('/^[\p{L}\s]+$/u', $tendaydu)) {
        $error_msg = "Họ và tên chỉ được chứa chữ cái, không được chứa số hoặc ký tự đặc biệt.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Địa chỉ Email không hợp lệ.";
    } elseif (!ctype_digit($mssv)) {
        $error_msg = "Mã số sinh viên chỉ được chứa các ký tự số.";
    } elseif (strlen($matkhau_raw) < 6) {
        $error_msg = "Mật khẩu phải có ít nhất 6 ký tự.";
    } elseif ($matkhau_raw !== $re_matkhau) {
        $error_msg = "Mật khẩu nhập lại không khớp.";
    } else {
        $sqlcheck = "SELECT username, email, ma_sinh_vien FROM tbluser WHERE username = ? OR email = ? OR ma_sinh_vien = ?";
        $stmt_check = $conn->prepare($sqlcheck);
        $stmt_check->bind_param("sss", $tendangnhap, $email, $mssv);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            while ($row = $result_check->fetch_assoc()) {
                if ($row['username'] === $tendangnhap) {
                    $error_msg = "Tên đăng nhập này đã có người sử dụng.";
                    break;
                } elseif ($row['email'] === $email) {
                    $error_msg = "Email này đã được đăng ký.";
                    break;
                } elseif ($row['ma_sinh_vien'] === $mssv) {
                    $error_msg = "Mã số sinh viên này đã tồn tại trong hệ thống.";
                    break;
                }
            }
        } else {
            $avatar_path = 'uploads/avatar-default.png';
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
            $upload_ok = true;

            if (!empty($_FILES["fileAnh"]["name"])) {
                $fileName = basename($_FILES["fileAnh"]["name"]);
                $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $fileSize = $_FILES["fileAnh"]["size"];

                if (!in_array($fileType, $allowTypes)) {
                    $error_msg = "Chỉ chấp nhận file ảnh (JPG, JPEG, PNG, GIF).";
                    $upload_ok = false;
                } elseif ($fileSize > 5000000) {
                    $error_msg = "File ảnh quá lớn (Tối đa 5MB).";
                    $upload_ok = false;
                } else {
                    $checkImage = getimagesize($_FILES["fileAnh"]["tmp_name"]);
                    if ($checkImage === false) {
                        $error_msg = "File tải lên không phải là ảnh hợp lệ.";
                        $upload_ok = false;
                    } else {
                        $tm = "uploads/";
                        if (!file_exists($tm))
                            mkdir($tm, 0777, true);

                        $newFileName = "IMG_" . $tendangnhap . "_" . time() . "." . $fileType;
                        $targetFilePath = $tm . $newFileName;

                        if (move_uploaded_file($_FILES["fileAnh"]["tmp_name"], $targetFilePath)) {
                            $avatar_path = $newFileName;
                        } else {
                            $error_msg = "Lỗi khi upload ảnh lên server.";
                            $upload_ok = false;
                        }
                    }
                }
            }

            if ($upload_ok && empty($error_msg)) {
                $matkhau_hash = md5($matkhau_raw);
                $role = 0;
                $status = 1;

                $sql_insert = "INSERT INTO tbluser (username, password, ho_va_ten, ma_lop, ma_sinh_vien, gioi_tinh, email, anh_dai_dien, quyen, trang_thai) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("sssssisisi", $tendangnhap, $matkhau_hash, $tendaydu, $malop, $mssv, $gioitinh, $email, $avatar_path, $role, $status);

                if ($stmt_insert->execute()) {
                    $_SESSION['username'] = $tendangnhap;
                    $_SESSION['emailUser'] = $email;
                    $_SESSION['role'] = $role;
                    $_SESSION['fullname'] = $tendaydu;
                    $_SESSION['avatar'] = basename($avatar_path);

                    $success_msg = "Đăng ký thành công! Đang chuyển hướng...";
                    header("refresh:2;url=index.php");
                } else {
                    $error_msg = "Lỗi hệ thống: " . $stmt_insert->error;
                }
                $stmt_insert->close();
            }
        }
        $stmt_check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký thành viên</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-body: #f1f5f9;
            --bg-card: #ffffff;
            --text-main: #1e293b;
            --text-light: #64748b;
            --border: #e2e8f0;
            --radius: 8px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .register-container {
            background: var(--bg-card);
            width: 100%;
            max-width: 650px;
            padding: 30px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .header p {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-main);
        }

        label span {
            color: #ef4444;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.95rem;
            transition: 0.2s;
            outline: none;
        }

        input[type="file"] {
            background: #f8fafc;
            padding: 7px;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .radio-group {
            display: flex;
            gap: 20px;
            padding-top: 5px;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .radio-item input {
            accent-color: var(--primary);
            width: 16px;
            height: 16px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
        }

        .footer-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .footer-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="header">
            <h2>Đăng Ký Thành Viên</h2>
            <p>Nhập thông tin đầy đủ để tham gia CLB</p>
        </div>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-error"><?= $error_msg ?></div>
        <?php endif; ?>

        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success"><?= $success_msg ?></div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group">
                    <label>Tên đăng nhập <span>*</span></label>
                    <input type="text" name="txtTendangnhap" required
                        value="<?= isset($_POST['txtTendangnhap']) ? htmlspecialchars($_POST['txtTendangnhap']) : '' ?>">
                </div>

                <div class="form-group">
                    <label>Mã số sinh viên <span>*</span></label>
                    <input type="text" name="txtMSSV" required
                        value="<?= isset($_POST['txtMSSV']) ? htmlspecialchars($_POST['txtMSSV']) : '' ?>">
                </div>

                <div class="form-group">
                    <label>Họ và tên <span>*</span></label>
                    <input type="text" name="txtTendaydu" required
                        value="<?= isset($_POST['txtTendaydu']) ? htmlspecialchars($_POST['txtTendaydu']) : '' ?>">
                </div>

                <div class="form-group">
                    <label>Mã lớp <span>*</span></label>
                    <input type="text" name="txtMalop" required
                        value="<?= isset($_POST['txtMalop']) ? htmlspecialchars($_POST['txtMalop']) : '' ?>">
                </div>

                <div class="form-group full-width">
                    <label>Email liên hệ <span>*</span></label>
                    <input type="email" name="txtEmail" required
                        value="<?= isset($_POST['txtEmail']) ? htmlspecialchars($_POST['txtEmail']) : '' ?>">
                </div>

                <div class="form-group">
                    <label>Mật khẩu (Tối thiểu 6 ký tự) <span>*</span></label>
                    <input type="password" name="txtMatkhau" required placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label>Nhập lại mật khẩu <span>*</span></label>
                    <input type="password" name="txtreMatkhau" required placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label>Giới tính</label>
                    <div class="radio-group">
                        <label class="radio-item">
                            <input type="radio" name="rdGt" value="0" checked> Nam
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="rdGt" value="1" <?= (isset($_POST['rdGt']) && $_POST['rdGt'] == 1) ? 'checked' : '' ?>> Nữ
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Ảnh đại diện</label>
                    <input type="file" name="fileAnh" accept="image/*">
                </div>

                <div class="form-group full-width">
                    <button type="submit" name="sbDangky" class="btn-submit">Đăng ký</button>
                </div>
            </div>
        </form>

        <div class="footer-link">
            Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
        </div>
    </div>
</body>

</html>