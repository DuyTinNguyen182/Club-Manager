<?php
require("phandau.php");

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Vui lòng đăng nhập để xem danh sách hoạt động.'); window.location.href='login.php';</script>";
    exit();
}

$username = $_SESSION['username'];

// 2. Truy vấn danh sách hoạt động user đã đăng ký
// JOIN giữa bảng đăng ký và bảng hoạt động để lấy tên và ngày
// Giả định: Bảng tbldangkyhoatdong có cột 'trang_thai' (0: Đã đăng ký, 1: Đã tham gia, 2: Vắng)
// Nếu chưa có cột này, SQL vẫn chạy nhưng bạn cần vào database thêm cột hoặc mặc định hiển thị "Đã đăng ký"

$sql = "SELECT dk.trang_thai, hd.hoatdong_id, hd.ten_hoat_dong, hd.ngay_bat_dau 
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
    /* Sử dụng lại style base giống file chitiethoatdong.php */
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

    /* Style cho bảng */
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
        font-size: 0.95rem;
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
        margin-bottom: 5px;
    }

    .activity-name:hover {
        color: #0d6efd;
    }

    .activity-date {
        font-size: 0.9rem;
        color: #64748b;
    }

    /* Badge trạng thái */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .st-registered {
        background: #e0f2fe;
        color: #0284c7;
    }

    /* Xanh dương */
    .st-attended {
        background: #dcfce7;
        color: #16a34a;
    }

    /* Xanh lá */
    .st-absent {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Đỏ */
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
                            <th width="50%">Thông tin hoạt động</th>
                            <th width="25%" class="text-center">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()):

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
                        ?>
                            <tr>
                                <td>
                                    <a href="chitiethoatdong.php?id=<?php echo $row['hoatdong_id']; ?>" class="activity-name">
                                        <?php echo htmlspecialchars($row['ten_hoat_dong']); ?>
                                    </a>
                                    <span class="activity-date">
                                        <i class="fa-regular fa-clock"></i>
                                        <?php echo date("H:i d/m/Y", strtotime($row['ngay_bat_dau'])); ?>
                                    </span>
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
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="Empty" style="width: 80px; opacity: 0.5; margin-bottom: 15px;">
                    <p class="text-muted">Bạn chưa đăng ký hoạt động nào.</p>
                    <a href="index.php" class="btn btn-primary">Tìm hoạt động ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require("phancuoi.php");
?>