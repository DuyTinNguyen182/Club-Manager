<?php
$path_to_admin = '../';
include('../includes/header.php');
?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-primary"><i class='bx bx-list-check'></i> Điểm danh Hoạt động (Đã kết thúc)</h5>
    </div>
    <div class="card-body">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px;">STT</th>
                    <th>Tên hoạt động</th>
                    <th>Thời gian & Địa điểm</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center" style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Chỉ lấy các hoạt động ĐÃ KẾT THÚC (trang_thai = 1)
                $sql = "SELECT * FROM tblhoatdong WHERE trang_thai = 1 ORDER BY ngay_bat_dau DESC";
                $result = $conn->query($sql);

                $stt = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <tr>
                            <td class="text-center fw-bold"><?= $stt++; ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= $row['ten_hoat_dong'] ?></div>
                            </td>
                            <td>
                                <div><i class='bx bx-calendar'></i> <?= date('d/m/Y H:i', strtotime($row['ngay_bat_dau'])) ?></div>
                                <small class="text-muted"><i class='bx bx-map'></i> <?= $row['dia_diem'] ?></small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">Đã kết thúc</span>
                            </td>
                            <td class="text-center">
                                <a href="take_attendance.php?id=<?= $row['hoatdong_id'] ?>" class="btn btn-primary btn-sm" title="Ghi danh thành viên">
                                    <i class='bx bx-user-check'></i> Ghi danh
                                </a>
                                <a href="export_word.php?id=<?= $row['hoatdong_id'] ?>" class="btn btn-success btn-sm" title="Xuất file Word">
                                    <i class='bx bxs-file-doc'></i>
                                </a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center text-muted py-4'>Chưa có hoạt động nào đã kết thúc để điểm danh.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>