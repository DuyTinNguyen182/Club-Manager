<?php
$path_to_admin = '../';
include('../includes/header.php');
require_once('../config.php');

if (!isset($_GET['id'])) {
    header("Location: posts.php");
    exit();
}
$id = (int) $_GET['id']; // Ép kiểu số cho an toàn

$sql = "SELECT * FROM tblbaiviet WHERE ma_bai_viet = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    echo "<script>alert('Bài viết không tồn tại!'); window.location.href='posts.php';</script>";
    exit();
}

if (isset($_POST['btnUpdate'])) {
    $noidung = mysqli_real_escape_string($conn, $_POST['noidung']);
    $machude = (int) $_POST['machude'];
    $trangthai = (int) $_POST['trangthai'];

    $teptin = $row['tep_tin']; // Giữ ảnh cũ

    if (isset($_FILES['teptin']) && $_FILES['teptin']['error'] == 0) {
        $target_dir = "../../uploads/";
        $filename = time() . "_" . basename($_FILES["teptin"]["name"]);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["teptin"]["tmp_name"], $target_file)) {
            // Xóa ảnh cũ
            if (!empty($row['tep_tin']) && file_exists("../../uploads/" . $row['tep_tin'])) {
                unlink("../../uploads/" . $row['tep_tin']);
            }
            $teptin = $filename;
        }
    }

    $sql_update = "UPDATE tblbaiviet SET 
                   noi_dung = '$noidung', 
                   ma_chu_de = '$machude', 
                   tep_tin = '$teptin', 
                   trang_thai = '$trangthai' 
                   WHERE ma_bai_viet = '$id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='posts.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }
}
?>

<div class="container-fluid mt-4 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 fw-bold"><i class='bx bx-edit'></i> Sửa bài viết #<?= $id ?></h5>
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12 col-lg-8 mb-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung</label>
                            <textarea name="noidung" class="form-control" rows="12"
                                required><?= htmlspecialchars($row['noi_dung']) ?></textarea>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="card bg-light border-0 p-3 mb-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chủ đề</label>
                                <select name="machude" class="form-select">
                                    <?php
                                    $res_cd = $conn->query("SELECT * FROM tblchude");
                                    while ($rc = $res_cd->fetch_assoc()) {
                                        $selected = ($rc['ma_chu_de'] == $row['ma_chu_de']) ? 'selected' : '';
                                        echo "<option value='" . $rc['ma_chu_de'] . "' $selected>" . $rc['ten_chu_de'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Ảnh hiện tại</label>
                                <div class="mb-2 text-center p-2 border bg-white rounded">
                                    <?php if (!empty($row['tep_tin'])): ?>
                                        <img src="../../uploads/<?= $row['tep_tin'] ?>" class="img-fluid rounded"
                                            style="max-height: 200px;">
                                    <?php else: ?>
                                        <span class="text-muted small">Không có ảnh</span>
                                    <?php endif; ?>
                                </div>
                                <label class="form-label small text-primary cursor-pointer"><i class='bx bx-upload'></i>
                                    Chọn ảnh thay thế</label>
                                <input type="file" name="teptin" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="trangthai" class="form-select">
                                    <option value="1" <?= ($row['trang_thai'] == 1) ? 'selected' : '' ?>>Đã duyệt</option>
                                    <option value="0" <?= ($row['trang_thai'] == 0) ? 'selected' : '' ?>>Chờ duyệt</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="btnUpdate" class="btn btn-primary"><i class='bx bx-save'></i>
                                Lưu thay đổi</button>
                            <a href="posts.php" class="btn btn-outline-secondary">Quay lại</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include('../includes/footer.php'); ?>