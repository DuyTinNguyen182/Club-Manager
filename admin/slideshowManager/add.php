<?php
$path_to_admin = '../';
include('../includes/header.php');

if (isset($_POST['btnAdd'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = $_POST['status'];

    // Xử lý upload ảnh
    $target_dir = "../../images/"; // Thư mục lưu ảnh (root/images)
    $image_name = basename($_FILES["image"]["name"]);
    // Thêm timestamp để tránh trùng tên
    $target_file_name = time() . "_" . $image_name;
    $target_file = $target_dir . $target_file_name;
    $db_image_path = "images/" . $target_file_name; // Đường dẫn lưu vào DB
    $uploadOk = 1;

    // Kiểm tra file ảnh
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Upload thành công -> Insert vào DB
            // username tạm để là admin hoặc lấy từ session nếu có
            $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'admin';

            $sql = "INSERT INTO tblslideshow (Title, Description, ImageUrl, Status, username) 
                    VALUES ('$title', '$description', '$db_image_path', '$status', '$username')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Thêm slide thành công!'); window.location.href='slideshows.php';</script>";
            } else {
                $error_msg = "Lỗi Database: " . $conn->error;
            }
        } else {
            $error_msg = "Lỗi khi upload file ảnh.";
        }
    } else {
        $error_msg = "File không phải là hình ảnh hợp lệ.";
    }
}
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-image-add'></i> Thêm Slide mới</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_msg)) {
                        echo "<div class='alert alert-danger'>$error_msg</div>";
                    } ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Hình ảnh <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" required accept="image/*">
                            <div class="form-text">Khuyên dùng kích thước banner ngang (ví dụ: 1200x400px).</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="1">Hiển thị</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="slideshows.php" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" name="btnAdd" class="btn btn-success">Thêm mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('../includes/footer.php'); ?>