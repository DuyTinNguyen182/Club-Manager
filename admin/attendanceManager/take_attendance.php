<?php
$path_to_admin = '../';
include('../includes/header.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('Không tìm thấy hoạt động!'); window.location.href='attendance.php';</script>";
    exit();
}
$hoatdong_id = intval($_GET['id']);

$sql_hd = "SELECT * FROM tblhoatdong WHERE ma_hoat_dong = $hoatdong_id";
$result_hd = $conn->query($sql_hd);
$row_hd = $result_hd->fetch_assoc();

if (!$row_hd) {
    echo "<script>alert('Hoạt động không tồn tại!'); window.location.href='attendance.php';</script>";
    exit();
}

// Xử lý lưu điểm danh
if (isset($_POST['btnSaveAttendance'])) {
    $users_list = isset($_POST['users']) ? $_POST['users'] : [];
    $present_list = isset($_POST['present']) ? $_POST['present'] : [];

    // Sử dụng Prepare Statement để tối ưu hiệu năng và bảo mật khi loop nhiều lần
    $stmt = $conn->prepare("UPDATE tbldangkyhoatdong SET trang_thai = ? WHERE ma_hoat_dong = ? AND username = ?");
    
    foreach ($users_list as $username) {
        // Logic: Nếu username nằm trong mảng present -> status = 1 (Tham gia), ngược lại = 2 (Vắng)
        $status = in_array($username, $present_list) ? 1 : 2;
        $stmt->bind_param("iis", $status, $hoatdong_id, $username);
        $stmt->execute();
    }
    $stmt->close();
    
    echo "<script>alert('Cập nhật điểm danh thành công!'); window.location.href='take_attendance.php?id=$hoatdong_id';</script>";
}

// Xử lý tìm kiếm
$search_query = "";
$search_sql = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $search_sql = " AND (u.ho_va_ten LIKE '%$search_query%' OR u.ma_sinh_vien LIKE '%$search_query%') ";
}

$sql_dk = "SELECT dk.*, u.ho_va_ten, u.email, u.ma_sinh_vien, u.username 
           FROM tbldangkyhoatdong dk 
           JOIN tbluser u ON dk.username = u.username 
           WHERE dk.ma_hoat_dong = $hoatdong_id $search_sql
           ORDER BY u.ho_va_ten ASC"; // Sắp xếp theo tên cho dễ gọi
$result_dk = $conn->query($sql_dk);
?>

<div class="container-fluid mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="attendance.php" class="btn btn-secondary btn-sm">
            <i class='bx bx-arrow-back'></i> Quay lại
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white p-3">
            <h5 class="mb-1 fw-bold"><i class='bx bx-check-shield'></i> Điểm danh thành viên</h5>
            <div class="small opacity-75">
                Hoạt động: <b><?= htmlspecialchars($row_hd['ten_hoat_dong']) ?></b>
            </div>
        </div>

        <div class="card-body">
            <form action="" method="GET" class="mb-4">
                <input type="hidden" name="id" value="<?= $hoatdong_id ?>">
                <div class="input-group shadow-sm">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Nhập tên hoặc MSSV..." value="<?= htmlspecialchars($search_query) ?>">
                    <button class="btn btn-primary" type="submit"><i class='bx bx-search'></i> Tìm</button>
                    <?php if (!empty($search_query)): ?>
                        <a href="take_attendance.php?id=<?= $hoatdong_id ?>" class="btn btn-danger">Xóa lọc</a>
                    <?php endif; ?>
                </div>
            </form>

            <form action="" method="POST">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light text-center sticky-top" style="z-index: 1;">
                            <tr>
                                <th width="50">STT</th>
                                <th class="text-start">Thành viên</th>
                                <th width="100">Minh chứng</th> 
                                <th width="120">Trạng thái cũ</th>
                                <th width="100" class="bg-warning bg-opacity-25 text-dark clickable-header" style="cursor: pointer;" onclick="toggleAll()">
                                    <div class="form-check d-flex justify-content-center align-items-center gap-1">
                                        <input class="form-check-input" type="checkbox" id="checkAll" style="transform: scale(1.2);">
                                        <label class="form-check-label small fw-bold" for="checkAll" style="cursor: pointer;">Tất cả</label>
                                    </div>
                                </th>
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
                                        <td>
                                            <div class="fw-bold text-primary"><?= htmlspecialchars($row['ho_va_ten']) ?></div>
                                            <div class="small text-muted">
                                                MSSV: <?= htmlspecialchars($row['ma_sinh_vien']) ?>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <?php if (!empty($row['minh_chung'])): 
                                                $file_path = "../../uploads/proofs/" . $row['minh_chung'];
                                                $ext = strtolower(pathinfo($row['minh_chung'], PATHINFO_EXTENSION));
                                            ?>
                                                <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                                    <a href="<?= $file_path ?>" target="_blank">
                                                        <img src="<?= $file_path ?>" class="rounded border" style="width: 40px; height: 40px; object-fit: cover;">
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= $file_path ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                                        <i class='bx bx-file'></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td class="text-center">
                                            <?php
                                            if ($row['trang_thai'] == 0) echo '<span class="badge bg-light text-secondary border">Chưa xét</span>';
                                            elseif ($row['trang_thai'] == 1) echo '<span class="badge bg-success">Đã tham gia</span>';
                                            elseif ($row['trang_thai'] == 2) echo '<span class="badge bg-danger">Vắng</span>';
                                            ?>
                                        </td>

                                        <td class="text-center bg-warning bg-opacity-10">
                                            <input type="hidden" name="users[]" value="<?= $row['username'] ?>">
                                            
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input user-checkbox" type="checkbox"
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
                                echo "<tr><td colspan='5' class='text-center text-muted py-4'>Không tìm thấy thành viên nào.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-grid gap-2 mt-4 sticky-bottom bg-white pt-2 pb-2 border-top">
                    <button type="submit" name="btnSaveAttendance" class="btn btn-primary fw-bold shadow-sm">
                        <i class='bx bx-save'></i> LƯU ĐIỂM DANH
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script xử lý chọn tất cả
    document.getElementById('checkAll').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.user-checkbox');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });
</script>

<?php include('../includes/footer.php'); ?>