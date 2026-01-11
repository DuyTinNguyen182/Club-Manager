<?php
$path_to_admin = '../';
include('../includes/header.php');
require_once('../config.php'); // Include config

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $sql = "SELECT * FROM tblslideshow WHERE ma_slide = '$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if (!$row) {
        echo "<script>alert('Slide không tồn tại!'); window.location.href='slideshows.php';</script>";
        exit();
    }
} else {
    header("Location: slideshows.php");
    exit();
}

if (isset($_POST['btnUpdate'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = $_POST['status'];

    $db_image_path = $row['hinh_anh'];

    // Kiểm tra nếu người dùng có chọn ảnh mới
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../../images/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file_name = time() . "_" . $image_name;
        $target_file = $target_dir . $target_file_name;

        // Upload ảnh mới
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Xóa ảnh cũ nếu tồn tại
            if (file_exists("../../" . $row['hinh_anh']) && !empty($row['hinh_anh'])) {
                unlink("../../" . $row['hinh_anh']);
            }
            $db_image_path = "images/" . $target_file_name;
        }
    }

    $sql_update = "UPDATE tblslideshow SET 
                   tieu_de = '$title', 
                   mo_ta = '$description', 
                   hinh_anh = '$db_image_path', 
                   trang_thai = '$status' 
                   WHERE ma_slide = '$id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='slideshows.php';</script>";
    } else {
        $error_msg = "Lỗi: " . $conn->error;
    }
}
?>

<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-edit'></i> Sửa Slide: #<?= $row['ma_slide'] ?></h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_msg)) {
                        echo "<div class='alert alert-danger alert-dismissible fade show'>
                                <i class='bx bx-error-circle'></i> $error_msg
                                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                              </div>";
                    } ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tiêu đề</label>
                            <input type="text" name="title" class="form-control"
                                value="<?= htmlspecialchars($row['tieu_de']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="description" class="form-control"
                                rows="3"><?= htmlspecialchars($row['mo_ta']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Hình ảnh hiện tại</label> <br>
                            <?php if (!empty($row['hinh_anh'])): ?>
                                <img src="../../<?= $row['hinh_anh'] ?>" class="img-fluid rounded border mb-2"
                                    style="max-height: 150px;">
                            <?php else: ?>
                                <p class="text-muted small">Chưa có ảnh</p>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-primary">
                                <i class='bx bx-upload'></i> Chọn ảnh mới (Nếu muốn thay đổi)
                            </label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="1" <?= ($row['trang_thai'] == 1) ? 'selected' : '' ?>>Hiển thị</option>
                                <option value="0" <?= ($row['trang_thai'] == 0) ? 'selected' : '' ?>>Ẩn</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="slideshows.php" class="btn btn-secondary"><i class='bx bx-arrow-back'></i> Quay
                                lại</a>
                            <button type="submit" name="btnUpdate" class="btn btn-primary"><i class='bx bx-save'></i>
                                Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('../includes/footer.php'); ?>