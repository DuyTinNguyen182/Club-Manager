<?php
$path_to_admin = '../';
include('../includes/header.php');

if (!isset($_GET['id'])) { header("Location: index.php"); exit(); }
$id = $_GET['id'];

// Lấy thông tin bình luận
$sql = "SELECT * FROM tblbinhluan WHERE Mabinhluan = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    echo "<script>alert('Bình luận không tồn tại!'); window.location.href='comments.php';</script>";
    exit();
}

if (isset($_POST['btnUpdate'])) {
    $noidung = $_POST['noidung'];
    $trangthai = $_POST['trangthai'];

    $sql_update = "UPDATE tblbinhluan SET 
                   Noidung = '$noidung', 
                   Trangthai = '$trangthai' 
                   WHERE Mabinhluan = '$id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='comments.php';</script>";
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
                    <h5 class="mb-0 fw-bold"><i class='bx bx-edit'></i> Kiểm duyệt bình luận #<?= $id ?></h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_msg)) { echo "<div class='alert alert-danger'>$error_msg</div>"; } ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Người gửi:</label>
                            <input type="text" class="form-control bg-light" value="@<?= $row['Username'] ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung bình luận</label>
                            <textarea name="noidung" class="form-control" rows="4" required><?= $row['Noidung'] ?></textarea>
                            <div class="form-text text-muted">Admin có thể chỉnh sửa nội dung nếu cần thiết.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="trangthai" class="form-select">
                                <option value="1" <?= ($row['Trangthai'] == 1) ? 'selected' : '' ?>>Hiển thị</option>
                                <option value="0" <?= ($row['Trangthai'] == 0) ? 'selected' : '' ?>>Ẩn (Chờ duyệt)</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="comments.php" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" name="btnUpdate" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>