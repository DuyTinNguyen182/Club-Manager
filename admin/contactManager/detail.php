<?php
$path_to_admin = '../';
include('../includes/header.php');

if (!isset($_GET['id'])) { header("Location: index.php"); exit(); }
$id = $_GET['id'];

$sql = "SELECT * FROM tblcontact WHERE id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    echo "<script>alert('Liên hệ không tồn tại!'); window.location.href='contacts.php';</script>";
    exit();
}
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class='bx bx-envelope'></i> Chi tiết liên hệ #<?= $id ?></h5>
                    <span class="badge bg-light text-dark">
                        <?= ($row['Trangthai'] == 1) ? 'Đã phản hồi' : 'Chưa xử lý' ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="fw-bold text-muted">Người gửi:</label>
                        <p class="fs-5 mb-1"><?= $row['Tennguoigui'] ?></p>
                        <a href="mailto:<?= $row['Email'] ?>" class="text-decoration-none">
                            <i class='bx bx-mail-send'></i> <?= $row['Email'] ?>
                        </a>
                    </div>
                    
                    <hr>

                    <div class="mb-3">
                        <label class="fw-bold text-muted">Thời gian gửi:</label>
                        <span><?= date('H:i:s - d/m/Y', strtotime($row['Ngaygui'])) ?></span>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted">Nội dung:</label>
                        <div class="p-3 bg-light rounded border mt-2">
                            <?= nl2br(htmlspecialchars($row['Noidung'])) ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="contacts.php" class="btn btn-secondary">Quay lại</a>
                        
                        <a href="mailto:<?= $row['Email'] ?>?subject=Phản hồi liên hệ từ CLB Tin Học" class="btn btn-primary">
                            <i class='bx bx-reply'></i> Trả lời qua Email
                        </a>
                        
                        <?php if ($row['Trangthai'] == 0): ?>
                            <a href="toggle_status.php?id=<?= $row['id'] ?>&status=0" class="btn btn-success">
                                <i class='bx bx-check'></i> Đánh dấu đã xong
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>