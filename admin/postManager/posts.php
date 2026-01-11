<?php
$path_to_admin = '../';
include('../includes/header.php');
?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
            <h5 class="mb-0 fw-bold text-primary"><i class='bx bx-news'></i> Danh sách Bài viết</h5>
            <a href="add.php" class="btn btn-primary btn-sm">
                <i class='bx bx-plus'></i> Viết bài mới
            </a>
        </div>

        <div class="card-body p-0 p-md-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th class="text-center" style="width: 80px;">Hình ảnh</th>
                            <th style="min-width: 200px;">Nội dung tóm tắt</th>
                            <th>Chủ đề</th>
                            <th>Tác giả</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center" style="width: 120px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT b.*, c.ten_chu_de 
                                FROM tblbaiviet b 
                                LEFT JOIN tblchude c ON b.ma_chu_de = c.ma_chu_de 
                                ORDER BY b.ma_bai_viet DESC";
                        $result = $conn->query($sql);

                        $stt = 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $uploadDir = "../../uploads/";
                                $defaultImg = "../../uploads/no-image.jpg"; // Bạn nên có file này làm mặc định
                                $img_display = $defaultImg;

                                if (!empty($row['tep_tin'])) {
                                    $checkPath = $uploadDir . $row['tep_tin'];
                                    if (file_exists($checkPath)) {
                                        $img_display = $checkPath;
                                    }
                                }
                                ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $stt++; ?></td>

                                    <td class="text-center">
                                        <img src="<?= $img_display ?>" class="rounded border shadow-sm" width="60" height="60"
                                            alt="Ảnh bài viết" style="object-fit: cover;">
                                    </td>

                                    <td>
                                        <div class="text-wrap" style="max-width: 300px;">
                                            <span class="fw-bold text-dark">
                                                <?= htmlspecialchars(mb_substr($row['noi_dung'], 0, 100)) ?>...
                                            </span>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            <i class='bx bx-time-five'></i>
                                            <?= date('H:i d/m/Y', strtotime($row['ngay_tao'])) ?>
                                        </small>
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= !empty($row['ten_chu_de']) ? htmlspecialchars($row['ten_chu_de']) : 'Chưa phân loại' ?>
                                        </span>
                                    </td>

                                    <td>
                                        <small class="fw-bold text-secondary">
                                            <i class='bx bx-user'></i> <?= htmlspecialchars($row['username']) ?>
                                        </small>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($row['trang_thai'] == 1): ?>
                                            <span class="badge bg-success"><i class='bx bx-check-circle'></i> Đã duyệt</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark"><i class='bx bx-hourglass'></i> Chờ
                                                duyệt</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="edit.php?id=<?= $row['ma_bai_viet'] ?>" class="btn btn-warning btn-sm"
                                                title="Sửa">
                                                <i class='bx bx-edit-alt'></i>
                                            </a>
                                            <a href="delete.php?id=<?= $row['ma_bai_viet'] ?>" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?')"
                                                title="Xóa">
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
    </div>
</div>

<?php include('../includes/footer.php'); ?>