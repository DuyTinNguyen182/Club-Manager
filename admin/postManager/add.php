<?php
$path_to_admin = '../';
include('../includes/header.php');

if (isset($_POST['btnAdd'])) {
    $noidung = $_POST['noidung'];
    $machude = $_POST['machude'];
    $trangthai = $_POST['trangthai'];
    $username = $_SESSION['username']; // Lấy người đang đăng nhập
    $ngaytao = date('Y-m-d'); // Lấy ngày hiện tại

    // Xử lý upload ảnh
    $teptin = "";
    if (isset($_FILES['teptin']) && $_FILES['teptin']['error'] == 0) {
        $target_dir = "../../uploads/";
        // Tạo tên file ngẫu nhiên để tránh trùng
        $filename = time() . "_" . basename($_FILES["teptin"]["name"]);
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($_FILES["teptin"]["tmp_name"], $target_file)) {
            $teptin = $filename;
        }
    }

    $sql = "INSERT INTO tblbaiviet (Noidung, Machude, Ngaytao, Teptin, Username, Trangthai) 
            VALUES ('$noidung', '$machude', '$ngaytao', '$teptin', '$username', '$trangthai')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Thêm bài viết thành công!'); window.location.href='posts.php';</script>";
    } else {
        $error_msg = "Lỗi: " . $conn->error;
    }
}
?>

<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0 fw-bold">Thêm bài viết mới</h5>
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data"> <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung bài viết</label>
                            <textarea name="noidung" class="form-control" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn chủ đề</label>
                            <select name="machude" class="form-select" required>
                                <option value="">-- Chọn chủ đề --</option>
                                <?php
                                $sql_cd = "SELECT * FROM tblchude WHERE Trangthai = 1";
                                $res_cd = $conn->query($sql_cd);
                                while ($row_cd = $res_cd->fetch_assoc()) {
                                    echo "<option value='" . $row_cd['Machude'] . "'>" . $row_cd['Tenchude'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Hình ảnh đính kèm</label>
                            <input type="file" name="teptin" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="trangthai" class="form-select">
                                <option value="1">Duyệt ngay</option>
                                <option value="0">Chờ duyệt</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="posts.php" class="btn btn-secondary">Hủy</a>
                    <button type="submit" name="btnAdd" class="btn btn-success">Đăng bài</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>