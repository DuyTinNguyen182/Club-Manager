<?php
$path_to_admin = '../';
include('../includes/header.php');

if (isset($_POST['btnAdd'])) {
    $tenchude = $_POST['tenchude'];
    $trangthai = $_POST['trangthai'];

    // Kiểm tra rỗng
    if (!empty($tenchude)) {
        $sql = "INSERT INTO tblchude (Tenchude, Trangthai) VALUES ('$tenchude', '$trangthai')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Thêm chủ đề thành công!'); window.location.href='topics.php';</script>";
        } else {
            $error_msg = "Lỗi: " . $conn->error;
        }
    } else {
        $error_msg = "Vui lòng nhập tên chủ đề!";
    }
}
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-folder-plus'></i> Thêm Chủ đề mới</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_msg)) { echo "<div class='alert alert-danger'>$error_msg</div>"; } ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên chủ đề <span class="text-danger">*</span></label>
                            <input type="text" name="tenchude" class="form-control" placeholder="Nhập tên chủ đề..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="trangthai" class="form-select">
                                <option value="0">Hiển thị</option>
                                <option value="1">Ẩn</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="topics.php" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" name="btnAdd" class="btn btn-success">Thêm mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>