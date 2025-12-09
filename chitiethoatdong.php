<?php
require("phandau.php");

// 1. Kiểm tra xem có ID được truyền vào không
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Truy vấn dữ liệu hoạt động theo ID
    $sql = "SELECT * FROM tblhoatdong WHERE hoatdong_id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        // Không tìm thấy hoạt động -> Chuyển hướng hoặc báo lỗi
        echo "<script>alert('Không tìm thấy hoạt động này!'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    // Không có ID -> Về trang chủ
    header("Location: index.php");
    exit();
}
?>

<style>
    /* CSS Riêng cho trang chi tiết */
    .detail-container {
        max-width: 900px;
        margin: 30px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    /* Phần Header chứa Tên hoạt động và Meta info */
    .detail-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        /* Màu xanh chủ đạo */
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
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.1);
        /* Nền mờ nhẹ */
        padding: 15px;
        border-radius: 10px;
        backdrop-filter: blur(5px);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Phần Nội dung chi tiết */
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

    /* Nút bấm hành động */
    .action-bar {
        padding: 20px 40px;
        background: #f8f9fa;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 15px;
        justify-content: flex-end;
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
    }

    .btn-back {
        background: #e2e8f0;
        color: #475569;
    }

    .btn-back:hover {
        background: #cbd5e1;
        color: #1e293b;
    }

    .btn-register {
        background: #0d6efd;
        color: #fff;
    }

    .btn-register:hover {
        background: #0b5ed7;
        color: #fff;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }

    /* Xử lý trạng thái */
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
</style>

<div class="content-section" style="background-color: #f1f5f9; min-height: 80vh; padding-top: 20px;">
    <div class="container">

        <a href="javascript:history.back()" style="color: #64748b; text-decoration: none; margin-bottom: 20px; display: inline-block;">
            <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
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
                    <div class="meta-item">
                        <i class="fa-regular fa-clock"></i>
                        <span><?php echo date('H:i', $ngay_hd); ?> - Ngày <?php echo date('d/m/Y', $ngay_hd); ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fa-solid fa-location-dot"></i>
                        <span><?php echo $row['dia_diem']; ?></span>
                    </div>
                </div>
            </div>

            <div class="detail-body">
                <span class="detail-label">Nội dung hoạt động</span>
                <div style="text-align: justify;">
                    <?php
                    // Hàm nl2br giúp chuyển đổi xuống dòng trong DB thành thẻ <br> trong HTML
                    echo nl2br($row['mo_ta_hoat_dong']);
                    ?>
                </div>
            </div>

            <div class="action-bar">
                <!-- <a href="index.php" class="btn-custom btn-back">
                    <i class="fa-solid fa-house"></i> Trang chủ
                </a> -->

                <?php if ($hien_tai < $ngay_hd): // Chỉ hiện nút đăng ký nếu chưa diễn ra 
                ?>
                    <a href="#" class="btn-custom btn-register" onclick="alert('Tính năng đăng ký đang được cập nhật!')">
                        <i class="fa-solid fa-pen-to-square"></i> Đăng ký tham gia
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php
require("phancuoi.php");
?>