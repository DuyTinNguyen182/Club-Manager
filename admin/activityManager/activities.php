<?php
$path_to_admin = '../';
include('../includes/header.php');
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
                    <th>Tên hoạt động</th>
                    <th>Thời gian & Địa điểm</th>
                    <th>Mô tả</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center" style="width: 120px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Sắp xếp theo ngày bắt đầu giảm dần (mới nhất lên đầu)
                $sql = "SELECT * FROM tblhoatdong ORDER BY ngay_bat_dau DESC";
                $result = $conn->query($sql);

                $stt = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td class="text-center fw-bold"><?= $stt++; ?></td>
                            <td>
                                <div class="fw-bold text-primary"><?= $row['ten_hoat_dong'] ?></div>
                            </td>
                            <td>
                                <div><i class='bx bx-calendar'></i> <?= date('d/m/Y H:i', strtotime($row['ngay_bat_dau'])) ?></div>
                                <small class="text-muted"><i class='bx bx-map'></i> <?= $row['dia_diem'] ?></small>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;">
                                    <?= htmlspecialchars($row['mo_ta_hoat_dong']) ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if ($row['trang_thai'] == 0): ?>
                                    <span class="badge bg-success">Sắp diễn ra</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Đã kết thúc</span>
                                <?php endif; ?>
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
                    echo "<tr><td colspan='6' class='text-center text-muted py-4'>Chưa có hoạt động nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>