<?php
$path_to_admin = '../';
require_once('../config.php');
include('../includes/header.php');

if (isset($_GET['action']) && $_GET['action'] == 'toggle' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_update = "UPDATE tblslideshow SET trang_thai = 1 - trang_thai WHERE ma_slide = $id";
    if ($conn->query($sql_update)) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Quản lý Slideshow</h5>
        <a href="add.php" class="btn btn-primary btn-sm">
            <i class='bx bx-plus'></i> Thêm Slide mới
        </a>
    </div>
    <div class="card-body">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px;">STT</th>
                    <th class="text-center" style="width: 120px;">Hình ảnh</th>
                    <th>Tiêu đề & Mô tả</th>
                    <th class="text-center" style="width: 100px;">Trạng thái</th>
                    <th class="text-center" style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tblslideshow ORDER BY ma_slide DESC";
                $result = $conn->query($sql);
                $stt = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $img_path = '../../' . $row['hinh_anh'];
                ?>
                        <tr>
                            <td class="text-center fw-bold"><?= $stt++; ?></td>
                            <td class="text-center">
                                <?php if (!empty($row['hinh_anh']) && file_exists($img_path)): ?>
                                    <img src="<?= $img_path ?>" alt="Slide" style="width: 100px; height: 60px; object-fit: cover; border-radius: 4px;">
                                <?php else: ?>
                                    <span class="text-muted small">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-primary"><?= $row['tieu_de'] ?></div>
                                <small class="text-muted"><?= $row['mo_ta'] ?></small>
                            </td>
                            <td class="text-center">
                                <a href="?action=toggle&id=<?= $row['ma_slide'] ?>" style="text-decoration: none;">
                                    <?php if ($row['trang_thai'] == 1): ?>
                                        <span class="badge bg-success" title="Đang hiện -> Nhấn để ẩn">Hiển thị</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary" title="Đang ẩn -> Nhấn để hiện">Ẩn</span>
                                    <?php endif; ?>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['ma_slide'] ?>" class="btn btn-warning btn-sm" title="Sửa">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <a href="delete.php?id=<?= $row['ma_slide'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc muốn xóa slide này?')" title="Xóa">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center text-muted py-4'>Chưa có slide nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>