<?php
$path_to_admin = '../';
include('../includes/header.php');
?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
            <h5 class="mb-0 fw-bold text-primary"><i class='bx bx-list-check'></i> Quản lý Điểm danh</h5>
        </div>

        <div class="card-body p-0 p-md-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th>Tên hoạt động</th>
                            <th>Thời gian & Địa điểm</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center" style="width: 180px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Lấy các hoạt động (Có thể lọc trang_thai = 1 hoặc đã kết thúc tùy logic của bạn)
                        $sql = "SELECT * FROM tblhoatdong ORDER BY ngay_bat_dau DESC";
                        $result = $conn->query($sql);

                        $stt = 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $is_finished = (strtotime($row['ngay_ket_thuc']) < time());
                                ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $stt++; ?></td>
                                    <td>
                                        <div class="fw-bold text-dark text-wrap" style="min-width: 200px;">
                                            <?= htmlspecialchars($row['ten_hoat_dong']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div><i class='bx bx-calendar'></i>
                                            <?= date('H:i d/m/Y', strtotime($row['ngay_bat_dau'])) ?></div>
                                        <small class="text-muted text-wrap" style="max-width: 250px; display:block;">
                                            <i class='bx bx-map'></i> <?= htmlspecialchars($row['dia_diem']) ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($is_finished): ?>
                                            <span class="badge bg-secondary">Đã kết thúc</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Đang diễn ra</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="take_attendance.php?id=<?= $row['ma_hoat_dong'] ?>"
                                                class="btn btn-primary btn-sm d-flex align-items-center gap-1" title="Ghi danh">
                                                <i class='bx bx-user-check'></i> <span class="d-none d-md-inline">Điểm
                                                    danh</span>
                                            </a>
                                            <a href="export_word.php?id=<?= $row['ma_hoat_dong'] ?>"
                                                class="btn btn-success btn-sm d-flex align-items-center gap-1"
                                                title="Xuất file Word">
                                                <i class='bx bxs-file-doc'></i> <span class="d-none d-md-inline">Xuất DS</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center text-muted py-4'>Chưa có hoạt động nào.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>