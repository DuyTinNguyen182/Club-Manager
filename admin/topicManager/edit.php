<?php
$path_to_admin = '../';
include('../includes/header.php');

// Kiểm tra ID có tồn tại trên URL không
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // CẬP NHẬT: Machude -> ma_chu_de
    $sql = "SELECT * FROM tblchude WHERE ma_chu_de = '$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "<script>alert('Chủ đề không tồn tại!'); window.location.href='topics.php';</script>";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

// Xử lý cập nhật
if (isset($_POST['btnUpdate'])) {
    $tenchude = $_POST['tenchude'];
    $trangthai = $_POST['trangthai'];

    // CẬP NHẬT: Tenchude -> ten_chu_de, Trangthai -> trang_thai, Machude -> ma_chu_de
    $sql_update = "UPDATE tblchude SET 
                   ten_chu_de = '$tenchude', 
                   trang_thai = '$trangthai' 
                   WHERE ma_chu_de = '$id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='topics.php';</script>";
    } else {
        $error_msg = "Lỗi: " . $conn->error;
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
                            <input type="text" name="tenchude" class="form-control" value="<?= $row['ten_chu_de'] ?>"
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