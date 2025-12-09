<?php
// Bắt đầu session ở đầu file
session_start();

// 1. Tải thư viện và kết nối CSDL
require_once 'vendor/autoload.php';
require_once 'config.php'; // Đảm bảo file này chứa biến $conn

// 2. CHUYỂN HƯỚNG NẾU ĐÃ ĐĂNG NHẬP
if (isset($_SESSION['emailUser'])) {
    header('Location: index.php');
    exit();
}

// --- Biến để chứa thông báo lỗi ---
$local_login_error = '';
$google_login_error = '';


// 4. XỬ LÝ GOOGLE OAUTH CALLBACK
if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        if (isset($token['error'])) {
            throw new Exception($token['error_description']);
        }
        $_SESSION['access_token'] = $token;
        header('Location: ' . filter_var($client->getRedirectUri(), FILTER_SANITIZE_URL));
        exit();
    } catch (Exception $e) {
        $google_login_error = 'Lỗi xác thực Google: ' . $e->getMessage();
    }
}

// 5. KIỂM TRA NẾU ĐÃ ĐĂNG NHẬP GOOGLE
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
}

// 6. XỬ LÝ ĐĂNG NHẬP BẰNG GOOGLE (Nếu có token)
if ($client->getAccessToken()) {
    try {
        $google_service = new Google_Service_Oauth2($client);
        $googleUserInfo = $google_service->userinfo->get();

        $email_google = $googleUserInfo['email'];

        // Kiểm tra xem email đã tồn tại chưa
        $sql_check_google = "SELECT * FROM tbluser WHERE email = ?";
        $stmt_google = $conn->prepare($sql_check_google);
        $stmt_google->bind_param("s", $email_google);
        $stmt_google->execute();
        $result_google = $stmt_google->get_result();

        if ($result_google->num_rows > 0) {
            // **TRƯỜNG HỢP 1: ĐÃ CÓ TÀI KHOẢN**
            // Email đã tồn tại -> Tiến hành đăng nhập
            $user_data = $result_google->fetch_assoc();

            $_SESSION['username'] = $user_data['username'];
            $_SESSION['emailUser'] = $user_data['email'];
            $_SESSION['role'] = $user_data['role'];
            $_SESSION['fullname'] = $user_data['fullname'];

            header('Location: index.php');
            exit();
        } else {
            // **TRƯỜNG HỢP 2: CHƯA CÓ TÀI KHOẢN**
            // Email chưa tồn tại -> Tự động tạo tài khoản mới

            $fullname = $googleUserInfo['name'];
            $avatar = $googleUserInfo['picture']; // Link ảnh đại diện từ Google

            // Tạo username từ email (phần trước @)
            // Lọc bỏ ký tự đặc biệt, chỉ giữ lại chữ/số
            $username_base = preg_replace("/[^a-zA-Z0-9]/", "", explode('@', $email_google)[0]);
            if (empty($username_base)) {
                $username_base = 'user';
            } // Đề phòng email lạ

            $new_username = $username_base;
            $counter = 0;

            // Vòng lặp để đảm bảo username là duy nhất
            $sql_check_username = "SELECT username FROM tbluser WHERE username = ?";
            $stmt_check_username = $conn->prepare($sql_check_username);

            while (true) {
                $stmt_check_username->bind_param("s", $new_username);
                $stmt_check_username->execute();
                $result_check = $stmt_check_username->get_result();

                if ($result_check->num_rows == 0) {
                    // Tên username này OK, không trùng
                    break;
                }

                // Bị trùng, thử tên khác (ví dụ: user -> user1, user2)
                $counter++;
                $new_username = $username_base . $counter;
            }
            $stmt_check_username->close();

            // Tạo mật khẩu ngẫu nhiên (vì họ sẽ luôn đăng nhập bằng Google)
            $random_pass = md5(rand() . time());
            $default_gender = 0; // 0 = Nam (mặc định)
            $default_role = 0;   // 0 = User (mặc định)
            $default_status = 1; // 1 = Active (vì Google đã xác thực)

            // SQL để chèn user mới
            $sql_insert = "INSERT INTO tbluser 
                           (username, password, fullname, gender, email, avatar, role, status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param(
                "sssisisi",
                $new_username,
                $random_pass,
                $fullname,
                $default_gender,
                $email_google,
                $avatar,
                $default_role,
                $default_status
            );

            if ($stmt_insert->execute()) {
                // Đăng ký thành công, giờ thì đăng nhập
                $_SESSION['username'] = $new_username;
                $_SESSION['emailUser'] = $email_google;
                $_SESSION['role'] = $default_role;

                header('Location: index.php'); // Chuyển hướng
                exit();
            } else {
                // Lỗi khi chèn vào CSDL
                $google_login_error = "Lỗi khi tạo tài khoản: " . $conn->error;
                unset($_SESSION['access_token']);
                $client->revokeToken();
            }
            $stmt_insert->close();
        }
        $stmt_google->close();
    } catch (Exception $e) {
        // Token hết hạn hoặc lỗi
        $google_login_error = "Phiên Google hết hạn, vui lòng thử lại.";
        unset($_SESSION['access_token']);
        $client->revokeToken();
    }
}

// Nếu không có token, tạo link đăng nhập
if (!$client->getAccessToken()) {
    $loginUrl = $client->createAuthUrl();
}


// 7. XỬ LÝ ĐĂNG NHẬP THƯỜNG (FORM CŨ CỦA BẠN)
if (isset($_REQUEST['sbSubmit'])) {
    $tendangnhap = $_REQUEST['txtUsername'];
    $matkhau_raw = $_REQUEST['txtPassword'];

    $sql = "SELECT * FROM tbluser WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tendangnhap);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $matkhau_hashed_db = $row['password'];
        $matkhau_input_md5 = md5($matkhau_raw);

        if ($matkhau_input_md5 === $matkhau_hashed_db && $row['status']) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['emailUser'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            header("Location: index.php");
            exit();
        } else if ($matkhau_input_md5 === $matkhau_hashed_db && !$row['status']) {
            $local_login_error = 'Tài khoản chưa được kích hoạt vui lòng liên hệ admin để được kích hoạt';
        } else {
            $local_login_error = 'Tên đăng nhập hoặc mật khẩu không đúng';
        }
    } else {
        $local_login_error = 'Tên đăng nhập hoặc mật khẩu không đúng';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập | Club Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #007bff;
            --danger-color: #dc3545;
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
        }

        .login-container {
            max-width: 450px;
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
            margin-bottom: 2rem;
        }

        /* --- Nút Đăng nhập Google --- */
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .btn-google svg {
            width: 20px;
            height: 20px;
            margin-right: 12px;
        }

        /* --- Dải phân cách "Hoặc" --- */
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

        /* --- Form đăng nhập thường --- */
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
            padding: 12px 15px;
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

        /* --- Nút Đăng nhập --- */
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
        }

        .btn-submit:hover {
            background-color: #0056b3;
            /* Màu tối hơn */
        }

        /* --- Link đăng ký --- */
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

        /* --- Thông báo lỗi --- */
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
    </style>
