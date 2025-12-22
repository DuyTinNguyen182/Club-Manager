<?php
require("phandau.php");

// --- XỬ LÝ GỬI BÌNH LUẬN (CHA HOẶC CON) ---
if (isset($_POST['btn_gui_binhluan']) && isset($_SESSION['emailUser'])) {
    $mabaiviet = $_POST['mabaiviet_post'];
    $noidung_bl = $_POST['noidung_bl'];
    // Lấy parent_id (nếu = 0 là cha, > 0 là trả lời cho bình luận khác)
    $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : 0;
    $username = $_SESSION['username'];

    $noidung_bl = str_replace("'", "\'", $noidung_bl);

    if (!empty($noidung_bl)) {
        // Câu lệnh INSERT có thêm cột parent_id
        $sql_them = "INSERT INTO tblbinhluan(Noidung, Mabaiviet, Ngaytao, Username, Trangthai, parent_id) 
                     VALUES('$noidung_bl', '$mabaiviet', NOW(), '$username', 1, $parent_id)";

        if ($conn->query($sql_them)) {
            // Reload lại trang và mở bài viết đó ra
            $cur_id = $_GET['id'];
            echo "<script>window.location.href='danhmuc_baiviet.php?id=$cur_id&open=$mabaiviet#post-$mabaiviet';</script>";
        }
    }
}

// --- XỬ LÝ ĐĂNG BÀI VIẾT MỚI ---
if (isset($_POST['btn_dang_bai']) && isset($_SESSION['username'])) {
    $machude_post = intval($_POST['machude_post']);
    $noidung_bai = $_POST['noidung_bai'];
    $username = $_SESSION['username'];

    // Xử lý nội dung (chống lỗi SQL khi có dấu nháy đơn)
    $noidung_bai = str_replace("'", "\'", $noidung_bai);

    // Xử lý file ảnh (nếu có)
    $ten_teptin = "";
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $duoi_file = pathinfo($_FILES['hinh_anh']['name'], PATHINFO_EXTENSION);
        // Đặt tên file theo thời gian để không bị trùng
        $ten_teptin = time() . "_" . $username . "." . $duoi_file;
        $duong_dan_upload = "uploads/" . $ten_teptin;
        move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $duong_dan_upload);
    }

    if (!empty($noidung_bai)) {
        $sql_dangbai = "INSERT INTO tblbaiviet(Noidung, Machude, Ngaytao, Username, Trangthai, Teptin) 
                        VALUES('$noidung_bai', '$machude_post', NOW(), '$username', 1, '$ten_teptin')";

        if ($conn->query($sql_dangbai)) {
            // Đăng xong thì load lại trang
            echo "<script>alert('Đăng bài thành công!'); window.location.href='danhmuc_baiviet.php?id=$machude_post';</script>";
        } else {
            echo "<script>alert('Lỗi khi đăng bài!');</script>";
        }
    } else {
        echo "<script>alert('Vui lòng nhập nội dung bài viết!');</script>";
    }
}

// Xử lý ẩn bình luận (Admin)
// if (isset($_GET['mbl']) && $_GET['act'] == 'hide' && isset($_SESSION['role']) && $_SESSION['role'] == 1) {
//     $mbl = $_GET['mbl'];
//     $conn->query("UPDATE tblbinhluan SET Trangthai = 1 WHERE Mabinhluan = '$mbl'");
//     $cur_id = $_GET['id'];
//     echo "<script>window.location.href='danhmuc_baiviet.php?id=$cur_id';</script>";
// }
// --- XỬ LÝ XÓA BÌNH LUẬN (Cập nhật mới) ---
// Kiểm tra: Nếu là Admin (role=1) HOẶC là Chính chủ thì cho xóa
if (isset($_GET['mbl']) && $_GET['act'] == 'del' && isset($_SESSION['username'])) {
    $mbl = intval($_GET['mbl']);
    $currentUser = $_SESSION['username'];
    $userRole = $_SESSION['role'] ?? 0;

    // 1. Kiểm tra xem bình luận này của ai?
    $checkSql = "SELECT Username FROM tblbinhluan WHERE Mabinhluan = '$mbl'";
    $checkRs = $conn->query($checkSql);

    if ($checkRs->num_rows > 0) {
        $commentOwner = $checkRs->fetch_assoc()['Username'];

        // 2. Nếu là Admin hoặc là Chính chủ thì xóa
        // 2. Nếu là Admin hoặc là Chính chủ thì xóa
        if ($userRole == 1 || $currentUser == $commentOwner) {

            // SỬA: Xóa bình luận hiện tại VÀ tất cả các bình luận con có parent_id là bình luận này
            $sql = "DELETE FROM tblbinhluan WHERE Mabinhluan = '$mbl' OR parent_id = '$mbl'";

            if ($conn->query($sql)) {
                // Xóa thành công thì mới reload
                $cur_id = $_GET['id'];
                echo "<script>window.location.href='danhmuc_baiviet.php?id=$cur_id';</script>";
            } else {
                // Xử lý lỗi nếu query thất bại (tùy chọn)
                echo "<script>alert('Lỗi khi xóa dữ liệu!');</script>";
            }

        } else {
            echo "<script>alert('Bạn không có quyền xóa bình luận này!');</script>";
        }
    }
}

