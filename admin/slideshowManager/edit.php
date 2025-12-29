<?php
$path_to_admin = '../';
include('../includes/header.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tblslideshow WHERE Id = '$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if (!$row) {
        header("Location: slideshows.php");
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

    // Logic xử lý ảnh
    $db_image_path = $row['ImageUrl']; // Mặc định giữ ảnh cũ

    if (!empty($_FILES['image']['name'])) {
        // Nếu có chọn ảnh mới
        $target_dir = "../../images/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file_name = time() . "_" . $image_name;
        $target_file = $target_dir . $target_file_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Xóa ảnh cũ nếu tồn tại
            if (file_exists("../../" . $row['ImageUrl'])) {
                unlink("../../" . $row['ImageUrl']);
            }
            $db_image_path = "images/" . $target_file_name;
        }
    }

    $sql_update = "UPDATE tblslideshow SET 
                   Title = '$title', 
                   Description = '$description', 
                   ImageUrl = '$db_image_path', 
                   Status = '$status' 
                   WHERE Id = '$id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='slideshows.php';</script>";
    } else {
        $error_msg = "Lỗi: " . $conn->error;
    }
}
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-edit'></i> Sửa Slide: #<?= $row['Id'] ?></h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_msg)) {
                        echo "<div class='alert alert-danger'>$error_msg</div>";
                    } ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="<?= $row['Title'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="description" class="form-control" rows="3"><?= $row['Description'] ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Hình ảnh hiện tại</label> <br>
                            <img src="../../<?= $row['ImageUrl'] ?>" style="max-width: 200px; border: 1px solid #ddd; padding: 5px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn ảnh mới (Nếu muốn thay đổi)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="1" <?= ($row['Status'] == 1) ? 'selected' : '' ?>>Hiển thị</option>
                                <option value="0" <?= ($row['Status'] == 0) ? 'selected' : '' ?>>Ẩn</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="slideshows.php" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" name="btnUpdate" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('../includes/footer.php'); ?>