<?php
$path_to_admin = '../';
include('../includes/header.php');

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_hoat_dong = $_POST['ten_hoat_dong'];
    $dia_diem = $_POST['dia_diem'];
    $ngay_bat_dau = $_POST['ngay_bat_dau'];
    $ngay_ket_thuc = $_POST['ngay_ket_thuc'];
    $mo_ta = $_POST['mo_ta_hoat_dong'];

    if (strtotime($ngay_ket_thuc) <= strtotime($ngay_bat_dau)) {
        $msg = "<div class='alert alert-danger alert-dismissible fade show'>
                    <i class='bx bx-error-circle'></i> Ngày kết thúc phải diễn ra sau ngày bắt đầu!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                </div>";
    } else {
        $sql = "INSERT INTO tblhoatdong (ten_hoat_dong, dia_diem, ngay_bat_dau, ngay_ket_thuc, mo_ta_hoat_dong, trang_thai) 
                VALUES (?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $ten_hoat_dong, $dia_diem, $ngay_bat_dau, $ngay_ket_thuc, $mo_ta);

        if ($stmt->execute()) {
            echo "<script>alert('Thêm hoạt động thành công!'); window.location.href='activities.php';</script>";
        } else {
            $msg = "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
        }
    }
}
?>

<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-plus-circle'></i> Thêm hoạt động mới</h5>
                </div>
                <div class="card-body">
                    <?= $msg ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên hoạt động <span class="text-danger">*</span></label>
                            <input type="text" name="ten_hoat_dong" class="form-control" required
                                placeholder="Ví dụ: Tập huấn kỹ năng...">
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-bold">Thời gian bắt đầu <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" name="ngay_bat_dau" class="form-control" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label fw-bold">Thời gian kết thúc <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" name="ngay_ket_thuc" class="form-control" required>
                                <div class="form-text small">Phải lớn hơn thời gian bắt đầu.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa điểm <span class="text-danger">*</span></label>
                            <input type="text" name="dia_diem" class="form-control" required
                                placeholder="Ví dụ: Phòng B101...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả nội dung</label>
                            <textarea name="mo_ta_hoat_dong" class="form-control" rows="5"
                                placeholder="Nội dung chi tiết hoạt động..."></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
                            <a href="activities.php" class="btn btn-secondary"><i class='bx bx-arrow-back'></i> Hủy</a>
                            <button type="submit" class="btn btn-success"><i class='bx bx-save'></i> Lưu hoạt
                                động</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>