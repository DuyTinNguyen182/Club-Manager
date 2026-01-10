<?php
require("phandau.php");

if (isset($_POST['btn_gui_binhluan']) && isset($_SESSION['emailUser'])) {
    $mabaiviet = isset($_POST['mabaiviet_post']) ? intval($_POST['mabaiviet_post']) : 0;
    $noidung_bl = isset($_POST['noidung_bl']) ? trim($_POST['noidung_bl']) : '';
    $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : 0;
    $username = $_SESSION['username'];

    if (empty($noidung_bl)) {
        echo "<script>alert('Nội dung bình luận không được để trống!');</script>";
    } else {
        $sql_them = "INSERT INTO tblbinhluan(noi_dung, ma_bai_viet, ngay_tao, username, trang_thai, ma_binh_luan_cha) 
                     VALUES(?, ?, NOW(), ?, 1, ?)";
        $stmt = $conn->prepare($sql_them);
        $stmt->bind_param("sisi", $noidung_bl, $mabaiviet, $username, $parent_id);

        if ($stmt->execute()) {
            $cur_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            echo "<script>window.location.href='danhmuc_baiviet.php?id=$cur_id&open=$mabaiviet#post-$mabaiviet';</script>";
        } else {
            echo "<script>alert('Lỗi khi gửi bình luận: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

if (isset($_POST['btn_dang_bai']) && isset($_SESSION['username'])) {
    $machude_post = isset($_POST['machude_post']) ? intval($_POST['machude_post']) : 0;
    $noidung_bai = isset($_POST['noidung_bai']) ? trim($_POST['noidung_bai']) : '';
    $username = $_SESSION['username'];
    $ten_teptin = "";
    $upload_ok = true;
    $error_msg = "";

    if (empty($noidung_bai)) {
        $upload_ok = false;
        $error_msg = "Vui lòng nhập nội dung bài viết!";
    }

    if ($upload_ok && isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        $fileName = $_FILES['hinh_anh']['name'];
        $fileSize = $_FILES['hinh_anh']['size'];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $checkImage = getimagesize($_FILES['hinh_anh']['tmp_name']);

        if ($checkImage === false) {
            $upload_ok = false;
            $error_msg = "File tải lên không phải là ảnh hợp lệ.";
        } elseif (!in_array($fileType, $allowed)) {
            $upload_ok = false;
            $error_msg = "Chỉ chấp nhận file ảnh (JPG, JPEG, PNG, GIF).";
        } elseif ($fileSize > 5000000) {
            $upload_ok = false;
            $error_msg = "File ảnh quá lớn (Tối đa 5MB).";
        } else {
            $ten_teptin = time() . "_" . $username . "." . $fileType;
            $duong_dan_upload = "uploads/" . $ten_teptin;
            if (!move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $duong_dan_upload)) {
                $upload_ok = false;
                $error_msg = "Lỗi khi lưu file lên server.";
            }
        }
    }

    if ($upload_ok) {
        $sql_dangbai = "INSERT INTO tblbaiviet(noi_dung, ma_chu_de, ngay_tao, username, trang_thai, tep_tin) 
                        VALUES(?, ?, NOW(), ?, 1, ?)";
        $stmt = $conn->prepare($sql_dangbai);
        $stmt->bind_param("siss", $noidung_bai, $machude_post, $username, $ten_teptin);

        if ($stmt->execute()) {
            echo "<script>alert('Đăng bài thành công!'); window.location.href='danhmuc_baiviet.php?id=$machude_post';</script>";
        } else {
            echo "<script>alert('Lỗi hệ thống: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('$error_msg');</script>";
    }
}

if (isset($_GET['mbl']) && isset($_GET['act']) && $_GET['act'] == 'del' && isset($_SESSION['username'])) {
    $mbl = intval($_GET['mbl']);
    $currentUser = $_SESSION['username'];
    $userRole = isset($_SESSION['role']) ? intval($_SESSION['role']) : 0;

    $checkSql = "SELECT username FROM tblbinhluan WHERE ma_binh_luan = ?";
    $stmt_check = $conn->prepare($checkSql);
    $stmt_check->bind_param("i", $mbl);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $row = $result_check->fetch_assoc();
        $commentOwner = $row['username'];

        if ($userRole == 1 || $currentUser == $commentOwner) {
            $sql_del = "DELETE FROM tblbinhluan WHERE ma_binh_luan = ? OR ma_binh_luan_cha = ?";
            $stmt_del = $conn->prepare($sql_del);
            $stmt_del->bind_param("ii", $mbl, $mbl);

            if ($stmt_del->execute()) {
                $cur_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                echo "<script>window.location.href='danhmuc_baiviet.php?id=$cur_id';</script>";
            } else {
                echo "<script>alert('Lỗi khi xóa dữ liệu!');</script>";
            }
            $stmt_del->close();
        } else {
            echo "<script>alert('Bạn không có quyền xóa bình luận này!');</script>";
        }
    }
    $stmt_check->close();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $machude = intval($_GET['id']);
    $stmt_chude = $conn->prepare("SELECT ten_chu_de FROM tblchude WHERE ma_chu_de = ?");
    $stmt_chude->bind_param("i", $machude);
    $stmt_chude->execute();
    $result_chude = $stmt_chude->get_result();

    if ($result_chude->num_rows > 0) {
        $ten_chu_de = htmlspecialchars($result_chude->fetch_assoc()['ten_chu_de']);
    } else {
        header("Location: index.php");
        exit();
    }
    $stmt_chude->close();
} else {
    header("Location: index.php");
    exit();
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

<style>
    .feed-container { max-width: 800px; margin: 0 auto; padding: 20px 0; }
    .feed-item { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 25px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); }
    .feed-header { display: flex; align-items: center; gap: 12px; margin-bottom: 15px; }
    .feed-avatar { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; border: 1px solid #ddd; }
    .feed-content { font-size: 1rem; color: #1e293b; line-height: 1.6; margin-bottom: 15px; }
    .feed-image-container { margin-top: 10px; margin-bottom: 15px; border-radius: 8px; overflow: hidden; border: 1px solid #f1f5f9; }
    .feed-image { width: 100%; max-height: 500px; object-fit: cover; display: block; cursor: zoom-in; }
    .action-bar { border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; padding: 5px 0; display: flex; justify-content: center; margin-bottom: 15px; }
    .btn-action { background: none; border: none; padding: 8px 0; width: 100%; cursor: pointer; color: #64748b; font-weight: 600; font-size: 0.95rem; border-radius: 5px; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-action:hover { background-color: #f1f5f9; color: #0d6efd; }
    .comment-section { background: #f8fafc; border-radius: 8px; padding: 15px; display: none; }
    .comment-item { display: flex; gap: 10px; margin-bottom: 15px; }
    .cmt-avatar-main { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
    .reply-list { margin-left: 42px; border-left: 2px solid #e2e8f0; padding-left: 10px; }
    .reply-item { display: flex; gap: 10px; margin-bottom: 10px; margin-top: 10px; }
    .cmt-avatar-sub { width: 24px; height: 24px; border-radius: 50%; object-fit: cover; }
    .cmt-bubble { background: #e2e8f0; padding: 8px 12px; border-radius: 12px; font-size: 0.9rem; flex-grow: 1; position: relative; }
    .cmt-author { font-weight: 700; font-size: 0.85rem; display: block; }
    .cmt-time { font-size: 0.75rem; color: #64748b; font-weight: normal; margin-left: 5px; }
    .btn-reply-text { font-size: 0.8rem; font-weight: 600; color: #006affff; cursor: pointer; margin-left: 5px; text-decoration: none; }
    .btn-reply-text:hover { color: #0d6efd; text-decoration: underline; }
    .comment-form { display: flex; gap: 10px; margin-top: 10px; }
    .comment-input { flex-grow: 1; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 20px; outline: none; font-size: 0.9rem; }
    .btn-send { background: #0d6efd; color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .btn-text-action { font-size: 0.8rem; font-weight: 600; cursor: pointer; margin-left: 5px; text-decoration: none; color: red; }
    .reply-form-container { display: none; margin-left: 42px; margin-top: 5px; }
</style>

<div class="content-section" style="background-color: #f1f5f9; min-height: 100vh;">
    <div class="container feed-container">

        <?php if (isset($_SESSION['username'])): ?>
            <div class="feed-item" style="border: 2px dashed #cbd5e1; background: #f8fafc;">
                <div class="feed-header" style="margin-bottom: 10px;">
                    <img src="uploads/<?php echo htmlspecialchars($_SESSION['avatar'] ?? ''); ?>" class="feed-avatar"
                        onerror="this.src='https://ui-avatars.com/api/?name=<?php echo htmlspecialchars($_SESSION['username']); ?>'">
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

        <div style="background:#fff; padding:15px; border-radius:10px; margin-bottom:20px; border-left: 5px solid #0d6efd;">
            <h2 style="margin:0; font-size:1.5rem; color:#334155;">
                <i class="fa-solid fa-layer-group"></i> Chủ đề: <?php echo $ten_chu_de; ?>
            </h2>
        </div>

        <?php
        $sql_bv = "SELECT bv.*, u.anh_dai_dien, u.ho_va_ten FROM tblbaiviet bv JOIN tbluser u ON bv.username = u.username 
                   WHERE bv.ma_chu_de = ? AND bv.trang_thai = 1 ORDER BY bv.ngay_tao DESC";
        $stmt_bv = $conn->prepare($sql_bv);
        $stmt_bv->bind_param("i", $machude);
        $stmt_bv->execute();
        $rs_bv = $stmt_bv->get_result();

        if ($rs_bv->num_rows > 0) {
            while ($bv = $rs_bv->fetch_assoc()) {
                $id_baiviet = $bv['ma_bai_viet'];

                $sql_count = "SELECT COUNT(*) as total FROM tblbinhluan WHERE ma_bai_viet = ? AND trang_thai = 1";
                $stmt_cnt = $conn->prepare($sql_count);
                $stmt_cnt->bind_param("i", $id_baiviet);
                $stmt_cnt->execute();
                $total_cmt = $stmt_cnt->get_result()->fetch_assoc()['total'];
                $stmt_cnt->close();
                ?>
                <div class="feed-item" id="post-<?php echo $id_baiviet; ?>">
                    <div class="feed-header">
                        <img src="uploads/<?php echo htmlspecialchars($bv['anh_dai_dien']); ?>" class="feed-avatar"
                            onerror="this.src='https://ui-avatars.com/api/?name=<?php echo htmlspecialchars($bv['username']); ?>'">
                        <div>
                            <h4 style="margin:0; font-size:1rem;">
                                <?php echo htmlspecialchars(!empty($bv['ho_va_ten']) ? $bv['ho_va_ten'] : $bv['username']); ?>
                            </h4>
                            <span style="font-size:0.8rem; color:#64748b"><?php echo date('H:i d/m/Y', strtotime($bv['ngay_tao'])); ?></span>
                        </div>
                    </div>

                    <div class="feed-content"><?php echo nl2br(htmlspecialchars($bv['noi_dung'])); ?></div>
                    <?php if (!empty($bv['tep_tin'])): ?>
                        <div class="feed-image-container">
                            <a href="uploads/<?php echo htmlspecialchars($bv['tep_tin']); ?>" data-fancybox="gallery-<?php echo $id_baiviet; ?>">
                                <img src="uploads/<?php echo htmlspecialchars($bv['tep_tin']); ?>" class="feed-image">
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
                                <input type="hidden" name="parent_id" value="0">
                                <img src="uploads/<?php echo htmlspecialchars($_SESSION['avatar'] ?? ''); ?>" class="cmt-avatar-main"
                                    onerror="this.src='https://ui-avatars.com/api/?name=User'">
                                <input type="text" name="noidung_bl" class="comment-input" placeholder="Viết bình luận công khai..."
                                    required autocomplete="off">
                                <button type="submit" name="btn_gui_binhluan" class="btn-send"><i class="fa-solid fa-paper-plane"></i></button>
                            </form>
                        <?php endif; ?>

                        <?php
                        $all_comments = [];
                        $sql_bl = "SELECT bl.*, u.anh_dai_dien, u.ho_va_ten FROM tblbinhluan bl JOIN tbluser u ON bl.username = u.username 
                                   WHERE bl.ma_bai_viet = ? AND bl.trang_thai = 1 ORDER BY bl.ngay_tao ASC";
                        $stmt_bl = $conn->prepare($sql_bl);
                        $stmt_bl->bind_param("i", $id_baiviet);
                        $stmt_bl->execute();
                        $rs_bl = $stmt_bl->get_result();

                        while ($row_bl = $rs_bl->fetch_assoc()) {
                            $all_comments[] = $row_bl;
                        }
                        $stmt_bl->close();

                        foreach ($all_comments as $cmt):
                            if ($cmt['ma_binh_luan_cha'] == 0):
                                ?>
                                <div class="comment-item">
                                    <img src="uploads/<?php echo htmlspecialchars($cmt['anh_dai_dien']); ?>" class="cmt-avatar-main"
                                        onerror="this.src='https://ui-avatars.com/api/?name=<?php echo htmlspecialchars($cmt['username']); ?>'">
                                    <div style="flex-grow:1;">
                                        <div class="cmt-bubble">
                                            <span class="cmt-author">
                                                <?php echo htmlspecialchars(!empty($cmt['ho_va_ten']) ? $cmt['ho_va_ten'] : $cmt['username']); ?>
                                                <span class="cmt-time"><?php echo date('H:i d/m/Y', strtotime($cmt['ngay_tao'])); ?></span>
                                            </span>
                                            <p style="margin:4px 0 0;"><?php echo nl2br(htmlspecialchars($cmt['noi_dung'])); ?></p>
                                        </div>

                                        <div style="margin-top:2px;">
                                            <?php if (isset($_SESSION['emailUser'])): ?>
                                                <span class="btn-reply-text" onclick="toggleReplyForm(<?php echo $cmt['ma_binh_luan']; ?>)">Trả lời</span>
                                            <?php endif; ?>

                                            <?php if (isset($_SESSION['username']) && ($_SESSION['role'] == 1 || $_SESSION['username'] == $cmt['username'])): ?>
                                                <a href="danhmuc_baiviet.php?id=<?php echo $machude; ?>&mbl=<?php echo $cmt['ma_binh_luan']; ?>&act=del"
                                                    class="btn-text-action btn-delete"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (isset($_SESSION['emailUser'])): ?>
                                            <div class="reply-form-container" id="reply-form-<?php echo $cmt['ma_binh_luan']; ?>">
                                                <form action="" method="POST" class="comment-form" style="margin-top:5px;">
                                                    <input type="hidden" name="mabaiviet_post" value="<?php echo $id_baiviet; ?>">
                                                    <input type="hidden" name="parent_id" value="<?php echo $cmt['ma_binh_luan']; ?>">
                                                    <img src="uploads/<?php echo htmlspecialchars($_SESSION['avatar'] ?? ''); ?>" class="cmt-avatar-sub"
                                                        onerror="this.src='https://ui-avatars.com/api/?name=User'">
                                                    <input type="text" name="noidung_bl" class="comment-input"
                                                        placeholder="Phản hồi <?php echo htmlspecialchars($cmt['username']); ?>..." required
                                                        autocomplete="off" style="font-size:0.85rem; padding:6px 10px;">
                                                    <button type="submit" name="btn_gui_binhluan" class="btn-send"
                                                        style="width:28px; height:28px;"><i class="fa-solid fa-paper-plane"
                                                            style="font-size:0.8rem"></i></button>
                                                </form>
                                            </div>
                                        <?php endif; ?>

                                        <div class="reply-list">
                                            <?php
                                            foreach ($all_comments as $reply):
                                                if ($reply['ma_binh_luan_cha'] == $cmt['ma_binh_luan']):
                                                    ?>
                                                    <div class="reply-item">
                                                        <img src="uploads/<?php echo htmlspecialchars($reply['anh_dai_dien']); ?>" class="cmt-avatar-sub"
                                                            onerror="this.src='https://ui-avatars.com/api/?name=<?php echo htmlspecialchars($reply['username']); ?>'">
                                                        <div style="flex-grow:1;">
                                                            <div class="cmt-bubble" style="background:#f1f5f9;">
                                                                <span class="cmt-author">
                                                                    <?php echo htmlspecialchars(!empty($reply['ho_va_ten']) ? $reply['ho_va_ten'] : $reply['username']); ?>
                                                                    <span class="cmt-time"><?php echo date('H:i d/m/Y', strtotime($reply['ngay_tao'])); ?></span>
                                                                </span>
                                                                <p style="margin:4px 0 0;"><?php echo nl2br(htmlspecialchars($reply['noi_dung'])); ?></p>
                                                            </div>
                                                            <div style="margin-top:2px;">
                                                                <?php if (isset($_SESSION['emailUser'])): ?>
                                                                    <span class="btn-reply-text" onclick="toggleReplyForm(<?php echo $cmt['ma_binh_luan']; ?>); var formInput = document.querySelector('#reply-form-<?php echo $cmt['ma_binh_luan']; ?> input[name=\'noidung_bl\']'); var currentVal = formInput.value; if(currentVal.startsWith('@')) { currentVal = currentVal.substring(currentVal.indexOf(' ') + 1); } formInput.value = '@<?php echo htmlspecialchars($reply['username']); ?> ' + currentVal; formInput.focus();">Trả lời</span>
                                                                <?php endif; ?>
                                                                <?php if (isset($_SESSION['username']) && ($_SESSION['role'] == 1 || $_SESSION['username'] == $reply['username'])): ?>
                                                                    <a href="danhmuc_baiviet.php?id=<?php echo $machude; ?>&mbl=<?php echo $reply['ma_binh_luan']; ?>&act=del"
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
                                </div>
                            <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<div style='text-align:center; padding:30px; color:#64748b;'>Chưa có bài viết nào.</div>";
        }
        $stmt_bv->close();
        ?>
    </div>
</div>

<script>
    Fancybox.bind("[data-fancybox]", {});

    function toggleCommentBox(id) {
        var box = document.getElementById("cmt-box-" + id);
        box.style.display = (box.style.display === "none" || box.style.display === "") ? "block" : "none";
    }

    function toggleReplyForm(cmtId) {
        var form = document.getElementById("reply-form-" + cmtId);
        document.querySelectorAll('.reply-form-container').forEach(el => {
            if (el.id !== "reply-form-" + cmtId) el.style.display = 'none';
        });
        form.style.display = (form.style.display === "none" || form.style.display === "") ? "flex" : "none";
        if (form.style.display === "flex") {
            form.querySelector('input').focus();
        }
    }

    const urlParams = new URLSearchParams(window.location.search);
    const openId = urlParams.get('open');
    if (openId) toggleCommentBox(openId);
</script>

<?php require("phancuoi.php"); ?>