<?php
require("phandau.php");

// 1. Kiểm tra ID hoạt động
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM tblhoatdong WHERE hoatdong_id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<script>alert('Không tìm thấy hoạt động!'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

// 2. KIỂM TRA: User đã đăng ký chưa? Lấy luôn thông tin minh chứng nếu có
$da_dang_ky = false;
$thong_tin_dk = null; // Biến chứa row trong bảng tbldangkyhoatdong

if (isset($_SESSION['username'])) {
    $check_stmt = $conn->prepare("SELECT * FROM tbldangkyhoatdong WHERE hoatdong_id = ? AND username = ?");
    $check_stmt->bind_param("is", $id, $_SESSION['username']);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    if ($check_result->num_rows > 0) {
        $da_dang_ky = true;
        $thong_tin_dk = $check_result->fetch_assoc(); // Lấy dữ liệu đăng ký để xem minh chứng
    }
}

// --- XỬ LÝ: Đăng ký tham gia ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dangky_submit'])) {
    if (!isset($_SESSION['username'])) {
        echo "<script>alert('Cần đăng nhập!'); window.location.href='login.php';</script>";
        exit();
    }
    if (time() >= strtotime($row['ngay_bat_dau'])) {
        echo "<script>alert('Hoạt động đã diễn ra!'); window.location.href='chitiethoatdong.php?id=" . $id . "';</script>";
        exit();
    }
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("INSERT INTO tbldangkyhoatdong (hoatdong_id, username) VALUES (?, ?)");
    $stmt->bind_param("is", $id, $username);
    if ($stmt->execute()) {
        echo "<script>alert('Đăng ký thành công! Bạn hãy nộp minh chứng.'); window.location.href='chitiethoatdong.php?id=" . $id . "';</script>";
    }
    exit();
}

// --- XỬ LÝ: Hủy đăng ký ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['huy_dangky_submit'])) {
    if (time() >= strtotime($row['ngay_bat_dau'])) {
        echo "<script>alert('Không thể hủy khi hoạt động đã/đang diễn ra!'); window.location.href='chitiethoatdong.php?id=" . $id . "';</script>";
        exit();
    }
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("DELETE FROM tbldangkyhoatdong WHERE hoatdong_id = ? AND username = ?");
    $stmt->bind_param("is", $id, $username);
    if ($stmt->execute()) {
        echo "<script>alert('Đã hủy đăng ký.'); window.location.href='chitiethoatdong.php?id=" . $id . "';</script>";
    }
    exit();
}

// --- XỬ LÝ MỚI: Upload Minh Chứng ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_minhchung'])) {
    if (!$da_dang_ky) {
        echo "<script>alert('Bạn chưa đăng ký hoạt động này!');</script>";
    } else {
        if (isset($_FILES['file_minhchung']) && $_FILES['file_minhchung']['error'] == 0) {
            $allowed = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'); // Cho phép cả ảnh và tài liệu
            $filename = $_FILES['file_minhchung']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                // Đặt tên file: proof_idhoatdong_username_timestamp
                $new_filename = "proof_" . $id . "_" . $_SESSION['username'] . "_" . time() . "." . $ext;
                $upload_dir = "uploads/proofs/"; // Tạo thư mục này nếu chưa có

                if (!file_exists($upload_dir))
                    mkdir($upload_dir, 0777, true);

                if (move_uploaded_file($_FILES['file_minhchung']['tmp_name'], $upload_dir . $new_filename)) {
                    // Update database
                    $stmt_up = $conn->prepare("UPDATE tbldangkyhoatdong SET minh_chung = ? WHERE hoatdong_id = ? AND username = ?");
                    $stmt_up->bind_param("sis", $new_filename, $id, $_SESSION['username']);

                    if ($stmt_up->execute()) {
                        echo "<script>alert('Nộp minh chứng thành công!'); window.location.href='chitiethoatdong.php?id=" . $id . "';</script>";
                    } else {
                        echo "<script>alert('Lỗi cập nhật DB: " . $conn->error . "');</script>";
                    }
                } else {
                    echo "<script>alert('Lỗi khi tải file lên server!');</script>";
                }
            } else {
                echo "<script>alert('File không hợp lệ! Chỉ chấp nhận ảnh, PDF, Word.');</script>";
            }
        } else {
            echo "<script>alert('Vui lòng chọn file!');</script>";
        }
    }
}
?>

