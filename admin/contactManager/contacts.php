<?php
$path_to_admin = '../';
include('../includes/header.php');
?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
            <h5 class="mb-0 fw-bold text-primary"><i class='bx bx-envelope'></i> Danh sách Liên hệ & Góp ý</h5>
        </div>

        <div class="card-body p-0 p-md-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th style="min-width: 200px;">Người gửi</th>
                            <th style="min-width: 250px;">Nội dung tóm tắt</th>
                            <th class="text-center" style="width: 150px;">Ngày gửi</th>
                            <th class="text-center" style="width: 160px;">Trạng thái</th>
                            <th class="text-center" style="width: 100px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM tblcontact ORDER BY ngay_gui DESC";
                        $result = $conn->query($sql);

                        $stt = 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $stt++; ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($row['ten_nguoi_gui']) ?></div>
                                        <div class="text-muted small">
                                            <i class='bx bx-mail-send'></i> <?= htmlspecialchars($row['email']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-wrap" style="max-width: 350px;">
                                            <?= htmlspecialchars(mb_substr($row['noi_dung'], 0, 80)) ?>...
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <small class="text-muted"><?= date('H:i d/m/Y', strtotime($row['ngay_gui'])) ?></small>
                                    </td>

                                    <td class="text-center">
                                        <a href="toggle_status.php?id=<?= $row['ma_lien_he'] ?>&status=<?= $row['trang_thai'] ?>"
                                            class="text-decoration-none"
                                            onclick="return confirm('Bạn muốn đổi trạng thái xử lý của thư này?')">
                                            <?php if ($row['trang_thai'] == 1): ?>
                                                <span class="badge bg-success w-100 py-2"><i class='bx bx-check-double'></i> Đã phản
                                                    hồi</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark w-100 py-2"><i class='bx bx-time'></i> Chờ
                                                    xử lý</span>
                                            <?php endif; ?>
                                        </a>
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="detail.php?id=<?= $row['ma_lien_he'] ?>"
                                                class="btn btn-info btn-sm text-white" title="Xem chi tiết">
                                                <i class='bx bx-show'></i>
                                            </a>
                                            <a href="delete.php?id=<?= $row['ma_lien_he'] ?>" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc muốn xóa liên hệ này?')" title="Xóa">
                                                <i class='bx bx-trash'></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-muted py-4'>Hộp thư trống.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>