<?php
$path_to_admin = '../';
include('../includes/header.php');
?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Danh sách Bài viết</h5>
        <a href="add.php" class="btn btn-primary btn-sm">
            <i class='bx bx-plus'></i> Viết bài mới
        </a>
    </div>
    <div class="card-body">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px;">STT</th>
                    <th style="width: 80px;">Hình ảnh</th>
                    <th>Nội dung tóm tắt</th>
                    <th>Chủ đề</th>
                    <th>Tác giả</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center" style="width: 120px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // JOIN bảng bài viết với bảng chủ đề để lấy tên chủ đề
                $sql = "SELECT b.*, c.Tenchude 
                        FROM tblbaiviet b 
                        LEFT JOIN tblchude c ON b.Machude = c.Machude 
                        ORDER BY b.Mabaiviet DESC";
                $result = $conn->query($sql);

                $stt = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Xử lý ảnh
                        $img_path = "../../uploads/" . $row['Teptin'];
                        $img_display = file_exists($img_path) && !empty($row['Teptin']) ? $img_path : "../../images/no-image.png";
                        ?>
                        <tr>
                            <td class="text-center fw-bold"><?= $stt++; ?></td>
                            <td>
                                <img src="<?= $img_display ?>" class="rounded border" width="60" height="60" style="object-fit: cover;">
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 250px;">
                                    <?= htmlspecialchars($row['Noidung']) ?>
                                </div>
                                <small class="text-muted"><i class='bx bx-time'></i> <?= date('d/m/Y', strtotime($row['Ngaytao'])) ?></small>
                            </td>
                            <td><span class="badge bg-info text-dark"><?= $row['Tenchude'] ?? 'Không xác định' ?></span></td>
                            <td><small class="fw-bold">@<?= $row['Username'] ?></small></td>
                            <td class="text-center">
                                <?php if ($row['Trangthai'] == 1): ?>
                                    <span class="badge bg-success">Đã duyệt</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['Mabaiviet'] ?>" class="btn btn-warning btn-sm" title="Sửa"><i class='bx bx-edit-alt'></i></a>
                                <a href="delete.php?id=<?= $row['Mabaiviet'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa bài viết này?')" title="Xóa"><i class='bx bx-trash'></i></a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center text-muted py-4'>Chưa có bài viết nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>