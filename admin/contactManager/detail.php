<?php
$path_to_admin = '../';
include('../includes/header.php');
require_once('../config.php'); // Đảm bảo có kết nối CSDL

if (!isset($_GET['id'])) {
    header("Location: contacts.php");
    exit();
}
$id = $_GET['id'];
// Ép kiểu ID để an toàn bảo mật
$id = mysqli_real_escape_string($conn, $id);

$sql = "SELECT * FROM tblcontact WHERE ma_lien_he = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    echo "<script>alert('Liên hệ không tồn tại!'); window.location.href='contacts.php';</script>";
    exit();
}
?>

<div class="container-fluid mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-9 col-lg-7">
            <div class="card shadow border-0">
                <div
                    class="card-header bg-info text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-envelope-open'></i> Chi tiết liên hệ
                        #<?= $row['ma_lien_he'] ?></h5>
                    <span class="badge bg-light text-dark shadow-sm">
                        <?= ($row['trang_thai'] == 1) ? '<i class="bx bx-check"></i> Đã phản hồi' : '<i class="bx bx-time"></i> Chưa xử lý' ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12 col-sm-6 mb-2">
                            <label class="fw-bold text-muted small text-uppercase">Người gửi</label>
                            <div class="fs-5 fw-bold text-dark"><?= htmlspecialchars($row['ten_nguoi_gui']) ?></div>
                        </div>
                        <div class="col-12 col-sm-6 mb-2">
                            <label class="fw-bold text-muted small text-uppercase">Email</label>
                            <div>
                                <a href="mailto:<?= htmlspecialchars($row['email']) ?>"
                                    class="text-decoration-none fw-bold">
                                    <i class='bx bx-mail-send'></i> <?= htmlspecialchars($row['email']) ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small text-uppercase">Thời gian gửi</label>
                        <div class="text-dark"><i class='bx bx-time'></i>
                            <?= date('H:i:s - d/m/Y', strtotime($row['ngay_gui'])) ?></div>
                    </div>

                    <hr class="text-muted">

                    <div class="mb-3">
                        <label class="fw-bold text-primary mb-2">Nội dung tin nhắn:</label>
                        <div class="p-3 bg-light rounded border border-start-0 border-end-0 border-primary border-3">
                            <?= nl2br(htmlspecialchars($row['noi_dung'])) ?>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                        <a href="contacts.php" class="btn btn-secondary">
                            <i class='bx bx-arrow-back'></i> Quay lại
                        </a>

                        <a href="mailto:<?= htmlspecialchars($row['email']) ?>?subject=Phản hồi liên hệ: CLB Tin Học"
                            class="btn btn-primary">
                            <i class='bx bx-reply'></i> Trả lời Email
                        </a>

                        <?php if ($row['trang_thai'] == 0): ?>
                            <a href="toggle_status.php?id=<?= $row['ma_lien_he'] ?>&status=0" class="btn btn-success">
                                <i class='bx bx-check-circle'></i> Đánh dấu đã xong
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>