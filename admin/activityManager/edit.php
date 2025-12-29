<?php
$path_to_admin = '../';
include('../includes/header.php');

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = $_GET['id'];

$sql = "SELECT * FROM tblhoatdong WHERE hoatdong_id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    echo "<script>alert('Hoạt động không tồn tại!'); window.location.href='activities.php';</script>";
    exit();
}

if (isset($_POST['btnUpdate'])) {
    $ten_hoat_dong = $_POST['ten_hoat_dong'];
    $mo_ta = $_POST['mo_ta'];
    $ngay_bat_dau = $_POST['ngay_bat_dau'];
    $dia_diem = $_POST['dia_diem'];
    $trang_thai = $_POST['trang_thai'];

    $now = date('Y-m-d H:i:s');
    $trang_thai = ($ngay_bat_dau < $now) ? 1 : 0;

    $sql_update = "UPDATE tblhoatdong SET 
                   ten_hoat_dong = '$ten_hoat_dong', 
                   mo_ta_hoat_dong = '$mo_ta',
                   ngay_bat_dau = '$ngay_bat_dau',
                   dia_diem = '$dia_diem',
                   trang_thai = '$trang_thai'
                   WHERE hoatdong_id = '$id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='activities.php';</script>";
    } else {
        $error_msg = "Lỗi: " . $conn->error;
    }
}

// Format lại ngày giờ để hiển thị đúng trong thẻ input datetime-local
$datetime_value = date('Y-m-d\TH:i', strtotime($row['ngay_bat_dau']));
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-edit'></i> Sửa Hoạt động: #<?= $id ?></h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_msg)) {
                        echo "<div class='alert alert-danger'>$error_msg</div>";
                    } ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên hoạt động</label>
                            <input type="text" name="ten_hoat_dong" class="form-control" value="<?= $row['ten_hoat_dong'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Thời gian bắt đầu</label>
                            <input type="datetime-local" name="ngay_bat_dau" class="form-control" value="<?= $datetime_value ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa điểm tổ chức</label>
                            <input type="text" name="dia_diem" class="form-control" value="<?= $row['dia_diem'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea name="mo_ta" class="form-control" rows="5" required><?= $row['mo_ta_hoat_dong'] ?></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="activities.php" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" name="btnUpdate" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>