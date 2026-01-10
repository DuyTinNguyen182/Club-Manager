<?php
include('phandau.php');

if (isset($_POST['btnGuiLienHe'])) {
    $ten = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $noidung = trim($_POST['content']);
    $ngaygui = date('Y-m-d H:i:s');
    $trangthai = 0;

    if (empty($ten) || empty($email) || empty($noidung)) {
        $_SESSION['msg_error'] = "Vui lòng nhập đầy đủ thông tin!";
    } elseif (!preg_match('/^[\p{L}\s]+$/u', $ten)) {
        $_SESSION['msg_error'] = "Họ tên chỉ được chứa chữ cái và khoảng trắng!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg_error'] = "Địa chỉ Email không hợp lệ!";
    } else {
        $sql = "INSERT INTO tblcontact (ten_nguoi_gui, noi_dung, email, ngay_gui, trang_thai) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $ten, $noidung, $email, $ngaygui, $trangthai);

        if ($stmt->execute()) {
            $_SESSION['msg_success'] = "Gửi liên hệ thành công! Ban chủ nhiệm sẽ phản hồi sớm nhất.";
        } else {
            $_SESSION['msg_error'] = "Có lỗi xảy ra: " . $stmt->error;
        }
        $stmt->close();
    }

    // Chuyển hướng ngay lập tức để tránh lỗi F5 (Resubmission)
    header("Location: contact.php");
    exit();
}

$u_fullname = "";
$u_email = "";
if (isset($_SESSION['username'])) {
    $u_fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : "";
    $u_email = isset($_SESSION['emailUser']) ? $_SESSION['emailUser'] : "";
}
?>

<style>
    .contact-wrapper {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 40px;
        margin-bottom: 40px;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }

    .section-title {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: var(--accent-color);
        border-radius: 2px;
    }

    .info-item {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        align-items: flex-start;
    }

    .info-icon {
        width: 45px;
        height: 45px;
        background: #eff6ff;
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        transition: 0.3s;
    }

    .info-item:hover .info-icon {
        background: var(--primary-color);
        color: #fff;
    }

    .info-content h5 {
        font-weight: 600;
        margin-bottom: 5px;
        color: #1e293b;
    }

    .info-content p {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0;
    }

    .map-container {
        width: 100%;
        height: 250px;
        border-radius: 12px;
        overflow: hidden;
        margin-top: 20px;
        border: 1px solid #e2e8f0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #334155;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        transition: border 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    @media (max-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="contact-wrapper">
    <div class="contact-grid">
        <div class="contact-info">
            <h3 class="section-title">Thông Tin Liên Hệ</h3>
            <p style="margin-bottom: 30px; color: #64748b;">
                Hãy liên hệ với chúng tôi nếu bạn có bất kỳ thắc mắc nào về hoạt động của CLB hoặc muốn tham gia cùng
                chúng tôi.
            </p>
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                <div class="info-content">
                    <h5>Địa chỉ</h5>
                    <p>Số 126 Nguyễn Thiện Thành, Phường Hòa Thuận, Tỉnh Vĩnh Long</p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                <div class="info-content">
                    <h5>Điện thoại</h5>
                    <p>0123456789</p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                <div class="info-content">
                    <h5>Email</h5>
                    <p>clbtinhoc@tvu.edu.vn</p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-globe"></i></div>
                <div class="info-content">
                    <h5>Website</h5>
                    <p>www.clbtinhoctvu.vn</p>
                </div>
            </div>
            <div class="map-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3930.126071850772!2d106.3439493747926!3d9.923456890177708!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0175ea296facb%3A0x55ded92e29068221!2zVHLGsOG7nW5nIMSQ4bqhaSBI4buNYyBUcsOgIFZpbmg!5e0!3m2!1svi!2s!4v1703088000000!5m2!1svi!2s"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>

        <div class="contact-form">
            <h3 class="section-title">Gửi Tin Nhắn</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" name="fullname" class="form-control" placeholder="Nhập họ tên của bạn..."
                        value="<?php echo htmlspecialchars($u_fullname); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="Nhập địa chỉ email..."
                        value="<?php echo htmlspecialchars($u_email); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                    <textarea name="content" class="form-control" rows="6" placeholder="Bạn cần hỗ trợ vấn đề gì?"
                        required></textarea>
                </div>
                <button type="submit" name="btnGuiLienHe" class="btn btn-primary"
                    style="width: 100%; padding: 12px; font-size: 1rem; cursor: pointer;">
                    <i class="fa-solid fa-paper-plane"></i> Gửi ngay
                </button>
            </form>
        </div>
    </div>
</div>

<?php
// Hiển thị thông báo từ Session và sau đó xóa nó đi
if (isset($_SESSION['msg_success'])) {
    echo "<script>alert('" . $_SESSION['msg_success'] . "');</script>";
    unset($_SESSION['msg_success']);
}
if (isset($_SESSION['msg_error'])) {
    echo "<script>alert('" . $_SESSION['msg_error'] . "');</script>";
    unset($_SESSION['msg_error']);
}
?>

</main>
</div>
</div>
<?php include('phancuoi.php'); ?>