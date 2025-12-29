<?php
$path_to_admin = '../';
include('../includes/header.php');

if (isset($_POST['btnAdd'])) {
    $ten_hoat_dong = $_POST['ten_hoat_dong'];
    $mo_ta = $_POST['mo_ta'];
    $ngay_bat_dau = $_POST['ngay_bat_dau'];
    $dia_diem = $_POST['dia_diem'];

    $now = date('Y-m-d H:i:s');
    $trang_thai = ($ngay_bat_dau < $now) ? 1 : 0;


    $sql = "INSERT INTO tblhoatdong (ten_hoat_dong, mo_ta_hoat_dong, ngay_bat_dau, dia_diem, trang_thai) 
            VALUES ('$ten_hoat_dong', '$mo_ta', '$ngay_bat_dau', '$dia_diem', '$trang_thai')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Thêm hoạt động thành công!'); window.location.href='activities.php';</script>";
    } else {
        $error_msg = "Lỗi: " . $conn->error;
    }
}
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-calendar-plus'></i> Thêm Hoạt động mới</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error_msg)) {
                        echo "<div class='alert alert-danger'>$error_msg</div>";
                    } ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên hoạt động</label>
                            <input type="text" name="ten_hoat_dong" class="form-control" required placeholder="Ví dụ: Workshop Lập trình Web...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Thời gian bắt đầu</label>
                            <input type="datetime-local" name="ngay_bat_dau" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa điểm tổ chức</label>
                            <input type="text" name="dia_diem" class="form-control" required placeholder="Ví dụ: Phòng A1.201...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea name="mo_ta" class="form-control" rows="5" required></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="activities.php" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" name="btnAdd" class="btn btn-success">Thêm mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>