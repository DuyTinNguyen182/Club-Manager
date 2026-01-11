<?php
include('includes/header.php');

// Kiểm tra quyền truy cập
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    echo "<script>window.location.href='../index.php';</script>";
    exit();
}

// --- TRUY VẤN DỮ LIỆU ---
// 1. Thống kê số lượng
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbluser"))['total'];
$total_posts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tblbaiviet"))['total'];
$total_contacts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tblcontact"))['total'];
$act_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tblhoatdong"));

// 2. Lấy dữ liệu chi tiết (LIMIT 4)
$limit = 4;
$res_new_users = mysqli_query($conn, "SELECT * FROM tbluser WHERE quyen = 0 ORDER BY username DESC LIMIT $limit");
$res_new_contacts = mysqli_query($conn, "SELECT * FROM tblcontact ORDER BY ngay_gui DESC LIMIT $limit");
$res_new_activities = mysqli_query($conn, "SELECT * FROM tblhoatdong ORDER BY ngay_bat_dau DESC LIMIT $limit");
$res_new_posts = mysqli_query($conn, "SELECT b.*, u.ho_va_ten FROM tblbaiviet b JOIN tbluser u ON b.username = u.username ORDER BY b.ngay_tao DESC LIMIT $limit");
?>

<style>
    /* CSS Tùy chỉnh */
    .card-dashboard {
        height: 100%;
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border-radius: 0.35rem;
    }

    .table-custom {
        margin-bottom: 0;
    }

    .dashboard-stat-card {
        transition: transform .2s;
        border: none;
        border-radius: 0.35rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .dashboard-stat-card:hover {
        transform: translateY(-5px);
    }
</style>

<div class="container-fluid p-0 mt-3">
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card text-white bg-primary h-100 dashboard-stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 small fw-bold text-white-50">Thành viên</h6>
                        <h2 class="fw-bold m-0"><?php echo $total_users; ?></h2>
                    </div>
                    <i class='bx bxs-user bx-lg opacity-25'></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card text-white bg-success h-100 dashboard-stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 small fw-bold text-white-50">Bài viết</h6>
                        <h2 class="fw-bold m-0"><?php echo $total_posts; ?></h2>
                    </div>
                    <i class='bx bxs-file-txt bx-lg opacity-25'></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card text-white bg-warning h-100 dashboard-stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 small fw-bold text-white-50">Liên hệ</h6>
                        <h2 class="fw-bold text-white m-0"><?php echo $total_contacts; ?></h2>
                    </div>
                    <i class='bx bxs-envelope bx-lg opacity-25 text-white'></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card text-white bg-danger h-100 dashboard-stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 small fw-bold text-white-50">Hoạt động</h6>
                        <h2 class="fw-bold m-0"><?php echo $act_count['total']; ?></h2>
                    </div>
                    <i class='bx bxs-calendar-event bx-lg opacity-25'></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card card-dashboard">
                <div
                    class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom flex-wrap gap-2">
                    <h6 class="m-0 fw-bold text-warning"><i class='bx bx-envelope'></i> Liên hệ mới</h6>
                    <a href="contacts.php" class="btn btn-sm btn-outline-warning">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-custom text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Người gửi</th>
                                    <th>Ngày</th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($res_new_contacts)): ?>
                                    <tr>
                                        <td class="py-3">
                                            <div class="fw-bold text-dark text-truncate" style="max-width: 180px;">
                                                <?php echo htmlspecialchars($row['ten_nguoi_gui']); ?>
                                            </div>
                                            <small class="text-muted d-block text-truncate" style="max-width: 180px;">
                                                <?php echo htmlspecialchars($row['email']); ?>
                                            </small>
                                        </td>
                                        <td><small
                                                class="text-muted"><?php echo date('d/m', strtotime($row['ngay_gui'])); ?></small>
                                        </td>
                                        <td class="text-center">
                                            <?php echo ($row['trang_thai'] == 0) ?
                                                '<span class="badge bg-danger bg-opacity-75">Chưa xử lý</span>' :
                                                '<span class="badge bg-success bg-opacity-75">Đã xử lý</span>';
                                            ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card card-dashboard">
                <div
                    class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom flex-wrap gap-2">
                    <h6 class="m-0 fw-bold text-primary"><i class='bx bx-calendar'></i> Hoạt động sắp tới</h6>
                    <a href="activities.php" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-custom text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Tên hoạt động</th>
                                    <th>Ngày BĐ</th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($res_new_activities)): ?>
                                    <tr>
                                        <td class="py-3">
                                            <div class="text-truncate fw-bold text-dark" style="max-width: 200px;"
                                                title="<?php echo $row['ten_hoat_dong']; ?>">
                                                <?php echo $row['ten_hoat_dong']; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted"><i class='bx bx-time'></i>
                                                <?php echo date('d/m/Y', strtotime($row['ngay_bat_dau'])); ?></small>
                                        </td>
                                        <td class="text-center">
                                            <?php echo ($row['trang_thai'] == 0) ?
                                                '<span class="badge bg-success bg-opacity-75">Sắp diễn ra</span>' :
                                                '<span class="badge bg-secondary bg-opacity-75">Đã kết thúc</span>';
                                            ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card card-dashboard">
                <div
                    class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom flex-wrap gap-2">
                    <h6 class="m-0 fw-bold text-info"><i class='bx bx-user-plus'></i> Thành viên mới</h6>
                    <a href="members.php" class="btn btn-sm btn-outline-info">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-custom text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Thành viên</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($res_new_users)): ?>
                                    <tr>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($row['anh_dai_dien'])): ?>
                                                    <img src="../uploads/<?php echo $row['anh_dai_dien']; ?>"
                                                        class="rounded-circle me-2 border" width="35" height="35"
                                                        style="object-fit:cover;">
                                                <?php else: ?>
                                                    <div class="rounded-circle me-2 bg-light d-flex justify-content-center align-items-center text-secondary"
                                                        style="width: 35px; height: 35px;">
                                                        <i class='bx bxs-user'></i>
                                                    </div>
                                                <?php endif; ?>
                                                <strong class="text-truncate" style="max-width: 100px;">
                                                    <?php echo htmlspecialchars($row['username']); ?>
                                                </strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate text-dark" style="max-width: 150px;">
                                                <?php echo htmlspecialchars($row['ho_va_ten']); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate text-muted small" style="max-width: 150px;">
                                                <?php echo htmlspecialchars($row['email']); ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card card-dashboard">
                <div
                    class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom flex-wrap gap-2">
                    <h6 class="m-0 fw-bold text-success"><i class='bx bx-news'></i> Bài viết mới</h6>
                    <a href="posts.php" class="btn btn-sm btn-outline-success">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <?php if (mysqli_num_rows($res_new_posts) > 0): ?>
                            <ul class="list-group list-group-flush">
                                <?php while ($post = mysqli_fetch_assoc($res_new_posts)): ?>
                                    <li class="list-group-item py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-3 overflow-hidden">
                                                <div class="fw-bold text-truncate text-dark" style="max-width: 100%;">
                                                    <?php echo htmlspecialchars(strip_tags($post['noi_dung'])); ?>
                                                </div>
                                                <small class="text-muted d-flex align-items-center gap-1 mt-1">
                                                    <i class='bx bx-user-circle'></i> <?php echo $post['ho_va_ten']; ?>
                                                </small>
                                            </div>
                                            <span class="badge bg-light text-secondary border">
                                                <?php echo date('d/m', strtotime($post['ngay_tao'])); ?>
                                            </span>
                                        </div>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <i class='bx bx-notepad bx-md mb-2 opacity-25'></i>
                                <p class="mb-0">Chưa có bài viết nào</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include('includes/footer.php'); ?>