</head>

<body>

    <div class="login-container">
        <h3>Trang đăng nhập</h3>

        <form action="" method="post" name="f1">
            <div class="form-group">
                <label for="txtUsername">Tên đăng nhập:</label>
                <input type="text" class="form-control" id="txtUsername" name="txtUsername" required
                    value="<?php echo @htmlspecialchars($_REQUEST['txtUsername']); ?>">
            </div>

            <div class="form-group">
                <label for="txtPassword">Mật khẩu:</label>
                <input type="password" class="form-control" id="txtPassword" name="txtPassword" required>
            </div>

            <button type="submit" class="btn-submit" name="sbSubmit">Đăng nhập</button>

            <div class="register-link">
                Chưa có tài khoản? <a href="signup.php">Đăng ký ngay</a>
                <br><br>
            </div>
        </form>

        <?php
        // 9. HIỂN THỊ CÁC LỖI (NẾU CÓ)
        if (!empty($google_login_error)) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($google_login_error) . '</div>';
        }
        if (!empty($local_login_error)) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($local_login_error) . '</div>';
        }

        // 10. HIỂN THỊ NÚT ĐĂNG NHẬP GOOGLE
        if (!empty($loginUrl)) {
            echo '<div class="divider">hoặc</div>';
            echo "<a href='" . htmlspecialchars($loginUrl) . "' class='btn-google'>";
            // SVG Logo Google
            echo '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><g><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.13 5.51C44.38 38.37 46.98 32.07 46.98 24.55z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.13-5.51c-2.18 1.45-5.04 2.3-8.76 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path><path fill="none" d="M0 0h48v48H0z"></path></g></svg>';
            echo 'Đăng nhập bằng Google';
            echo "</a>";
        }
        ?>

    </div>
</body>

</html>