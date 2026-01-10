<?php
$path_to_admin = '../';
include('../includes/header.php');
require_once('../config.php');

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $sql = "SELECT * FROM tblchude WHERE ma_chu_de = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "<script>alert('Chủ đề không tồn tại!'); window.location.href='topics.php';</script>";
        exit();
    }
    $stmt->close();
} else {
    header("Location: index.php");
    exit();
}

if (isset($_POST['btnUpdate'])) {
    $tenchude = trim($_POST['tenchude']);
    $trangthai = (int) $_POST['trangthai'];

    if (empty($tenchude)) {
        $error_msg = "Vui lòng nhập tên chủ đề!";
    } elseif (strlen($tenchude) < 3) {
        $error_msg = "Tên chủ đề phải có ít nhất 3 ký tự!";
    } else {
        $sql_check = "SELECT ma_chu_de FROM tblchude WHERE ten_chu_de = ? AND ma_chu_de != ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("si", $tenchude, $id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error_msg = "Tên chủ đề này đã tồn tại!";
        } else {
            $sql_update = "UPDATE tblchude SET ten_chu_de = ?, trang_thai = ? WHERE ma_chu_de = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("sii", $tenchude, $trangthai, $id);

            if ($stmt_update->execute()) {
                echo "<script>alert('Cập nhật thành công!'); window.location.href='topics.php';</script>";
            } else {
                $error_msg = "Lỗi hệ thống: " . $stmt_update->error;
            }
            $stmt_update->close();
        }
        $stmt_check->close();
    }
}
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-edit'></i> Sửa chủ đề: #<?= $row['ma_chu_de'] ?></h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_msg)) {
                        echo "<div class='alert alert-danger'>$error_msg</div>";
                    } ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên chủ đề</label>
                            <input type="text" name="tenchude" class="form-control"
                                value="<?= isset($tenchude) ? htmlspecialchars($tenchude) : htmlspecialchars($row['ten_chu_de']) ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="trangthai" class="form-select">
                                <option value="0" <?= ($row['trang_thai'] == 0) ? 'selected' : '' ?>>Ẩn</option>
                                <option value="1" <?= ($row['trang_thai'] == 1) ? 'selected' : '' ?>>Hiển thị</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="topics.php" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" name="btnUpdate" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>