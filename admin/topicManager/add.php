<?php
$path_to_admin = '../';
include('../includes/header.php');
require_once('../config.php');

if (isset($_POST['btnAdd'])) {
    // ... Logic PHP giữ nguyên ...
    $tenchude = trim($_POST['tenchude']);
    $trangthai = (int) $_POST['trangthai'];

    if (empty($tenchude)) {
        $error_msg = "Vui lòng nhập tên chủ đề!";
    } elseif (strlen($tenchude) < 3) {
        $error_msg = "Tên chủ đề phải có ít nhất 3 ký tự!";
    } else {
        $sql_check = "SELECT ma_chu_de FROM tblchude WHERE ten_chu_de = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $tenchude);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error_msg = "Tên chủ đề này đã tồn tại!";
        } else {
            $sql_insert = "INSERT INTO tblchude (ten_chu_de, trang_thai) VALUES (?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("si", $tenchude, $trangthai);

            if ($stmt_insert->execute()) {
                echo "<script>alert('Thêm chủ đề thành công!'); window.location.href='topics.php';</script>";
            } else {
                $error_msg = "Lỗi hệ thống: " . $stmt_insert->error;
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-folder-plus'></i> Thêm Chủ đề mới</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_msg)) {
                        echo "<div class='alert alert-danger alert-dismissible fade show'>
                                <i class='bx bx-error-circle'></i> $error_msg
                                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                              </div>";
                    } ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên chủ đề <span class="text-danger">*</span></label>
                            <input type="text" name="tenchude" class="form-control" placeholder="Nhập tên chủ đề..."
                                value="<?= isset($tenchude) ? htmlspecialchars($tenchude) : '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="trangthai" class="form-select">
                                <option value="1">Hiển thị</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="topics.php" class="btn btn-secondary"><i class='bx bx-arrow-back'></i> Quay lại</a>
                            <button type="submit" name="btnAdd" class="btn btn-success"><i class='bx bx-save'></i> Thêm
                                mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>