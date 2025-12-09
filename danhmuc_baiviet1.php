<?php
require("phandau.php");

// --- PHẦN 1: XỬ LÝ LOGIC (Gửi bình luận, Ẩn bình luận) ---

// 1.1. Xử lý gửi bình luận mới
if (isset($_POST['btn_gui_binhluan']) && isset($_SESSION['emailUser'])) {
    $mabaiviet = $_POST['mabaiviet_post'];
    $noidung_bl = $_POST['noidung_bl'];
    $username = $_SESSION['username']; // Lấy username từ session

    // Chống SQL Injection cơ bản
    $noidung_bl = str_replace("'", "\'", $noidung_bl);

    if (!empty($noidung_bl)) {
        $sql_them_bl = "INSERT INTO tblbinhluan(Noidung, Mabaiviet, Ngaytao, Username, Trangthai) 
                            VALUES('$noidung_bl', '$mabaiviet', NOW(), '$username', 0)";
        if ($conn->query($sql_them_bl)) {
            // Refresh lại trang để hiện bình luận mới (giữ nguyên ID chủ đề)
            $cur_id = $_GET['id'];
            echo "<script>window.location.href='danhmuc_baiviet.php?id=$cur_id';</script>";
        }
    }
}

// 1.2. Xử lý ẩn bình luận (Dành cho Admin)
if (isset($_GET['mbl']) && isset($_GET['act']) && $_GET['act'] == 'hide') {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 1) { // Kiểm tra quyền Admin
        $mbl = $_GET['mbl'];
        $sql_an = "UPDATE tblbinhluan SET Trangthai = 1 WHERE Mabinhluan = '$mbl'";
        $conn->query($sql_an);

        // Quay về trang cũ
        $cur_id = $_GET['id'];
        echo "<script>window.location.href='danhmuc_baiviet.php?id=$cur_id';</script>";
    }
}

