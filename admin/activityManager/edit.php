<?php
$path_to_admin = '../';
include('../includes/header.php');

// 1. Kiểm tra ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = $_GET['id'];
$msg = "";

// 2. Lấy thông tin hoạt động từ DB
$sql = "SELECT * FROM tblhoatdong WHERE hoatdong_id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    echo "<script>alert('Hoạt động không tồn tại!'); window.location.href='index.php';</script>";
    exit();
}

// 3. XỬ LÝ CẬP NHẬT KHI SUBMIT
if (isset($_POST['btnUpdate'])) {
    $ten_hoat_dong = $_POST['ten_hoat_dong'];
    $mo_ta = $_POST['mo_ta'];
    $ngay_bat_dau = $_POST['ngay_bat_dau'];
    $ngay_ket_thuc = $_POST['ngay_ket_thuc']; // Lấy giá trị mới
    $dia_diem = $_POST['dia_diem'];

    // Validate: Ngày kết thúc phải sau ngày bắt đầu
    if (strtotime($ngay_ket_thuc) <= strtotime($ngay_bat_dau)) {
        $msg = "<div class='alert alert-danger'>Lỗi: Ngày kết thúc phải diễn ra sau ngày bắt đầu!</div>";
    } else {
        // Cập nhật trạng thái tự động (0: Sắp/Đang diễn ra, 1: Đã kết thúc)
        // Logic: Nếu hiện tại > ngày kết thúc => Đã xong
        $now = date('Y-m-d H:i:s');
        $trang_thai = ($now > $ngay_ket_thuc) ? 1 : 0;

        $sql_update = "UPDATE tblhoatdong SET 
                       ten_hoat_dong = ?, 
                       mo_ta_hoat_dong = ?,
                       ngay_bat_dau = ?,
                       ngay_ket_thuc = ?,
                       dia_diem = ?,
                       trang_thai = ?
                       WHERE hoatdong_id = ?";
        
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("sssssii", $ten_hoat_dong, $mo_ta, $ngay_bat_dau, $ngay_ket_thuc, $dia_diem, $trang_thai, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Cập nhật thành công!'); window.location.href='activities.php';</script>";
            exit();
        } else {
            $msg = "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
        }
    }
}

// 4. Chuẩn bị dữ liệu hiển thị vào form
$time_start_val = date('Y-m-d\TH:i', strtotime($row['ngay_bat_dau']));
// Nếu chưa có ngày kết thúc thì để trống hoặc lấy bằng ngày bắt đầu
$time_end_val = !empty($row['ngay_ket_thuc']) ? date('Y-m-d\TH:i', strtotime($row['ngay_ket_thuc'])) : '';
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-edit'></i> Chỉnh sửa Hoạt động: #<?= $id ?></h5>
                </div>
                <div class="card-body">
                    <?= $msg ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên hoạt động</label>
                            <input type="text" name="ten_hoat_dong" class="form-control" value="<?= htmlspecialchars($row['ten_hoat_dong']) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Thời gian bắt đầu</label>
                                <input type="datetime-local" name="ngay_bat_dau" class="form-control" value="<?= $time_start_val ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Thời gian kết thúc</label>
                                <input type="datetime-local" name="ngay_ket_thuc" class="form-control" value="<?= $time_end_val ?>" required>
                                <div class="form-text text-muted">Phải lớn hơn thời gian bắt đầu.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa điểm tổ chức</label>
                            <input type="text" name="dia_diem" class="form-control" value="<?= htmlspecialchars($row['dia_diem']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea name="mo_ta" class="form-control" rows="6" required><?= htmlspecialchars($row['mo_ta_hoat_dong']) ?></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="activities.php" class="btn btn-secondary px-4">Quay lại</a>
                            <button type="submit" name="btnUpdate" class="btn btn-warning px-4 fw-bold">
                                <i class='bx bx-save'></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>