// Lấy thông tin chủ đề
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $machude = intval($_GET['id']);
    $rs_chude = $conn->query("SELECT Tenchude FROM tblchude WHERE Machude = $machude");
    if ($rs_chude->num_rows > 0)
        $ten_chu_de = $rs_chude->fetch_assoc()['Tenchude'];
    else {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

<style>
    /* CSS CƠ BẢN */
    .feed-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px 0;
    }

    .feed-item {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .feed-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
    }

    .feed-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 50%;
        border: 1px solid #ddd;
    }

    .feed-content {
        font-size: 1rem;
        color: #1e293b;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    /* ẢNH BÀI VIẾT */
    .feed-image-container {
        margin-top: 10px;
        margin-bottom: 15px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #f1f5f9;
    }

    .feed-image {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        display: block;
        cursor: zoom-in;
    }

    /* THANH ACTION */
    .action-bar {
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        padding: 5px 0;
        display: flex;
        justify-content: center;
        margin-bottom: 15px;
    }

    .btn-action {
        background: none;
        border: none;
        padding: 8px 0;
        width: 100%;
        cursor: pointer;
        color: #64748b;
        font-weight: 600;
        font-size: 0.95rem;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-action:hover {
        background-color: #f1f5f9;
        color: #0d6efd;
    }

    /* KHU VỰC BÌNH LUẬN */
    .comment-section {
        background: #f8fafc;
        border-radius: 8px;
        padding: 15px;
        display: none;
    }

    /* Mặc định ẩn */

    /* BÌNH LUẬN CHA */
    .comment-item {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .cmt-avatar-main {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* BÌNH LUẬN CON (REPLY) - Thụt đầu dòng */
    .reply-list {
        margin-left: 42px;
        /* Thụt vào */
        border-left: 2px solid #e2e8f0;
        padding-left: 10px;
    }

    .reply-item {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        margin-top: 10px;
    }

    .cmt-avatar-sub {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        object-fit: cover;
    }

    .cmt-bubble {
        background: #e2e8f0;
        padding: 8px 12px;
        border-radius: 12px;
        font-size: 0.9rem;
        flex-grow: 1;
        position: relative;
    }

    .cmt-author {
        font-weight: 700;
        font-size: 0.85rem;
        display: block;
    }

    .cmt-time {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: normal;
        margin-left: 5px;
    }

    /* Nút Trả lời nhỏ dưới comment */
    .btn-reply-text {
        font-size: 0.8rem;
        font-weight: 600;
        color: #006affff;
        cursor: pointer;
        margin-left: 5px;
        text-decoration: none;
    }

    .btn-reply-text:hover {
        color: #0d6efd;
        text-decoration: underline;
    }

    /* FORM NHẬP */
    .comment-form {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .comment-input {
        flex-grow: 1;
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 20px;
        outline: none;
        font-size: 0.9rem;
    }

    .btn-send {
        background: #0d6efd;
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-text-action {
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        margin-left: 5px;
        text-decoration: none;
        color: red;
    }

    /* Form trả lời ẩn (cho reply) */
    .reply-form-container {
        display: none;
        margin-left: 42px;
        margin-top: 5px;
    }
</style>

<div class="content-section" style="background-color: #f1f5f9; min-height: 100vh;">
    <div class="container feed-container">

        <?php if (isset($_SESSION['username'])): ?>
            <div class="feed-item" style="border: 2px dashed #cbd5e1; background: #f8fafc;">
                <div class="feed-header" style="margin-bottom: 10px;">
                    <img src="uploads/<?php echo $_SESSION['avatar'] ?? ''; ?>" class="feed-avatar"
                        onerror="this.src='https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>'">
                    <h4 style="margin:0; font-size:1rem; color:#475569;">Tạo bài viết mới</h4>
                </div>

                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="machude_post" value="<?php echo $machude; ?>">

                    <textarea name="noidung_bai" class="comment-input" rows="3"
                        style="width:100%; border-radius: 8px; padding: 10px; resize: vertical; margin-bottom: 10px;"
                        placeholder="Bạn đang nghĩ gì về chủ đề này?..." required></textarea>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="position: relative; overflow: hidden; display: inline-block;">
                            <button type="button" class="btn-action"
                                style="width: auto; padding: 5px 15px; border: 1px solid #cbd5e1;">
                                <i class="fa-regular fa-image" style="color: #10b981;"></i> Thêm ảnh
                            </button>
                            <input type="file" name="hinh_anh" accept="image/*"
                                style="font-size: 100px; position: absolute; left: 0; top: 0; opacity: 0; cursor: pointer;"
                                onchange="document.getElementById('file-chosen').textContent = this.files[0].name">
                            <span id="file-chosen" style="margin-left: 10px; font-size: 0.9rem; color: #64748b;"></span>
                        </div>

                        <button type="submit" name="btn_dang_bai" class="btn-send"
                            style="width: auto; padding: 0 20px; border-radius: 5px; font-weight: bold;">
                            Đăng bài
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <div
            style="background:#fff; padding:15px; border-radius:10px; margin-bottom:20px; border-left: 5px solid #0d6efd;">
            <h2 style="margin:0; font-size:1.5rem; color:#334155;">
                <i class="fa-solid fa-layer-group"></i> Chủ đề: <?php echo $ten_chu_de; ?>
            </h2>
        </div>

        <?php
        $sql_bv = "SELECT bv.*, u.avatar FROM tblbaiviet bv JOIN tbluser u ON bv.Username = u.Username 
                   WHERE bv.Machude = $machude AND bv.Trangthai = 1 ORDER BY bv.Ngaytao DESC";
        $rs_bv = $conn->query($sql_bv);

        if ($rs_bv->num_rows > 0) {
            while ($bv = $rs_bv->fetch_assoc()) {
                $id_baiviet = $bv['Mabaiviet'];

                // Đếm tổng bình luận (cả cha và con)
                $sql_count = "SELECT COUNT(*) as total FROM tblbinhluan WHERE Mabaiviet = $id_baiviet AND Trangthai = 1";
                $total_cmt = $conn->query($sql_count)->fetch_assoc()['total'];
                ?>
                <div class="feed-item" id="post-<?php echo $id_baiviet; ?>">

                    <div class="feed-header">
                        <img src="uploads/<?php echo $bv['avatar']; ?>" class="feed-avatar"
                            onerror="this.src='https://ui-avatars.com/api/?name=<?php echo $bv['Username']; ?>'">
                        <div>
                            <h4 style="margin:0; font-size:1rem;"><?php echo $bv['Username']; ?></h4>
                            <span
                                style="font-size:0.8rem; color:#64748b"><?php echo date('H:i d/m/Y', strtotime($bv['Ngaytao'])); ?></span>
                        </div>
                    </div>

                    <div class="feed-content"><?php echo nl2br($bv['Noidung']); ?></div>
                    <?php if (!empty($bv['Teptin'])): ?>
                        <div class="feed-image-container">
                            <a href="uploads/<?php echo $bv['Teptin']; ?>" data-fancybox="gallery-<?php echo $id_baiviet; ?>">
                                <img src="uploads/<?php echo $bv['Teptin']; ?>" class="feed-image">
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="action-bar">
                        <button class="btn-action" onclick="toggleCommentBox(<?php echo $id_baiviet; ?>)">
                            <i class="fa-regular fa-comment-dots"></i> Bình luận (<?php echo $total_cmt; ?>)
                        </button>
                    </div>

                    <div class="comment-section" id="cmt-box-<?php echo $id_baiviet; ?>">

                        <?php if (isset($_SESSION['emailUser'])): ?>
                            <form action="" method="POST" class="comment-form" style="margin-bottom: 20px;">
                                <input type="hidden" name="mabaiviet_post" value="<?php echo $id_baiviet; ?>">
                                <input type="hidden" name="parent_id" value="0"> <img
                                    src="uploads/<?php echo $_SESSION['avatar'] ?? ''; ?>" class="cmt-avatar-main"
                                    onerror="this.src='https://ui-avatars.com/api/?name=User'">
                                <input type="text" name="noidung_bl" class="comment-input" placeholder="Viết bình luận công khai..."
                                    required autocomplete="off">
                                <button type="submit" name="btn_gui_binhluan" class="btn-send"><i
                                        class="fa-solid fa-paper-plane"></i></button>
                            </form>
                        <?php endif; ?>

                        <?php
                        // 1. Lấy tất cả bình luận của bài viết ra mảng trước để dễ xử lý
                        $all_comments = [];
                        $sql_bl = "SELECT bl.*, u.avatar FROM tblbinhluan bl JOIN tbluser u ON bl.Username = u.Username 
                                   WHERE bl.Mabaiviet = $id_baiviet AND bl.Trangthai = 1 ORDER BY bl.Ngaytao ASC";
                        $rs_bl = $conn->query($sql_bl);
                        while ($row_bl = $rs_bl->fetch_assoc()) {
                            $all_comments[] = $row_bl;
                        }

                        // 2. Lọc ra các bình luận Cha (parent_id = 0)
                        foreach ($all_comments as $cmt):
                            if ($cmt['parent_id'] == 0):
                                ?>
                                <div class="comment-item">
                                    <img src="uploads/<?php echo $cmt['avatar']; ?>" class="cmt-avatar-main"
                                        onerror="this.src='https://ui-avatars.com/api/?name=<?php echo $cmt['Username']; ?>'">
                                    <div style="flex-grow:1;">
                                        <div class="cmt-bubble">
                                            <span class="cmt-author">
                                                <?php echo $cmt['Username']; ?>
                                                <span class="cmt-time"><?php echo date('d/m H:i', strtotime($cmt['Ngaytao'])); ?></span>
                                            </span>
                                            <p style="margin:4px 0 0;"><?php echo $cmt['Noidung']; ?></p>
                                        </div>

                                        <div style="margin-top:2px;">
                                            <?php if (isset($_SESSION['emailUser'])): ?>
                                                <span class="btn-reply-text"
                                                    onclick="toggleReplyForm(<?php echo $cmt['Mabinhluan']; ?>)">Trả lời</span>
                                            <?php endif; ?>

                                            <?php
                                            // Kiểm tra: Nếu là Admin HOẶC là chủ bình luận thì hiện nút Xóa
                                            if (isset($_SESSION['username']) && ($_SESSION['role'] == 1 || $_SESSION['username'] == $cmt['Username'])):
                                                ?>
                                                <a href="danhmuc_baiviet.php?id=<?php echo $machude; ?>&mbl=<?php echo $cmt['Mabinhluan']; ?>&act=del"
                                                    class="btn-text-action btn-delete"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (isset($_SESSION['emailUser'])): ?>
                                            <div class="reply-form-container" id="reply-form-<?php echo $cmt['Mabinhluan']; ?>">
                                                <form action="" method="POST" class="comment-form" style="margin-top:5px;">
                                                    <input type="hidden" name="mabaiviet_post" value="<?php echo $id_baiviet; ?>">
                                                    <input type="hidden" name="parent_id" value="<?php echo $cmt['Mabinhluan']; ?>"> <img
                                                        src="uploads/<?php echo $_SESSION['avatar'] ?? ''; ?>" class="cmt-avatar-sub"
                                                        onerror="this.src='https://ui-avatars.com/api/?name=User'">
                                                    <input type="text" name="noidung_bl" class="comment-input"
                                                        placeholder="Phản hồi <?php echo $cmt['Username']; ?>..." required
                                                        autocomplete="off" style="font-size:0.85rem; padding:6px 10px;">
                                                    <button type="submit" name="btn_gui_binhluan" class="btn-send"
                                                        style="width:28px; height:28px;"><i class="fa-solid fa-paper-plane"
                                                            style="font-size:0.8rem"></i></button>
                                                </form>
                                            </div>
                                        <?php endif; ?>

                                        <div class="reply-list">
                                            <?php
                                            // Lặp lại mảng để tìm các bình luận con của bình luận Cha ($cmt)
                                            foreach ($all_comments as $reply):
                                                if ($reply['parent_id'] == $cmt['Mabinhluan']):
                                                    ?>
                                                    <div class="reply-item">
                                                        <img src="uploads/<?php echo $reply['avatar']; ?>" class="cmt-avatar-sub"
                                                            onerror="this.src='https://ui-avatars.com/api/?name=<?php echo $reply['Username']; ?>'">

                                                        <div style="flex-grow:1;">
                                                            <div class="cmt-bubble" style="background:#f1f5f9;">
                                                                <span class="cmt-author">
                                                                    <?php echo $reply['Username']; ?>
                                                                    <span
                                                                        class="cmt-time"><?php echo date('d/m H:i', strtotime($reply['Ngaytao'])); ?></span>
                                                                </span>
                                                                <p style="margin:4px 0 0;"><?php echo $reply['Noidung']; ?></p>
                                                            </div>

                                                            <div style="margin-top:2px;">
                                                                <?php if (isset($_SESSION['emailUser'])): ?>
                                                                    <span class="btn-reply-text" onclick="
                                                                        // 1. Mở form của BÌNH LUẬN CHA
                                                                        toggleReplyForm(<?php echo $cmt['Mabinhluan']; ?>); 
                                                                        
                                                                        // 2. Tìm ô input trong form đó
                                                                        var formInput = document.querySelector('#reply-form-<?php echo $cmt['Mabinhluan']; ?> input[name=\'noidung_bl\']');
                                                                        
                                                                        // 3. Xóa @Cu nếu có và thêm @Mới
                                                                        var currentVal = formInput.value;
                                                                        // Regex đơn giản để xóa tag cũ nếu người dùng đổi ý click reply người khác
                                                                        if(currentVal.startsWith('@')) {
                                                                            currentVal = currentVal.substring(currentVal.indexOf(' ') + 1);
                                                                        }
                                                                        
                                                                        // 4. Điền @TênNgườiĐượcTrảLời
                                                                        formInput.value = '@<?php echo $reply['Username']; ?> ' + currentVal;
                                                                        formInput.focus();
                                                                    ">
                                                                        Trả lời
                                                                    </span>
                                                                <?php endif; ?>

                                                                <?php
                                                                // Kiểm tra quyền xóa: Admin HOẶC Chính chủ bình luận con này
                                                                if (isset($_SESSION['username']) && ($_SESSION['role'] == 1 || $_SESSION['username'] == $reply['Username'])):
                                                                    ?>
                                                                    <a href="danhmuc_baiviet.php?id=<?php echo $machude; ?>&mbl=<?php echo $reply['Mabinhluan']; ?>&act=del"
                                                                        class="btn-text-action btn-delete"
                                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa bình luận này?')">Xóa</a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                endif;
                                            endforeach;
                                            ?>
                                        </div>
                                    </div>
                                </div> <?php
                            endif; // End check Parent
                        endforeach; // End Loop
                        ?>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<div style='text-align:center; padding:30px; color:#64748b;'>Chưa có bài viết nào.</div>";
        }
        ?>
    </div>
</div>

<script>
    Fancybox.bind("[data-fancybox]", {});

    // Mở/Đóng khung bình luận tổng
    function toggleCommentBox(id) {
        var box = document.getElementById("cmt-box-" + id);
        box.style.display = (box.style.display === "none" || box.style.display === "") ? "block" : "none";
    }

    // Mở/Đóng form trả lời (reply)
    function toggleReplyForm(cmtId) {
        var form = document.getElementById("reply-form-" + cmtId);
        // Tắt tất cả form reply khác đang mở (để cho gọn)
        document.querySelectorAll('.reply-form-container').forEach(el => {
            if (el.id !== "reply-form-" + cmtId) el.style.display = 'none';
        });
        form.style.display = (form.style.display === "none" || form.style.display === "") ? "flex" : "none";
        // Focus vào ô input
        if (form.style.display === "flex") {
            form.querySelector('input').focus();
        }

    }

    // Tự động mở bài viết khi vừa comment xong
    const urlParams = new URLSearchParams(window.location.search);
    const openId = urlParams.get('open');
    if (openId) toggleCommentBox(openId);
</script>

<?php require("phancuoi.php"); ?>