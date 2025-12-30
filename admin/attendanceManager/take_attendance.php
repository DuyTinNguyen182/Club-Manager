<?php
$path_to_admin = '../';
include('../includes/header.php');

// 1. Kiểm tra ID hoạt động
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('Không tìm thấy hoạt động!'); window.location.href='attendance.php';</script>";
    exit();
}
$hoatdong_id = intval($_GET['id']);

// 2. Lấy thông tin hoạt động
$sql_hd = "SELECT * FROM tblhoatdong WHERE hoatdong_id = $hoatdong_id";
$result_hd = $conn->query($sql_hd);
$row_hd = $result_hd->fetch_assoc();

if (!$row_hd) {
    echo "<script>alert('Hoạt động không tồn tại!'); window.location.href='attendance.php';</script>";
    exit();
}

// 3. XỬ LÝ FORM ĐIỂM DANH
if (isset($_POST['btnSaveAttendance'])) {
    $users_list = isset($_POST['users']) ? $_POST['users'] : [];
    $present_list = isset($_POST['present']) ? $_POST['present'] : [];

    foreach ($users_list as $username) {
        $status = in_array($username, $present_list) ? 1 : 2;
        $stmt = $conn->prepare("UPDATE tbldangkyhoatdong SET trang_thai = ? WHERE hoatdong_id = ? AND username = ?");
        $stmt->bind_param("iis", $status, $hoatdong_id, $username);
        $stmt->execute();
    }
    echo "<script>alert('Cập nhật điểm danh thành công!'); window.location.href='take_attendance.php?id=$hoatdong_id';</script>";
}

// 4. Xử lý tìm kiếm
$search_query = "";
$search_sql = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $_GET['search'];
    $search_sql = " AND u.fullname LIKE '%$search_query%' ";
}

// 5. Lấy danh sách thành viên
$sql_dk = "SELECT dk.*, u.fullname, u.email, u.username 
           FROM tbldangkyhoatdong dk 
           JOIN tbluser u ON dk.username = u.username 
           WHERE dk.hoatdong_id = $hoatdong_id $search_sql
           ORDER BY dk.ngay_dangky ASC";
$result_dk = $conn->query($sql_dk);
?>

<div class="container-fluid mt-3">
    <a href="attendance.php" class="text-decoration-none text-secondary mb-3 d-inline-block">
        <i class='bx bx-arrow-back'></i> Quay lại danh sách
    </a>

    <div class="card shadow">
        <div class="card-header bg-primary text-white p-3">
            <h5 class="mb-0">
                <i class='bx bx-user-check'></i> Điểm danh: <?= htmlspecialchars($row_hd['ten_hoat_dong']) ?>
            </h5>
            <small><i class='bx bx-time'></i> <?= date('d/m/Y H:i', strtotime($row_hd['ngay_bat_dau'])) ?> | <i class='bx bx-map'></i> <?= $row_hd['dia_diem'] ?></small>
        </div>

        <div class="card-body">
            <form action="" method="GET" class="mb-4">
                <input type="hidden" name="id" value="<?= $hoatdong_id ?>">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên thành viên..." value="<?= htmlspecialchars($search_query) ?>">
                    <button class="btn btn-outline-primary" type="submit"><i class='bx bx-search'></i> Tìm kiếm</button>
                    <?php if (!empty($search_query)): ?>
                        <a href="take_attendance.php?id=<?= $hoatdong_id ?>" class="btn btn-outline-secondary">Xóa lọc</a>
                    <?php endif; ?>
                </div>
            </form>

            <form action="" method="POST">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th width="50">STT</th>
                                <th>Tên thành viên</th>
                                <th>Email</th>
                                <th width="120">Minh chứng</th> 
                                <th width="150">Trạng thái</th>
                                <th width="120" class="bg-warning bg-opacity-10">Tham gia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_dk && $result_dk->num_rows > 0) {
                                $stt = 1;
                                while ($row = $result_dk->fetch_assoc()) {
                            ?>
                                    <tr>
                                        <td class="text-center"><?= $stt++ ?></td>
                                        <td class="fw-bold"><?= $row['fullname'] ?></td>
                                        <td><?= $row['email'] ?></td>

                                        <td class="text-center">
                                            <?php if (!empty($row['minh_chung'])): 
                                                // Đường dẫn file (Lưu ý: file admin nằm trong thư mục con nên cần ../ để ra ngoài)
                                                $file_path = "../../uploads/proofs/" . $row['minh_chung'];
                                                $ext = strtolower(pathinfo($row['minh_chung'], PATHINFO_EXTENSION));
                                                
                                                // Nếu là ảnh -> Hiển thị thumbnail
                                                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): 
                                            ?>
                                                <a href="<?= $file_path ?>" target="_blank" title="Xem ảnh lớn">
                                                    <img src="<?= $file_path ?>" alt="Proof" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                                </a>
                                            <?php else: 
                                                // Nếu là file khác (PDF, Doc) -> Hiển thị nút tải/xem
                                            ?>
                                                <a href="<?= $file_path ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class='bx bx-file'></i> File
                                                </a>
                                            <?php endif; ?>

                                            <?php else: ?>
                                                <span class="text-muted small"><em>--</em></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($row['trang_thai'] == 0) echo '<span class="badge bg-info text-dark">Chưa điểm danh</span>';
                                            elseif ($row['trang_thai'] == 1) echo '<span class="badge bg-success">Đã tham gia</span>';
                                            elseif ($row['trang_thai'] == 2) echo '<span class="badge bg-danger">Vắng</span>';
                                            ?>
                                        </td>

                                        <td class="text-center bg-warning bg-opacity-10">
                                            <input type="hidden" name="users[]" value="<?= $row['username'] ?>">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox"
                                                    name="present[]"
                                                    value="<?= $row['username'] ?>"
                                                    style="transform: scale(1.5); cursor: pointer;"
                                                    <?= ($row['trang_thai'] == 1) ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted py-4'>Không tìm thấy thành viên đăng ký nào.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-light rounded border">
                    <div class="text-muted">
                        <small>* Xem minh chứng trước khi tick <b>Tham gia</b>.</small>
                    </div>
                    <button type="submit" name="btnSaveAttendance" class="btn btn-primary px-4 fw-bold">
                        <i class='bx bx-save'></i> Xác nhận điểm danh
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>