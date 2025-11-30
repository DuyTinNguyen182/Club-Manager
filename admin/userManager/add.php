<?php

$path_to_admin = '../'; 
include('../includes/header.php'); 
?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Danh sách thành viên</h5>
        <a href="add.php" class="btn btn-primary btn-sm">
            <i class='bx bx-plus'></i> Thêm thành viên
        </a>
    </div>
    <div class="card-body">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px;">STT</th>
                    <th style="width: 80px;">Avatar</th>
                    <th>Thông tin cá nhân</th>
                    <th>Email</th>
                    <th class="text-center">Vai trò</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center" style="width: 120px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tbluser ORDER BY role DESC";
                $result = $conn->query($sql);

                $stt = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Xử lý đường dẫn ảnh: Lùi 2 cấp (../../) để ra thư mục gốc chứa images
                        $avatarPath = "../../images/default.png";
                        if (!empty($row['avatar']) && $row['avatar'] != '0') {
                            $checkPath = "../../" . $row['avatar'];
                            if (file_exists($checkPath)) {
                                $avatarPath = $checkPath;
                            }
                        }
                        ?>
                        <tr>
                            <td class="text-center fw-bold"><?= $stt++; ?></td>
                            <td>
                                <img src="<?= $avatarPath ?>" class="rounded-circle border" width="45" height="45" style="object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-bold"><?= $row['fullname'] ?></div>
                                <small class="text-muted">@<?= $row['username'] ?></small>
                            </td>
                            <td><?= $row['email'] ?></td>
                            <td class="text-center">
                                <?php if ($row['role'] == 1): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">Thành viên</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($row['status'] == 1): ?>
                                    <span class="badge bg-success"><i class='bx bx-check'></i> Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Khóa</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?user=<?= $row['username'] ?>" class="btn btn-warning btn-sm" title="Sửa"><i class='bx bx-edit-alt'></i></a>
                                
                                <a href="delete.php?user=<?= $row['username'] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Bạn có chắc muốn xóa thành viên <?= $row['fullname'] ?>?')"
                                   title="Xóa">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center text-muted py-4'>Chưa có thành viên nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>