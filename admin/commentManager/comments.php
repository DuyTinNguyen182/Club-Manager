<?php
$path_to_admin = '../';
include('../includes/header.php');
?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Danh sách Bình luận</h5>
    </div>
    <div class="card-body">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px;">STT</th>
                    <th>Người bình luận</th>
                    <th style="width: 30%;">Nội dung bình luận</th>
                    <th style="width: 20%;">Thuộc bài viết</th>
                    <th class="text-center">Ngày tạo</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center" style="width: 100px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // JOIN bảng bình luận với bảng bài viết để lấy thông tin bài viết gốc
                $sql = "SELECT bl.*, bv.Noidung as BaivietGoc 
                        FROM tblbinhluan bl 
                        LEFT JOIN tblbaiviet bv ON bl.Mabaiviet = bv.Mabaiviet 
                        ORDER BY bl.Ngaytao DESC";
                $result = $conn->query($sql);

                $stt = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td class="text-center fw-bold"><?= $stt++; ?></td>
                            <td>
                                <div class="fw-bold text-primary">@<?= $row['Username'] ?></div>
                            </td>
                            <td>
                                <div class="text-break"><?= htmlspecialchars($row['Noidung']) ?></div>
                            </td>
                            <td>
                                <small class="text-muted fst-italic">
                                    <?php 
                                        $post_content = strip_tags($row['BaivietGoc']);
                                        if (strlen($post_content) > 50) {
                                            echo substr($post_content, 0, 50) . "...";
                                        } else {
                                            echo $post_content;
                                        }
                                        if (empty($post_content)) echo "<span class='text-danger'>(Bài viết đã bị xóa)</span>";
                                    ?>
                                </small>
                            </td>
                            <td class="text-center">
                                <small><?= date('d/m/Y', strtotime($row['Ngaytao'])) ?></small>
                            </td>
                            <td class="text-center">
                                <?php if ($row['Trangthai'] == 1): ?>
                                    <span class="badge bg-success">Hiển thị</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Đã ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['Mabinhluan'] ?>" class="btn btn-warning btn-sm" title="Duyệt/Sửa">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <a href="delete.php?id=<?= $row['Mabinhluan'] ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')" 
                                   title="Xóa">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center text-muted py-4'>Chưa có bình luận nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>