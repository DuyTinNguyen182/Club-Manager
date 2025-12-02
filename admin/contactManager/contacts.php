<?php
$path_to_admin = '../';
include('../includes/header.php');
?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Danh sách Liên hệ & Góp ý</h5>
    </div>
    <div class="card-body">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px;">STT</th>
                    <th style="width: 200px;">Người gửi</th>
                    <th>Nội dung tóm tắt</th>
                    <th class="text-center" style="width: 150px;">Ngày gửi</th>
                    <th class="text-center" style="width: 150px;">Trạng thái</th>
                    <th class="text-center" style="width: 100px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Sắp xếp thư mới nhất lên đầu
                $sql = "SELECT * FROM tblcontact ORDER BY Ngaygui DESC";
                $result = $conn->query($sql);

                $stt = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td class="text-center fw-bold"><?= $stt++; ?></td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($row['Tennguoigui']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($row['Email']) ?></small>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 300px;">
                                    <?= htmlspecialchars($row['Noidung']) ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <small><?= date('d/m/Y H:i', strtotime($row['Ngaygui'])) ?></small>
                            </td>
                            
                            <td class="text-center">
                                <a href="toggle_status.php?id=<?= $row['id'] ?>&status=<?= $row['Trangthai'] ?>" 
                                   class="text-decoration-none"
                                   onclick="return confirm('Bạn muốn đổi trạng thái xử lý của thư này?')">
                                    <?php if ($row['Trangthai'] == 1): ?>
                                        <span class="badge bg-success p-2 w-100"><i class='bx bx-check-double'></i> Đã phản hồi</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark p-2 w-100"><i class='bx bx-time'></i> Chờ xử lý</span>
                                    <?php endif; ?>
                                </a>
                            </td>

                            <td class="text-center">
                                <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm text-white" title="Xem chi tiết">
                                    <i class='bx bx-show'></i>
                                </a>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Bạn có chắc muốn xóa liên hệ này?')" 
                                   title="Xóa">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center text-muted py-4'>Hộp thư trống.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>