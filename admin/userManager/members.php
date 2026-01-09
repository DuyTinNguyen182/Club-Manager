<?php
require_once('../../config.php');

// Xử lý đổi trạng thái nhanh
if (isset($_GET['action']) && $_GET['action'] == 'toggle' && isset($_GET['user'])) {
    $user = $_GET['user'];
    $user = $conn->real_escape_string($user);
    // CẬP NHẬT: status -> trang_thai
    $sql_update = "UPDATE tbluser SET trang_thai = 1 - trang_thai WHERE username = '$user'";
    if ($conn->query($sql_update)) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

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
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">STT</th>
                        <th style="width: 60px;">Avatar</th>
                        <th>Thông tin cá nhân</th>
                        <th>MSSV</th>
                        <th>Lớp</th>

                        <th>Email</th>
                        <th class="text-center">Vai trò</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center" style="width: 100px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // CẬP NHẬT: role -> quyen
                    $sql = "SELECT * FROM tbluser ORDER BY quyen DESC";
                    $result = $conn->query($sql);

                    $stt = 1;
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $avatarPath = "../../uploads/default.jpg";
                            // CẬP NHẬT: avatar -> anh_dai_dien
                            if (!empty($row['anh_dai_dien'])) {
                                $checkPath = "../../uploads/" . $row['anh_dai_dien'];
                                if (file_exists($checkPath)) {
                                    $avatarPath = $checkPath;
                                }
                            }
                            ?>
                            <tr>
                                <td class="text-center fw-bold"><?= $stt++; ?></td>
                                <td>
                                    <img src="<?= $avatarPath ?>" class="rounded-circle border" width="40" height="40"
                                        style="object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-bold"><?= $row['ho_va_ten'] ?></div>
                                    <small class="text-muted">@<?= $row['username'] ?></small>
                                </td>

                                <td><?= $row['ma_sinh_vien'] ?></td>
                                <td><?= $row['ma_lop'] ?></td>

                                <td><?= $row['email'] ?></td>
                                <td class="text-center">
                                    <?php if ($row['quyen'] == 1): ?>
                                        <span class="badge bg-danger">Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary">Thành viên</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <a href="?action=toggle&user=<?= $row['username'] ?>" style="text-decoration: none;"
                                        onclick="return confirm('Bạn có muốn thay đổi trạng thái của <?= $row['ho_va_ten'] ?>?')">
                                        <?php if ($row['trang_thai'] == 1): ?>
                                            <span class="badge bg-success"><i class='bx bx-check'></i> Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Khóa</span>
                                        <?php endif; ?>
                                    </a>
                                </td>

                                <td class="text-center">
                                    <a href="edit.php?user=<?= $row['username'] ?>" class="btn btn-warning btn-sm" title="Sửa">
                                        <i class='bx bx-edit-alt'></i>
                                    </a>
                                    <a href="delete.php?user=<?= $row['username'] ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Xóa thành viên <?= $row['ho_va_ten'] ?>?')" title="Xóa">
                                        <i class='bx bx-trash'></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center text-muted py-4'>Chưa có thành viên nào.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>