// --- PHẦN 2: LẤY DỮ LIỆU CHỦ ĐỀ ---

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $machude = intval($_GET['id']);

    // Lấy tên chủ đề
    $sql_chude = "SELECT Tenchude FROM tblchude WHERE Machude = $machude";
    $rs_chude = $conn->query($sql_chude);
    if ($rs_chude->num_rows > 0) {
        $row_chude = $rs_chude->fetch_assoc();
        $ten_chu_de = $row_chude['Tenchude'];
    } else {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<style>
    /* CSS cho khung bài viết */
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
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .feed-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #e0f2fe;
    }

    .feed-info h4 {
        margin: 0;
        font-size: 1rem;
        color: #334155;
        font-weight: 700;
    }

    .feed-time {
        font-size: 0.8rem;
        color: #64748b;
    }

    .feed-content {
        font-size: 1rem;
        color: #1e293b;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    /* CSS cho phần Bình luận */
    .comment-section {
        background: #f8fafc;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }

    .comment-list {
        max-height: 300px;
        overflow-y: auto;
        /* Cho phép cuộn nếu quá nhiều comment */
        margin-bottom: 15px;
    }

    .comment-item {
        display: flex;
        gap: 10px;
        margin-bottom: 12px;
    }

    .cmt-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }

    .cmt-bubble {
        background: #e2e8f0;
        padding: 8px 12px;
        border-radius: 12px;
        border-top-left-radius: 2px;
        font-size: 0.9rem;
        flex-grow: 1;
    }

    .cmt-author {
        font-weight: 700;
        font-size: 0.85rem;
        color: #0f172a;
        display: block;
    }

    .cmt-time {
        font-size: 0.75rem;
        color: #64748b;
        margin-left: 5px;
        font-weight: normal;
    }

    .cmt-text {
        margin: 4px 0 0 0;
        color: #334155;
    }

    .btn-hide-cmt {
        font-size: 0.75rem;
        color: #ef4444;
        margin-left: 10px;
        text-decoration: none;
    }

    /* Form nhập bình luận */
    .comment-form {
        display: flex;
        gap: 10px;
    }

    .comment-input {
        flex-grow: 1;
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 20px;
        font-size: 0.9rem;
        outline: none;
    }

    .comment-input:focus {
        border-color: #0d6efd;
    }

    .btn-send {
        background: #0d6efd;
        color: white;
        border: none;
        padding: 0 15px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn-send:hover {
        background: #0b5ed7;
    }
</style>

<div class="content-section" style="background-color: #f1f5f9; min-height: 100vh;">
    <div class="container feed-container">

        <div style="background:#fff; padding:15px; border-radius:10px; margin-bottom:20px; border-left: 5px solid #0d6efd;">
            <h2 style="margin:0; font-size:1.5rem; color:#334155;">
                <i class="fa-solid fa-layer-group"></i> Chủ đề: <?php echo $ten_chu_de; ?>
            </h2>
        </div>

        <?php
        // Lấy bài viết thuộc chủ đề này, kèm thông tin người đăng (avatar)
        $sql_bv = "SELECT bv.*, u.avatar 
                   FROM tblbaiviet bv 
                   JOIN tbluser u ON bv.Username = u.Username 
                   WHERE bv.Machude = $machude AND bv.Trangthai = 0 
                   ORDER BY bv.Ngaytao DESC";
        $rs_bv = $conn->query($sql_bv);

        if ($rs_bv->num_rows > 0) {
            while ($bv = $rs_bv->fetch_assoc()) {
                $id_baiviet = $bv['Mabaiviet'];
        ?>
                <div class="feed-item">

                    <div class="feed-header">
                        <img src="uploads/<?php echo $bv['avatar']; ?>" class="feed-avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo $bv['Username']; ?>'">
                        <div class="feed-info">
                            <h4><?php echo $bv['Username']; ?></h4>
                            <span class="feed-time"><i class="fa-regular fa-clock"></i> <?php echo date('H:i d/m/Y', strtotime($bv['Ngaytao'])); ?></span>
                        </div>
                    </div>

                    <div class="feed-content">
                        <?php echo nl2br($bv['Noidung']); ?>
                    </div>

                    <div class="comment-section">

                        <div class="comment-list">
                            <?php
                            // Lấy bình luận CỦA BÀI VIẾT NÀY
                            $sql_bl = "SELECT bl.*, u.avatar 
                                   FROM tblbinhluan bl 
                                   JOIN tbluser u ON bl.Username = u.Username 
                                   WHERE bl.Mabaiviet = $id_baiviet AND bl.Trangthai = 0 
                                   ORDER BY bl.Ngaytao ASC";
                            $rs_bl = $conn->query($sql_bl);

                            if ($rs_bl->num_rows > 0) {
                                while ($bl = $rs_bl->fetch_assoc()) {
                            ?>
                                    <div class="comment-item">
                                        <img src="uploads/<?php echo $bl['avatar']; ?>" class="cmt-avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo $bl['Username']; ?>'">
                                        <div class="cmt-bubble">
                                            <span class="cmt-author">
                                                <?php echo $bl['Username']; ?>
                                                <span class="cmt-time"><?php echo date('d/m H:i', strtotime($bl['Ngaytao'])); ?></span>

                                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
                                                    <a href="danhmuc_baiviet.php?id=<?php echo $machude; ?>&mbl=<?php echo $bl['Mabinhluan']; ?>&act=hide"
                                                        class="btn-hide-cmt" onclick="return confirm('Ẩn bình luận này?')">
                                                        (Ẩn)
                                                    </a>
                                                <?php endif; ?>
                                            </span>
                                            <p class="cmt-text"><?php echo $bl['Noidung']; ?></p>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "<p style='font-size:0.85rem; color:#94a3b8; font-style:italic;'>Chưa có bình luận nào.</p>";
                            }
                            ?>
                        </div>

                        <?php if (isset($_SESSION['emailUser'])): ?>
                            <form action="" method="POST" class="comment-form">
                                <input type="hidden" name="mabaiviet_post" value="<?php echo $id_baiviet; ?>">
                                <img src="uploads/<?php echo $_SESSION['avatar'] ?? ''; ?>" class="cmt-avatar" style="border:1px solid #ccc" onerror="this.src='https://ui-avatars.com/api/?name=User'">
                                <input type="text" name="noidung_bl" class="comment-input" placeholder="Viết bình luận của bạn..." required autocomplete="off">
                                <button type="submit" name="btn_gui_binhluan" class="btn-send"><i class="fa-solid fa-paper-plane"></i></button>
                            </form>
                        <?php else: ?>
                            <div style="text-align:center; font-size:0.9rem;">
                                <a href="login.php" style="color:#0d6efd;">Đăng nhập</a> để bình luận.
                            </div>
                        <?php endif; ?>

                    </div>
                </div> <?php
                    } // End while posts
                } else {
                    echo "<div style='text-align:center; padding:30px; color:#64748b;'>Chưa có bài viết nào trong chủ đề này.</div>";
                }
                        ?>

    </div>
</div>

<?php
require("phancuoi.php");
?>