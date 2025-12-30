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

    // Validate: Ngày kết thúc phải sau ngày bắt đầu
    if (strtotime($ngay_ket_thuc) <= strtotime($ngay_bat_dau)) {
        $msg = "<div class='alert alert-danger'>Lỗi: Ngày kết thúc phải diễn ra sau ngày bắt đầu!</div>";
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

<div class="card shadow-sm mx-auto" style="max-width: 800px;">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-primary"><i class='bx bx-plus-circle'></i> Thêm hoạt động mới</h5>
    </div>
    <div class="card-body">
        <?= $msg ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-bold">Tên hoạt động</label>
                <input type="text" name="ten_hoat_dong" class="form-control" required placeholder="Ví dụ: Tập huấn kỹ năng...">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Thời gian bắt đầu</label>
                    <input type="datetime-local" name="ngay_bat_dau" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Thời gian kết thúc</label>
                    <input type="datetime-local" name="ngay_ket_thuc" class="form-control" required>
                    <div class="form-text">Phải lớn hơn thời gian bắt đầu.</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Địa điểm</label>
                <input type="text" name="dia_diem" class="form-control" required placeholder="Ví dụ: Phòng B101...">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả nội dung</label>
                <textarea name="mo_ta_hoat_dong" class="form-control" rows="5" placeholder="Nội dung chi tiết hoạt động..."></textarea>
            </div>

            <div class="text-end">
                <a href="activities.php" class="btn btn-secondary me-2">Hủy</a>
                <button type="submit" class="btn btn-primary"><i class='bx bx-save'></i> Lưu hoạt động</button>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>