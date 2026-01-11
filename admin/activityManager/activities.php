<?php
$path_to_admin = '../';
include('../includes/header.php');

date_default_timezone_set('Asia/Ho_Chi_Minh');
$current_time = date('Y-m-d H:i:s');

// Tự động cập nhật trạng thái nếu đã qua ngày kết thúc
$sql_auto_update = "UPDATE tblhoatdong SET trang_thai = 1 WHERE ngay_ket_thuc < '$current_time' AND trang_thai = 0";
$conn->query($sql_auto_update);
?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
            <h5 class="mb-0 fw-bold text-primary"><i class='bx bx-calendar-event'></i> Danh sách Hoạt động CLB</h5>
            <a href="add.php" class="btn btn-primary btn-sm">
                <i class='bx bx-plus'></i> Thêm hoạt động
            </a>
        </div>

        <div class="card-body p-0 p-md-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th style="min-width: 200px;">Tên hoạt động</th>
                            <th style="min-width: 220px;">Thời gian</th>
                            <th style="min-width: 150px;">Địa điểm</th>
                            <th style="min-width: 200px;">Mô tả</th>
                            <th class="text-center" style="width: 120px;">Trạng thái</th>
                            <th class="text-center" style="width: 120px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM tblhoatdong ORDER BY ngay_bat_dau DESC";
                        $result = $conn->query($sql);

                        $stt = 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $t_start = strtotime($row['ngay_bat_dau']);
                                $t_end = !empty($row['ngay_ket_thuc']) ? strtotime($row['ngay_ket_thuc']) : $t_start;
                                $now = time();

                                if ($now < $t_start) {
                                    $badge = '<span class="badge bg-success">Sắp diễn ra</span>';
                                } elseif ($now >= $t_start && $now <= $t_end) {
                                    $badge = '<span class="badge bg-warning text-dark">Đang diễn ra</span>';
                                } else {
                                    $badge = '<span class="badge bg-secondary">Đã kết thúc</span>';
                                }
                                ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $stt++; ?></td>

                                    <td>
                                        <div class="fw-bold text-dark text-wrap"><?= htmlspecialchars($row['ten_hoat_dong']) ?>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column small">
                                            <span class="text-muted mb-1">
                                                <i class='bx bx-play-circle'></i> Bắt đầu:
                                                <span class="text-dark fw-bold"><?= date('H:i d/m/Y', $t_start) ?></span>
                                            </span>
                                            <span class="text-muted">
                                                <i class='bx bx-stop-circle'></i> Kết thúc:
                                                <span class="text-dark fw-bold"><?= date('H:i d/m/Y', $t_end) ?></span>
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <small class="text-dark text-wrap d-block">
                                            <i class='bx bx-map'></i> <?= htmlspecialchars($row['dia_diem']) ?>
                                        </small>
                                    </td>

                                    <td>
                                        <div class="text-wrap" style="max-width: 250px; font-size: 0.9em;">
                                            <?= htmlspecialchars(mb_substr($row['mo_ta_hoat_dong'], 0, 50)) ?>...
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <?= $badge ?>
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="edit.php?id=<?= $row['ma_hoat_dong'] ?>" class="btn btn-warning btn-sm"
                                                title="Sửa">
                                                <i class='bx bx-edit-alt'></i>
                                            </a>
                                            <a href="delete.php?id=<?= $row['ma_hoat_dong'] ?>" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc muốn xóa hoạt động này?')" title="Xóa">
                                                <i class='bx bx-trash'></i>
                                            </a>
                                        </div>
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
    </div>
</div>

<?php include('../includes/footer.php'); ?>