<style>
    /* --- CSS Cũ --- */
    .detail-container {
        max-width: 900px;
        margin: 30px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .detail-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: #fff;
        padding: 40px;
        position: relative;
    }

    .detail-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 20px;
        line-height: 1.3;
    }

    .detail-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        background: rgba(255, 255, 255, 0.1);
        padding: 15px;
        border-radius: 10px;
        backdrop-filter: blur(5px);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-body {
        padding: 40px;
        color: #334155;
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .detail-label {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        display: block;
        font-size: 1.2rem;
        border-left: 4px solid #0d6efd;
        padding-left: 10px;
    }

    .status-tag {
        position: absolute;
        top: 20px;
        right: 20px;
        background: #fff;
        color: #0d6efd;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.9rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .action-bar {
        padding: 20px 40px;
        background: #f8f9fa;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        align-items: center;
    }

    .btn-custom {
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        border: none;
    }

    .btn-register {
        background: #0d6efd;
        color: #fff;
    }

    .btn-register:hover {
        background: #0b5ed7;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }

    .btn-cancel {
        background: #ef4444;
        color: #fff;
    }

    .btn-cancel:hover {
        background: #dc2626;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    .btn-disabled {
        background: #94a3b8;
        color: #e2e8f0;
        cursor: not-allowed !important;
    }

    /* --- CSS MỚI CHO KHUNG UPLOAD MINH CHỨNG --- */
    .proof-section {
        margin-top: 30px;
        padding: 20px;
        background: #f1f5f9;
        border-radius: 12px;
        border: 2px dashed #cbd5e1;
    }

    .proof-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .proof-preview {
        margin-bottom: 15px;
        text-align: center;
    }

    .proof-img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .file-input-wrapper {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .file-custom {
        padding: 8px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background: #fff;
        flex: 1;
    }

    .btn-upload {
        background: #10b981;
        color: white;
    }

    .btn-upload:hover {
        background: #059669;
    }
</style>

<div class="content-section" style="background-color: #f1f5f9; min-height: 80vh; padding-top: 20px;">
    <div class="container">
        <a href="javascript:history.back()"
            style="color: #64748b; text-decoration: none; margin-bottom: 20px; display: inline-block;">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>

        <div class="detail-container">
            <div class="detail-header">
                <?php
                $ngay_hd = strtotime($row['ngay_bat_dau']);
                $hien_tai = time();
                if ($hien_tai < $ngay_hd) {
                    echo '<span class="status-tag"><i class="fa-solid fa-bolt"></i> Sắp diễn ra</span>';
                } else {
                    echo '<span class="status-tag" style="color:#64748b;"><i class="fa-solid fa-check"></i> Đã kết thúc</span>';
                }
                ?>
                <h1 class="detail-title"><?php echo $row['ten_hoat_dong']; ?></h1>
                <div class="detail-meta">
                    <div class="meta-item"><i class="fa-regular fa-clock"></i>
                        <span><?php echo date('H:i d/m/Y', $ngay_hd); ?></span></div>
                    <div class="meta-item"><i class="fa-solid fa-location-dot"></i>
                        <span><?php echo $row['dia_diem']; ?></span></div>
                </div>
            </div>

            <div class="detail-body">
                <span class="detail-label">Nội dung hoạt động</span>
                <div style="text-align: justify;">
                    <?php echo nl2br($row['mo_ta_hoat_dong']); ?>
                </div>

                <?php if ($da_dang_ky): ?>
                    <div class="proof-section">
                        <div class="proof-header">
                            <strong style="color:#0f172a; font-size:1.1rem;"><i class="fa-solid fa-paperclip"></i> Minh
                                chứng tham gia</strong>
                            <?php if (!empty($thong_tin_dk['minh_chung'])): ?>
                                <span style="color:#10b981; font-size:0.9rem;"><i class="fa-solid fa-circle-check"></i> Đã
                                    nộp</span>
                            <?php else: ?>
                                <span style="color:#f59e0b; font-size:0.9rem;"><i class="fa-solid fa-circle-exclamation"></i>
                                    Chưa nộp</span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($thong_tin_dk['minh_chung'])):
                            $file_path = "uploads/proofs/" . $thong_tin_dk['minh_chung'];
                            $file_ext = pathinfo($thong_tin_dk['minh_chung'], PATHINFO_EXTENSION);
                            ?>
                            <div class="proof-preview">
                                <?php if (in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                    <img src="<?php echo $file_path; ?>" class="proof-img" alt="Minh chứng">
                                <?php else: ?>
                                    <a href="<?php echo $file_path; ?>" target="_blank" class="btn-custom"
                                        style="background:#e2e8f0; color:#333;">
                                        <i class="fa-solid fa-file-arrow-down"></i> Tải về xem file (<?php echo $file_ext; ?>)
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($hien_tai < $ngay_hd + 86400 * 7): // Cho phép nộp minh chứng đến 7 ngày sau khi bắt đầu (ví dụ) ?>
                            <form method="POST" enctype="multipart/form-data" class="file-input-wrapper">
                                <input type="file" name="file_minhchung" class="file-custom" required
                                    accept="image/*, .pdf, .doc, .docx">
                                <button type="submit" name="upload_minhchung" class="btn-custom btn-upload">
                                    <i class="fa-solid fa-upload"></i>
                                    <?php echo empty($thong_tin_dk['minh_chung']) ? 'Nộp minh chứng' : 'Cập nhật lại'; ?>
                                </button>
                            </form>
                            <small style="color:#64748b; margin-top:5px; display:block;">Hỗ trợ ảnh (jpg, png) và tài liệu (pdf,
                                word).</small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="action-bar">
                <?php if ($hien_tai < $ngay_hd): ?>
                    <?php if ($da_dang_ky): ?>
                        <span style="margin-right: auto; color: #10b981; font-weight: 600;">
                            <i class="fa-solid fa-check-double"></i> Bạn đã đăng ký tham gia
                        </span>

                        <form method="post"
                            onsubmit="return confirm('Bạn chắc chắn muốn hủy? Minh chứng (nếu có) sẽ bị xóa.');">
                            <input type="hidden" name="huy_dangky_submit" value="1">
                            <button type="submit" class="btn-custom btn-cancel">
                                <i class="fa-solid fa-user-xmark"></i> Hủy đăng ký
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="post">
                            <input type="hidden" name="dangky_submit" value="1">
                            <button type="submit" class="btn-custom btn-register">
                                <i class="fa-solid fa-pen-to-square"></i> Đăng ký tham gia
                            </button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="btn-custom btn-disabled"><i class="fa-solid fa-lock"></i> Đã đóng đăng ký</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require("phancuoi.php"); ?>