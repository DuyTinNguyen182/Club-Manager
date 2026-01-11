<?php
$path_to_admin = '../';
include('../includes/header.php');
require_once('../config.php'); // Nhớ include file config để kết nối DB

if (isset($_POST['btnAdd'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = $_POST['status'];

    // Xử lý upload ảnh
    $target_dir = "../../images/";
    // Kiểm tra thư mục tồn tại chưa, nếu chưa thì tạo (tùy chọn)
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $image_name = basename($_FILES["image"]["name"]);
    $target_file_name = time() . "_" . $image_name; // Thêm time() để tránh trùng tên
    $target_file = $target_dir . $target_file_name;
    $db_image_path = "images/" . $target_file_name;

    // Validate ảnh
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {

            $sql = "INSERT INTO tblslideshow (tieu_de, mo_ta, hinh_anh, trang_thai) 
                    VALUES ('$title', '$description', '$db_image_path', '$status')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Thêm slide thành công!'); window.location.href='slideshows.php';</script>";
            } else {
                $error_msg = "Lỗi Database: " . $conn->error;
            }
        } else {
            $error_msg = "Lỗi khi upload file ảnh. Vui lòng kiểm tra quyền thư mục.";
        }
    } else {
        $error_msg = "File tải lên không phải là hình ảnh hợp lệ.";
    }
}
?>

<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-image-add'></i> Thêm Slide mới</h5>
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
                            <input type="text" name="title" class="form-control" placeholder="Nhập tiêu đề slide"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="Mô tả ngắn gọn..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Hình ảnh <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" required accept="image/*">
                            <div class="form-text text-muted">
                                <i class='bx bx-info-circle'></i> Khuyên dùng kích thước banner ngang (ví dụ:
                                1200x400px).
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="1">Hiển thị ngay</option>
                                <option value="0">Tạm ẩn</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="slideshows.php" class="btn btn-secondary"><i class='bx bx-arrow-back'></i> Quay
                                lại</a>
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