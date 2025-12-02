<?php
$path_to_admin = '../';
include('../includes/header.php');

if (!isset($_GET['id'])) { header("Location: index.php"); exit(); }
$id = $_GET['id'];

// Lấy thông tin bài viết cũ
$sql = "SELECT * FROM tblbaiviet WHERE Mabaiviet = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (isset($_POST['btnUpdate'])) {
    $noidung = $_POST['noidung'];
    $machude = $_POST['machude'];
    $trangthai = $_POST['trangthai'];
    
    // Giữ ảnh cũ mặc định
    $teptin = $row['Teptin'];

    // Nếu có chọn ảnh mới
    if (isset($_FILES['teptin']) && $_FILES['teptin']['error'] == 0) {
        $target_dir = "../../uploads/";
        $filename = time() . "_" . basename($_FILES["teptin"]["name"]);
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($_FILES["teptin"]["tmp_name"], $target_file)) {
            // Xóa ảnh cũ nếu có
            if (!empty($row['Teptin']) && file_exists("../../uploads/" . $row['Teptin'])) {
                unlink("../../uploads/" . $row['Teptin']);
            }
            $teptin = $filename;
        }
    }

    $sql_update = "UPDATE tblbaiviet SET 
                   Noidung = '$noidung', 
                   Machude = '$machude', 
                   Teptin = '$teptin', 
                   Trangthai = '$trangthai' 
                   WHERE Mabaiviet = '$id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='posts.php';</script>";
    } else {
        echo "<script>alert('Lỗi!');</script>";
    }
}
?>

<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 fw-bold">Sửa bài viết #<?= $id ?></h5>
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung</label>
                            <textarea name="noidung" class="form-control" rows="6" required><?= $row['Noidung'] ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chủ đề</label>
                            <select name="machude" class="form-select">
                                <?php
                                $res_cd = $conn->query("SELECT * FROM tblchude");
                                while ($rc = $res_cd->fetch_assoc()) {
                                    $selected = ($rc['Machude'] == $row['Machude']) ? 'selected' : '';
                                    echo "<option value='" . $rc['Machude'] . "' $selected>" . $rc['Tenchude'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ảnh hiện tại</label><br>
                            <?php if (!empty($row['Teptin'])): ?>
                                <img src="../../uploads/<?= $row['Teptin'] ?>" width="100" class="mb-2 border rounded">
                            <?php endif; ?>
                            <input type="file" name="teptin" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="trangthai" class="form-select">
                                <option value="1" <?= ($row['Trangthai'] == 1) ? 'selected' : '' ?>>Đã duyệt</option>
                                <option value="0" <?= ($row['Trangthai'] == 0) ? 'selected' : '' ?>>Chờ duyệt</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="posts.php" class="btn btn-secondary">Quay lại</a>
                    <button type="submit" name="btnUpdate" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include('../includes/footer.php'); ?>