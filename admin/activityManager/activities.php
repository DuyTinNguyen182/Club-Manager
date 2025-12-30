<?php
$path_to_admin = '../';
include('../includes/header.php');

// 1. Cấu hình múi giờ để so sánh chính xác
date_default_timezone_set('Asia/Ho_Chi_Minh');
$current_time = date('Y-m-d H:i:s');

// 2. Cập nhật trạng thái tự động (Tuỳ chọn: Nếu bạn dùng cột trang_thai trong DB)
// Nếu đã qua ngày kết thúc -> Set thành 1 (Đã kết thúc)
// Lưu ý: Logic hiển thị bên dưới sẽ tính toán lại chính xác hơn theo thời gian thực
$sql_auto_update = "UPDATE tblhoatdong SET trang_thai = 1 WHERE ngay_ket_thuc < '$current_time' AND trang_thai = 0";
$conn->query($sql_auto_update);
?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Danh sách Hoạt động CLB</h5>
        <a href="add.php" class="btn btn-primary btn-sm">
            <i class='bx bx-plus'></i> Thêm hoạt động
        </a>
    </div>
    <div class="card-body">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px;">STT</th>
                    <th style="width: 25%;">Tên hoạt động</th>
                    <th style="width: 25%;">Thời gian</th>
                    <th style="width: 15%;">Địa điểm</th>
                    <th>Mô tả</th>
                    <th class="text-center" style="width: 100px;">Trạng thái</th>
                    <th class="text-center" style="width: 100px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Sắp xếp: Mới nhất lên đầu
                $sql = "SELECT * FROM tblhoatdong ORDER BY ngay_bat_dau DESC";
                $result = $conn->query($sql);

                $stt = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // --- XỬ LÝ THỜI GIAN ---
                        $t_start = strtotime($row['ngay_bat_dau']);
                        // Nếu không có ngày kết thúc thì lấy bằng ngày bắt đầu
                        $t_end = !empty($row['ngay_ket_thuc']) ? strtotime($row['ngay_ket_thuc']) : $t_start;
                        $now = time();

                        // --- XÁC ĐỊNH TRẠNG THÁI HIỂN THỊ ---
                        if ($now < $t_start) {
                            $badge = '<span class="badge bg-success">Sắp diễn ra</span>'; // Xanh lá
                        } elseif ($now >= $t_start && $now <= $t_end) {
                            $badge = '<span class="badge bg-warning text-dark">Đang diễn ra</span>'; // Cam/Vàng
                        } else {
                            $badge = '<span class="badge bg-secondary">Đã kết thúc</span>'; // Xám
                        }
                ?>
                        <tr>
                            <td class="text-center fw-bold"><?= $stt++; ?></td>
                            
                            <td>
                                <div class="fw-bold text-primary"><?= $row['ten_hoat_dong'] ?></div>
                            </td>

                            <td>
                                <div class="d-flex flex-column" style="font-size: 0.9rem;">
                                    <span class="text-muted mb-1">
                                        <i class='bx bx-time'></i> Từ: 
                                        <span class="text-dark fw-bold"><?= date('H:i d/m/Y', $t_start) ?></span>
                                    </span>
                                    <span class="text-muted">
                                        <i class='bx bx-time-five'></i> Đến: 
                                        <span class="text-dark fw-bold"><?= date('H:i d/m/Y', $t_end) ?></span>
                                    </span>
                                </div>
                            </td>

                            <td>
                                <small class="text-dark"><i class='bx bx-map'></i> <?= $row['dia_diem'] ?></small>
                            </td>

                            <td>
                                <div class="text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($row['mo_ta_hoat_dong']) ?>">
                                    <?= htmlspecialchars($row['mo_ta_hoat_dong']) ?>
                                </div>
                            </td>

                            <td class="text-center">
                                <?= $badge ?>
                            </td>

                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['hoatdong_id'] ?>" class="btn btn-warning btn-sm" title="Sửa">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <a href="delete.php?id=<?= $row['hoatdong_id'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc muốn xóa hoạt động này?')"
                                    title="Xóa">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center text-muted py-4'>Chưa có hoạt động nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>