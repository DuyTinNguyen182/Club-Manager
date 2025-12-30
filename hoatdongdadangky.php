<?php
require("phandau.php");

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Vui lòng đăng nhập để xem danh sách hoạt động.'); window.location.href='login.php';</script>";
    exit();
}

$username = $_SESSION['username'];

// 2. Truy vấn danh sách hoạt động user đã đăng ký
// CẬP NHẬT: Thêm hd.ngay_ket_thuc vào SELECT
$sql = "SELECT dk.trang_thai, dk.minh_chung, hd.hoatdong_id, hd.ten_hoat_dong, hd.ngay_bat_dau, hd.ngay_ket_thuc 
        FROM tbldangkyhoatdong dk
        JOIN tblhoatdong hd ON dk.hoatdong_id = hd.hoatdong_id
        WHERE dk.username = ?
        ORDER BY hd.ngay_bat_dau DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
    /* CSS Base */
    .list-container {
        max-width: 1000px;
        margin: 30px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        padding: 30px;
    }

    .page-title {
        color: #0f172a;
        font-weight: 800;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f5f9;
    }

    /* Table Styles */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .custom-table th {
        text-align: left;
        padding: 15px;
        color: #64748b;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .custom-table td {
        background: #f8f9fa;
        padding: 20px 15px;
        vertical-align: middle;
    }

    .custom-table tr td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .custom-table tr td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .activity-name {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
        color: #334155;
        text-decoration: none;
        margin-bottom: 8px;
    }

    .activity-name:hover {
        color: #0d6efd;
    }

    /* Style cho phần hiển thị ngày giờ */
    .activity-time-block {
        font-size: 0.9rem;
        color: #64748b;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .time-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .time-label {
        font-size: 0.8rem;
        color: #94a3b8;
        width: 30px; /* Cố định chiều rộng để thẳng hàng */
    }

    /* Badge trạng thái */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
        display: inline-block;
    }
    .st-registered { background: #e0f2fe; color: #0284c7; }
    .st-attended { background: #dcfce7; color: #16a34a; }
    .st-absent { background: #fee2e2; color: #dc2626; }

    /* Badge minh chứng */
    .proof-badge {
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .proof-done { color: #10b981; }
    .proof-pending { color: #f59e0b; }
</style>

<div class="content-section" style="background-color: #f1f5f9; min-height: 80vh; padding-top: 20px;">
    <div class="container">
        <a href="index.php" style="color: #64748b; text-decoration: none; margin-bottom: 20px; display: inline-block;">
            <i class="fa-solid fa-arrow-left"></i> Về trang chủ
        </a>

        <div class="list-container">
            <h2 class="page-title"><i class="fa-solid fa-clipboard-list"></i> Hoạt động đã đăng ký</h2>

            <?php if ($result->num_rows > 0): ?>
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th width="45%">Thông tin hoạt động</th>
                            <th width="25%">Minh chứng</th>
                            <th width="30%" class="text-center">Điểm danh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()):

                            // 1. Xử lý trạng thái điểm danh
                            $status_code = isset($row['trang_thai']) ? $row['trang_thai'] : 0;
                            $status_html = '';
                            switch ($status_code) {
                                case 1:
                                    $status_html = '<span class="status-badge st-attended"><i class="fa-solid fa-check"></i> Đã tham gia</span>';
                                    break;
                                case 2:
                                    $status_html = '<span class="status-badge st-absent"><i class="fa-solid fa-xmark"></i> Vắng</span>';
                                    break;
                                default:
                                    $status_html = '<span class="status-badge st-registered"><i class="fa-solid fa-pen"></i> Đã đăng ký</span>';
                                    break;
                            }

                            // 2. Xử lý thời gian & Minh chứng
                            $has_proof = !empty($row['minh_chung']);
                            
                            $t_start = strtotime($row['ngay_bat_dau']);
                            // Nếu không có ngày kết thúc thì lấy ngày bắt đầu
                            $t_end = !empty($row['ngay_ket_thuc']) ? strtotime($row['ngay_ket_thuc']) : $t_start;
                            
                            // Logic Hết hạn: Bây giờ > Ngày kết thúc (Chính xác hơn là ngày bắt đầu)
                            $is_expired = time() > $t_end;
                        ?>
                            <tr>
                                <td>
                                    <a href="chitiethoatdong.php?id=<?php echo $row['hoatdong_id']; ?>" class="activity-name">
                                        <?php echo htmlspecialchars($row['ten_hoat_dong']); ?>
                                    </a>
                                    
                                    <div class="activity-time-block">
                                        <div class="time-row">
                                            <span class="time-label">Từ:</span>
                                            <i class="fa-regular fa-clock" style="font-size:0.8rem; margin-right:4px;"></i>
                                            <?php echo date("H:i - d/m/Y", $t_start); ?>
                                        </div>
                                        <div class="time-row">
                                            <span class="time-label">Đến:</span>
                                            <i class="fa-regular fa-clock" style="font-size:0.8rem; margin-right:4px;"></i>
                                            <?php echo date("H:i - d/m/Y", $t_end); ?>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <?php if ($has_proof): ?>
                                        <div class="proof-badge proof-done">
                                            <i class="fa-solid fa-file-circle-check"></i>
                                            <span>Đã nộp</span>
                                        </div>
                                        <small style="color: #64748b; font-size: 0.8rem;">
                                            <a href="chitiethoatdong.php?id=<?php echo $row['hoatdong_id']; ?>"
                                                style="text-decoration:none; color:inherit;">(Xem lại)</a>
                                        </small>

                                    <?php else: ?>
                                        <div class="proof-badge proof-pending">
                                            <i class="fa-solid fa-circle-exclamation"></i>
                                            <span>Chưa nộp</span>
                                        </div>

                                        <small style="font-size: 0.8rem;">
                                            <?php if (!$is_expired): ?>
                                                <a href="chitiethoatdong.php?id=<?php echo $row['hoatdong_id']; ?>"
                                                    style="color: #0d6efd; text-decoration:none;">
                                                    <i class="fa-solid fa-upload"></i> Nộp ngay
                                                </a>
                                            <?php else: ?>
                                                <span style="color: #94a3b8;">(Đã kết thúc)</span>
                                            <?php endif; ?>
                                        </small>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <?php echo $status_html; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="Empty"
                        style="width: 80px; opacity: 0.5; margin-bottom: 15px;">
                    <p class="text-muted">Bạn chưa đăng ký hoạt động nào.</p>
                    <a href="index.php" class="btn btn-primary"
                        style="background:#0d6efd; color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none;">Tìm
                        hoạt động ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require("phancuoi.php");
?>