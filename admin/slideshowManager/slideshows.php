<?php
$path_to_admin = '../';
require_once('../config.php');
include('../includes/header.php');

if (isset($_GET['action']) && $_GET['action'] == 'toggle' && isset($_GET['id'])) {
    $id = (int) $_GET['id']; // Ép kiểu int để an toàn hơn
    $sql_update = "UPDATE tblslideshow SET trang_thai = 1 - trang_thai WHERE ma_slide = $id";
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
            <h5 class="mb-0 fw-bold text-primary"><i class='bx bx-images'></i> Quản lý Slideshow</h5>
            <a href="add.php" class="btn btn-primary btn-sm">
                <i class='bx bx-plus'></i> Thêm Slide mới
            </a>
        </div>

        <div class="card-body p-0 p-md-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th class="text-center" style="width: 150px;">Hình ảnh</th>
                            <th style="min-width: 200px;">Thông tin</th>
                            <th class="text-center" style="width: 100px;">Trạng thái</th>
                            <th class="text-center" style="width: 120px;">Hành động</th>
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
                                            <img src="<?= $img_path ?>" alt="Slide" class="img-fluid rounded border"
                                                style="max-width: 120px; height: auto; object-fit: cover;">
                                        <?php else: ?>
                                            <span class="badge bg-light text-secondary border">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark text-wrap"><?= htmlspecialchars($row['tieu_de']) ?></div>
                                        <small
                                            class="text-muted text-wrap d-block mt-1"><?= htmlspecialchars($row['mo_ta']) ?></small>
                                    </td>
                                    <td class="text-center">
                                        <a href="?action=toggle&id=<?= $row['ma_slide'] ?>" style="text-decoration: none;"
                                            onclick="return confirm('Bạn có muốn đổi trạng thái slide này?')">
                                            <?php if ($row['trang_thai'] == 1): ?>
                                                <span class="badge bg-success"><i class='bx bx-show'></i> Hiển thị</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><i class='bx bx-hide'></i> Đang ẩn</span>
                                            <?php endif; ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="edit.php?id=<?= $row['ma_slide'] ?>" class="btn btn-warning btn-sm"
                                                title="Sửa">
                                                <i class='bx bx-edit-alt'></i>
                                            </a>
                                            <a href="delete.php?id=<?= $row['ma_slide'] ?>" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc muốn xóa slide này?')" title="Xóa">
                                                <i class='bx bx-trash'></i>
                                            </a>
                                        </div>
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
    </div>
</div>

<?php include('../includes/footer.php'); ?>