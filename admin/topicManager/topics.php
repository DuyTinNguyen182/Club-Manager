<?php
$path_to_admin = '../';
require_once('../config.php');
include('../includes/header.php');

// Xử lý Toggle trạng thái nhanh
if (isset($_GET['action']) && $_GET['action'] == 'toggle' && isset($_GET['id'])) {
    $id = $_GET['id'];
    // Ép kiểu số nguyên để bảo mật cơ bản nếu không dùng prepare statement ở đây
    $id = (int) $id;
    $sql_update = "UPDATE tblchude SET trang_thai = 1 - trang_thai WHERE ma_chu_de = $id";

    if ($conn->query($sql_update)) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
            <h5 class="mb-0 fw-bold text-primary"><i class='bx bx-category'></i> Danh sách Chủ đề</h5>
            <a href="add.php" class="btn btn-primary btn-sm">
                <i class='bx bx-plus'></i> Thêm chủ đề
            </a>
        </div>

        <div class="card-body p-0 p-md-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th>Tên chủ đề</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM tblchude ORDER BY ma_chu_de DESC";
                        $result = $conn->query($sql);

                        $stt = 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $stt++; ?></td>
                                    <td class="fw-bold text-dark"><?= htmlspecialchars($row['ten_chu_de']) ?></td>

                                    <td class="text-center">
                                        <a href="?action=toggle&id=<?= $row['ma_chu_de'] ?>" style="text-decoration: none;"
                                            onclick="return confirm('Bạn có muốn đổi trạng thái chủ đề này?')">
                                            <?php if ($row['trang_thai'] == 1): ?>
                                                <span class="badge bg-success"><i class='bx bx-show'></i> Hiển thị</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><i class='bx bx-hide'></i> Đang ẩn</span>
                                            <?php endif; ?>
                                        </a>
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="edit.php?id=<?= $row['ma_chu_de'] ?>" class="btn btn-warning btn-sm"
                                                title="Sửa">
                                                <i class='bx bx-edit-alt'></i>
                                            </a>

                                            <a href="delete.php?id=<?= $row['ma_chu_de'] ?>" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc muốn xóa chủ đề: <?= htmlspecialchars($row['ten_chu_de']) ?>?')"
                                                title="Xóa">
                                                <i class='bx bx-trash'></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center text-muted py-4'>Chưa có chủ đề nào.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>