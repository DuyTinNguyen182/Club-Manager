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
                    <th style="width: 100px;">Hình ảnh</th>
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
                        $uploadDir = "../../uploads/";
                        $defaultImg = "../../uploads/no-image.jpg"; 
                        $img_display = $defaultImg; 
                
                        if (!empty($row['Teptin'])) {
                            $checkPath = $uploadDir . $row['Teptin'];
                            if (file_exists($checkPath)) {
                                $img_display = $checkPath;
                            }
                        }
                        ?>
                        <tr>
                            <td class="text-center fw-bold align-middle"><?= $stt++; ?></td>

                            <td class="align-middle text-center">
                                <img src="<?= $img_display ?>" class="rounded border shadow-sm" width="60" height="60"
                                    alt="Ảnh bài viết" style="object-fit: cover;">
                            </td>

                            <td class="align-middle">
                                <div class="text-truncate fw-bold text-primary" style="max-width: 250px; cursor: help;"
                                    title="<?= htmlspecialchars($row['Noidung']) ?>">
                                    <?= htmlspecialchars($row['Noidung']) ?>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class='bx bx-time-five'></i> <?= date('H:i d/m/Y', strtotime($row['Ngaytao'])) ?>
                                </small>
                            </td>

                            <td class="align-middle">
                                <span class="badge bg-info text-dark">
                                    <?= !empty($row['Tenchude']) ? $row['Tenchude'] : 'Chưa phân loại' ?>
                                </span>
                            </td>

                            <td class="align-middle">
                                <small class="fw-bold text-secondary">
                                    <i class='bx bx-user'></i> <?= $row['Username'] ?>
                                </small>
                            </td>

                            <td class="text-center align-middle">
                                <?php if ($row['Trangthai'] == 1): ?>
                                    <span class="badge bg-success"><i class='bx bx-check-circle'></i> Đã duyệt</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><i class='bx bx-hourglass'></i> Chờ duyệt</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center align-middle">
                                <div class="btn-group" role="group">
                                    <a href="edit.php?id=<?= $row['Mabaiviet'] ?>" class="btn btn-outline-warning btn-sm"
                                        title="Sửa">
                                        <i class='bx bx-edit-alt'></i>
                                    </a>
                                    <a href="delete.php?id=<?= $row['Mabaiviet'] ?>" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?')" title="Xóa">
                                        <i class='bx bx-trash'></i>
                                    </a>
                                </div>
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