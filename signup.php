<?php
// Bắt đầu session
session_start();

require_once 'config.php'; // File kết nối CSDL ($conn)

// 2. CHUYỂN HƯỚNG NẾU ĐÃ ĐĂNG NHẬP
if (isset($_SESSION['emailUser'])) {
	header('Location: index.php');
	exit();
}

// --- Biến thông báo ---
$error_msg = '';
$success_msg = '';



// 4. XỬ LÝ ĐĂNG KÝ FORM THƯỜNG
if (isset($_POST['sbDangky'])) {
	$tendangnhap = $_POST['txtTendangnhap'];
	$matkhau_raw = $_POST['txtMatkhau'];
	$re_matkhau = $_POST['txtreMatkhau'];
	$tendaydu = $_POST['txtTendaydu'];
	$email = $_POST['txtEmail'];
	$gioitinh = $_POST['rdGt'];

	// Validate cơ bản
	if ($matkhau_raw !== $re_matkhau) {
		$error_msg = "Mật khẩu nhập lại không khớp.";
	} else {
		// Kiểm tra user/email tồn tại
		$sqlcheck = "SELECT * FROM tbluser WHERE username = ? OR email = ?";
		$stmt_check = $conn->prepare($sqlcheck);
		$stmt_check->bind_param("ss", $tendangnhap, $email);
		$stmt_check->execute();
		$result_check = $stmt_check->get_result();

		if ($result_check->num_rows > 0) {
			$error_msg = "Tên đăng nhập hoặc Email đã tồn tại.";
		} else {
			// Xử lý Upload Ảnh
			$avatar_path = 'uploads/avatar-default.png'; // Mặc định
			$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
			$upload_ok = true;

			if (!empty($_FILES["fileAnh"]["name"])) {
				$fileName = basename($_FILES["fileAnh"]["name"]);
				$fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

				if (in_array($fileType, $allowTypes)) {
					$tm = "uploads/";
					// Tạo tên file mới để tránh trùng: IMG_username_time.jpg
					$newFileName = "IMG_" . $tendangnhap . "_" . time() . "." . $fileType;
					$targetFilePath = $tm . $newFileName;

					if (move_uploaded_file($_FILES["fileAnh"]["tmp_name"], $targetFilePath)) {
						$avatar_path = $targetFilePath;
					} else {
						$error_msg = "Lỗi khi upload ảnh.";
						$upload_ok = false;
					}
				} else {
					$error_msg = "Chỉ chấp nhận file ảnh (JPG, JPEG, PNG, GIF).";
					$upload_ok = false;
				}
			}

			// Nếu không có lỗi upload thì Insert vào DB
			if ($upload_ok && empty($error_msg)) {
				$matkhau_hash = md5($matkhau_raw); // Giữ nguyên cách mã hóa cũ của bạn
				$role = 0;
				$status = 0; // Đăng ký xong có thể cần duyệt hoặc active ngay tùy bạn

				$sql_insert = "INSERT INTO tbluser (username, password, fullname, gender, email, avatar, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
				$stmt_insert = $conn->prepare($sql_insert);
				$stmt_insert->bind_param("sssisisi", $tendangnhap, $matkhau_hash, $tendaydu, $gioitinh, $email, $avatar_path, $role, $status);

				if ($stmt_insert->execute()) {
					$success_msg = "Đăng ký thành công! Đang chuyển hướng...";
					// Tự động chuyển trang sau 2 giây
					header("refresh:2;url=login.php");
				} else {
					$error_msg = "Lỗi hệ thống: " . $conn->error;
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
	<title>Đăng ký | Club Manager</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

	<style>
		/* --- COPY CSS TỪ LOGIN.PHP --- */
		:root {
			--primary-color: #007bff;
			--danger-color: #dc3545;
			--success-color: #28a745;
			--light-gray: #f0f2f5;
			--text-color: #333;
			--border-color: #ddd;
			--white: #ffffff;
			--shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
		}

		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		body {
			font-family: 'Roboto', sans-serif;
			background-color: var(--light-gray);
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
			color: var(--text-color);
			padding: 20px 0;
			/* Thêm padding để tránh sát lề khi form dài */
		}

		.login-container {
			max-width: 500px;
			/* Rộng hơn login xíu vì nhiều trường hơn */
			width: 100%;
			padding: 2.5rem;
			background-color: var(--white);
			border-radius: 10px;
			box-shadow: var(--shadow);
			border: 1px solid var(--border-color);
			margin: 1rem;
		}

		.login-container h3 {
			text-align: center;
			font-weight: 700;
			font-size: 1.75rem;
			color: var(--text-color);
			margin-bottom: 1.5rem;
		}

		/* Form Controls */
		.form-group {
			margin-bottom: 1.25rem;
		}

		.form-group label {
			display: block;
			font-weight: 500;
			margin-bottom: 0.5rem;
			font-size: 0.95rem;
		}

		.form-control {
			width: 100%;
			padding: 10px 15px;
			font-size: 1rem;
			border-radius: 6px;
			border: 1px solid var(--border-color);
			transition: border-color 0.2s ease, box-shadow 0.2s ease;
		}

		.form-control:focus {
			outline: none;
			border-color: var(--primary-color);
			box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
		}

		.form-control[type="file"] {
			padding: 8px;
			/* Chỉnh lại padding cho input file */
			background: #fafafa;
		}

		/* Radio buttons styling */
		.radio-group {
			display: flex;
			gap: 20px;
			margin-top: 5px;
		}

		.radio-label {
			display: flex;
			align-items: center;
			cursor: pointer;
			font-weight: normal;
		}

		.radio-label input {
			margin-right: 8px;
		}

		/* Buttons */
		.btn-submit {
			width: 100%;
			padding: 14px 15px;
			font-size: 1rem;
			font-weight: 700;
			color: var(--white);
			background-color: var(--primary-color);
			border: none;
			border-radius: 6px;
			cursor: pointer;
			transition: background-color 0.3s ease;
			margin-top: 10px;
		}

		.btn-submit:hover {
			background-color: #0056b3;
		}

		/* Google Button */
		.btn-google {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100%;
			padding: 12px 15px;
			font-size: 1rem;
			font-weight: 500;
			color: #444;
			background-color: var(--white);
			border: 1px solid #cdd1d4;
			border-radius: 6px;
			text-decoration: none;
			transition: all 0.3s ease;
			margin-bottom: 1.5rem;
		}

		.btn-google:hover {
			background-color: #f8f9fa;
			border-color: #b2b6b9;
		}

		.btn-google svg {
			width: 20px;
			height: 20px;
			margin-right: 12px;
		}

		.divider {
			display: flex;
			align-items: center;
			text-align: center;
			color: #888;
			margin-bottom: 1.5rem;
			font-size: 0.9rem;
			font-weight: 500;
		}

		.divider::before,
		.divider::after {
			content: '';
			flex: 1;
			border-bottom: 1px solid #e0e0e0;
		}

		.divider::before {
			margin-right: 0.75em;
		}

		.divider::after {
			margin-left: 0.75em;
		}

		/* Alerts */
		.alert {
			padding: 1rem;
			margin-bottom: 1rem;
			border-radius: 6px;
			font-size: 0.95rem;
			border: 1px solid transparent;
		}

		.alert-danger {
			color: #721c24;
			background-color: #f8d7da;
			border-color: #f5c6cb;
		}

		.alert-success {
			color: #155724;
			background-color: #d4edda;
			border-color: #c3e6cb;
		}

		/* Footer Link */
		.register-link {
			text-align: center;
			margin-top: 1.5rem;
			font-size: 0.95rem;
		}

		.register-link a {
			color: var(--primary-color);
			font-weight: 500;
			text-decoration: none;
		}

		.register-link a:hover {
			text-decoration: underline;
		}
	</style>
</head>

<body>

	<div class="login-container">
		<h3>Đăng ký tài khoản</h3>

		<?php
		// Hiển thị thông báo lỗi hoặc thành công
		if (!empty($error_msg)) {
			echo '<div class="alert alert-danger">' . $error_msg . '</div>';
		}
		if (!empty($success_msg)) {
			echo '<div class="alert alert-success">' . $success_msg . '</div>';
		}
		?>

		<form action="" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="txtTendangnhap">Tên đăng nhập (*):</label>
				<input type="text" class="form-control" id="txtTendangnhap" name="txtTendangnhap"
					value="<?php echo isset($_POST['txtTendangnhap']) ? htmlspecialchars($_POST['txtTendangnhap']) : ''; ?>"
					required>
			</div>

			<div class="form-group">
				<label for="txtEmail">Email (*):</label>
				<input type="email" class="form-control" id="txtEmail" name="txtEmail"
					value="<?php echo isset($_POST['txtEmail']) ? htmlspecialchars($_POST['txtEmail']) : ''; ?>"
					required>
			</div>

			<div class="form-group">
				<label for="txtTendaydu">Họ và tên:</label>
				<input type="text" class="form-control" id="txtTendaydu" name="txtTendaydu"
					value="<?php echo isset($_POST['txtTendaydu']) ? htmlspecialchars($_POST['txtTendaydu']) : ''; ?>">
			</div>

			<div class="form-group">
				<label for="txtMatkhau">Mật khẩu (*):</label>
				<input type="password" class="form-control" id="txtMatkhau" name="txtMatkhau" required>
			</div>

			<div class="form-group">
				<label for="txtreMatkhau">Nhập lại mật khẩu (*):</label>
				<input type="password" class="form-control" id="txtreMatkhau" name="txtreMatkhau" required>
			</div>

			<div class="form-group">
				<label>Giới tính:</label>
				<div class="radio-group">
					<label class="radio-label">
						<input type="radio" name="rdGt" value="0" checked> Nam
					</label>
					<label class="radio-label">
						<input type="radio" name="rdGt" value="1" <?php if (isset($_POST['rdGt']) && $_POST['rdGt'] == 1)
							echo 'checked'; ?>> Nữ
					</label>
				</div>
			</div>

			<div class="form-group">
				<label for="fileAnh">Ảnh đại diện:</label>
				<input type="file" class="form-control" id="fileAnh" name="fileAnh">
			</div>

			<button type="submit" class="btn-submit" name="sbDangky">Đăng ký tài khoản</button>
		</form>
	</div>

</body>

</html>