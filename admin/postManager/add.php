<?php
$path_to_admin = '../';
include('../includes/header.php');
require_once('../config.php'); // Đảm bảo include file kết nối

if (isset($_POST['btnAdd'])) {
    // Thêm real_escape_string để tránh lỗi SQL khi nội dung có dấu nháy '
    $noidung = mysqli_real_escape_string($conn, $_POST['noidung']);
    $machude = (int) $_POST['machude'];
    $trangthai = (int) $_POST['trangthai'];
    $username = $_SESSION['username'];
    $ngaytao = date('Y-m-d H:i:s');

    // Xử lý upload ảnh
    $teptin = "";
    if (isset($_FILES['teptin']) && $_FILES['teptin']['error'] == 0) {
        $target_dir = "../../uploads/";
        // Kiểm tra tạo thư mục nếu chưa có
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $filename = time() . "_" . basename($_FILES["teptin"]["name"]);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["teptin"]["tmp_name"], $target_file)) {
            $teptin = $filename;
        }
    }

    $sql = "INSERT INTO tblbaiviet (noi_dung, ma_chu_de, ngay_tao, tep_tin, username, trang_thai) 
            VALUES ('$noidung', '$machude', '$ngaytao', '$teptin', '$username', '$trangthai')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Thêm bài viết thành công!'); window.location.href='posts.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }
}
?>

<div class="container-fluid mt-4 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0 fw-bold"><i class='bx bx-pencil'></i> Thêm bài viết mới</h5>
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12 col-lg-8 mb-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung bài viết <span
                                    class="text-danger">*</span></label>
                            <textarea name="noidung" class="form-control" rows="10"
                                placeholder="Nhập nội dung bài viết..." required></textarea>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="card bg-light border-0 p-3 mb-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chọn chủ đề <span class="text-danger">*</span></label>
                                <select name="machude" class="form-select" required>
                                    <option value="">-- Chọn chủ đề --</option>
                                    <?php
                                    $sql_cd = "SELECT * FROM tblchude WHERE trang_thai = 1";
                                    $res_cd = $conn->query($sql_cd);
                                    while ($row_cd = $res_cd->fetch_assoc()) {
                                        echo "<option value='" . $row_cd['ma_chu_de'] . "'>" . $row_cd['ten_chu_de'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Hình ảnh đính kèm</label>
                                <input type="file" name="teptin" class="form-control">
                                <div class="form-text text-muted small">Định dạng: jpg, png, jpeg.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="trangthai" class="form-select">
                                    <option value="1">Duyệt ngay</option>
                                    <option value="0">Chờ duyệt</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="btnAdd" class="btn btn-success"><i class='bx bx-send'></i> Đăng
                                bài</button>
                            <a href="posts.php" class="btn btn-outline-secondary">Hủy bỏ</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>