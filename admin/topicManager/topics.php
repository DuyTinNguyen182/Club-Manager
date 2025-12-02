<?php
$path_to_admin = '../';
include('../includes/header.php');
?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Danh sách Chủ đề</h5>
        <a href="add.php" class="btn btn-primary btn-sm">
            <i class='bx bx-plus'></i> Thêm chủ đề
        </a>
    </div>
    <div class="card-body">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px;">STT</th>
                    <th class="text-center" style="width: 80px;">ID</th>
                    <th>Tên chủ đề</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center" style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Lấy danh sách chủ đề
                $sql = "SELECT * FROM tblchude ORDER BY Machude DESC";
                $result = $conn->query($sql);

                $stt = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td class="text-center fw-bold"><?= $stt++; ?></td>
                            <td class="text-center text-muted">#<?= $row['Machude'] ?></td>
                            <td class="fw-bold text-primary"><?= $row['Tenchude'] ?></td>
                            <td class="text-center">
                                <?php if ($row['Trangthai'] == 1): ?>
                                    <span class="badge bg-secondary">Ẩn</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Hiển thị</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['Machude'] ?>" class="btn btn-warning btn-sm" title="Sửa">
                                    <i class='bx bx-edit-alt'></i>
                                </a>

                                <a href="delete.php?id=<?= $row['Machude'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc muốn xóa chủ đề: <?= $row['Tenchude'] ?>?')"
                                    title="Xóa">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center text-muted py-4'>Chưa có chủ đề nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>